<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Services\SettingsService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class EmailTemplateController extends Controller
{
    use ResponseTrait;
    public $settingsService;

    public function __construct()
    {
        $this->settingsService = new SettingsService();
    }

    public function emailTemplate()
    {
        $data['title'] = __('Email Template');
        $data['showManageApplicationSetting'] = 'show';
        $data['pageTitle'] = __('Email Template');
        $data['activeEmailSetting'] = 'active';

        $data['emailTemplates'] = $this->settingsService->getEmailTemplate(auth()->user()->tenant_id);
        return view('admin.setting.email_temp.email-temp', $data);
    }

    public function emailTemplateConfig(Request $request)
    {
        return $this->settingsService->emailTemplateConfig($request);
    }

    public function emailTemplateConfigUpdate(Request $request)
    {
        return $this->settingsService->emailTemplateConfigUpdate($request);
    }

    public function previewMailTest($id)
    {
        $data['template'] =  $this->settingsService->findById($id);
        $data['details'] = $data['template']->body;
        return view('admin.setting.email_temp.test-mail-view-model', $data);
    }

    public function sendTestMail(Request $request, $id)
    {
        return $this->settingsService->sendPreviewEmail($request, $id);
    }

}
