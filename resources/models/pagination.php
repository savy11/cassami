<?php
 
 namespace resources\models;
 
 use resources\models\model as db;
 
 class pagination
 {
  
  private $db = null;
  private $fn = null;
  private $rows_per_page = 50;
  private $sql = '';
  private $current_page = '';
  private $total_rows = 0;
  private $count_rows = 0;
  private $offset = 0;
  private $total_pages = 0;
  private $links_per_page = 5;
  private $link_start = 0;
  private $link_end = 0;
  private $params = '';
  
  public function __construct($fn, $db, $sql, $rows_per_page = 50, $page = 1)
  {
   $this->db = $db;
   $this->fn = $fn;
   $this->sql = $sql;
   $this->rows_per_page = $rows_per_page;
   
   
   if ($this->fn->post_get('p')) {
    $this->current_page = ($this->fn->post_get('p') ? max($this->fn->post_get('p'), 1) : 1);
   } else {
    $this->current_page = max($page, 1);
   }
   
   unset($_GET['p'], $_POST['p']);
   $parse = parse_url($this->fn->current_url());
   if (isset($parse['query'])) {
    parse_str($parse['query'], $exp);
    unset($exp['p']);
    if (!empty($exp)) {
     $this->params .= http_build_query($exp) . '&';
    }
   }
   
   /*if ($this->fn->post_get()) {
    foreach ($this->fn->post_get() as $k => $v) {
     if ($k == 'p') {
      if (is_array($v)) {
       foreach ($v as $k2 => $v2) {
        $this->params .= "{$k}[{$k2}]={$v2}&";
       }
      } else {
       $this->params .= "{$k}={$v}&";
      }
     }
    }
   }*/
  }
  
  public function get_sno()
  {
   return $this->offset + 1;
  }
  
  public function paging($field = '*')
  {
   //echo $q = "SELECT {$field} FROM " . explode('FROM', $this->sql)[1];
   $q = $this->sql;
   $this->total_rows = $this->db->count($q);
   $this->offset = $this->rows_per_page * ($this->current_page - 1);
   $this->sql .= " LIMIT {$this->offset},{$this->rows_per_page}";
   $this->total_pages = ceil($this->total_rows / $this->rows_per_page);
   
   $this->link_start = $this->current_page - floor($this->links_per_page / 2);
   $this->link_end = $this->current_page + floor($this->links_per_page / 2);
   
   if ($this->link_start <= 0) {
    $this->link_start = 1;
    if ($this->links_per_page <= $this->total_pages)
     $this->link_end = $this->links_per_page;
   }
   if ($this->link_end > $this->total_pages) {
    $this->link_end = $this->total_pages;
    if ($this->links_per_page <= $this->total_pages)
     $this->link_start = $this->link_end - $this->links_per_page + 1;
   }
   $data = $this->db->selectall($this->sql);
   $this->count_rows = count($data);
   return $data;
  }
  
  public function paging_data()
  {
   return array('total' => $this->total_rows, 'count' => $this->count_rows, 'load' => $this->rows_per_page);
  }
  
  public function get_info()
  {
   return array('page' => $this->current_page, 'total_pages' => $this->total_pages, 'rows_start' => $this->offset + 1, 'rows_end' => min($this->offset + $this->rows_per_page, $this->total_rows), 'total_rows' => $this->total_rows);
  }
  
  public function total_rows()
  {
   return $this->total_rows;
  }
  
  public function display_first()
  {
   if ($this->link_end > $this->link_start) {
    if ($this->current_page > 1) {
     return '<li class="page-item first"><a class="page-link" href="?' . $this->params . 'p=1">FIRST</a></li>';
    } else {
     return '<li class="page-item first disabled"><a class="page-link" disabled>FIRST</a></li>';
    }
   }
   return '';
  }
  
  public function display_previous()
  {
   if ($this->link_end > $this->link_start) {
    if ($this->current_page > 1) {
     return '<li class="page-item prev"><a class="page-link" href="?' . $this->params . 'p=' . ($this->current_page - 1) . '"><i class="fa fa-angle-left" aria-hidden="true"></i></a></li>';
    } else {
     return '<li class="page-item prev disabled"><a class="page-link" disabled><i class="fa fa-angle-left" aria-hidden="true"></i></a></li>';
    }
   }
   return '';
  }
  
  public function display_next()
  {
   if ($this->link_end > $this->link_start) {
    if ($this->current_page < $this->total_pages) {
     return '<li class="page-item next"><a class="page-link" href="?' . $this->params . 'p=' . ($this->current_page + 1) . '"><i class="fa fa-angle-right" aria-hidden="true"></i></a></li>';
    } else {
     return '<li class="page-item next disabled"><a class="page-link" disabled><i class="fa fa-angle-right" aria-hidden="true"></i></a></li>';
    }
   }
   return '';
  }
  
  public function display_last()
  {
   if ($this->link_end > $this->link_start) {
    if ($this->current_page < $this->total_pages) {
     return '<li class="page-item last"><a class="page-link" href="?' . $this->params . 'p=' . $this->total_pages . '">LAST</a></li>';
    } else {
     return '<li class="page-item last disabled"><a class="page-link" disabled>LAST</a></li>';
    }
   }
   return '';
  }
  
  public function display_rand()
  {
   $str = '';
   if ($this->link_end > $this->link_start) {
    for ($i = $this->link_start; $i <= $this->link_end; $i++) {
     if ($i == $this->current_page) {
      $str .= '<li class="page-item active"><a class="page-link">' . $i . '</a></li>';
     } else {
      $str .= '<li class="page-item"><a class="page-link" href="?' . $this->params . 'p=' . $i . '">' . $i . '</a></li>';
     }
    }
   }
   return $str;
  }
  
  public function display_info()
  {
   return _('Showing') . ' ' . $this->get_sno() . ' to ' . min($this->offset + $this->rows_per_page, $this->total_rows) . ' ' . _('of') . ' ' . $this->total_rows . ($this->total_rows > $this->rows_per_page ? ' (' . (round($this->total_rows / $this->rows_per_page, 0)) . ' Pages)' : '');
  }
  
  public function display_all()
  {
   return $this->display_previous() . $this->display_first() . $this->display_rand() . $this->display_last() . $this->display_next();
  }
  
  public function display_paging_info()
  {
   $str = '<div class="row"><div class="col-sm-6">';
   if ($this->link_end > $this->link_start) {
    $str .= '<ul class="pagination pagination-box">' . $this->display_all() . '</ul>';
   }
   $str .= '</div><div class="col-sm-6 text-right"><div class="paging-info">' . $this->display_info() . '</div></div></div>';
   return $str;
  }
  
 }
