<?php
 require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'autoload.php';
 $fn = new controllers\blog;
 include_once app_path . 'inc' . ds . 'head.php';
 include_once app_path . 'inc' . ds . 'header.php';
 $fn->blogs();
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
                                            <h3><?php echo $fn->varv('page_heading', $fn->cms); ?></h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                 <?php if ($fn->data) {
                                  foreach ($fn->data as $k => $v) {
                                   ?>
                                      <div class="col-12 col-md-6 col-lg-3 item">
                                          <a href="<?php echo $fn->permalink('blog-detail', $v); ?>" class="image">
                                              <img class="lazy" src="<?php echo $fn->permalink('resources/files/blank.gif'); ?>" data-src="<?php echo $fn->get_file($v['blog_image']); ?>" alt="<?php echo $v['blog_title']; ?>"/>
                                          </a>
                                          <div class="info">
                                              <a href="<?php echo $fn->permalink('blog-detail', $v); ?>" class="name"><?php echo $v['blog_title']; ?></a>
                                              <span class="excerpt"><?php echo $fn->show_string($v['blog_desc'], 100, false); ?></span>
                                              <span class="posted"><i class="far fa-clock" aria-hidden="true"></i> Posted <?php echo $fn->time_ago($v['blog_date']); ?></span>
                                          </div>
                                      </div>
                                   <?php
                                  }
                                 } else {
                                  ?>
                                     <div class="col-12 mt-5">
                                         <div class="alert alert-danger">OOPS, No blogs found right now. Please try after some days.</div>
                                     </div>
                                  <?php
                                 } ?>
                                </div>
                             <?php if ($fn->data) { ?>
                                 <div class="row">
                                     <div class="col-12">
                                         <ul class="pagination text-center">
                                          <?php echo $fn->pagination->display_all(); ?>
                                         </ul>
                                     </div>
                                 </div>
                             <?php } ?>
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