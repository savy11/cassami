<?php
 ob_start();
 $str = '';
?>
    <div class="row comment" id="comment-<?php echo $v['id']; ?>">
        <div class="col-sm-12">
            <div class="review-block-title">
                <img alt="<?php echo $v['name']; ?>" src="<?php echo $fn->permalink('assets/images/no-user.jpg'); ?>"
                     class="review-block-image"/>
                <div class="review-block-name">
                    <i class="icofont icofont-ui-user"></i> <?php echo $v['name']; ?>
                    <a href="javascript:;" data-id="<?php echo $v['id'] ?>"
                       data-parent="<?php echo $v['parent_id'] == 0 ? $v['id'] : $v['parent_id']; ?>"
                       class="btn-link pull-right btn-reply">Reply</a>
                </div>
                <div class="review-block-date"><i class="icofont icofont-ui-calendar"></i>
                 <?php echo $fn->dt_format($v['add_date'], 'F d, Y'); ?> | <?php echo $fn->time_ago($v['add_date']); ?>
                </div>
            </div>
            <div class="review-block-description"><?php echo $v['comment']; ?>
            </div>
        </div>
    </div>
    <hr>
<?php
 if ($fn->varv($v['id'], $fn->replies)) {
  foreach ($fn->varv($v['id'], $fn->replies) as $k => $v) {
   ?>
      <div class="row comment" id="comment-<?php echo $v['id']; ?>">
          <div class="col-sm-12">
              <div class="review-block-title">
                  <img alt="<?php echo $v['name']; ?>" src="<?php echo $fn->permalink('assets/images/no-user.jpg'); ?>"
                       class="review-block-image"/>
                  <div class="review-block-name">
                      <i class="icofont icofont-ui-user"></i> <?php echo $v['name']; ?>
                      <a href="javascript:;" data-id="<?php echo $v['id'] ?>"
                         data-parent="<?php echo $v['parent_id'] == 0 ? $v['id'] : $v['parent_id']; ?>"
                         class="btn-link pull-right btn-reply">Reply</a>
                  </div>
                  <div class="review-block-date"><i class="icofont icofont-ui-calendar"></i>
                   <?php echo $fn->dt_format($v['add_date'], 'F d, Y'); ?>
                      | <?php echo $fn->time_ago($v['add_date']); ?>
                  </div>
              </div>
              <div class="review-block-description"><?php echo $v['comment']; ?>
              </div>
          </div>
      </div>
      <hr>
   <?php
  }
 }
 $str .= ob_get_clean();
 return $str;
?>