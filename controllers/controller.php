<?php

namespace controllers;

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'sessions.php';

use Exception;
use resources\controllers\controller as main;

class controller extends main
{

    public $style = '', $script = '', $pagination = null, $sno = 0, $rows = array();
    public $socials = array(), $modal = array();
    public $filter = array(), $ip = array(), $common = array();

    public function __construct($main_db = false, $need_db = true, $c = true)
    {
        parent::__construct($main_db, $need_db);
        if ($need_db && $c) {
            $this->company_detail();
            $this->ip = $this->ip_api();
            if (!$this->is_ajax_call()) {
                $this->auto_login();
                if ($this->get('page_url') == 'account') {
                    $this->redirecting('account/dashboard');
                }
            }
            if ($this->session('user', 'login')) {
                $this->auth();
            }
        }
    }

    public function company_detail()
    {
        $query = "SELECT * FROM a_company WHERE id='1'";
        $this->company = $this->db->select($query);
        $this->company['email'] = $this->json_decode($this->company['email']);
        $site_url = parse_url(app_url);
        $this->company['site_url'] = 'www.' . $site_url['host'];
        $this->socials = $this->db->selectall("SELECT * FROM a_socials WHERE publish='Y' ORDER BY title");
        $this->common['cats'] = $this->db->freg_all("SELECT id, category_name, page_url FROM m_ads_cat WHERE publish='Y' AND master_id='0' ORDER BY category_name", ['id'], ['category_name', 'page_url']);

        $query = "SELECT id, page_title, page_url FROM m_pages WHERE publish='Y' AND quick_link='Y' ORDER BY id LIMIT 6";
        $this->common['quick'] = $this->db->freg_all($query, ['id'], ['id', 'page_title', 'page_url']);

        $query = "SELECT id, page_title, page_url FROM m_pages WHERE publish='Y' AND help_link='Y' ORDER BY id LIMIT 6";
        $this->common['help'] = $this->db->freg_all($query, ['id'], ['id', 'page_title', 'page_url']);
    }

    function ip_api($ip = NULL, $track = true)
    {
        $result = [];
        $ip = $_SERVER['REMOTE_ADDR'];
        if (!is_null($ip)) {
            $ip = $ip;
        }
        if (local) {
            $ip = '103.217.123.3';
//    $ip = '72.229.28.185';
        }
        $user_id = '0';
        if ($this->validate_login()) {
            $user_id = $this->session('user', 'id');
        }
        $date = date('Y-m-d H:i:s');
        $current_url = $this->current_url();
        $info = pathinfo($current_url);
        if ($this->varv('extension', $info)) {
            if (in_array(strtok($info['extension'], '?'), array_merge($this->allowed_file_formats, ['map', 'gif', 'svg', 'css', 'js']))) {
                return;
            }
            if (in_array(strtok($info['filename'], '?'), ['captcha'])) {
                return;
            }
        }
        if ($exists = $this->db->select("SELECT id, json FROM ip_info WHERE ip='" . $this->replace_sql($ip) . "'")) {
            $result = $this->json_decode($exists['json']);
            if ($track) {
                $this->db->insert('ip_visits', ['user_id' => $user_id, 'ip_id' => $exists['id'], 'visit_url' => $current_url, 'visit_date' => $date]);
            }
        } else {
            $ip_json = @file_get_contents('http://ip-api.com/json/' . $ip, true);
            if ($ip_json) {
                $ip_data = $this->json_decode($ip_json);
                if ($ip_data['status'] == 'success') {
                    $result = $ip_data;
                    if ($track) {
                        $id = $this->db->insert('ip_info', ['ip' => $ip, 'json' => $ip_json, 'add_date' => $date]);
                        $this->db->insert('ip_visits', ['user_id' => $user_id, 'ip_id' => $id, 'visit_url' => $current_url, 'visit_date' => $date]);
                    }
                }
            }
        }
        return $result;
    }

    public function auto_login()
    {
        if ($this->cookie('user') && $this->session('user', 'login') == '') {
            try {
                $query = "SELECT id, publish FROM users WHERE publish='Y' AND id='" . $this->cookie('user', 'id') . "'";
                if (!$data = $this->db->select($query)) {
                    $this->clear_cookie('user');
                    throw new Exception('Your account does not exists. May be deleted by administrator.');
                }
                if ($data['publish'] == 'N') {
                    $this->clear_cookie('user');
                    throw new Exception('Your account is not publish. Please contact with administrator.');
                }
                unset($data['publish']);

                $_SESSION['user'] = $data;
                $_SESSION['user']['login_time'] = date('Y-m-d H:i:s');
                $_SESSION['user']['login'] = true;

                $this->gen_cookie('user', array('id' => $data['id']));
                $this->update_log($this->session('user', 'id'), 'auto_login');
                $this->redirect($this->server('REQUEST_URI'));
            } catch (Exception $ex) {
                $this->session_msg($ex->getMessage(), 'error', 'Login');
                $this->redirecting('login');
            }
        }
    }

    public function update_log($id, $type)
    {
        $columns = array('user_id' => $id, 'session_id' => session_id(), 'type' => $type, 'ip' => $this->server('REMOTE_ADDR'), 'browser' => $this->get_browser(), 'os' => $this->get_os(), 'log_date' => date('Y-m-d H:i:s'));
        $this->db->insert('logs', $columns);
    }

    public function redirect($url = '')
    {
        if ($url == '') {
            $url = $this->permalink();
        }
        header('HTTP/1.1 301 Moved Permanently');
        header('Location:' . $url);
        exit();
    }

    public function session_msg($message, $type, $id = '', $title = '')
    {
        if ($title == '') {
            $title = $this->cms['page_title'];
        }
        $_SESSION['er']['title'] = $title;
        $_SESSION['er']['message'] = $message;
        $_SESSION['er']['type'] = $type;
        if ($id) {
            $_SESSION['er']['id'] = $id;
        }
    }

    public function redirecting($type = '', $dt = array())
    {
        $this->redirect($this->permalink($type, $dt));
    }

    public function auth()
    {
        $query = "SELECT id, gender, first_name, last_name, display_name, email, mobile_no, state_id, state, city_id, city, verified, add_date FROM users u WHERE u.id='" . $this->session('user', 'id') . "'";
        $this->user = $this->db->select($query);
    }

    public function cms_page($page_url = '', $key_type = '', $freg = false)
    {
        $fields = "";
        if ($key_type) {
            $fields = ", '{$key_type}'as key_type";
        }
        $query = "SELECT f.meta_value as image,h.meta_value as header_image, p.page_title, p.page_heading, p.page_desc, p.meta_keywords, p.meta_desc{$fields} FROM m_pages p LEFT OUTER JOIN files f ON p.image=f.id LEFT OUTER JOIN files h ON p.header_image=h.id WHERE p.page_url='" . $this->replace_sql($page_url) . "' AND p.publish='Y'";
        if ($freg) {
            return $this->db->freg_all($query, array('key_type'), array('page_title', 'page_heading', 'page_desc', 'meta_keywords', 'meta_desc'));
        } else {
            $this->cms = $this->db->select($query);
        }
    }

    public function show_er_msg($id = null)
    {
        $msg = '';
        if ($this->session('er') != '' && count($this->session('er')) > 0) {
            if ($id) {
                if ($this->session('er', 'id') != $id) {
                    return;
                }
            }
            $type = $this->session('er', 'type');
            if ($type == 'error') {
                $type = 'danger';
            }
            $msg = '<div class="alert-box alert alert-' . $type . '">' . $this->replace_sql($this->session('er', 'message')) . '</div>';
        }
        unset($_SESSION['er']);
        return $msg;
    }

    public function not_found()
    {
        header('HTTP/1.1 404 Not Found');
        include_once(app_path . ds . '404.php');
        exit();
    }


    public function files($ids = '')
    {
        if (!$ids) {
            return '';
        }
        $query = "SELECT id, meta_value FROM files WHERE id IN({$ids}) ORDER BY id";
        return $this->db->selectall($query);
    }

    public function check_v_code($id, $type, $code = '')
    {
        if (!$data = $this->get_v_code($id, $type)) {
            throw new Exception('May be verification expired or deleted.');
        }
        if (time() > strtotime($data['expiry_date'])) {
            $this->update_v_code($data['id'], 'E');
            throw new Exception('Verification code is expired.');
        }
        if ($data['code'] != $code) {
            throw new Exception('Verification code is invalid.');
        }
        return $data;
    }

    public function get_v_code($id, $type = null)
    {
        $query = "SELECT * FROM v_codes WHERE id='" . $id . "' AND type='" . $type . "' AND status='N'";
        if ($data = $this->db->select($query)) {
            if (time() > strtotime($data['expiry_date'])) {
                $this->update_v_code($data['id'], 'E');
                return false;
            }
        }
        return $data;
    }

    public function update_v_code($id, $status = 'Y')
    {
        $this->db->update('v_codes', array('status' => $status), array('id' => $id));
    }


    /** Auth * */
    public function already_login()
    {
        if ($this->session('user', 'id') != '') {
            if ($this->session('user', 'login') == true) {
                $this->redirecting('account/dashboard');
            }
        }
    }

    public function require_login()
    {
        if ($this->session('user', 'login') == '') {
            $this->set_login_referer();
            $this->redirecting('login');
        }
    }

    public function set_login_referer()
    {
        $url = request_scheme . $this->server('HTTP_HOST') . $this->server('REQUEST_URI');
        if (!$this->is_ajax_call()) {
            $url = ($this->server('HTTP_REFERER') != '' ? $this->server('HTTP_REFERER') : '');
        }
        $_SESSION['login_url'] = $url;
    }

    public function login_referer()
    {
        $url = $this->permalink('account/dashboard');
        if ($this->session('login_url') != '') {
            $url = $this->session('login_url');
            unset($_SESSION['login_url']);
        }
        $this->redirect($url);
    }

    public function validate_login()
    {
        if ($this->session('user', 'login') != '') {
            return true;
        }
        return false;
    }

    public function show_list($list, $sel = '', $empty = true)
    {
        $str = '';
        if ($empty) {
            $str = '<option value=""></option>';
        }
        if ($list) {
            $key = current(array_keys($list));
            if (isset($list[$key]) && is_array($list[$key]) && count($list[$key]) > 1) {
                $keys = array_keys($list[$key]);
                $ele = $keys[0];
                unset($keys[0]);
                $str = "<option value=\"\" data-" . strtolower(str_replace("_", "", implode("='' data-", $keys))) . "=\"\"></option>";
                foreach ($list as $k => $v) {
                    $values = array();
                    foreach ($v as $a => $b) {
                        if ($ele != $a) {
                            $values[] = "data-" . strtolower(str_replace("_", "", $a)) . "='" . $b . "'";
                        }
                    }
                    $str .= '<option value="' . $k . '"' . (strpos(',' . $sel . ',', ',' . $k . ',') !== false ? ' selected' : '') . ' ' . implode(' ', $values) . '>' . $this->make_html($v[$ele]) . '</option>';
                }
            } else {
                foreach ($list as $k => $v) {
                    $str .= '<option value="' . $k . '"' . (strpos(',' . $sel . ',', ',' . $k . ',') !== false ? 'selected' : '') . '>' . $this->make_html($v) . '</option>';
                }
            }
        }
        return $str;
    }

    public function filter_array($type)
    {
        $data = $this->post_get($type);
        if (is_array($data)) {
            $data = implode(',', $data);
        }
        $_POST[$type] = $data;
        $_GET[$type] = $data;
        $this->filter[$type] = $data;
        return $data;
    }

    public function share_url($type = 'fb', $dt = array())
    {
        $url = urlencode($dt['ogurl']);
        if ($type == 'fb') {
            $url = 'https://www.facebook.com/sharer.php?u=' . $url;
        } else if ($type == 'tw') {
            $site = tw_site;
            $url = 'https://twitter.com/intent/tweet?url=' . $dt['ogurl'] . '&text=' . $dt['ogtitle'] . (isset($site) && !empty($site) ? '&via=' . $site : '');
        } else if ($type == 'pi') {
            $url = 'https://pinterest.com/pin/create/button/?url=' . $url . '&description=' . $this->show_string($dt['ogtitle'], 250) . '&media=' . $dt['ogimage'];
        } else if ($type == 'li') {
            $url = 'https://www.linkedin.com/shareArticle?mini=true&url=' . $dt['ogurl'];
        } else if ($type = 'em') {
            $url = 'mailto:enteryour@addresshere.com?subject=' . $dt['ogtitle'] . '&body=Check%20this%20out:%20' . $dt['ogurl'];
        }
        return $url;
    }

    /** V Code * */
    public function v_code($id, $type = null, $mins = 10)
    {
        $code = $this->gen_code(6, false);
        $date = date('Y-m-d H:i:s');
        $code_expiry = date('Y-m-d H:i:s', strtotime($date . ' + ' . $mins . ' mins'));

        $code_id = $this->db->insert('v_codes', array('type_id' => $id, 'session_id' => session_id(), 'code' => $code, 'expiry_date' => $code_expiry, 'type' => $type, 'ip' => $this->server('REMOTE_ADDR'), 'browser' => $this->get_browser(), 'os' => $this->get_os(), 'add_date' => $date, 'update_date' => $date));
        return array('code' => $code, 'code_id' => $code_id);
    }

    public function register_verification()
    {
        if ($this->get('key') == '') {
            throw new Exception('Oops, Invalid Link.');
        }
        $data = $this->decrypt_post_data($this->get('key'));
        $query = "SELECT * FROM users WHERE email='" . $this->replace_sql($data['email']) . "'";
        if (!$dt = $this->db->select($query)) {
            throw new Exception(_('Oops, something went wrong.'));
        }
        if ($dt['publish'] == 'N') {
            throw new Exception(_('Your account is blocked. Please contact with administrator.'));
        }
        if ($dt['verified'] == 'Y') {
            throw new Exception(_('Your account is already verified.'));
        }
        if (!$this->get_v_code($data['code_id'], 'Register', $data['code'])) {
            throw new Exception(_('Oops, Verification code is expired, Please try again.'));
        }
        $this->db->update('users', array('verified' => 'Y'), array('email' => $data['email']));
        $this->db->update('v_subscribers', array('verified' => 'Y'), array('email' => $data['email']));
        $this->update_v_code($data['code_id']);
    }

    public function login()
    {
        $query = "SELECT id, first_name, last_name, email, mobile_no, publish, verified FROM users WHERE email='" . $this->replace_sql($this->post('email')) . "' AND password='" . $this->encrypt($this->post('password')) . "'";
        if (!$data = $this->db->select($query)) {
            throw new Exception('Invalid email or password!');
        }
        if ($data['publish'] == 'N') {
            throw new Exception('Your account is blocked. Please contact with administrator.');
        }
        unset($data['publish'], $data['verified']);

        $_SESSION['user'] = $data;
        $_SESSION['user']['login_time'] = date('Y-m-d H:i:s');
        $_SESSION['user']['login'] = true;

        if ($this->post('remember') == 1) {
            $this->gen_cookie('user', array('id' => $data['id']));
        }
        $this->update_log($this->session('user', 'id'), 'login');
    }


    /*  Post Ads  */

    public function get_ads_cat()
    {
        $query = "SELECT id, category_name FROM m_ads_cat WHERE publish='Y' AND master_id='0' ORDER BY id";
        return $this->db->freg($query, ['id'], 'category_name');
    }

    public function get_ads_subcat($master_id = NULL)
    {
        $result = [];
        if (!is_null($master_id) && !empty($master_id)) {
            $query = "SELECT id, category_name FROM m_ads_cat WHERE publish='Y' AND master_id='" . $this->replace_sql($master_id) . "' ORDER BY id";
            $result = $this->db->freg($query, ['id'], 'category_name');
        }
        return $result;
    }

    public function get_filters($sub_cat_id = NULL)
    {
        $result = [];
        if (!is_null($sub_cat_id)) {
            $query = "SELECT * FROM m_filters WHERE sub_category_id='" . $this->replace_sql($sub_cat_id) . "' AND publish='Y'";
            if ($filters = $this->db->select($query)) {
                $query = "SELECT * FROM m_filters_label WHERE af_id='" . $this->replace_sql($filters['id']) . "' ORDER BY id";
                $labels = $this->db->freg_all($query, ['id'], ['af_id', 'title', 'placeholder', 'type', 'req']);
                foreach ($labels as $k => $v) {
                    if ($v['type'] == 'S') {
                        $query = "SELECT l.id, l.label_id, l.linked_id FROM m_filters_link l WHERE l.label_id='" . $this->replace_sql($k) . "'";
                        $link = $this->db->select($query);
                        if ($link) {
                            if ($link['linked_id'] > 0) {
                                $nested = $this->db->select('SELECT id, title FROM m_filters_label WHERE id="' . $this->replace_sql($link['linked_id']) . '"');
                                $labels[$nested['id']]['ajax'] = true;
                                $labels[$k]['nested_id'] = $nested['id'];
                                $labels[$k]['nested_label'] = $nested['title'];
                            }
                            $labels[$k]['linked_id'] = $link['linked_id'];
                            $query = "SELECT id, title FROM m_filters_value WHERE fl_id='" . $this->replace_sql($link['id']) . "' AND master_id='0' AND publish='Y' ORDER BY id";
                            $options = $this->db->freg($query, ['id'], 'title');
                            if ($options) {
                                $labels[$k]['options'] = $options;
                            } else {
                                $labels[$k]['options'] = [];
                            }
                        }
                    }
                }
                $result = $labels;
            }
        }
        return $result;
    }

    public function get_nested_filters($linked_id = NULL, $value_id = NULL, $rec = false)
    {
        $result = [];
        if ($rec) {
            $result = ['id' => 0, 'title' => '', 'html' => '<option value=""></option>'];
        }
        if (!is_null($linked_id) && !is_null($value_id)) {
            $query = "SELECT fl.id, fl.value_id, fl.label_id, fl.linked_id, l.title FROM m_filters_link fl "
                . "LEFT OUTER JOIN m_filters_label l ON fl.label_id=l.id "
                . "WHERE fl.linked_id='" . $this->replace_sql($linked_id) . "' AND fl.value_id='" . $this->replace_sql($value_id) . "'";
            $link = $this->db->select($query);
            if ($link) {
                $values = [];
                $query = "SELECT id, title FROM m_filters_value WHERE fl_id='" . $link['id'] . "' AND master_id='" . $link['value_id'] . "'";
                $values = $this->db->freg($query, ['id'], 'title');
                $result = $values;
                if ($rec) {
                    $result = ['id' => $link['label_id'], 'title' => $link['title'], 'html' => $this->show_list($values, '', true)];
                }
            }
        }
        return $result;
    }


    public function galleries($type = '', $data = array(), $default = 0)
    {
        include app_path . 'views' . ds . 'galleries.php';
    }

    public function get_states()
    {
        $query = "SELECT id, state_name FROM m_states WHERE publish='Y' ORDER BY state_name";
        return $this->db->freg($query, ['id'], 'state_name');
    }

    public function get_cities($state_id = NULL)
    {
        $result = [];
        if (!is_null($state_id)) {
            $query = "SELECT id, city_name FROM m_cities WHERE publish='Y' AND state_id='" . $this->replace_sql($state_id) . "' ORDER BY city_name";
            $result = $this->db->freg($query, ['id'], 'city_name');
        }
        return $result;
    }

    function initials($str)
    {
        preg_match_all('/[A-Z]/', $str, $matches);
        if (!empty($matches[0])) {
            $str = join($matches[0]);
            $initials = substr($str, 0, 2);
            $filename = upload_path . 'initials' . ds . strtolower($initials) . '.png';
            if (file_exists($filename)) {
                return upload_url . 'initials' . ds . strtolower($initials) . '.png';
            }
        }
        return upload_url . 'initials' . ds . 'no-user.png';
    }

    public function add_step()
    {
        if (!$this->validate_login()) {
            $this->set_login_referer();
            unset($_SESSION['add_step']);
            $this->redirecting('login');
        } else {
            if ($this->session('add_step') == '') {
                $_SESSION['add_step'] = 1;
            }
        }
    }


    public function preview()
    {
        if ($this->post()) {
            $data = $this->post();
        }
        if ($data) {
            $data['ad_state'] = $this->db->get_value('m_states', 'state_name', 'id="' . $this->replace_sql($this->post('ad_state_id')) . '"');
            $data['ad_city'] = $this->db->get_value('m_cities', 'city_name', 'state_id="' . $this->replace_sql($this->post('ad_state_id')) . '" AND id="' . $this->replace_sql($this->post('ad_city_id')) . '"');
            if ($data['filter']) {
                foreach ($data['filter'] as $k => $v) {
                    $is = explode('_', $k);
                    if ($is[0] == 'S') {
                        $data['filter'][$k] = ['id' => $v, 'value' => $this->db->get_value('m_filters_value', 'title', 'id="' . $this->replace_sql($v) . '"')];
                    } else {
                        $data['filter'][$k] = ['id' => 0, 'value' => $v];
                    }
                }
            }
        }
        $this->data = $_POST = array_replace_recursive($this->post(), $data);
    }

    public function post_ad()
    {
        try {
            if (!isset($_SESSION['ads']) || $_SESSION['ads'] == '') {
                throw new Exception('Please upload atleast one image.');
            }
            if ($this->session('ads') && !count($this->session('ads')) > 0) {
                throw new Exception('Please upload at least one image');
            }
            $this->validate_post_token(true);
            $this->db->trans_start();
            $this->preview();
            $id = $this->db->insert('m_ads', array(
                'ad_user_id' => $this->post('ad_user_id'),
                'ad_category_id' => $this->post('ad_category_id'),
                'ad_sub_category_id' => $this->post('ad_sub_category_id'),
                'ad_title' => $this->post('ad_title'),
                'ad_price' => $this->post('ad_price'),
                'ad_desc' => $this->post('ad_desc'),
                'ad_keywords' => $this->post('ad_keywords'),
                'ad_no' => $this->post('ad_no'),
                'ad_email' => $this->post('ad_email'),
                'ad_state_id' => $this->post('ad_state_id'),
                'ad_state' => $this->post('ad_state'),
                'ad_city_id' => $this->post('ad_city_id'),
                'ad_city' => $this->post('ad_city'),
                'add_date' => date('Y-m-d H:i:s')
            ));

            $columns = [];
            if ($this->post('filter')) {
                foreach ($this->post('filter') as $k => $v) {
                    $key = explode('_', $k);
                    $columns[] = [
                        'ad_id' => $id,
                        'type' => $key[0],
                        'label' => trim(str_replace('||', ' ', $key[1])),
                        'label_id' => $key[2],
                        'value_id' => $v['id'],
                        'value' => $v['value']
                    ];
                }
            }
            if ($columns) {
                $this->db->batch('insert', 'm_ads_filter', $columns);
            }

            if ($ids = $this->save_session_files('ads', 'm_ads', $id)) {
                $this->db->update('m_ads', array('ad_image' => $ids), array('id' => $id));
            }
            $this->db->trans_commit();
        } catch (Exception $ex) {
            $this->db->trans_rollback();
            throw new Exception($ex->getMessage());
        }
    }

    public function update_ad()
    {
        try {
            if ($this->get('id') == '') {
                throw new Exception('Oops, something is wrong, Please reload and try again.');
            }
            $id = $this->get('id');
            if ($this->session('ads') && !count($this->session('ads')) > 0) {
                throw new Exception('Please upload at least one image');
            }
            $this->validate_post_token(true);
            $this->db->trans_start();
            $this->preview();
            $this->db->update('m_ads', array(
                'ad_user_id' => $this->post('ad_user_id'),
                'ad_category_id' => $this->post('ad_category_id'),
                'ad_sub_category_id' => $this->post('ad_sub_category_id'),
                'ad_title' => $this->post('ad_title'),
                'ad_price' => $this->post('ad_price'),
                'ad_sold' => $this->post('ad_sold'),
                'ad_desc' => $this->post('ad_desc'),
                'ad_keywords' => $this->post('ad_keywords'),
                'ad_no' => $this->post('ad_no'),
                'ad_email' => $this->post('ad_email'),
                'ad_state_id' => $this->post('ad_state_id'),
                'ad_state' => $this->post('ad_state'),
                'ad_city_id' => $this->post('ad_city_id'),
                'ad_city' => $this->post('ad_city'),
                'update_date' => date('Y-m-d H:i:s')
            ), ['id' => $id]);

            $query = "DELETE FROM m_ads_filter WHERE ad_id='" . $this->replace_sql($id) . "'";
            $this->db->query($query);

            $columns = [];
            if ($this->post('filter')) {
                foreach ($this->post('filter') as $k => $v) {
                    $key = explode('_', $k);
                    $columns[] = [
                        'ad_id' => $id,
                        'type' => $key[0],
                        'label' => trim(str_replace('||', ' ', $key[1])),
                        'label_id' => $key[2],
                        'value_id' => $v['id'],
                        'value' => $v['value']
                    ];
                }
            }
            if ($columns) {
                $this->db->batch('insert', 'm_ads_filter', $columns);
            }

            if ($ids = $this->save_session_files('ads', 'm_ads', $id)) {
                $this->db->update('m_ads', array('ad_image' => $ids), array('id' => $id));
            }
            $this->db->trans_commit();
        } catch (Exception $ex) {
            $this->db->trans_rollback();
            throw new Exception($ex->getMessage());
        }
    }

    public function delete_ad()
    {
        if ($this->get('id') == '') {
            throw new Exception('Oops, something is wrong. Please reload and try again');
        }
        $this->db->update('m_ads', ['deleted' => 'Y'], ['id' => $this->get('id')]);
    }


    public function ad_favourite()
    {
        if (!$this->validate_login()) {
            throw new Exception('Please login first to save this ad as favourite');
        }
        if ($this->post('id') == '') {
            throw new Exception('Oops, something is wrong, Please reload the page and try agin');
        }
        $query = "SELECT id FROM users_fav_ad WHERE user_id='" . $this->session('user', 'id') . "' AND ad_id='" . $this->post('id') . "'";
        $exists = $this->db->select($query);
        if ($exists) {
            throw new Exception('Ad already exists in your favourites');
        }
        $this->db->insert('users_fav_ad', ['user_id' => $this->session('user', 'id'), 'ad_id' => $this->post('id'), 'add_date' => date('Y-m-d H:i:s')]);
    }

    public function report_ad()
    {
        if (!$this->validate_login()) {
            throw new Exception('Please login first to report this ad');
        }
        if ($this->post('id') == '') {
            throw new Exception('Oops, something is wrong, Please reload the page and try agin');
        }
        $id = $this->db->insert('users_ad_reports', [
            'ad_id' => $this->post('id'),
            'user_id' => $this->session('user', 'id'),
            'ad_title' => $this->post('title'),
            'reason' => $this->post('reason'),
            'add_date' => date('Y-m-d H:i:s')
        ]);
        $_SESSION['user']['reports'] = array_merge_recursive($_SESSION['user']['reports'], [$id]);
        $this->send_email('report_ad', $id);
    }

    /*
* Subscribe
* */

    public function subscribe()
    {
        $this->validate_post_token(true);
        if ($this->post('sub') == '') {
            throw new Exception('Oops, something went wrong.');
        }
        $_POST = $this->post('sub');
        if ($this->post('email') == '') {
            throw new Exception('Please enter your email address.');
        }
        if (filter_var($this->post('email'), FILTER_VALIDATE_EMAIL) === false) {
            throw new Exception('Please enter valid email address.');
        }
        $query = "SELECT s.*, v.id as code_id, v.code, v.expiry_date FROM v_subscribers s LEFT OUTER JOIN v_codes v ON v.type_id=s.id AND v.type='Subscribe' AND v.status='N' WHERE s.email='" . $this->replace_sql($this->post('email')) . "'";
        if ($data = $this->db->select($query)) {
            $id = $data['id'];
            if ($data['verified'] == 'N') {
                if ($data['code_id'] != '') {
                    if (!$data = $this->check_v_code($data['code_id'], 'Subscribe', $data['code'])) {
                        $this->send_email('verify_subscriber', $id);
                        $data = $this->v_code($id, 'Subscribe');
                    }
                } else {
                    $this->send_email('verify_subscriber', $id);
                    $data = $this->v_code($id, 'Subscribe');
                }
                $_SESSION['subscriber'] = array('id' => $id, 'code' => $data['code'], 'email' => $this->post('email'));
                return false;
            }
            throw new Exception('Email already exists in our records.');
        }
        $id = $this->db->insert('v_subscribers', array('email' => $this->post('email'), 'ip' => $this->server('REMOTE_ADDR'), 'browser' => $this->get_browser(), 'os' => $this->get_os(), 'add_date' => date('Y-m-d H:i:s')));
        $data = $this->v_code($id, 'Subscribe');
        $_SESSION['subscriber'] = array('id' => $id, 'code' => $data['code']);
        $this->send_email('verify_subscriber', $id);
    }

    public function verify_subscriber()
    {
        $this->validate_post_token(true);
        if ($this->post('sub') == '') {
            throw new Exception('Oops, something went wrong.');
        }
        $_POST = $this->post('sub');
        if ($this->post('code') == '') {
            throw new Exception('Please enter your verification code.');
        }
        if (!is_numeric($this->post('code'))) {
            throw new Exception('Only numbers are allowed.');
        }
        if ($this->post('code') != $this->session('subscriber', 'code')) {
            throw new Exception('Your verification code is not correct.');
        }
        $this->db->update('v_subscribers', array('verified' => 'Y'), array('id' => $this->session('subscriber', 'id')));
        $this->db->update('v_codes', array('status' => 'Y'), array('code' => $this->post('code')));
        unset($_SESSION['subscriber']);
    }


    public function faqs()
    {
        $query = "SELECT * FROM m_faqs WHERE publish='Y' ORDER BY id DESC";
        return $this->db->selectall($query);
    }

}
