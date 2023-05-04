<?php
 
 namespace controllers;
 
 use Exception;
 
 class test extends controller
 {
  
  public function __construct()
  {
   parent::__construct();
   $this->page['name'] = 'Test';
  }
  
  public function geturlcontents($url)
  {
   $html = '';
   if (function_exists("curl_init")) {
    $c = curl_init($url);
    $headers = array(
     ":authority: jiji.ng",
     ":method: GET",
     ":path: " . str_replace('https://jiji.ng', '', $url),
     ":scheme: https",
     "accept: application/json, text/plain, */*",
//     "accept-encoding: gzip, deflate, br",
     "accept-language: en-US,en;q=0.9",
     'cookie: uid=3e912263bff9a965bd29f7e4515e4733e17ef89f;',
//     "referer: https://jiji.ng/cars",
     "sec-fetch-dest: empty",
     "sec-fetch-mode: cors",
     "sec-fetch-site: same-origin",
     "user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.121 Safari/537.36",
     "x-requested-with: XMLHttpRequest"
    );
    
    curl_setopt($c, CURLOPT_HEADER, 0);
    curl_setopt($c, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($c, CURLOPT_URL, $url);
    curl_setopt($c, CURLOPT_MAXCONNECTS, 1);
    curl_setopt($c, CURLOPT_MAXREDIRS, 1);
    curl_setopt($c, CURLOPT_FOLLOWLOCATION, false);
    curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($c, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($c, CURLOPT_ENCODING, '');
    $html = curl_exec($c);
    curl_close($c);
   }
   return $html;
  }
  
  public function filter_data()
  {
   echo "<pre>";
   echo "Start Nested Entries";
   die();
   $date = date('Y-m-d H:i:s');
   
   $query = "SELECT t.*, c.master_id FROM test t "
    . "LEFT OUTER JOIN m_ads_cat c ON t.ad_cat_id=c.id "
    . "WHERE t.status='N'";
   $cats = $this->db->selectall($query);
   if ($cats) {
    foreach ($cats as $c) {
     $contents = $this->geturlcontents('https://jiji.ng/api_web/v1/listing?slug=' . $c['slug']);
     if ($contents) {
      $this->db->update('test', ['status' => 'I', 'filter_json' => $contents], ['id' => $c['id']]);
      $filters = $this->json_decode($contents)['filters'];
      $af_id = '0';
      $exists = $this->db->select("SELECT id FROM m_filters WHERE category_id='" . $c['master_id'] . "' AND sub_category_id='" . $c['ad_cat_id'] . "'");
      if (!$exists) {
       $af_id = $this->db->insert('m_filters', ['category_id' => $c['master_id'], 'sub_category_id' => $c['ad_cat_id'], 'test_id' => $c['id'], 'publish' => 'Y', 'add_date' => $date]);
      } else {
       $af_id = $exists['id'];
      }
      if ($filters) {
       $f_label = $n_label = $label = [];
       foreach ($filters['scheme'] as $k => $f) {
        if ($f['visual_type'] == 'select' && isset($f['filter_data']) && is_array($f['filter_data'])) {
         foreach ($f['filter_data'] as $key => $data) {
          $f_label[$key] = [
           'af_id' => $af_id,
           'title' => $data['attr_name'],
           'placeholder' => ($data['visual_type'] == 'select' ? 'Select ' : '') . $data['attr_name'],
           'type' => ($data['visual_type'] == 'select' ? 'S ' : 'I'),
           'linked' => 'Y',
           'parent_name' => $data['attr_parent_name'],
           'filter_name' => $data['filter_name'],
           'update_url' => $filters['update_filters_url']
          ];
          if (!empty($data['popular_values']) || !empty($data['possible_values'])) {
           $pp_values = $data['popular_values'];
           $ps_values = $data['possible_values'];
           $values = array_replace_recursive($pp_values, $ps_values);
           if ($values) {
            $f_label[$key]['values'] = array_unique(array_column($values, 'value'));
           } else {
            $f_label[$key]['linked'] = 'N';
           }
          } else {
           $f_label[$key]['linked'] = 'N';
          }
         }
        } else {
         $data = $f;
         $n_label[$k] = [
          'af_id' => $af_id,
          'title' => $data['attr_name'],
          'placeholder' => ($data['visual_type'] == 'select' ? 'Select ' : '') . $data['attr_name'],
          'type' => ($data['visual_type'] == 'select' ? 'S ' : 'I'),
          'linked' => 'N',
          'filter_name' => $data['filter_name'],
          'update_url' => $filters['update_filters_url']
         ];
         if (!empty($data['popular_values']) || !empty($data['possible_values'])) {
          $pp_values = $data['popular_values'];
          $ps_values = $data['possible_values'];
          $values = array_replace_recursive($pp_values, $ps_values);
          if ($values) {
           $n_label[$k]['values'] = array_unique(array_column($values, 'value'));
          }
         }
        }
       }
       $label = array_merge_recursive($label, $f_label);
       $label = array_merge_recursive($label, $n_label);
       if ($label) {
        foreach ($label as $l) {
         $filter_val = [];
         if ($l) {
          $fl_id = $this->db->insert('m_filters_label', [
           'af_id' => $l['af_id'],
           'title' => $l['title'],
           'placeholder' => $l['placeholder'],
           'type' => $l['type'],
           'linked' => $l['linked'],
           'filter_name' => $l['filter_name'],
           'update_url' => $l['update_url']
          ]);
          if (isset($l['values']) && is_array($l['values'])) {
           foreach ($l['values'] as $fv) {
            $filter_val[] = [
             'fl_id' => $fl_id,
             'title' => $fv,
             'publish' => 'Y'
            ];
           }
          }
          if ($filter_val) {
           $this->db->batch('insert', 'm_filters_value', $filter_val);;
          }
         }
        }
       }
       $this->db->update('test', ['status' => 'Y'], ['id' => $c['id']]);
      }
     }
     sleep(1);
     echo $c['name'] . " Filters Added<br/>";
    }
   }
   
   /*$contents = $this->geturlcontents('https://jiji.ng/update-filters?category_id=29&filter_attr_1_make=Toyota');
   $contents = htmlspecialchars($contents);
   echo mb_check_encoding($contents);
   echo "<br/>";
   print_r($contents);
   echo "<br/>";
   print_r(json_decode($contents));
   echo "<br/>";
   print_r(json_last_error_msg());*/
   
   
  }
  
  
  public function nested_filter_data()
  {
   echo "<pre>";
   $date = date('Y-m-d H:i:s');
   
   $query = "SELECT id, af_id, linked, filter_name, update_url FROM m_filters_label WHERE linked='Y' AND status='Y'";
   $labels = $this->db->selectall($query);
   if ($labels) {
    foreach ($labels as $l) {
     $this->db->update('m_filters_label', ['status' => 'I'], ['id' => $l['id']]);
     $query = "SELECT id, title FROM m_filters_value WHERE fl_id='" . $this->replace_sql($l['id']) . "' AND master_id='0' AND filter_json LIKE '%<h1>400 Bad request</h1>%'";
     $values = $this->db->selectall($query);
     if ($values) {
      foreach ($values as $v) {
       $url = 'https://jiji.ng' . $l['update_url'] . '&' . $l['filter_name'] . '=' . urlencode($v['title']);
       $contents = $this->geturlcontents($url);
       if ($contents) {
        $filters = $this->json_decode($contents);
        if ($filters) {
         $f_values = [];
         foreach ($filters['schema'] as $k => $f) {
          if ($f['visual_type'] == 'select' && isset($f['filter_data']) && is_array($f['filter_data'])) {
           foreach ($f['filter_data'] as $key => $data) {
            if (!empty($data['attr_parent_name'])) {
             $filter_exists = $this->db->select("SELECT id FROM m_filters_label WHERE filter_name='" . $data['filter_name'] . "' AND update_url='" . $l['update_url'] . "'");
             if ($filter_exists) {
              $this->db->update('m_filters_label', ['linked_id' => $l['id'], 'parent_name' => $data['attr_parent_name']], ['id' => $filter_exists['id']]);
             }
             if (!empty($data['popular_values']) || !empty($data['possible_values'])) {
              $pp_values = $data['popular_values'];
              $ps_values = $data['possible_values'];
              $values = array_replace_recursive($pp_values, $ps_values);
              if ($values) {
               $f_values[$filter_exists['id']] = array_unique(array_column($values, 'value'));
              }
             }
            }
           }
          }
         }
         if ($f_values) {
          foreach ($f_values as $fl_id => $val) {
           $filter_val = [];
           if ($val) {
            foreach ($val as $fv) {
             $filter_val[] = [
              'fl_id' => $fl_id,
              'master_id' => $v['id'],
              'title' => $fv,
              'publish' => 'Y'
             ];
            }
           }
           if ($filter_val) {
            $this->db->batch('insert', 'm_filters_value', $filter_val);;
           }
          }
          
         }
        }
        $this->db->update('m_filters_value', ['filter_json' => $contents], ['id' => $v['id']]);
       }
      }
     }
     $this->db->update('m_filters_label', ['status' => 'Y'], ['id' => $l['id']]);
     sleep(1);
     echo "Nested Filters Added<br/>";
    }
   }
  }
  
 }
