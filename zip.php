<?php
function createZip($addr,$file)
{
    //この中にファイルを全部入れておく。サブディレクトリなどあってもOK
    $tempDir = "/tmp";//作業用ディレクトリ;
    //ここにzipファイルを作ります
    $filepath = "{$tempDir}/{$file}";//生成するzipファイルのパス;
    //このコマンドを
    //$command = "zip -r  {$filepath} {$addr}";
    $command = "tar zcvf {$filepath} {$addr}";
    //実行します
    exec($command);
    return $filepath;
}
$addr = isset($_GET["path"])?rtrim($_GET["path"],"/"):"";
if( strlen($addr) > 0 ){
    $tempname=md5(uniqid(rand(), true));
    //$tempname .= '.zip';
    $tempname .= '.tar.gz';
    $path = createZip($addr,$tempname);
    
    // ダウンロードさせるファイル名
    $tmp_file = $path;
    $j_file   = $tempname;
    // ヘッダ
    header("Content-Type: application/octet-stream");
    // ダイアログボックスに表示するファイル名
    header("Content-Disposition: attachment; filename=$j_file");
    // 対象ファイルを出力する。
    readfile($tmp_file);
    //削除
    unlink( $tmp_file);
    exit;
}else{
    print "圧縮するファイルがみつかりません。";
}

?>
