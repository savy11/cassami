<?php
 
 namespace admin\controllers;
 
 use Exception;
 use resources\models\backend_pagination as pagination;
 
 class m_ads_subcat extends controller
 {
  
  public $pagination = null, $sno = 0;
  
  public function __construct()
  {
   parent::__construct();
   $this->require_login('m-ads-subcat');
  }
  
  public function delete()
  {
   if (!$this->per_delete) {
    throw new Exception(_('You have no permission of delete.'));
   }
   $this->validate_delete_token(true);
   $id = $this->get('id');
   
   $this->db->delete('m_ads_cat', array('id' => $id));
  }
  
  public function insert()
  {
   if (!$this->per_add) {
    throw new Exception(_('You have no permission of add.'));
   }
   $this->validate_post_token(true);
   $id = $this->db->insert('m_ads_cat', array(
    'master_id' => $this->post('master_id'),
    'category_name' => $this->post('category_name'),
    'publish' => $this->post('publish'),
    'page_title' => $this->post('page_title'),
    'page_heading' => $this->post('page_heading'),
    'page_url' => $this->post('page_url'),
    'meta_keywords' => $this->post('meta_keywords'),
    'meta_desc' => $this->post('meta_desc'),
    'add_date' => date('Y-m-d H:i:s')));
  }
  
  public function update()
  {
   if (!$this->per_edit) {
    throw new Exception(_('You have no permission of update.'));
   }
   $this->validate_post_token(true);
   $id = $this->post('id');
   if ($id == '') {
    throw new Exception(_('Invalid ID for update!'));
   }
   $this->db->update('m_ads_cat', array(
    'master_id' => $this->post('master_id'),
    'category_name' => $this->post('category_name'),
    'publish' => $this->post('publish'),
    'page_title' => $this->post('page_title'),
    'page_heading' => $this->post('page_heading'),
    'page_url' => $this->post('page_url'),
    'meta_keywords' => $this->post('meta_keywords'),
    'meta_desc' => $this->post('meta_desc')), array(
    'id' => $id
   ));
  }
  
  public function select()
  {
   $query = "SELECT c.* FROM m_ads_cat c WHERE c.id='" . $this->replace_sql($this->get('id')) . "'";
   if (!$this->data = $this->db->select($query)) {
    $this->not_found();
   }
   $this->populate_post_data();
  }
  
  public function select_all()
  {
   global $dtoken;
   $dtoken = $this->delete_token();
   $where = "WHERE c.master_id!='0'";
   if ($this->get('keyword') != '') {
    $where .= " AND c.category_name LIKE '%" . $this->replace_sql($this->get('keyword')) . "%'";
   }
   $query = "SELECT c.*, c1.category_name as parent_name FROM m_ads_cat c "
    ."INNER JOIN m_ads_cat c1 ON c.master_id=c1.id "
   ."{$where} ORDER BY c.id ASC";
   $this->pagination = new pagination($this, $this->db, $query);
   $this->data = $this->pagination->paging('c.id');
   $this->sno = $this->pagination->get_sno();
  }
  
  function publish()
  {
   $this->db->update('m_ads_cat', array('publish' => $this->post('publish')), array('id' => $this->post('id')));
  }
  
  
 }
