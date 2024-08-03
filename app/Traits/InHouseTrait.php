<?php

namespace App\Traits;

use App\Contracts\Repositories\AdminRepositoryInterface;
use App\Models\Admin;
use App\Models\Seller;
use App\Models\Shop;
use Illuminate\Support\Str;

trait InHouseTrait
{
    public function __construct(
        private readonly AdminRepositoryInterface             $adminRepo,
    )
    {
    }

    public function getInHouseShopObject(): Shop
    {
        $inhouseVacation = getWebConfig(name: 'vacation_add');

        $current_date = date('Y-m-d');
        $start_date = date('Y-m-d', strtotime($inhouseVacation['vacation_start_date']));
        $end_date = date('Y-m-d', strtotime($inhouseVacation['vacation_end_date']));
        $is_vacation_mode_now = $inhouseVacation['status'] && ($current_date >= $inhouseVacation['vacation_start_date']) && ($current_date <= $inhouseVacation['vacation_end_date']) ? 1 : 0;

        $inhouseShop = new Shop([
            'seller_id' => 0,
            'name' => getWebConfig(name: 'company_name'),
            'slug' => Str::slug(getWebConfig(name: 'company_name')),
            'address' => getWebConfig(name: 'shop_address'),
            'contact' => getWebConfig(name: 'company_phone'),
            'image' => getWebConfig(name: 'company_fav_icon'),
            'bottom_banner' => getWebConfig(name: 'bottom_banner'),
            'offer_banner' => getWebConfig(name: 'offer_banner'),
            'vacation_start_date' => $inhouseVacation['vacation_start_date'] ?? null,
            'vacation_end_date' => $inhouseVacation['vacation_end_date'] ?? null,
            'is_vacation_mode_now' => $is_vacation_mode_now,
            'vacation_note' => $inhouseVacation['vacation_note'],
            'vacation_status' => $inhouseVacation['status'] ?? false,
            'temporary_close' => getWebConfig(name: 'temporary_close') ? getWebConfig(name: 'temporary_close')['status'] : 0,
            'banner' => getWebConfig(name: 'shop_banner'),
            'created_at' => isset(Admin::where(['id' => 1])->first()->created_at) ? Admin::where(['id' => 1])->first()->created_at : null,
        ]);
        $inhouseShop->id = 0;
        return $inhouseShop;
    }

    public function getInHouseSellerObject(): Seller
    {
        $inhouseSeller = new Seller([
            "f_name" => getWebConfig(name: 'company_name'),
            "l_name" => getWebConfig(name: 'company_name'),
            "phone" => getWebConfig(name: 'company_phone'),
            "image" => getWebConfig(name: 'company_fav_icon'),
            "email" => getWebConfig(name: 'company_email'),
            "status" => "approved",
            "pos_status" => 1,
            "minimum_order_amount" => (int)getWebConfig(name: 'minimum_order_amount'),
            "free_delivery_status" => (int)getWebConfig(name: 'free_delivery_status'),
            "free_delivery_over_amount" => getWebConfig(name: 'free_delivery_over_amount'),
            "app_language" => getDefaultLanguage(),
            'created_at' => Admin::where(['id' => 1])->first()->created_at,
            'updated_at' => Admin::where(['id' => 1])->first()->created_at,
            "bank_name" => "",
            "branch" => "",
            "account_no" => "",
            "holder_name" => "",
        ]);
        $inhouseSeller->id = 0;
        return $inhouseSeller;
    }


}
