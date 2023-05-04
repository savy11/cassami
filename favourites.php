<?php
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'autoload.php';
$fn = new controllers\account;
if ($fn->get('for') != 'account') {
    $fn->not_found();
}
$fn->cms_page('favourites');
if ($fn->get('action') == 'remove') {
    try {
        $fn->remove_fav();
        $fn->session_msg('Your ad has been removed successfully!', 'success', $fn->cms['page_title']);
        $fn->redirecting('account/favourites');
    } catch (Exception $ex) {
        $fn->session_msg($fn->replace_sql($ex->getMessage()), 'error', $fn->cms['page_title']);
    }
}
$fn->favourites();
include app_path . 'inc' . ds . 'head.php';
include app_path . 'inc' . ds . 'header.php';
?>
    <section class="bkg-light medium boxed">
        <div class="container">
            <div class="row">
                <div class="col-12">

                    <div class="boxed-container my-account">

                        <?php include_once app_path . 'inc' . ds . 'acc_menus.php'; ?>
                        <div class="account-listing">
                            <ul class="table-sheet list-my-listings">
                                <li>
                                    <ul>
                                        <li class="image">Image</li>
                                        <li class="name">Name</li>
                                        <li class="data">Added Date</li>
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
                                                    <span class="name-holder"><a href="<?php echo $fn->permalink('ad-detail', ['ad_title' => $v['ad_title'], 'id' => $v['id']]); ?>"><?php echo $v['ad_title']; ?></a></span>
                                                </li>
                                                <li class="data"><span class="data-holder"><i class="far fa-clock"></i> <?php echo $fn->dt_format($v['add_date'], 'M d,Y h:i A'); ?></span></li>
                                                <li class="actions">
                                                    <ul>
                                                        <li>
                                                            <a href="<?php echo $fn->permalink('account/favourites?action=remove&id=' . $v['fav_id']) ?>" class="btn btn-default"><i class="far fa-trash-alt"></i> Remove</a>
                                                        </li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </li>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <li>
                                        <div class="alert alert-danger">Oops, No favourite ads found here right now. Please save your favourite ads to showcase here.</div>
                                    </li>
                                    <?php
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
                    </div>

                </div>
            </div>
        </div>
    </section>
<?php
include app_path . 'inc' . ds . 'footer.php';
include app_path . 'inc' . ds . 'foot.php';
?>