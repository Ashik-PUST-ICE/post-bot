<?php

namespace App\Http\Services;

use App\Models\FileManager;
use App\Models\FrontendAbout;
use App\Models\FrontendContent;
use App\Models\FrontendSection;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FrontendSettingService
{
    use ResponseTrait;

    // ── Section keys ─────────────────────────────────────────────────────────

    const SECTION_KEYS = [
        'hero_area', 'features', 'services', 'core_features',
        'choose_us', 'pricing', 'testimonials_area', 'faqs_area', 'demo_ection',
    ];

    // ─────────────────────────────────────────────────────────────────────────
    // PUBLIC: Data for landing page
    // ─────────────────────────────────────────────────────────────────────────

    public function getLandingData(): array
    {
        $section      = FrontendSection::allKeyed();
        $features     = FrontendContent::ofType(FrontendContent::TYPE_FEATURE)->get();
        $services     = FrontendContent::ofType(FrontendContent::TYPE_SERVICE)->get();
        $coreFeatures = FrontendContent::ofType(FrontendContent::TYPE_CORE_FEATURE)->get();
        $chooseUs     = FrontendContent::ofType(FrontendContent::TYPE_CHOOSE_US)->get();
        $faqs         = FrontendContent::ofType(FrontendContent::TYPE_FAQ)->get();
        $testimonials = FrontendContent::ofType(FrontendContent::TYPE_TESTIMONIAL)->get();

        return compact('section', 'features', 'services', 'coreFeatures', 'chooseUs', 'faqs', 'testimonials');
    }

    public function getAboutData(): array
    {
        $section      = FrontendSection::allKeyed();
        $about        = FrontendAbout::instance();
        $testimonials = FrontendContent::ofType(FrontendContent::TYPE_TESTIMONIAL)->get();
        $faqs         = FrontendContent::ofType(FrontendContent::TYPE_FAQ)->get();

        return compact('section', 'about', 'testimonials', 'faqs');
    }

    public function getPolicyData(string $pageTitle): array
    {
        $section = FrontendSection::allKeyed();
        return compact('section', 'pageTitle');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // SUPER ADMIN: Sections management
    // ─────────────────────────────────────────────────────────────────────────

    public function getAllSections(): \Illuminate\Database\Eloquent\Collection
    {
        $existing = FrontendSection::all()->keyBy('section_key');

        foreach (self::SECTION_KEYS as $key) {
            if (!$existing->has($key)) {
                FrontendSection::create(['section_key' => $key, 'page_title' => ucwords(str_replace('_', ' ', $key)), 'title' => ucwords(str_replace('_', ' ', $key))]);
            }
        }

        return FrontendSection::orderByRaw("FIELD(section_key, '" . implode("','", self::SECTION_KEYS) . "')")->get();
    }

    public function updateSection(Request $request, int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $section = FrontendSection::findOrFail($id);

            $data = $request->only(['page_title', 'title', 'description', 'status']);

            if ($request->hasFile('banner_image')) {
                $fm = (new FileManager())->upload('Frontend', $request->file('banner_image'));
                if ($fm) {
                    $data['banner_image'] = $fm->id;
                }
            }

            $section->update($data);

            return $this->success([], __('Section updated successfully'));
        } catch (\Exception $e) {
            Log::error('FrontendSettingService::updateSection', ['error' => $e->getMessage()]);
            return $this->error(__('Something went wrong'));
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // SUPER ADMIN: Contents (features, services, faqs, etc.)
    // ─────────────────────────────────────────────────────────────────────────

    public function getContentsOfType(string $type)
    {
        return FrontendContent::where('type', $type)->orderBy('sort_order')->get();
    }

    public function storeContent(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $request->only(['type', 'name', 'title', 'sub_title', 'description', 'rating', 'sort_order', 'status']);

            if ($request->hasFile('image')) {
                $fm = (new FileManager())->upload('Frontend', $request->file('image'));
                if ($fm) {
                    $data['image'] = $fm->id;
                }
            }

            if ($request->filled('others')) {
                $lines = array_filter(array_map('trim', explode("\n", $request->input('others'))));
                $data['others'] = array_values($lines);
            }

            FrontendContent::create($data);

            return $this->success([], __('Item created successfully'));
        } catch (\Exception $e) {
            Log::error('FrontendSettingService::storeContent', ['error' => $e->getMessage()]);
            return $this->error(__('Something went wrong'));
        }
    }

    public function updateContent(Request $request, int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $content = FrontendContent::findOrFail($id);
            $data    = $request->only(['name', 'title', 'sub_title', 'description', 'rating', 'sort_order', 'status']);

            if ($request->hasFile('image')) {
                $fm = (new FileManager())->upload('Frontend', $request->file('image'));
                if ($fm) {
                    $data['image'] = $fm->id;
                }
            }

            if ($request->filled('others')) {
                $lines = array_filter(array_map('trim', explode("\n", $request->input('others'))));
                $data['others'] = array_values($lines);
            }

            $content->update($data);

            return $this->success([], __('Item updated successfully'));
        } catch (\Exception $e) {
            Log::error('FrontendSettingService::updateContent', ['error' => $e->getMessage()]);
            return $this->error(__('Something went wrong'));
        }
    }

    public function deleteContent(int $id): \Illuminate\Http\JsonResponse
    {
        try {
            FrontendContent::findOrFail($id)->delete();
            return $this->success([], __('Item deleted successfully'));
        } catch (\Exception $e) {
            return $this->error(__('Something went wrong'));
        }
    }

    public function getContentInfo(int $id): \Illuminate\Http\JsonResponse
    {
        $item = FrontendContent::findOrFail($id);
        if ($item->others) {
            $item->others_text = implode("\n", $item->others);
        }
        return $this->success($item);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // SUPER ADMIN: About page
    // ─────────────────────────────────────────────────────────────────────────

    public function getAbout(): FrontendAbout
    {
        return FrontendAbout::instance();
    }

    public function updateAbout(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $about = FrontendAbout::instance();

            $data = $request->only([
                'title', 'description',
                'statistic_title_1', 'statistic_description_1',
                'statistic_title_2', 'statistic_description_2',
                'statistic_title_3', 'statistic_description_3',
                'mission_title', 'mission_description',
                'vision_title', 'vision_description',
                'team_section_title', 'team_section_description',
                'core_value_section_title', 'core_value_section_description',
            ]);

            foreach (['image_1', 'image_2', 'image_3', 'image_4', 'mission_image', 'vision_image'] as $imgField) {
                if ($request->hasFile($imgField)) {
                    $fm = (new FileManager())->upload('Frontend', $request->file($imgField));
                    if ($fm) {
                        $data[$imgField] = $fm->id;
                    }
                }
            }

            // Team members JSON
            if ($request->filled('team_members_json')) {
                $data['team_members'] = json_decode($request->input('team_members_json'), true) ?? [];
            }

            // Core values JSON
            if ($request->filled('core_values_json')) {
                $data['core_values'] = json_decode($request->input('core_values_json'), true) ?? [];
            }

            $about->update($data);

            return $this->success([], __('About page updated successfully'));
        } catch (\Exception $e) {
            Log::error('FrontendSettingService::updateAbout', ['error' => $e->getMessage()]);
            return $this->error(__('Something went wrong'));
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // SUPER ADMIN: Policy pages
    // ─────────────────────────────────────────────────────────────────────────

    public function updatePolicy(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $locale = session()->get('local', 'en');

            if ($request->filled('privacy_policy')) {
                setOption('privacy_policy_' . $locale, $request->input('privacy_policy'));
            }
            if ($request->filled('return_policy')) {
                setOption('return_policy_' . $locale, $request->input('return_policy'));
            }
            if ($request->filled('t_and_c')) {
                setOption('t_and_c_' . $locale, $request->input('t_and_c'));
            }

            return $this->success([], __('Policies updated successfully'));
        } catch (\Exception $e) {
            Log::error('FrontendSettingService::updatePolicy', ['error' => $e->getMessage()]);
            return $this->error(__('Something went wrong'));
        }
    }

}
