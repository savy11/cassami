<?php
 require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'autoload.php';
 $fn = new controllers\forgot_password;
 $data = $fn->reset_validate();
 if ($fn->post('btn_reset') != '') {
  try {
   $fn->reset_password();
   $fn->session_msg('Your password has been reset successfully. Please login.', 'success', 'Login');
   $fn->redirecting('login');
  } catch (Exception $ex) {
   $fn->session_msg($ex->getMessage(), 'error', 'Reset Password');
  }
 }
 $fn->cms['page_title'] = $fn->cms['page_heading'] = 'Reset Password';
 include app_path . 'inc' . ds . 'head.php';
 include app_path . 'inc' . ds . 'header.php';
 include app_path . 'inc' . ds . 'breadcrumb.php';
?>
    <section class="login_page">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6 mx-auto">
                    <div class="widget">
                        <div class="login-modal-right">
                            <h5 class="heading-design-h5">Reset Password</h5>
                            <p>Enter your new password for <strong>(<?php echo $fn->varv('email', $data); ?>)</strong>
                                in the below field</p>
                            <form class="form-validate" id="reset-frm" name="reset-frm" method="post"
                                  autocomplete="off">

                                <fieldset class="form-group">
                                    <label>New Password</label>
                                    <input type="password" name="reset[password]" id="reset-password"
                                           class="form-control"
                                           placeholder="New Password" required/>
                                </fieldset>
                                <fieldset class="form-group">
                                    <label class="input-label req">Confirm New Password</label>
                                    <input type="password" name="reset[re_password]" id="reset-re-password"
                                           class="form-control" placeholder="Repeat New Password"
                                           data-rule-equalto="#reset-password" required/>
                                </fieldset>
                                <fieldset class="form-group">
                                    <input type="hidden" name="token" value="<?php echo $fn->post_token(); ?>"/>
                                    <input type="hidden" class="form-control" name="reset[email]" id="reset-email"
                                           value="<?php echo $fn->varv('email', $data); ?>"/>
                                    <button type="submit" name="btn_reset" value="Submit" class="btn btn-lg
                                    btn-theme-round btn-block">
                                        Reset Password
                                    </button>
                                </fieldset>

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