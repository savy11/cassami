<?php
$str = '';
ob_start();
if ($type == 'subscribe') {
    $fn->modal = array('title' => 'Verify Email Address');
    ?>
    <form method="post" name="subv-frm" id="subv-frm" class="form-validate" data-ajax="true" data-url="process"
          data-action="verify">
        <div class="modal-body">
            <p>You need to enter 6-digit verificaiton code which sent to you at email address.</p>
            <div class="form-group mb-0">
                <label class="input-label req">Verification Code</label>
                <input type="text" name="sub[code]" id="sub-code" class="form-control numbers-only"
                       placeholder="Enter your verification code" maxlength="6" required/>
            </div>
        </div>
        <div class="modal-footer">
            <input type="hidden" name="token" value="<?php echo $fn->post_token(); ?>"/>
            <button type="submit" class="sigma_btn-custom">Submit</button>
        </div>
    </form>
    <?php
}
$str = preg_replace('/^\s+|\n|\r|\s+$/m', '', ob_get_clean());
if ($fn->is_ajax_call()) {
    $modal = '';
    ob_start();
    ?>
    <div class="modal-dialog<?php echo $fn->varv('md_class', $fn->modal); ?>"
         role="document"<?php echo $fn->varv('style', $fn->modal) ? ' style="' . $fn->modal['style'] . '"' : ''; ?>>
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><?php echo $fn->modal['title']; ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <?php echo $str; ?>
        </div>
    </div>
    <?php
    $modal = preg_replace('/^\s+|\n|\r|\s+$/m', '', ob_get_clean());
    return $modal;
}
return $str;
?>