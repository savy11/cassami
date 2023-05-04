<?php

 namespace admin\controllers;

 use Exception;
 use resources\models\backend_pagination as pagination;

 class m_ads_comment extends controller {

  public $pagination = null, $sno = 0;

  public function __construct() {
   parent::__construct();
   $this->require_login('m-ads-comment');
  }

  public function delete() {

   if (!$this->per_delete) {
    throw new Exception(_('You have no permission of delete.'));
   }
   $this->validate_delete_token(true);
   $id = $this->get('id');
   $this->db->delete('m_ads_comment', array('id' => $id));
  }

  public function select() {
   $query = "SELECT ac.*, a.ad_title "
           . "FROM m_ads_comment ac "
           . "LEFT OUTER JOIN m_ads a ON ac.ad_id=a.id "
           . "WHERE ac.id='" . $this->replace_sql($this->get('id')) . "'";
   if (!$this->data = $this->db->select($query)) {
    $this->not_found();
   }
   $this->populate_post_data();
  }

  public function select_all() {
   global $dtoken;
   $dtoken = $this->delete_token();
   $where = "WHERE 1=1";
   if ($this->get('keyword') != '') {
    $where .= " AND name LIKE '%" . $this->replace_sql($this->get('keyword')) . "%'";
   }
   $query = "SELECT * FROM m_ads_comment {$where} ORDER BY id ASC";
   $this->pagination = new pagination($this, $this->db, $query);
   $this->data = $this->pagination->paging('id');
   $this->sno = $this->pagination->get_sno();
  }

  function publish() {

   $this->db->update('m_ads_comment', array(
       'publish' => $this->post('publish')), array(
       'id' => $this->post('id')
   ));

  }

 }
 