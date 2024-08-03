<?php

namespace App\Http\Controllers\RestAPI\v2\delivery_man;

use App\Events\ChattingEvent;
use App\Http\Controllers\Controller;
use App\Models\Chatting;
use App\Models\Seller;
use App\User;
use App\Utils\Helpers;
use App\Utils\ImageManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ChatController extends Controller
{
    public function list(Request $request, $type)
    {

        $delivery_man = $request['delivery_man'];

        if ($type == 'customer') {
            $with_param = 'customer';
            $id_param = 'user_id';
        } elseif ($type == 'seller') {
            $with_param = 'sellerInfo.shops';
            $id_param = 'seller_id';
        } elseif ($type == 'admin') {
            $with_param = 'admin';
            $id_param = 'admin_id';
        } else {
            return response()->json(['message' => translate('Invalid Chatting Type!')], 403);
        }

        $total_size = Chatting::where(['delivery_man_id' => $delivery_man['id']])
            ->whereNotNull($id_param)
            ->select($id_param)
            ->distinct()
            ->get()
            ->count();

        $all_chat_ids = Chatting::where(['delivery_man_id' => $delivery_man['id']])
            ->whereNotNull($id_param)
            ->select('id',$id_param)
            ->latest()
            ->get()
            ->unique($id_param)
            ->toArray();

        $unique_chat_ids = array_slice($all_chat_ids, $request->offset-1, $request->limit);

        $chats = array();
        if($unique_chat_ids){
            foreach($unique_chat_ids as $unique_chat_id){
                $user_chatting = Chatting::with([$with_param])
                    ->where(['delivery_man_id' => $delivery_man['id'], $id_param=> $unique_chat_id[$id_param]])
                    ->whereNotNull($id_param)
                    ->latest()
                    ->first();

                $user_chatting->unseen_message_count = Chatting::where(['delivery_man_id'=>$user_chatting->delivery_man_id, $id_param=>$user_chatting->$id_param, 'seen_by_delivery_man'=>'0'])->count();
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

    public function search(Request $request, $type){
        $delivery_man = $request['delivery_man'];
        $terms = explode(" ", $request->input('search'));

        if ($type == 'customer') {
            $with_param = 'customer';
            $id_param = 'user_id';
            $users = User::where('id', '!=', 0)
                ->when($request->search, function ($query) use ($terms) {
                    foreach ($terms as $term) {
                        $query->where('f_name', 'like', '%' . $term . '%')
                            ->orWhere('l_name', 'like', '%' . $term . '%');
                    }
                })->pluck('id')->toArray();

        } elseif ($type == 'seller') {
            $id_param = 'seller_id';
            $with_param = 'sellerInfo.shops';
            $users = Seller::when($request->search, function ($query) use ($terms) {
                foreach ($terms as $term) {
                    $query->where('f_name', 'like', '%' . $term . '%')
                        ->orWhere('l_name', 'like', '%' . $term . '%');
                }
            })->pluck('id')->toArray();
        } else {
            return response()->json(['message' => translate('Invalid Chatting Type!')], 403);
        }

        $unique_chat_ids = Chatting::where(['delivery_man_id' => $delivery_man['id']])
            ->whereIn($id_param, $users)
            ->select($id_param)
            ->distinct()
            ->get()
            ->toArray();
        $unique_chat_ids = call_user_func_array('array_merge', $unique_chat_ids);

        $chats = array();
        if($unique_chat_ids){
            foreach($unique_chat_ids as $unique_chat_id){
                $chats[] = Chatting::with([$with_param])
                    ->where(['delivery_man_id' => $delivery_man['id'], $id_param=> $unique_chat_id])
                    ->whereNotNull($id_param)
                    ->latest()
                    ->first();
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

        $delivery_man = $request['delivery_man'];

        if ($type == 'customer') {
            $id_param = 'user_id';
            $sent_by = 'sent_by_customer';
            $with = 'customer';
        } elseif ($type == 'seller') {
            $id_param = 'seller_id';
            $sent_by = 'sent_by_seller';
            $with  = 'sellerInfo.shops';

        } elseif ($type == 'admin') {
            $id_param = 'admin_id';
            $sent_by = 'sent_by_admin';
            $with = 'admin';

        } else {
            return response()->json(['message' => translate('Invalid Chatting Type!')], 403);
        }

        $query = Chatting::with($with)->where(['delivery_man_id' => $delivery_man['id'], $id_param => $id])->latest();

        if (!empty($query->get())) {
            $message = $query->paginate($request->limit, ['*'], 'page', $request->offset);

            $message->map(function ($conversation) {
                $conversation->attachment = json_decode($conversation->attachment);
            });

            $query->where($sent_by, 1)->update(['seen_by_delivery_man' => 1]);

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

        $delivery_man = $request['delivery_man'];

        $image = [];
        if ($request->file('image')) {
            foreach ($request->image as $key=>$value) {
                $image_name = ImageManager::upload('chatting/', 'webp', $value);
                $image[] = $image_name;
            }
        }

        $chatting                       = new Chatting();
        $chatting->delivery_man_id      = $delivery_man->id;
        $chatting->message              = $request->message;
        $chatting->attachment           = json_encode($image);
        $chatting->sent_by_delivery_man = 1;
        $chatting->seen_by_delivery_man = 1;

        if ($type == 'seller') {
            $chatting->seller_id                = $request->id;
            $chatting->seen_by_seller           = 0;
            $chatting->notification_receiver    = 'seller';

            $seller = Seller::find($request->id);
            ChattingEvent::dispatch('message_from_delivery_man', 'seller', $seller, $delivery_man);
        } elseif ($type == 'customer') {
            $chatting->user_id                  = $request->id;
            $chatting->seen_by_customer         = 0;
            $chatting->notification_receiver    = 'customer';

            $customer = User::find($request->id);
            ChattingEvent::dispatch('message_from_delivery_man', 'customer', $customer, $delivery_man);
        } elseif ($type == 'admin') {
            $chatting->admin_id                 = 0;
            $chatting->seen_by_admin            = 0;
            $chatting->notification_receiver    = 'admin';
        } else {
            return response()->json(translate('Invalid Chatting Type!'), 403);
        }

        if ($chatting->save()) {
            return response()->json(['message' => $request->message, 'time' => now(), 'image' => $image], 200);
        } else {
            return response()->json(['message' => translate('Message sending failed')], 403);
        }
    }
}
