<?php
 require dirname(__FILE__) . DIRECTORY_SEPARATOR . "autoload.php";
 $fn = new controllers\login;
 if ($fn->post('btn_login')) {
  $fn->login_validation();
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
                                        <h3>Sign In</h3>
                                    </div>
                                </div>
                            </div>
                            <form method="post" name="login-frm" id="login-frm" class="form-validate" autocomplete="off">
                                <div class="row justify-content-center">
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <input type="email" class="form-control" name="login[email]" id="login-email" value="<?php echo $fn->post('login', 'email') ?>" placeholder="Email *" required/>
                                        </div>
                                    </div>
                                </div>
                                <div class="row justify-content-center">
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <input type="password" class="form-control" name="login[password]" id="login-password" placeholder="Password *" required/>
                                        </div>
                                    </div>
                                </div>
                             <?php if ($fn->session('lstep') > 2) { ?>
                                 <div class="row justify-content-center">
                                     <div class="col-12 col-md-6">
                                         <div class="row">
                                             <div class="col-6">
                                                 <div class="form-group captcha">
                                                     <a class="btn btn-primary float-right refresh-captcha" tabindex="-1"><i class="fa fas fa-sync"></i></a>
                                                     <img src="<?php echo $fn->permalink('captcha') . '?key=' . $fn->encrypt_post_data(array('for' => 'login', 'color' => 1)) . '&' . ((float)rand() / (float)getrandmax()); ?>" alt="Captcha" class="captcha-code"/>
                                                 </div>
                                             </div>
                                             <div class="col-6">
                                                 <div class="form-group">
                                                     <input type="text" name="login[captcha]" id="login-captcha" class="form-control" maxlength="6" placeholder="######" required/>
                                                 </div>

                                             </div>
                                         </div>
                                     </div>
                                 </div>
                             <?php } ?>
                                <div class="row justify-content-center">
                                    <div class="col-12 col-md-6">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <input type="checkbox" name="login[remember]" id="login-remember" value="1" checked/>
                                                    <label for="login-remember">Remember Me</label>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="text-right">
                                                    <a href="<?php echo $fn->permalink('forgot'); ?>" class="forgot">Forgot password?</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row justify-content-center">
                                    <div class="col-12 col-md-6 col-lg-4">
                                        <div class="text-center">
                                            <input type="hidden" name="token" value="<?php echo $fn->post_token(); ?>"/>
                                            <button type="submit" class="btn btn-primary full-width" name="btn_login" value="Login">Log in</button>
                                            <p>New Customer? <a href="<?php echo $fn->permalink('register'); ?>">Join here.</a></p>
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
	