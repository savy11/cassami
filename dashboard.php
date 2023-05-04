<?php
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'autoload.php';
$fn = new controllers\account;
$fn->cms_page('dashboard');
$fn->my_ads();
include app_path . 'inc' . ds . 'head.php';
include app_path . 'inc' . ds . 'header.php';
?>
    <section class="bkg-light medium boxed">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="boxed-container my-account pb-3">
                        <div class="account-listing">
                            <ul class="table-sheet list-my-listings">
                                <li>
                                    <ul>
                                        <li class="image">Image</li>
                                        <li class="name">Name</li>
                                        <li class="data">Date</li>
                                        <li class="category">Category</li>
                                        <li class="status">Approved</li>
                                        <li class="status">Status</li>
                                        <li class="actions">Actions</li>
                                    </ul>
                                </li>
                                <?php if ($fn->data) {
                                    foreach ($fn->data as $k => $v) {
                                        ?>
                                        <li>
                                            <ul>
                                                <li class="image">
                                                    <span class="image-holder"><img class="lazy" src="<?php echo $fn->permalink('assets/images/loader.svg'); ?>" data-src="<?php echo $fn->get_file($v['ad_image'], 0, 0, 200); ?>" alt="<?php echo $v['ad_title']; ?>"></span>
                                                </li>
                                                <li class="name">
                                                    <span class="name-holder"><a href="<?php echo $fn->permalink('account/my-ads?action=edit&id=' . $v['id']) ?>"><?php echo $v['ad_title']; ?></a></span>
                                                    <div class="d-lg-none">
                                                        <span class="data-holder"><i class="far fa-clock"></i> Posted <?php echo $fn->time_ago($v['add_date']); ?></span>
                                                        <span class="category-holder"><?php echo $v['category_name']; ?></span>
                                                        <span class="status-holder<?php echo $v['approved'] == 'Y' ? ' active' : ''; ?>"><i class="fas fa-calendar-check"></i> <?php echo $v['approved'] == 'Y' ? 'Approved' : 'Unapproved'; ?></span>
                                                    </div>
                                                </li>
                                                <li class="data"><span class="data-holder"><i class="far fa-clock"></i> Posted <?php echo $fn->time_ago($v['add_date']); ?></span></li>
                                                <li class="category"><span class="category-holder"><?php echo $v['category_name']; ?></span></li>
                                                <li class="status"><span class="status-holder<?php echo $v['approved'] == 'Y' ? ' active' : ''; ?>"><i class="fas fa-calendar-check"></i> <?php echo $v['approved'] == 'Y' ? 'Approved' : 'Unapproved'; ?></span></li>
                                                <li class="status"><span class="status-holder<?php echo $v['ad_sold'] == 'N' ? ' active' : ''; ?>"><i class="fas fa-check"></i> <?php echo $v['ad_sold'] == 'Y' ? 'Sold' : 'Available'; ?></span></li>
                                                <li class="actions">
                                                    <ul>
                                                        <li><a href="<?php echo $fn->permalink('account/my-ads?action=edit&id=' . $v['id']) ?>" class="btn btn-primary"><i class="far fa-edit"></i> Edit</a></li>
                                                        <li><a href="<?php echo $fn->permalink('account/my-ads?action=delete&id=' . $v['id']) ?>" class="btn btn-default"><i class="far fa-trash-alt"></i> Delete</a></li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </li>
                                        <?php
                                    }
                                } ?>
                            </ul>
                            <div class="row">
                                <div class="col-12">
                                    <ul class="pagination text-center">
                                        <?php echo $fn->pagination->display_all(); ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <strong class="card-title m-0 p-0">Referral Link</strong>
                                </div>
                                <div class="card-body">
                                    <div class="input-group">
                                        <input type="url" class="form-control" id="ref_link" value="<?php echo $fn->permalink('referral?ref=CS' . $fn->user['id']); ?>"/>
                                        <button type="button" class="copy btn btn-info btn-sm" data-copy-elem="#ref_link">Copy Link</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php
include app_path . 'inc' . ds . 'footer.php';
include app_path . 'inc' . ds . 'foot.php';
?>