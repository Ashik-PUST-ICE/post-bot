<?php

namespace Database\Seeders;

use App\Models\ReplyTemplate;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Seeds comprehensive quick-reply templates for every admin user.
 *
 * Covers: price inquiry, order process, payment (bKash/Nagad/COD),
 * delivery, product details, comment-to-inbox, business contact, and more.
 *
 * Run: php artisan db:seed --class=ReplyTemplateSeeder
 */
class ReplyTemplateSeeder extends Seeder
{
    private array $templates = [

        // ── Welcome & General ────────────────────────────────────────────────

        [
            'title'    => '👋 Welcome Message',
            'platform' => TEMPLATE_PLATFORM_ALL,
            'content'  => "আস্সালামু আলাইকুম / Hello {customer_name}! 😊\n\n{business_name}-এ আপনাকে স্বাগতম! আমরা কীভাবে আপনাকে সাহায্য করতে পারি?\n\nHi {customer_name}! Welcome to {business_name}. How can we help you today? 🛍️",
        ],

        [
            'title'    => '🙏 Thank You for Contacting',
            'platform' => TEMPLATE_PLATFORM_ALL,
            'content'  => "ধন্যবাদ {customer_name} আমাদের সাথে যোগাযোগ করার জন্য! 🙏\n\nThank you for reaching out to {business_name}. We have received your message and will get back to you shortly.\n\nআমাদের টিম শীঘ্রই আপনার সাথে যোগাযোগ করবে। একটু অপেক্ষা করুন। ⏳",
        ],

        [
            'title'    => '⏰ Business Hours',
            'platform' => TEMPLATE_PLATFORM_ALL,
            'content'  => "আমাদের অফিস সময়:\n🕘 সকাল ৯টা – রাত ৯টা (শনি–বৃহস্পতি)\n🕌 শুক্রবার: দুপুর ২টা – রাত ৯টা\n\nBusiness Hours:\n🕘 9 AM – 9 PM (Sat–Thu)\n🕌 Friday: 2 PM – 9 PM\n\nআপনার প্রশ্ন আমাদের কার্যসময়ের মধ্যে পাঠান, আমরা দ্রুত উত্তর দেবো! 😊",
        ],

        // ── Price & Product Info ─────────────────────────────────────────────

        [
            'title'    => '💰 Price Inquiry — Send Product Details',
            'platform' => TEMPLATE_PLATFORM_ALL,
            'content'  => "হ্যালো {customer_name}! 👋\n\nআপনি কোন পণ্যের দাম জানতে চাইছেন, দয়া করে পণ্যের নাম/কোড/লিংক পাঠান।\n\nPlease share the product name, code or link and we will send you the full price and details from {business_name} right away! 📋",
        ],

        [
            'title'    => '📋 Full Product Details (Price + Info)',
            'platform' => TEMPLATE_PLATFORM_ALL,
            'content'  => "✅ পণ্যের বিস্তারিত / Product Details:\n\n🏷️ পণ্যের নাম: [Product Name]\n💰 মূল্য / Price: ৳ [Amount] টাকা\n📦 স্টক: পাওয়া যাচ্ছে ✅\n🎨 কালার/সাইজ: [Colors / Sizes]\n📐 Material: [Material]\n\n🚚 ডেলিভারি চার্জ:\n• ঢাকার ভেতরে: ৳৬০–৮০\n• ঢাকার বাইরে: ৳১০০–১৩০\n\n📞 অর্ডার করতে বা আরো তথ্যের জন্য ইনবক্স করুন অথবা WhatsApp করুন।\n\n{business_name} — আপনার বিশ্বস্ত অনলাইন শপ 🛍️",
        ],

        [
            'title'    => '📦 Product Available — Price Confirmed',
            'platform' => TEMPLATE_PLATFORM_ALL,
            'content'  => "সুখবর {customer_name}! ✅\n\nআপনার পছন্দের পণ্যটি এখন স্টকে আছে!\n\n✅ পণ্য: [Product Name]\n💰 মূল্য: ৳[Amount] (সব চার্জ সহ)\n🎁 অফার: [Any Offer / Discount]\n\nঅর্ডার করতে আপনার নাম, ঠিকানা এবং ফোন নম্বর পাঠান। আমরা দ্রুত কনফার্ম করবো! 😊\n\n{business_name} 🛍️",
        ],

        [
            'title'    => '❌ Out of Stock',
            'platform' => TEMPLATE_PLATFORM_ALL,
            'content'  => "দুঃখিত {customer_name}! 😔\n\nএই পণ্যটি এখন স্টকে নেই।\n\n📩 আমরা আপনাকে জানাবো যখন স্টকে আসবে — শুধু আপনার নাম ও ফোন নম্বর পাঠান।\n\nSorry! This item is currently out of stock at {business_name}. We will notify you as soon as it's back! 📬",
        ],

        [
            'title'    => '🎨 Size / Color Selection',
            'platform' => TEMPLATE_PLATFORM_ALL,
            'content'  => "হ্যালো {customer_name}! 😊\n\nএই পণ্যটি নিচের সাইজ ও কালারে পাওয়া যায়:\n\n📐 সাইজ: S / M / L / XL / XXL\n🎨 কালার: [Available Colors]\n\nআপনার পছন্দের সাইজ ও কালার জানান, আমরা দ্রুত কনফার্ম করবো! ✅",
        ],

        // ── Order Process ────────────────────────────────────────────────────

        [
            'title'    => '🛒 How to Order (Step by Step)',
            'platform' => TEMPLATE_PLATFORM_ALL,
            'content'  => "অর্ডার করার নিয়ম / How to Order from {business_name}:\n\n1️⃣ পণ্যের নাম ও পরিমাণ জানান\n2️⃣ আপনার নাম লিখুন\n3️⃣ সম্পূর্ণ ঠিকানা দিন (জেলা সহ)\n4️⃣ মোবাইল নম্বর দিন\n5️⃣ পেমেন্ট করুন (bKash / Nagad / COD)\n\nঅর্ডার কনফার্ম হলে আমরা আপনাকে জানাবো! 📦✅",
        ],

        [
            'title'    => '✅ Order Confirmed',
            'platform' => TEMPLATE_PLATFORM_ALL,
            'content'  => "✅ আপনার অর্ডার কনফার্ম হয়েছে {customer_name}!\n\n📦 পণ্য: [Product Name]\n💰 মোট মূল্য: ৳[Amount]\n🚚 ডেলিভারি: [Delivery Date/Time]\n\nআমরা শীঘ্রই আপনার পার্সেল পাঠাবো। ট্র্যাকিং নম্বর পেলে জানাবো। ধন্যবাদ {business_name} বেছে নেওয়ার জন্য! 🙏",
        ],

        [
            'title'    => '📮 Order Shipped — Tracking Info',
            'platform' => TEMPLATE_PLATFORM_ALL,
            'content'  => "সুখবর {customer_name}! 🎉 আপনার পার্সেল পাঠানো হয়েছে!\n\n🚚 কুরিয়ার: [Courier Name]\n🔢 ট্র্যাকিং নম্বর: [Tracking ID]\n📍 ট্র্যাক করুন: [Tracking Link]\n⏳ ডেলিভারি: [Expected Days] কার্যদিবস\n\nযেকোনো সমস্যায় আমাদের জানান। ধন্যবাদ! {business_name} 🛍️",
        ],

        // ── Payment ──────────────────────────────────────────────────────────

        [
            'title'    => '💳 Payment Methods (bKash / Nagad / COD)',
            'platform' => TEMPLATE_PLATFORM_ALL,
            'content'  => "আমাদের পেমেন্ট পদ্ধতি / Payment Methods — {business_name}:\n\n📱 bKash (Personal): 01XXXXXXXXX\n📱 Nagad (Personal): 01XXXXXXXXX\n🏦 Bank Transfer: [Bank Name — Account No]\n🚚 Cash on Delivery (COD): ঢাকার ভেতরে পাওয়া যায়\n\nপেমেন্টের পর Transaction ID এবং আপনার নাম পাঠান। ✅",
        ],

        [
            'title'    => '📱 bKash Payment Details',
            'platform' => TEMPLATE_PLATFORM_ALL,
            'content'  => "bKash পেমেন্ট করুন এই নম্বরে:\n\n📱 bKash Number: 01XXXXXXXXX (Personal)\n💰 Amount: ৳[Amount]\n\nSend Money করার পর:\n✅ Transaction ID (TrxID)\n✅ আপনার নাম\n✅ ফোন নম্বর\n\n— এগুলো আমাদের ইনবক্সে পাঠান। তারপর অর্ডার কনফার্ম হবে। 🎉",
        ],

        [
            'title'    => '📱 Nagad Payment Details',
            'platform' => TEMPLATE_PLATFORM_ALL,
            'content'  => "Nagad পেমেন্ট করুন এই নম্বরে:\n\n📱 Nagad Number: 01XXXXXXXXX (Personal)\n💰 Amount: ৳[Amount]\n\nSend Money করার পর:\n✅ Transaction ID\n✅ আপনার নাম ও ফোন নম্বর\n\n— ইনবক্সে পাঠান। অর্ডার ২৪ ঘণ্টার মধ্যে শিপ হবে। 📦",
        ],

        [
            'title'    => '🚚 Cash on Delivery (COD) Info',
            'platform' => TEMPLATE_PLATFORM_ALL,
            'content'  => "Cash on Delivery (COD) সুবিধা পাচ্ছেন! 🎉\n\n✅ পণ্য পেয়ে টাকা দিন\n✅ শুধুমাত্র ঢাকার ভেতরে\n⚠️ ঢাকার বাইরে: অগ্রিম ৫০% পেমেন্ট প্রয়োজন\n\n📞 অর্ডার করতে আপনার নাম, ঠিকানা ও ফোন নম্বর পাঠান। {business_name} 🙏",
        ],

        // ── Delivery ─────────────────────────────────────────────────────────

        [
            'title'    => '🚚 Delivery Info — Inside & Outside Dhaka',
            'platform' => TEMPLATE_PLATFORM_ALL,
            'content'  => "ডেলিভারি তথ্য / Delivery Info — {business_name}:\n\n📍 ঢাকার ভেতরে:\n• চার্জ: ৳৬০–৮০\n• সময়: ১–২ কার্যদিবস\n\n🚛 ঢাকার বাইরে (সারা বাংলাদেশ):\n• চার্জ: ৳১০০–১৩০\n• সময়: ২–৫ কার্যদিবস\n\n✅ সকল জেলায় ডেলিভারি দেওয়া হয়! 📦",
        ],

        // ── Contact & Business Info ──────────────────────────────────────────

        [
            'title'    => '📞 Business Contact & WhatsApp Number',
            'platform' => TEMPLATE_PLATFORM_ALL,
            'content'  => "আমাদের সাথে যোগাযোগ করুন / Contact {business_name}:\n\n📞 Phone: 01XXXXXXXXX\n💬 WhatsApp: 01XXXXXXXXX (সরাসরি চ্যাট)\n📧 Email: info@yourdomain.com\n📍 Address: [Your Business Address]\n🌐 Website: www.yourdomain.com\n\n⏰ সময়: সকাল ৯টা – রাত ৯টা (শুক্রবার ছাড়া)",
        ],

        [
            'title'    => '💬 WhatsApp-এ যোগাযোগ করুন',
            'platform' => TEMPLATE_PLATFORM_FACEBOOK,
            'content'  => "হ্যালো {customer_name}! 👋\n\nআরো তথ্যের জন্য আমাদের WhatsApp-এ মেসেজ করুন:\n👉 wa.me/8801XXXXXXXXX\n\nবা সরাসরি এই নম্বরে কল / WhatsApp করুন:\n📞 01XXXXXXXXX\n\n{business_name} আপনার সেবায় সর্বদা প্রস্তুত! 🙏",
        ],

        // ── Facebook Comment Replies ─────────────────────────────────────────

        [
            'title'    => '💬 FB Comment → Inbox করুন',
            'platform' => TEMPLATE_PLATFORM_FACEBOOK,
            'content'  => "ধন্যবাদ {customer_name} কমেন্ট করার জন্য! 🙏\n\nবিস্তারিত তথ্য ও অর্ডারের জন্য আমাদের ইনবক্স / DM করুন অথবা সরাসরি কল করুন:\n📞 01XXXXXXXXX\n\nআমরা দ্রুত উত্তর দেবো! ✅ {business_name} 🛍️",
        ],

        [
            'title'    => '💬 FB Comment — Price Interested',
            'platform' => TEMPLATE_PLATFORM_FACEBOOK,
            'content'  => "হ্যালো {customer_name}! 😊\n\nএই পণ্যটির দাম ও বিস্তারিত তথ্যের জন্য আমাদের ইনবক্স করুন বা সরাসরি WhatsApp করুন:\n📱 01XXXXXXXXX\n\nস্টকে থাকা পণ্য দ্রুত শেষ হয়ে যায় — আজই অর্ডার করুন! ✅\n{business_name} 🛍️",
        ],

        [
            'title'    => '💬 FB Comment — Order Now CTA',
            'platform' => TEMPLATE_PLATFORM_FACEBOOK,
            'content'  => "আগ্রহের জন্য ধন্যবাদ {customer_name}! 💙\n\n✅ এখনই অর্ডার করতে:\n👉 ইনবক্স করুন অথবা\n👉 📞 01XXXXXXXXX (Call / WhatsApp)\n\nআমরা সারা বাংলাদেশে ডেলিভারি দিই! 🚚\n{business_name} 🛍️",
        ],

        // ── WhatsApp Specific ────────────────────────────────────────────────

        [
            'title'    => '🟢 WA — Full Order Form',
            'platform' => TEMPLATE_PLATFORM_WHATSAPP,
            'content'  => "অর্ডার ফর্ম পূরণ করুন / Please fill the order form:\n\n📝 পণ্যের নাম:\n📐 সাইজ / কালার:\n🔢 পরিমাণ:\n👤 আপনার নাম:\n📍 সম্পূর্ণ ঠিকানা (জেলা সহ):\n📞 মোবাইল নম্বর:\n💳 পেমেন্ট পদ্ধতি (bKash / Nagad / COD):\n\nএই তথ্য পাঠালে আমরা দ্রুত কনফার্ম করবো! ✅ {business_name}",
        ],

        [
            'title'    => '🟢 WA — Payment Received Confirmation',
            'platform' => TEMPLATE_PLATFORM_WHATSAPP,
            'content'  => "✅ পেমেন্ট পেয়েছি, {customer_name}!\n\n💰 Amount: ৳[Amount]\n🔢 TrxID: [Transaction ID]\n\nআপনার অর্ডার কনফার্ম হয়েছে। পার্সেল [X] কার্যদিবসের মধ্যে পাঠানো হবে।\n\nট্র্যাকিং নম্বর পেলে জানাবো। ধন্যবাদ! 🙏 {business_name}",
        ],

        // ── Instagram ────────────────────────────────────────────────────────

        [
            'title'    => '📸 IG — DM Reply',
            'platform' => TEMPLATE_PLATFORM_INSTAGRAM,
            'content'  => "Hey {customer_name}! 🌟 Thanks for reaching out to {business_name}.\n\nFor product details, pricing and ordering — please send us a DM or contact us on WhatsApp:\n📱 01XXXXXXXXX\n\nWe deliver all over Bangladesh! 🇧🇩🚚",
        ],

        // ── After Sales ──────────────────────────────────────────────────────

        [
            'title'    => '🔄 Return / Refund Policy',
            'platform' => TEMPLATE_PLATFORM_ALL,
            'content'  => "আমাদের রিটার্ন পলিসি / Return Policy — {business_name}:\n\n✅ পণ্য পাওয়ার ২৪ ঘণ্টার মধ্যে সমস্যা জানান\n✅ ত্রুটিপূর্ণ পণ্য বিনামূল্যে বদলে দেওয়া হবে\n❌ ব্যবহার করা পণ্য ফেরত নেওয়া হয় না\n\n📞 সমস্যায় কল করুন: 01XXXXXXXXX\nবা ইনবক্স করুন — আমরা সমাধান করবো! 🙏",
        ],

        [
            'title'    => '⭐ Review Request (After Delivery)',
            'platform' => TEMPLATE_PLATFORM_ALL,
            'content'  => "ধন্যবাদ {customer_name} {business_name} থেকে কেনাকাটা করার জন্য! 🙏🎉\n\nআপনার পণ্য পেয়ে সন্তুষ্ট হলে আমাদের পেজে একটি রিভিউ দিন — এতে আমাদের অনেক উৎসাহ হয়! ⭐⭐⭐⭐⭐\n\nপুনরায় কেনাকাটায় স্বাগতম! 😊",
        ],

        [
            'title'    => '🤝 Wholesale / Bulk Order',
            'platform' => TEMPLATE_PLATFORM_ALL,
            'content'  => "হোলসেল / বাল্ক অর্ডারের জন্য ধন্যবাদ {customer_name}! 🤝\n\n{business_name}-এ পাইকারি রেটে পণ্য পাওয়া যায়।\n\n📦 Minimum Order: [MOQ]\n💰 Special Price: পরিমাণ অনুযায়ী\n📞 আলোচনার জন্য: 01XXXXXXXXX (WhatsApp)\n\nআজই যোগাযোগ করুন এবং সেরা দাম নিশ্চিত করুন! ✅",
        ],

        [
            'title'    => '⚠️ Escalate — Senior Support',
            'platform' => TEMPLATE_PLATFORM_ALL,
            'content'  => "প্রিয় {customer_name}, আপনার সমস্যার জন্য দুঃখিত। 🙏\n\nআপনার বিষয়টি আমাদের সিনিয়র সাপোর্ট টিমে পাঠানো হয়েছে। একজন দায়িত্বশীল ব্যক্তি শীঘ্রই আপনার সাথে যোগাযোগ করবেন।\n\nঅথবা এখনই কল করুন: 📞 01XXXXXXXXX\n\nঅসুবিধার জন্য আন্তরিক ক্ষমাপ্রার্থী। {business_name} 🙏",
        ],
    ];

    public function run(): void
    {
        $users = User::whereNotNull('id')->get(['id', 'tenant_id']);

        foreach ($users as $user) {
            // Skip users who already have templates to avoid duplicates
            if (ReplyTemplate::where('user_id', $user->id)->exists()) {
                continue;
            }

            foreach ($this->templates as $tpl) {
                ReplyTemplate::create([
                    'user_id'   => $user->id,
                    'tenant_id' => $user->tenant_id,
                    'title'     => $tpl['title'],
                    'platform'  => $tpl['platform'],
                    'content'   => $tpl['content'],
                    'status'    => 1,
                ]);
            }
        }

        $this->command->info(count($this->templates) . ' reply templates seeded per user successfully.');
    }
}
