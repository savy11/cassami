<?php

 @ini_set('zlib.output_compression', 1);
 @ini_set('post_max_size', '20M');
 @ini_set('upload_max_size', '20M');
 define('environment', 'development');
 define('default_timezone', 'Asia/Kolkata');

 if (environment) {
  switch (environment) {
   case 'development':
    error_reporting(E_ALL);
    break;
   case 'production':
    error_reporting(0);
    break;
   default:
    exit('The application environment is not set correctly.');
  }
 }
 date_default_timezone_set(default_timezone);
 define('app_name', 'Cassami - Home For All');
 define('app_email', 'info@cassami.com');
 define('ds', '/');
 define('encrypt_key', 'CA&%$SS%$AMI#$%%');
 define('cookie_key', 'cassami');
 define('cookie_encrypt', true);
 define('date_format', 'Y-m-d');
 define('date_disp_format', 'd M, Y');
 define('time_format', 'H:i:s');
 define('copyright_year', 2017);
 define('domain', $_SERVER['HTTP_HOST']);
 define('local', (domain == 'localhost' ? true : false));
 if (local) {
  define('domain_path', ds . 'cassami.com' . ds);
 } else {
  define('domain_path', ds . 'demo' . ds);
 }
 define('request_scheme', 'http' . (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 's' : '') . '://');
 define('app_url', request_scheme . domain . domain_path);
 define('app_path', $_SERVER['DOCUMENT_ROOT'] . domain_path);
 define('admin_url', app_url . 'admin' . ds);
 define('admin_path', app_path . 'admin' . ds);
 define('upload_url', app_url . 'resources' . ds . 'files' . ds);
 define('upload_path', app_path . 'resources' . ds . 'files' . ds);

 // Twitter
 define('tw_site', '');

 //Paystack

 // Live Keys
 //define('ps_secret_key', 'sk_live_a23a082f462f4b50d0c389ea68b5c60389ffcf96');
 //define('ps_public_key', 'pk_live_0f0fddef523cfb502dfc9587cbd2b6bd42ba80f8');

 // Test Keys
 define('ps_secret_key', 'sk_test_c5c6998decb3f04249459e7aaf4921c7ff56ed37');
 define('ps_public_key', 'pk_test_1f58bc677369e299cd9e39e1e7c3ed1ac785e004 ');