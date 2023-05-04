<?php
 
 namespace admin\controllers;
 
 use Exception;
 use resources\models\backend_pagination as pagination;
 
 class m_states extends controller
 {
  
  public $pagination = null, $sno = 0;
  
  public function __construct()
  {
   parent::__construct();
   $this->require_login('m-states');
   $this->show_search = true;
  }
  
  public function delete()
  {
   if (!$this->per_delete) {
    throw new Exception(_('You have no permission of delete.'));
   }
   $this->validate_delete_token(true);
   $id = $this->get('id');
   $this->db->delete('m_states', array('id' => $id));
  }
  
  public function insert()
  {
   if (!$this->per_add) {
    throw new Exception(_('You have no permission of add.'));
   }
   $this->validate_post_token(true);
   $id = $this->db->insert('m_states', array(
    'state_name' => $this->post('state_name'),
    'publish' => $this->post('publish'),
    'add_date' => date('Y-m-d H:i:s')
   ));
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
   $this->db->update('m_states', array(
    'state_name' => $this->post('state_name'),
    'publish' => $this->post('publish')), array(
    'id' => $id
   ));
  }
  
  public function select()
  {
   $query = "SELECT * FROM m_states WHERE id='" . $this->replace_sql($this->get('id')) . "'";
   if (!$this->data = $this->db->select($query)) {
    $this->not_found();
   }
   $this->populate_post_data();
  }
  
  public function select_all()
  {
   global $dtoken;
   $dtoken = $this->delete_token();
   $where = "WHERE 1=1";
   if ($this->get('keyword') != '') {
    $where .= " AND s.state_name LIKE '%" . $this->replace_sql($this->get('keyword')) . "%'";
   }
   $query = "SELECT s.*, 'Nigeria' as country_name FROM m_states s {$where} ORDER BY s.id DESC";
   $this->pagination = new pagination($this, $this->db, $query);
   $this->data = $this->pagination->paging('s.id');
   $this->sno = $this->pagination->get_sno();
  }
  
  public function publish()
  {
   if ($this->post('id') == '') {
    throw new Exception('Oops, something went wrong.');
   }
   $id = $this->replace_sql($this->post('id'));
   $this->db->update('m_states', array(
    'publish' => $this->post('publish')), array(
    'id' => $id
   ));
  }
  
 }
