<?php

namespace App\Http\Services;

use App\Http\Repositories\AdminSettingRepository;
use App\Http\Services\SmsMail\TwilioService;
use App\Mail\CustomizeMail;
use App\Models\Alumni;
use Illuminate\Support\Str;
use App\Models\EmailTemplate;
use App\Models\Gateway;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Arr;
use App\Models\License;
use App\Models\MailHistory;
use App\Models\Plan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SettingsService
{
    use ResponseTrait;
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

    public function smsConfigurationStore($request)
    {
        $inputs = Arr::except($request->all(), ['_token']);

        foreach ($inputs as $key => $value) {

            $option = Setting::firstOrCreate(['option_key' => $key]);
            $option->option_value = $value;
            $option->save();
        }

        return $this->success([], getMessage(UPDATED_SUCCESSFULLY));
    }

    public function smsTest($request)
    {
        try {
            $phoneNumber = trim($request->get('to'));
            $smsText = trim($request->get('message'));

            $sendSmsStatus = TwilioService::sendSms($phoneNumber, '', $smsText);
            if ($sendSmsStatus == true) {

                return $this->success([], __("Test sms has been sent to your phone number"));
            } else {
                return $this->error([], __("Something went wrong,please check your phone number"));
            }
        } catch (Exception $exception) {
            return $this->error([], getMessage(SOMETHING_WENT_WRONG));
        }
    }

}
