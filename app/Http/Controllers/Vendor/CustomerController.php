<?php

namespace App\Http\Controllers\Vendor;

use App\Contracts\Repositories\CustomerRepositoryInterface;
use App\Contracts\Repositories\PasswordResetRepositoryInterface;
use App\Events\CustomerRegistrationEvent;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Vendor\CustomerRequest;
use App\Repositories\ShippingAddressRepository;
use App\Services\CustomerService;
use App\Services\PasswordResetService;
use App\Services\ShippingAddressService;
use App\Traits\EmailTemplateTrait;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use phpseclib3\Common\Functions\Strings;

class CustomerController extends BaseController
{
    use EmailTemplateTrait;
    public function __construct(
        private readonly CustomerRepositoryInterface $customerRepo,
        private readonly PasswordResetRepositoryInterface $passwordResetRepo,
        private readonly PasswordResetService $passwordResetService,
        private readonly ShippingAddressRepository $shippingAddressRepo,
        private readonly ShippingAddressService $shippingAddressService,

    )
    {
    }
    public function index(?Request $request, string $type = null): View|Collection|LengthAwarePaginator|null|callable|RedirectResponse
    {
        // TODO: Implement index() method.
    }
    public function getList(Request $request):JsonResponse
    {
        $customers = $this->customerRepo->getCustomerNameList(
            request:$request,
            dataLimit: getWebConfig(name:'pagination_limit')
        );
        return response()->json($customers);
    }
    public function add(CustomerRequest $request,CustomerService $customerService):RedirectResponse
    {
        $token = Str::random(120);
        $this->passwordResetRepo->add($this->passwordResetService->getAddData(identity:getWebConfig('forgot_password_verification') == 'email' ? $request['email'] : $request['phone'],token: $token,userType:'customer'));
        $this->customerRepo->add($customerService->getCustomerData(request: $request));
        $customer = $this->customerRepo->getFirstWhere(params: ['email'=>$request['email']]);
        $this->shippingAddressRepo->add($this->shippingAddressService->getAddAddressData(request:$request,customerId: $customer['id'],addressType: 'home'));
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
        Toastr::success(translate('customer_added_successfully'));
        return redirect()->back();
    }
}
