<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use LaravelQRCode\Facades\QRCode;
use Madnest\Madzipper\Facades\Madzipper;

/*Route::get('zip-extract', function () {
    Madzipper::make('test-zip.zip')->extractTo('public');
});*/

/*Route::get('/view-test', function () {
    view('welcome');
});*/


/*Route::get('qr-code', function () {
    return QRCode::text('Laravel QR Code Generator!')
        ->setOutfile('storage/app/public/deal/2021-10-30-617d68a9a7e8b.png')
        ->png();
});*/

use App\Utils\Helpers;
Route::get('aws-data', function () {
    return "bdsb";
    return view('installation.step5');
    $mail_config = Helpers::get_business_settings('mail_config');
     return $mail_config['status']??0;
    return view('welcome');
});
Route::get('order-email',function(){
    //Mail::to('safayet2218@gmail.com')->send(new \App\Mail\OrderPlaced(100206));
    $id = 100207;
    return view('email-templates.order-placed-v2',compact('id'));
});
Route::post('aws-upload', function (Request $request) {
    $request->validate([
        'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    $imageName = time() . '.' . $request->image->extension();

    $path = Storage::disk('s3')->put('images', $request->image);
    $path = Storage::disk('s3')->url($path);

    dd($path);
    /* Store $imageName name in DATABASE from HERE */
    return back()
        ->with('success', 'You have successfully upload image.')
        ->with('image', $path);
})->name('aws-upload');

/*Route::get('test-data-insert', function () {
    ini_set('max_execution_time', '300');
    $order_id = \App\Models\Order::orderBy('id', 'DESC')->first()->id??0;
    $user_id = rand(1, 20);
    $or = [];
    for ($count = 1; $count < 110000; $count++) {
        array_push($or, [
            'id' => $order_id+$count,
            'verification_code' => rand(100000, 999999),
            'customer_id' => $user_id,
            'seller_id' => 1,
            'seller_is' => 'admin',
            'customer_type' => 'customer',
            'payment_status' => 'paid',
            'order_status' => "delivered",
            'payment_method' => "cash_on_delivery",
            'transaction_ref' => rand(0000, 100939),
            'order_group_id' => rand(1234, 43353),
            'discount_amount' => 0,
            'discount_type' => 0 == 0 ? null : 'coupon_discount',
            'coupon_code' => 0,
            'order_amount' => rand(1234, 23434),
            'shipping_address' => 1,
            'shipping_address_data' => "",
            'billing_address' => 1,
            'billing_address_data' => "",
            'shipping_cost' => 5,
            'shipping_method_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
            'order_note' => ''
        ]);
    }

    $insert_data = collect($or);

    $chunks = $insert_data->chunk(500);

    foreach ($chunks as $chunk)
    {
        DB::table('orders')->insert($chunk->toArray());
    }
});*/
