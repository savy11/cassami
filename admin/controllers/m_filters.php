<?php
 
 namespace admin\controllers;
 
 use Exception;
 use resources\models\backend_pagination as pagination;
 
 class m_filters extends controller
 {
  
  public $pagination = null, $sno = 0;
  
  public function __construct()
  {
   parent::__construct();
   $this->require_login('m-filters');
  }
  
  public function delete()
  {
   if (!$this->per_delete) {
    throw new Exception(_('You have no permission of delete.'));
   }
   $this->validate_delete_token(true);
   $id = $this->get('id');
   $this->db->delete('m_filters', array('id' => $id));
  }
  
  public function insert()
  {
   try {
    if (!$this->per_add) {
     throw new Exception(_('You have no permission of add.'));
    }
    $this->validate_post_token(true);
    $this->db->trans_start();
    $id = $this->db->insert('m_filters', array(
     'category_id' => $this->post('category_id'),
     'sub_category_id' => $this->post('sub_category_id'),
     'publish' => $this->post('publish'),
     'add_date' => date('Y-m-d H:i:s')));
    
    $filters = [];
    if ($this->post('filters')) {
     $this->db->delete('m_filters_label', ['af_id' => $id]);
     foreach ($this->post('filters') as $k => $v) {
      $filters[] = [
       'af_id' => $id,
       'title' => $v['title'],
       'placeholder' => $v['placeholder'],
       'type' => $v['type'],
       'req' => $v['req']
      ];
     }
     if ($filters) {
      $this->db->batch('insert', 'm_filters_label', $filters);
     }
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
    $this->db->trans_start();
    $this->db->update('m_filters', array(
     'category_id' => $this->post('category_id'),
     'sub_category_id' => $this->post('sub_category_id'),
     'publish' => $this->post('publish')), array(
     'id' => $id
    ));
    
    $filters = $where = [];
    if ($this->post('filters_del_ids') != '') {
     $query = "DELETE FROM m_filters_label WHERE id IN(" . $this->post('filters_del_ids') . ")";
     $this->db->query($query);
    }
    
    if ($this->post('filters')) {
     foreach ($this->post('filters') as $k => $v) {
      if ($this->varv('id', $v) > 0) {
       $info['update'][] = array('af_id' => $id, 'title' => $v['title'], 'placeholder' => $v['placeholder'], 'type' => $v['type'], 'req' => $v['req']);
       $where[] = array('id' => $this->varv('id', $v));
      } else {
       if ($this->varv('title', $v) != '' || $this->varv('placeholder', $v) != '')
        $info['insert'][] = array('af_id' => $id, 'title' => $v['title'], 'placeholder' => $v['placeholder'], 'type' => $v['type'], 'req' => $v['req']);
      }
     }
     if ($info) {
      foreach ($info as $k => $v) {
       $this->db->batch($k, 'm_filters_label', $v, ($k == 'update' ? $where : ''));
      }
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
   $query = "SELECT * FROM m_filters WHERE id='" . $this->replace_sql($this->get('id')) . "'";
   if (!$this->data = $this->db->select($query)) {
    $this->not_found();
   }
   $query = "SELECT * FROM m_filters_label WHERE af_id='" . $this->data['id'] . "' ORDER BY id";
   $this->data['filters'] = $this->db->selectall($query);
   $this->populate_post_data();
  }
  
  public function select_all()
  {
   global $dtoken;
   $dtoken = $this->delete_token();
   $where = "WHERE 1=1";
   if ($this->get('keyword') != '') {
    $where .= " AND c.category_name LIKE '%" . $this->replace_sql($this->get('keyword')) . "%'";
   }
   $query = "SELECT f.*, c.category_name, s.category_name as sub_category_name FROM m_filters f "
    . "LEFT OUTER JOIN m_ads_cat c ON f.category_id=c.id "
    . "LEFT OUTER JOIN m_ads_cat s ON f.sub_category_id=s.id "
    . "{$where} ORDER BY c.id ASC";
   $this->pagination = new pagination($this, $this->db, $query);
   $this->data = $this->pagination->paging('f.id');
   $this->sno = $this->pagination->get_sno();
  }
  
  function publish()
  {
   $this->db->update('m_filters', array('publish' => $this->post('publish')), array('id' => $this->post('id')));
  }
  
  
 }
