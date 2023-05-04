<?php

 namespace resources\controllers;

 final class email
 {

  private $fn, $db;
  private $bcc = '', $cc = '', $prevent = '';
  private $replace = array();

  public function __construct($fn, $db)
  {
   $this->fn = $fn;
   $this->db = $db;
  }

  public function send_auto_email($key, $id, $attachments = array())
  {
   if ($content = $this->db->select("SELECT * FROM a_nots WHERE not_key='" . $this->fn->replace_sql($key) . "'")) {
    $options = $this->get_mail_options($key, $id);
    $content = $this->compile_mail_matter($content, $options);
    $users = $this->get_mail_users($content, $options);
    if ($users) {
     foreach ($users as $v) {
      $body = $content['not_desc'];
      $this->send_template_email($v['email'], $content['not_subject'], $body, $attachments);
     }
    }
   }
  }

  public function get_mail_options($key, $id)
  {
   $options = array();
   switch ($key) {
    case in_array($key, array('forgot_pass')):
     $options = $this->db->select("SELECT v.*, u.first_name, u.email as user_email, u.display_name as user_name FROM v_codes v INNER JOIN users u ON v.type_id=u.id WHERE v.type='Forgot' AND v.type_id='" . $this->fn->replace_sql($id) . "' AND v.status='N'");
     $options['reset_link'] = $this->fn->permalink('reset-verification', array(
      'code_id' => $options['id'],
      'code' => $options['code'],
      'email' => $options['user_email']
     ));
     break;

    case in_array($key, array('verify_subscriber')):
     $options = $this->db->select("SELECT v.*, u.email as user_email, u.name as user_name FROM v_codes v INNER JOIN v_subscribers u ON v.type_id=u.id WHERE v.type='Subscribe' AND v.type_id='" . $this->fn->replace_sql($id) . "' AND v.status='N'");
     break;

    case in_array($key, array('new_registration', 'change_pass')):
     $options = $this->db->select("SELECT u.*, u.first_name as first_name, u.display_name as user_name, u.email as user_email, u.ip, u.browser, u.os FROM users u WHERE u.id='" . $this->fn->replace_sql($id) . "'");
     break;

    case in_array($key, array('register_verification')):
     $options = $this->db->select("SELECT v.*, u.first_name, u.email as user_email, u.display_name as user_name FROM v_codes v INNER JOIN users u ON v.type_id=u.id WHERE v.type='Register' AND v.type_id='" . $this->fn->replace_sql($id) . "' AND v.status='N'");
     $options['verify_link'] = $this->fn->permalink('register-verification', array(
      'code_id' => $options['id'],
      'code' => $options['code'],
      'email' => $options['user_email']
     ));
     break;

    case in_array($key, array('contact', 'contact_thanks', 'contact_reply')):
     $options = $this->db->select("SELECT *, display_name as user_name, email as user_email FROM v_contacts WHERE id='" . $this->fn->replace_sql($id) . "'");
     break;

    case in_array($key, array('a_forgot_pass', 'a_change_pass')):
     $options = $this->db->select("SELECT u.first_name, u.display_name as user_name, u.email as user_email, u.a_code, g.group_name FROM a_users u INNER JOIN a_groups g ON u.group_id=g.id WHERE u.id='" . $this->fn->replace_sql($id) . "'");
     break;
   }
   return $options;
  }

  public function compile_mail_matter($content, $options = array())
  {
   if (count($options) > 0) {
    foreach ($options as $k => $v) {
     if (strpos($k, '_box') !== false) {
      $content['not_subject'] = str_replace('#' . $k . '#', $v, $content['not_subject']);
      $content['not_desc'] = str_replace('#' . $k . '#', $v, $content['not_desc']);
     } else {
      if ($options[$k] == '') {
       $v = '-';
      }
      $content['not_subject'] = str_replace('#' . $k . '#', $this->fn->make_html($v), $content['not_subject']);
      $content['not_desc'] = str_replace('#' . $k . '#', $this->fn->make_html($v), $content['not_desc']);
     }
    }
   }
   return $content;
  }

  public function get_mail_users($content, $options)
  {
   $users = array();
   if ($content['for_admin'] == 'Y') {
    if (in_array($content['not_key'], array('a_forgot_pass', 'a_change_pass'))) {
     if ($this->fn->varv('user_email', $options)) {
      $dt = array('group' => $options['group_name'], 'name' => $options['user_name'], 'email' => $options['user_email']);
      $users[] = $dt;
     }
    } else {
     if ($dt = $this->db->selectall("SELECT g.group_name, u.display_name as name, u.email FROM a_nots_per p INNER JOIN a_groups g ON p.group_id=g.id INNER JOIN a_users u ON g.id=u.group_id AND u.publish='Y' AND u.email!='' WHERE p.not_id='" . $this->fn->replace_sql($content['id']) . "'")) {
      $users = $dt;
     }
    }
   }
   if ($content['for_user'] == 'Y') {
    if (isset($options['user_email'])) {
     $names = explode(',', $options['user_name']);
     $emails = explode(',', $options['user_email']);
     if ($names) {
      foreach ($names as $k => $v) {
       $users[] = array('name' => $v, 'email' => $emails[$k]);
      }
     }
    }
   }

   $query = "SELECT * FROM a_nots_user WHERE (not_id=0 AND not_id='" . $content['not_key'] . "') ORDER BY type_id";
   if ($data = $this->db->selectall($query)) {
    foreach ($data as $v) {
     if ($v['emails'] = ($this->fn->varv('emails', $v) ? $this->fn->json_decode($this->fn->varv('emails', $v)) : '')) {
      $v['emails'] = explode(',', $v['emails']);
      // If CC
      if ($v['type_id'] == 1) {
       foreach ($v['emails'] as $email) {
        $this->cc[$email] = $email;
       }
      }
      // If BCC
      if ($v['type_id'] == 2) {
       foreach ($v['emails'] as $email) {
        $this->bcc[$email] = $email;
       }
      }
      // If Prevent
      if ($v['type_id'] == 3) {
       foreach ($v['emails'] as $email) {
        $this->prevent[$email] = $email;
       }
      }
     }
    }
   }
   return $users;
  }

  public function send_template_email($to, $subject, $matter, $attachments = array())
  {
   $this->replace = array(
    'matter' => $matter,
    'app_name' => app_name,
    'app_url' => app_url,
    'to' => $to,
    'logo_url' => app_url . 'assets/images/logo.png',
    'ip' => $this->fn->server('REMOTE_ADDR'),
    'browser' => $this->fn->get_browser(),
    'os' => $this->fn->get_os(),
    'timing' => $this->fn->user_timing()
   );
   $template = app_path . 'resources' . ds . 'emails' . ds . 'template.html';
   $subject = $this->replace_str($subject);
   $body = file_get_contents($template);
   $body = $this->replace_str($body);
   $this->send_email($to, $subject, $body, $attachments);
   @usleep(100);
  }

  public function replace_str($str)
  {
   foreach ($this->replace as $k => $v) {
    $str = str_replace('#' . $k . '#', $v, $str);
   }
   return $str;
  }

  public function send_email($to, $subject, $body, $attachments = array())
  {
   if ($this->fn->varv($to, $this->prevent) != '') {
    return false;
   }
   require_once(app_path . 'resources' . ds . 'apis' . ds . 'PHPMailer' . ds . 'class.phpmailer.php');
   $email = new \PHPMailer();
   if (local) {
    $email->isSMTP();
    //$email->SMTPDebug = 3;
    $email->SMTPAuth = true;

    //   For Mailtrap
    $email->Host = 'smtp.mailtrap.io';
    $email->Port = 2525;
    $email->Username = '834956fb766cea';
    $email->Password = '865a10e6b52733';
    $email->SMTPSecure = 'tls';
   }
   $email->ContentType = 'text/html';
   $email->From = app_email;
   $email->FromName = app_name;
   $email->Subject = $subject;
   $email->Body = $body;
   $email->IsHTML(true);
   $email->AddAddress($to);
   if ($this->cc) {
    foreach ($this->cc as $cc) {
     if ($this->fn->varv($cc, $this->prevent) == '') {
      $email->AddCC($cc);
     }
    }
   }
   if ($this->bcc) {
    foreach ($this->bcc as $bcc) {
     if ($this->fn->varv($bcc, $this->prevent) == '') {
      $email->AddBCC($bcc);
     }
    }
   }
   if (isset($attachments) && count($attachments) > 0) {
    foreach ($attachments as $v) {
     $email->AddAttachment($v['path'], $v['name']);
    }
   }
   $email->Send();
  }

 }
 