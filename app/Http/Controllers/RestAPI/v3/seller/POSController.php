<?php

namespace App\Http\Controllers\RestAPI\v3\seller;

use App\Contracts\Repositories\DigitalProductVariationRepositoryInterface;
use App\Contracts\Repositories\PasswordResetRepositoryInterface;
use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Contracts\Repositories\StorageRepositoryInterface;
use App\Events\CustomerRegistrationEvent;
use App\Events\DigitalProductDownloadEvent;
use App\Http\Controllers\Controller;
use App\Models\BusinessSetting;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Services\PasswordResetService;
use App\Models\User;
use App\Utils\BackEndHelper;
use App\Utils\Helpers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class POSController extends Controller
{

    /**
     * @param PasswordResetRepositoryInterface $passwordResetRepo
     * @param DigitalProductVariationRepositoryInterface $digitalProductVariationRepo
     * @param ProductRepositoryInterface $productRepo
     * @param StorageRepositoryInterface $storageRepo
     * @param PasswordResetService $passwordResetService
     */
    public function __construct(
        private readonly PasswordResetRepositoryInterface           $passwordResetRepo,
        private readonly DigitalProductVariationRepositoryInterface $digitalProductVariationRepo,
        private readonly ProductRepositoryInterface                 $productRepo,
        private readonly StorageRepositoryInterface                 $storageRepo,
        private readonly PasswordResetService                       $passwordResetService,
    )
    {
    }


    public function getProductDiscount($product, $price)
    {
        if ($product['discount_type'] == 'percent') {
            return ($price / 100) * $product['discount'];
        }
        return $product['discount'];
    }

    public function getOrderDetailsAddData($cartItem, $product): array
    {
        $variant = $cartItem['variant'];
        $unitPrice = $product['unit_price'];
        $tax = Helpers::tax_calculation(product: $product, price: $product['unit_price'], tax: $product['tax'], tax_type: $product['tax_type']);
        $price = $product['tax_model'] == 'include' ? $product['unit_price'] - $tax : $product['unit_price'];
        $productDiscount = self::getProductDiscount(product: $product, price: $product['unit_price']);
        $productSubtotal = ($product['unit_price']) * $cartItem['quantity'];

        if ($cartItem['variant'] != null) {
            foreach (json_decode($product['variation'], true) as $variation) {
                if ($cartItem['variant'] == $variation['type']) {
                    $tax = Helpers::tax_calculation(product: $product, price: $variation['price'], tax: $product['tax'], tax_type: $product['tax_type']);
                    $unitPrice = $variation['price'];
                    $price = $product['tax_model'] == 'include' ? $variation['price'] - $tax : $variation['price'];
                    $productDiscount = self::getProductDiscount(product: $product, price: $variation['price']);
                    $productSubtotal = $variation['price'] * $cartItem['quantity'];
                }
            }
        }

        if ($product['product_type'] == 'digital' && $product['digital_product_type'] == 'ready_product' && !empty($product['digital_file_ready']) && !isset($cartItem['variant_key'])) {
            $product['storage_path'] = $product['digital_file_ready_storage_type'] ??  'public';
        }

        if ($product['product_type'] == 'digital' && isset($cartItem['variant_key']) && !empty($cartItem['variant_key'])) {
            foreach ($product['digitalVariation'] as $digitalVariation) {
                if ($digitalVariation['variant_key'] == $cartItem['variant_key']) {
                    $digitalProductVariation = $this->digitalProductVariationRepo->getFirstWhere(
                        params: ['product_id' => $cartItem['id'], 'variant_key' => $cartItem['variant_key']],
                        relations: ['storage']
                    );
                    if ($product['digital_product_type'] == 'ready_product' && $digitalProductVariation) {
                        $getStoragePath = $this->storageRepo->getFirstWhere(params: [
                            'data_id' => $digitalProductVariation['id'],
                            "data_type" => "App\Models\DigitalProductVariation",
                        ]);

                        $product['digital_file_ready'] = $digitalProductVariation['file'];
                        $product['storage_path'] = $getStoragePath ? $getStoragePath['value'] : 'public';
                    }

                    $variant = $digitalVariation['variant_key'];
                    $tax = Helpers::tax_calculation(product: $product, price: $digitalVariation['price'], tax: $product['tax'], tax_type: $product['tax_type']);
                    $unitPrice = $digitalVariation['price'];
                    $price = $product['tax_model'] == 'include' ? $digitalVariation['price'] - $tax : $digitalVariation['price'];
                    $productDiscount = self::getProductDiscount(product: $product, price: $digitalVariation['price']);
                    $productSubtotal = $digitalVariation['price'] * $cartItem['quantity'];
                }
            }
        }

        $product['unit_price_amount'] = $unitPrice;
        return [
            'tax' => $tax,
            'price' => $price,
            'variant' => $variant,
            'product' => $product,
            'productDiscount' => $productDiscount,
            'productSubtotal' => $productSubtotal,
        ];

    }

    public function getProductStockCalculate($cartItem, $product): void
    {
        if ($cartItem['variant'] != null) {
            $variationStore = [];
            foreach (json_decode($product['variation'], true) as $variation) {
                if ($cartItem['variant'] == $variation['type']) {
                    $variation['qty'] -= $cartItem['quantity'];
                }
                $variationStore[] = $variation;
            }
            $this->productRepo->updateByParams(params: ['id' => $product['id']], data: ['variation' => json_encode($variationStore)]);
        }

        if ($product['product_type'] == 'physical') {
            $this->productRepo->updateByParams(params: ['id' => $product['id']], data: [
                'current_stock' => $product['current_stock'] - $cartItem['quantity']
            ]);
        }
    }

    public static function getOrderNewId()
    {
        $generateOrderID = 100000 + Order::all()->count() + 1;
        if (Order::find($generateOrderID)) {
            $generateOrderID = Order::orderBy('id', 'DESC')->first()->id + 1;
        }
        return $generateOrderID;
    }

    public function get_categories(): JsonResponse
    {
        $categories = Category::with(['childes.childes'])->where(['position' => 0])->priority()->get();
        return response()->json($categories, 200);
    }

    public function customer_store(Request $request): JsonResponse
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
        ], [
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
            'street_address' => $request['address'],
            'is_active' => 1,
            'password' => bcrypt('password')
        ]);

        $token = Str::random(120);
        $this->passwordResetRepo->add($this->passwordResetService->getAddData(identity: getWebConfig('forgot_password_verification') == 'email' ? $request['email'] : $request['phone'], token: $token, userType: 'customer'));
        $resetRoute = getWebConfig('forgot_password_verification') == 'email' ? url('/') . '/customer/auth/reset-password?token=' . $token : route('customer.auth.recover-password');
        $data = [
            'userName' => $request['f_name'],
            'userType' => 'customer',
            'templateName' => 'registration-from-pos',
            'subject' => translate('Customer_Registration_Successfully_Completed'),
            'title' => translate('welcome_to') . ' ' . getWebConfig('company_name') . '!',
            'resetPassword' => $resetRoute,
            'message' => translate('thank_you_for_joining') . ' ' . getWebConfig('company_name') . '.' . translate('if_you_want_to_become_a_registered_customer_then_reset_your_password_below_by_using_this_') . getWebConfig('forgot_password_verification') . ' ' . (getWebConfig('forgot_password_verification') == 'email' ? $request['email'] : $request['phone']) . '.' . translate('then_you’ll_be_able_to_explore_the_website_and_app_as_a_registered_customer') . '.',
        ];
        event(new CustomerRegistrationEvent(email: $request['email'], data: $data));
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

        if ($request->type != 'all') {
            array_shift($customers);
        }
        $data = array(
            'customers' => $customers
        );

        return response()->json($data, 200);
    }

    public function get_product_by_barcode(Request $request): JsonResponse
    {
        $seller = $request->seller;
        $product = Product::withCount('reviews')->where([
            'added_by' => 'seller',
            'user_id' => $seller->id,
            'code' => $request->code
        ])->first();

        $final_product = array();
        if ($product) {
            $final_product = Helpers::product_data_formatting($product, false);
        }

        return response()->json($final_product, 200);
    }

    public function product_list(Request $request): JsonResponse
    {
        $seller = $request->seller;
        $search = $request['name'];

        $products = Product::with(['digitalVariation'])->withCount('reviews')->where(['added_by' => 'seller', 'user_id' => $seller['id'], 'status' => 1])
            ->when($request->has('category_id') && $request['category_id'] != 0, function ($query) use ($request) {
                $category_ids = json_decode($request->category_id);
                $query->where(function ($query) use ($category_ids) {
                    foreach ($category_ids as $category_id) {
                        $query->orWhereJsonContains('category_ids', [[['id' => $category_id]]]);
                    }
                });
            })
            ->when($request->has('name') && $search != null, function ($query) use ($search) {
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

    public function isDigitalProductExist($cartList): bool
    {
        $cartIDs = [];
        $isDigitalProduct = false;
        foreach ($cartList as $cart) {
            if (is_array($cart)) {
                $cartIDs = [$cart['id']];
            }
        }

        if (!empty($cartIDs) && Product::whereIn('id', $cartIDs)->where(['product_type' => 'digital'])->count() > 0) {
            $isDigitalProduct = true;
        }
        return $isDigitalProduct;
    }

    public function place_order(Request $request): JsonResponse
    {
        $seller = $request->seller;

        $customerId = $request['customer_id'];
        $carts = $request['cart'];
        $extraDiscount = $request['extra_discount'];
        $extraDiscountType = $request['extra_discount_type'];
        $couponDiscountAmount = $request['coupon_discount_amount'];
        $couponCode = $request['coupon_code'];
        $paymentMethod = $request['payment_method'];

        $isDigitalProduct = self::isDigitalProductExist(cartList: $carts);
        if ($customerId == 0 && $isDigitalProduct) {
            return response()->json([
                'checkProductTypeForWalkingCustomer' => true,
                'message' => translate('To_order_digital_product') . ',' . translate('_kindly_fill_up_the_“Add_New_Customer”_form') . '.'
            ]);
        }

        $cartsTotalAmount = 0;
        $generateOrderID = self::getOrderNewId();
        foreach ($carts as $cartItem) {
            if (is_array($cartItem)) {
                $product = Product::where(['id' => $cartItem['id']])->with(['digitalVariation'])->withCount('reviews')->first();
                if ($product) {
                    $getOrderDetailsArray = self::getOrderDetailsAddData(cartItem: $cartItem, product: $product);
                    $cartsTotalAmount += $getOrderDetailsArray['price'] * $cartItem['quantity'];
                    $cartsTotalAmount += $getOrderDetailsArray['tax'] * $cartItem['quantity'];

                    $orderDetailsData = [
                        'order_id' => $generateOrderID,
                        'product_id' => $cartItem['id'],
                        'product_details' => $getOrderDetailsArray['product'],
                        'qty' => $cartItem['quantity'],
                        'price' => $getOrderDetailsArray['price'],
                        'seller_id' => $product['user_id'],
                        'tax' => $getOrderDetailsArray['tax'] * $cartItem['quantity'],
                        'tax_model' => $product['tax_model'],
                        'discount' => $getOrderDetailsArray['productDiscount'] * $cartItem['quantity'],
                        'discount_type' => 'discount_on_product',
                        'delivery_status' => 'delivered',
                        'payment_status' => 'paid',
                        'variant' => $getOrderDetailsArray['variant'],
                        'variation' => json_encode($cartItem['variation']),
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                    self::getProductStockCalculate(cartItem: $cartItem, product: $product);
                    DB::table('order_details')->insert($orderDetailsData);
                }
            }
        }

        $orderData = [
            'id' => $generateOrderID,
            'customer_id' => $customerId,
            'customer_type' => 'customer',
            'payment_status' => 'paid',
            'order_status' => 'delivered',
            'seller_id' => $seller->id,
            'seller_is' => 'seller',
            'payment_method' => $paymentMethod,
            'order_type' => 'POS',
            'checked' => 1,
            'extra_discount' => $extraDiscount ?? 0,
            'extra_discount_type' => $extraDiscountType ?? null,
            'order_amount' => BackEndHelper::currency_to_usd($cartsTotalAmount),
            'discount_amount' => BackEndHelper::currency_to_usd($couponDiscountAmount ?? 0),
            'coupon_code' => $couponCode ?? null,
            'discount_type' => (isset($carts['coupon_code']) && $carts['coupon_code']) ? 'coupon_discount' : NULL,
            'coupon_discount_bearer' => $carts['coupon_bearer'] ?? 'inhouse',
            'created_at' => now(),
            'updated_at' => now(),
        ];
        DB::table('orders')->insertGetId($orderData);

        if ($isDigitalProduct) {
            $order = Order::with(['details.productAllStatus', 'customer'])->find($generateOrderID);
            $data = [
                'userName' => $order->customer->f_name,
                'userType' => 'customer',
                'templateName' => 'digital-product-download',
                'order' => $order,
                'subject' => translate('download_Digital_Product'),
                'title' => translate('Congratulations') . '!',
                'emailId' => $order->customer['email'],
            ];
            event(new DigitalProductDownloadEvent(email: $order->customer['email'], data: $data));
        }
        return response()->json(['order_id' => $generateOrderID], 200);
    }

    public function get_invoice(Request $request)
    {
        $seller = $request->seller;
        $id = $request->id;

        $seller_pos = BusinessSetting::where('type', 'seller_pos')->first()->value;
        if ($seller->pos_status == 0 || $seller_pos == 0) {
            return response()->json(['message' => translate('access_denied!')], 403);
        }

        $orders = Order::with('details', 'shipping')->where(['seller_id' => $seller['id']])->find($id);
        if ($orders) {
            foreach ($orders['details'] as $order) {
                $order['product_details'] = Helpers::product_data_formatting(json_decode($order['product_details'], true));
            }
        }

        return response()->json($orders, 200);
    }


}
