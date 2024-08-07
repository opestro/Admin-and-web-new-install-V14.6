<?php

namespace App\Http\Controllers\Web;

use App\Contracts\Repositories\BusinessSettingRepositoryInterface;
use App\Contracts\Repositories\HelpTopicRepositoryInterface;
use App\Contracts\Repositories\RobotsMetaContentRepositoryInterface;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class PageController extends Controller
{
    public function __construct(
        private readonly BusinessSettingRepositoryInterface   $businessSettingRepo,
        private readonly HelpTopicRepositoryInterface         $helpTopicRepo,
        private readonly RobotsMetaContentRepositoryInterface $robotsMetaContentRepo,
    )
    {
    }

    public function getAboutUsView(): View
    {
        $robotsMetaContentData = $this->robotsMetaContentRepo->getFirstWhere(params: ['page_name' => 'about-us']);
        if (!$robotsMetaContentData) {
            $robotsMetaContentData = $this->robotsMetaContentRepo->getFirstWhere(params: ['page_name' => 'default']);
        }
        $aboutUs = getWebConfig(name: 'about_us');
        $pageTitleBanner = $this->businessSettingRepo->whereJsonContains(params: ['type' => 'banner_about_us'], value: ['status' => '1']);
        return view(VIEW_FILE_NAMES['about_us'], compact('aboutUs', 'pageTitleBanner', 'robotsMetaContentData'));
    }

    public function getContactView(): View
    {
        $robotsMetaContentData = $this->robotsMetaContentRepo->getFirstWhere(params: ['page_name' => 'contacts']);
        if (!$robotsMetaContentData) {
            $robotsMetaContentData = $this->robotsMetaContentRepo->getFirstWhere(params: ['page_name' => 'default']);
        }
        $recaptcha = getWebConfig(name: 'recaptcha');
        return view(VIEW_FILE_NAMES['contacts'], compact('recaptcha', 'robotsMetaContentData'));
    }

    public function getHelpTopicView(): View
    {
        $robotsMetaContentData = $this->robotsMetaContentRepo->getFirstWhere(params: ['page_name' => 'helpTopic']);
        if (!$robotsMetaContentData) {
            $robotsMetaContentData = $this->robotsMetaContentRepo->getFirstWhere(params: ['page_name' => 'default']);
        }
        $helps = $this->helpTopicRepo->getListWhere(orderBy: ['id' => 'desc'], filters: ['status' => 1, 'type' => 'default'], dataLimit: 'all');
        $pageTitleBanner = $this->businessSettingRepo->whereJsonContains(params: ['type' => 'banner_faq_page'], value: ['status' => '1']);
        return view(VIEW_FILE_NAMES['faq'], compact('helps', 'pageTitleBanner', 'robotsMetaContentData'));
    }

    public function getRefundPolicyView(): View|RedirectResponse
    {
        $robotsMetaContentData = $this->robotsMetaContentRepo->getFirstWhere(params: ['page_name' => 'refund-policy']);
        if (!$robotsMetaContentData) {
            $robotsMetaContentData = $this->robotsMetaContentRepo->getFirstWhere(params: ['page_name' => 'default']);
        }
        $refundPolicy = getWebConfig(name: 'refund-policy');
        if (!$refundPolicy['status']) {
            return back();
        }
        $pageTitleBanner = $this->businessSettingRepo->whereJsonContains(params: ['type' => 'banner_refund_policy'], value: ['status' => '1']);
        return view(VIEW_FILE_NAMES['refund_policy_page'], compact('refundPolicy', 'pageTitleBanner', 'robotsMetaContentData'));
    }

    public function getReturnPolicyView(): View|RedirectResponse
    {
        $robotsMetaContentData = $this->robotsMetaContentRepo->getFirstWhere(params: ['page_name' => 'return-policy']);
        if (!$robotsMetaContentData) {
            $robotsMetaContentData = $this->robotsMetaContentRepo->getFirstWhere(params: ['page_name' => 'default']);
        }
        $returnPolicy = getWebConfig(name: 'return-policy');
        if (!$returnPolicy['status']) {
            return back();
        }
        $pageTitleBanner = $this->businessSettingRepo->whereJsonContains(params: ['type' => 'banner_return_policy'], value: ['status' => '1']);
        return view(VIEW_FILE_NAMES['return_policy_page'], compact('returnPolicy', 'pageTitleBanner', 'robotsMetaContentData'));
    }

    public function getPrivacyPolicyView(): View
    {
        $robotsMetaContentData = $this->robotsMetaContentRepo->getFirstWhere(params: ['page_name' => 'privacy-policy']);
        if (!$robotsMetaContentData) {
            $robotsMetaContentData = $this->robotsMetaContentRepo->getFirstWhere(params: ['page_name' => 'default']);
        }
        $privacyPolicy = getWebConfig(name: 'privacy_policy');
        $pageTitleBanner = $this->businessSettingRepo->whereJsonContains(params: ['type' => 'banner_privacy_policy'], value: ['status' => '1']);
        return view(VIEW_FILE_NAMES['privacy_policy_page'], compact('privacyPolicy', 'pageTitleBanner', 'robotsMetaContentData'));
    }

    public function getCancellationPolicyView(): View|RedirectResponse
    {
        $robotsMetaContentData = $this->robotsMetaContentRepo->getFirstWhere(params: ['page_name' => 'cancellation-policy']);
        if (!$robotsMetaContentData) {
            $robotsMetaContentData = $this->robotsMetaContentRepo->getFirstWhere(params: ['page_name' => 'default']);
        }
        $cancellationPolicy = getWebConfig(name: 'cancellation-policy');
        if (!$cancellationPolicy['status']) {
            return back();
        }
        $pageTitleBanner = $this->businessSettingRepo->whereJsonContains(params: ['type' => 'banner_cancellation_policy'], value: ['status' => '1']);
        return view(VIEW_FILE_NAMES['cancellation_policy_page'], compact('cancellationPolicy', 'pageTitleBanner', 'robotsMetaContentData'));
    }

    public function getTermsAndConditionView(): View
    {
        $robotsMetaContentData = $this->robotsMetaContentRepo->getFirstWhere(params: ['page_name' => 'terms']);
        if (!$robotsMetaContentData) {
            $robotsMetaContentData = $this->robotsMetaContentRepo->getFirstWhere(params: ['page_name' => 'default']);
        }
        $termsCondition = getWebConfig(name: 'terms_condition');
        $pageTitleBanner = $this->businessSettingRepo->whereJsonContains(params: ['type' => 'banner_terms_conditions'], value: ['status' => '1']);
        return view(VIEW_FILE_NAMES['terms_conditions_page'], compact('termsCondition', 'pageTitleBanner', 'robotsMetaContentData'));
    }

}
