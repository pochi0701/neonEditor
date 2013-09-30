<?php
require_once("req.php");

$path = array_get($_REQUEST,'path');
$errMsg = '<!-- no error -->';
if ($code = array_get($_POST, 'code')) {
    //bakファイル作成
    $bak = $path.".bak";
    if( file_exists($path) ){
        copy($path,$bak);
    }
    //保存
    if (file_put_contents($path, $code) === false) {
        print "<html><head></head><body><script type=\"text/javascript\">alert( 'error! (not saved)' );</script></body><html>";
    }else{
        print "<html><head></head><body><script type=\"text/javascript\">alert( '{$path} saved!' );</script></body><html>";
    }
    //読み込み処理
}
?>