<?php
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "autoload.php";
$fn = new controllers\ads;
if ($fn->is_ajax_call()) {
    header('Content-Type: application/json');
    $json = [];

    if ($fn->post('type') == 'category') {
        try {
            $html = $fn->show_list($fn->get_ads_subcat($fn->post('category')), '', true);
            $json = ['success' => true, 'html' => $html, 'script' => '$(\'#sub_category\').prop(\'disabled\', false);'];
        } catch (Exception $ex) {
            $json = ['error' => true, 'g_title' => 'Error', 'g_message' => $ex->getMessage()];
        }
    }

    if ($fn->post('action') == 'ads') {
        try {
            $fn->ads();
            $html = include app_path . 'views' . ds . 'ads.php';
            $json = array('success' => true, 'html' => $html, 'append' => true, 'load' => $fn->rows['load'], 'count' => $fn->rows['count'], 'total' => $fn->rows['total']);
        } catch (Exception $ex) {
            $json = array('error' => true, 'g_title' => 'Error', 'g_message' => $ex->getMessage());
        }
    }

    if ($fn->post('action') == 'search') {
        try {
            $fn->ads();
            $html = include app_path . 'views' . ds . 'ads.php';
            $url = explode('?', $fn->server('HTTP_REFERER'))[0];
            if (count($fn->filter) > 0) {
                $url = $url . '?' . urldecode(http_build_query($fn->filter));
            }
            $json = array('success' => true, 'html' => $html, 'load' => $fn->rows['load'], 'count' => $fn->rows['count'], 'total' => $fn->rows['total'], 'script' => 'app.change_url(\'\', \'' . $url . '\');app.scroll($(\'#result\').offset().top - 150);');
        } catch (Exception $ex) {
            $json = array('error' => true, 'g_title' => 'Error', 'g_message' => $ex->getMessage());
        }
    }

    if ($fn->post('type') == 'filters') {
        try {
            $filters = $fn->get_filters($fn->post('sub_category'));
            $html = include app_path . 'views' . ds . 'search_filters.php';
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


    if ($json) {
        echo $fn->json_encode($json);
    }
    exit();
}
$fn->ads();
$current = $fn->current_url();
$parse = parse_url($current);
$url = str_ireplace(app_url, '', $current);
if ($fn->varv('query', $parse) != '') {
    $url = str_ireplace('?' . $parse['query'], '', $url);
}
include app_path . 'inc' . ds . 'head.php';
include app_path . 'inc' . ds . 'header.php';
?>
    <section class="bkg-light medium">
        <div class="container">
            <div class="row">
                <div class="col-12">

                    <div class="boxed-container search-results">
                        <div class="row">
                            <div class="col-12">
                                <form method="post" class="search-block form-validate" name="search-frm" id="search-frm"
                                      autocomplete="off" data-ajax="true" data-url="<?php echo $url; ?>"
                                      data-page="true" data-action="search" data-recid="result">
                                    <div class="row">
                                        <div class="col-12 col-lg-4">
                                            <div class="form-group icon">
                                                <i class="fa fa-search" aria-hidden="true"></i>
                                                <input type="text" class="form-control" name="q" id="q"
                                                       placeholder="Search for anything"
                                                       value="<?php echo $fn->post_get('q'); ?>"/>
                                            </div>
                                        </div>
                                        <div class="col-12 col-lg-4">
                                            <div class="form-group icon">
                                                <i class="fa fa-map-marker-alt" aria-hidden="true"></i>
                                                <input type="text" class="form-control" placeholder="Location"
                                                       name="location" id="location"
                                                       value="<?php echo $fn->post_get('location'); ?>"/>
                                            </div>
                                        </div>
                                        <div class="col-12 col-lg-4">
                                            <div class="form-group icon">
                                                <i class="fa fa-bars" aria-hidden="true"></i>
                                                <select class="form-control" name="category" id="category"
                                                        data-placeholder="Category" data-allow-clear="true"
                                                        data-ajaxify="true" data-page="true" data-url="ads"
                                                        data-type="category" data-event="change"
                                                        data-recid="sub_category">
                                                    <?php
                                                    $cat_id = $fn->post_get('category');
                                                    if (!$fn->post_get('action')) {
                                                        $cat_id = $fn->cms['id'];
                                                    }
                                                    echo $fn->show_list($fn->get_ads_cat(), $cat_id, true); ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 col-lg-4">
                                            <div class="form-group icon">
                                                <i class="fa fa-bars" aria-hidden="true"></i>
                                                <select class="form-control" name="sub_category" id="sub_category"
                                                        data-placeholder="Sub Category"
                                                        data-allow-clear="true"<?php echo $cat_id ? '' : ' disabled'; ?>
                                                        data-ajaxify="true" data-page="true" data-url="ads"
                                                        data-type="filters"
                                                        data-event="change" data-recid="adv-filters">
                                                    <?php echo $fn->show_list($fn->get_ads_subcat($cat_id), $fn->post_get('sub_category'), true); ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12 col-lg-4">
                                            <div class="form-group icon">
                                                <i class="fa fa-money-bill" aria-hidden="true"></i>
                                                <input type="text" class="form-control" placeholder="Min price"
                                                       name="min_price" id="min_price"
                                                       value="<?php echo $fn->post_get('min_price'); ?>"/>
                                            </div>
                                        </div>
                                        <div class="col-12 col-lg-4">
                                            <div class="form-group icon">
                                                <i class="fa fa-money-bill" aria-hidden="true"></i>
                                                <input type="text" class="form-control" placeholder="Max price"
                                                       name="max_price" id="max_price"
                                                       value="<?php echo $fn->post_get('max_price'); ?>"/>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row" id="adv-filters">
                                        <?php if ($fn->post_get('sub_category') != '') {
                                            $filters = $fn->get_filters($fn->post_get('sub_category'));
                                            echo include app_path . 'views' . ds . 'search_filters.php';
                                        } ?>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 col-lg-6 ">
                                            <div class="form-group">
                                                <?php if ($fn->get('show') != '') { ?>
                                                    <input type="hidden" name="show" value="<?php echo $fn->get('show'); ?>"/>
                                                <?php } ?>
                                                <button type="submit" class="btn btn-primary full-width" value="Submit">
                                                    Search
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-12 col-lg-6 ">
                                            <div class="form-group">
                                                <button type="button" class="btn btn-default full-width" value="Submit"
                                                        onclick="app.reset_form('search-frm');">Reset
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <section class="listing-products grid-3 grid" data-listing-id="listing-1">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-header text-center">
                        <h3>Ads</h3>
                    </div>
                </div>
            </div>
            <div class="row" id="result">
                <?php echo include_once app_path . 'views' . ds . 'ads.php'; ?>
            </div>
            <?php if ($fn->rows['load'] < $fn->rows['total']) { ?>
                <div class="col-12">
                    <div class="view-more text-center load-more">
                        <button type="button" data-page="true" data-url="<?php echo $url; ?>" data-paging="1"
                                data-action="ads" data-recid="result" class="btn btn-primary">Load More
                        </button>
                    </div>
                </div>
            <?php } ?>

        </div>
    </section>
<?php
include app_path . 'inc' . ds . 'footer.php';
include app_path . 'inc' . ds . 'foot.php';
?>