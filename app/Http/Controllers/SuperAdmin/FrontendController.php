<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Services\FrontendSettingService;
use App\Models\FrontendContent;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    use ResponseTrait;

    public function __construct(protected FrontendSettingService $service) {}

    // ── Sections (hero, pricing heading, features heading…) ──────────────────

    public function sections()
    {
        $data['title']               = __('Frontend Sections');
        $data['activeFrontendSection'] = 'active';
        $data['sections']            = $this->service->getAllSections();
        return view('sadmin.frontend.sections', $data);
    }

    public function updateSection(Request $request, int $id)
    {
        return $this->service->updateSection($request, $id);
    }

    // ── Features ─────────────────────────────────────────────────────────────

    public function features()
    {
        $data['title']               = __('Features');
        $data['activeFrontendFeatures'] = 'active';
        $data['items']               = $this->service->getContentsOfType(FrontendContent::TYPE_FEATURE);
        $data['type']                = FrontendContent::TYPE_FEATURE;
        return view('sadmin.frontend.contents', $data);
    }

    // ── Services / Goal blocks ────────────────────────────────────────────────

    public function services()
    {
        $data['title']               = __('Services');
        $data['activeFrontendServices'] = 'active';
        $data['items']               = $this->service->getContentsOfType(FrontendContent::TYPE_SERVICE);
        $data['type']                = FrontendContent::TYPE_SERVICE;
        return view('sadmin.frontend.contents', $data);
    }

    // ── Core Features ─────────────────────────────────────────────────────────

    public function coreFeatures()
    {
        $data['title']               = __('Core Features');
        $data['activeFrontendCore'] = 'active';
        $data['items']               = $this->service->getContentsOfType(FrontendContent::TYPE_CORE_FEATURE);
        $data['type']                = FrontendContent::TYPE_CORE_FEATURE;
        return view('sadmin.frontend.contents', $data);
    }

    // ── Why Choose Us ─────────────────────────────────────────────────────────

    public function chooseUs()
    {
        $data['title']               = __('Why Choose Us');
        $data['activeFrontendChooseUs'] = 'active';
        $data['items']               = $this->service->getContentsOfType(FrontendContent::TYPE_CHOOSE_US);
        $data['type']                = FrontendContent::TYPE_CHOOSE_US;
        return view('sadmin.frontend.contents', $data);
    }

    // ── FAQs ─────────────────────────────────────────────────────────────────

    public function faqs()
    {
        $data['title']               = __('FAQs');
        $data['activeFrontendFaqs']  = 'active';
        $data['items']               = $this->service->getContentsOfType(FrontendContent::TYPE_FAQ);
        $data['type']                = FrontendContent::TYPE_FAQ;
        return view('sadmin.frontend.contents', $data);
    }

    // ── Testimonials ──────────────────────────────────────────────────────────

    public function testimonials()
    {
        $data['title']               = __('Testimonials');
        $data['activeFrontendTestimonials'] = 'active';
        $data['items']               = $this->service->getContentsOfType(FrontendContent::TYPE_TESTIMONIAL);
        $data['type']                = FrontendContent::TYPE_TESTIMONIAL;
        return view('sadmin.frontend.contents', $data);
    }

    // ── Shared content CRUD (AJAX) ────────────────────────────────────────────

    public function storeContent(Request $request)
    {
        return $this->service->storeContent($request);
    }

    public function updateContent(Request $request, int $id)
    {
        return $this->service->updateContent($request, $id);
    }

    public function deleteContent(int $id)
    {
        return $this->service->deleteContent($id);
    }

    public function getContentInfo(int $id)
    {
        return $this->service->getContentInfo($id);
    }

    // ── About page ────────────────────────────────────────────────────────────

    public function about()
    {
        $data['title']           = __('About Page');
        $data['activeFrontendAbout'] = 'active';
        $data['about']           = $this->service->getAbout();
        return view('sadmin.frontend.about', $data);
    }

    public function updateAbout(Request $request)
    {
        return $this->service->updateAbout($request);
    }

    // ── Policy pages ──────────────────────────────────────────────────────────

    public function policies()
    {
        $locale = session()->get('local', 'en');
        $data['title']               = __('Policy Pages');
        $data['activeFrontendPolicies'] = 'active';
        $data['privacy_policy']      = getOption('privacy_policy_' . $locale);
        $data['return_policy']       = getOption('return_policy_' . $locale);
        $data['t_and_c']             = getOption('t_and_c_' . $locale);
        return view('sadmin.frontend.policies', $data);
    }

    public function updatePolicies(Request $request)
    {
        return $this->service->updatePolicy($request);
    }
}
