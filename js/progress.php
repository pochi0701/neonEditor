<?php
header('Content-type: application/json; charset=utf-8');

$status = apc_fetch('upload_progress_key');
echo json_encode($status);
exit;
?>
