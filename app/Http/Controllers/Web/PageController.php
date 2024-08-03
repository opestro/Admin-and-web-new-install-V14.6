<?php

namespace App\Http\Controllers\Web;

use App\Contracts\Repositories\BusinessSettingRepositoryInterface;
use App\Contracts\Repositories\HelpTopicRepositoryInterface;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class PageController extends Controller
{
    public function __construct(
        private readonly BusinessSettingRepositoryInterface $businessSettingRepo,
        private readonly HelpTopicRepositoryInterface       $helpTopicRepo,
    )
    {
    }

    public function getAboutUsView(): View
    {
        $aboutUs = getWebConfig(name: 'about_us');
        $pageTitleBanner = $this->businessSettingRepo->whereJsonContains(params: ['type' => 'banner_about_us'], value: ['status' => '1']);
        return view(VIEW_FILE_NAMES['about_us'], compact('aboutUs', 'pageTitleBanner'));
    }

    public function getContactView(): View
    {
        $recaptcha = getWebConfig(name: 'recaptcha');
        return view(VIEW_FILE_NAMES['contacts'], compact('recaptcha'));
    }

    public function getHelpTopicView(): View
    {
        $helps = $this->helpTopicRepo->getListWhere(orderBy: ['id' => 'desc'], filters: ['status' => 1,'type'=>'default'], dataLimit: 'all');
        $pageTitleBanner = $this->businessSettingRepo->whereJsonContains(params: ['type' => 'banner_faq_page'], value: ['status' => '1']);
        return view(VIEW_FILE_NAMES['faq'], compact('helps', 'pageTitleBanner'));
    }

    public function getRefundPolicyView(): View|RedirectResponse
    {
        $refundPolicy = getWebConfig(name: 'refund-policy');
        if (!$refundPolicy['status']) {return back();}
        $pageTitleBanner = $this->businessSettingRepo->whereJsonContains(params: ['type' => 'banner_refund_policy'], value: ['status' => '1']);
        return view(VIEW_FILE_NAMES['refund_policy_page'], compact('refundPolicy', 'pageTitleBanner'));
    }

    public function getReturnPolicyView(): View|RedirectResponse
    {
        $returnPolicy = getWebConfig(name: 'return-policy');
        if (!$returnPolicy['status']) {return back();}
        $pageTitleBanner = $this->businessSettingRepo->whereJsonContains(params: ['type' => 'banner_return_policy'], value: ['status' => '1']);
        return view(VIEW_FILE_NAMES['return_policy_page'], compact('returnPolicy', 'pageTitleBanner'));
    }

    public function getPrivacyPolicyView(): View
    {
        $privacyPolicy = getWebConfig(name: 'privacy_policy');
        $pageTitleBanner = $this->businessSettingRepo->whereJsonContains(params: ['type' => 'banner_privacy_policy'], value: ['status' => '1']);
        return view(VIEW_FILE_NAMES['privacy_policy_page'], compact('privacyPolicy', 'pageTitleBanner'));
    }

    public function getCancellationPolicyView(): View|RedirectResponse
    {
        $cancellationPolicy = getWebConfig(name: 'cancellation-policy');
        if (!$cancellationPolicy['status']) {return back();}
        $pageTitleBanner = $this->businessSettingRepo->whereJsonContains(params: ['type' => 'banner_cancellation_policy'], value: ['status' => '1']);
        return view(VIEW_FILE_NAMES['cancellation_policy_page'], compact('cancellationPolicy', 'pageTitleBanner'));
    }

    public function getTermsAndConditionView(): View
    {
        $termsCondition = getWebConfig(name: 'terms_condition');
        $pageTitleBanner = $this->businessSettingRepo->whereJsonContains(params: ['type' => 'banner_terms_conditions'], value: ['status' => '1']);
        return view(VIEW_FILE_NAMES['terms_conditions_page'], compact('termsCondition', 'pageTitleBanner'));
    }

}
