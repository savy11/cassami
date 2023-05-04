<?php
 require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'autoload.php';
 $fn = new controllers\account;
 if ($fn->is_ajax_call()) {
  header('Content-Type: application/json');
  $json = [];
  
  if ($fn->post('type') == 'cities') {
   try {
    $json = ['success' => true, 'html' => $fn->show_list($fn->get_cities($fn->post('update', 'state_id')), '', true), 'script' => '$(\'#update-city-id\').prop(\'disabled\', false);'];
   } catch (Exception $ex) {
    $json = ['error' => true, 'g_title' => 'Error', 'g_message' => $ex->getMessage()];
   }
  }
  
  if ($json) {
   echo $fn->json_encode($json);
  }
  exit();
 }
 if ($fn->get('for') != 'account') {
  $fn->not_found();
 }
 $fn->cms_page('profile');
 if ($fn->post('btn_update') != '') {
  try {
   $fn->update_details();
   $fn->session_msg('Your changes has been saved successfully!', 'success', 'Account Info');
  } catch (Exception $ex) {
   $fn->session_msg($fn->replace_sql($ex->getMessage()), 'error', 'Error');
  }
  $fn->redirecting('account/profile');
 }
 if ($fn->post('btn_change') != '') {
  try {
   $fn->change_password();
   $fn->session_msg('Your password has been changed successfully!', 'success', 'Change Password');
   $fn->redirecting('account/profile');
  } catch (Exception $ex) {
   $fn->session_msg($fn->replace_sql($ex->getMessage()), 'error', 'Error');
  }
 }
 if ($fn->post('btn_update_mail') != '') {
  try {
   $fn->update_email();
   $fn->session_msg('Your email has been changed successfully!', 'success', 'Change Email');
   $fn->redirecting('account/profile');
  } catch (Exception $ex) {
   $fn->session_msg($fn->replace_sql($ex->getMessage()), 'error', 'Error');
  }
 }
 include app_path . 'inc' . ds . 'head.php';
 include app_path . 'inc' . ds . 'header.php';
?>
<?php /*<div class="dash-content">
        <div class="container-fluid">
            <div class="row">
             <?php if ($fn->get('action') == 'edit') { ?>
                 <div class="col-md-12">
                     <div class="db-add-list-wrap">
                         <div class="act-title">
                             <h5>Edit Profile :</h5>
                         </div>
                         <div class="db-add-listing">
                             <div class="row">
                                 <div class="col-md-4 offset-md-4">
                                     <!-- Avatar -->
                                     <div class="edit-profile-photo">
                                         <img src="<?php echo $fn->permalink('assets/images/' . $fn->varv($fn->user['gender'], $fn->gender) . '.svg'); ?>" alt="<?php echo $fn->user['display_name']; ?>"/>
                                         <div class="change-photo-btn">
                                             <div class="contact-form__upload-btn xs-left">
                                                 <input class="contact-form__input-file" type="file" name="photo-upload" id="photo-upload">
                                                 <span>
                                                            Upload Photos
                                                        </span>
                                             </div>
                                         </div>
                                     </div>
                                 </div>
                                 <div class="col-md-12">
                                     <form name="update-frm" id="update-frm" class="form-validate" method="post" autocomplete="off">
                                         <div class="row">
                                             <div class="col-sm-4">
                                                 <div class="form-group">
                                                     <label class="input-label req">I am a</label>
                                                     <select class="form-control no-select" name="update[gender]" id="update-gender"
                                                             required>
                                                      <?php echo $fn->show_list($fn->gender, $fn->user['gender'], false); ?>
                                                     </select>
                                                 </div>
                                             </div>
                                             <div class="col-sm-4">
                                                 <div class="form-group">
                                                     <label class="input-label req">First Name</label>
                                                     <input class="form-control"
                                                            value="<?php echo $fn->user['first_name']; ?>"
                                                            placeholder="e.g. John" type="text"
                                                            name="update[first_name]" id="first_name"
                                                            required/>
                                                 </div>
                                             </div>
                                             <div class="col-sm-4">
                                                 <div class="form-group">
                                                     <label class="input-label req">Last Name</label>
                                                     <input class="form-control"
                                                            value="<?php echo $fn->user['last_name']; ?>"
                                                            placeholder="e.g. Walter" type="text"
                                                            name="update[last_name]" id="last_name"
                                                            required/>
                                                 </div>
                                             </div>
                                             <div class="clearfix"></div>
                                             <div class="col-sm-4">
                                                 <div class="form-group">
                                                     <label class="input-label">Title</label>
                                                     <input class="form-control" value="<?php echo $fn->user['title']; ?>" placeholder="e.g. Agent" type="text" name="update[title]" id="update-title"/>
                                                 </div>
                                             </div>
                                             <div class="col-sm-4">
                                                 <div class="form-group">
                                                     <label class="input-label">Company</label>
                                                     <input class="form-control" value="<?php echo $fn->user['company']; ?>" placeholder="e.g. 123 456 7890" type="text" name="update[company]" id="update-company"/>
                                                 </div>
                                             </div>
                                             <div class="col-sm-4">
                                                 <div class="form-group">
                                                     <label class="input-label req">Phone</label>
                                                     <input class="form-control"
                                                            value="<?php echo $fn->post('mobile_no') ? $fn->post('mobile_no') : $fn->user['mobile_no']; ?>"
                                                            placeholder="e.g. 123 456 7890"
                                                            type="text" name="update[mobile_no]" id="mobile_no"
                                                            required/>
                                                 </div>
                                             </div>
                                             <div class="clearfix"></div>
                                             <div class="col-sm-6">
                                                 <div class="form-group">
                                                     <label class="input-label req">Email Address</label>
                                                     <input class="form-control"
                                                            value="<?php echo $fn->user['email']; ?>"
                                                            placeholder="john@gmail.com" disabled type="email" required/>
                                                 </div>
                                             </div>
                                             <div class="col-sm-6">
                                                 <div class="form-group">
                                                     <label class="input-label">Facebook Url</label>
                                                     <input class="form-control" value="<?php echo $fn->user['fb_url']; ?>" placeholder="e.g. https://www.facebook.com" type="text" name="update[fb_url]" id="update-fb-url"/>
                                                 </div>
                                             </div>
                                             <div class="col-sm-6">
                                                 <div class="form-group">
                                                     <label class="input-label">Twitter Url</label>
                                                     <input class="form-control" value="<?php echo $fn->user['tw_url']; ?>" placeholder="e.g. https://www.twitter.com" type="text" name="update[tw_url]" id="update-tw-url"/>
                                                 </div>
                                             </div>
                                             <div class="col-sm-6">
                                                 <div class="form-group">
                                                     <label class="input-label">Pinterest Url</label>
                                                     <input class="form-control" value="<?php echo $fn->user['pi_url']; ?>" placeholder="e.g. https://www.pinterest.com" type="text" name="update[pi_url]" id="update-pi-url"/>
                                                 </div>
                                             </div>
                                             <div class="col-sm-6">
                                                 <div class="form-group">
                                                     <label class="input-label">Instagram Url</label>
                                                     <input class="form-control" value="<?php echo $fn->user['ig_url']; ?>" placeholder="e.g. https://www.instagram.com" type="text" name="update[ig_url]" id="update-ig-url"/>
                                                 </div>
                                             </div>
                                             <div class="col-sm-6">
                                                 <div class="form-group">
                                                     <label class="input-label">Youtube</label>
                                                     <input class="form-control" value="<?php echo $fn->user['yt_url']; ?>" placeholder="e.g. https://www.youtube.com" type="text" name="update[yt_url]" id="update-yt-url"/>
                                                 </div>
                                             </div>
                                             <div class="col-sm-12 text-right">
                                                 <input type="hidden" name="token" value="<?php echo $fn->post_token(); ?>"/>
                                                 <button type="submit" name="btn_update" id="btn_update" value="Update"
                                                         class="btn v3"> Save Changes
                                                 </button>
                                             </div>
                                         </div>
                                     </form>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>
             <?php } else { ?>
                 <div class="col-md-12">
                     <div class="recent-activity">
                         <div class="act-title">
                             <h5>Profile Details</h5>
                         </div>
                         <div class="profile-wrap">
                             <div class="row mb-50">
                                 <div class="col-lg-4 col-md-6 col-sm-4 text-center">
                                     <img src="<?php echo $fn->permalink('assets/images/' . $fn->varv($fn->user['gender'], $fn->gender) . '.svg'); ?>" alt="<?php echo $fn->user['display_name']; ?>" class="img-responsive" width="300"/>
                                 </div>
                                 <div class="col-lg-8 col-md-6 col-sm-8">
                                     <div class="agent-details">
                                         <h3><?php echo $fn->user['display_name']; ?></h3>
                                         <ul class="address-list">
                                          <?php if ($fn->user['company']) { ?>
                                              <li>
                                                  <span>Company: </span>
                                               <?php echo $fn->user['company']; ?>
                                              </li>
                                          <?php } ?>
                                             <li>
                                                 <span>Title: </span>
                                              <?php echo $fn->user['title']; ?>
                                             </li>
                                             <li>
                                                 <span>Mobile No.: </span>
                                              <?php echo $fn->user['mobile_no']; ?>
                                             </li>
                                             <li>
                                                 <span>Email: </span>
                                              <?php echo $fn->user['email']; ?>
                                             </li>
                                             <li>
                                                 <span>Join Date: </span>
                                              <?php echo $fn->dt_format($fn->user['add_date'], 'F d,Y'); ?>
                                             </li>
                                         </ul>
                                      <?php if ($fn->user['fb_url'] || $fn->user['tw_url'] || $fn->user['pi_url'] || $fn->user['ig_url'] || $fn->user['yt_url']) { ?>
                                          <ul class="social-buttons style1">
                                              <li><a href="<?php echo $fn->user['fb_url']; ?>"><i class="fab fa-facebook-f"></i></a></li>
                                              <li><a href="<?php echo $fn->user['tw_url']; ?>"><i class="fab fa-twitter"></i></a></li>
                                              <li><a href="<?php echo $fn->user['pi_url']; ?>"><i class="fab fa-pinterest-p"></i></a></li>
                                              <li><a href="<?php echo $fn->user['yt_url']; ?>"><i class="fab fa-youtube"></i></a></li>
                                              <li><a href="<?php echo $fn->user['ig_url']; ?>"><i class="fab fa-instagram"></i></a></li>
                                          </ul>
                                      <?php } ?>
                                         <a href="<?php echo $fn->permalink('account/profile?action=edit'); ?>" class="btn v3 mt-50">Edit profile</a>
                                     </div>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>
             <?php } ?>
            </div>
        </div>
    </div>*/ ?>
    <section class="bkg-light medium boxed">
        <div class="container">
            <div class="row">
                <div class="col-12">

                    <div class="boxed-container my-account">
                     
                     <?php include_once app_path . 'inc' . ds . 'acc_menus.php'; ?>

                        <div class="account-block">
                            <div class="row justify-content-center">
                                <div class="col-12">
                                    <div class="section-header text-center">
                                        <h3 class="d-none d-lg-block">Tell us about you</h3>
                                        <span class="caption-header">Please take a few moments to keep this information up to date.</span>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-10">
                                    <form class="form-validate" name="update-frm" id="update-frm" method="post" autocomplete="off">
                                     <?php $update = $fn->post('update') ? $fn->post('update') : $fn->user; ?>
                                        <div class="row justify-content-center">

                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" name="update[first_name]" id="update-first-name" placeholder="First Name" value="<?php echo $fn->varv('first_name', $update); ?>" required/>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" name="update[last_name]" id="update-last-name" placeholder="Last Name" value="<?php echo $fn->varv('last_name', $update); ?>" required/>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-4">
                                                <div class="form-group">
                                                    <select name="update[gender]" id="update-gender" class="form-control" data-placeholder="Gender" required>
                                                     <?php echo $fn->show_list($fn->gender, $fn->varv('gender', $update), true); ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-4">
                                                <div class="form-group">
                                                    <input type="text" class="form-control numbers-only" name="update[mobile_no]" id="update-mobile-no" placeholder="Phone Number" value="<?php echo $fn->varv('mobile_no', $update); ?>" required/>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-4">
                                                <div class="form-group">
                                                    <input type="email" class="form-control" name="update[email]" id="update-email" placeholder="Email Address" value="<?php echo $fn->varv('email', $update); ?>" disabled/>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <select name="update[state_id]" id="update-state-id" class="form-control" data-placeholder="State" data-ajaxify="true" data-page="true" data-url="account/profile" data-type="cities" data-event="change" data-recid="update-city-id" required>
                                                     <?php echo $fn->show_list($fn->get_states(), $fn->varv('state_id', $update), true); ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <select name="update[city_id]" id="update-city-id" class="form-control" data-placeholder="City" required<?php echo $fn->varv('state_id', $update) ? '' : ' disabled'; ?>>
                                                     <?php echo $fn->show_list($fn->get_cities($fn->varv('state_id', $update)), $fn->varv('city_id', $update), true); ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6 col-lg-5">
                                                <input type="hidden" name="token" value="<?php echo $fn->post_token(); ?>"/>
                                                <button class="btn btn-primary" type="submit" name="btn_update" value="update">Submit</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="account-block">
                            <div class="row justify-content-center">
                                <div class="col-12">
                                    <div class="section-header text-center">
                                        <h3>Change password</h3>
                                        <span class="caption-header">
                                            Choose a unique password to protect your account.<br/>
                                            Passwords are case-sensitive and must be at least 6 characters.
                                        </span>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-10">
                                    <form class="form-validate" name="pass-frm" id="pass-frm" method="post" autocomplete="off">
                                        <div class="row justify-content-center">
                                            <div class="col-12 col-lg-6">
                                                <div class="form-group">
                                                    <input type="password" class="form-control" name="change[old]" id="change-old" placeholder="Enter current password" value="<?php echo $fn->post('old') ?>" required/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row justify-content-center">
                                            <div class="col-12 col-lg-6">
                                                <div class="form-group">
                                                    <input type="password" class="form-control" name="change[new]" id="change-new" placeholder="Enter new password" value="<?php echo $fn->post('new'); ?>" minlength="6" required/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row justify-content-center">
                                            <div class="col-12 col-lg-6">
                                                <div class="form-group">
                                                    <input type="password" class="form-control" name="change[retype]" id="change-retype" placeholder="Reenter new password" value="<?php echo $fn->post('retype'); ?>" minlength="6" data-rule-equalTo="#change-new" required/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row justify-content-center">
                                            <div class="col-12 col-md-6 col-lg-5">
                                                <input type="hidden" name="token" value="<?php echo $fn->post_token(); ?>"/>
                                                <button class="btn btn-primary" name="btn_change" type="submit" value="change">Submit</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="account-block">
                            <div class="row justify-content-center">
                                <div class="col-12">
                                    <div class="section-header text-center">
                                        <h3>Change email address</h3>
                                        <span class="caption-header">Choose a unique email for log in.</span>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-10">
                                    <form class="form-validate" name="email-frm" id="email-frm" method="post" autocomplete="off">
                                        <div class="row justify-content-center">
                                            <div class="col-12 col-lg-6">
                                                <div class="form-group">
                                                    <input type="email" class="form-control" placeholder="Old email address" name="mail[old_email]" id="mail-old-email" value="<?php echo $fn->post('old_email'); ?>" required/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row justify-content-center">
                                            <div class="col-12 col-lg-6">
                                                <div class="form-group">
                                                    <input type="email" class="form-control" placeholder="New email address" name="mail[new_email]" id="mail-new-email" value="<?php echo $fn->post('new_email'); ?>" required/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row justify-content-center">
                                            <div class="col-12 col-md-6 col-lg-5">
                                                <input type="hidden" name="token" value="<?php echo $fn->post_token(); ?>"/>
                                                <button class="btn btn-primary" type="submit" name="btn_update_mail" value="update">Submit</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                     
                     <?php /*<div class="account-block">
                            <div class="row justify-content-center">
                                <div class="col-12">
                                    <div class="section-header text-center">
                                        <h3>Delete account</h3>
                                        <span class="caption-header">
                                            Delete your account.<br/>
                                            Are you sure you want to delete this account? All your data will be permanently deleted.
                                        </span>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-10">
                                    <div class="row justify-content-center">
                                        <div class="col-12 col-md-6 col-lg-5">
                                            <a href="my-account-info.html#" class="btn btn-primary full-width">I'm sure</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>*/ ?>

                    </div>
                </div>
            </div>
        </div>
    </section>
<?php
 include app_path . 'inc' . ds . 'footer.php';
 include app_path . 'inc' . ds . 'foot.php';
?>