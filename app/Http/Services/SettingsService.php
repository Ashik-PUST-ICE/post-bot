<?php

namespace App\Http\Services;

use App\Mail\TestEmailSend;
use App\Models\EmailTemplate;
use App\Models\MailHistory;
use App\Models\Setting;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class SettingsService
{
    use ResponseTrait;

    public static function sentMailHistoryStore($user_id, $email, $subject, $content, $status, $error = null)
    {
        $history = new MailHistory();
        $history->user_id = $user_id;
        $history->host = env('MAIL_HOST');
        $history->email = $email;
        $history->subject = $subject;
        $history->content = $content;
        $history->status = $status;
        $history->date = now();
        $history->error = $error;
        $history->save();
    }

    public function cookieSettingUpdated($request)
    {
        $inputs = Arr::except($request->all(), ['_token']);

        foreach ($inputs as $key => $value) {
            $option = Setting::firstOrCreate(['option_key' => $key]);
            if ($request->hasFile('cookie_image') && $key == 'cookie_image') {
                $upload = settingImageStoreUpdate($value, $request->cookie_image);
                $option->option_value = $upload;
                $option->save();
            } else {
                $option->option_value = $value;
                $option->save();
            }
        }

        return $this->success([], getMessage(UPDATED_SUCCESSFULLY));
    }

    public function commonSettingUpdate($request)
    {
        $inputs = Arr::except($request->all(), ['_token']);
        foreach ($inputs as $key => $value) {
            $option = Setting::firstOrCreate(['option_key' => $key]);
            if ($request->hasFile('cookie_image') && $key == 'cookie_image') {
                $upload = settingImageStoreUpdate($value, $request->cookie_image);
                $option->option_value = $upload;
                $option->save();
            } else {
                $option->option_value = $value;
                $option->save();
            }
        }

        return $this->success([], getMessage(UPDATED_SUCCESSFULLY));
    }

    public function getEmailTemplate($tenant_id = null)
    {
        return EmailTemplate::where('tenant_id', $tenant_id)->get();
    }

    public function emailTemplateConfig($request)
    {
        try {
            $data['template'] = EmailTemplate::where('tenant_id', auth()->user()->tenant_id)->find($request->id);
            $data['fields'] = \customNotifyTempFields($data['template']->slug);
            return $this->success($data);
        } catch (Exception $e) {
            return $this->error([], $e->getMessage());
        }
    }

    public function findById($id)
    {
        return EmailTemplate::where('tenant_id', auth()->user()->tenant_id)->findOrFail($id);
    }

    public function sendPreviewEmail($request, $id)
    {
        try {
            $template = $request->email;
            $data = getEmailTemplateById($id, []);
            dispatch(new TestEmailSend($template, $data['subject'], $data['content']));

            return redirect()->back()->with('success', 'Test email sent successfully');
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error sending test email');
        }
    }

    public function emailTemplateConfigUpdate($request)
    {
        DB::beginTransaction();
        try {
            $emailTemplate = EmailTemplate::where('tenant_id', auth()->user()->tenant_id)->findOrFail($request->id);
            $emailTemplate->title = $request->title;
            $emailTemplate->subject = $request->subject;
            $emailTemplate->body = $request->body;
            $emailTemplate->save();

            DB::commit();
            return $this->success([], __(UPDATED_SUCCESSFULLY));
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error([], $e->getMessage());
        }
    }

    public function emailTemplateStatus($request)
    {
        DB::beginTransaction();
        try {
            $template = EmailTemplate::where('category', $request->category)->where('user_id', auth()->id())->first();
            if ($template && $template->subject) {
                $status = $template->status == ACTIVE ? DEACTIVATE : ACTIVE;
                $template->status = $status;
                $template->save();
            } else {
                throw new Exception(__('Please Config Email Template'));
            }
            DB::commit();
            return $this->success([], __(STATUS_UPDATED_SUCCESSFULLY));
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error([], $e->getMessage());
        }
    }
}
