<?php
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "autoload.php";
$fn = new controllers\controller;
$fn->cms_page($fn->get('page_url'));
$faqs = $fn->faqs();
include app_path . 'inc' . ds . 'head.php';
include app_path . 'inc' . ds . 'header.php';
?>
    <section class="bkg-light medium">
        <div class="container">
            <div class="row">
                <div class="col-12">

                    <div class="boxed-container">

                        <div class="block">
                            <div class="row">
                                <div class="col-12">
                                    <div class="section-header text-center">
                                        <h3><?php echo $fn->cms['page_heading']; ?></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <?php if ($faqs) { ?>
                                        <div id="accordion">
                                            <?php foreach ($faqs as $k => $v) { ?>
                                                <div class="card mb-2">
                                                    <div class="card-header" id="hfaq-<?php echo $v['id']; ?>">
                                                        <h5 class="pt-0" data-toggle="collapse" data-target="#faq-<?php echo $v['id']; ?>" aria-expanded="true" aria-controls="faq-<?php echo $v['id']; ?>"><?php echo $v['question']; ?></h5>
                                                    </div>

                                                    <div id="faq-<?php echo $v['id']; ?>" class="collapse<?php echo $k == 0 ? ' show' : ''; ?>" aria-labelledby="hfaq-<?php echo $v['id']; ?>" data-parent="#accordion">
                                                        <div class="card-body"><?php echo $v['answer']; ?></div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    <?php } else { ?>
                                        <div class="alert alert-danger">
                                            OOPS! No faqs found right now. Please try after sometime or later.
                                        </div>
                                    <?php } ?>
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