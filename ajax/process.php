<?php

require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'autoload.php';
$fn = new controllers\controller;

if ($fn->is_ajax_call()) {
    header('Content-Type: application/json');
    $json = '';

    /*
     * Subscribe
     */

    if ($fn->post('action') == 'subscribe') {
        try {
            $fn->subscribe();
            $type = 'subscribe';
            $json = array('success' => true, 'html' => include(app_path . 'views' . ds . 'modals.php'), 'script' => 'app.reset_form(\'sub-frm\');', 'modal' => true, 'modalBackdrop' => 'static');
        } catch (Exception $ex) {
            $json = array('error' => true, 'g_title' => 'Error', 'g_message' => $ex->getMessage(), 'script' => 'app.reset_form(\'sub-frm\');');
        }
    }

    /*
  * Verify Subscriber
  */

    if ($fn->post('action') == 'verify') {
        try {
            $fn->verify_subscriber();
            $json = array('success' => true, 'g_title' => 'Subscribe', 'g_message' => 'Thanks for subscribe ' . app_name . '.', 'script' => 'app.hide_modal(\'modal\');');
        } catch (Exception $ex) {
            $json = array('error' => true, 'g_title' => 'Error', 'g_message' => $ex->getMessage());
        }
    }

    if ($json) {
        echo $fn->json_encode($json);
    }
    exit();
}
?>
