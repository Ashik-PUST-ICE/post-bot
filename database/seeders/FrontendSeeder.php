<?php

namespace Database\Seeders;

use App\Models\FrontendAbout;
use App\Models\FrontendContent;
use App\Models\FrontendSection;
use Illuminate\Database\Seeder;

class FrontendSeeder extends Seeder
{
    public function run(): void
    {
        // ── Sections ─────────────────────────────────────────────────────────

        $sections = [
            ['section_key' => 'hero_area',          'page_title' => 'AI-Powered Automation',    'title' => 'Automate Your Facebook & WhatsApp Replies with AI',   'description' => 'Let AI handle your customer messages 24/7 so you can focus on growing your business.',    'status' => 1],
            ['section_key' => 'features',            'page_title' => 'Platform Features',        'title' => 'Everything You Need to Automate Customer Replies',    'description' => '',    'status' => 1],
            ['section_key' => 'services',            'page_title' => 'Our Services',             'title' => 'Powerful Tools for Every Business',                  'description' => '',    'status' => 1],
            ['section_key' => 'core_features',       'page_title' => 'Core Features',            'title' => 'Built for Scale, Designed for Simplicity',           'description' => '',    'status' => 1],
            ['section_key' => 'choose_us',           'page_title' => 'Why Choose Us',            'title' => 'The Smart Choice for Social Media Automation',       'description' => '',    'status' => 1],
            ['section_key' => 'pricing',             'page_title' => 'Pricing Plans',            'title' => 'Simple Pricing, No Hidden Fees',                     'description' => '',    'status' => 1],
            ['section_key' => 'testimonials_area',   'page_title' => 'Testimonials',             'title' => 'What Our Customers Say',                             'description' => '',    'status' => 1],
            ['section_key' => 'faqs_area',           'page_title' => 'FAQ',                      'title' => 'Frequently Asked Questions',                         'description' => '',    'status' => 1],
            ['section_key' => 'demo_ection',         'page_title' => 'Get Started',              'title' => 'Ready to Automate Your Business?',                   'description' => '',    'status' => 1],
        ];

        foreach ($sections as $s) {
            FrontendSection::updateOrCreate(['section_key' => $s['section_key']], $s);
        }

        // ── Features ─────────────────────────────────────────────────────────

        if (FrontendContent::where('type', 'feature')->count() === 0) {
            $features = [
                ['title' => 'Facebook Auto-Reply',       'sort_order' => 1],
                ['title' => 'WhatsApp Automation',       'sort_order' => 2],
                ['title' => 'AI-Powered Responses',      'sort_order' => 3],
                ['title' => 'Multi-Platform Support',    'sort_order' => 4],
                ['title' => 'Real-Time Analytics',       'sort_order' => 5],
                ['title' => '24/7 Customer Support',     'sort_order' => 6],
                ['title' => 'Team Collaboration',        'sort_order' => 7],
                ['title' => 'Keyword Rules Engine',      'sort_order' => 8],
            ];
            foreach ($features as $f) {
                FrontendContent::create(array_merge($f, ['type' => 'feature', 'status' => 1]));
            }
        }

        // ── Services / Goals ─────────────────────────────────────────────────

        if (FrontendContent::where('type', 'service')->count() === 0) {
            FrontendContent::create([
                'type'       => 'service',
                'name'       => 'Messenger Bot',
                'title'      => 'Reply to Every Facebook Message Instantly',
                'sub_title'  => 'Never miss a customer enquiry again.',
                'description'=> 'Our AI reads every incoming Facebook Messenger message and crafts a personalised, context-aware reply within seconds — day or night.',
                'others'     => ['Auto-reply on Messenger', 'Context-aware AI replies', 'Conversation history tracking', 'Human handover when needed'],
                'sort_order' => 1, 'status' => 1,
            ]);
            FrontendContent::create([
                'type'       => 'service',
                'name'       => 'WhatsApp Bot',
                'title'      => 'Automate Your WhatsApp Business Conversations',
                'sub_title'  => 'Scale customer support without scaling your team.',
                'description'=> 'Connect your WhatsApp Business account and let AI handle FAQs, order enquiries, and more — automatically and accurately.',
                'others'     => ['WhatsApp Cloud API integration', 'Rich message templates', 'Auto read-receipts', 'Keyword-triggered responses'],
                'sort_order' => 2, 'status' => 1,
            ]);
        }

        // ── Core Features ─────────────────────────────────────────────────────

        if (FrontendContent::where('type', 'core_feature')->count() === 0) {
            $coreFeatures = [
                ['title' => 'AI Dashboard',           'description' => 'Get a bird\'s-eye view of all conversations, reply rates, and AI performance in real time.'],
                ['title' => 'Keyword Rules Engine',   'description' => 'Set rules to auto-reply, escalate, or tag conversations based on specific keywords.'],
                ['title' => 'Team Inbox',             'description' => 'Collaborate with your team in a shared inbox — assign, comment, and resolve conversations together.'],
                ['title' => 'Analytics & Reports',    'description' => 'Track response times, sentiment trends, and message volumes with exportable charts.'],
            ];
            foreach ($coreFeatures as $i => $cf) {
                FrontendContent::create(array_merge($cf, ['type' => 'core_feature', 'sort_order' => $i + 1, 'status' => 1]));
            }
        }

        // ── Why Choose Us ─────────────────────────────────────────────────────

        if (FrontendContent::where('type', 'choose_us')->count() === 0) {
            $chooseUs = [
                ['title' => 'Setup in Minutes',      'description' => 'Connect your Facebook Page or WhatsApp number and go live in under 5 minutes — no coding required.'],
                ['title' => 'AI That Learns',        'description' => 'Our AI uses your business context and conversation history to craft better replies over time.'],
                ['title' => 'Enterprise Security',   'description' => 'All tokens are encrypted, webhooks are verified, and your data is never shared with third parties.'],
            ];
            foreach ($chooseUs as $i => $cu) {
                FrontendContent::create(array_merge($cu, ['type' => 'choose_us', 'sort_order' => $i + 1, 'status' => 1]));
            }
        }

        // ── FAQs ─────────────────────────────────────────────────────────────

        if (FrontendContent::where('type', 'faq')->count() === 0) {
            $faqs = [
                ['title' => 'Do I need coding skills to use SocialAgent?',           'description' => 'No. Our platform is 100% no-code. Connect your accounts and configure the AI from a simple dashboard.'],
                ['title' => 'Which platforms are supported?',                         'description' => 'We currently support Facebook Messenger, Facebook Comments, and WhatsApp Business. Instagram is coming soon.'],
                ['title' => 'How does the AI know what to reply?',                    'description' => 'You provide your business context, FAQs, and tone preferences. The AI uses this to generate accurate, on-brand replies.'],
                ['title' => 'Can I override the AI and reply manually?',              'description' => 'Yes. You can take over any conversation at any time and reply manually from your inbox.'],
                ['title' => 'Is there a free trial?',                                 'description' => 'Yes! All plans include a 14-day free trial with no credit card required.'],
                ['title' => 'What happens if the AI is not confident in a reply?',    'description' => 'If the AI confidence falls below your threshold, the conversation is automatically escalated to a human agent.'],
            ];
            foreach ($faqs as $i => $faq) {
                FrontendContent::create(array_merge($faq, ['type' => 'faq', 'sort_order' => $i + 1, 'status' => 1]));
            }
        }

        // ── Testimonials ─────────────────────────────────────────────────────

        if (FrontendContent::where('type', 'testimonial')->count() === 0) {
            $testimonials = [
                ['name' => 'Ahmed Rahman',    'sub_title' => 'E-commerce Owner',          'description' => 'SocialAgent cut our response time from 4 hours to under 30 seconds. Our customer satisfaction score jumped by 40%.', 'rating' => 5],
                ['name' => 'Sarah Johnson',   'sub_title' => 'Digital Marketing Manager', 'description' => 'The keyword rules engine alone saved us 20 hours of manual work per week. Absolutely worth every penny.',          'rating' => 5],
                ['name' => 'Carlos Mendez',   'sub_title' => 'Agency Founder',            'description' => 'We manage 15 client pages from one dashboard. The AI handles 90% of messages — our team focuses on strategy.',      'rating' => 4],
            ];
            foreach ($testimonials as $i => $t) {
                FrontendContent::create(array_merge($t, ['type' => 'testimonial', 'sort_order' => $i + 1, 'status' => 1]));
            }
        }

        // ── About page ───────────────────────────────────────────────────────

        FrontendAbout::instance();
    }
}
