<?php
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "autoload.php";
$fn = new controllers\ads;
if ($fn->is_ajax_call()) {
    header('Content-Type: application/json');
    $json = [];

    if ($fn->post('action') == 'ads') {
        try {
            $fn->user_ads($fn->post('user_id'));
            $html = include app_path . 'views' . ds . 'ads.php';
            $json = array('success' => true, 'html' => $html, 'append' => true, 'load' => $fn->rows['load'], 'count' => $fn->rows['count'], 'total' => $fn->rows['total']);
        } catch (Exception $ex) {
            $json = array('error' => true, 'g_title' => 'Error', 'g_message' => $ex->getMessage());
        }
    }

    if ($json) {
        echo $fn->json_encode($json);
    }
    exit();
}
$fn->user_detail();
$fn->cms['page_title'] = $fn->cms['page_heading'] = $fn->cms['display_name'];
include app_path . 'inc' . ds . 'head.php';
include app_path . 'inc' . ds . 'header.php';
?>
    <section class="bkg-light profile">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="view-profile">
                        <div class="infos">
                            <div class="actions-block">
                                <ul class="stack">
                                    <li>
                                                <span class="user-details-wrapper">
                                                    <span class="user-details">
                                                        <span class="user-img">
                                                            <span class="avatar text-center">
                                                                <img src="<?php echo $fn->initials($fn->cms['display_name']); ?>"
                                                                     alt="<?php echo $fn->cms['display_name']; ?>">
                                                            </span>
                                                        </span>
                                                        <span class="user-name">
                                                            <span class="name">
                                                                <?php echo $fn->cms['display_name'];
                                                                if ($fn->cms['verified'] == 'Y') {
                                                                    ?>
                                                                    <span class="verified">
                                                                        <i class="far fa-check-circle"></i> Verified
                                                                    </span>
                                                                <?php } ?>
                                                            </span>
                                                        </span>
                                                    </span>
                                                </span>
                                    </li>
                                    <li>
                                                <span class="joined">
                                                    <strong>Joined:</strong> <?php echo $fn->time_ago($fn->cms['add_date']); ?>
                                                </span>
                                        <span class="location">
                                                    <i class="fa fa-map-marker-alt"></i> <?php echo $fn->cms['location']; ?></span>
                                    </li>
                                    <li>
                                        <ul class="links">
                                            <li>
                                                <a href="javascript:void(0);"
                                                   onclick="$(this).find('span').text('<?php echo $fn->cms['email']; ?>');">
                                                    <i class="fa fa-envelope" aria-hidden="true"></i>
                                                    <span>Show email</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);"
                                                   onclick="$(this).find('span').text('<?php echo $fn->cms['mobile_no']; ?>');">
                                                    <i class="fa fa-phone" aria-hidden="true"></i>
                                                    <span>Show number</span>
                                                </a>
                                            </li>
                                        </ul>
                                        <ul class="social">
                                            <li>
                                                <a href="<?php echo $fn->share_url('fb', $fn->meta); ?>"
                                                   target="_blank">
                                                    <i class="fab fa-facebook" aria-hidden="true"></i>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="<?php echo $fn->share_url('tw', $fn->meta); ?>"
                                                   target="_blank">
                                                    <i class="fab fa-twitter" aria-hidden="true"></i>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="<?php echo $fn->share_url('li', $fn->meta); ?>"
                                                   target="_blank">
                                                    <i class="fab fa-linkedin" aria-hidden="true"></i>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="<?php echo $fn->share_url('pi', $fn->meta); ?>"
                                                   target="_blank">
                                                    <i class="fab fa-pinterest" aria-hidden="true"></i>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="<?php echo $fn->share_url('em', $fn->meta); ?>"
                                                   target="_blank">
                                                    <i class="fa fa-envelope" aria-hidden="true"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="listing-products grid grid-4" data-listing-id="listing-1">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-header text-center">
                        <h3>User Ads</h3>
                    </div>
                </div>
            </div>
            <div class="row row-list" id="result">
                <?php echo include_once app_path . 'views' . ds . 'ads.php'; ?>
            </div>
            <?php if ($fn->rows['load'] < $fn->rows['total']) { ?>
                <div class="col-12">
                    <div class="view-more text-center load-more">
                        <button type="button" data-page="true" data-url="<?php echo str_ireplace(app_url, '', $fn->cms['url']) ; ?>" data-paging="1"
                                data-action="ads" data-app="<?php echo $fn->encrypt_post_data(['user_id' => $fn->cms['id']]); ?>" data-recid="result" class="btn btn-primary">Load More
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