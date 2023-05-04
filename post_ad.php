<?php
 require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'autoload.php';
 $fn = new controllers\controller;
 $fn->require_login();
 $fn->add_step();
 $fn->cms_page('post-ad');
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
    <section class="bkg-light medium">
        <div class="container">
            <div class="row">
                <div class="col-12">

                    <div class="boxed-container post">
                        <div class="post-listing" id="result">
                            <form class="form-validate" name="ad-frm" id="ad-frm" method="post" autocomplete="off" enctype="multipart/form-data" data-ajax="true" data-url="ads" data-recid="modal">
                                <div class="block">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="section-header text-center">
                                                <h3>Post your ad</h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row justify-content-center">
                                        <div class="col-12 col-xl-11">
                                            <div class="row">
                                                <div class="col-12 col-md-9">
                                                    <div class="form-group">
                                                        <label class="input-label req">Ad Title</label>
                                                        <input type="text" class="form-control" name="ad_title" id="ad-title" value="<?php echo $fn->post('ad_title'); ?>" placeholder="Ad Title" required/>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-3">
                                                    <div class="form-group">
                                                        <label class="input-label req">Ad Price</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text"><?php echo $fn->currency; ?></div>
                                                            </div>
                                                            <input type="text" class="form-control numbers-only text-right" name="ad_price" id="ad-price" value="<?php echo $fn->post('ad_price'); ?>" placeholder="0.00" required/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label class="input-label req">Ad Description</label>
                                                        <textarea id="ad-desc" name="ad_desc" class="form-control tinymce" placeholder="Description" required><?php echo $fn->post('ad_desc'); ?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label class="input-label req">Ad Keywords</label>
                                                        <textarea class="form-control tagsinput" name="ad_keywords" id="ad-keywords" data-default-text="Add a Keyword" required><?php echo $fn->post('ad_keywords'); ?></textarea>
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
                                                                <input type="text" class="form-control" name="ad_no" id="ad-no" value="<?php echo $fn->post('ad_no'); ?>" placeholder="Phone" required/>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 col-md-6">
                                                            <div class="form-group">
                                                                <label class="input-label req">Email Address</label>
                                                                <input type="email" class="form-control" name="ad_email" id="ad-email" value="<?php echo $fn->post('ad_email'); ?>" placeholder="Email" required/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-12 col-md-6">
                                                            <div class="form-group">
                                                                <label class="input-label req">State</label>
                                                                <select id="ad-state-id" name="ad_state_id" class="form-control" data-placeholder="State" data-ajaxify="true" data-url="ads" data-type="cities" data-event="change" data-recid="ad-city-id" required>
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
                                                        <select class="form-control" name="ad_category_id" id="ad-category" data-placeholder="Category" data-ajaxify="true" data-url="ads" data-type="category" data-event="change" data-recid="ad-sub-category" required>
                                                         <?php echo $fn->show_list($fn->get_ads_cat(), $fn->post('ad_category_id'), true); ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label class="input-label req">Ad Sub Category</label>
                                                        <select class="form-control" name="ad_sub_category_id" id="ad-sub-category" data-placeholder="Sub Category" data-ajaxify="true" data-url="ads" data-type="filters" data-event="change" data-recid="filters" required<?php echo $fn->post('ad_category_id') ? '' : ' disabled'; ?>>
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
                                                    <button class="btn btn-default full-width" type="submit" name="action" value="preview">Preview</button>
                                                </div>
                                                <div class="col-6 col-lg-2">
                                                    <button class="btn btn-primary full-width" type="submit" name="action" value="post">Submit</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
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