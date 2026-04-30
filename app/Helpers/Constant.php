<?php

// All status
const PAYMENT_STATUS_PENDING = 0;
const PAYMENT_STATUS_PAID = 1;
const PAYMENT_STATUS_CANCELLED = 2;

const STATUS_PENDING = 0;
const STATUS_SUCCESS = 1;
const STATUS_REJECT = 3;
const STATUS_ACTIVE = 1;
const STATUS_DRAFT = 2;
const STATUS_DISABLE = 3;
const STATUS_DEACTIVATE = 3;
const STATUS_EXPIRED = 4;
const STATUS_SUSPENDED = 5;
const STATUS_CANCELED = 2;

const DURATION_MONTH = 1;
const DURATION_YEAR = 2;

// Payment types
const PAYMENT_TYPE_ONETIME   = 1;
const PAYMENT_TYPE_RECURRING = 2;

// Notify / template routing (customNotifyTemplate in Helper.php)
const NOTIFY_TYPE_EMAIL = 0;
const NOTIFY_TYPE_NOTIFICATION = 1;

// User Role Type
const USER_STATUS_ACTIVE = 1;
const USER_STATUS_INACTIVE = 0;

const USER_ROLE_SUPER_ADMIN = 1;
const USER_ROLE_ADMIN = 2;
const USER_ROLE_SUPER_ADMIN_STAFF = 3;
const USER_ROLE_ADMIN_STAFF = 4;


// shipping
const SHIPPING_METHOD_FREE = 1;
const SHIPPING_METHOD_PAID = 2;

// Message
const SOMETHING_WENT_WRONG = "Something went wrong! Please try again";
const CREATED_SUCCESSFULLY = "Created Successfully";
const FAVORITES_SUCCESSFULLY = "Image add to favorite list";
const FAVORITES_REMOVE_SUCCESSFULLY = "Image removed from favorite list";
const UPDATED_SUCCESSFULLY = "Updated Successfully";
const SUBMIT_SUCCESSFULLY = "Submit Successfully";
const STATUS_UPDATED_SUCCESSFULLY = "Status Updated Successfully";
const DELETED_SUCCESSFULLY = "Deleted Successfully";
const UPLOADED_SUCCESSFULLY = "Uploaded Successfully";
const DATA_FETCH_SUCCESSFULLY = "Data Fetch Successfully";
const SENT_SUCCESSFULLY = "Sent Successfully";
const PAY_SUCCESSFULLY = "Pay Successfully";
const ASSIGNED_SUCCESSFULLY = "Assigned Successfully";
const DO_NOT_HAVE_PERMISSION = 7;

// Currency placement
const CURRENCY_SYMBOL_BEFORE=1;

// storage driver
const STORAGE_DRIVER_PUBLIC = 'public';
const STORAGE_DRIVER_AWS = 'aws';
const STORAGE_DRIVER_WASABI = 'wasabi';
const STORAGE_DRIVER_VULTR = 'vultr';
const STORAGE_DRIVER_DO = 'do';

const ACTIVE = 1;
const DEACTIVATE = 0;
const INITIATE = 2;

const GATEWAY_MODE_LIVE = 1;
const GATEWAY_MODE_SANDBOX = 2;

//Gateway name
const PAYPAL = 'paypal';
const STRIPE = 'stripe';
const RAZORPAY = 'razorpay';
const INSTAMOJO = 'instamojo';
const MOLLIE = 'mollie';
const PAYSTACK = 'paystack';
const SSLCOMMERZ = 'sslcommerz';
const MERCADOPAGO = 'mercadopago';
const FLUTTERWAVE = 'flutterwave';
const BINANCE = 'binance';
const ALIPAY = 'alipay';
const BANK = 'bank';
const COINBASE = 'coinbase';
const PAYTM = 'paytm';
const MAXICASH = 'maxicash';
const IYZICO = 'iyzico';
const BITPAY = 'bitpay';
const ZITOPAY = 'zitopay';
const PAYHERE = 'payhere';
const CINETPAY = 'cinetpay';
const VOGUEPAY = 'voguepay';
const TOYYIBPAY = 'toyyibpay';
const PAYMOB = 'paymob';
const AUTHORIZE = 'authorize';
const XENDIT = 'xendit';
const PADDLE = 'paddle';
const BKASH = 'bkash';
const NAGAD = 'nagad';


const DEFAULT_COLOR = 1;
const CUSTOM_COLOR = 2;

const LINK_SAAS_ADDON = "";
const RECURRING_GATEWAY = ['stripe', 'paypal'];

// ─── SocialAgent: Platform Types ─────────────────────────────────────────────
const PLATFORM_FACEBOOK_PAGE = 1;
const PLATFORM_MESSENGER     = 2;
const PLATFORM_WHATSAPP      = 3;
const PLATFORM_INSTAGRAM     = 4;

// ─── SocialAgent: Conversation Statuses ──────────────────────────────────────
const CONVERSATION_STATUS_OPEN      = 1;
const CONVERSATION_STATUS_RESOLVED  = 2;
const CONVERSATION_STATUS_PENDING   = 3;
const CONVERSATION_STATUS_ESCALATED = 4;

// ─── SocialAgent: Message Direction ──────────────────────────────────────────
const MESSAGE_DIRECTION_INBOUND  = 1;
const MESSAGE_DIRECTION_OUTBOUND = 2;

// ─── SocialAgent: Message Sender Types ───────────────────────────────────────
const MESSAGE_SENDER_CUSTOMER    = 1;
const MESSAGE_SENDER_AI          = 2;
const MESSAGE_SENDER_HUMAN_ADMIN = 3;

// ─── SocialAgent: Message Statuses ───────────────────────────────────────────
const MESSAGE_STATUS_SENT      = 1;
const MESSAGE_STATUS_DELIVERED = 2;
const MESSAGE_STATUS_READ      = 3;
const MESSAGE_STATUS_FAILED    = 4;

// ─── SocialAgent: Keyword Match Types ────────────────────────────────────────
const KEYWORD_MATCH_CONTAINS    = 1;
const KEYWORD_MATCH_EXACT       = 2;
const KEYWORD_MATCH_STARTS_WITH = 3;

// ─── SocialAgent: Keyword Rule Actions ───────────────────────────────────────
const KEYWORD_ACTION_REPLY    = 'reply';
const KEYWORD_ACTION_ESCALATE = 'escalate';
const KEYWORD_ACTION_IGNORE   = 'ignore';

// ─── SocialAgent: Reply Template Platforms ────────────────────────────────────
const TEMPLATE_PLATFORM_ALL       = 'all';
const TEMPLATE_PLATFORM_FACEBOOK  = 'facebook';
const TEMPLATE_PLATFORM_WHATSAPP  = 'whatsapp';
const TEMPLATE_PLATFORM_INSTAGRAM = 'instagram';

// ─── SocialAgent: AI Providers ──────────────────────────────────────────────
const AI_PROVIDER_CLAUDE   = 'claude';
const AI_PROVIDER_OPENAI   = 'openai';
const AI_PROVIDER_GEMINI   = 'gemini';
const AI_PROVIDER_GROK     = 'grok';
const AI_PROVIDER_DEEPSEEK = 'deepseek';

// ─── SocialAgent: Claude Models ──────────────────────────────────────────────
const AI_MODEL_CLAUDE_SONNET = 'claude-sonnet-4-5';
const AI_MODEL_CLAUDE_OPUS   = 'claude-opus-4-5';
const AI_MODEL_CLAUDE_HAIKU  = 'claude-haiku-4-5';

// ─── SocialAgent: OpenAI / ChatGPT Models ────────────────────────────────────
const AI_MODEL_GPT4O         = 'gpt-4o';
const AI_MODEL_GPT4O_MINI    = 'gpt-4o-mini';
const AI_MODEL_GPT41         = 'gpt-4.1';
const AI_MODEL_O3_MINI       = 'o3-mini';

// ─── SocialAgent: Google Gemini Models ───────────────────────────────────────
const AI_MODEL_GEMINI_25_FLASH   = 'gemini-2.5-flash-preview-04-17';
const AI_MODEL_GEMINI_25_PRO     = 'gemini-2.5-pro-preview-05-06';
const AI_MODEL_GEMINI_20_FLASH   = 'gemini-2.0-flash';

// ─── SocialAgent: xAI Grok Models ────────────────────────────────────────────
const AI_MODEL_GROK3       = 'grok-3';
const AI_MODEL_GROK3_MINI  = 'grok-3-mini';

// ─── SocialAgent: DeepSeek Models ────────────────────────────────────────────
const AI_MODEL_DEEPSEEK_CHAT     = 'deepseek-chat';
const AI_MODEL_DEEPSEEK_REASONER = 'deepseek-reasoner';

// ─── SocialAgent: Language Mode ──────────────────────────────────────────────
const AI_LANGUAGE_AUTO = 'auto';

// ─── SocialAgent: Package Limit Rule Keys ─────────────────────────────────────
// These match the field names used inside getAdminLimit() in Helper.php
const RULES_PAGE_LIMIT    = 'page_limit';
const RULES_MESSAGE_LIMIT = 'message_limit';
