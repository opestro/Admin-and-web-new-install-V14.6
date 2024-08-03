<?php

namespace App\Services;

use App\Traits\CommonTrait;
use App\Traits\FileManagerTrait;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\RedirectResponse;

class DeliveryManService
{
    use CommonTrait;
    use FileManagerTrait;

    /**
     * @param object $request
     * @param string $addedBy
     * @return array
     */
    protected function getCommonDeliveryManData(object $request, string $addedBy) :array
    {
        return [
            'seller_id' =>  $addedBy == 'seller' ? auth('seller')->id() : 0,
            'f_name' => $request['f_name'],
            'l_name' => $request['l_name'],
            'address' => $request['address'],
            'email' => $request['email'],
            'country_code' => '+'.$request['country_code'],
            'phone' => $request['phone'],
            'identity_number' => $request['identity_number'],
            'identity_type' => $request['identity_type'],
            'password' => bcrypt($request['password']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * @param object $request
     * @param string $addedBy
     * @return array
     * This array return column name and there value when add delivery man
     */
    public function getDeliveryManAddData(object $request, string $addedBy): array
    {
        $identityImage = [];
        if (!empty($request->file('identity_image'))) {
            foreach ($request->identity_image as $image) {
                $identityImage[] = $this->upload(dir: 'delivery-man/', format: 'webp', image: $image);
            }
            $identityImage = json_encode($identityImage);
        } else {
            $identityImage = json_encode([]);
        }
        $commonArray = $this->getCommonDeliveryManData(request: $request, addedBy: $addedBy);
        $imageArray = [
            'identity_image' => $identityImage,
            'image' => $this->upload(dir: 'delivery-man/', format: 'webp', image: $request->file('image')),
        ];
       return array_merge($commonArray,$imageArray);
    }

    /**
     * @param object $request
     * @param string $addedBy
     * @param string $identityImages
     * @param string $deliveryManImage
     * @return array
     * This array return column name and there value when update delivery man
     */
    public function getDeliveryManUpdateData(object $request,string $addedBy,string $identityImages,string $deliveryManImage): array
    {
        if (!empty($request->file('identity_image'))) {
            foreach (json_decode($identityImages, true) as $image) {
                $this->delete(filePath: 'delivery-man/' . $image);
            }
            $identityImage = [];
            foreach ($request['identity_image'] as $img) {
                $identityImage[] = $this->upload('delivery-man/', 'webp', $img);
            }
            $identityImage = json_encode($identityImage);
        } else {
            $identityImage = $identityImages;
        }
        if($request->has('image')){
            $image =  $this->update(dir: 'delivery-man/', oldImage: $deliveryManImage, format: 'webp', image: $request->file('image'));
        }else{
            $image = $deliveryManImage;
        }
        $commonArray = $this->getCommonDeliveryManData(request: $request, addedBy: $addedBy);
        $imageArray = [
            'identity_image' => $identityImage,
            'image' => $image,
        ];
        return array_merge($commonArray, $imageArray);
    }

    function deleteImages(object $deliveryMan): bool
    {
        $this->delete(filePath: 'delivery-man/' . $deliveryMan['image']);
        foreach (json_decode($deliveryMan['identity_image'], true) as $image) {
            $this->delete(filePath: 'delivery-man/' . $image);
        }

        return true;
    }

    public function getOrderHistoryListExportData(object $request, object $deliveryMan, object $orders): array
    {
        $orders->each(function ($order) {
            $totalQty = $order->details->sum('qty');
            $order->total_qty = $totalQty;
        });

        return [
            'delivery_man' => $deliveryMan,
            'orders' => $orders,
            'total_earn' => $this->delivery_man_total_earn($deliveryMan['id']),
            'withdrawable_balance' => $this->delivery_man_withdrawable_balance($deliveryMan['id']),
            'search' => $request['searchValue'],
            'type' => $request['type'],
        ];
    }
    public function checkConditions():bool
    {
        $shippingMethod = getWebConfig(name: 'shipping_method');
        if($shippingMethod == 'inhouse_shipping')
        {
            Toastr::warning(translate('access_denied!!'));
            return false;
        }
        return true;
    }
}
