<?php

namespace controllers;

use Exception;
use resources\models\pagination as pagination;

class account extends controller
{

    public $order = false;

    public function __construct()
    {
        parent::__construct();
        $this->require_login();
    }

    public function update_details()
    {
        $this->validate_post_token(true);
        if ($this->post('update') == '') {
            throw new Exception('Oops, something went wrong.');
        }
        $post = $this->post('update');
        if ($this->varv('first_name', $post) == '') {
            throw new Exception('Please enter your first name.');
        }
        if ($this->varv('last_name', $post) == '') {
            throw new Exception('Please enter your last name.');
        }
        if ($this->varv('mobile_no', $post) == '') {
            throw new Exception('Please enter your mobile no.');
        }
        $display_name = ($this->varv('first_name', $post) . ' ' . $this->varv('last_name', $post));
        $state = $this->db->get_value('m_states', 'state_name', 'id="' . $this->replace_sql($this->varv('state_id', $post)) . '"');
        $city = $this->db->get_value('m_cities', 'city_name', 'state_id="' . $this->replace_sql($this->varv('state_id', $post)) . '" AND id="' . $this->replace_sql($this->varv('city_id', $post)) . '"');
        $this->db->update('users', array(
            'gender' => $this->varv('gender', $post),
            'display_name' => $display_name,
            'first_name' => $this->varv('first_name', $post),
            'last_name' => $this->varv('last_name', $post),
            'mobile_no' => $this->varv('mobile_no', $post),
            'state_id' => $this->varv('state_id', $post),
            'state' => $state,
            'city_id' => $this->varv('city_id', $post),
            'city' => $city,
        ), array('id' => $this->session('user', 'id')));
    }

    public function change_password()
    {
        $this->validate_post_token(true);
        if ($this->post('change') == '') {
            throw new Exception('Oops, something went wrong.');
        }
        $_POST = $this->post('change');
        if ($this->post('old') == '') {
            throw new Exception('Please enter your old password.');
        }

        $pass = $this->db->select("SELECT password FROM users WHERE id='" . $this->session('user', 'id') . "'");
        if ($this->encrypt($this->post('old')) != $pass['password']) {
            throw new Exception('Your old password is not correct.');
        }
        if ($this->post('new') == '') {
            throw new Exception('Please enter your new password.');
        }
        if ($this->post('retype') == '') {
            throw new Exception('Please retype your new password.');
        }
        if ($this->post('new') != $this->post('retype')) {
            throw new Exception('Your new password and retype new password does not match.');
        }
        $this->db->update('users', array('password' => $this->post('new')), array('id' => $this->user['id']));
        $this->send_email('change_pass', $this->user['id']);
    }

    public function update_email()
    {
        $this->validate_post_token(true);
        if ($this->post('mail') == '') {
            throw new Exception('Oops, something went wrong.');
        }
        $_POST = $this->post('mail');
        if ($this->post('old_email') == '') {
            throw new Exception('Please enter your old email address.');
        }

        if ($this->post('new_email') == '') {
            throw new Exception('Please enter your new email address.');
        }

        if (filter_var($this->post('old_email'), FILTER_VALIDATE_EMAIL) === false) {
            throw new Exception(_('Please enter valid old email address.'));
        }

        if (filter_var($this->post('new_email'), FILTER_VALIDATE_EMAIL) === false) {
            throw new Exception(_('Please enter valid new email address.'));
        }

        $exists = $this->db->select("SELECT id FROM users WHERE id='" . $this->session('user', 'id') . "' AND email='" . $this->post('old_email') . "'");
        if (!$exists) {
            throw new Exception(_('Old email is not matched with your account email.'));
        }

        if ($this->post('old_email') == $this->post('new_email')) {
            throw new Exception('Your old and new email address must not be same.');
        }
        $id = $this->session('user', 'id');
        $this->db->update('users', array('email' => $this->post('new_email'), 'verified' => 'N'), array('id' => $id));
        $this->v_code($id, 'Register', 60);
        $this->send_email('register_verification', $id);
    }

    public function new_ads()
    {
        $query = "SELECT id, ad_title, ad_price, promoted, add_date FROM m_ads  WHERE ad_user_id='" . $this->replace_sql($this->session('user', 'id')) . "' ORDER BY id DESC LIMIT 3";
        $this->data = $this->db->freg_all($query, ['id'], ['ad_title', 'ad_price', 'promoted', 'add_date']);
    }

    public function remove_fav()
    {
        if ($this->get('id') == '') {
            throw new Exception('Oops, something is wrong, Please try again.');
        }
        $this->db->delete('users_fav_ad', ['id' => $this->get('id')]);
    }

    public function favourites()
    {
        $query = "SELECT a.id, a.ad_title, a.promoted, fv.id as fav_id, fv.add_date, c.category_name, f.meta_value as ad_image FROM users_fav_ad fv "
            . "INNER JOIN m_ads a ON fv.ad_id=a.id AND a.deleted='N' "
            . "LEFT OUTER JOIN m_ads_cat c ON a.ad_category_id=c.id "
            . "LEFT OUTER JOIN files f ON a.ad_image=f.id "
            . "WHERE fv.user_id='" . $this->replace_sql($this->session('user', 'id')) . "' "
            . "ORDER BY a.id DESC";
        $this->pagination = new pagination($this, $this->db, $query, 15);
        $this->data = $this->pagination->paging('fv.id');
        $this->sno = $this->pagination->get_sno();
    }

    public function my_ads()
    {
        $query = "SELECT a.*, c.category_name, f.meta_value as ad_image FROM m_ads a "
            . "LEFT OUTER JOIN m_ads_cat c ON a.ad_category_id=c.id "
            . "LEFT OUTER JOIN files f ON a.ad_image=f.id "
            . "WHERE a.ad_user_id='" . $this->replace_sql($this->session('user', 'id')) . "' AND a.deleted='N' "
            . "ORDER BY a.id DESC";
        $this->pagination = new pagination($this, $this->db, $query, 15);
        $this->data = $this->pagination->paging('a.id');
        $this->sno = $this->pagination->get_sno();
    }

    public function my_ad()
    {
        $query = "SELECT * FROM m_ads WHERE id='" . $this->replace_sql($this->get('id')) . "' AND deleted='N'";
        if (!$this->data = $this->db->select($query)) {
            $this->not_found();
        }

        $query = "SELECT * FROM files WHERE type_id='" . $this->data['id'] . "' AND type='ads' AND table_name='m_ads'";
        $this->data['ads'] = $this->db->selectall($query);

        $query = "SELECT CONCAT(type, '_', REPLACE(label, ' ', '||'), '_', label_id) as id, IF(type='S', value_id, value) as value FROM m_ads_filter WHERE ad_id='" . $this->data['id'] . "' ORDER BY id";
        $this->data['filter'] = $this->db->freg($query, ['id'], 'value');
        $this->populate_post_data();
    }

}
