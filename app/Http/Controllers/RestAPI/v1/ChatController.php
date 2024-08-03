<?php

namespace App\Http\Controllers\RestAPI\v1;

use App\Events\ChattingEvent;
use App\Http\Controllers\Controller;
use App\Models\Chatting;
use App\Models\DeliveryMan;
use App\Models\Seller;
use App\Models\Shop;
use App\User;
use App\Utils\Helpers;
use App\Utils\ImageManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ChatController extends Controller
{
    public function list(Request $request, $type)
    {
        $admin_size = 0;
        $admin_chat_id = [];
        if ($type == 'delivery-man') {
            $id_param = 'delivery_man_id';
            $with = 'deliveryMan';
        } elseif ($type == 'seller') {
            $id_param = 'seller_id';
            $with = 'sellerInfo.shops';

            $admin = $this->getAdminChatList($request);
            $admin_size = $admin['admin_size'];
            $admin_chat_id = $admin['admin_chat_id'];
        } else {
            return response()->json(['message' => 'Invalid Chatting Type!'], 403);
        }

        $total_size = Chatting::where(['user_id' => $request->user()->id])
            ->whereNotNull($id_param)
            ->select($id_param)
            ->distinct()
            ->get()
            ->count()+$admin_size;

        $all_chat_ids = Chatting::where(['user_id' => $request->user()->id])
            ->whereNotNull($id_param)
            ->select($id_param)
            ->latest()
            ->get()
            ->unique($id_param)
            ->toArray();

        $unique_chat_ids = array_slice(array_values($all_chat_ids), $request->offset-1, $request->limit);

        $chats = array();
        if($type == 'seller' && $admin_chat_id){
            $user_chatting = Chatting::with([$with])
                ->where(['user_id' => $request->user()->id, 'admin_id' => '0'])
                ->whereNotNull('admin_id')
                ->latest()
                ->first();

            $user_chatting->unseen_message_count = Chatting::where(['user_id'=>$user_chatting->user_id, 'admin_id'=>$user_chatting->admin_id, 'seen_by_customer'=>'0'])->count();
            $chats[] = $user_chatting;
        }

        if ($unique_chat_ids) {
            foreach ($unique_chat_ids as $unique_chat_id) {
                $user_chatting = Chatting::with([$with])
                    ->where(['user_id' => $request->user()->id, $id_param => $unique_chat_id[$id_param]])
                    ->whereNotNull($id_param)
                    ->latest()
                    ->first();

                $user_chatting->unseen_message_count = Chatting::where(['user_id'=>$user_chatting->user_id, $id_param=>$user_chatting->$id_param, 'seen_by_customer'=>'0'])->count();
                $chats[] = $user_chatting;
            }
        }

        $data = array();
        $data['total_size'] = $total_size;
        $data['limit'] = $request->limit;
        $data['offset'] = $request->offset;
        $data['chat'] = $chats;

        return response()->json($data, 200);
    }

    private function getAdminChatList($request): array
    {
        $admin_size = Chatting::where(['user_id' => $request->user()->id])
                ->whereNotNull(['admin_id', 'user_id'])
                ->select(['admin_id','seller_id'])
                ->distinct()
                ->get()
                ->count();

        return [
            'admin_size' => $admin_size,
            'admin_chat_id' => $admin_size > 0 ? [['admin_id'=>0]] : [],
        ];
    }

    public function search(Request $request, $type)
    {
        $terms = explode(" ", $request->input('search'));
        if ($type == 'seller') {
            $id_param = 'seller_id';
            $with_param = 'sellerInfo.shops';
            $users = Shop::when($request->search, function ($query) use ($terms) {
                foreach ($terms as $term) {
                    $query->where('name', 'like', '%' . $term . '%');
                }
            })->pluck('seller_id')->toArray();

        } elseif ($type == 'delivery-man') {
            $with_param = 'deliveryMan';
            $id_param = 'delivery_man_id';
            $users = DeliveryMan::when($request->search, function ($query) use ($terms) {
                foreach ($terms as $term) {
                    $query->where('f_name', 'like', '%' . $term . '%')
                        ->orWhere('l_name', 'like', '%' . $term . '%');
                }
            })->pluck('id')->toArray();
        } else {
            return response()->json(['message' => translate('Invalid Chatting Type!')], 403);
        }

        $unique_chat_ids = Chatting::where(['user_id' => $request->user()->id])
            ->whereIn($id_param, $users)
            ->select($id_param)
            ->distinct()
            ->get()
            ->toArray();
        $unique_chat_ids = call_user_func_array('array_merge', $unique_chat_ids);

        $chats = array();
        if ($unique_chat_ids) {
            foreach ($unique_chat_ids as $unique_chat_id) {
                $user_chatting = Chatting::with([$with_param])
                    ->where(['user_id' => $request->user()->id, $id_param => $unique_chat_id])
                    ->whereNotNull($id_param)
                    ->latest()
                    ->first();

                $user_chatting->unseen_message_count = Chatting::where(['user_id'=>$user_chatting->user_id, $id_param=>$user_chatting->$id_param, 'seen_by_customer'=>'0'])->count();
                $chats[] = $user_chatting;
            }
        }

        return response()->json($chats, 200);
    }

    public function get_message(Request $request, $type, $id)
    {
        $validator = Validator::make($request->all(), [
            'offset' => 'required',
            'limit' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        if ($type == 'delivery-man') {
            $id_param = 'delivery_man_id';
            $sent_by = 'sent_by_delivery_man';
            $with = 'deliveryMan';
        } elseif ($type == 'seller') {
            $id_param = $id==0 ? 'admin_id' : 'seller_id';
            $sent_by = 'sent_by_seller';
            $with = 'sellerInfo.shops';

        } else {
            return response()->json(['message' => translate('Invalid Chatting Type!')], 403);
        }

        $query = Chatting::with($with)->where(['user_id' => $request->user()->id, $id_param => $id]);

        if (!empty($query->get())) {
            $message = $query->paginate($request->limit, ['*'], 'page', $request->offset);
            $message?->map(function ($conversation) {
                $conversation->attachment = $conversation->attachment ? json_decode($conversation->attachment) : [];
            });
            $query->where($sent_by, 1)->update(['seen_by_customer' => 1]);

            $data = array();
            $data['total_size'] = $message->total();
            $data['limit'] = $request->limit;
            $data['offset'] = $request->offset;
            $data['message'] = $message->items();
            return response()->json($data, 200);
        }
        return response()->json(['message' => translate('no messages found!')], 200);

    }

    public function send_message(Request $request, $type)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $image = [] ;
        if ($request->file('image')) {
            foreach ($request->image as $key=>$value) {
                $image_name = ImageManager::upload('chatting/', 'webp', $value);
                $image[] = $image_name;
            }
        }

        $chatting = new Chatting();
        $chatting->user_id = $request->user()->id;
        $chatting->message = $request->message;
        $chatting->attachment = json_encode($image);
        $chatting->sent_by_customer = 1;
        $chatting->seen_by_customer = 1;
        $message_form = User::find($request->user()->id);
        if ($type == 'seller') {
            $seller = Seller::with('shop')->find($request->id);
            $chatting->seller_id = $request->id == 0 ? null : $request->id;
            $chatting->admin_id = $request->id == 0 ? 0 : null;
            $chatting->shop_id = isset($seller->shop->id) ? $seller->shop->id : null;
            $chatting->seen_by_seller = 0;
            $chatting->notification_receiver = $request->id == 0 ? 'admin' : 'seller';

            if($request->id != 0){
                ChattingEvent::dispatch('message_from_customer', 'seller', $seller, $message_form);
            }
        } elseif ($type == 'delivery-man') {
            $chatting->delivery_man_id = $request->id;
            $chatting->seen_by_delivery_man = 0;
            $chatting->notification_receiver = 'deliveryman';

            $delivery_man = DeliveryMan::find($request->id);
            ChattingEvent::dispatch('message_from_customer', 'delivery_man', $delivery_man, $message_form);
        } else {
            return response()->json(translate('Invalid Chatting Type!'), 403);
        }

        if ($chatting->save()) {
            return response()->json(['message' => $request->message, 'time' => now(), 'image'=>$image], 200);
        } else {
            return response()->json(['message' => translate('Message sending failed')], 403);
        }
    }

    public function seen_message(Request $request, $type)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        if ($type == 'delivery-man') {
            $id_param = 'delivery_man_id';
        } elseif ($type == 'seller') {
            $id_param = $request->id==0 ? 'admin_id' : 'seller_id';
        } else {
            return response()->json(['message' => 'Invalid Chatting Type'], 403);
        }

        $chatting = Chatting::where(['user_id'=>$request->user()->id, $id_param=>$request->id])->update(['seen_by_customer' => 1]);

        if ($chatting) {
            return response()->json(['message' => 'Successfully seen'], 200);
        } else {
            return response()->json(['message' => 'Fail'], 403);
        }
    }
}
