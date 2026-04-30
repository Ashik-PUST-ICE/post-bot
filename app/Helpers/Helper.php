<?php

use App\Mail\CustomEmailNotify;
use App\Models\Currency;
use App\Models\Department;
use App\Models\EmailTemplate;
use App\Models\EmployeeDetails;
use App\Models\FileManager;
use App\Models\KpiSessionApprovalTree;
use App\Models\Language;
use App\Models\Meta;
use App\Models\Notification;
use App\Models\NotificationTemplates;
use App\Models\SmartSetting;
use App\Models\User;
use App\Models\UserPackage;
use Jenssegers\Agent\Agent;
use App\Models\UserActivityLog;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Gateway;
use App\Models\Package;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;

if (!function_exists("getOption")) {
    function getOption($option_key, $default = NULL)
    {
        $system_settings = config('settings');

        if ($option_key && isset($system_settings[$option_key])) {
            return $system_settings[$option_key];
        } else {
            return $default;
        }
    }
}

if (!function_exists('setOption')) {
    /**
     * Persist a single key→value pair to the settings table and refresh the config cache.
     */
    function setOption(string $option_key, $option_value): void
    {
        $row = \App\Models\Setting::firstOrCreate(['option_key' => $option_key]);
        $row->option_value = $option_value;
        $row->save();

        // Refresh the in-memory config so getOption() reflects the new value
        $all = \App\Models\Setting::pluck('option_value', 'option_key')->toArray();
        config(['settings' => $all]);
    }
}

function getSettingImage($option_key)
{

    if ($option_key && $option_key != null) {

        $setting = Setting::where('option_key', $option_key)->first();
        if (isset($setting->option_value) && isset($setting->option_value) != null) {

            $file = FileManager::select('path', 'storage_type')->find($setting->option_value);


            if (!is_null($file)) {
                if (Storage::disk($file->storage_type)->exists($file->path)) {

                    if ($file->storage_type == 'public') {
                        return asset('storage/' . $file->path);
                    }
                    return Storage::disk($file->storage_type)->url($file->path);
                }
            }
        }
    }
    return asset('assets/images/no-image.jpg');
}

function settingImageStoreUpdate($option_value, $requestFile)
{

    if ($requestFile) {

        /*File Manager Call upload*/
        if ($option_value && $option_value != null) {
            $new_file = FileManager::where('id', $option_value)->first();

            if ($new_file) {
                $new_file->removeFile();
                $uploaded = $new_file->upload('Setting', $requestFile, '', $new_file->id);
            } else {
                $new_file = new FileManager();
                $uploaded = $new_file->upload('Setting', $requestFile);
            }
        } else {
            $new_file = new FileManager();
            $uploaded = $new_file->upload('Setting', $requestFile);
        }

        /*End*/

        return $uploaded->id;
    }

    return null;
}


if (!function_exists("getDefaultImage")) {
    function getDefaultImage()
    {
        // return asset('assets/images/no-image.jpg');
        return asset('assets/images/icon/upload-img-1.svg');
    }
}
if (!function_exists("dateFormat")) {
    function dateFormat($date)
    {
        return date('Y-m-d', strtotime($date));
    }
}

if (!function_exists("getDefaultLanguage")) {
    function getDefaultLanguage()
    {
        $language = Language::where('default', STATUS_ACTIVE)->first();
        if ($language) {
            $iso_code = $language->iso_code;
            return $iso_code;
        }

        return 'en';
    }
}

if (!function_exists("getCurrencySymbol")) {
    function getCurrencySymbol()
    {
        $currency = Currency::where('current_currency', STATUS_ACTIVE)->first();
        if ($currency) {
            $symbol = $currency->symbol;
            return $symbol;
        }

        return '';
    }
}

if (!function_exists("getIsoCode")) {
    function getIsoCode()
    {
        $currency = Currency::where('current_currency', STATUS_ACTIVE)->first();
        if ($currency) {
            $currency_code = $currency->currency_code;
            return $currency_code;
        }

        return '';
    }
}

if (!function_exists("getCurrencyPlacement")) {
    function getCurrencyPlacement()
    {
        $currency = Currency::where('current_currency', STATUS_ACTIVE)->first();
        $placement = 'before';
        if ($currency) {
            $placement = $currency->symbol;
            return $placement;
        }

        return $placement;
    }
}

if (!function_exists("showPrice")) {
    function showPrice($price)
    {
        $price = getNumberFormat($price);
        if (config('app.currencyPlacement') == 'after') {
            return $price . config('app.currencySymbol');
        } else {
            return config('app.currencySymbol') . $price;
        }
    }
}


if (!function_exists("getNumberFormat")) {
    function getNumberFormat($amount)
    {
        return number_format($amount, 2, '.', '');
    }
}

if (!function_exists("decimalToInt")) {
    function decimalToInt($amount)
    {
        return number_format(number_format($amount, 2, '.', '') * 100, 0, '.', '');
    }
}

if (!function_exists("intToDecimal")) {
}
function intToDecimal($amount)
{
    return number_format($amount / 100, 2, '.', '');
}

if (!function_exists("appLanguages")) {
    function appLanguages()
    {
        return Language::where('status', 1)->get();
    }
}

if (!function_exists("selectedLanguage")) {
    function selectedLanguage()
    {
        $language = Language::where('iso_code', session()->get('local'))->first();
        if (!$language) {
            $language = Language::first();
            if ($language) {
                $ln = $language->iso_code;
                session(['local' => $ln]);
                App::setLocale(session()->get('local'));
            }
        }

        return $language;
    }
}

if (!function_exists("getVideoFile")) {
    function getFile($path, $storageType)
    {
        if (!is_null($path)) {
            if (Storage::disk($storageType)->exists($path)) {

                if ($storageType == 'public') {
                    return asset('storage/' . $path);
                }

                if ($storageType == 'wasabi') {
                    return Storage::disk('wasabi')->url($path);
                }


                return Storage::disk($storageType)->url($path);
            }
        }

        return asset('assets/images/no-image.jpg');
    }
}

if (!function_exists("notificationForUser")) {
    function notificationForUser()
    {
        $instructor_notifications = \App\Models\Notification::where('user_id', auth()->user()->id)->where('user_type', 2)->where('is_seen', 'no')->orderBy('created_at', 'DESC')->get();
        $student_notifications = \App\Models\Notification::where('user_id', auth()->user()->id)->where('user_type', 3)->where('is_seen', 'no')->orderBy('created_at', 'DESC')->get();
        return array('instructor_notifications' => $instructor_notifications, 'student_notifications' => $student_notifications);
    }
}

if (!function_exists("adminNotifications")) {
    function adminNotifications()
    {
        return \App\Models\Notification::where('user_type', 1)->where('is_seen', 'no')->orderBy('created_at', 'DESC')->paginate(5);
    }
}

if (!function_exists('getSlug')) {
    function getSlug($text)
    {
        if ($text) {
            $data = preg_replace("/[~`{}.'\"\!\@\#\$\%\^\&\*\(\)\_\=\+\/\?\>\<\,\[\]\:\;\|\\\]/", "", $text);
            $slug = preg_replace("/[\/_|+ -]+/", "-", $data);
            return $slug;
        }
        return '';
    }
}


if (!function_exists('getCustomerCurrentBuildVersion')) {
    function getCustomerCurrentBuildVersion()
    {
        $buildVersion = getOption('build_version');

        if (is_null($buildVersion)) {
            return 1;
        }

        return (int)$buildVersion;
    }
}

if (!function_exists('setCustomerBuildVersion')) {
    function setCustomerBuildVersion($version)
    {
        $option = Setting::firstOrCreate(['option_key' => 'build_version']);
        $option->option_value = $version;
        $option->save();
    }
}

if (!function_exists('setCustomerCurrentVersion')) {
    function setCustomerCurrentVersion()
    {
        $option = Setting::firstOrCreate(['option_key' => 'current_version']);
        $option->option_value = config('app.current_version');
        $option->save();
    }
}


if (!function_exists('getAddonCodeBuildVersion')) {
    function getAddonCodeBuildVersion($appCode)
    {
        Artisan::call("config:clear");
        return config('addon.' . $appCode . '.build_version', 0);
    }
}

if (!function_exists('getCustomerAddonBuildVersion')) {
    function getCustomerAddonBuildVersion($code)
    {
        $buildVersion = getOption($code . '_build_version', 0);
        if (is_null($buildVersion)) {
            return 0;
        }
        return (int)$buildVersion;
    }
}

if (!function_exists('isAddonInstalled')) {
    function isAddonInstalled($code)
    {
        $buildVersion = getOption($code . '_build_version', 0);
        $codeBuildVersion = config('addon.' . $code . '.build_version', 0);
        if (is_null($buildVersion) || $codeBuildVersion == 0) {
            return 0;
        }
        return (int)$buildVersion;
    }
}

if (!function_exists('setCustomerAddonCurrentVersion')) {
    function setCustomerAddonCurrentVersion($code)
    {
        $option = Setting::firstOrCreate(['option_key' => $code . '_current_version']);
        if (config($code . '.current_version', 0) > 0) {
            $option->option_value = config($code . '.current_version', 0);
            $option->save();
        }
    }
}

if (!function_exists('setCustomerAddonBuildVersion')) {
    function setCustomerAddonBuildVersion($code, $version)
    {
        $option = Setting::firstOrCreate(['option_key' => $code . '_build_version']);
        $option->option_value = $version;
        $option->save();
    }
}

if (!function_exists('getDomainName')) {
    function getDomainName($url)
    {
        $parseUrl = parse_url(trim($url));
        if (isset($parseUrl['host'])) {
            $host = $parseUrl['host'];
        } else {
            $path = explode('/', $parseUrl['path']);
            $host = $path[0];
        }
        return trim($host);
    }
}

if (!function_exists('updateEnv')) {
    function updateEnv($values)
    {
        if (count($values) > 0) {
            foreach ($values as $envKey => $envValue) {
                setEnvironmentValue($envKey, $envValue);
            }
            return true;
        }
    }
}

if (!function_exists('setEnvironmentValue')) {
    function setEnvironmentValue($envKey, $envValue)
    {
        try {
            $envFile = app()->environmentFilePath();
            $str = file_get_contents($envFile);
            $str .= "\n"; // In case the searched variable is in the last line without \n
            $keyPosition = strpos($str, "{$envKey}=");
            if ($keyPosition) {
                if (PHP_OS_FAMILY === 'Windows') {
                    $endOfLinePosition = strpos($str, "\n", $keyPosition);
                } else {
                    $endOfLinePosition = strpos($str, PHP_EOL, $keyPosition);
                }
                $oldLine = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);
                $envValue = str_replace(chr(92), "\\\\", $envValue);
                $envValue = str_replace('"', '\"', $envValue);
                $newLine = "{$envKey}=\"{$envValue}\"";
                if ($oldLine != $newLine) {
                    $str = str_replace($oldLine, $newLine, $str);
                    $str = substr($str, 0, -1);
                    $fp = fopen($envFile, 'w');
                    fwrite($fp, $str);
                    fclose($fp);
                }
            } else if (strtoupper($envKey) == $envKey) {
                $envValue = str_replace(chr(92), "\\\\", $envValue);
                $envValue = str_replace('"', '\"', $envValue);
                $newLine = "{$envKey}=\"{$envValue}\"\n";
                $str .= $newLine;
                $str = substr($str, 0, -1);
                $fp = fopen($envFile, 'w');
                fwrite($fp, $str);
                fclose($fp);
            }
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}

if (!function_exists('base64urlEncode')) {
    function base64urlEncode($str)
    {
        return rtrim(strtr(base64_encode($str), '+/', '-_'), '=');
    }
}

if (!function_exists('getTimeZone')) {
    function getTimeZone()
    {
        return DateTimeZone::listIdentifiers(
            DateTimeZone::ALL
        );
    }
}

function getErrorMessage($e, $customMsg = null)
{
    if ($customMsg != null) {
        return $customMsg;
    }
    if (env('APP_DEBUG')) {
        return $e->getMessage() . $e->getLine();
    } else {
        return SOMETHING_WENT_WRONG;
    }
}

if (!function_exists('getFileUrl')) {
    function getFileUrl($id = null)
    {

        $file = FileManager::select('path', 'storage_type')->find($id);

        if (!is_null($file)) {
            if (Storage::disk($file->storage_type)->exists($file->path)) {

                if ($file->storage_type == 'public') {
                    return asset('storage/' . $file->path);
                }

                if ($file->storage_type == 'wasabi') {
                    return Storage::disk('wasabi')->url($file->path);
                }


                return Storage::disk($file->storage_type)->url($file->path);
            }
        }

        return asset('assets/images/no-image.jpg');
    }
}

if (!function_exists('getFileData')) {
    function getFileData($id, $property)
    {
        $file = FileManager::find($id);
        if ($file) {
            return $file->{$property};
        }
        return null;
    }
}

if (!function_exists('emailTemplateStatus')) {
    function emailTemplateStatus($category)
    {
        $status = EmailTemplate::where('category', $category)->where('user_id', auth()->id())->pluck('status')->first();
        if ($status) {
            return $status;
        }
        return DEACTIVATE;
    }
}


if (!function_exists('languageLocale')) {
    function languageLocale($locale)
    {
        $data = Language::where('code', $locale)->first();
        if ($data) {
            return $data->code;
        }
        return 'en';
    }
}


if (!function_exists('getUseCase')) {
    function getUseCase($useCase = [])
    {
        if (in_array("-1", $useCase)) {
            return __("All");
        }
        return count($useCase);
    }
}

function currentCurrency($attribute = '')
{
    $currentCurrency = Currency::where('current_currency', 1)->first();
    if (isset($currentCurrency->{$attribute})) {
        return $currentCurrency->{$attribute};
    }
    return '';
}

function currentCurrencyType()
{
    $currentCurrency = Currency::where('current_currency', 1)->first();
    return $currentCurrency->currency_code;
}

function currentCurrencyIcon()
{
    $currentCurrency = Currency::where('current_currency', 1)->first();
    return $currentCurrency->symbol;
}

// Convert currency
function convertCurrency($amount, $to = 'USD', $from = 'USD')
{
    //1-BTC-GBP
    try {
        $jsondata = "";

        $coinPriceInCurrency = Setting::where('option_key', 'COIN_PRICE_IN_CURRENCY_FOR' . $from)->first();


        if ($coinPriceInCurrency != null) {

            if ($coinPriceInCurrency->option_value == null) {
                $url = "https://min-api.cryptocompare.com/data/price?fsym=$from&tsyms=$to";
                $json = file_get_contents($url); //,FALSE,$ctx);
                $jsondata = json_decode($json, TRUE);

                $coinPriceInCurrency->option_value = $jsondata[$to];
                $coinPriceInCurrency->save();
            }

            $dateTime = Carbon::now()->addMinute(5);
            $currentTime = $dateTime->format('Y-m-d H:i:s');


            if (($coinPriceInCurrency->option_value != null) && (date('Y-m-d H:i:s', strtotime($coinPriceInCurrency->updated_at)) < $currentTime)) {
                $url = "https://min-api.cryptocompare.com/data/price?fsym=$from&tsyms=$to";
                $json = file_get_contents($url); //,FALSE,$ctx);
                $jsondata = json_decode($json, TRUE);

                $coinPriceInCurrency->option_value = $jsondata[$to];
                $coinPriceInCurrency->save();
            }
        } else {

            $url = "https://min-api.cryptocompare.com/data/price?fsym=$from&tsyms=$to";
            $json = file_get_contents($url); //,FALSE,$ctx);
            $jsondata = json_decode($json, TRUE);

            if ($jsondata != null) {
                $newObj = new Setting();
                $newObj->option_key = 'COIN_PRICE_IN_CURRENCY_FOR' . $from;
                $newObj->option_value = $jsondata[$to];
                $newObj->save();
            }
        }


        return [
            'total' => $amount * getOption('COIN_PRICE_IN_CURRENCY_FOR' . $from),
            'price' => getOption('COIN_PRICE_IN_CURRENCY_FOR' . $from)
        ];
    } catch (\Exception $e) {
        return [
            'total' => 0.00000000,
            'price' => 0.00000000
        ];
    }
}


function convertCurrencySwap($amount, $to = 'USD', $from = 'USD')
{
    try {
        $jsondata = "";

        $coinPriceInCurrency = Setting::where('option_key', 'COIN_PRICE_IN_CURRENCY_FOR' . $from)->first();
        if ($coinPriceInCurrency != null) {

            if ($coinPriceInCurrency->option_value == null) {
                $url = "https://min-api.cryptocompare.com/data/price?fsym=$from&tsyms=$to";
                $json = file_get_contents($url); //,FALSE,$ctx);
                $jsondata = json_decode($json, TRUE);

                $coinPriceInCurrency->option_value = $jsondata[$to];
                $coinPriceInCurrency->save();
            }

            $dateTime = Carbon::now()->addMinute(5);
            $currentTime = $dateTime->format('Y-m-d H:i:s');

            if (($coinPriceInCurrency->option_value != null) && (date('Y-m-d H:i:s', strtotime($coinPriceInCurrency->updated_at)) < $currentTime)) {
                $url = "https://min-api.cryptocompare.com/data/price?fsym=$from&tsyms=$to";
                $json = file_get_contents($url); //,FALSE,$ctx);
                $jsondata = json_decode($json, TRUE);

                $coinPriceInCurrency->option_value = $jsondata[$to];
                $coinPriceInCurrency->save();
            }
        } else {

            $url = "https://min-api.cryptocompare.com/data/price?fsym=$from&tsyms=$to";
            $json = file_get_contents($url); //,FALSE,$ctx);
            $jsondata = json_decode($json, TRUE);

            if ($jsondata != null) {
                $newObj = new Setting();
                $newObj->option_key = 'COIN_PRICE_IN_CURRENCY_FOR' . $from;
                $newObj->option_value = $jsondata[$to];
                $newObj->save();
            }
        }

        return [
            'total' => $amount * getOption('COIN_PRICE_IN_CURRENCY_FOR' . $from),
            'price' => getOption('COIN_PRICE_IN_CURRENCY_FOR' . $from)
        ];
    } catch (\Exception $e) {
        return [
            'total' => 0.00000000,
            'price' => 0.00000000
        ];
    }
}

function random_strings($length_of_string)
{
    $str_result = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz';
    return substr(str_shuffle($str_result), 0, $length_of_string);
}

function makeTenantId()
{
    $str_result = 'abcdefghijklmnopqrstuvwxyz';
    return substr(str_shuffle($str_result), 0, 8);
}

function broadcastPrivate($eventName, $broadcastData, $userId)
{
    //    $channelName = 'private-'.env("PUSHER_PRIVATE_CHANEL_NAME").'.' . customEncrypt($userId);
    //    dispatch(new BroadcastJob($channelName, $eventName, $broadcastData))->onQueue('broadcast-data');
}

function getUserId()
{
    try {
        return Auth::id();
    } catch (\Exception $e) {
        return 0;
    }
}


if (!function_exists('visual_number_format')) {
    function visual_number_format($value)
    {
        if (is_integer($value)) {
            return number_format($value, 2, '.', '');
        } elseif (is_string($value)) {
            $value = floatval($value);
        }
        $number = explode('.', number_format($value, 10, '.', ''));
        $intVal = (int)$value;
        if ($value > $intVal || $value < 0) {
            $intPart = $number[0];
            $floatPart = substr($number[1], 0, 8);
            $floatPart = rtrim($floatPart, '0');
            if (strlen($floatPart) < 2) {
                $floatPart = substr($number[1], 0, 2);
            }
            return $intPart . '.' . $floatPart;
        }
        return $number[0] . '.' . substr($number[1], 0, 2);
    }
}

function getError($e)
{
    if (env('APP_DEBUG')) {
        return " => " . $e->getMessage();
    }
    return '';
}

function notification($title = null, $body = null, $user_id = null, $link = null)
{
    try {
        $obj = new Notification();
        $obj->title = $title;
        $obj->body = $body;
        $obj->user_id = $user_id;
        $obj->link = $link;
        $obj->save();
        return "notification sent!";
    } catch (\Exception $e) {
        return "something error!";
    }
}

if (!function_exists('get_default_language')) {
    function get_default_language()
    {
        $language = Language::where('default', STATUS_ACTIVE)->first();
        if ($language) {
            $iso_code = $language->iso_code;
            return $iso_code;
        }

        return 'en';
    }
}

if (!function_exists('get_currency_symbol')) {
    function get_currency_symbol()
    {
        $currency = Currency::where('current_currency', STATUS_ACTIVE)->first();
        if ($currency) {
            $symbol = $currency->symbol;
            return $symbol;
        }

        return '';
    }
}

if (!function_exists('get_currency_code')) {
    function get_currency_code()
    {
        $currency = Currency::where('current_currency', STATUS_ACTIVE)->first();
        if ($currency) {
            $currency_code = $currency->currency_code;
            return $currency_code;
        }

        return '';
    }
}

if (!function_exists('get_currency_placement')) {
    function get_currency_placement()
    {
        $currency = Currency::where('current_currency', STATUS_ACTIVE)->first();
        $placement = 'before';
        if ($currency) {
            $placement = $currency->currency_placement;
            return $placement;
        }

        return $placement;
    }
}

if (!function_exists('customNumberFormat')) {
    function customNumberFormat($value)
    {
        $number = explode('.', $value);
        if (!isset($number[1])) {
            return number_format($value, 8, '.', '');
        } else {
            $result = substr($number[1], 0, 8);
            if (strlen($result) < 8) {
                $result = number_format($value, 8, '.', '');
            } else {
                $result = $number[0] . "." . $result;
            }

            return $result;
        }
    }
}


if (!function_exists('calculateFees')) {
    function calculateFees($amount, $feeMethod, $feePercentage, $feeFixed)
    {
        try {
            if ($feeMethod == 1) {
                return customNumberFormat($feeFixed);
            } elseif ($feeMethod == 2) {
                return customNumberFormat(bcdiv(bcmul($feePercentage, $amount), 100));
            } elseif ($feeMethod == 3) {
                return customNumberFormat(bcadd($feeFixed, bcdiv(bcmul($feePercentage, $amount), 100)));
            } else {
                return 0;
            }
        } catch (\Exception $e) {
            return 0;
        }
    }
}

if (!function_exists('trade_max_level')) {
    function trade_max_level()
    {
        return 5;
    }
}

if (!function_exists('reviewStar')) {
    function reviewStar($star)
    {
        $html = '';
        for ($i = 1; $i <= 5; $i++) {
            if ($i > $star) {
                $html .= '<li class="no-rating"><i class="fa-solid fa-star"></i></li>';
            } else {
                $html .= '<li><i class="fa-solid fa-star"></i></li>';
            }
        }
        return $html;
    }
}

if (!function_exists('getExistingEmployees')) {
    function getExistingEmployees($userId = null)
    {
        $userId = is_null($userId) ? auth()->id() : $userId;
        $userPackage = userCurrentPackage($userId);

        if (is_null($userPackage)) {
            return 0;
        } else {
            $totalCount = User::query()
                ->where('created_by', $userId)
                ->where('role', USER_ROLE_ADMIN)
                ->count();
            return $totalCount;
        }
    }
}

if (!function_exists('userCurrentPackage')) {
    function userCurrentPackage($userId)
    {
        return UserPackage::query()
            ->where('status', ACTIVE)
            ->where('user_id', $userId)
            ->whereDate('end_date', '>=', now()->toDateTimeString())
            ->first();
    }
}

if (!function_exists('getPackageOtherFields')) {
    function getPackageOtherFields($userId = null)
    {
        $userId = is_null($userId) ? auth()->id() : $userId;
        $userPackage = userCurrentPackage($userId);

        if (is_null($userPackage)) {
            return [];
        }
        $package = Package::find($userPackage->package_id);
        if (!$package) {
            return [];
        }
        $others = $package->others;
        return is_array($others) ? $others : [];
    }
}

if (!function_exists('getPerCoinRate')) {
    function getPerCoinRate($coin_type)
    {
        return convertCurrencySwap(1, $coin_type, currentCurrency('currency_code'))["price"];
    }
}


if (!function_exists('allsetting')) {
    function allsetting($keys = null)
    {

        if ($keys && is_array($keys)) {
            $settings = Setting::whereIn('option_key', $keys)->pluck('option_value', 'option_key')->toArray();
            $settingsNotFoundInDB = array_fill_keys(array_diff($keys, array_keys($settings)), false);
            if (!empty($settingsNotFoundInDB)) {
                $settings = array_merge($settings, $settingsNotFoundInDB);
            }
            return $settings;
        } elseif ($keys && is_string($keys)) {
            $setting = Setting::where('option_key', $keys)->first();
            return empty($setting) ? false : $setting->value;
        }
        return Setting::pluck('option_value', 'option_key')->toArray();
    }
}

if (!function_exists('getRandomDecimal')) {
    function getRandomDecimal($min, $max, $probabilityRatio)
    {
        // Calculate the adjusted maximum value based on the probability ratio
        $adjustedMax = $max + ($max - $min) * ($probabilityRatio - 1);

        // Generate a random decimal number within the range
        $randomDecimal = mt_rand($min * 10000, $adjustedMax * 10000) / 10000;

        // Check if the random decimal number needs to be adjusted
        if ($randomDecimal > $max) {
            // Set the number to the maximum value
            $randomDecimal = $max;
        }

        return $randomDecimal;
    }
}

if (!function_exists('getPlanEarningEstimation')) {
    function getPlanEarningEstimation($plan)
    {
        if ($plan->return_type == RETURN_TYPE_FIXED) {
            return $plan->return_amount_per_day . ' ' . $plan->coin->coin_type;
        } elseif ($plan->return_type == RETURN_TYPE_RANDOM) {
            return $plan->min_return_amount_per_day . ' ' . $plan->coin->coin_type . '-' . $plan->max_return_amount_per_day . ' ' . $plan->coin->coin_type;
        }
    }
}

if (!function_exists('privateUserNotification')) {
    function privateUserNotification()
    {
        return Notification::where('user_id', Auth::id())
            ->where('status', ACTIVE)
            ->orderBy('id', 'DESC')
            ->where('view_status', STATUS_PENDING)
            ->get();
    }
}
if (!function_exists('publicUserNotification')) {
    function publicUserNotification()
    {
        return Notification::where('user_id', null)
            ->where('status', ACTIVE)
            ->orderBy('id', 'DESC')
            ->where('view_status', STATUS_PENDING)
            ->get();
    }
}

function get_clientIp()
{
    return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
}

function humanFileSize($size, $unit = '')
{
    if ((!$unit && $size >= 1 << 30) || $unit == 'GB') {
        return number_format($size / (1 << 30), 2) . 'GB';
    }

    if ((!$unit && $size >= 1 << 20) || $unit == 'MB') {
        return number_format($size / (1 << 20), 2) . 'MB';
    }

    if ((!$unit && $size >= 1 << 10) || $unit == 'KB') {
        return number_format($size / (1 << 10), 2) . 'KB';
    }

    return number_format($size) . ' bytes';
}

if (!function_exists('getMeta')) {
    function getMeta($slug)
    {
        $metaData = [
            'meta_title' => null,
            'meta_description' => null,
            'meta_keyword' => null,
            'og_image' => null,
        ];

        $meta = Meta::where('slug', $slug)->select([
            'meta_title',
            'meta_description',
            'meta_keyword',
            'og_image',
        ])->first();

        if (!is_null($meta)) {
            $metaData = $meta->toArray();
        } else {
            $meta = Meta::where('slug', 'default')->select([
                'meta_title',
                'meta_description',
                'meta_keyword',
                'og_image',
            ])->first();

            if (!is_null($meta)) {
                $metaData = $meta->toArray();
            }
        }

        $metaData['meta_title'] = $metaData['meta_title'] != NULL ? $metaData['meta_title'] : getOption('app_name');
        $metaData['meta_description'] = $metaData['meta_description'] != NULL ? $metaData['meta_description'] : getOption('app_name');
        $metaData['meta_keyword'] = $metaData['meta_keyword'] != NULL ? $metaData['meta_keyword'] : getOption('app_name');
        $metaData['og_image'] = $metaData['og_image'] != NULL ? getFileUrl($metaData['og_image']) : getFileUrl(getOption('app_logo'));

        return $metaData;
    }
}

if (!function_exists('gatewaySettings')) {
    function gatewaySettings()
    {
        $settings = [
            "paypal" => [
                ["label" => "Url", "name" => "url", "is_show" => 0],
                ["label" => "Client ID", "name" => "key", "is_show" => 1],
                ["label" => "Secret", "name" => "secret", "is_show" => 1]
            ],
            "stripe" => [
                ["label" => "Url", "name" => "url", "is_show" => 0],
                ["label" => "Public Key", "name" => "key", "is_show" => 1],
                ["label" => "Secret Key", "name" => "secret", "is_show" => 1]
            ],
            "razorpay" => [
                ["label" => "Url", "name" => "url", "is_show" => 0],
                ["label" => "Key", "name" => "key", "is_show" => 1],
                ["label" => "Secret", "name" => "secret", "is_show" => 1]
            ],
            "instamojo" => [
                ["label" => "Url", "name" => "url", "is_show" => 0],
                ["label" => "Api Key", "name" => "key", "is_show" => 1],
                ["label" => "Auth Token", "name" => "secret", "is_show" => 1]
            ],
            "mollie" => [
                ["label" => "Url", "name" => "url", "is_show" => 0],
                ["label" => "Mollie Key", "name" => "key", "is_show" => 1],
                ["label" => "Secret", "name" => "secret", "is_show" => 0]
            ],
            "paystack" => [
                ["label" => "Url", "name" => "url", "is_show" => 0],
                ["label" => "Secret Key", "name" => "key", "is_show" => 1],
                ["label" => "Public Key", "name" => "secret", "is_show" => 0]
            ],
            "mercadopago" => [
                ["label" => "Url", "name" => "url", "is_show" => 0],
                ["label" => "Client ID", "name" => "key", "is_show" => 1],
                ["label" => "Client Secret", "name" => "secret", "is_show" => 1]
            ],
            "sslcommerz" => [
                ["label" => "Url", "name" => "url", "is_show" => 0],
                ["label" => "Store ID", "name" => "key", "is_show" => 1],
                ["label" => "Store Password", "name" => "secret", "is_show" => 1]
            ],
            "flutterwave" => [
                ["label" => "Hash", "name" => "url", "is_show" => 1],
                ["label" => "Public Key", "name" => "key", "is_show" => 1],
                ["label" => "Client Secret", "name" => "secret", "is_show" => 1]
            ],
            "coinbase" => [
                ["label" => "Hash", "name" => "url", "is_show" => 0],
                ["label" => "API Key", "name" => "key", "is_show" => 1],
                ["label" => "Client Secret", "name" => "secret", "is_show" => 0]
            ],
            "binance" => [
                ["label" => "Url", "name" => "url", "is_show" => 0],
                ["label" => "Client ID", "name" => "key", "is_show" => 1],
                ["label" => "Client Secret", "name" => "secret", "is_show" => 1]
            ],
            "bitpay" => [
                ["label" => "Url", "name" => "url", "is_show" => 0],
                ["label" => "Key", "name" => "key", "is_show" => 1],
                ["label" => "Client Secret", "name" => "secret", "is_show" => 0]
            ],
            "iyzico" => [
                ["label" => "Url", "name" => "url", "is_show" => 0],
                ["label" => "Key", "name" => "key", "is_show" => 1],
                ["label" => "Secret", "name" => "secret", "is_show" => 1]
            ],
            "payhere" => [
                ["label" => "Url", "name" => "url", "is_show" => 0],
                ["label" => "Merchant ID", "name" => "key", "is_show" => 1],
                ["label" => "Merchant Secret", "name" => "secret", "is_show" => 1]
            ],
            "maxicash" => [
                ["label" => "Url", "name" => "url", "is_show" => 0],
                ["label" => "Merchant ID", "name" => "key", "is_show" => 1],
                ["label" => "Password", "name" => "secret", "is_show" => 1]
            ],
            "paytm" => [
                ["label" => "Industry Type", "name" => "url", "is_show" => 1],
                ["label" => "Merchant Key", "name" => "key", "is_show" => 1],
                ["label" => "Merchant ID", "name" => "secret", "is_show" => 1]
            ],
            "zitopay" => [
                ["label" => "Industry Type", "name" => "url", "is_show" => 0],
                ["label" => "Key", "name" => "key", "is_show" => 1],
                ["label" => "Merchant ID", "name" => "secret", "is_show" => 0]
            ],
            "cinetpay" => [
                ["label" => "Industry Type", "name" => "url", "is_show" => 0],
                ["label" => "API Key", "name" => "key", "is_show" => 1],
                ["label" => "Site ID", "name" => "secret", "is_show" => 1]
            ],
            "voguepay" => [
                ["label" => "Industry Type", "name" => "url", "is_show" => 0],
                ["label" => "Merchant ID", "name" => "key", "is_show" => 1],
                ["label" => "Merchant ID", "name" => "secret", "is_show" => 0]
            ],
            "toyyibpay" => [
                ["label" => "Industry Type", "name" => "url", "is_show" => 0],
                ["label" => "Secret Key", "name" => "key", "is_show" => 1],
                ["label" => "Category Code", "name" => "secret", "is_show" => 1]
            ],
            "paymob" => [
                ["label" => "Industry Type", "name" => "url", "is_show" => 0],
                ["label" => "API Key", "name" => "key", "is_show" => 1],
                ["label" => "Integration ID", "name" => "secret", "is_show" => 1]
            ],
            "alipay" => [
                ["label" => "APP ID", "name" => "url", "is_show" => 1],
                ["label" => "Public Key", "name" => "key", "is_show" => 1],
                ["label" => "Private Key", "name" => "secret", "is_show" => 1]
            ],
            "authorize" => [
                ["label" => "Industry Type", "name" => "url", "is_show" => 0],
                ["label" => "Login ID", "name" => "key", "is_show" => 1],
                ["label" => "Transaction Key", "name" => "secret", "is_show" => 1]
            ],
            "xendit" => [
                ["label" => "APP ID", "name" => "url", "is_show" => 0],
                ["label" => "Public Key", "name" => "key", "is_show" => 1],
                ["label" => "Secret", "name" => "secret", "is_show" => 0]
            ],
            "paddle" => [
                ["label" => "Vendor Id", "name" => "url", "is_show" => 1],
                ["label" => "Vendor Auth Key", "name" => "key", "is_show" => 1],
                ["label" => "Secret", "name" => "secret", "is_show" => 0]
            ],
            "bkash" => [
                ["label" => "Username | Password (pipe-separated)", "name" => "url", "is_show" => 1],
                ["label" => "App Key", "name" => "key", "is_show" => 1],
                ["label" => "App Secret", "name" => "secret", "is_show" => 1]
            ],
            "nagad" => [
                ["label" => "Nagad Public Key (Base64)", "name" => "url", "is_show" => 1],
                ["label" => "Merchant ID", "name" => "key", "is_show" => 1],
                ["label" => "Merchant Private Key (Base64)", "name" => "secret", "is_show" => 1]
            ]
        ];

        return json_encode($settings);
    }
}

if (!function_exists("getGatewaySupportedCurrencies")) {
    function getGatewaySupportedCurrencies($gateway = null)
    {
        $supported_currencies = array(
            PAYPAL => [
                'AUD', 'BRL', 'CAD', 'CNY', 'CZK', 'DKK', 'EUR', 'HKD', 'HUF', 'INR',
                'ILS', 'JPY', 'MYR', 'MXN', 'TWD', 'NZD', 'NOK', 'PHP', 'PLN', 'GBP',
                'RUB', 'SGD', 'SEK', 'CHF', 'THB', 'USD', 'VND', 'ZAR'
            ],
            STRIPE => [
                // Comprehensive global currency support
                'AED', 'AFN', 'ALL', 'AMD', 'ANG', 'AOA', 'ARS', 'AUD', 'AWG', 'AZN',
                'BAM', 'BBD', 'BDT', 'BGN', 'BHD', 'BIF', 'BMD', 'BND', 'BOB', 'BRL',
                'BSD', 'BTN', 'BWP', 'BYN', 'BZD', 'CAD', 'CDF', 'CHF', 'CLP', 'CNY',
                'COP', 'CRC', 'CUC', 'CUP', 'CVE', 'CZK', 'DJF', 'DKK', 'DOP', 'DZD',
                'EGP', 'ERN', 'ETB', 'EUR', 'FJD', 'FKP', 'GBP', 'GEL', 'GGP', 'GHS',
                'GIP', 'GMD', 'GNF', 'GTQ', 'GYD', 'HKD', 'HNL', 'HRK', 'HTG', 'HUF',
                'IDR', 'ILS', 'IMP', 'INR', 'IQD', 'IRR', 'ISK', 'JEP', 'JMD', 'JOD',
                'JPY', 'KES', 'KGS', 'KHR', 'KMF', 'KPW', 'KRW', 'KWD', 'KYD', 'KZT',
                'LAK', 'LBP', 'LKR', 'LRD', 'LSL', 'LYD', 'MAD', 'MDL', 'MGA', 'MKD',
                'MMK', 'MNT', 'MOP', 'MRU', 'MUR', 'MVR', 'MWK', 'MXN', 'MYR', 'MZN',
                'NAD', 'NGN', 'NIO', 'NOK', 'NPR', 'NZD', 'OMR', 'PAB', 'PEN', 'PGK',
                'PHP', 'PKR', 'PLN', 'PYG', 'QAR', 'RON', 'RSD', 'RUB', 'RWF', 'SAR',
                'SBD', 'SCR', 'SDG', 'SEK', 'SGD', 'SHP', 'SLL', 'SOS', 'SRD', 'STN',
                'SVC', 'SYP', 'SZL', 'THB', 'TJS', 'TMT', 'TND', 'TOP', 'TRY', 'TTD',
                'TVD', 'TWD', 'TZS', 'UAH', 'UGX', 'USD', 'UYU', 'UZS', 'VES', 'VND',
                'VUV', 'WST', 'XAF', 'XCD', 'XOF', 'XPF', 'YER', 'ZAR', 'ZMW', 'ZWL'
            ],
            RAZORPAY => ['INR', 'USD', 'EUR', 'GBP', 'AED', 'AUD', 'CAD', 'SGD'],
            INSTAMOJO => ['INR'],
            MOLLIE => ['EUR', 'GBP', 'USD', 'CHF', 'PLN', 'SEK', 'NOK', 'DKK', 'AUD', 'CAD'],
            COINBASE => ['BTC', 'ETH', 'LTC', 'BCH', 'XRP', 'USDC', 'USDT', 'ADA', 'DOGE', 'MATIC', 'SHIB', 'APE', 'SOL', 'DOT', 'UNI', 'ATOM'],
            PAYSTACK => ['NGN', 'USD', 'ZAR', 'GHS', 'EUR', 'GBP'],
            SSLCOMMERZ => ['BDT', 'USD', 'INR', 'EUR', 'GBP'],
            MERCADOPAGO => ['ARS', 'BRL', 'CLP', 'COP', 'MXN', 'PEN', 'UYU', 'USD'],
            FLUTTERWAVE => ['NGN', 'USD', 'KES', 'GHS', 'ZAR', 'GBP', 'EUR'],
            IYZICO => ['TRY', 'USD', 'EUR', 'GBP'],
            BITPAY => ['BTC', 'BCH', 'ETH', 'USDT', 'DOGE', 'SHIB', 'LTC', 'WBTC', 'GUSD', 'USDC', 'DAI', 'EUROC'],
            ZITOPAY => ['USD', 'EUR', 'GBP', 'NGN'],  // Assuming major currencies based on typical global coverage
            BINANCE => ['BTC', 'ETH', 'BNB', 'USDT', 'BUSD', 'ADA', 'DOT', 'SOL'],  // Cryptocurrencies
            PAYTM => ['INR'],
            PAYHERE => ['LKR', 'USD', 'EUR', 'GBP'],
            MAXICASH => ['USD', 'XAF', 'XOF'],
            CINETPAY => ['XOF', 'XAF', 'EUR', 'USD'],
            VOGUEPAY => ['NGN', 'USD', 'GBP'],
            TOYYIBPAY => ['MYR'],
            PAYMOB => ['EGP'],
            AUTHORIZE => ['USD', 'CAD', 'GBP', 'EUR', 'AUD', 'NZD'],
            ALIPAY => [
                'CNY', 'USD', 'EUR', 'GBP', 'HKD', 'JPY', 'AUD', 'SGD', 'CAD', 'NZD',
                'KRW', 'THB'
            ],
            XENDIT => ['IDR', 'PHP', 'USD', 'VND', 'THB', 'MYR','SGD'],
            PADDLE => ['USD','EUR','GBP','AUD','CAD'],
            BKASH  => ['BDT'],
            NAGAD  => ['BDT'],
        );

        if (is_null($gateway)) {
            return $supported_currencies;
        } else {
            return $supported_currencies[$gateway] ?? [];
        }
    }
}

if (!function_exists('setUserGateway')) {
    function setUserGateway($tenantId, $userId = null)
    {
        $data = [
            ['user_id' => $userId, 'tenant_id' => $tenantId, 'title' => 'Paypal', 'slug' => 'paypal', 'image' => 'assets/images/gateway-icon/paypal.png', 'status' => ACTIVE, 'mode' => GATEWAY_MODE_SANDBOX, 'url' => '', 'key' => '', 'secret' => ''],
            ['user_id' => $userId, 'tenant_id' => $tenantId, 'title' => 'Stripe', 'slug' => 'stripe', 'image' => 'assets/images/gateway-icon/stripe.png', 'status' => ACTIVE, 'mode' => GATEWAY_MODE_SANDBOX, 'url' => '', 'key' => '', 'secret' => ''],
            ['user_id' => $userId, 'tenant_id' => $tenantId, 'title' => 'Razorpay', 'slug' => 'razorpay', 'image' => 'assets/images/gateway-icon/razorpay.png', 'status' => ACTIVE, 'mode' => GATEWAY_MODE_SANDBOX, 'url' => '', 'key' => '', 'secret' => ''],
            ['user_id' => $userId, 'tenant_id' => $tenantId, 'title' => 'Instamojo', 'slug' => 'instamojo', 'image' => 'assets/images/gateway-icon/instamojo.png', 'status' => ACTIVE, 'mode' => GATEWAY_MODE_SANDBOX, 'url' => '', 'key' => '', 'secret' => ''],
            ['user_id' => $userId, 'tenant_id' => $tenantId, 'title' => 'Mollie', 'slug' => 'mollie', 'image' => 'assets/images/gateway-icon/mollie.png', 'status' => ACTIVE, 'mode' => GATEWAY_MODE_SANDBOX, 'url' => '', 'key' => '', 'secret' => ''],
            ['user_id' => $userId, 'tenant_id' => $tenantId, 'title' => 'Paystack', 'slug' => 'paystack', 'image' => 'assets/images/gateway-icon/paystack.png', 'status' => ACTIVE, 'mode' => GATEWAY_MODE_SANDBOX, 'url' => '', 'key' => '', 'secret' => ''],
            ['user_id' => $userId, 'tenant_id' => $tenantId, 'title' => 'Sslcommerz', 'slug' => 'sslcommerz', 'image' => 'assets/images/gateway-icon/sslcommerz.png', 'status' => ACTIVE, 'mode' => GATEWAY_MODE_SANDBOX, 'url' => '', 'key' => '', 'secret' => ''],
            ['user_id' => $userId, 'tenant_id' => $tenantId, 'title' => 'Flutterwave', 'slug' => 'flutterwave', 'image' => 'assets/images/gateway-icon/flutterwave.png', 'status' => ACTIVE, 'mode' => GATEWAY_MODE_SANDBOX, 'url' => '', 'key' => '', 'secret' => ''],
            ['user_id' => $userId, 'tenant_id' => $tenantId, 'title' => 'Mercadopago', 'slug' => 'mercadopago', 'image' => 'assets/images/gateway-icon/mercadopago.png', 'status' => ACTIVE, 'mode' => GATEWAY_MODE_SANDBOX, 'url' => '', 'key' => '', 'secret' => ''],
            ['user_id' => $userId, 'tenant_id' => $tenantId, 'title' => 'Bank', 'slug' => 'bank', 'image' => 'assets/images/gateway-icon/bank.png', 'status' => ACTIVE, 'mode' => GATEWAY_MODE_SANDBOX, 'url' => '', 'key' => '', 'secret' => ''],
            ['user_id' => $userId, 'tenant_id' => $tenantId, 'title' => 'Cash', 'slug' => 'cash', 'image' => 'assets/images/gateway-icon/cash.png', 'status' => ACTIVE, 'mode' => GATEWAY_MODE_SANDBOX, 'url' => '', 'key' => '', 'secret' => ''],
            ['user_id' => $userId, 'tenant_id' => $tenantId, 'title' => 'bKash', 'slug' => 'bkash', 'image' => 'assets/images/gateway-icon/bkash.png', 'status' => ACTIVE, 'mode' => GATEWAY_MODE_SANDBOX, 'url' => '', 'key' => '', 'secret' => ''],
            ['user_id' => $userId, 'tenant_id' => $tenantId, 'title' => 'Nagad', 'slug' => 'nagad', 'image' => 'assets/images/gateway-icon/nagad.png', 'status' => ACTIVE, 'mode' => GATEWAY_MODE_SANDBOX, 'url' => '', 'key' => '', 'secret' => ''],
        ];
        Gateway::insert($data);
    }
}

if (!function_exists('setUserEmailTemplate')) {
    function setUserEmailTemplate($tenantId, $userId = null)
    {
        $data = [
            ['user_id' => $userId, 'tenant_id' => $tenantId, 'category' => 'Employee Create Notify', 'subject' => 'Employee Create Notify', 'title' => 'Employee Create Notify', 'slug' => 'employee-create-notify', 'body' => '<p>Hello, {{username}}, Thank you for creating an account with us. We\'re excited to have you as a part of our community! Before you can start using your account, we need to verify your email address. Please click on the link below to complete the verification process:</p>', 'default' => 1, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],

            ['user_id' => $userId, 'tenant_id' => $tenantId, 'category' => 'Department Head Assign Notify', 'subject' => 'Department Head Assign Notify', 'title' => 'Welcome,{username}', 'slug' => 'department-head-assign-notify', 'body' => '<p>Hello, {{username}}, Thank you for creating an account with us. We\'re excited to have you as a part of our community! Before you can start using your account, we need to verify your email address. Please click on the link below to complete the verification process:</p>', 'default' => 1, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],

            ['user_id' => $userId, 'tenant_id' => $tenantId, 'category' => 'Employee Session Assign Notify', 'subject' => 'Employee Session Assign Notify', 'title' => 'Welcome,{username}', 'slug' => 'employee-session-assign-notify', 'body' => '<p>Dear, {{username}}, We are pleased to inform you that you have been assigned to participate in a new session. This session is scheduled to take place on Date at time and will be conducted. Please click on the link below to complete the Goal process:</p>', 'default' => 1, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],

            ['user_id' => $userId, 'tenant_id' => $tenantId, 'category' => 'Employee Goal Submit Notify', 'subject' => 'Employee Goal Submit Notify', 'title' => 'Welcome,{username}', 'slug' => 'employee-goal-submit-notify', 'body' => '<p>Dear, {{username}}, I hope this message finds you well. This is a friendly reminder regarding the upcoming deadline for goal submission.Please take the time to review your objectives and submit your goals by the specified deadline. If you encounter any difficulties or have questions regarding the goal-setting process so please cancel your Goal. Please click on the link below to complete the process:</p>', 'default' => 1, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],

            ['user_id' => $userId, 'tenant_id' => $tenantId, 'category' => 'Goal Approved Notify', 'subject' => 'Goal Approved Notify', 'title' => 'Welcome,{username}, Your goal is Approved', 'slug' => 'goal-approved-notify-for-next-approval', 'body' => '<p>Hello, {{username}}, I am pleased to inform you that your proposed goals  have been reviewed and approved. Please click on the link and see your goal details:</p>', 'default' => 1, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],

            ['user_id' => $userId, 'tenant_id' => $tenantId, 'category' => 'Goal Approved Notify', 'subject' => 'Goal Approved Notify', 'title' => 'Welcome,{username}, Your goal is Approved', 'slug' => 'goal-approved-notify-for-goal-creator', 'body' => '<p>Hello, {{username}}, I am pleased to inform you that your proposed goals  have been reviewed and approved. Please click on the link and see your goal details:</p>', 'default' => 1, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],

            ['user_id' => $userId, 'tenant_id' => $tenantId, 'category' => 'Goal Back Notify', 'subject' => 'Goal Back Notify', 'title' => 'Welcome,{username}, Your goal is Back', 'slug' => 'goal-back-notify', 'body' => '<p>Hello, {{username}}, I hope this message finds you well. I am reaching out to inform you about the cancellation of the goal previously assigned to you and cearfully resubmit your goal. Please click on the link and see your goal details:</p>', 'default' => 1, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],

            ['user_id' => $userId, 'tenant_id' => $tenantId, 'category' => 'Goal Resubmit Notify', 'subject' => 'Goal Resubmit Notify', 'title' => 'Welcome,{username} Resubmit Goal', 'slug' => 'goal-resubmit-notify', 'body' => '<p>Hello, {{username}}, I hope this message finds you well. I am writing to remind you about the need to resubmit your Goal. Please click on the link and see your goal details:</p>', 'default' => 1, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],

            ['user_id' => $userId, 'tenant_id' => $tenantId, 'category' => 'Approval Process Date End Notify', 'subject' => 'Approval Process Date End Notify', 'title' => 'Welcome,{username}, Your approval process date is end', 'slug' => 'goal-final-approved', 'body' => '<p>Hello, {{username}}, I hope this message finds you well. This is to inform you that the approval process has now reached its scheduled end date. Please click on the link and see your approval process details:</p>', 'default' => 1, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
        ];
        EmailTemplate::insert($data);
    }
}

if (!function_exists('setDefaultPermission')) {
    function setDefaultPermission($tenantId, $userId = null)
    {
        $user = User::where('id', $userId)->first();
        $role = Role::find(1);
        $user->syncRoles($role->id);
    }
}


if (!function_exists('setUserNotifyTemplate')) {
    function setUserNotifyTemplate($tenantId, $userId = null)
    {
        $data = [
            ['user_id' => $userId, 'tenant_id' => $tenantId, 'subject' => 'Employee Create Notify', 'title' => 'Employee Create Notify', 'slug' => 'employee-create-notify', 'body' => '<p>Hello, {{username}}, Thank you for creating an account with us. We\'re excited to have you as a part of our community! Before you can start using your account, we need to verify your email address. Please click on the link below to complete the verification process:</p>',  'status' => 1, 'created_at' => now(), 'updated_at' => now()],

            ['user_id' => $userId, 'tenant_id' => $tenantId, 'subject' => 'Department Head Assign Notify', 'title' => 'Welcome,{username}', 'slug' => 'department-head-assign-notify', 'body' => '<p>Hello, {{username}}, Thank you for creating an account with us. We\'re excited to have you as a part of our community! Before you can start using your account, we need to verify your email address. Please click on the link below to complete the verification process:</p>',  'status' => 1, 'created_at' => now(), 'updated_at' => now()],

            ['user_id' => $userId, 'tenant_id' => $tenantId, 'subject' => 'Employee Session Assign Notify', 'title' => 'Welcome,{username}', 'slug' => 'employee-session-assign-notify', 'body' => '<p>Dear, {{username}}, We are pleased to inform you that you have been assigned to participate in a new session. This session is scheduled to take place on Date at time and will be conducted. Please click on the link below to complete the Goal process:</p>',  'status' => 1, 'created_at' => now(), 'updated_at' => now()],

            ['user_id' => $userId, 'tenant_id' => $tenantId, 'subject' => 'Employee Goal Submit Notify', 'title' => 'Welcome,{username}', 'slug' => 'employee-goal-submit-notify', 'body' => '<p>Dear, {{username}}, I hope this message finds you well. This is a friendly reminder regarding the upcoming deadline for goal submission.Please take the time to review your objectives and submit your goals by the specified deadline. If you encounter any difficulties or have questions regarding the goal-setting process so please cancel your Goal. Please click on the link below to complete the process:</p>',  'status' => 1, 'created_at' => now(), 'updated_at' => now()],

            ['user_id' => $userId, 'tenant_id' => $tenantId, 'subject' => 'Goal Approved Notify', 'title' => 'Welcome,{username}, Your goal is Approved', 'slug' => 'goal-approved-notify-for-next-approval', 'body' => '<p>Hello, {{username}}, I am pleased to inform you that your proposed goals  have been reviewed and approved. Please click on the link and see your goal details:</p>',  'status' => 1, 'created_at' => now(), 'updated_at' => now()],

            ['user_id' => $userId, 'tenant_id' => $tenantId, 'subject' => 'Goal Approved Notify', 'title' => 'Welcome,{username}, Your goal is Approved', 'slug' => 'goal-approved-notify-for-goal-creator', 'body' => '<p>Hello, {{username}}, I am pleased to inform you that your proposed goals  have been reviewed and approved. Please click on the link and see your goal details:</p>',  'status' => 1, 'created_at' => now(), 'updated_at' => now()],

            ['user_id' => $userId, 'tenant_id' => $tenantId, 'subject' => 'Goal Back Notify', 'title' => 'Welcome,{username}, Your goal is Back', 'slug' => 'goal-back-notify', 'body' => '<p>Hello, {{username}}, I hope this message finds you well. I am reaching out to inform you about the cancellation of the goal previously assigned to you and cearfully resubmit your goal. Please click on the link and see your goal details:</p>',  'status' => 1, 'created_at' => now(), 'updated_at' => now()],

            ['user_id' => $userId, 'tenant_id' => $tenantId, 'subject' => 'Goal Resubmit Notify', 'title' => 'Welcome,{username} Resubmit Goal', 'slug' => 'goal-resubmit-notify', 'body' => '<p>Hello, {{username}}, I hope this message finds you well. I am writing to remind you about the need to resubmit your Goal. Please click on the link and see your goal details:</p>',  'status' => 1, 'created_at' => now(), 'updated_at' => now()],

            ['user_id' => $userId, 'tenant_id' => $tenantId, 'subject' => 'Approval Process Date End Notify', 'title' => 'Welcome,{username}, Your approval process date is end', 'slug' => 'goal-final-approved', 'body' => '<p>Hello, {{username}}, I hope this message finds you well. This is to inform you that the approval process has now reached its scheduled end date. Please click on the link and see your approval process details:</p>',  'status' => 1, 'created_at' => now(), 'updated_at' => now()]
        ];
        NotificationTemplates::insert($data);
    }
}

function replaceBrackets($content, $customizedFieldsArray)
{
    $pattern = '/{{(.*?)}}/';
    $content = preg_replace_callback($pattern, function ($matches) use ($customizedFieldsArray) {
        $field = trim($matches[1]);
        if (array_key_exists($field, $customizedFieldsArray)) {
            return $customizedFieldsArray[$field];
        }
        return $matches[0];
    }, $content);
    return $content;
}

function custom_number_format($value)
{
    if (is_integer($value)) {
        return number_format($value, 8, '.', '');
    } elseif (is_string($value)) {
        $value = floatval($value);
    }
    $number = explode('.', number_format($value, 10, '.', ''));
    return $number[0] . '.' . substr($number[1], 0, 8);
}

if (!function_exists('sendCommonEmailNotification')) {
    function sendCommonEmailNotification($template, $userIds = [], $customData = null, $link = null)
    {
        try {
            if (getOption('app_mail_status')) {
                foreach ($userIds as $userId) {
                    $userData = User::find($userId);
                    $subject = customNotifyTemplate(NOTIFY_TYPE_EMAIL, 'subject', $template, $customData, $userData, '');
                    $body = customNotifyTemplate(NOTIFY_TYPE_EMAIL, 'body', $template, $customData, $userData, '');
                    $link = '';
                    Mail::to($userData->email)->send(new CustomEmailNotify($subject, $body, $link));
                }
            } else {
                Log::info('Email Notify Alert: App mail status not active');
            }
            return true;
        } catch (Exception $e) {
            Log::info('Email notify error: ' . $e->getMessage());
            return false;
        }
    }
}

if (!function_exists('sendCommonNotification')) {
    function sendCommonNotification($template, $userIds = [], $customData = null, $link = null)
    {
        try {
            if (!empty($userIds)) {
                foreach ($userIds as $userId) {
                    $userData = User::find($userId);
                    $title = customNotifyTemplate(NOTIFY_TYPE_NOTIFICATION, 'subject', $template, $customData, $userData, '');
                    $body = customNotifyTemplate(NOTIFY_TYPE_NOTIFICATION, 'body', $template, $customData, $userData, '');
                    $obj = new Notification();
                    $obj->user_id = $userId;
                    $obj->title = $title;
                    $obj->body = $body;
                    $obj->link = $link;
                    $obj->save();
                }
            } else {
                $title = customNotifyTemplate(NOTIFY_TYPE_NOTIFICATION, 'subject', $template, $customData, '', '');
                $body = customNotifyTemplate(NOTIFY_TYPE_NOTIFICATION, 'body', $template, $customData, '', '');
                $obj = new Notification();
                $obj->user_id = NULL;
                $obj->title = $title;
                $obj->body = $body;
                $obj->link = $link;
                $obj->save();
            }

            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}

function customNotifyTemplate($notifyType, $property, $template, $customData = null, $userData = null, $link = null)
{
    if ($notifyType == NOTIFY_TYPE_NOTIFICATION) {
        $query = NotificationTemplates::query();
    } else {
        $query = EmailTemplate::query();
    }
    $data = $query->clone()->where('slug', $template)
        ->where(function ($q) use ($template) {
            if (($template == "password-reset") || ($template == "subscription-paid-notify-for-super-admin") || ($template == "subscription-cancel-notify-for-super-admin") || $template == "email-verify") {
                $q->where('tenant_id', null);
            } else {
                $q->where('tenant_id', auth()->user()->tenant_id);
            }
        })
        ->first();

    $body = $data->{$property};

    foreach (customNotifyTempFields($template) as $key => $item) {
        if ($key == '{{reset_password_url}}') {
            $link = "<a href='$customData->reset_password_url'>Reset Link</a>";
            $body = str_replace($key, $link, $body);
        } else if ($key == '{{email_verify_url}}') {
            $body = str_replace($key, $link, $body);
        } else if ($key == '{{order_id}}' && $customData != NULL) {
            $body = str_replace($key, is_object($customData) ? $customData->order_id : $customData['order_id'], $body);
        } else if ($key == '{{tracking_no}}' && $customData != NULL) {
            $body = str_replace($key, is_object($customData) ? $customData->ticket_id : $customData['ticket_id'], $body);
        } else if ($key == '{{username}}') {
            $body = str_replace($key, is_object($customData) ? $userData->name : $userData['name'], $body);
        } else if ($key == '{{session_name}}') {
            $body = str_replace($key, $customData->kpi_phase_name, $body);
        } else if ($key == '{{goal_setup_start_date}}') {
            $body = str_replace($key, $customData->goal_setup_start_date, $body);
        } else if ($key == '{{goal_setup_end_date}}') {
            $body = str_replace($key, $customData->goal_setup_end_date, $body);
        } else if ($key == '{{session_period_start_date}}') {
            $body = str_replace($key, $customData->session_setup_start_date, $body);
        } else if ($key == '{{sender_name}}') {
            $body = str_replace($key, $customData->sender_name, $body);
        } else if ($key == '{{final_approval_name}}') {
            $body = str_replace($key, $customData->sender_name, $body);
        } else if ($key == '{{approval_name}}') {
            $body = str_replace($key, $customData->sender_name, $body);
        } else if ($key == '{{feedback}}') {
            $body = str_replace($key, $customData->feedback, $body);
        } else if ($key == '{{rating}}') {
            $body = str_replace($key, $customData->rating, $body);
        } else if ($key == '{{goal_creator_name}}') {
            $body = str_replace($key, $customData->goal_creator_name, $body);
        } else if ($key == '{{session_period_end_date}}') {
            $body = str_replace($key, $customData->session_setup_end_date, $body);
        } else if ($key == '{{session_approval_process_start_date}}') {
            $body = str_replace($key, $customData->appraisement_setup_start_date, $body);
        } else if ($key == '{{session_approval_process_end_date}}') {
            $body = str_replace($key, $customData->appraisement_setup_end_date, $body);
        } else if ($key == '{{email}}') {
            $body = str_replace($key, $customData->email, $body);
        } else if ($key == '{{password}}') {
            $body = str_replace($key, $customData->password, $body);
        } else if ($key == '{{otp}}') {
            $body = str_replace($key, $customData->otp, $body);
        } else if ($key == '{{link}}') {
            $body = str_replace($key, $link, $body);
        } else if ($key == '{{package}}') {
            $body = str_replace($key, $customData->package, $body);
        } else if ($key == '{{gateway}}') {
            $body = str_replace($key, $customData->gateway, $body);
        } else {
            $body = str_replace($key, $item, $body);
        }
    }
    return $body;
}

if (!function_exists('checkoutPaymentMail')) {
    function checkoutPaymentMail($invoice)
    {
        return true;
    }
}

if (!function_exists('userNotification')) {
    function userNotification($type)
    {
        if ($type == 'seen') {
            return Notification::leftJoin('notification_seens', 'notifications.id', '=', 'notification_seens.notification_id')
                ->where(function ($query) {
                    $query->where('notifications.user_id', null)->orWhere('notifications.user_id', Auth::id());
                })
                ->where('notifications.status', ACTIVE)
                ->where('notification_seens.id', '!=', null)
                ->orderBy('id', 'DESC')
                ->get([
                    'notifications.*',
                    'notification_seens.id as seen_id',
                ]);
        } else if ($type == 'unseen') {
            $test = Notification::leftJoin('notification_seens', 'notifications.id', '=', 'notification_seens.notification_id')
                ->where(function ($query) {
                    $query->where('notifications.user_id', null)->orWhere('notifications.user_id', Auth::id());
                })
                ->where('notifications.status', ACTIVE)
                ->where('notification_seens.id', null)
                ->orderBy('id', 'DESC')
                ->get([
                    'notifications.*',
                    'notification_seens.id as seen_id',
                ]);
            return $test;
        } else if ($type == 'seen-unseen') {
            return Notification::leftJoin('notification_seens', 'notifications.id', '=', 'notification_seens.notification_id')
                ->where(function ($query) {
                    $query->where('notifications.user_id', null)->orWhere('notifications.user_id', Auth::id());
                })
                ->where('notifications.status', ACTIVE)
                ->orderBy('id', 'DESC')
                ->get([
                    'notifications.*',
                    'notification_seens.id as seen_id',
                ]);
        }
    }
}

if (!function_exists('getAdminLimit')) {
    /**
     * Check how many units remain for a given limit type on the admin's active package.
     *
     * Returns:
     *   (int)  remaining count  → limit applies, N units left (0 means exhausted)
     *   true                   → no limit / unlimited (-1) or no package check needed
     *   false                  → no active package found — deny access
     *
     * @param string $type  RULES_PAGE_LIMIT | RULES_MESSAGE_LIMIT
     * @param int|null $userId  defaults to auth()->id()
     */
    function getAdminLimit(string $type, ?int $userId = null): int|bool
    {
        $userId = $userId ?? auth()->id();

        $userPackage = UserPackage::query()
            ->where('user_id', $userId)
            ->where('status', ACTIVE)
            ->whereDate('end_date', '>=', now())
            ->first();

        // No active package → deny
        if (is_null($userPackage)) {
            return false;
        }

        if ($type === RULES_PAGE_LIMIT) {
            // -1 means unlimited
            if ($userPackage->page_limit == -1) {
                return true;
            }
            $used   = \App\Models\PlatformConnection::where('user_id', $userId)->count();
            $remain = max(0, $userPackage->page_limit - $used);
            return $remain;
        }

        if ($type === RULES_MESSAGE_LIMIT) {
            // -1 means unlimited
            if ($userPackage->message_limit == -1) {
                return true;
            }
            // Count outbound messages sent since this subscription started
            $used   = \App\Models\Message::where('user_id', $userId)
                ->where('direction', MESSAGE_DIRECTION_OUTBOUND)
                ->whereDate('created_at', '>=', $userPackage->start_date)
                ->count();
            $remain = max(0, $userPackage->message_limit - $used);
            return $remain;
        }

        return true;
    }
}

if (!function_exists('getSubText')) {
    function getSubText($html, $limit = 100000)
    {
        return \Illuminate\Support\Str::limit(strip_tags($html), $limit);
    }
}
if (!function_exists('getPaymentType')) {
    function getPaymentType($object)
    {
        return $className = class_basename(get_class($object));
    }
}
if (!function_exists('thousandFormat')) {
    function thousandFormat($number)
    {
        $number = (int)preg_replace('/[^0-9]/', '', $number);
        if ($number >= 1000) {
            $rn = round($number);
            $format_number = number_format($rn);
            $ar_nbr = explode(',', $format_number);
            $x_parts = array('K', 'M', 'B', 'T', 'Q');
            $x_count_parts = count($ar_nbr) - 1;
            $dn = $ar_nbr[0] . ((int)$ar_nbr[1][0] !== 0 ? '.' . $ar_nbr[1][0] : '');
            $dn .= $x_parts[$x_count_parts - 1];

            return $dn;
        }
        return $number;
    }
}

if (!function_exists('getTicketNumber')) {
    function getTicketNumber($eventId, $oldTotal)
    {
        return $eventId . sprintf('%04d', ++$oldTotal);
    }
}

if (!function_exists('encodeId')) {
    function encodeId($id)
    {
        return encrypt($id);
    }
}
if (!function_exists('decodeId')) {
    function decodeId($id)
    {
        return decrypt($id);
    }
}

function getApiCall($url, $requestData)
{
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url . '?' . http_build_query($requestData),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    Log::info($response);
    return $response;
    throw new Exception($response->getBody());
}

function addUserActivityLog($action, $user_id)
{
    $current_ip = get_clientIp();
    $agent = new Agent();
    $deviceType = isset($agent) && $agent->isMobile() == true ? 'Mobile' : 'Web';
    $location = geoip()->getLocation($current_ip);
    $activity['user_id'] = $user_id;
    $activity['action'] = $action;
    $activity['ip_address'] = isset($current_ip) ? $current_ip : '0.0.0.0';
    $activity['source'] = $deviceType;
    $activity['location'] = $location->country;
    UserActivityLog::create($activity);
};


function currencyPrice($price)
{
    if ($price == null) {
        return 0;
    }
    if (getCurrencyPlacement() == 'after')
        return number_format($price, 2) . '' . getCurrencySymbol();
    else {
        return getCurrencySymbol() . number_format($price, 2);
    }
}

function getEmailByUserId($user_id)
{
    return User::where('id', $user_id)->first(['email'])?->email;
}

function getUserData($user_id, $property)
{
    $data = User::where('id', $user_id)->first();
    if (!is_null($data)) {
        return $data?->{$property};
    }
    return null;
}

function getRoleByUserId($user_id)
{
    return User::where('id', $user_id)->first(['role'])->role;
}

function getCustomEmailTemplate($type, $template, $property, $link = null, $customData = null, $userData = null)
{
    $data = EmailTemplate::where('slug', $template)->first();
    if ($data && $data != null) {
        if ($property == 'body') {
            $body = $data->{$property};
            foreach (customNotifyTempFields($type) as $key => $item) {
                if ($key == '{{reset_password_url}}') {
                    $body = str_replace($key, $link, $body);
                } else if ($key == '{{email_verify_url}}') {
                    $body = str_replace($key, $link, $body);
                } else if ($key == '{{order_id}}' && $customData != NULL) {
                    $body = str_replace($key, is_object($customData) ? $customData->order_id : $customData['order_id'], $body);
                } else if ($key == '{{tracking_no}}' && $customData != NULL) {
                    $body = str_replace($key, is_object($customData) ? $customData->ticket_id : $customData['ticket_id'], $body);
                } else if ($key == '{{username}}') {
                    $body = str_replace($key, is_object($customData) ? $userData->name : $userData['name'], $body);
                } else if ($key == '{{ticket_title}}') {
                    $body = str_replace($key, $customData->ticket_title, $body);
                } else if ($key == '{{ticket_description}}') {
                    $body = str_replace($key, $customData->ticket_description, $body);
                } else if ($key == '{{ticket_created_time}}') {
                    $body = str_replace($key, $customData->created_at, $body);
                } else if ($key == '{{client_name}}') {
                    $body = str_replace($key, $customData->client_name, $body);
                } else if ($key == '{{link}}') {
                    $body = str_replace($key, $link, $body);
                } else {
                    $body = str_replace($key, $item, $body);
                }
            }
            return $body;
        } else if ($property == 'subject') {
            $subject = $data->{$property};

            foreach (customNotifyTempFields($type) as $key => $item) {
                if ($key == '{{tracking_no}}') {
                    $subject = str_replace($key, $customData->ticket_id, $subject);
                }
            }
            return $subject;
        } else {
            return $data->{$property};
        }
    }
    return '';
}

//email notification helper start


function sendForgotMail($email)
{
    try {
        if (getOption('app_mail_status')) {
            $token = Str::random(64);

            DB::table('password_resets')->insert([
                'email' => $email,
                'token' => $token,
                'created_at' => now()
            ]);

            $user = User::where('email', $email)->first();
            $link = route('password.reset.verify', $token);
            $customData = (object)[
                'reset_password_url' => $link
            ];
            sendCommonEmailNotification('password-reset', [$user->id], $customData, '');
        } else {
            Log::info('Forgot Email Notify Alert: App mail status not active');
            return [
                'status' => false,
                'msg' => __("App mail status not active"),
            ];
        }

        return [
            'status' => true,
            'msg' => __("We have mailed your password reset link!"),
        ];
    } catch (\Exception $e) {
        Log::info('Ticket Conversation Email Notify Error: ' . $e->getMessage());
        return [
            'status' => false,
            'msg' => SOMETHING_WENT_WRONG,
        ];
    }
}


//Subscription notification and mail helper end


//notification helper end

if (!function_exists('setUserPackage')) {
    function setUserPackage($userId, $package, $duration, $orderId = NULL)
    {
        UserPackage::where(['user_id' => $userId])->whereIn('status', [ACTIVE, INITIATE])->update(['status' => DEACTIVATE]);

        UserPackage::create([
            'user_id' => $userId,
            'package_id' => $package->id,
            'name' => $package->name,
            'page_limit' => $package->page_limit,
            'message_limit' => $package->message_limit,
            'monthly_price' => $package->monthly_price,
            'yearly_price' => $package->yearly_price,
            'order_id' => $orderId,
            'is_trail' => $package->is_trail,
            'start_date' => now(),
            'end_date' => Carbon::now()->addDays($duration),
            'status' => ACTIVE,
        ]);
    }
}

if (!function_exists('getAddonCodeCurrentVersion')) {
    function getAddonCodeCurrentVersion($appCode)
    {
        Artisan::call('config:clear');
        return config('addon.' . $appCode . '.current_version', 0);
    }
}


// email tamp body & object create start
if (!function_exists('getEmailTemplateById')) {
    function getEmailTemplateById($id, $data)
    {
        $template = EmailTemplate::where('id', $id)->first();
        $emailData['content'] = replaceTemplateFields($template->body ?? '', $data);
        $emailData['subject'] = replaceTemplateFields($template->subject ?? '', $data);

        return $emailData;
    }
}

if (!function_exists('replaceTemplateFields')) {
    function replaceTemplateFields($template, $data)
    {
        $replacements = [
            '{{app_contact_number}}' => getOption('app_contact_number'),
            '{{app_email}}' => getOption('app_email'),
            '{{app_name}}' => getOption('app_name'),
        ];
        // Exclude 'user' key from $data
        unset($data['user']);

        $replacements = array_merge($replacements, $data);

        return str_replace(array_keys($replacements), array_values($replacements), $template);
    }
}



if (!function_exists('goalApprovalTree')) {
    function approvalTree($eid, $sid, $type, $id_details_data = false)
    {
        $apploverTree = KpiSessionApprovalTree::join('users', ['users.id' => 'kpi_session_approval_trees.user_id'])
            ->where(['session_id' => $sid, 'goal_submitted_by' => $eid, 'type' => $type])
            ->select(["kpi_session_approval_trees.*", "users.name as name", "users.image as image"])
            ->orderBy('kpi_session_approval_trees.id', 'ASC')->get();
        $approval_list_data = [];
        $next = 0;
        $is_final = 0;
        $goalEditPermision = false;
        foreach ($apploverTree as $approver) {
            $sender_class = '';
            if ($approver->process_status == SESSION_PROCESS_APPROVED) {
                $sender_class = 'stage-done';
            } elseif ($approver->process_status == SESSION_PROCESS_PROCESSING) {
                $sender_class = 'stage-process';
                $next = $approver->id;
                $is_final = $approver->is_final_approver;
            } elseif ($approver->process_status == SESSION_PROCESS_CANCELED) {
                $sender_class = 'stage-cancel';
            }

            $icon = '';
            if ($sender_class == 'stage-done' && $approver->user_id != $approver->goal_submitted_by && $approver->is_final_approver != 1) {
                $icon = asset('assets/images/icon/process-icon-3.svg');
            } else if (($sender_class == 'stage-done' && $approver->user_id == $approver->goal_submitted_by) || ($approver->is_final_approver == 1)) {
                $icon = asset('assets/images/icon/process-icon-1.svg');
            } else {
                $icon = asset('assets/images/icon/process-icon-2.svg');
            }

            $approval_list_data[] = [
                'id' => $approver->id,
                'session_id' => $sid,
                'user_id' => $approver->user_id,
                'goal_creator' => $approver->goal_submitted_by,
                'user_name' => $approver->name,
                'user_image' => getFileUrl($approver->image),
                'process_status_class' => $sender_class,
                'sender' => $approver->sender_id,
                'receiver' => $approver->receiver_id,
                'final_approves' => $approver->is_final_approver,
                'title' => __("Goal created & submitted by"),
                'icon' => $icon,
                'create_or_approved_date' => $sender_class == '' ? '' : $approver->updated_at,
            ];

            if ($approver->process_status == SESSION_PROCESS_PROCESSING &&  $approver->user_id == auth()->id() &&  $approver->goal_submitted_by == auth()->id()) {
                $goalEditPermision = true;
            }
        }

        if ($id_details_data) {
            return ['next' => $next, 'is_final' => $is_final, 'data' => $approval_list_data, 'is_goal_edit_permission' => $goalEditPermision];
        }
        return $approval_list_data;
    }
}

function approvalTreeByUser($eid)
{
    $employeeId = $eid;

    $employeeData = User::find($employeeId);
    $smartSetting = SmartSetting::where(['department_id' => $employeeData->employee->department_id])->first();

    $all_user = [$employeeData->id];
    $uid = $employeeData->id;

    $approval_list_data[] = [
        'user_id' => $employeeData->id,
        'user_name' => $employeeData->name,
        'user_image' => getFileUrl($employeeData->image),
        'is_final_approves' => false,
    ];

    $level = $smartSetting->hierarchy_level;
    if ($level != 0) {
        $u = EmployeeDetails::join('departments', 'employee_details.department_id', '=', 'departments.id')
            ->join('users', 'departments.department_head', '=', 'users.id')
            ->where(['user_id' => $uid])
            ->whereNotIn('users.id', $all_user)
            ->first([
                'employee_details.*',
                'users.id as user_id',
                'users.name as user_name',
                'users.image as user_image',
            ]);

        if (!is_null($u)) {
            $uid = $u->user_id;
            $approval_list_data[] = [
                'user_id' => $uid,
                'user_name' => $u?->user_name,
                'user_image' => getFileUrl($u->user_image),
                'is_final_approves' => false
            ];
            array_push($approval_list_data);
            array_push($all_user, $uid);

            if ($level > 1) {
                $level = $level - 1;
                for ($i = 0; $i < $level; $i++) {
                        $u = EmployeeDetails::where(['user_id' => $uid])->first();
                    if (is_null($u)) {
                        break;
                    }
                    $uid = $u->supervisor_id;
                    if ($uid == null || $uid == 0) {
                        continue;
                    }
                    $approval_list_data[] = [
                        'user_id' => $uid,
                        'user_name' => $u->supervisor?->name,
                        'user_image' => getFileUrl($u->user->image),
                        'is_final_approves' => false
                    ];
                    array_push($approval_list_data);
                    array_push($all_user, $uid);
                }
            }
        }
    }


    $hr = User::where(['id' => $smartSetting->assign_hr])->whereNotIn('id', $all_user)->first();
    if (!is_null($hr)) {
        $approval_list_data[] = [
            'user_id' => $hr->id,
            'user_name' => $hr->name,
            'user_image' => getFileUrl($hr->image),
            'is_final_approves' => false,
        ];
        array_push($all_user, $hr->id);
    }

    $spacial_approval = User::whereIn('id', json_decode($smartSetting->special_approval))->whereNotIn('id', $all_user)->get();
    foreach ($spacial_approval as $sa) {
        $approval_list_data[] = [
            'user_id' => $sa->id,
            'user_name' => $sa->name,
            'user_image' => getFileUrl($sa->image),
            'is_final_approves' => false
        ];
        array_push($all_user, $sa->id);
    }

    $fa = User::find($smartSetting->final_approval);
    $approval_list_data[] = [
        'user_id' => $fa->id,
        'user_name' => $fa->name,
        'user_image' => getFileUrl($fa->image),
        'is_final_approves' => true,
    ];

    return $approval_list_data;
}

// email tamp body & object create end

if (!function_exists('syncMissingGateway')) {
    function syncMissingGateway(): void
    {
        $users = \App\Models\User::where('role',USER_ROLE_SUPER_ADMIN)->get();
        $gateways = getPaymentServiceClass();

        foreach ($users as $user) {
            $existingGateways = \App\Models\Gateway::where('user_id', $user->id)->pluck('slug')->toArray();

            foreach ($gateways as $gatewaySlug => $gatewayService) {
                if (!in_array($gatewaySlug, $existingGateways)) {
                    $gateway = new \App\Models\Gateway();
                    $gateway->user_id = $user->id;
                    $gateway->tenant_id = null;
                    $gateway->title = ucfirst($gatewaySlug);
                    $gateway->slug = $gatewaySlug;
                    $gateway->image = 'assets/images/gateway-icon/' . $gatewaySlug . '.png';
                    $gateway->status = 1;
                    $gateway->mode = 2;
                    $gateway->created_at = now();
                    $gateway->updated_at = now();
                    $gateway->save();

                    // Insert currency for the new gateway
                    $currency = new \App\Models\GatewayCurrency();
                    $currency->user_id = $user->id;
                    $currency->gateway_id = $gateway->id;
                    $currency->currency = 'USD';
                    $currency->conversion_rate = 1.0;
                    $currency->created_at = now();
                    $currency->updated_at = now();
                    $currency->save();
                }
            }
        }
    }
}
if (!function_exists('get_domain_name')) {
    function get_domain_name($url)
    {
        $parseUrl = parse_url(trim($url));
        if (isset($parseUrl['host'])) {
            $host = $parseUrl['host'];
        } else {
            $path = explode('/', $parseUrl['path']);
            $host = $path[0];
        }
        return trim($host);
    }
}

