<?php

namespace App\Http\Controllers\RestAPI\v3\seller;

use App\Contracts\Repositories\PasswordResetRepositoryInterface;
use App\Events\CustomerRegistrationEvent;
use App\Events\DigitalProductDownloadEvent;
use App\Http\Controllers\Controller;
use App\Models\BusinessSetting;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Services\PasswordResetService;
use App\User;
use App\Utils\BackEndHelper;
use App\Utils\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class POSController extends Controller
{
    public function __construct(
        private readonly PasswordResetRepositoryInterface $passwordResetRepo,
        private readonly PasswordResetService $passwordResetService,
    )
    {
    }
    public function get_categories()
    {
        $categories = Category::with(['childes.childes'])->where(['position' => 0])->priority()->get();
        return response()->json($categories, 200);
    }
    public function customer_store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'f_name' => 'required',
            'l_name' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'unique:users',
            'country' => 'required',
            'city' => 'required',
            'zip_code' => 'required',
            'address' => 'required',
        ],[
            'f_name.required' => 'First name is required!',
            'l_name.required' => 'Last name is required!'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        User::create([
            'f_name' => $request['f_name'],
            'l_name' => $request['l_name'],
            'email' => $request['email'],
            'phone' => $request['phone'],
            'country' => $request['country'],
            'city' => $request['city'],
            'zip' => $request['zip_code'],
            'street_address' =>$request['address'],
            'is_active' => 1,
            'password' => bcrypt('password')
        ]);

        $token = Str::random(120);
        $this->passwordResetRepo->add($this->passwordResetService->getAddData(identity:getWebConfig('forgot_password_verification') == 'email' ? $request['email'] : $request['phone'],token: $token,userType:'customer'));
        $resetRoute = getWebConfig('forgot_password_verification') == 'email' ? url('/') . '/customer/auth/reset-password?token='.$token : route('customer.auth.recover-password');
        $data = [
            'userName' => $request['f_name'],
            'userType' => 'customer',
            'templateName'=>'registration-from-pos',
            'subject' => translate('Customer_Registration_Successfully_Completed'),
            'title' => translate('welcome_to').' '.getWebConfig('company_name').'!',
            'resetPassword' => $resetRoute,
            'message' => translate('thank_you_for_joining').' '.getWebConfig('company_name').'.'.translate('if_you_want_to_become_a_registered_customer_then_reset_your_password_below_by_using_this_').getWebConfig('forgot_password_verification').' '.(getWebConfig('forgot_password_verification') == 'email' ? $request['email'] : $request['phone']).'.'.translate('then_you’ll_be_able_to_explore_the_website_and_app_as_a_registered_customer').'.',
        ];
        event(new CustomerRegistrationEvent(email:$request['email'],data: $data));
        return response()->json(['message' => translate('customer added successfully!')], 200);
    }

    public function customers(Request $request)
    {
        $seller = $request->seller;
        $customers = User::when(!empty($request['name']), function ($query) use ($request) {
                $search = $request['name'];
                $query->where(function ($q) use ($search) {
                    $q->orWhere('f_name', 'like', "%{$search}%")
                        ->orWhere('l_name', 'like', "%{$search}%");
                });
            })
            ->whereNotNull(['f_name', 'l_name', 'phone'])
            ->take(10)
            ->get()
            ->toArray();

        if($request->type!='all'){
            array_shift($customers);
        }
        $data = array(
            'customers'=>$customers
        );


        return response()->json($data, 200);
    }

    public function get_product_by_barcode(Request $request)
    {
        $seller = $request->seller;
        $product = Product::withCount('reviews')->where([
            'added_by'=>'seller',
            'user_id'=>$seller->id,
            'code' => $request->code
        ])->first();

        $final_product = array();
        if($product) {
            $final_product = Helpers::product_data_formatting($product, false);
        }

        return response()->json($final_product, 200);
    }

    public function product_list(Request $request)
    {
        $seller = $request->seller;
        $search = $request['name'];

        $products = Product::withCount('reviews')->where(['added_by' => 'seller', 'user_id' => $seller['id'], 'status'=>1])
            ->when($request->has('category_id') && $request['category_id'] != 0, function ($query) use ($request) {
                $category_ids = json_decode($request->category_id);
                $query->where(function ($query) use ($category_ids) {
                    foreach ($category_ids as $category_id) {
                        $query->orWhereJsonContains('category_ids', [[['id' => $category_id]]]);
                    }
                });
            })
            ->when($request->has('name') && $search!=null,function ($query) use ($search) {
                $key = $search ? explode(' ', $search) : '';
                foreach ($key as $value) {
                    $query->where('name', 'like', "%{$value}%");
                }
            })
            ->orderBy('id', 'DESC')
            ->paginate($request['limit'], ['*'], 'page', $request['offset']);

        $products_final = Helpers::product_data_formatting($products, true);

        $data = array();
        $data['total_size'] = $products->total();
        $data['limit'] = $request['limit'];
        $data['offset'] = $request['offset'];
        $data['products'] = $products_final;
        return response()->json($data, 200);
    }

    public function place_order(Request $request)
    {
        $seller = $request->seller;

        $customer_id = $request->customer_id;
        $carts = $request->cart;
        $extra_discount = $request->extra_discount;
        $extra_discount_type = $request->extra_discount_type;
        $coupon_discount_amount = $request->coupon_discount_amount;
        $coupon_code = $request->coupon_code;
        $order_amount = $request->order_amount;
        $payment_method = $request->payment_method;

        $total_tax_amount = 0;
        $product_price = 0;
        $order_details = [];

        $isDigitalProduct = false;
        foreach($carts as $cart){
            if(is_array($cart)){
                $product = Product::withCount('reviews')->find($cart['id']);
                if ($product && $product->product_type == 'digital') {
                    $isDigitalProduct = true;
                }
            }
        }
        if($customer_id == 0 && $isDigitalProduct){
            return response()->json(['checkProductTypeForWalkingCustomer' =>true,'message'=> translate('To_order_digital_product').','.translate('_kindly_fill_up_the_“Add_New_Customer”_form').'.']);
        }

        $order_id = 100000 + Order::all()->count() + 1;
        if (Order::find($order_id)) {
            $order_id = Order::orderBy('id', 'DESC')->first()->id + 1;
        }

        $product_subtotal = 0;

        foreach($carts as $c)
        {
            if(is_array($c))
            {
                $product = Product::withCount('reviews')->find($c['id']);

                $discount_on_product = 0;
                $product_subtotal = ($c['price']) * $c['quantity'];
                $discount_on_product += ($c['discount'] * $c['quantity']);
                if($product)
                {
                    $tax = Helpers::tax_calculation(product: $product, price: $c['price'], tax: $product['tax'], tax_type: $product['tax_type']);
                    $price = $product['tax_model']=='include' ? $c['price']-$tax : $c['price'];

                    $or_d = [
                        'order_id' => $order_id,
                        'product_id' => $c['id'],
                        'product_details' => $product,
                        'qty' => $c['quantity'],
                        'price' => $price,
                        'seller_id' => $product['user_id'],
                        'tax' => $tax*$c['quantity'],
                        'tax_model' => $product['tax_model'],
                        'discount' => $c['discount']*$c['quantity'],
                        'discount_type' => 'discount_on_product',
                        'delivery_status' => 'delivered',
                        'payment_status' => 'paid',
                        'variant' => $c['variant'],
                        'variation' => json_encode($c['variation']),
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                    $total_tax_amount += $or_d['tax'] * $c['quantity'];
                    $product_price += $product_subtotal - $discount_on_product;
                    $order_details[] = $or_d;

                    if ($c['variant'] != null) {
                        $type = $c['variant'];
                        $var_store = [];

                        foreach (json_decode($product['variation'],true) as $var) {
                            if ($type == $var['type']) {
                                $var['qty'] -= $c['quantity'];
                            }
                            array_push($var_store, $var);
                        }
                        Product::where(['id' => $product['id']])->update([
                            'variation' => json_encode($var_store),
                        ]);
                    }

                    if($product['product_type'] == 'physical') {
                        Product::where(['id' => $product['id']])->update([
                            'current_stock' => $product['current_stock'] - $c['quantity']
                        ]);
                    }

                    DB::table('order_details')->insert($or_d);
                }

            }

        }

        $total_price = $product_price;
        if (isset($carts['ext_discount'])) {
            $extra_discount = $extra_discount_type == 'percent' && $extra_discount > 0 ? (($total_price * $extra_discount) / 100) : $extra_discount;
            $total_price -= $extra_discount;
        }
        $or = [
            'id' => $order_id,
            'customer_id' => $customer_id,
            'customer_type' => 'customer',
            'payment_status' => 'paid',
            'order_status' => 'delivered',
            'seller_id' => $seller->id,
            'seller_is' => 'seller',
            'payment_method' => $payment_method,
            'order_type' => 'POS',
            'checked' =>1,
            'extra_discount' =>$extra_discount??0,
            'extra_discount_type' => $extra_discount_type??null,
            'order_amount' => BackEndHelper::currency_to_usd($order_amount),
            'discount_amount' => BackEndHelper::currency_to_usd($coupon_discount_amount??0),
            'coupon_code' => $coupon_code??null,
            'discount_type' => (isset($carts['coupon_code']) && $carts['coupon_code']) ? 'coupon_discount' : NULL,
            'coupon_discount_bearer' => $carts['coupon_bearer']??'inhouse',
            'created_at' => now(),
            'updated_at' => now(),
        ];
        DB::table('orders')->insertGetId($or);
        if ($isDigitalProduct){
            $order = Order::with(['details.productAllStatus','customer'])->find($order_id);
            $data = [
                'userName'=>$order->customer->f_name,
                'userType' =>'customer',
                'templateName' =>'digital-product-download',
                'order' => $order,
                'subject' => translate('download_Digital_Product'),
                'title' => translate('Congratulations').'!',
                'emailId' => $order->customer['email'],
            ];
            event(new DigitalProductDownloadEvent(email: $order->customer['email'],data: $data));
        }
        return response()->json(['order_id'=>$order_id], 200);

    }

    public function get_invoice(Request $request)
    {
        $seller = $request->seller;
        $id = $request->id;

        $seller_pos = BusinessSetting::where('type','seller_pos')->first()->value;
        if($seller->pos_status == 0 || $seller_pos == 0)
        {
            return response()->json(['message' => translate('access_denied!')], 403);
        }

        $orders = Order::with('details', 'shipping')->where(['seller_id' => $seller['id']])->find($id);

        if($orders) {
            foreach ($orders['details'] as $order) {
                $order['product_details'] = Helpers::product_data_formatting(json_decode($order['product_details'], true));
            }
        }

        return response()->json($orders, 200);
    }



}
