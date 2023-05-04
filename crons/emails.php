<?php
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'autoload.php';
$fn = new \controllers\controller(false, true, false);
$fn->send_cron_email();
echo "Emails Sent";
