<?php
 require dirname(__FILE__) . DIRECTORY_SEPARATOR . "autoload.php";
 $fn = new controllers\register;
 if ($fn->post('btn_register')) {
  try {
   $fn->register();
   $fn->session_msg('Your account has been registered successfully. Please login to your account.', 'success', '');
   $fn->redirecting('login');
  } catch (Exception $ex) {
   $fn->session_msg($ex->getMessage(), 'error');
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
                                        <h3>Sign Up</h3>
                                    </div>
                                </div>
                            </div>
                            <form class="form-validate" method="post" name="register-frm" id="register-frm" autocomplete="off">
                                <div class="row justify-content-center">
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <select class="form-control no-select" name="register[gender]" id="register-gender" required>
                                             <?php echo $fn->show_list($fn->gender, $fn->post('register', 'gender'), false); ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row justify-content-center">
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="register[first_name]" id="register-first-name" value="<?php echo $fn->post('register', 'first_name'); ?>" placeholder="First Name *" required/>
                                        </div>
                                    </div>
                                </div>
                                <div class="row justify-content-center">
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="register[last_name]" id="register-last-name" value="<?php echo $fn->post('register', 'last_name'); ?>" placeholder="Last Name *" required/>
                                        </div>
                                    </div>
                                </div>
                                <div class="row justify-content-center">
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <input type="text" class="form-control numbers-only" name="register[mobile_no]" id="register-mobile-no" value="<?php echo $fn->post('register', 'mobile_no'); ?>" placeholder="Mobile No. *" required/>
                                        </div>
                                    </div>
                                </div>
                                <div class="row justify-content-center">
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <input type="email" class="form-control" name="register[email]" id="register-email" value="<?php echo $fn->post('register', 'email'); ?>" placeholder="Email *" required/>
                                        </div>
                                    </div>
                                </div>
                                <div class="row justify-content-center">
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <input type="password" class="form-control" name="register[password]" id="register-password" placeholder="Password *" required/>
                                        </div>
                                    </div>
                                </div>
                                <div class="row justify-content-center">
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <input type="password" class="form-control" name=register[confirm_password]"" id="register-confirm-password" placeholder="Retype Password *" data-rule-equalTo="#register-password" required/>
                                        </div>
                                    </div>
                                </div>
                                <div class="row justify-content-center">
                                    <div class="col-12 col-md-6">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group captcha">
                                                    <a class="btn btn-primary float-right refresh-captcha" tabindex="-1"><i class="fa fas fa-sync"></i></a>
                                                    <img src="<?php echo $fn->permalink('captcha') . '?key=' . $fn->encrypt_post_data(array('for' => 'register', 'color' => 1)) . '&' . ((float)rand() / (float)getrandmax()); ?>" alt="Captcha" class="captcha-code"/>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <input type="text" name="register[captcha]" id="register-captcha" class="form-control" maxlength="6" placeholder="######" required/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row justify-content-center">
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <input type="checkbox" name="register[terms]" id="register-terms" value="1" required/>
                                            <label for="register-terms">I agree to <a target="_blank" href="<?php echo $fn->permalink('terms-conditions'); ?>">Terms and Conditions</a> and <a target="_blank" href="<?php echo $fn->permalink('privacy-policy'); ?>">Privacy Policy</a>.</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row justify-content-center">
                                    <div class="col-12 col-md-6 col-lg-4">
                                        <div class="text-center">
                                            <input type="hidden" name="token" value="<?php echo $fn->post_token(); ?>"/>
                                            <button type="submit" class="btn btn-primary full-width" name="btn_register" value="Register">Join</button>
                                            <p>Already have an account? <a href="<?php echo $fn->permalink('login'); ?>">Sign in.</a></p>
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
	