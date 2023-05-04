<?php
 
 namespace admin\controllers;
 
 use Exception;
 use resources\models\backend_pagination as pagination;
 
 class m_filters_value extends controller
 {
  
  public $pagination = null, $sno = 0;
  
  public function __construct()
  {
   parent::__construct();
   $this->require_login('m-filters-value');
  }
  
  public function delete()
  {
   if (!$this->per_delete) {
    throw new Exception(_('You have no permission of delete.'));
   }
   $this->validate_delete_token(true);
   $id = $this->get('id');
   $this->db->delete('m_filters_value', array('fl_id' => $id));
  }
  
  public function insert()
  {
   try {
    if (!$this->per_add) {
     throw new Exception(_('You have no permission of add.'));
    }
    $this->validate_post_token(true);
    $exists = $this->db->select("SELECT id FROM m_filters_link WHERE label_id='" . $this->replace_sql($this->post('label_id')) . "'");
    if ($exists) {
     throw new Exception(_('Filter already exists in database.'));
    }
    $value_id = $this->post('value_id') ? $this->post('value_id') : '0';
    if (!empty($this->post('linked_id'))) {
     if (empty($this->post('value_id'))) {
      throw new Exception(_('Please select linked filter value or remove linked filter.'));
     }
    }
    $this->db->trans_start();
    $id = $this->db->insert('m_filters_link', [
     'label_id' => $this->post('label_id'),
     'linked_id' => $this->post('linked_id'),
     'value_id' => $value_id,
     'add_date' => date('Y-m-d H:i:s')
    ]);
    
    $filters = [];
    if ($this->post('filters')) {
     $this->db->delete('m_filters_value', ['fl_id' => $id]);
     foreach ($this->post('filters') as $k => $v) {
      if ($this->varv('title', $v) != '' || $this->varv('publish', $v) != '')
       $filters[] = [
        'fl_id' => $id,
        'master_id' => $value_id,
        'title' => $v['title'],
        'publish' => $v['publish']
       ];
     }
    }
    if ($filters) {
     $this->db->batch('insert', 'm_filters_value', $filters);
    }
    $this->db->trans_commit();
   } catch (Exception $ex) {
    $this->db->trans_rollback();
    throw new Exception($ex->getMessage());
   }
  }
  
  public function update()
  {
   try {
    if (!$this->per_edit) {
     throw new Exception(_('You have no permission of update.'));
    }
    $this->validate_post_token(true);
    $id = $this->post('id');
    if ($id == '') {
     throw new Exception(_('Invalid ID for update!'));
    }
    $exists = $this->db->select("SELECT id FROM m_filters_link WHERE label_id='" . $this->replace_sql($this->post('label_id')) . "' AND id!='" . $this->replace_sql($id) . "'");
    if ($exists) {
     throw new Exception(_('Filter already exists in database.'));
    }
    $value_id = $this->post('value_id') ? $this->post('value_id') : '0';
    if (!empty($this->post('linked_id'))) {
     if (empty($this->post('value_id'))) {
      throw new Exception(_('Please select linked filter value or remove linked filter.'));
     }
    }
    $this->db->trans_start();
    $this->db->update('m_filters_link', [
     'label_id' => $this->post('label_id'),
     'linked_id' => $this->post('linked_id'),
     'value_id' => $value_id], [
     'id' => $id
    ]);
    
    $filters = $where = [];
    if ($this->post('filters_del_ids') != '') {
     $query = "DELETE FROM m_filters_value WHERE id IN(" . $this->post('filters_del_ids') . ")";
     $this->db->query($query);
    }
    
    if ($this->post('filters')) {
     foreach ($this->post('filters') as $k => $v) {
      if ($this->varv('id', $v) > 0) {
       $filters['update'][] = array('fl_id' => $id, 'master_id' => $value_id, 'title' => $v['title'], 'publish' => $v['publish']);
       $where[] = array('id' => $this->varv('id', $v));
      } else {
       if ($this->varv('title', $v) != '' || $this->varv('publish', $v) != '')
        $filters['insert'][] = array('fl_id' => $id, 'master_id' => $value_id, 'title' => $v['title'], 'publish' => $v['publish']);
      }
     }
    }
    if ($filters) {
     foreach ($filters as $k => $v) {
      $this->db->batch($k, 'm_filters_value', $v, ($k == 'update' ? $where : ''));
     }
    }
    $this->db->trans_commit();
   } catch (Exception $ex) {
    $this->db->trans_rollback();
    throw new Exception($ex->getMessage());
   }
  }
  
  public function select()
  {
   $query = "SELECT * FROM m_filters_link WHERE id='" . $this->replace_sql($this->get('id')) . "'";
   if (!$this->data = $this->db->select($query)) {
    $this->not_found();
   }
   $query = "SELECT * FROM m_filters_value WHERE fl_id='" . $this->data['id'] . "' AND master_id='" . $this->data['value_id'] . "' ORDER BY id";
   $this->data['filters'] = $this->db->selectall($query);
   $this->populate_post_data();
  }
  
  public function select_all()
  {
   global $dtoken;
   $dtoken = $this->delete_token();
   $where = "WHERE 1=1";
   if ($this->get('keyword') != '') {
    $where .= " AND l.title LIKE '%" . $this->replace_sql($this->get('keyword')) . "%'";
   }
   $query = "SELECT k.id, l.title, v.title as linked_value, ll.title as linked_label, CONCAT_WS(' &raquo; ', c.category_name, sc.category_name) as category_name FROM m_filters_link k "
    . "LEFT OUTER JOIN m_filters_value v ON k.value_id=v.id "
    . "LEFT OUTER JOIN m_filters_label ll ON k.linked_id=ll.id "
    . "LEFT OUTER JOIN m_filters_label l ON k.label_id=l.id "
    . "LEFT OUTER JOIN m_filters f ON l.af_id=f.id "
    . "LEFT OUTER JOIN m_ads_cat sc ON f.sub_category_id=sc.id "
    . "INNER JOIN m_ads_cat c ON sc.master_id=c.id "
    . "{$where} "
    . "ORDER BY l.id, v.id ASC";
   $this->pagination = new pagination($this, $this->db, $query);
   $this->data = $this->pagination->paging('f.id');
   $this->sno = $this->pagination->get_sno();
  }
  
  public function get_ads_labels()
  {
   $query = "SELECT l.id, CONCAT_WS(' &raquo; ', c.category_name, l.title) as title FROM m_filters_label l "
    . "LEFT OUTER JOIN m_filters f ON l.af_id=f.id "
    . "LEFT OUTER JOIN m_ads_cat c ON f.sub_category_id=c.id "
    . "WHERE l.type='S' "
    . "ORDER BY l.id";
   return $this->db->freg($query, ['id'], 'title');
  }
  
  public function get_linked_labels($id = NULL)
  {
   $result = [];
   if (!is_null($id)) {
    $exists = $this->db->select("SELECT af_id FROM m_filters_label WHERE id='" . $this->replace_sql($id) . "'");
    if ($exists) {
     $query = "SELECT l.id, CONCAT_WS(' &raquo; ', c.category_name, l.title) as title FROM m_filters_label l "
      . "LEFT OUTER JOIN m_filters f ON l.af_id=f.id "
      . "LEFT OUTER JOIN m_ads_cat c ON f.sub_category_id=c.id "
      . "WHERE l.type='S' AND l.id!='" . $this->replace_sql($id) . "' AND af_id='" . $this->replace_sql($exists['af_id']) . "' "
      . "ORDER BY l.id";
     $result = $this->db->freg($query, ['id'], 'title');
    }
   }
   return $result;
  }
  
  public function get_linked_values($id = NULL)
  {
   $result = [];
   if (!is_null($id)) {
    $exists = $this->db->select("SELECT id FROM m_filters_link WHERE label_id='" . $this->replace_sql($id) . "'");
    if ($exists) {
     $query = "SELECT id, title FROM m_filters_value WHERE fl_id='" . $this->replace_sql($exists['id']) . "'";
     $result = $this->db->freg($query, ['id'], 'title');
    }
   }
   return $result;
  }
  
 }
