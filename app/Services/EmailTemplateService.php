<?php

namespace App\Services;

use App\Enums\EmailTemplateKey;
use App\Traits\FileManagerTrait;

class EmailTemplateService
{
    use FileManagerTrait;
    public function getEmailTemplateData($userType):array
    {
        $admin = EmailTemplateKey::ADMIN_EMAIL_LIST;
        $vendor = EmailTemplateKey::VENDOR_EMAIL_LIST;
        $customer = EmailTemplateKey::CUSTOMER_EMAIL_LIST;
        $deliveryMan = EmailTemplateKey::DELIVERY_MAN_EMAIL_LIST;

        return match ($userType) {
            'admin' => $admin,
            'customer' => $customer,
            'vendor' => $vendor,
            'delivery-man' => $deliveryMan,
        };
    }
    public function getHiddenField(string $userType, string $templateName):array|null
    {

        $hiddenData =  [
            EmailTemplateKey::REGISTRATION=>array('product_information','order_information','button_content','banner_image'),
            EmailTemplateKey::REGISTRATION_VERIFICATION=>array('product_information','order_information','button_content','banner_image'),
            EmailTemplateKey::REGISTRATION_FROM_POS=>array('product_information','order_information','button_url','button_content_status','banner_image'),
            EmailTemplateKey::REGISTRATION_APPROVED=>array('product_information','order_information','button_content','banner_image'),
            EmailTemplateKey::REGISTRATION_DENIED=>array('product_information','order_information','button_content','banner_image'),
            EmailTemplateKey::ACCOUNT_ACTIVATION=>array('product_information','order_information','button_content','banner_image'),
            EmailTemplateKey::ACCOUNT_SUSPENDED=>array('product_information','order_information','button_content','banner_image'),
            EmailTemplateKey::ACCOUNT_UNBLOCK=>array('product_information','order_information','button_content','banner_image'),
            EmailTemplateKey::ACCOUNT_BLOCK=>array('product_information','order_information','button_content','banner_image'),
            EmailTemplateKey::DIGITAL_PRODUCT_DOWNLOAD=>array('product_information','button_content','banner_image'),
            EmailTemplateKey::DIGITAL_PRODUCT_OTP=>array('product_information','order_information','button_content','banner_image'),
            EmailTemplateKey::ORDER_PLACE=>array('icon','product_information','banner_image'),
            EmailTemplateKey::ORDER_DElIVERED=>array('product_information','order_information','button_content','banner_image'),
            EmailTemplateKey::FORGET_PASSWORD=>array('product_information','order_information','button_content','banner_image'),
            EmailTemplateKey::ORDER_RECEIVED=>array('icon','product_information','button_content','banner_image'),
            EmailTemplateKey::ADD_FUND_TO_WALLET=>array('product_information','order_information','button_content','banner_image'),
            EmailTemplateKey::RESET_PASSWORD_VERIFICATION=>array('product_information','order_information','button_content','banner_image'),


        ];
        return $hiddenData[$templateName];
    }

    public function getAddData(string $userType,string $templateName,array|null $hideField,string $title, string $body):array
    {
        return [
            'template_name' => $templateName,
            'user_type' => $userType,
            'template_design_name' => $templateName,
            'title' => $title,
            'body' => $body,
            'hide_field' => $hideField ,
            'copyright_text' => translate('copyright_').date('Y').' '.getWebConfig('company_name').'. '.translate('all_right_reserved').'.',
            'footer_text' => translate('please_contact_us_for_any_queries').','.translate('_we’re_always_happy_to_help').'.',
        ];
    }
    public function getUpdateData(object $request,$template):array
    {
        $image = $request['image'] ? $this->update(dir:'email-template/', oldImage: $template['image'], format: 'webp',image:  $request->file('image')) : $template['image'];
        $logo = $request['logo'] ? $this->update(dir:'email-template/', oldImage: $template['logo'], format: 'webp',image:  $request->file('logo')) : $template['logo'];
        $icon = $request['icon'] ? $this->update(dir:'email-template/', oldImage: $template['logo'], format: 'webp',image:  $request->file('icon')) : $template['icon'];
        $bannerImage = $request['banner_image'] ? $this->update(dir: 'email-template/',oldImage:  $template['banner_image'], format: 'webp',image:  $request->file('banner_image')): $template['banner_image'];
        return [
            'title' => $request['title']['en'],
            'body' => $request['body']['en'],
            'banner_image' => $bannerImage,
            'image' => $icon,
            'logo' => $logo,
            'button_name' => $request['button_name']['en'] ?? null,
            'button_url' => $request['button_url'],
            'footer_text' => $request['footer_text']['en'],
            'copyright_text' => $request['copyright_text']['en'],
            'pages' => $request['social_media'] ? array_keys($request['pages']) :null,
            'social_media' =>$request['social_media'] ? array_keys($request['social_media']) : null,
            'button_content_status' => $request->get('button_content_status', 0),
            'product_information_status' => $request->get('product_information_status', 0),
            'order_information_status' => $request->get('order_information_status', 0),
        ];
    }

    public function getTitleData(string $userType,$templateName):string
    {
        $titleData =  [
            EmailTemplateKey::REGISTRATION=>'Registration Complete',
            EmailTemplateKey::REGISTRATION_VERIFICATION=>'Registration Verification',
            EmailTemplateKey::REGISTRATION_FROM_POS=>'Registration Complete',
            EmailTemplateKey::REGISTRATION_APPROVED=>'Registration Approved',
            EmailTemplateKey::REGISTRATION_DENIED=>'Registration Denied',
            EmailTemplateKey::ACCOUNT_ACTIVATION=>'Account Activation',
            EmailTemplateKey::ACCOUNT_SUSPENDED=>'Account Suspended',
            EmailTemplateKey::ACCOUNT_UNBLOCK=>'Account Unblocked',
            EmailTemplateKey::ACCOUNT_BLOCK=>'Account Blocked',
            EmailTemplateKey::DIGITAL_PRODUCT_DOWNLOAD=>'Congratulations',
            EmailTemplateKey::DIGITAL_PRODUCT_OTP=>'Digital Product Download OTP Verification',
            EmailTemplateKey::ORDER_PLACE=>'Order'.' # '.'{orderId}'.' Has Been Placed Successfully!',
            EmailTemplateKey::FORGET_PASSWORD=>'Change Password Request',
            EmailTemplateKey::ORDER_RECEIVED=>'New Order Received',
            EmailTemplateKey::ADD_FUND_TO_WALLET=>'Transaction Successful',
            EmailTemplateKey::RESET_PASSWORD_VERIFICATION=>'OTP Verification For Password Reset',
        ];
        return $titleData[$templateName];
    }
    public function getBodyData(string $userType,$templateName):string
    {
        $userType = match ($userType) {
            'admin' => '{adminName}',
            'customer' => '{userName}',
            'vendor' => '{vendorName}',
            'delivery-man' => '{deliveryManName}',
        };

        $bodyData =  [
            EmailTemplateKey::REGISTRATION=>'<div><b>Hi '.$userType.',</b></div><div><b><br></b></div><div>Congratulation! Your registration request has been send to admin successfully! Please wait until admin reviewal. </div><div><br></div><div>meanwhile click here to visit the '.getWebConfig('company_name').' Shop Website</div><div><font color="#0000ff"> <a href="'.url('/').'" target="_blank">'.url('/').'</a></font></div>',
            EmailTemplateKey::REGISTRATION_VERIFICATION=>'<p><b>Hi '.$userType.',</b></p><p>Your verification code is</p>',
            EmailTemplateKey::REGISTRATION_FROM_POS=>'<p><b>Hi '.$userType.',</b></p><p>Thank you for joining '.getWebConfig('company_name').' Shop.If you want to become a registered customer then reset your password below by using this email. Then you’ll be able to explore the website and app as a registered customer.</p>',
            EmailTemplateKey::REGISTRATION_APPROVED=>'<div><b>Hi '.$userType.',</b></div><div><b><br></b></div><div>Your registration request has been approved by admin. Now you can complete your store setting and start selling your product on '.getWebConfig('company_name').' Shop. </div><div><br></div><div>Meanwhile, click here to visit the'.getWebConfig('company_name').' shop website</div><div><font color="#0000ff"> <a href="'.url('/').'" target="_blank">'.url('/').'</a></font></div>',
            EmailTemplateKey::REGISTRATION_DENIED=>'<div><b>Hi '.$userType.',</b></div><div><b><br></b></div><div>Your registration request has been denied by admin. Please contact with admin or support center if you have any queries.</div><div><br></div><div>Meanwhile, click here to visit the'.getWebConfig('company_name').' shop website</div><div><font color="#0000ff"> <a href="'.url('/').'" target="_blank">'.url('/').'</a></font></div>',
            EmailTemplateKey::ACCOUNT_ACTIVATION=>'<div><b>Hi '.$userType.',</b></div><div><b><br></b></div><div>Your account suspension has been revoked by admin. From now you can access your app and panel again Please contact us for any queries we’re always happy to help.</div><div><br></div><div>Meanwhile, click here to visit the'.getWebConfig('company_name').' shop website</div><div><font color="#0000ff"> <a href="'.url('/').'" target="_blank">'.url('/').'</a></font></div>',
            EmailTemplateKey::ACCOUNT_SUSPENDED=>'<div><b>Hi '.$userType.',</b></div><div><b><br></b></div><div>Your account access has been suspended by admin.From now you can access your app and panel again Please contact us for any queries we’re always happy to help.</div><div><br></div><div>Meanwhile, click here to visit the'.getWebConfig('company_name').' shop website</div><div><font color="#0000ff"> <a href="'.url('/').'" target="_blank">'.url('/').'</a></font></div>',
            EmailTemplateKey::ACCOUNT_UNBLOCK=>'<div><b>Hi '.$userType.',</b></div><div><b><br></b></div><div>Your account has been successfully unblocked. We appreciate your cooperation in resolving this issue. Thank you for your understanding and patience. </div><div><br></div><div>Meanwhile, click here to visit the'.getWebConfig('company_name').' shop website</div><div><font color="#0000ff"> <a href="'.url('/').'" target="_blank">'.url('/').'</a></font></div>',
            EmailTemplateKey::ACCOUNT_BLOCK=>'<div><b>Hi '.$userType.',</b></div><div><b><br></b></div><div>Your account has been blocked due to suspicious activity by the admin .To resolve this issue please contact with admin or support center. We apologize for any inconvenience caused.</div><div><br></div><div>Meanwhile, click here to visit the'.getWebConfig('company_name').'shop website</div><div><font color="#0000ff"> <a href="'.url('/').'" target="_blank">'.url('/').'</a></font></div>',
            EmailTemplateKey::DIGITAL_PRODUCT_DOWNLOAD=>'<p>Thank you for choosing '.getWebConfig('company_name') .' shop! Your digital product is ready for download. To download your product use your email <b>{emailId}</b> and order # {orderId} below.</b><br></p>',
            EmailTemplateKey::DIGITAL_PRODUCT_OTP=>'<p><b>Hi '.$userType.',</b></p><p>Your verification code is</p>',
            EmailTemplateKey::ORDER_PLACE=>'<p><b>Hi '.$userType.',</b></p><p>Your order from {shopName} has been placed to know the current status of your order click track order</p>',
            EmailTemplateKey::FORGET_PASSWORD=>'<p><b>Hi '.$userType.',</b></p><p>Please click the link below to change your password.</p>',
            EmailTemplateKey::ORDER_RECEIVED=> '<p><b>Hi '.$userType.',</b></p><p>We have sent you this email to notify that you have a new order.You will be able to see your orders after login to your panel.</p>',
            EmailTemplateKey::ADD_FUND_TO_WALLET=>'<div style="text-align: center; ">Amount successfully credited to your wallet .</div><div style="text-align: center; "><br></div>',
            EmailTemplateKey::RESET_PASSWORD_VERIFICATION=>'<p><b>Hi '.$userType.',</b></p><p>Your verification code is</p>',
        ];
        return $bodyData[$templateName];
    }
}
