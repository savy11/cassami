<?php

namespace controllers;

use Exception;

class contact_us extends controller
{

    public function __construct()
    {
        parent::__construct();
        $this->cms_page('contact-us');
    }

    public function contact_enq()
    {
        $this->validate_post_token(true);
        if ($this->post('contact') == '') {
            throw new Exception('Oops, something went wrong.');
        }
        if ($this->post('contact', 'name') == '') {
            throw new Exception('Please enter your name.');
        }
        if ($this->post('contact', 'email') == '') {
            throw new Exception('Please enter your email address.');
        }
        if (filter_var($this->post('contact', 'email'), FILTER_VALIDATE_EMAIL) === false) {
            throw new Exception('Please enter valid email address.');
        }
        /*   if ($this->post('no') == '') {
          throw new Exception('Please enter your contact number.');
          } */
        if ($this->post('contact', 'subject') == '') {
            throw new Exception('Please enter your subject.');
        }
        if ($this->post('contact', 'message') == '') {
            throw new Exception('Please enter your message.');
        }
        if ($this->post('contact', 'captcha') == '') {
            throw new Exception('Please enter security captcha.');
        }
        if ($this->post('contact', 'captcha') != $this->session('captcha', 'contact')) {
            throw new Exception('Invalid security code, try again.');
        }
        $id = $this->db->insert('v_contacts', array(
            'display_name' => $this->post('contact', 'name'),
            'email' => $this->post('contact', 'email'),
            'contact_no' => $this->post('contact', 'no'),
            'subject' => $this->post('contact', 'subject'),
            'message' => $this->post('contact', 'message'),
            'ip' => $this->server('REMOTE_ADDR'),
            'browser' => $this->get_browser(),
            'os' => $this->get_os(),
            'add_date' => date('Y-m-d H:i:s')
        ));

        $this->send_email('contact', $id);
        $this->send_email('contact_thanks', $id);
    }

}
