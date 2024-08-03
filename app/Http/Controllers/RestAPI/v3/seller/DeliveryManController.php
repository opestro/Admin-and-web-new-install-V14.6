<?php

namespace App\Http\Controllers\RestAPI\v3\seller;

use App\Http\Controllers\Controller;
use App\Models\DeliveryMan;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Models\Review;
use App\Traits\CommonTrait;
use App\Utils\Helpers;
use App\Utils\ImageManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DeliveryManController extends Controller
{
    use CommonTrait;

    private $shippingMethod;
    public function __construct()
    {
        $this->shippingMethod = Helpers::get_business_settings('shipping_method');
    }

    public function list(Request $request)
    {
        $seller = $request->seller;
        $delivery_men = DeliveryMan::with(['rating', 'orders'=>function($query){
                $query->select('delivery_man_id', DB::raw('COUNT(delivery_man_id) as count'));
            }])
            ->where(['seller_id' => $seller['id']])
            ->when(!empty($request['search']), function($query) use($request){
                $query->where(function($query) use ($request){
                    $query->where('f_name', 'like', "%{$request['search']}%")
                        ->orWhere('l_name', 'like', "%{$request['search']}%");
                });
            })
            ->paginate($request['limit'], ['*'], 'page', $request['offset']);

        $data = array();
        $data['total_size'] = $delivery_men->total();
        $data['limit'] = $request['limit'];
        $data['offset'] = $request['offset'];
        $data['delivery_man'] = $delivery_men->items();
        return response()->json($data, 200);
    }

    public function store(Request $request)
    {
        $seller = $request->seller;
        $validator = Validator::make($request->all(), [
            'f_name' => 'required',
            'l_name' => 'required',
            'phone' => 'required',
            'email' => 'required|unique:delivery_men,email',
            'country_code' => 'required',
            'password' => 'required|same:confirm_password|min:8'
        ],[
            'f_name.required' => 'First name is required!',
            'l_name.required' => 'Last name is required!'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $id_img_names = [];
        if (!empty($request->file('identity_image'))) {
            foreach ($request->identity_image as $img) {
                array_push($id_img_names, ImageManager::upload('delivery-man/', 'webp', $img));
            }
            $identity_image = json_encode($id_img_names);
        } else {
            $identity_image = json_encode([]);
        }

        $dm = new DeliveryMan();
        $dm->seller_id = $seller->id;
        $dm->f_name = $request->f_name;
        $dm->l_name = $request->l_name;
        $dm->address = $request->address;
        $dm->phone = $request->phone;
        $dm->email = $request->email;
        $dm->country_code = $request->country_code;
        $dm->identity_number = $request->identity_number;
        $dm->identity_type = $request->identity_type;
        $dm->identity_image = $identity_image;
        $dm->image = ImageManager::upload('delivery-man/', 'webp', $request->file('image'));
        $dm->password = bcrypt($request->password);
        $dm->save();

        return response()->json(['message' => translate('Delivery-man_added_successfully!')], 200);

    }

    public function update(Request $request, $id)
    {
        $seller = $request->seller;

        $validator = Validator::make($request->all(), [
            'f_name' => 'required',
            'l_name' => 'required',
            'email' => 'required|email|unique:delivery_men,email,' . $id,
            'phone' => 'required',
            'country_code' => 'required',
        ], [
            'f_name.required' => 'First name is required!',
            'l_name.required' => 'Last name is required!'
        ]);

        if ($request->password) {
            $validator->make($request->all(), [
                'password' => 'required|min:8|same:confirm_password'
            ]);
        }
        if (isset($delivery_man) && $request['email'] != $delivery_man['email']) {
            $validator->make($request->all(), [
                'email' => 'required|unique:delivery_men',
            ]);
        }

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $delivery_man = DeliveryMan::where(['id' => $id, 'seller_id' => $seller->id])->first();

        $phone_combo_exists = DeliveryMan::where(['phone' => $request->phone, 'country_code' => $request->country_code])->first();

        if (isset($phone_combo_exists) && $phone_combo_exists->id != $delivery_man->id) {
            return response()->json(['message' => translate('This_phone_number_is_already_taken')], 403);
        }

        if (!empty($request->file('identity_image'))) {
            foreach (json_decode($delivery_man['identity_image'], true) as $img) {
                if (Storage::disk('public')->exists('delivery-man/' . $img)) {
                    Storage::disk('public')->delete('delivery-man/' . $img);
                }
            }
            $img_keeper = [];
            foreach ($request->identity_image as $img) {
                array_push($img_keeper, ImageManager::upload('delivery-man/', 'webp', $img));
            }
            $identity_image = json_encode($img_keeper);
        } else {
            $identity_image = $delivery_man['identity_image'];
        }
        $delivery_man->seller_id = $seller->id;;
        $delivery_man->f_name = $request->f_name;
        $delivery_man->l_name = $request->l_name;
        $delivery_man->address = $request->address;
        $delivery_man->email = $request->email;
        $delivery_man->country_code = $request->country_code;
        $delivery_man->phone = $request->phone;
        $delivery_man->identity_number = $request->identity_number;
        $delivery_man->identity_type = $request->identity_type;
        $delivery_man->identity_image = $identity_image;
        $delivery_man->image = $request->has('image') ? ImageManager::update('delivery-man/', $delivery_man->image, 'webp', $request->file('image')) : $delivery_man->image;
        $delivery_man->password = strlen($request->password) > 1 ? bcrypt($request->password) : $delivery_man['password'];
        $delivery_man->save();

        return response()->json(['message' => translate('Delivery_man_updated_successfully!')], 200);
    }

    public function details(Request $request, $id){
        $seller = $request->seller;
        $delivery_man = DeliveryMan::with(['wallet'])->where(['seller_id'=>$seller->id])->find($id);

        if (isset($delivery_man->wallet)) {
            $withdrawbale_balance = CommonTrait::delivery_man_withdrawable_balance($id);
        } else {
            $withdrawbale_balance = 0;
        }

        $data = array();
        $data['delivery_man'] = $delivery_man;
        $data['withdrawbale_balance'] = $withdrawbale_balance;
        return response()->json($data, 200);
    }

    public function delete(Request $request, $id)
    {
        $seller = $request->seller;
        if($this->shippingMethod=='inhouse_shipping') {
            return response()->json(['message' => translate('access_denied!')], 403);
        }
        $delivery_man = DeliveryMan::where(['seller_id' => $seller->id, 'id' => $id])->first();
        if(!$delivery_man){
            return response()->json(['message' => translate('Invalid delivery-man!')], 403);
        }


        if (Storage::disk('public')->exists('delivery-man/' . $delivery_man['image'])) {
            Storage::disk('public')->delete('delivery-man/' . $delivery_man['image']);
        }

        foreach (json_decode($delivery_man['identity_image'], true) as $img) {
            if (Storage::disk('public')->exists('delivery-man/' . $img)) {
                Storage::disk('public')->delete('delivery-man/' . $img);
            }
        }

        $delivery_man->delete();
        return response()->json(['message' => translate('Deliveryman deleted successfully!')], 200);
    }

    public function reviews(Request $request, $id)
    {
        $seller = $request->seller;

        $delivery_man = DeliveryMan::where(['seller_id' => $seller->id])->with(['review'])->find($id);
        if (!$delivery_man) {
            return response()->json(['message' => translate('invalid_deliveryman!')], 403);
        }

        $reviews = Review::with('customer')
            ->where(['delivery_man_id' => $id])
            ->latest('updated_at')
            ->paginate($request['limit'], ['*'], 'page', $request['offset']);

        $average_rating = Review::where(['delivery_man_id' => $id])->avg('rating');

        $data = array();
        $data['total_size'] = $reviews->total();
        $data['limit'] = $request['limit'];
        $data['offset'] = $request['offset'];
        $data['average_rating'] = $average_rating;
        $data['reviews'] = $reviews->items();

        return response()->json($data, 200);
    }

    public function order_list(Request $request, $id)
    {
        $seller = $request->seller;
        $orders = Order::where('delivery_man_id', $id)
            ->whereHas('deliveryMan', function($query) use($seller){
                $query->where('seller_id',$seller->id);
            })
            ->latest('updated_at')
            ->paginate($request['limit'], ['*'], 'page', $request['offset']);

        $data = array();
        $data['total_size'] = $orders->total();
        $data['limit'] = $request['limit'];
        $data['offset'] = $request['offset'];
        $data['orders'] = $orders->items();

        return response()->json($data, 200);
    }

    public function order_status_history(Request $request, $id)
    {
        $histories = OrderStatusHistory::where(['order_id' => $id])
            ->latest()
            ->get();

        return response()->json($histories, 200);
    }

    public function earning(Request $request, $id)
    {
        $seller = $request->seller;
        $delivery_man = DeliveryMan::with('wallet')->where('seller_id',$seller->id)->find($id);
        $total_earn = 0;
        $withdrawable_balance = 0;
        if($delivery_man){
            $total_earn = self::delivery_man_total_earn($id);
            $withdrawable_balance = self::delivery_man_withdrawable_balance($id);
        }else{
            return response()->json(['message' => translate('invalid_deliveryman!')], 403);
        }

        $orders = Order::select('id', 'deliveryman_charge', 'order_status', 'delivery_man_id', 'updated_at')
            ->where(['delivery_man_id' => $id])
            ->whereHas('deliveryMan', function($query) use($seller){
                $query->where('seller_id',$seller->id);
            })
            ->latest('updated_at')
            ->paginate($request['limit'], ['*'], 'page', $request['offset']);

        $data = array();
        $data['total_size'] = $orders->total();
        $data['limit'] = $request['limit'];
        $data['offset'] = $request['offset'];
        $data['total_earn'] = $total_earn;
        $data['withdrawable_balance'] = $withdrawable_balance;
        $data['delivery_man'] = $delivery_man;
        $data['orders'] = $orders->items();

        return response()->json($data, 200);
    }

    public function status(Request $request)
    {
        $seller = $request->seller;
        $delivery_man = DeliveryMan::where(['seller_id'=>$seller->id])->find($request->id);
        if(!$delivery_man){
            return response()->json(['message' => translate('invalid_deliveryman')], 403);
        }
        $delivery_man->is_active = $request->status;
        $delivery_man->save();
        return response()->json(['message' => translate('status_update_successfully')], 200);
    }
}
