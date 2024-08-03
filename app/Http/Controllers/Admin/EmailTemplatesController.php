<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\Repositories\TranslationRepositoryInterface;
use App\Enums\ViewPaths\Admin\EmailTemplate;
use App\Http\Controllers\BaseController;
use App\Repositories\BusinessSettingRepository;
use App\Repositories\EmailTemplatesRepository;
use App\Repositories\SocialMediaRepository;
use App\Services\EmailTemplateService;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class   EmailTemplatesController extends BaseController
{
    public function __construct(
        private readonly EmailTemplatesRepository $emailTemplatesRepo,
        private readonly EmailTemplateService $emailTemplateService,
        private readonly BusinessSettingRepository $businessSettingRepo,
        private readonly SocialMediaRepository $socialMediaRepo,
        private readonly TranslationRepositoryInterface $translationRepo,
    )
    {
    }
    public function index(Request|null $request, string $type = null): View
    {
        $socialMedia = $this->socialMediaRepo->getListWhere(filters:['status'=>1],dataLimit: 'all');

        return view('email-templates.mail-tester',compact('socialMedia'));
    }
    public function getView($userType,$templateName):View
    {
        $this->getEmailTemplateForData(userType: $userType);
        $language = $this->businessSettingRepo->getFirstWhere(params: ['type' => 'pnc_language']);
        $socialMedia = $this->socialMediaRepo->getListWhere(filters:['status'=>1],dataLimit: 'all');
        $template = $this->emailTemplatesRepo->getFirstWhere(params:['user_type'=>$userType,'template_name'=>$templateName],relations: ['translations','translationCurrentLanguage']);
        return view(EmailTemplate::VIEW[VIEW],compact('template','language','socialMedia'));
    }
    protected function getEmailTemplateForData($userType):Collection
    {
        $emailTemplates = $this->emailTemplatesRepo->getListWhere(filters:['user_type'=>$userType]);
        $emailTemplateArray = $this->emailTemplateService->getEmailTemplateData(userType: $userType);
        foreach ($emailTemplateArray as $value ){
            $checkKey = $emailTemplates->where('template_name',$value)->first();
            if($checkKey === null){
                $hideField = $this->emailTemplateService->getHiddenField(userType:$userType,templateName:$value);
                $title = $this->emailTemplateService->getTitleData(userType:$userType,templateName:$value);
                $body = $this->emailTemplateService->getBodyData(userType:$userType,templateName:$value);
                $this->emailTemplatesRepo->add(
                    data: $this->emailTemplateService->getAddData(userType: $userType, templateName: $value,hideField:$hideField,title: $title,body: $body)
                );
            }
        }
        foreach ($emailTemplates as $value ){
            if (!in_array($value['template_name'], $emailTemplateArray)) {
                $this->emailTemplatesRepo->delete(params: ['id' => $value['id']]);
            }
        }
        return $this->emailTemplatesRepo->getListWhere(filters:['user_type'=>$userType]);
    }
    public function update(Request $request ,$templateName,$userType):RedirectResponse
    {
        $emailTemplate = $this->emailTemplatesRepo->getFirstWhere(params:['template_name'=>$templateName,'user_type'=>$userType]);
        $this->emailTemplatesRepo->update(id:$emailTemplate['id'],data: $this->emailTemplateService->getUpdateData(request:$request,template: $emailTemplate));
        $requestNames  = ['title','body','button_name','footer_text','copyright_text'];
        $this->addTranslateData(lang:$request['lang'],requestNames: $requestNames,id:$emailTemplate['id'],request: $request);
        Toastr::success(translate('update_successfully'));
        return redirect()->back();
    }
    protected function addTranslateData($lang,$requestNames,$id,$request):void
    {
        foreach ($lang as $index => $value) {
            foreach ($requestNames as $key => $name){
                if ($request->$name && $request->$name[$value] && $value != 'en') {
                    $this->translationRepo->updateData(
                        model: 'App\Models\EmailTemplate',
                        id: $id,
                        lang: $value,
                        key: $name,
                        value: $request->$name[$value],
                    );
                }
            }
        }
    }
    public function updateStatus(Request $request ,$templateName,$userType):JsonResponse
    {
        $emailTemplate = $this->emailTemplatesRepo->getFirstWhere(params:['template_name'=>$templateName,'user_type'=>$userType]);
        $this->emailTemplatesRepo->update(id:$emailTemplate['id'],data:['status'=> $request->get('status', 0)]);
        return response()->json([
            'message' => $request->has('value') && $request['value'] ? translate($emailTemplate.'_mail_is_on') : translate($emailTemplate.'_mail_is_off'),
            'success'=>200
        ]);
    }
}
