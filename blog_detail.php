<?php
 require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'autoload.php';
 $fn = new controllers\blog;
 $fn->blog();
 include_once app_path . 'inc' . ds . 'head.php';
 include_once app_path . 'inc' . ds . 'header.php';
?>
    <section class="bkg-light medium">
        <div class="container">
            <div class="row">
                <div class="col-12">

                    <div class="boxed-container">

                        <div class="blog">
                            <div class="block">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="section-header text-center no-padding">
                                            <h3>Blog Article</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="article">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="hero-image">
                                                        <img class="lazy" src="<?php echo $fn->permalink('resources/files/blank.gif'); ?>" data-src="<?php echo $fn->get_file($fn->cms['blog_image']); ?>" alt="<?php echo $fn->cms['blog_title']; ?>"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="section-header text-center">
                                                        <h4><?php echo $fn->cms['blog_title']; ?></h4>
                                                        <div class="caption-header"><i class="far fa-clock" aria-hidden="true"></i> Posted on <?php echo $fn->date_format($fn->cms['blog_date'], 'd.M.Y'); ?></div>

                                                        <div class="caption-header ml-2"><i class="far fa-comment" aria-hidden="true"></i> <?php echo $fn->cms['total_comments']; ?> Comments</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <?php echo $fn->cms['blog_desc']; ?>
                                                </div>
                                            </div>
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
<?php
 include_once app_path . 'inc' . ds . 'footer.php';
 include_once app_path . 'inc' . ds . 'foot.php';
?>