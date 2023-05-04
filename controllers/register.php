<?php
 
 namespace controllers;
 
 use Exception;
 
 class register extends controller
 {
  
  public function __construct()
  {
   parent::__construct();
   $this->cms_page('register');
   $this->already_login();
   $this->page['name'] = 'Register';
  }
  
  public function register()
  {
   $this->validate_post_token(true);
   if ($this->post('register') == '') {
    throw new Exception(_('Oops, something went wrong.'));
   }
   $post = $this->post('register');
   if ($this->varv('first_name', $post) == '') {
    throw new Exception(_('Please enter your first name.'));
   }
   if ($this->varv('last_name', $post) == '') {
    throw new Exception(_('Please enter your last name.'));
   }
   if ($this->varv('email', $post) == '') {
    throw new Exception(_('Please enter your email address.'));
   }
   if (filter_var($this->varv('email', $post), FILTER_VALIDATE_EMAIL) === false) {
    throw new Exception(_('Please enter valid email address.'));
   }
   if ($this->db->value_exists('users', 'email', $this->varv('email', $post))) {
    throw new Exception(_('Email already exists in our records.'));
   }
   if ($this->varv('mobile_no', $post) == '') {
    throw new Exception(_('Please enter your mobile no.'));
   }
   if ($this->varv('password', $post) == '') {
    throw new Exception(_('Please enter your password.'));
   }
   if ($this->varv('confirm_password', $post) == '') {
    throw new Exception(_('Please enter your confirm password.'));
   }
   if ($this->varv('password', $post) != $this->varv('confirm_password', $post)) {
    throw new Exception('Passwords does not matched.');
   }
   if ($this->varv('captcha', $post) == '') {
    throw new Exception(_('Please enter the security captcha.'));
   }
   if ($this->varv('captcha', $post) != $this->session('captcha', 'register')) {
    throw new Exception(_('Invalid security captcha, Please try again.'));
   }
   if ($this->varv('terms', $post) != 1) {
    throw new Exception('You must agree with our terms and conditions and privacy policy.');
   }
   
   
   $date = date('Y-m-d H:i:s');
   $id = $this->db->insert('users', array(
    'gender' => $this->varv('gender', $post),
    'first_name' => $this->varv('first_name', $post),
    'last_name' => $this->varv('last_name', $post),
    'display_name' => ($this->varv('first_name', $post) . ' ' . $this->varv('last_name', $post)),
    'email' => $this->varv('email', $post),
    'mobile_no' => $this->varv('mobile_no', $post),
    'password' => $this->varv('password', $post),
    'add_date' => $date,
    'ip' => $this->server('REMOTE_ADDR'),
    'browser' => $this->get_browser(),
    'os' => $this->get_os()));
   
   $this->v_code($id, 'Register', 60);
   $this->send_email('register_verification', $id);
   $this->send_email('new_registration', $id);
   return $id;
  }
  
  
 }
