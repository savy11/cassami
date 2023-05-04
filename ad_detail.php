<?php
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "autoload.php";
$fn = new controllers\ads;
if ($fn->is_ajax_call()) {
    header('Content-Type: application/json');
    $json = array();

    if ($fn->post('action') == 'comment') {
        try {
            $fn->insert_comment();
            $json = array('success' => true, 'g_title' => 'Comment', 'g_message' => 'Your comment has been submitted successfully. It will be published after admin approval.', 'script' => 'app.reset_form(\'ad-comment-frm\');$(\'.refresh-captcha\').trigger(\'click\');');
        } catch (Exception  $ex) {
            $json = ['error' => true, 'g_title' => 'Error', 'g_message' => $ex->getMessage()];
        }
    }

    if ($json) {
        echo $fn->json_encode($json);
    }
    exit();
}
$fn->ad();
$fn->cms['page_title'] = $fn->cms['page_heading'] = $fn->cms['ad_title'];
$fn->cms['meta_keywords'] = $fn->cms['ad_keywords'];
$fn->cms['meta_desc'] = $fn->show_string($fn->cms['ad_desc'], 100, false);
$breadcrumb = ['Ads' => $fn->permalink('ads')];
include app_path . 'inc' . ds . 'head.php';
include app_path . 'inc' . ds . 'header.php';
?>
    <section class="bkg-light view-listing-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="view-listing">
                        <div class="title">
                            <div class="row justify-content-center align-self-center h-100">
                                <div class="col-12 col-lg-12 my-auto">
                                    <div class="row justify-content-center align-self-center h-100">
                                        <div class="col-12 col-md-8 my-auto">
                                            <h4><?php echo $fn->cms['ad_title']; ?></h4>
                                        </div>
                                        <div class="col-12 col-md-4 my-auto">
                                            <div class="price"><?php echo $fn->show_price($fn->cms['ad_price']); ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="infos">
                            <div class="row">
                                <div class="col-12 col-lg-8">
                                    <div class="gallery">
                                        <?php if ($fn->cms['promoted'] == 'Y') { ?>
                                            <div class="promoted">
                                                <span>Promoted</span>
                                            </div>
                                        <?php } ?>
                                        <div class="counter">
                                            <span><?php echo count($fn->cms['files']); ?></span>Images
                                        </div>
                                        <?php if ($fn->cms['files']) { ?>
                                            <div class="slider-single animation-slide height-auto dir-nav">
                                                <?php foreach ($fn->cms['files'] as $v) { ?>
                                                    <div class="slider-item">
                                                        <div class="image" style="background-image: url('<?php echo $fn->get_file($v['meta_value']) ?>');">
                                                            <a href="<?php echo $fn->get_file($v['meta_value']) ?>" data-toggle="lightbox" data-gallery="product-gallery">
                                                                <img src="<?php echo $fn->get_file($v['meta_value']) ?>" alt="<?php echo $fn->cms['ad_title'] . ' ' . $v['id']; ?>"/>
                                                            </a>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-4">
                                    <div class="actions-block">

                                        <ul class="stack">
                                            <li>
                                                <span class="user-details-wrapper">
                                                    <span class="user-details">
                                                        <span class="user-img">
                                                            <span class="avatar text-center">
                                                                <img src="<?php echo $fn->initials($fn->cms['display_name']); ?>" alt="<?php echo $fn->cms['display_name']; ?>"/>
                                                                <!--<i class="far fa-user" aria-hidden="true"></i>-->
                                                            </span>
                                                        </span>
                                                        <span class="user-name">
                                                            <span class="name">
                                                                <?php echo ucwords($fn->cms['display_name']);
                                                                if ($fn->cms['verified'] == 'Y') {
                                                                    ?>
                                                                    <span class="verified"><i class="far fa-check-circle"></i> Verified</span>
                                                                <?php } ?>
                                                            </span>
                                                        </span>
                                                    </span>
                                                </span>
                                            </li>
                                            <li>
                                                <a href="<?php echo $fn->permalink('user-profile', ['page_url' => $fn->cms['display_name'], 'id' => $fn->cms['ad_user_id']]); ?>"
                                                   class="btn-action active">View profile</a>
                                            </li>
                                            <li>
                                                <span class="joined">
                                                    <strong>Joined:</strong> <?php echo $fn->time_ago($fn->cms['user_add_date']); ?>
                                                </span>
                                            </li>
                                            <li>
                                                <a href="mailto:<?php echo $fn->cms['ad_email']; ?>" class="btn btn-primary"><i class="fa fa-envelope"></i> Send email</a>
                                                <a href="tel:<?php echo $fn->cms['ad_no']; ?>" class="btn btn-primary"><i class="fa fa-phone"></i> Call</a>
                                            </li>
                                            <li>
                                                <span class="location"><i class="fa fa-map-marker-alt"></i> <?php echo $fn->cms['location']; ?></span>
                                                <span class="posted"><i class="far fa-clock"></i> Posted <?php echo $fn->time_ago($v['add_date']); ?></span>
                                                <span class="views"><i class="fa fa-eye"></i> <?php echo $fn->cms['total_views']; ?> ad views</span>
                                            </li>
                                            <li>
                                                <ul class="social">
                                                    <li>
                                                        <a href="<?php echo $fn->share_url('fb', $fn->meta); ?>" target="_blank">
                                                            <i class="fab fa-facebook" aria-hidden="true"></i>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="<?php echo $fn->share_url('tw', $fn->meta); ?>" target="_blank">
                                                            <i class="fab fa-twitter" aria-hidden="true"></i>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="<?php echo $fn->share_url('li', $fn->meta); ?>" target="_blank">
                                                            <i class="fab fa-linkedin" aria-hidden="true"></i>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="<?php echo $fn->share_url('pi', $fn->meta); ?>" target="_blank">
                                                            <i class="fab fa-pinterest" aria-hidden="true"></i>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="<?php echo $fn->share_url('em', $fn->meta); ?>" target="_blank">
                                                            <i class="fa fa-envelope" aria-hidden="true"></i>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);" class="btn btn-primary" data-ajaxify="true" data-url="ads" data-action="add_fav" data-app="<?php echo $fn->encrypt_post_data(['id' => $v['id']]); ?>">
                                                    <i class="fa fa-heart"></i> Save as favorite
                                                </a>
                                                <a href="javascript:void(0);" class="btn btn-primary" data-ajaxify="true" data-url="ads" data-type="report" data-app="<?php echo $fn->encrypt_post_data(['id' => $v['id'], 'title' => $fn->cms['ad_title']]); ?>" data-recid="modal">
                                                    <i class="fa fa-exclamation-triangle"></i> Report this ad
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="details">
                            <div class="row justify-content-center">
                                <div class="col-12">
                                    <div class="listing-custom labels">
                                        <div class="item labeled">
                                            <span><?php echo _('Category'); ?></span>
                                            <span><?php echo $fn->cms['ad_category']; ?></span>
                                        </div>
                                        <div class="item labeled">
                                            <span><?php echo _('Sub Category'); ?></span>
                                            <span><?php echo $fn->cms['ad_sub_category']; ?></span>
                                        </div>
                                        <?php
                                        if ($fn->varv('filters', $fn->cms)) {
                                            foreach ($fn->cms['filters'] as $k => $v) { ?>
                                                <div class="item labeled">
                                                    <span><?php echo $v['label']; ?></span>
                                                    <span><?php echo $v['value']; ?></span>
                                                </div>
                                                <?php
                                            }
                                        } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="detail-wrapper">
                                        <div class="detail-header text-center">
                                            <h3>Description</h3>
                                        </div>
                                        <div class="detail-content">
                                            <?php echo $fn->cms['ad_desc']; ?>
                                        </div>
                                    </div>
                                    <div class="detail-wrapper">
                                        <div class="card mb-5" style="max-height: 500px; overflow-y: auto;">
                                            <div class="card-header">
                                                <strong class="card-title">Comments</strong>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <?php echo include app_path . 'views' . ds . 'ad_comments.php'; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="detail-header text-center">
                                            <h3>Leave Comment</h3>
                                        </div>
                                        <div class="detail-content">
                                            <form class="form-validate" name="ad-comment-frm" id="ad-comment-frm" method="post" autocomplete="off" data-ajax="true" data-url="<?php echo str_replace(app_url, '', $fn->cms['url']); ?>" data-page="true" data-action="comment">
                                                <div class="row">
                                                    <div class="col-12 col-md-6">
                                                        <div class="form-group">
                                                            <input type="text" name="ad[name]" id="ad-comment-name" placeholder="Full Name" value="<?php echo $fn->post('ad', 'name'); ?>" required/>
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="email" name="ad[email]" id="ad-comment-email" placeholder="Email Address" value="<?php echo $fn->post('ad', 'email'); ?>" required/>
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="text" name="ad[phone]" id="ad-comment-phone" placeholder="Phone No." value="<?php echo $fn->post('ad', 'phone'); ?>" required/>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6 form-group captcha">
                                                                <img src="<?php echo $fn->permalink('captcha') . '?key=' . $fn->encrypt_post_data(array('for' => 'ad', 'color' => 1)) . '&' . ((float)rand() / (float)getrandmax()); ?>" alt="Captcha" class="captcha-code"/>
                                                                <a class="btn btn-primary refresh-captcha" tabindex="-1"><i class="fa fas fa-sync"></i></a>
                                                            </div>
                                                            <div class="col-md-6 form-group">
                                                                <input type="text" name="ad[captcha]" id="ad-comment-captcha" class="form-control" maxlength="6" placeholder="######" required/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                    <div class="col-12 col-md-6">
                                                        <div class="form-group">
                                                            <textarea class="form-control" name="ad[comment]" id="ad-comment" placeholder="Your Comment" required style="min-height: 190px;"><?php echo $fn->post('ad', 'comment'); ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-md-12 text-center">
                                                        <input type="hidden" name="id" value="<?php echo $fn->get('id'); ?>"/>
                                                        <input type="hidden" name="token" value="<?php echo $fn->post_token(); ?>"/>
                                                        <button type="submit" class="btn btn-primary float-none" value="Submit">Submit</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
<?php if ($fn->cms['related']) { ?>
    <section class="listing-products grid-3">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-header text-center">
                        <h3>Related Ads</h3>
                    </div>
                </div>
            </div>
            <div class="row">
                <?php foreach ($fn->cms['related'] as $k => $v) { ?>
                    <div class="col-6 col-md-6 col-lg-3 item">
                        <div class="item-wrapper">
                            <div class="image-wrapper">
                                <?php if ($v['promoted'] == 'Y') { ?>
                                    <div class="promoted">
                                        <span>Promoted</span>
                                    </div>
                                <?php } ?>
                                <a href="<?php echo $fn->permalink('ad-detail', $v); ?>" class="image">
                                    <img class="lazy" src="<?php echo $fn->permalink('assets/images/loader.svg'); ?>" data-src="<?php echo $fn->get_file($v['ad_image'], 0, 0, 400); ?>" alt="<?php echo $v['ad_title']; ?>"/>
                                </a>
                                <div class="quick-actions">
                                    <a href="<?php echo $fn->permalink('ad-detail', $v); ?>" class="btn btn-primary btn-circle">
                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                    </a>
                                    <a href="<?php echo $fn->permalink('ad-detail', $v); ?>" class="btn btn-primary btn-circle add-to-fav" data-ajaxify="true" data-url="ads" data-action="add_fav" data-app="<?php echo $fn->encrypt_post_data(['id' => $v['id']]); ?>">
                                        <i class="fa fa-heart" aria-hidden="true"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="info-wrapper">
                                <div class="info">
                                    <div class="category"><?php echo $v['ad_cat']; ?></div>
                                    <a href="<?php echo $fn->permalink('ad-detail', $v); ?>" class="name"><?php echo $v['ad_title']; ?></a>
                                    <span class="price"><?php echo $fn->show_price($v['ad_price']); ?></span>
                                    <span class="excerpt"><?php echo $fn->show_string($v['ad_desc'], 100, false); ?></span>
                                    <span class="location">
                                        <i class="fa fa-map-marker-alt" aria-hidden="true"></i> <?php echo $v['location']; ?>
                                    </span>
                                    <span class="posted">
                                        <i class="far fa-clock" aria-hidden="true"></i> Posted <?php echo $fn->time_ago($v['add_date']); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>
    <?php
}
include app_path . 'inc' . ds . 'footer.php';
include app_path . 'inc' . ds . 'foot.php';
?>