<?php
 require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'autoload.php';
 $fn = new admin\controllers\m_sliders;
 if ($fn->is_ajax_call()) {
  header('Content-Type: application/json');
  $json = '';
  if ($fn->post('action') == 'publish') {
   try {
    $fn->publish();
    $status = 'unpublished';
    if ($fn->post('publish') == 'Y') {
     $status = 'published';
    }
    $json = array('success' => true, 'html' => $fn->get_view('button', 'YES_NO', array('status' => $fn->post('publish'), 'id' => $fn->post('id'), 'action' => $fn->post('action'))), 'g_title' => $fn->page['name'], 'g_message' => 'Data has been ' . $status . ' successfully!');
   } catch (Exception $ex) {
    $json = array('error' => true, 'g_title' => $fn->page['name'], 'g_message' => $ex->getMessage());
   }
  }
  if ($fn->post('action') == 'sort') {
   try {
    $fn->sort();
    $json = array('success' => true, 'g_title' => $fn->page['name'], 'g_message' => 'Data has been sorted successfully!');
   } catch (Exception $ex) {
    $json = array('error' => true, 'g_title' => $fn->page['name'], 'g_message' => $ex->getMessage());
   }
  }
  if ($json) {
   echo $fn->json_encode($json);
  }
  exit();
 }
 if ($fn->get('action') == 'delete') {
  try {
   $fn->delete();
   $fn->session_msg('Data has been deleted successfully!', 'success');
  } catch (Exception $ex) {
   $fn->session_msg($ex->getMessage(), 'error');
  }
  $fn->return_ref();
 }
 if ($fn->post('btn_save') == 'save') {
  try {
   $fn->insert();
   $fn->session_msg('Data has been saved successfully!', 'success');
   $fn->return_ref();
  } catch (Exception $ex) {
   $fn->session_msg($ex->getMessage(), 'error');
  }
 }
 if ($fn->post('btn_update') == 'update') {
  try {
   $fn->update();
   $fn->session_msg('Data has been updated successfully!', 'success');
   $fn->return_ref();
  } catch (Exception $ex) {
   $fn->session_msg($ex->getMessage(), 'error');
  }
 }
 ob_start();
?>
<link rel="stylesheet"
      href="<?php echo $fn->permalink('resources/vendor/magnific-popup/magnific-popup.css', '', true); ?>"
      type="text/css"/>
<?php
 $fn->style = ob_get_clean();
 include 'inc/head.php';
 include 'inc/header.php';
?>
<div class="panel panel-default">
 <?php
  include 'inc/panel-head.php';
  if (($fn->per_add && $fn->get('action') == 'add') || ($fn->per_edit && $fn->get('action') == 'edit')) {
   if ($fn->get('action') == 'edit' && $fn->get('id')) {
    $fn->select();
   }
   ?>
      <form id="data-frm" name="data-frm" method="post" class="form-validate" autocomplete="off"
            enctype="multipart/form-data">
          <div class="panel-body">
              <div class="row">
                  <div class="form-group col-sm-9">
                      <label for="slider_title" class="input-label req"><?php echo _('Slider Title'); ?></label>
                      <input type="text" name="slider_title" id="slider_title" class="form-control"
                             value="<?php echo $fn->post('slider_title'); ?>" data-rule-title="true" required/>
                  </div>
                  <div class="form-group col-sm-3">
                      <label for="publish" class="input-label req"><?php echo _('Publish'); ?></label>
                      <select name="publish" id="publish" class="form-control" data-placeholder="Publish">
                       <?php echo $fn->show_list($fn->yes_no, $fn->post('publish'), false); ?>
                      </select>
                  </div>
                  <div class="clearfix"></div>
                  <div class="form-group col-sm-12">
                      <label for="slider_desc" class="input-label req"><?php echo _('Slider Description'); ?></label>
                      <textarea name="slider_desc" id="slider_desc" class="form-control" rows="5" required><?php echo $fn->post('slider_desc'); ?></textarea>
                  </div>
                  <div class="clearfix"></div>
                  <div class="form-group col-sm-4">
                      <label for="slider_caption" class="input-label"><?php echo _('Button Caption'); ?>
                          <small>(Optional)</small>
                      </label>
                      <input type="text" name="slider_caption" id="slider_caption" class="form-control"
                             value="<?php echo $fn->post('slider_caption'); ?>"/>
                  </div>
                  <div class="form-group col-sm-4">
                      <label for="slider_image" class="input-label req"><?php echo _('Slider Image'); ?>
                          <small>(Size: 1920 x 1000 px)</small>
                      </label>
                      <div class="clearfix custom-file">
                          <input type="file" name="slider_image" id="slider_image" required/>
                          <label for="slider_image"><span></span> <strong>Choose a file...</strong></label>
                      </div>
                   <?php
                    $image = $fn->post('slider_image');
                    if ($fn->file_exists($image)) {
                     ?>
                        <div class="clearfix mt-10">
                            <img src="<?php echo $fn->get_file($image, 0, 0, 200); ?>" width="200"/>
                        </div>
                    <?php } ?>
                  </div>
              </div>
          </div>
       <?php include 'inc/panel-footer.php'; ?>
      </form>
   <?php
  } else if ($fn->per_edit && $fn->get('action') == 'sort') {
   $fn->select_all(true);
   ?>
      <div class="panel-body">
       <?php if ($fn->data) { ?>
           <ul class="sorting" data-url="<?php echo $fn->page['page_url']; ?>">
            <?php
             foreach ($fn->data as $v) {
              ?>
                 <li id="SORT_<?php echo $v['id']; ?>"><?php echo $v['slider_title']; ?></li>
              <?php
             }
            ?>
           </ul>
       <?php } else { ?>
           <div class="alert alert-danger mb-0"><?php echo _('Oops, nothing found to sort.'); ?></div>
       <?php } ?>
      </div>
   <?php
  } else {
   $fn->select_all();
   ?>
      <div class="panel-body">
       <?php if ($fn->data) {
        ?>
           <div class="table-responsive">
               <table class="table table-striped table-bordered">
                   <thead>
                   <tr>
                       <th width="5%" class="text-center hidden-xs"><?php echo _('#'); ?></th>
                       <th><?php echo _('Slider Title'); ?></th>
                       <th width="5%"><?php echo _('Slider Image'); ?></th>
                       <th width="5%" class="text-center"><?php echo _('Publish'); ?></th>
                    <?php if ($fn->check_per()) { ?>
                        <th width="5%" class="text-center">Actions</th>
                    <?php } ?>
                   </tr>
                   </thead>
                   <tbody>
                   <?php
                    $i = $fn->sno;
                    foreach ($fn->data as $row) {
                     ?>
                        <tr>
                            <td class="text-center hidden-xs"><?php echo $i++; ?></td>
                            <td><?php echo $row['slider_title']; ?></td>
                            <td><a href="<?php echo $fn->get_file($row['slider_image']); ?>"
                                   class="magnific-gallery"><img
                                            src="<?php echo $fn->get_file($row['slider_image'], 0, 0, 50); ?>"
                                            alt="<?php echo $row['slider_title']; ?>"/></a></td>
                            <td align="center">
                                <div id="publish-<?php echo $row['id']; ?>">
                                 <?php echo $fn->get_view('button', 'YES_NO', array('status' => $row['publish'], 'id' => $row['id'], 'action' => 'publish')); ?>
                                </div>
                            </td>
                         <?php include 'inc/actions.php'; ?>
                        </tr>
                    <?php } ?>
                   </tbody>
               </table>
           </div>
        <?php
        echo $fn->pagination->display_paging_info();
       } else {
        ?>
           <div class="alert alert-danger mb-0">Oops, nothing found.</div>
       <?php }
       ?>
      </div>
  <?php }
 ?>
</div>
<?php ob_start(); ?>
<script type="text/javascript"
        src="<?php echo $fn->permalink('resources/vendor/magnific-popup/jquery.magnific-popup.js', '', true); ?>"></script>
<?php
 $fn->script = ob_get_clean();
 include 'inc/footer.php';
 include 'inc/foot.php';
?>
