<?php
 
 namespace admin\controllers;
 
 use Exception;
 use resources\models\backend_pagination as pagination;
 
 class m_cities extends controller
 {
  
  public $pagination = null, $sno = 0;
  
  public function __construct()
  {
   parent::__construct();
   $this->require_login('m-cities');
   $this->list['states'] = $this->get_states();
   $this->show_search = true;
  }
  
  public function delete()
  {
   if (!$this->per_delete) {
    throw new Exception(_('You have no permission of delete.'));
   }
   $this->validate_delete_token(true);
   $id = $this->get('id');
   $this->db->delete('m_cities', array('id' => $id));
  }
  
  public function insert()
  {
   if (!$this->per_add) {
    throw new Exception(_('You have no permission of add.'));
   }
   $this->validate_post_token(true);
   $id = $this->db->insert('m_cities', array(
    'state_id' => $this->post('state_id'),
    'city_name' => $this->post('city_name'),
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
   $this->db->update('m_cities', array(
    'state_id' => $this->post('state_id'),
    'city_name' => $this->post('city_name'),
    'publish' => $this->post('publish')), array(
    'id' => $id
   ));
  }
  
  public function select()
  {
   $query = "SELECT r.* FROM m_cities r WHERE r.id='" . $this->replace_sql($this->get('id')) . "'";
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
    $where .= " AND s.state_name LIKE '%" . $this->replace_sql($this->get('keyword')) . "%' OR c.city_name LIKE '%" . $this->replace_sql($this->get('keyword')) . "%'";
   }
   $query = "SELECT c.*, s.state_name, 'Nigeria' as country_name FROM m_cities c "
    . "LEFT OUTER JOIN m_states s ON c.state_id=s.id "
    . "{$where} ORDER BY c.id";
   $this->pagination = new pagination($this, $this->db, $query);
   $this->data = $this->pagination->paging('c.id');
   $this->sno = $this->pagination->get_sno();
  }
  
  public function publish()
  {
   if ($this->post('id') == '') {
    throw new Exception('Oops, something went wrong.');
   }
   $id = $this->replace_sql($this->post('id'));
   $this->db->update('m_cities', array(
    'publish' => $this->post('publish')), array(
    'id' => $id
   ));
  }
  
  public function get_states()
  {
   $query = "SELECT id, state_name FROM m_states WHERE publish='Y' ORDER BY state_name";
   return $this->db->freg($query, ['id'], 'state_name');
  }
  
 }
