<?php
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "autoload.php";
$fn = new controllers\index;
$fn->get_data();
include app_path . 'inc' . ds . 'head.php';
include app_path . 'inc' . ds . 'header.php';
if ($fn->list['sliders']) {
    ?>
    <section class="home-slider">
        <div class="slider-single control-nav">
            <?php foreach ($fn->list['sliders'] as $k => $v) { ?>
                <div class="slider-item">
                    <div class="item" style="background-image: url('<?php echo $fn->get_file($v['slider_image']) ?>');">
                        <div class="content-wrapper">
                            <div class="info">
                                <div class="info-header text-center">
                                    <h3 class="heading"><?php echo $v['slider_title']; ?></h3>
                                    <p class="caption-header"><?php echo $v['slider_desc']; ?></p>
                                </div>
                                <a href="<?php echo $fn->permalink('post-ad'); ?>" class="btn btn-primary"><?php echo $v['slider_caption'] ? $v['slider_caption'] : 'Post Ad'; ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </section>
<?php } ?>
    <div class="home-categories no-padding-top">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="categories_wrapper">
                        <div class="category-search">
                            <form class="form-validate" name="search-frm" id="search-frm" method="get" autocomplete="off" action="<?php echo $fn->permalink('ads'); ?>">
                                <div class="row">
                                    <div class="col-12 col-lg-10">
                                        <div class="row">
                                            <div class="col-12 col-md-4">
                                                <div class="form-group">
                                                    <i class="fa fa-search" aria-hidden="true"></i>
                                                    <input type="text" class="form-control" placeholder="Search for anything" name="q" id="q" value="<?php echo $fn->get('q'); ?>"/>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-4">
                                                <div class="form-group">
                                                    <i class="fa fa-map-marker-alt" aria-hidden="true"></i>
                                                    <input type="text" class="form-control" placeholder="Location" name="location" id="location" value="<?php echo $fn->get('location'); ?>"/>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-4">
                                                <div class="form-group icon">
                                                    <i class="fa fa-bars" aria-hidden="true"></i>
                                                    <select name="category" id="category" class="form-control" data-placeholder="Category">
                                                        <?php echo $fn->show_list($fn->get_ads_cat(), $fn->get('category'), true); ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-2">
                                        <div class="form-group">
                                            <button class="btn btn-primary" type="submit" name="action" value="search">Search</button>
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
<?php if ($fn->list['ads']) {
    $promoted[] = current(array_filter($fn->list['ads'], function ($item) {
        return isset($item['promoted']) && 'Y' == $item['promoted'];
    }));
    if (!empty($promoted)) {
        ?>
        <section class="listing-products grid-4 bkg-light">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="section-header text-center">
                            <h3>Promoted Ads</h3>
                        </div>
                    </div>
                </div>
                <div class="row row-list">
                    <?php
                    $i = 0;
                    foreach ($fn->list['ads'] as $k => $v) {
                        if ($v['promoted'] == 'Y') {
                            if ($i >= 8) {
                                break;
                            }
                            ?>
                            <div class="col-6 col-md-6 col-lg-3 item">
                                <div class="item-wrapper<?php echo $v['ad_sold'] == 'Y' ? ' sold' : ''; ?>">
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
                                            <a href="<?php echo $fn->permalink('ad-detail', $v); ?>" class="btn btn-primary btn-circle"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                            <a href="<?php echo $fn->permalink('ad-detail', $v); ?>" class="btn btn-primary btn-circle add-to-fav" data-ajaxify="true" data-url="ads" data-action="add_fav" data-app="<?php echo $fn->encrypt_post_data(['id' => $v['id']]); ?>">
                                                <i class="fa fa-heart" aria-hidden="true"></i></a>
                                        </div>
                                    </div>
                                    <div class="info-wrapper">
                                        <div class="info">
                                            <div class="category"><?php echo $v['ad_cat']; ?></div>
                                            <a href="<?php echo $fn->permalink('ad-detail', $v); ?>" class="name"><?php echo $v['ad_title']; ?></a>
                                            <span class="price"><?php echo $fn->show_price($v['ad_price']); ?></span>
                                            <span class="excerpt"><?php echo $fn->show_string($v['ad_desc'], 100, false); ?></span>
                                            <span class="location"><i class="fa fa-map-marker-alt" aria-hidden="true"></i> <?php echo $v['location']; ?></span>
                                            <span class="posted"><i class="far fa-clock" aria-hidden="true"></i> Posted <?php echo $fn->time_ago($v['add_date']); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                            $i++;
                        }
                    } ?>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="view-more text-center">
                            <a href="<?php echo $fn->permalink('ads?show=promoted'); ?>" class="btn btn-primary">View All</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php } ?>
    <section class="listing-products grid-3">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-header text-center">
                        <h3>New Ads</h3>
                    </div>
                </div>
            </div>
            <div class="row">
                <?php foreach ($fn->list['ads'] as $k => $v) { ?>
                    <div class="col-6 col-md-6 col-lg-3 item">
                        <div class="item-wrapper<?php echo $v['ad_sold'] == 'Y' ? ' sold' : ''; ?>">
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
                                    <a href="<?php echo $fn->permalink('ad-detail', $v); ?>" class="btn btn-primary btn-circle"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                    <a href="<?php echo $fn->permalink('ad-detail', $v); ?>" class="btn btn-primary btn-circle add-to-fav" data-ajaxify="true" data-url="ads" data-action="add_fav" data-app="<?php echo $fn->encrypt_post_data(['id' => $v['id']]); ?>">
                                        <i class="fa fa-heart" aria-hidden="true"></i></a>
                                </div>
                            </div>
                            <div class="info-wrapper">
                                <div class="info">
                                    <div class="category"><?php echo $v['ad_cat']; ?></div>
                                    <a href="<?php echo $fn->permalink('ad-detail', $v); ?>" class="name"><?php echo $v['ad_title']; ?></a>
                                    <span class="price"><?php echo $fn->show_price($v['ad_price']); ?></span>
                                    <span class="excerpt"><?php echo $fn->show_string($v['ad_desc'], 100, false); ?></span>
                                    <span class="location"><i class="fa fa-map-marker-alt" aria-hidden="true"></i> <?php echo $v['location']; ?></span>
                                    <span class="posted"><i class="far fa-clock" aria-hidden="true"></i> Posted <?php echo $fn->time_ago($v['add_date']); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="view-more text-center">
                        <a href="<?php echo $fn->permalink('ads'); ?>" class="btn btn-primary">View All</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php }
if ($fn->list['blogs']) { ?>
    <section class="featured-articles grid-4 bkg-light">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-header text-center">
                        <h3>Recent Blogs</h3>
                    </div>
                </div>
            </div>
            <div class="row">
                <?php foreach ($fn->list['blogs'] as $k => $v) { ?>
                    <div class="col-6 col-md-6 col-lg-3 item">
                        <a href="<?php echo $fn->permalink('blog-detail', ['page_url' => $v['page_url'], 'id' => $v['id']]); ?>" class="image">
                            <img class="lazy" src="<?php echo $fn->permalink('assets/images/loader.svg'); ?>" data-src="<?php echo $fn->get_file($v['blog_image']); ?>" alt="<?php echo $v['blog_title']; ?>"/>
                        </a>
                        <div class="info">
                            <a href="<?php echo $fn->permalink('blog-detail', $v); ?>" class="name"><?php echo $v['blog_title']; ?></a>
                            <span class="excerpt"><?php echo $fn->show_string($v['blog_desc'], 100, false); ?></span>
                            <span class="posted"><i class="far fa-clock" aria-hidden="true"></i> Posted <?php echo $fn->time_ago($v['blog_date']); ?></span>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="view-more text-center">
                        <a href="<?php echo $fn->permalink('blog'); ?>" class="btn btn-primary">View All</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php
}
include app_path . 'inc' . ds . 'footer.php';
include app_path . 'inc' . ds . 'foot.php';
?>