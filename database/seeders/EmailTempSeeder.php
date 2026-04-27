<?php

namespace Database\Seeders;

use App\Models\EmailTemplate;
use Illuminate\Database\Seeder;

class EmailTempSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['user_id' => 1, 'tenant_id' => null, 'category' => 'Email Verify', 'subject' => 'Verify Your Account', 'title' => 'Email Verification', 'slug' => 'email-verify', 'body' => '<p>Hello, {{username}}</p><p>Thank you for creating an account with us. We\'re excited to have you as a part of our community! Before you can start using your account, we need to verify your email address.</p> <p>OTP: {{otp}}</p>', 'default' => 1, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => 1, 'tenant_id' => null, 'category' => 'reset-password', 'subject' => 'Reset your password', 'title' => 'Password Reset', 'slug' => 'password-reset', 'body' => '<div><b>Hello</b> ,{{username}}</div><div><br></div><div>we\'re sending you this email because you requested a password reset. Click on this link to create a new password.</div><div><br></div><div>Set a new password . Here is a link -</div><div><br></div><div>Link :&nbsp;{{reset_password_url}}</div><div><br></div><div>If you didn\'t request a password reset, you can ignore this email. Your password will not be a changed.</div>', 'default' => 1, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],

            ['user_id' => 1, 'tenant_id' => null, 'category' => 'Subscription Paid Notify For Super Admin', 'subject' => 'Subscription Paid Notify For Super Admin', 'title' => 'Subscription Paid Notify For Super Admin', 'slug' => 'subscription-paid-notify-for-super-admin', 'body' => 'Subscription Paid Notify For Admin', 'default' => 1, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],

            ['user_id' => 1, 'tenant_id' => null, 'category' => 'Subscription Cancel Notify For Super Admin', 'subject' => 'Subscription Cancel Notify For Super Admin', 'title' => 'Subscription cancel Notify For Super Admin', 'slug' => 'subscription-cancel-notify-for-super-admin', 'body' => 'Subscription Cancel Notify For Admin', 'default' => 1, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],

            ['user_id' => 1, 'tenant_id' => null, 'category' => 'subscription-update', 'subject' => 'Saas Subscription Notify', 'title' => 'Welcome,{username}', 'slug' => 'saas-subscription-notify', 'body' => '<p>Hello, {{username}}, . Please click on the link and see your subscription details:</p>', 'default' => 1, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],


            ['user_id' => 2, 'tenant_id' => 'zainiklab', 'category' => 'Employee Create Notify', 'subject' => 'Employee Create Notify', 'title' => 'Employee Create Notify', 'slug' => 'employee-create-notify', 'body' => '<p>Hello, {{username}}, Thank you for creating an account with us. We\'re excited to have you as a part of our community! Before you can start using your account, we need to verify your email address. Please click on the link below to complete the verification process:</p>', 'default' => 1, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],

            ['user_id' => 2, 'tenant_id' => 'zainiklab', 'category' => 'Department Head Assign Notify', 'subject' => 'Department Head Assign Notify', 'title' => 'Welcome,{username}', 'slug' => 'department-head-assign-notify', 'body' => '<p>Hello, {{username}}, Thank you for creating an account with us. We\'re excited to have you as a part of our community! Before you can start using your account, we need to verify your email address. Please click on the link below to complete the verification process:</p>', 'default' => 1, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],

            ['user_id' => 2, 'tenant_id' => 'zainiklab', 'category' => 'Employee Session Assign Notify', 'subject' => 'Employee Session Assign Notify', 'title' => 'Welcome,{username}', 'slug' => 'employee-session-assign-notify', 'body' => '<p>Dear, {{username}}, We are pleased to inform you that you have been assigned to participate in a new session. This session is scheduled to take place on Date at time and will be conducted. Please click on the link below to complete the Goal process:</p>', 'default' => 1, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],

            ['user_id' => 2, 'tenant_id' => 'zainiklab', 'category' => 'Employee Goal Submit Notify', 'subject' => 'Employee Goal Submit Notify', 'title' => 'Welcome,{username}', 'slug' => 'employee-goal-submit-notify', 'body' => '<p>Dear, {{username}}, I hope this message finds you well. This is a friendly reminder regarding the upcoming deadline for goal submission.Please take the time to review your objectives and submit your goals by the specified deadline. If you encounter any difficulties or have questions regarding the goal-setting process so please cancel your Goal. Please click on the link below to complete the process:</p>', 'default' => 1, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],

            ['user_id' => 2, 'tenant_id' => 'zainiklab', 'category' => 'Goal Approved Notify', 'subject' => 'Goal Approved Notify', 'title' => 'Welcome,{username}, Your goal is Approved', 'slug' => 'goal-approved-notify-for-next-approval', 'body' => '<p>Hello, {{username}}, I am pleased to inform you that your proposed goals  have been reviewed and approved. Please click on the link and see your goal details:</p>', 'default' => 1, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],

            ['user_id' => 2, 'tenant_id' => 'zainiklab', 'category' => 'Goal Approved Notify', 'subject' => 'Goal Approved Notify', 'title' => 'Welcome,{username}, Your goal is Approved', 'slug' => 'goal-approved-notify-for-goal-creator', 'body' => '<p>Hello, {{username}}, I am pleased to inform you that your proposed goals  have been reviewed and approved. Please click on the link and see your goal details:</p>', 'default' => 1, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],

            ['user_id' => 2, 'tenant_id' => 'zainiklab', 'category' => 'Goal Back Notify', 'subject' => 'Goal Back Notify', 'title' => 'Welcome,{username}, Your goal is Back', 'slug' => 'goal-back-notify', 'body' => '<p>Hello, {{username}}, I hope this message finds you well. I am reaching out to inform you about the cancellation of the goal previously assigned to you and cearfully resubmit your goal. Please click on the link and see your goal details:</p>', 'default' => 1, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],

            ['user_id' => 2, 'tenant_id' => 'zainiklab', 'category' => 'Goal Resubmit Notify', 'subject' => 'Goal Resubmit Notify', 'title' => 'Welcome,{username} Resubmit Goal', 'slug' => 'goal-resubmit-notify', 'body' => '<p>Hello, {{username}}, I hope this message finds you well. I am writing to remind you about the need to resubmit your Goal. Please click on the link and see your goal details:</p>', 'default' => 1, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],

            ['user_id' => 2, 'tenant_id' => 'zainiklab', 'category' => 'Approval Process Date End Notify', 'subject' => 'Approval Process Date End Notify', 'title' => 'Welcome,{username}, Your approval process date is end', 'slug' => 'goal-final-approved', 'body' => '<p>Hello, {{username}}, I hope this message finds you well. This is to inform you that the approval process has now reached its scheduled end date. Please click on the link and see your approval process details:</p>', 'default' => 1, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
        ];
        EmailTemplate::insert($data);
    }
}
