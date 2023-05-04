<?php
require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'autoload.php';
$fn = new admin\controllers\a_company;
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
?>
<div class="panel panel-default">
    <?php
    include 'inc/panel-head.php';
    if (($fn->per_add && $fn->get('action') == 'add') || ($fn->per_edit && $fn->get('action') == 'edit')) {
        if ($fn->get('action') == 'edit' && $fn->get('id')) {
            $fn->select();
        }
        ?>
        <form id="data-frm" name="data-frm" method="post" class="form-validate" autocomplete="off">
            <div class="panel-body">
                <div class="row">
                    <div class="form-group col-sm-8">
                        <label for="name" class="input-label req"><?php echo _('Name'); ?></label>
                        <input type="text" name="name" id="name" class="form-control"
                               value="<?php echo $fn->post('name'); ?>" autofocus required/>
                    </div>
                    <div class="form-group col-sm-2">
                        <label for="company_email" class="input-label req"><?php echo _('Email'); ?></label>
                        <input type="email" name="email[company]" id="company_email" class="form-control"
                               value="<?php echo $fn->post('email', 'company'); ?>" required/>
                    </div>
                    <div class="form-group col-sm-2">
                        <label for="support_email" class="input-label req"><?php echo _('Support Email'); ?></label>
                        <input type="email" name="email[support]" id="support_email" class="form-control"
                               value="<?php echo $fn->post('email', 'support'); ?>" required/>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group col-sm-12">
                        <label for="address" class="input-label req"><?php echo _('Address'); ?></label>
                        <textarea name="address" id="address" class="form-control" rows="3"
                                  required><?php echo $fn->post('address'); ?></textarea>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group col-sm-4">
                        <label for="phone_no" class="input-label req"><?php echo _('Phone No.'); ?></label>
                        <input type="text" name="phone_no" id="phone_no" class="form-control"
                               value="<?php echo $fn->post('phone_no'); ?>" placeholder="e.g. 123456789, 9898966566"
                               required/>
                    </div>
                    <div class="form-group col-sm-4">
                        <label for="whatsapp_no" class="input-label req"><?php echo _('Whatsapp No.'); ?></label>
                        <input type="text" name="whatsapp_no" id="whatsapp_no" class="form-control"
                               value="<?php echo $fn->post('whatsapp_no'); ?>" placeholder="e.g. 123456789"/>
                    </div>
                    <div class="form-group col-sm-4">
                        <label for="fax_no" class="input-label"><?php echo _('Fax No.'); ?>
                            <small>(Optional)</small></label>
                        <input type="text" name="fax_no" id="fax_no" class="form-control"
                               value="<?php echo $fn->post('fax_no'); ?>" placeholder="e.g. 9898966566"/>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-group col-sm-12">
                        <label for="footer_msg" class="input-label"><?php echo _('Footer Message'); ?> <small>(Optional)
                            </small></label>
                        <textarea name="footer_msg" id="footer_msg" class="form-control" rows="5"
                                  placeholder="Enter Message"><?php echo $fn->post('footer_msg'); ?></textarea>
                    </div>
                </div>
            </div>
            <?php include 'inc/panel-footer.php'; ?>
        </form>
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
                            <th><?php echo _('Name'); ?></th>
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
                                <td><?php echo $row['name']; ?></td>
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
<?php
include 'inc/footer.php';
include 'inc/foot.php';
?>
