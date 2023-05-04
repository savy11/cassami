<?php
require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'autoload.php';
$fn = new admin\controllers\m_ads;
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
    if ($fn->post('action') == 'approved') {
        try {
            $field = $fn->post('field');
            $fn->approved();
            $status = 'unapproved';
            if ($fn->post('approved') == 'Y') {
                $status = 'approved';
            }
            $json = array('success' => true, 'html' => $fn->get_view('button', 'COMMON', array($field => $fn->post($field), 'field' => $field, 'id' => $fn->post('id'), 'action' => $fn->post('action'))), 'g_title' => $fn->page['name'], 'g_message' => 'Data has been ' . $status . ' successfully!');
        } catch (Exception $ex) {
            $json = array('error' => true, 'g_title' => $fn->page['name'], 'g_message' => $ex->getMessage());
        }
    }
    if ($fn->post('action') == 'promoted') {
        try {
            $field = $fn->post('field');
            $fn->promoted();
            $json = array('success' => true, 'html' => $fn->get_view('button', 'COMMON', array($field => $fn->post($field), 'field' => $field, 'id' => $fn->post('id'), 'action' => $fn->post('action'))), 'g_title' => $fn->page['name'], 'g_message' => 'Data has been updated successfully!');
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
                    <div class="col-sm-12 table-responsive">
                        <table class="table table-striped table-hover">
                            <tbody>
                            <tr>
                                <th>Date</th>
                                <td><?php echo $fn->date_format($fn->post('add_date'), 'F d, Y H:i A'); ?></td>
                                <th>User</th>
                                <td><?php echo $fn->post('display_name'); ?></td>
                            </tr>
                            <tr>
                                <th>Ad Category</th>
                                <td><?php echo $fn->post('cat'); ?></td>
                                <th>Ad Sub Category</th>
                                <td><?php echo $fn->post('subcat'); ?></td>
                            </tr>
                            <tr>
                                <th>Ad Title</th>
                                <td><?php echo $fn->post('ad_title'); ?></td>
                                <th>Ad Price</th>
                                <td><?php echo $fn->show_price($fn->post('ad_price')); ?></td>
                            </tr>
                            <tr>
                                <th>Contact No.</th>
                                <td><?php echo $fn->post('ad_no'); ?></td>
                                <th>Contact Email</th>
                                <td><?php echo $fn->post('ad_email'); ?></td>
                            </tr>
                            <tr>
                                <th>Location</th>
                                <td><?php echo $fn->post('ad_city') . ', ' . $fn->post('ad_state') . ', Nigeria'; ?></td>
                                <th>Ad Keywords</th>
                                <td><?php echo $fn->post('ad_keywords'); ?></td>
                            </tr>
                            <tr>
                                <th>Ad Description</th>
                                <td><?php echo $fn->post('ad_desc'); ?></td>
                                <th>Promoted</th>
                                <td id="promoted-<?php echo $fn->post('id'); ?>" style="vertical-align: top;">
                                    <?php echo $fn->get_view('button', 'COMMON', array('promoted' => $fn->post('promoted'), 'id' => $fn->post('id'), 'action' => 'promoted', 'field' => 'promoted')); ?></td>
                            </tr>
                            <tr>
                                <th>Approved</th>
                                <td id="approved-<?php echo $fn->post('id'); ?>">
                                    <?php echo $fn->get_view('button', 'COMMON', array('approved' => $fn->post('approved'), 'id' => $fn->post('id'), 'action' => 'approved', 'field' => 'approved')); ?></td>
                                <th>Publish</th>
                                <td id="publish-<?php echo $fn->post('id'); ?>">
                                    <?php echo $fn->get_view('button', 'YES_NO', array('status' => $fn->post('publish'), 'id' => $fn->post('id'), 'action' => 'publish')); ?></td>

                            </tr>
                            <tr>
                                <th>Ad Gallery</th>
                                <td colspan="3">
                                    <?php if ($fn->post('ads')) {
                                        foreach ($fn->post('ads') as $k => $v) {
                                            ?>
                                            <a href="<?php echo $fn->get_file($v['meta_value']); ?>" class="magnific-gallery">
                                                <img src="<?php echo $fn->get_file($v['meta_value'], 0, 0, 200); ?>" alt="Ad Image <?php echo $v['id']; ?>"/>
                                            </a>
                                            <?php
                                        }
                                    } ?>
                                </td>
                            </tr>
                            </tbody>
                        </table>

                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Features/Filters</h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <?php if ($fn->post('filters')) {
                                foreach ($fn->post('filters') as $k => $v) {
                                    ?>
                                    <p class="col-sm-6"><strong><?php echo $v['label']; ?>: </strong><?php echo $v['value']; ?></p>
                                    <?php
                                }
                            } ?>
                        </div>
                    </div>
                </div>
            </div>
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
                            <th><?php echo _('Ad Title'); ?></th>
                            <th width="6%"><?php echo _('Ad Price'); ?></th>
                            <th width="5%"><?php echo _('Contact No'); ?></th>
                            <th width="5%"><?php echo _('Contact Email'); ?></th>
                            <th width="5%" class="text-center"><?php echo _('Approved'); ?></th>
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
                                <td><?php echo $row['ad_title']; ?></td>
                                <td><?php echo $fn->show_price($row['ad_price']); ?></td>
                                <td><?php echo $row['ad_no']; ?></td>
                                <td><?php echo $row['ad_email']; ?></td>
                                <td align="center">
                                    <div id="approved-<?php echo $row['id']; ?>">
                                        <?php echo $fn->get_view('button', 'COMMON', array('approved' => $row['approved'], 'id' => $row['id'], 'action' => 'approved', 'field' => 'approved')); ?>
                                    </div>
                                </td>
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
