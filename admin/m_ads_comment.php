<?php
require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'autoload.php';
$fn = new admin\controllers\m_ads_comment;
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
    echo $fn->json_encode($json);
    exit();
}
if ($fn->post('btn_update') == 'update') {
    try {
        $fn->insert();
        $fn->session_msg('Data has been saved successfully!', 'success');
        $fn->return_ref();
    } catch (Exception $ex) {
        $fn->session_msg($ex->getMessage(), 'error');
    }
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
include 'inc/head.php';
include 'inc/header.php';
if ((in_array($fn->get('action'), ['view']))) {
    if ($fn->get('id')) {
        $fn->select();
    }
    ?>
    <form id="data-frm" name="data-frm" method="post" class="form-validate" autocomplete="off"
          enctype="multipart/form-data">
        <input type="hidden" name="blog_id" value="<?php echo $fn->post('blog_id'); ?>"/>
        <div class="panel panel-default">
            <?php include 'inc/panel-head.php'; ?>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Ad : </strong><a href="<?php echo $fn->permalink('ad/' . $fn->url_string($fn->post('ad_title')) . '/' . $fn->post('ad_id'), [], true); ?>"
                                                    target="_blank"><?php echo $fn->post('ad_title'); ?></a></p>
                        <p><strong>Name : </strong><?php echo $fn->post('name'); ?></p>
                        <p><strong>Email : </strong><a
                                    href="mailto:<?php echo $fn->post('email'); ?>"><?php echo $fn->post('email'); ?></a>
                            <label class="label label-<?php echo $fn->post('verified') == 'Y' ? 'success' : 'danger'; ?>"><?php echo $fn->post('verified') == 'Y' ? 'Verified' : 'Not Verified'; ?></label>
                        </p>
                        <p><strong>Phone : </strong><?php echo $fn->post('phone'); ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Date
                                : </strong> <?php echo $fn->dt_format($fn->post('add_date'), 'F d, Y h:i A'); ?></p>
                        <p><strong>IP Address : </strong><?php echo $fn->post('ip'); ?></p>
                        <p><strong>Browser/OS : </strong><?php echo $fn->post('browser'); ?>/<?php echo $fn->post('os'); ?></p>
                        <p><strong>Publish : </strong><label
                                    class="label label-<?php echo $fn->post('publish') == 'Y' ? 'success' : 'danger'; ?>"><?php echo $fn->yes_no[$fn->post('publish')]; ?></label>
                        </p>
                    </div>
                    <div class="col-md-12">
                        <p><strong>Comment :</strong><?php echo $fn->post('comment'); ?></p>
                    </div>
                </div>
            </div>
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
                            <th width="10%"><?php echo _('Date'); ?></th>
                            <th><?php echo _('Name'); ?></th>
                            <th><?php echo _('Email'); ?></th>
                            <th><?php echo _('Phone'); ?></th>
                            <th><?php echo _('Comment'); ?></th>
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
                                <td><?php echo $row['add_date']; ?></td>
                                <td><?php echo $row['name']; ?></td>
                                <td><?php echo $row['email']; ?></td>
                                <td><?php echo $row['phone']; ?></td>
                                <td><?php echo $row['comment']; ?></td>
                                <td align="center">
                                    <div id="publish-<?php echo $row['id']; ?>">
                                        <?php echo $fn->get_view('button', 'YES_NO', array('status' => $row['publish'], 'id' => $row['id'], 'action' => 'publish')); ?>
                                    </div>
                                </td>
                                <?php ob_start(); ?>
                                <a href="<?php echo $fn->get_action_url('view', $row['id']); ?>"
                                   class="btn btn-sm btn-info"><span class="icon
        ti-eye"></span> <?php
                                    echo _('View'); ?></a>
                                <?php
                                $fn->actions_multi = ob_get_clean();
                                include 'inc/actions.php';
                                ?>
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
