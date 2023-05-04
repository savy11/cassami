<?php
 require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'autoload.php';
 $fn = new controllers\forgot;
 $fn->cms_page('forgot');
 $fn->cms['page_title'] = $fn->page['name'];
 if ($fn->post('btn_forgot') != '') {
  try {
   $fn->check_forgot();
   $fn->session_msg('Reset link has sent to your email address.', 'success', $fn->page['name']);
   $fn->redirecting('forgot');
  } catch (Exception $ex) {
   $fn->session_msg($ex->getMessage(), 'error', $fn->page['name']);
  }
 }
 include app_path . 'inc' . ds . 'head.php';
 include app_path . 'inc' . ds . 'header.php';
?>
    <section class="bkg-light medium">
        <div class="container">
            <div class="row">
                <div class="col-12">

                    <div class="boxed-container">

                        <div class="authentication">
                            <div class="block">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="section-header text-center">
                                            <h3>Forgot password</h3>
                                        </div>
                                    </div>
                                </div>
                                <form class="form-validate" name="forgot-frm" id="forgot-frm" method="post" autocomplete="off">
                                    <div class="row justify-content-center">
                                        <div class="col-12 col-md-6">
                                            <div class="form-group">
                                                <input type="email" class="form-control" name="forgot[email]" id="forgot-email" placeholder="e.g. demo@example.com" value="<?php echo $fn->post('forgot', 'email'); ?>" required/>
                                                <p><a href="<?php echo $fn->permalink('login'); ?>"><i class="fas fa-arrow-circle-left"></i> Back to Sign in</a></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row justify-content-center">
                                        <div class="col-12 col-md-6 col-lg-4">
                                            <div class="text-center">
                                                <input type="hidden" name="token" value="<?php echo $fn->post_token(); ?>"/>
                                                <button type="submit" class="btn btn-primary full-width" name="btn_forgot" value="Submit">Send Reset Link</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
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