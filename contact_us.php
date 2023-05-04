<?php
 require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'autoload.php';
 $fn = new controllers\contact_us;
 if ($fn->is_ajax_call()) {
  header('Content-Type: application/json');
  $json = '';
  if ($fn->post('action') == 'contact') {
   try {
    $fn->contact_enq();
    $json = array('success' => true, 'g_title' => 'Contact Us', 'g_message' => 'Your contact request has been submitted successfully.', 'script' => 'app.reset_form(\'contact-frm\');$(\'.refresh-captcha\').trigger(\'click\');');
   } catch (Exception $ex) {
    $json = array('error' => true, 'g_title' => 'Error', 'g_message' => $ex->getMessage());
   }
  }
  if ($json) {
   echo $fn->json_encode($json);
  }
  exit();
 }
 include_once app_path . 'inc' . ds . 'head.php';
 include_once app_path . 'inc' . ds . 'header.php';
?>

    <section class="bkg-light medium">
        <div class="container">
            <div class="row">
                <div class="col-12">

                    <div class="boxed-container">

                        <div class="contact">
                            <div class="block">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="section-header text-center">
                                            <h3><?php echo $fn->varv('page_heading', $fn->cms); ?></h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="row justify-content-center">
                                    <div class="col-12 col-md-12">
                                        <div class="form-group">
                                         <p>Our customer support team is always ready to answer your questions and provide all the necessary assistance. You can contact us via <a href="mailto:<?php echo $fn->varv('company', $fn->company['email']); ?>"><?php echo $fn->varv('company', $fn->company['email']); ?></a> for your queries or using the below form.</p>
                                        </div>
                                    </div>
                                </div>
                                <form class="form-validate" name="contact-frm" id="contact-frm" method="post" autocomplete="off" data-ajax="true" data-page="true" data-url="contact-us">
                                    <div class="row justify-content-center">
                                        <div class="col-12 col-md-6">
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="contact[name]" id="contact-name" value="<?php echo $fn->post('contact', 'name'); ?>" placeholder="Full name *" required/>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <div class="form-group">
                                                <input type="email" class="form-control" name="contact[email]" id="contact-email" value="<?php echo $fn->post('contact', 'email'); ?>" placeholder="Email *" required/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row justify-content-center">
                                        <div class="col-12 col-md-6">
                                            <div class="form-group">
                                                <input type="text" class="form-control numbers-only" name="contact[no]" id="contact-no" value="<?php echo $fn->post('contact', 'no'); ?>" placeholder="Phone No."/>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="contact[subject]" id="contact-subject" value="<?php echo $fn->post('contact', 'subject'); ?>" placeholder="Subject *" required/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 col-md-8">
                                            <div class="form-group">
                                                <textarea class="form-control" name="contact[message]" id="contact-message" placeholder="Message *" required><?php echo $fn->post('contact', 'message'); ?></textarea>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group captcha">
                                                        <a class="btn btn-primary float-right refresh-captcha" tabindex="-1"><i class="fa fas fa-sync"></i></a>
                                                        <img src="<?php echo $fn->permalink('captcha') . '?key=' . $fn->encrypt_post_data(array('for' => 'contact', 'color' => 1)) . '&' . ((float)rand() / (float)getrandmax()); ?>" alt="Captcha" class="captcha-code"/>
                                                    </div>
                                                    <div class="form-group">
                                                        <input type="text" name="contact[captcha]" id="contact-captcha" class="form-control" maxlength="6" placeholder="######" required/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row justify-content-center">
                                        <div class="col-12">
                                            <div class="text-center">
                                                <input type="hidden" name="token" value="<?php echo $fn->post_token(); ?>"/>
                                                <button type="submit" class="btn btn-primary" name="action" value="contact">Submit</button>
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
 include_once app_path . 'inc' . ds . 'footer.php';
 include_once app_path . 'inc' . ds . 'foot.php';
?>