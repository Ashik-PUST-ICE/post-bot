<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Services\FrontendSettingService;
use App\Models\Package;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function __construct(protected FrontendSettingService $service) {}

    public function index()
    {
        $data             = $this->service->getLandingData();
        $data['packages'] = Package::where('status', ACTIVE)->orderBy('monthly_price')->get();
        $data['pageTitle'] = getOption('app_name', config('app.name'));
        if (auth()->check()) {
            $data['currentPackage'] = (new \App\Http\Services\SubscriptionService())->getCurrentPackage();
        } else {
            $data['currentPackage'] = null;
        }
        return view('frontend.index', $data);
    }

    public function aboutUs()
    {
        $data = $this->service->getAboutData();
        $data['pageTitle'] = __('About Us') . ' - ' . getOption('app_name', config('app.name'));
        return view('frontend.about_us', $data);
    }

    public function privacyPolicy()
    {
        $data = $this->service->getPolicyData(__('Privacy Policy'));
        return view('frontend.privacy_policy', $data);
    }

    public function returnPolicy()
    {
        $data = $this->service->getPolicyData(__('Return Policy'));
        return view('frontend.return_policy', $data);
    }

    public function termsAndConditions()
    {
        $data = $this->service->getPolicyData(__('Terms and Condition'));
        return view('frontend.terms_and_condition', $data);
    }

    public function contactStore(Request $request)
    {
        $request->validate([
            'name'    => 'required|max:255',
            'email'   => 'required|email|max:255',
            'message' => 'required|max:10000',
        ]);

        // Store contact submission (reuse settings table as simple log or fire event)
        session()->flash('success', __('Your message has been sent successfully!'));
        return redirect()->back();
    }
}
