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

// User Role Type
const USER_STATUS_ACTIVE = 1;
const USER_STATUS_INACTIVE = 0;
const USER_ROLE_ADMIN = 2;
const USER_ROLE_USER = 3;
const USER_ROLE_EMPLOYEE = 4;
const USER_ROLE_SUPER_ADMIN = 1;

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


const DEFAULT_COLOR = 1;
const CUSTOM_COLOR = 2;

const LINK_SAAS_ADDON = "";
const RECURRING_GATEWAY = ['stripe', 'paypal'];
