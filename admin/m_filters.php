<?php
 require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'autoload.php';
 $fn = new admin\controllers\m_filters;
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
     <form id="data-frm" name="data-frm" method="post" class="form-validate" autocomplete="off"
           enctype="multipart/form-data">
         <div class="panel panel-default">
          <?php include 'inc/panel-head.php'; ?>
             <div class="panel-body">
                 <div class="row">
                     <div class="form-group col-sm-4">
                         <label for="category_id" class="input-label req"><?php echo _('Category'); ?></label>
                         <select name="category_id" id="category_id" class="form-control" data-placeholder="Category" data-ajaxify="true" data-url="combos" data-type="category" data-event="change" data-recid="sub_category_id" required>
                          <?php echo $fn->show_list($fn->get_ads_cat(), $fn->post('category_id'), true); ?>
                         </select>
                     </div>
                     <div class="form-group col-sm-4">
                         <label for="sub_category_id" class="input-label req"><?php echo _('Category'); ?></label>
                         <select name="sub_category_id" id="sub_category_id" class="form-control" data-placeholder="Sub Category" required>
                          <?php echo $fn->show_list($fn->get_ads_subcat($fn->post('category_id')), $fn->post('sub_category_id'), true); ?>
                         </select>
                     </div>
                     <div class="form-group col-sm-4">
                         <label for="publish" class="input-label req"><?php echo _('Publish'); ?></label>
                         <select name="publish" id="publish" class="form-control" data-placeholder="Publish">
                          <?php echo $fn->show_list($fn->yes_no, $fn->post('publish'), false); ?>
                         </select>
                     </div>
                 </div>
             </div>
         </div>

         <div class="panel panel-default">
             <div class="panel-heading">
                 <h3 class="panel-title">Filters</h3>
             </div>
             <div class="panel-body">
                 <input type="hidden" name="filters_del_ids" id="filters_del_ids" class="form-control"/>
                 <table class="table table-bordered multi-table grid mb-15" cellpadding="0" cellspacing="0"
                        data-btn-add="filters_row_add" data-btn-delete="filters_row_delete" data-row-index="<?php echo count($fn->post('filters')) - 1; ?>" data-grid-body="filters_grid_body">
                     <thead>
                     <tr>
                         <th width="5%" class="text-center">
                             <a href="#" class="btn btn-success btn-sm" rel="filters_row_add" tabindex="-1"><span class="icon ti-plus"></span></a>
                         </th>
                         <th class="req">Title</th>
                         <th>Placeholder</th>
                         <th class="req">Type</th>
                         <th class="req">Required</th>
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
                                   <input type="text" name="filters[<?php echo $tr; ?>][placeholder]" id="filters<?php echo $tr; ?>_placeholder" class="form-control" value="<?php echo $fn->varv('placeholder', $v); ?>"/>
                               </td>
                               <td class="relative">
                                   <select name="filters[<?php echo $tr; ?>][type]" id="filters<?php echo $tr; ?>_type" class="form-control" data-placeholder="Type" required>
                                    <?php echo $fn->show_list($fn->filter_type, $fn->varv('type', $v), false); ?>
                                   </select>
                               </td>
                               <td class="relative">
                                   <select name="filters[<?php echo $tr; ?>][req]" id="filters<?php echo $tr; ?>_req" class="form-control" data-placeholder="Required" required>
                                    <?php echo $fn->show_list(array_reverse($fn->yes_no), $fn->varv('req', $v), false); ?>
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
          
          <?php if ($fn->data) {
           ?>
              <div class="table-responsive">
                  <table class="table table-striped table-bordered">
                      <thead>
                      <tr>
                          <th width="5%" class="text-center hidden-xs"><?php echo _('#'); ?></th>
                          <th width="20%"><?php echo _('Category Name'); ?></th>
                          <th><?php echo _('Sub Category Name'); ?></th>
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
                               <td><?php echo $row['category_name']; ?></td>
                               <td><?php echo $row['sub_category_name']; ?></td>
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
     </div>
  <?php
 }
 include 'inc/footer.php';
 include 'inc/foot.php';
?>
