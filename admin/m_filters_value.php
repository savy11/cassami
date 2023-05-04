<?php
 require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'autoload.php';
 $fn = new admin\controllers\m_filters_value;
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
  if ($fn->post('type') == 'labels') {
   try {
    $html = $fn->show_list($fn->get_linked_labels($fn->post('label_id'), '', true));
    $json = array('success' => true, 'html' => $html, 'script' => '$(\'#linked_id\').prop(\'disabled\', false);');
   } catch (Exception $ex) {
    $json = array('error' => true, 'g_title' => $fn->page['name'], 'g_message' => $ex->getMessage());
   }
  }
  if ($fn->post('type') == 'values') {
   try {
    $html = $fn->show_list($fn->get_linked_values($fn->post('linked_id'), '', true));
    $json = array('success' => true, 'html' => $html, 'script' => '$(\'#value_id\').prop(\'disabled\', false);');
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
 include 'inc/head.php';
 include 'inc/header.php';
 if (($fn->per_add && $fn->get('action') == 'add') || ($fn->per_edit && $fn->get('action') == 'edit')) {
  if ($fn->get('action') == 'edit' && $fn->get('id')) {
   $fn->select();
  }
  if (!($fn->post('filters') != '' && count($fn->post('filters')) > 0)) {
   $_POST['filters'] = array(0 => '');
  }
  ?>
     <form id="data-frm" name="data-frm" method="post" class="form-validate" autocomplete="off" enctype="multipart/form-data">
         <div class="panel panel-default">
          <?php include 'inc/panel-head.php'; ?>
             <div class="panel-body">
                 <div class="row">
                     <div class="form-group col-sm-4">
                         <label for="label_id" class="input-label req"><?php echo _('Filter'); ?></label>
                         <select name="label_id" id="label_id" class="form-control" data-placeholder="Filter" data-ajaxify="true" data-page="true" data-url="m-filters-value" data-type="labels" data-event="change" data-recid="linked_id" required>
                          <?php echo $fn->show_list($fn->get_ads_labels(), $fn->post('label_id'), true); ?>
                         </select>
                     </div>
                     <div class="form-group col-sm-4">
                         <label for="linked_id" class="input-label"><?php echo _('Linked Filter'); ?></label>
                         <select name="linked_id" id="linked_id" class="form-control" data-placeholder="Linked Filter" data-allow-clear="true" data-ajaxify="true" data-page="true" data-url="m-filters-value" data-type="values" data-event="change" data-recid="value_id">
                          <?php echo $fn->show_list($fn->get_linked_labels($fn->post('label_id')), $fn->post('linked_id'), true); ?>
                         </select>
                     </div>
                     <div class="form-group col-sm-4">
                         <label for="value_id" class="input-label"><?php echo _('Linked Filter Value'); ?></label>
                         <select name="value_id" id="value_id" class="form-control" data-placeholder="Linked Filter Value" data-allow-clear="true">
                          <?php echo $fn->show_list($fn->get_linked_values($fn->post('linked_id')), $fn->post('value_id'), true); ?>
                         </select>
                     </div>
                 </div>
             </div>
         </div>

         <div class="panel panel-default">
             <div class="panel-heading">
                 <h3 class="panel-title">Values</h3>
             </div>
             <div class="panel-body">
                 <input type="hidden" name="filters_del_ids" id="filters_del_ids" class="form-control"/>
                 <table class="table table-bordered multi-table grid mb-15" cellpadding="0" cellspacing="0" data-btn-add="filters_row_add" data-btn-delete="filters_row_delete" data-row-index="<?php echo count($fn->post('filters')) - 1; ?>" data-grid-body="filters_grid_body">
                     <thead>
                     <tr>
                         <th width="5%" class="text-center">
                             <a href="#" class="btn btn-success btn-sm" rel="filters_row_add" tabindex="-1"><span class="icon ti-plus"></span></a>
                         </th>
                         <th class="req">Title</th>
                         <th class="req">Publish</th>
                     </tr>
                     </thead>
                     <tbody class="filters_grid_body">
                     <?php
                      if ($fn->post('filters') != '') {
                       foreach ($fn->post('filters') as $k => $v) {
                        $tr = '_TR' . $k;
                        ?>
                           <tr class="entry" id="<?php echo $tr; ?>">
                               <td align="center">
                                   <a href="#" class="btn btn-danger btn-sm" rel="filters_row_delete" data-id="<?php echo $tr; ?>" data-del-id="<?php echo $fn->varv('id', $v); ?>" data-del-input="#filters_del_ids" tabindex="-1"><span class="icon ti-trash"></span></a>
                                   <input type="hidden" name="filters[<?php echo $tr; ?>][id]" id="filters<?php echo $tr; ?>_id" class="form-control" value="<?php echo $fn->varv('id', $v); ?>"/>
                               </td>

                               <td class="relative">
                                   <input type="text" name="filters[<?php echo $tr; ?>][title]" id="filters<?php echo $tr; ?>_title" class="form-control" value="<?php echo $fn->varv('title', $v); ?>" required/>
                               </td>
                               <td class="relative">
                                   <select name="filters[<?php echo $tr; ?>][publish]" id="filters<?php echo $tr; ?>_publish" class="form-control" data-placeholder="Required" required>
                                    <?php echo $fn->show_list(array_reverse($fn->yes_no), $fn->varv('publish', $v), false); ?>
                                   </select>
                               </td>
                           </tr>
                        <?php
                       }
                      }
                     ?>
                     </tbody>
                 </table>
             </div>
          <?php include admin_path . 'inc' . ds . 'panel-footer.php'; ?>
         </div>

     </form>
  <?php
 } else {
  $fn->select_all();
  ?>
     <div class="panel panel-default">
      <?php include 'inc/panel-head.php'; ?>
         <div class="panel-body">
          <?php if ($fn->data) { ?>
              <div class="table-responsive">
                  <table class="table table-striped table-bordered">
                      <thead>
                      <tr>
                          <th width="5%" class="text-center hidden-xs"><?php echo _('#'); ?></th>
                          <th><?php echo _('Category Name'); ?></th>
                          <th><?php echo _('Label'); ?></th>
                          <th><?php echo _('Linked Label'); ?></th>
                          <th><?php echo _('Linked Value'); ?></th>
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
                               <td><?php echo $row['category_name']; ?></td>
                               <td><?php echo $row['title']; ?></td>
                               <td><?php echo $row['linked_label'] != '' ? $row['linked_label'] : '-'; ?></td>
                               <td><?php echo $row['linked_value'] != '' ? $row['linked_value'] : '-'; ?></td>
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
     </div>
  <?php
 }
 include 'inc/footer.php';
 include 'inc/foot.php';
?>
