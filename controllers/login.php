<?php

namespace controllers;

use Exception;

class login extends controller
{

    public function __construct()
    {
        parent::__construct();
        $this->already_login();
        $this->cms_page('login');
        $this->set_login_referer();
    }

    public function login_validation()
    {
        if ($this->session('lstep') == '') {
            $_SESSION['lstep'] = 0;
        }
        try {
            $this->login();
            unset($_SESSION['lstep'], $_SESSION['er']);
            $this->login_referer();
            exit();
        } catch (Exception $ex) {
            $_SESSION['lstep']++;
            $this->session_msg($ex->getMessage(), 'error', 'Login');
        }
    }

    public function login()
    {
        $this->validate_post_token(true);
        $query = "SELECT id, publish, verified FROM users WHERE email='" . $this->replace_sql($this->post('login', 'email')) . "' AND password='" . $this->encrypt($this->post('login', 'password')) . "'";
        if (!$data = $this->db->select($query)) {
            throw new Exception('Invalid email or password!');
        }
        if ($data['publish'] == 'N') {
            throw new Exception('Your account is blocked. Please contact with administrator.');
        }
        if ($this->session('lstep') > 2) {
            if ($this->session('captcha', 'login') == '') {
                throw new Exception('Please enter the security captcha.');
            }
            if ($this->session('captcha', 'login') != $this->post('login', 'captcha')) {
                throw new Exception('Invalid security captcha, Please try again!');
            }
        }
        /*if ($data['verified'] == 'N') {
          throw new Exception('Your email is not verified. To verify go to forgot password.');
        }*/
        unset($data['publish']);

        $_SESSION['user'] = $data;
        $_SESSION['user']['login_time'] = date('Y-m-d H:i:s');
        $_SESSION['user']['login'] = true;
        $_SESSION['user']['reports'] = $this->db->freg("SELECT user_id, ad_id FROM users_ad_reports WHERE user_id='" . $this->replace_sql($data['id']) . "'", ['user_id'], 'ad_id');

        if ($this->post('login', 'remember') == 1) {
            $this->gen_cookie('user', array('id' => $data['id']));
        }
        $this->update_log($this->session('user', 'id'), 'login');
    }

}
