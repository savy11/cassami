<?php

namespace controllers;

use Exception;
use resources\models\pagination as pagination;

class ads extends controller
{

    public $url = '';
    public $comments = array(), $replies = array();

    public function __construct()
    {
        parent::__construct();
        $this->cms_page('ads');
        $this->rows['load'] = 52;
    }

    public function ads()
    {
        $where = "WHERE a.publish='Y' AND a.deleted='N' AND a.ad_sold='N'";
        if ($this->post_get('q') != '') {
            $this->filter_array('q');
            $where .= " AND (a.ad_title LIKE '%" . $this->replace_sql($this->post_get('q')) . "%' OR a.ad_keywords LIKE '%" . $this->replace_sql($this->post_get('q')) . "%' OR a.ad_desc LIKE '%" . $this->replace_sql($this->post_get('q')) . "%' OR c.category_name LIKE '%" . $this->replace_sql($this->post_get('q')) . "%' OR s.category_name LIKE '%" . $this->replace_sql($this->post_get('q')) . "%')";
        }

        if ($this->post_get('show') != '') {
            $this->filter_array('show');
            $where .= " AND a.promoted='Y'";
        }

        if ($this->post_get('location') != '') {
            $this->filter_array('location');
            $where .= " AND (a.ad_state LIKE '%" . $this->replace_sql($this->post_get('location')) . "%' OR a.ad_city LIKE '%" . $this->replace_sql($this->post_get('location')) . "%')";
        }

        if ($this->post_get('action') != '') {
            $this->filter_array('action');
        }


        if ($this->post_get('category') != '') {
            $this->filter_array('category');
            if ($this->post_get('action') == 'search') {
                $where .= " AND a.ad_category_id='" . $this->replace_sql($this->post_get('category')) . "'";
            } else {
                $query = "SELECT id, category_name, page_title, page_heading, meta_keywords, meta_desc FROM m_ads_cat WHERE page_url='" . $this->replace_sql($this->post_get('category')) . "'";
                if ($this->cms = $this->db->select($query)) {
                    $where .= " AND a.ad_category_id='" . $this->replace_sql($this->cms['id']) . "'";
                }
            }
        }

        if ($this->post_get('sub_category') != '') {
            $this->filter_array('sub_category');
            $where .= " AND a.ad_sub_category_id='" . $this->replace_sql($this->post_get('sub_category')) . "'";
        }

        if ($this->post_get('min_price') != '' || $this->post_get('max_price') != '') {
            if ($this->post_get('min_price') != '' && $this->post_get('max_price') != '') {
                $where .= " AND (a.ad_price >= '" . $this->replace_sql($this->post_get('min_price')) . "' OR a.ad_price <= '" . $this->replace_sql($this->post_get('max_price')) . "')";
                $this->filter_array('min_price');
                $this->filter_array('max_price');
            } else if ($this->post_get('min_price') != '') {
                $where .= " AND a.ad_price >= '" . $this->replace_sql($this->post_get('min_price')) . "'";
                $this->filter_array('min_price');
            } else if ($this->post_get('max_price') != '') {
                $where .= " AND a.ad_price <= '" . $this->replace_sql($this->post_get('max_price')) . "'";
                $this->filter_array('max_price');
            }
        }
        $label_id = $value_id = $value = [];
        if ($this->post_get('filter')) {
            $filters = array_filter(array_unique($this->post_get('filter')));
            $data = $select = $input = [];
            if ($filters) {
                foreach ($filters as $k => $v) {
                    $this->filter['filter[' . $k . ']'] = $v;
                    $key = explode('_', $k);
                    $label_id[] = $key[1];
                    if ($key[0] == 'S') {
                        $value_id[] = $v;
                    } else if ($key[0] == 'I') {
                        $value[] = $v;
                    }
                }
            }
            if ($label_id || $value_id || $value) {
                $query = "SELECT ad_id FROM m_ads_filter WHERE label_id IN (" . implode(',', $label_id) . ") AND (value_id IN (" . implode(',', $value_id) . ") OR value IN ('" . implode("','", $value) . "')) GROUP BY ad_id";
                if ($data = $this->db->freg($query, ['ad_id'], 'ad_id')) {
                    $where .= " AND a.id IN (" . implode(',', $data) . ")";
                }
            }
        }

        if ($this->session('user', 'reports')) {
            $where .= " AND a.id NOT IN (" . implode(',', $this->session('user', 'reports')) . ")";
        }

        $query = "SELECT a.id, a.ad_title, a.ad_desc, a.ad_price, a.promoted, a.add_date, CONCAT_WS(' &raquo; ', c.category_name, s.category_name) as ad_cat, CONCAT(a.ad_city, ', ', a.ad_state,', Nigeria') as location, f.meta_value as ad_image FROM m_ads a "
            . "LEFT OUTER JOIN m_ads_cat c ON a.ad_category_id=c.id "
            . "LEFT OUTER JOIN m_ads_cat s ON a.ad_category_id=c.id AND a.ad_sub_category_id=s.id "
            . "LEFT OUTER JOIN files f ON a.ad_image=f.id "
            . "{$where} GROUP BY a.id ORDER BY a.id DESC ";
        $page = ($this->post('p') != '' ? $this->post('p') : 1);
        $this->pagination = new pagination($this, $this->db, $query, $this->rows['load'], $page);
        $this->data = $this->pagination->paging('a.id');
        $this->sno = $this->pagination->get_sno();
        $this->rows = array_merge($this->rows, array('count' => count($this->data), 'total' => $this->pagination->total_rows()));
    }

    function ad()
    {
        $query = "SELECT a.*, u.display_name, u.verified, u.add_date as user_add_date, c.category_name as ad_category, s.category_name as ad_sub_category, CONCAT(a.ad_city, ', ', a.ad_state,', Nigeria') as location, f.meta_value as ad_image FROM m_ads a "
            . "LEFT OUTER JOIN users u ON a.ad_user_id=u.id "
            . "LEFT OUTER JOIN m_ads_cat c ON a.ad_category_id=c.id "
            . "LEFT OUTER JOIN m_ads_cat s ON a.ad_category_id=c.id AND a.ad_sub_category_id=s.id "
            . "LEFT OUTER JOIN files f ON a.ad_image=f.id "
            . "WHERE a.id='" . $this->replace_sql($this->get('id')) . "' AND a.publish='Y' AND a.deleted='N'" . ($this->session('user', 'reports') ? " AND a.id NOT IN (" . implode(',', $this->session('user', 'reports')) . ")" : '') . "";
        if (!$this->cms = $this->db->select($query)) {
            $this->not_found();
        }
        if ($this->cms['ad_sold'] == 'Y') {
            $this->session_msg('This ad has been sold. Please look for another available ads.', 'error');
            $this->redirecting('ads');
        }

        $this->cms['url'] = $this->permalink('ad-detail', $this->cms);
        if ($this->cms['url'] != $this->current_url()) {
            $this->redirect($this->cms['url']);
        }
        $this->meta = array('ogurl' => $this->cms['url'], 'ogtype' => 'article', 'ogtitle' => $this->cms['ad_title'], 'ogdesc' => $this->show_string($this->cms['ad_desc'], 200, false), 'ogimage' => $this->get_file($this->cms['ad_image']));

        $query = "UPDATE m_ads SET total_views = total_views + 1 WHERE id='" . $this->cms['id'] . "'";
        $this->db->query($query);

        $query = "SELECT * FROM files WHERE type_id='" . $this->cms['id'] . "' AND type='ads' AND table_name='m_ads'";
        $this->cms['files'] = $this->db->selectall($query);

        $query = "SELECT id, label, value FROM m_ads_filter WHERE ad_id='" . $this->cms['id'] . "'";
        $this->cms['filters'] = $this->db->freg_all($query, ['id'], ['label', 'value']);

        $query = "SELECT * FROM m_ads_comment WHERE publish='Y' AND verified='Y' AND deleted='N' AND ad_id='" . $this->replace_sql($this->cms['id']) . "' ORDER BY add_date DESC";
        $this->comments = $this->db->selectall($query);

        $query = "SELECT a.id, a.ad_title, a.ad_desc, a.ad_price, a.promoted, a.add_date, CONCAT_WS(' &raquo; ', c.category_name, s.category_name) as ad_cat, CONCAT(a.ad_city, ', ', a.ad_state,', Nigeria') as location, f.meta_value as ad_image FROM m_ads a "
            . "LEFT OUTER JOIN m_ads_cat c ON a.ad_category_id=c.id "
            . "LEFT OUTER JOIN m_ads_cat s ON a.ad_category_id=c.id AND a.ad_sub_category_id=s.id "
            . "LEFT OUTER JOIN files f ON a.ad_image=f.id "
            . "WHERE a.id!='" . $this->cms['id'] . "' AND a.ad_category_id='" . $this->cms['ad_category_id'] . "' AND a.publish='Y' AND a.ad_sold='N' AND a.deleted='N'" . ($this->session('user', 'reports') ? " AND a.id NOT IN (" . implode(',', $this->session('user', 'reports')) . ")" : '') . " ORDER BY RAND() LIMIT 8";
        $this->cms['related'] = $this->db->selectall($query);
    }

    function insert_comment()
    {
        $this->validate_post_token();
        if ($this->post('id') == '') {
            throw new Exception('Oops, something went wrong!');
        }
        $data = $this->db->select("SELECT id FROM m_ads WHERE id='" . $this->replace_sql($this->post('id')) . "'");
        if (!$data) {
            throw new Exception('Sorry, ad not exists.');
        }
        if ($this->post('ad', 'name') == '') {
            throw new Exception('Please enter your name.');
        }
        if ($this->post('ad', 'email') == '') {
            throw new Exception('Please enter your email.');
        }
        if ($this->post('ad', 'phone') == '') {
            throw new Exception('Please enter your phone no.');
        }
        if ($this->post('ad', 'comment') == '') {
            throw new Exception('Please enter your comment.');
        }
        if ($this->post('ad', 'captcha') == '') {
            throw new Exception('Please enter the security captcha.');
        }
        if ($this->post('ad', 'captcha') != $this->session('captcha', 'ad')) {
            throw new Exception('Invalid security captcha, try again.');
        }
        $date = date('Y-m-d H:i:s');
        $id = $this->db->insert('m_blogs_comment', array(
            'ad_id' => $data['id'],
            'name' => $this->post('ad', 'name'),
            'email' => $this->post('ad', 'email'),
            'phone' => $this->post('ad', 'phone'),
            'comment' => $this->post('ad', 'comment'),
            'verified' => 'Y',
            'ip' => $this->server('REMOTE_ADDR'),
            'browser' => $this->get_browser(),
            'os' => $this->get_os(),
            'add_date' => $date
        ));
    }


    public
    function user_detail()
    {
        $query = "SELECT *, CONCAT(city, ', ', state, ', Nigeria') as location FROM users WHERE id='" . $this->replace_sql($this->get('id')) . "'";
        if (!$this->cms = $this->db->select($query)) {
            $this->not_found();
        }

        $this->cms['url'] = $this->permalink('user-profile', ['page_url' => $this->cms['display_name'], 'id' => $this->cms['id']]);
        if ($this->cms['url'] != $this->current_url()) {
            $this->redirect($this->cms['url']);
        }
        $this->meta = array('ogurl' => $this->cms['url'], 'ogtype' => 'article', 'ogtitle' => 'View ' . $this->cms['display_name'] . ' profile', 'ogdesc' => 'View ' . $this->cms['display_name'] . ' on ' . app_name, 'ogimage' => $this->initials($this->cms['display_name']));

        $this->cms['ads'] = $this->user_ads($this->cms['id']);

    }

    public
    function user_ads($id = NULL)
    {
        $result = [];
        if (!is_null($id) && !empty($id)) {
            $query = "SELECT a.id, a.ad_title, a.ad_desc, a.ad_price, a.promoted, a.add_date, CONCAT_WS(' &raquo; ', c.category_name, s.category_name) as ad_cat, CONCAT(a.ad_city, ', ', a.ad_state,', Nigeria') as location, f.meta_value as ad_image FROM m_ads a "
                . "LEFT OUTER JOIN m_ads_cat c ON a.ad_category_id=c.id "
                . "LEFT OUTER JOIN m_ads_cat s ON a.ad_category_id=c.id AND a.ad_sub_category_id=s.id "
                . "LEFT OUTER JOIN files f ON a.ad_image=f.id "
                . "WHERE a.publish='Y' AND a.deleted='N' AND ad_user_id='" . $this->replace_sql($id) . "'" . ($this->session('user', 'reports') ? " AND a.id NOT IN (" . implode(',', $this->session('user', 'reports')) . ")" : '') . " ORDER BY id";
            $page = ($this->post_get('p') != '' ? $this->post_get('p') : 1);
            $this->pagination = new pagination($this, $this->db, $query, $this->rows['load'], $page);
            $result = $this->data = $this->pagination->paging('a.id');
            $this->sno = $this->pagination->get_sno();
            $this->rows = array_merge($this->rows, array('count' => count($this->data), 'total' => $this->pagination->total_rows()));
        }
        return $result;
    }


}
