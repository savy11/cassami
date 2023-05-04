<?php
 ob_start();
 $str = '';
?>
    <div class="widget blog-sidebar mb18">
        <div class="sidebar-widget">
            <div class="widget-search">
                <form name="search-frm" id="search-frm" method="get"
                      action="<?php echo $fn->permalink('blog'); ?>"
                      autocomplete="off">
                    <div class="input-group">
                        <input type="text" class="form-control border-form-control" name="q"
                               placeholder="Search Blog" value="<?php echo $fn->get('q'); ?>"/>
                        <span class="input-group-btn">
                              <button class="btn btn-theme-round" type="submit">
                                  <i class="icofont icofont-search-alt-2"></i>
                              </button>
                              </span>
                    </div>
                </form>
            </div>
        </div>
     <?php if ($fn->list['cats']) { ?>
         <div class="sidebar-widget">
             <h5>Categories</h5>
             <ul class="widget-tag">
              <?php foreach ($fn->list['cats'] as $k => $v) { ?>
                  <li>
                      <a href="<?php echo $fn->permalink('blog-cat', $v); ?>">
                          <i class="icofont icofont-square-right"></i>
                       <?php echo $v['category_name']; ?></a>
                  </li>
              <?php } ?>
             </ul>
         </div>
     <?php }
      if ($fn->list['archives']) { ?>
          <div class="sidebar-widget">
              <h5>Archives</h5>
              <ul class="widget-tag">
               <?php foreach ($fn->list['archives'] as $k => $v) { ?>
                   <li>
                       <a href="<?php echo $fn->permalink('blog-archive', $v); ?>">
                           <i class="icofont icofont-square-right"></i>
                        <?php echo $fn->dt_format($v['blog_date'], 'F Y'); ?></a>
                   </li>
               <?php } ?>
              </ul>
          </div>
      <?php }
      if ($fn->list['recent']) {
       ?>
          <div class="sidebar-widget">
              <h5>Top Posts</h5>
              <ul class="widget-post">
               <?php foreach ($fn->list['recent'] as $k => $v) { ?>
                   <li>
                       <a href="<?php echo $fn->permalink('blog-detail', $v); ?>"
                          class="widget-post-media">
                           <img src="<?php echo $fn->get_file($v['blog_image'], 0, 0, 215); ?>"
                                alt="<?php echo $v['blog_title']; ?>"/>
                       </a>
                       <div class="widget-post-info">
                           <h6>
                               <a href="<?php echo $fn->permalink('blog-detail', $v); ?>"><?php echo $v['blog_title']; ?></a>
                           </h6>
                           <span class="entry-meta"><i class="icofont icofont-ui-user"></i> by
                                               Administrator
                                               &nbsp; <i
                                       class="icofont icofont-calendar"></i> <?php echo $fn->dt_format($v['blog_date'], 'F d, Y'); ?></span>
                       </div>
                   </li>
               <?php } ?>
              </ul>
          </div>
      <?php }
      if ($fn->list['tags']) {
       ?>
          <div class="sidebar-widget">
              <h5>Popular Tags</h5>
              <ul class="widget-tag-btn">
               <?php foreach ($fn->list['tags'] as $k => $v) { ?>
                   <li>
                       <a href="<?php echo $fn->permalink('blog-tag', $v); ?>">
                           <i class="icofont icofont-square-right"></i>
                        <?php echo ucwords($v); ?></a>
                   </li>
               <?php } ?>
              </ul>
          </div>
      <?php } ?>
    </div>
<?php
 $str .= ob_get_clean();
 return $str;
?>