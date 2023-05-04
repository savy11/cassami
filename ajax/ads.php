<?php

require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'autoload.php';
$fn = new controllers\controller;
if ($fn->is_ajax_call()) {
    header('Content-Type: application/json');
    $json = [];
    if ($fn->post('type') == 'category') {
        try {
            $html = $fn->show_list($fn->get_ads_subcat($fn->post('ad_category_id')), '', true);
            $json = ['success' => true, 'html' => $html, 'script' => '$(\'#ad-sub-category\').prop(\'disabled\', false);$(\'#filters\').html(\'\');'];
        } catch (Exception $ex) {
            $json = ['error' => true, 'g_title' => 'Error', 'g_message' => $ex->getMessage()];
        }
    }

    if ($fn->post('type') == 'filters') {
        try {
            $filters = $fn->get_filters($fn->post('ad_sub_category_id'));
            $html = include app_path . 'views' . ds . 'filters.php';
            $json = ['success' => true, 'html' => $html];
        } catch (Exception $ex) {
            $json = ['error' => true, 'g_title' => 'Error', 'g_message' => $ex->getMessage()];
        }
    }

    if ($fn->post('type') == 'nested_filter') {
        try {
            $linked_id = $fn->post('linked_id');
            $key = 'S_' . $fn->post('label') . '_' . $linked_id;
            $filter_id = $fn->post('filter', $key);;
            $data = $fn->get_nested_filters($linked_id, $filter_id, true);
            $title = trim(preg_replace('/\s+/', '||', $data['title']));
            $json = ['success' => true, 'rec' => ['filter-S_' . $title . '_' . $data['id'] => $data['html']], 'script' => '$(\'#filter-S_' . $title . '_' . $data['id'] . '\').prop(\'disabled\', false);'];
        } catch (Exception $ex) {
            $json = ['error' => true, 'g_title' => 'Error', 'g_message' => $ex->getMessage()];
        }
    }

    if ($fn->post('type') == 'cities') {
        try {
            $json = ['success' => true, 'html' => $fn->show_list($fn->get_cities($fn->post('ad_state_id')), '', true), 'script' => '$(\'#ad-city-id\').prop(\'disabled\', false);'];
        } catch (Exception $ex) {
            $json = ['error' => true, 'g_title' => 'Error', 'g_message' => $ex->getMessage()];
        }
    }

    if ($fn->post('action') == 'preview') {
        try {
            $fn->preview();
            $type = 'preview';
            $json = ['success' => true, 'html' => include_once app_path . 'views' . ds . 'post_ad.php', 'modalBackdrop' => 'static', 'modal' => true, 'script' => 'app.single_slider();'];
        } catch (Exception $ex) {
            $json = ['error' => true, 'g_title' => 'Error', 'g_message' => $ex->getMessage()];
        }
    }

    if ($fn->post('action') == 'post') {
        try {
            $type = 'post';
            $fn->post_ad();
            $json = ['success' => true, 'rec' => ['result' => include_once app_path . 'views' . ds . 'post_ad.php'], 'script' => 'app.scroll($(\'#result\').offset().top);'];
        } catch (Exception $ex) {
            $json = ['error' => true, 'g_title' => 'Error', 'g_message' => $ex->getMessage()];
        }
    }

    if ($fn->post('action') == 'add_fav') {
        try {
            $fn->ad_favourite();
            $json = ['success' => true, 'g_title' => 'Favourite', 'g_message' => 'Ad has been saved as favourite.'];
        } catch (Exception $ex) {
            $json = ['error' => true, 'g_title' => 'Error', 'g_message' => $ex->getMessage()];
        }
    }

    if ($fn->post('type') == 'report') {
        try {
            if (!$fn->validate_login()) {
                throw new Exception('Please login first to report this ad');
            }
            $type = $fn->post('type');
            $json = ['success' => true, 'html' => include_once app_path . 'views' . ds . 'modals.php', 'modal' => true, 'modalBackdrop' => 'static'];
        } catch (Exception $ex) {
            $json = ['error' => true, 'g_title' => 'Error', 'g_message' => $ex->getMessage()];
        }
    }

    if ($fn->post('action') == 'report_ad') {
        try {
            $fn->report_ad();
            $json = ['success' => true, 'g_title' => 'Report Ad', 'g_message' => 'Ad has been reported successfully.', 'script' => 'app.hide_modal(\'modal\');setTimeout(function(){window.location.href=\'' . $fn->permalink() . '\'}, 1000);'];
        } catch (Exception $ex) {
            $json = ['error' => true, 'g_title' => 'Error', 'g_message' => $ex->getMessage()];
        }
    }

    if ($json) {
        echo $fn->json_encode($json);
    }
    exit();
}