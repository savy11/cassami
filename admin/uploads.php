<?php
 require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'autoload.php';
 $fn = new admin\controllers\controller;
 
 function toBytes($val)
 {
  $val = trim($val);
  $last = strtolower($val[strlen($val) - 1]);
  $val = substr($val, 0, -1);
  switch ($last) {
   case 'g':
    $val *= 1024;
   case 'm':
    $val *= 1024;
   case 'k':
    $val *= 1024;
  }
  return $val;
 }
 
 $json = [];
 try {
  if (!(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')) {
   throw new Exception('Bad request');
  }
  $allowedExtensions = ['pdf', 'png', 'jpeg', 'gif', 'jpg', 'webp', 'csv', 'doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx'];
  $sizeLimit = (10 * 1024 * 1024);
  
  $type = $fn->get('type');
  $upload_dir = $fn->tmp_path();
  
  
  if (!is_writable($upload_dir)) {
   throw new Exception('Server error. Upload directory is not writable. ' . $upload_dir);
  }
  
  $file = (isset($_FILES['qqfile']) ? $_FILES['qqfile'] : []);
  if (!$file) {
   throw new Exception('Please upload at least one file.' . $file);
  }
  $info = pathinfo($file['name']);
  $filename = $fn->img_replace($info['basename']);
  $ext = strtolower($info['extension']);
  $size = $file['size'];
  if ($size == 0) {
   throw new Exception('(' . $filename . ') is empty, please select file again without it.');
  }
  if (!in_array(strtolower($ext), $allowedExtensions)) {
   $these = implode(', ', $allowedExtensions);
   throw new Exception('Your file could not be uploaded because ' . $filename . ' has invalid extension. Only ' . $these . ' are allowed.');
  }
  if ($size > $sizeLimit) {
   throw new Exception('Your file could not be uploaded. File (' . $filename . ') should be less than ' . toBytes($sizeLimit) . '.');
  }
  
  $new_filename = date('YmdHis') . '_' . rand(0000000, 9999999) . '.' . $ext;
  $source_path = $upload_dir . $new_filename;
  
  move_uploaded_file($file['tmp_name'], $source_path);
  
  if (in_array($ext, ['png', 'jpg', 'jpeg', 'webp']) !== FALSE) {
   $im = new \resources\controllers\image_resize;
   if ($type == 'ads') {
    list($w, $h) = $im->get_wh(500, $source_path, 2000, 2000);
    $im->square_resize($source_path, $source_path, $w, $h);
   } else {
    $im->resize_wh($source_path, $source_path, 2000, 2000);
   }
  }
  
  $tmp_thumb = $fn->tmp_file_data($new_filename);
  $_SESSION[$type][$new_filename] = ['name' => $filename, 'filename' => $new_filename, 'size' => $size, 'ext' => $ext];
  
  $thumb = $fn->is_image($ext) ? $fn->get_file($tmp_thumb, 0, 0, 200) : $fn->get_default_icon($ext);
  
  $json = ['success' => TRUE, 'type' => $type, 'name' => $filename, 'file' => $fn->get_file($tmp_thumb), 'thumb' => $thumb, 'app' => $fn->encrypt_post_data(['for' => $type, 'filename' => $new_filename]), 'filename' => $new_filename];
 } catch (Exception $ex) {
  $json = ['error' => TRUE, 'message' => $ex->getMessage()];
 }
 
 header('Content-Type: application/json');
 echo $fn->json_encode($json);
 exit();
