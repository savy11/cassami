<?php
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'autoload.php';
$fn = new controllers\account;
if ($fn->get('for') != 'account') {
    $fn->not_found();
}
$fn->cms_page('my-ads');
if ($fn->get('action') == 'delete') {
    try {
        $fn->delete_ad();
        $fn->session_msg('Your ad has been deleted successfully!', 'success', $fn->cms['page_title']);
        $fn->redirecting('account/my-ads');
    } catch (Exception $ex) {
        $fn->session_msg($fn->replace_sql($ex->getMessage()), 'error', $fn->cms['page_title']);
    }
}
if ($fn->post('btn_update') != '') {
    try {
        $fn->update_ad();
        $fn->session_msg('Your changes has been saved successfully!', 'success', $fn->cms['page_title']);
        $fn->redirecting('account/my-ads');
    } catch (Exception $ex) {
        $fn->session_msg($fn->replace_sql($ex->getMessage()), 'error', $fn->cms['page_title']);
    }
}
$fn->my_ads();
ob_start();
?>
    <link rel="stylesheet" type="text/css" href="<?php echo $fn->permalink('resources/vendor/uploader/uploader.css'); ?>"/>
    <style type="text/css">
        .files li .attachment {
            border: 1px solid rgba(0, 0, 0, 0.1);
        }

        .files li .button-link.remove-item {
            background-color: #01dcb1;
            box-shadow: none;
            border-radius: 50%;
            width: 20px;
            height: 20px;
        }

        .files li .button-link.remove-item:hover {
            background-color: #05c39e;
            box-shadow: 0 0 0 1px #fff, 0 0 0 2px #05c39e;
        }

        .files.check-it li.selected div.checked:before {
            content: '\2713';
            font-family: 'Montserrat', sans-serif;
        }

        .files li .button-link.remove-item .icon {
            width: 100%;
            height: 100%;
            display: block;
            position: relative;
            text-align: center;
            line-height: 20px;
            top: 0;
            font-size: 12px;
        }

        .files li .button-link.remove-item .icon:before {
            content: '\f1f8';
            font-family: 'Font Awesome 5 Free';
            font-weight: 600;
        }

        .qq-upload-drop-area {
            position: relative;
            display: block;
            border: 1px dashed #ddd;
            width: 100%;
            height: 100%;
            text-align: center;
            font-size: 20px;
            font-weight: 600;
            box-shadow: inset 0 0 25px rgba(0, 0, 0, .1);
        }

        .qq-upload-drop-area .drop-border {
            position: relative;
            display: block;
            padding: 50px;
            border: 5px double #ddd;
            margin: 10px;
            opacity: .5;
            transition: all ease .3s;
            -webkit-transition: all ease .3s;
            -moz-transition: all ease .3s;
            -ie-transition: all ease .3s;
            -ms-transition: all ease .3s;
            -o-transition: all ease .3s;
            animation: infinite 3s paused;
            -webkit-animation: infinite 3s paused;
            -moz-animation: infinite 3s paused;
            -ie-animation: infinite 3s paused;
            -ms-animation: infinite 3s paused;
            -o-animation: infinite 3s paused;
            animation-name: active;
            -webkit-animation-name: active;
            -moz-animation-name: active;
            -ie-animation-name: active;
            -o-animation-name: active;
        }

        .qq-upload-drop-area.qq-upload-drop-area-active .drop-border {
            opacity: 1;
            animation-play-state: running;
            -webkit-animation-play-state: running;
            -moz-animation-play-state: running;
            -ie-animation-play-state: running;
            -o-animation-play-state: running;
            transition: all ease .3s;
            -webkit-transition: all ease .3s;
            -moz-transition: all ease .3s;
            -ie-transition: all ease .3s;
            -o-transition: all ease .3s;

        }

        .qq-upload-drop-area.qq-upload-drop-area-active .drop-border:after {
            content: "Drop Here to upload";
        }

        .qq-upload-drop-area.qq-upload-drop-area-active .drop-border span {
            display: none;
        }

        @keyframes active {
            0% {
                opacity: 0.5;
            }
            50% {
                opacity: 1;
            }
            100% {
                opacity: 0.5;
            }
        }

        .ekko-lightbox {
            z-index: 999999;
        }

        .ekko-lightbox + .modal-backdrop.show {
            z-index: 99999;
        }
    </style>
<?php
$fn->style = ob_get_clean();
include app_path . 'inc' . ds . 'head.php';
include app_path . 'inc' . ds . 'header.php';
?>
    <section class="bkg-light medium boxed">
        <div class="container">
            <div class="row">
                <div class="col-12">

                    <div class="boxed-container my-account">

                        <?php include_once app_path . 'inc' . ds . 'acc_menus.php'; ?>

                        <?php if ($fn->get('action') == 'edit' && $fn->get('id')) {
                            $fn->my_ad();
                            ?>
                            <form class="form-validate" name="ad-frm" id="ad-frm" method="post" autocomplete="off">
                                <div class="block">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="section-header text-center">
                                                <h3>Edit ad</h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row justify-content-center">
                                        <div class="col-12 col-xl-11">
                                            <div class="row">
                                                <div class="col-12 col-md-8">
                                                    <div class="form-group">
                                                        <label class="input-label req">Ad Title</label>
                                                        <input type="text" class="form-control" name="ad_title" id="ad_title" value="<?php echo $fn->post('ad_title'); ?>" placeholder="Ad Title" required/>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-2">
                                                    <div class="form-group">
                                                        <label class="input-label req">Ad Price</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text"><?php echo $fn->currency; ?></div>
                                                            </div>
                                                            <input type="text" class="form-control numbers-only text-right" name="ad_price" id="ad_price" value="<?php echo $fn->post('ad_price'); ?>" placeholder="0.00" required/>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-2">
                                                    <div class="form-group">
                                                        <label class="input-label req">Ad Sold</label>
                                                        <select name="ad_sold" id="ad_sold" class="form-control" placeholder="Yes/No" required>
                                                            <?php echo $fn->show_list($fn->yes_no, $fn->post('ad_sold'), false); ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label class="input-label req">Ad Description</label>
                                                        <textarea id="ad_desc" name="ad_desc" class="form-control tinymce" placeholder="Description" required><?php echo $fn->post('ad_desc'); ?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label class="input-label req">Ad Keywords</label>
                                                        <textarea class="form-control tagsinput" name="ad_keywords" id="ad_keywords" data-default-text="Add a Keyword" required><?php echo $fn->post('ad_keywords'); ?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="section-header text-center">
                                                        <h3>Contact details</h3>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="row">
                                                        <div class="col-12 col-md-6">
                                                            <div class="form-group">
                                                                <label class="input-label req">Phone No.</label>
                                                                <input type="text" class="form-control" name="ad_no" id="ad_no" value="<?php echo $fn->post('ad_no'); ?>" placeholder="Phone" required/>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 col-md-6">
                                                            <div class="form-group">
                                                                <label class="input-label req">Email Address</label>
                                                                <input type="email" class="form-control" name="ad_email" id="ad_email" value="<?php echo $fn->post('ad_email'); ?>" placeholder="Email" required/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-12 col-md-6">
                                                            <div class="form-group">
                                                                <label class="input-label req">State</label>
                                                                <select id="ad_state_id" name="ad_state_id" class="form-control" data-placeholder="State" data-ajaxify="true" data-url="ads" data-type="cities" data-event="change" data-recid="ad-city-id" required>
                                                                    <?php echo $fn->show_list($fn->get_states(), $fn->post('ad_state_id'), true); ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 col-md-6">
                                                            <div class="form-group">
                                                                <label class="input-label req">City</label>
                                                                <select id="ad-city-id" name="ad_city_id" class="form-control" data-placeholder="City" required<?php echo $fn->post('ad_state_id') ? '' : ' disabled'; ?>>
                                                                    <?php
                                                                    $cities = [];
                                                                    if ($fn->post('ad_state_id')) {
                                                                        $cities = $fn->get_cities($fn->post('ad_state_id'));
                                                                    }
                                                                    echo $fn->show_list($cities, $fn->post('ad_city_id'), true); ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label class="input-label req">Ad Category</label>
                                                        <select class="form-control" name="ad_category_id" id="ad_category_id" data-placeholder="Category" data-ajaxify="true" data-url="ads" data-type="category" data-event="change" data-recid="ad-sub-category" required>
                                                            <?php echo $fn->show_list($fn->get_ads_cat(), $fn->post('ad_category_id'), true); ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label class="input-label req">Ad Sub Category</label>
                                                        <select class="form-control" name="ad_sub_category_id" id="ad-sub-category" data-placeholder="Sub Category" data-ajaxify="true" data-url="ads" data-type="filters" data-event="change" data-recid="filters"
                                                                required<?php echo $fn->post('ad_category_id') ? '' : ' disabled'; ?>>
                                                            <?php
                                                            $sub_cat = [];
                                                            if ($fn->post('ad_category_id')) {
                                                                $sub_cat = $fn->get_ads_subcat($fn->post('ad_category_id'));
                                                            }
                                                            echo $fn->show_list($sub_cat, $fn->post('ad_sub_category_id'), true); ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="filters">
                                                <?php
                                                if ($fn->post('ad_sub_category_id') != '') {
                                                    $filters = $fn->get_filters($fn->post('ad_sub_category_id'));
                                                    echo include app_path . 'views' . ds . 'filters.php';
                                                }
                                                ?>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="section-header text-center">
                                                <h3>Photo gallery</h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row justify-content-center">
                                        <div class="col-12 col-xl-11">
                                            <div class="row">
                                                <div class="form-group col-12">
                                                    <div>
                                                        <div class="uploader" data-list-id="files" data-type="ads" data-upload-button-text="Upload Images">Upload Images</div>
                                                        <ul class="files check-it clearfix" id="files" data-columns="6" data-checked-limit="1">
                                                            <?php echo $fn->galleries('ads', $fn->post('ads'), $fn->post('ad_image')); ?>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row justify-content-center">
                                                <input type="hidden" name="ad_user_id" value="<?php echo $fn->varv('id', $fn->user); ?>"/>
                                                <input type="hidden" name="token" value="<?php echo $fn->post_token(); ?>"/>
                                                <div class="col-6 col-lg-2">
                                                    <button class="btn btn-primary full-width" type="submit" name="btn_update" value="post">Save Changes</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <?php
                        } else {
                            ?>
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
                        <?php } ?>
                    </div>

                </div>
            </div>
        </div>
    </section>
<?php
include app_path . 'inc' . ds . 'footer.php';
include app_path . 'inc' . ds . 'foot.php';
?>