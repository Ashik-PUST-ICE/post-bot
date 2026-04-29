<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\CustomerEmail;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * AdminMailController
 *
 * Handles everything mail-related for the admin panel:
 *  1. SMTP Configuration — read/write .env MAIL_* settings
 *  2. Email Templates    — list + edit templates for this user
 *  3. Send to Customer   — compose and send a custom email directly
 */
class AdminMailController extends Controller
{
    // ─── 1. SMTP Configuration ────────────────────────────────────────────────

    public function configIndex()
    {
        $data = [
            'title'           => __('Mail Configuration'),
            'activeMailConfig' => 'active',
            'mail'            => [
                'mailer'       => env('MAIL_MAILER', 'smtp'),
                'host'         => env('MAIL_HOST', ''),
                'port'         => env('MAIL_PORT', 587),
                'username'     => env('MAIL_USERNAME', ''),
                'password'     => env('MAIL_PASSWORD', ''),
                'encryption'   => env('MAIL_ENCRYPTION', 'tls'),
                'from_address' => env('MAIL_FROM_ADDRESS', ''),
                'from_name'    => env('MAIL_FROM_NAME', config('app.name')),
            ],
        ];

        return view('admin.mail.config', $data);
    }

    public function configSave(Request $request)
    {
        $request->validate([
            'mail_host'         => 'required|string|max:255',
            'mail_port'         => 'required|integer',
            'mail_username'     => 'required|string|max:255',
            'mail_password'     => 'nullable|string|max:255',
            'mail_encryption'   => 'required|in:tls,ssl,starttls,none',
            'mail_from_address' => 'required|email',
            'mail_from_name'    => 'required|string|max:255',
        ]);

        try {
            $this->writeEnv([
                'MAIL_MAILER'       => $request->mail_mailer ?? 'smtp',
                'MAIL_HOST'         => $request->mail_host,
                'MAIL_PORT'         => $request->mail_port,
                'MAIL_USERNAME'     => $request->mail_username,
                'MAIL_PASSWORD'     => $request->mail_password ?? '',
                'MAIL_ENCRYPTION'   => $request->mail_encryption,
                'MAIL_FROM_ADDRESS' => $request->mail_from_address,
                'MAIL_FROM_NAME'    => '"' . $request->mail_from_name . '"',
            ]);

            // Also persist FROM address for getOption() usage
            setOption('MAIL_FROM_ADDRESS', $request->mail_from_address);

            return response()->json(['status' => true, 'message' => __('Mail configuration saved successfully.')]);
        } catch (\Exception $e) {
            Log::error('Mail config save failed', ['error' => $e->getMessage()]);
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    public function configTest(Request $request)
    {
        $request->validate(['test_email' => 'required|email']);

        try {
            Mail::to($request->test_email)->send(
                new CustomerEmail(
                    __('Test Email from ') . config('app.name'),
                    __('This is a test email to confirm your SMTP configuration is working correctly.')
                )
            );

            return response()->json(['status' => true, 'message' => __('Test email sent to ') . $request->test_email]);
        } catch (\Exception $e) {
            Log::error('Mail test failed', ['error' => $e->getMessage()]);
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    // ─── 2. Email Templates ───────────────────────────────────────────────────

    public function templates()
    {
        $templates = EmailTemplate::where('user_id', auth()->id())->get();

        // Create default templates if none exist yet
        if ($templates->isEmpty()) {
            $this->seedDefaultTemplates();
            $templates = EmailTemplate::where('user_id', auth()->id())->get();
        }

        return view('admin.mail.templates', [
            'title'                => __('Email Templates'),
            'activeMailTemplates'  => 'active',
            'templates'            => $templates,
        ]);
    }

    public function getTemplate(Request $request)
    {
        $template = EmailTemplate::where('user_id', auth()->id())->findOrFail($request->id);
        return response()->json(['status' => true, 'data' => $template]);
    }

    public function updateTemplate(Request $request)
    {
        $request->validate([
            'id'      => 'required|integer',
            'subject' => 'required|string|max:255',
            'body'    => 'required|string',
        ]);

        try {
            $template = EmailTemplate::where('user_id', auth()->id())->findOrFail($request->id);
            $template->update([
                'subject' => $request->subject,
                'body'    => $request->body,
            ]);

            return response()->json(['status' => true, 'message' => __('Template updated successfully.')]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    // ─── 3. Send Email to Customer ────────────────────────────────────────────

    public function sendToCustomer(Request $request)
    {
        $request->validate([
            'to_email'   => 'required|email',
            'subject'    => 'required|string|max:255',
            'body'       => 'required|string',
        ]);

        try {
            Mail::to($request->to_email)->send(
                new CustomerEmail($request->subject, $request->body)
            );

            Log::info('Admin sent email to customer', [
                'admin_id' => auth()->id(),
                'to'       => $request->to_email,
                'subject'  => $request->subject,
            ]);

            return response()->json(['status' => true, 'message' => __('Email sent successfully to ') . $request->to_email]);
        } catch (\Exception $e) {
            Log::error('Send to customer failed', ['error' => $e->getMessage()]);
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    // ─── Private Helpers ──────────────────────────────────────────────────────

    /**
     * Write key=value pairs to the .env file.
     */
    private function writeEnv(array $values): void
    {
        $envPath = base_path('.env');
        $content = file_get_contents($envPath);

        foreach ($values as $key => $value) {
            // Replace existing key
            if (preg_match("/^{$key}=.*/m", $content)) {
                $content = preg_replace("/^{$key}=.*/m", "{$key}={$value}", $content);
            } else {
                // Append if not found
                $content .= "\n{$key}={$value}";
            }
        }

        file_put_contents($envPath, $content);
    }

    /**
     * Seed default email templates for this admin.
     */
    private function seedDefaultTemplates(): void
    {
        $userId = auth()->id();

        $defaults = [
            [
                'user_id'  => $userId,
                'category' => 'order_confirmation',
                'title'    => 'Order Confirmation',
                'slug'     => 'order-confirmation',
                'subject'  => 'Your Order Has Been Confirmed! 🎉',
                'body'     => "Hi {customer_name},\n\nThank you for your order! We're pleased to confirm that your order has been received and is being processed.\n\nOrder Details:\n- Order ID: {order_id}\n- Amount: {amount}\n- Payment Method: {payment_method}\n\nDelivery Information:\nYour order will be delivered to your address within 3-5 business days.\n\nFor any questions, please reply to this email or contact us on WhatsApp.\n\nThank you for shopping with us!\n\n{business_name}",
                'status'   => 1,
            ],
            [
                'user_id'  => $userId,
                'category' => 'order_shipped',
                'title'    => 'Order Shipped',
                'slug'     => 'order-shipped',
                'subject'  => 'Your Order Is On The Way! 🚚',
                'body'     => "Hi {customer_name},\n\nGreat news! Your order has been shipped and is on its way to you.\n\nTracking Information:\n- Tracking ID: {tracking_id}\n- Courier: {courier_name}\n- Estimated Delivery: {delivery_date}\n\nYou can track your order using the tracking ID above.\n\nIf you have any questions, feel free to reach out to us.\n\nThank you!\n{business_name}",
                'status'   => 1,
            ],
            [
                'user_id'  => $userId,
                'category' => 'payment_received',
                'title'    => 'Payment Received',
                'slug'     => 'payment-received',
                'subject'  => 'Payment Confirmed ✅',
                'body'     => "Hi {customer_name},\n\nWe have successfully received your payment. Thank you!\n\nPayment Details:\n- Amount: {amount}\n- Transaction ID: {transaction_id}\n- Date: {date}\n- Method: {payment_method}\n\nYour order is now being prepared for dispatch.\n\nThank you for your trust in us!\n{business_name}",
                'status'   => 1,
            ],
            [
                'user_id'  => $userId,
                'category' => 'custom_message',
                'title'    => 'Custom Message',
                'slug'     => 'custom-message',
                'subject'  => 'Message from {business_name}',
                'body'     => "Hi {customer_name},\n\n{message}\n\nBest regards,\n{business_name}",
                'status'   => 1,
            ],
        ];

        foreach ($defaults as $tpl) {
            EmailTemplate::firstOrCreate(
                ['user_id' => $userId, 'category' => $tpl['category']],
                $tpl
            );
        }
    }
}
