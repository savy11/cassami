<?php

namespace admin\controllers;

use Exception;
use resources\models\backend_pagination as pagination;

class m_ads extends controller
{

    public $pagination = null, $sno = 0;

    public function __construct()
    {
        parent::__construct();
        $this->require_login('m-ads');
    }

    public function delete()
    {
        if (!$this->per_delete) {
            throw new Exception(_('you have no permission to delete.'));
        }
        $this->validate_delete_token(true);
        $this->db->update('m_ads', ['deleted' => 'Y'], ['id' => $this->get('id')]);
    }

    public function select()
    {
        $query = "SELECT a.*, c.category_name as cat, s.category_name as subcat, u.display_name FROM m_ads a "
            . "LEFT OUTER JOIN m_ads_cat c ON a.ad_category_id=c.id "
            . "LEFT OUTER JOIN m_ads_cat s ON a.ad_sub_category_id=s.id "
            . "LEFT OUTER JOIN users u ON a.ad_user_id=u.id "
            . "WHERE a.id='" . $this->replace_sql($this->get('id')) . "'";
        if (!$this->data = $this->db->select($query)) {
            $this->not_found();
        }

        $query = "SELECT * FROM files WHERE type_id='" . $this->data['id'] . "' AND type='ads' AND table_name='m_ads' ORDER BY id";
        $this->data['ads'] = $this->db->selectall($query);

        $query = "SELECT * FROM m_ads_filter WHERE ad_id='" . $this->data['id'] . "' ORDER BY id";
        $this->data['filters'] = $this->db->selectall($query);
        $this->populate_post_data();
    }

    public function select_all()
    {
        global $dtoken;
        $dtoken = $this->delete_token();
        $where = "";
        if ($this->get('keyword') != '') {
            $where .= "WHERE ad_title LIKE '%" . $this->replace_sql($this->get('keyword')) . "%'";
        }
        $query = "SELECT * FROM m_ads {$where} ORDER BY id DESC";
        $this->pagination = new pagination($this, $this->db, $query);
        $this->data = $this->pagination->paging('id');
        $this->sno = $this->pagination->get_sno();
    }

    public function publish()
    {

        $this->db->update('m_ads', array(
            'publish' => $this->post('publish')
        ), array(
            'id' => $this->post('id')
        ));
    }

    public function approved()
    {

        $this->db->update('m_ads', array(
            'approved' => $this->post('approved')
        ), array(
            'id' => $this->post('id')
        ));
    }

    public function promoted()
    {

        $this->db->update('m_ads', array(
            'promoted' => $this->post('promoted')
        ), array(
            'id' => $this->post('id')
        ));
    }

}
 