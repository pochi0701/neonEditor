<?php
session_start();
require_once("req.php");
/**
*    File Tree for Web Editor
*/
//URLエンコード
function u($str) {
    return urlencode($str);
}

function size_num_read($size) {
    $bytes = array('B','KB','MB','GB','TB');
    foreach($bytes as $val) {
        if($size > 1024){
            $size = $size / 1024;
        }else{
            break;
        }
    }
    //return "<span style=\"font-size: small\">".	round($size, 2).$val."</span>";
    return round($size, 2).$val;
}
//変数取得
$base =  $_SERVER['DOCUMENT_ROOT'];
$cd   = array_get($_SESSION,'cd');
$root = array_get($_REQUEST, "root");
$newDir = array_get($_REQUEST, "dirName");
$newFile = array_get($_REQUEST, "FileName");
$del = array_get($_REQUEST,"del");
$renf = array_get($_REQUEST,"renf");
$rent = array_get($_REQUEST,"rent");
$vaf  = array_get($_REQUEST,"vaf");
$search = array_get($_REQUEST,"search");
$cd=1;


//ユーザ限定処理
//初回の処理
if( strlen($root)==0){
    $root = $_SERVER["DOCUMENT_ROOT"];
}
//ファイル削除
if( strlen($del)>0 ){
    if( is_file($del) ){
        unlink($del);
    }else if ( is_dir($del) ){
        rmdir($del);
    }
    //リネーム
}else if( strlen($renf)>0 && strlen($rent)>0 ){
    if( file_exists($renf) && (! file_exists($rent))){
        rename($renf,$rent);
    }else if ( file_exists($rent) ){
        print "すでに同名のファイルが存在します";
    }
    //パス名取得、作成
}else if (is_dir($root) && strlen($newDir)>0 ) {
    $path = "{$root}/{$newDir}";
    if (!file_exists($path)){
        mkdir($path);
    }
    $root = $path;
    //ファイル名取得、作成
}else if (is_dir($root) && strlen($newFile)>0 ) {
    $path = "{$root}/{$newFile}";
    if (!file_exists($path)){
        touch($path);
    }
    //$root = $path;
}
//右端の/をなくす
$root=rtrim($root,"/");
$me  =h($_SERVER['SCRIPT_NAME']);
?>
<!DOCTYPE html>
<html>
<head>
<title>file tree - <?php echo h($root); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
<link href="tree.css" rel="stylesheet" type="text/css" />
<link href="css/bootstrap-responsive.min.css" rel="stylesheet"  media="all" />

<script type="text/javascript">
<!--
var root = "<?php echo h($root);?>";
function viewCode(url) {
    if( parent.right.pushCode){
        parent.right.pushCode(0);
    }
    parent.right.location.href=encodeURI(url);
    return false;
}
function viewData(path) {
    var base = "<?php echo $base;?>";
    //var pos = path.indexOf(path,root);
    path = "http://<?php echo $_SERVER['HTTP_HOST']?>"+path.substring(base.length,path.length);
    myWin = window.open(path);
}
function deleteFile(url,file) {
    if( window.confirm(file+"を削除します。よろしいですか？") ){
        location.href=encodeURI(url+"&root="+root);
    }
    return false;
}
function renameFile(url,file) {
    msg = window.prompt("変更するファイル名を入力してください",file);
    if( msg.length>0 && window.confirm(file+"を"+msg+"に変更します。よろしいですか？") ){
        location.href=encodeURI(url+"&rent="+root+"/"+msg+"&root="+root);
    }
    return false;
}
function createDir() {
    msg = window.prompt("作成するフォルダ名を入力してください");
    if( msg.length>0 && window.confirm("フォルダ"+msg+"を<?php echo $root?>に作成します。よろしいですか？") ){
        location.href=encodeURI("<?php echo $me?>?dirName="+msg+"&root="+root);
    }
    return false;
}
function createFile() {
    msg = window.prompt("作成するファイル名を入力してください");
    if( msg.length>0 && window.confirm("ファイル"+msg+"を<?php echo $root?>に作成します。よろしいですか？") ){
        location.href=encodeURI("<?php echo $me;?>?FileName="+msg+"&root="+root);
    }
    return false;
}
function myreload(num) {
    location.href=encodeURI("<?php echo $me;?>?root="+root+"&vaf="+num);
}
function dl() {
    msg1 = window.prompt("ダウンロードするＵＲＬを入力してください","");
    msg2 = root;
    msg3 = "./dl.php?target="+msg2+"&dl="+msg1;
    if( msg1.length>0){
        if( window.confirm(msg1+"をダウンロードします。よろしいですか？") ){
            parent.tmp.location.href=encodeURI(msg3);
        }
    }
}
function todo() {
    var Window_Option='titlebar=no,menubar=no,toolbar=no,location=no,scrollbars=no,status=no,resizable=no,width=500,height=200';
    var W1;
    W1=window.open("","mwin",Window_Option);
    W1.location.href='/main/todo.php';//NN対策
}
function expt() {
    //ZIPを作って
    var W1;
    if( window.confirm(root+"を圧縮してダウンロードします。よろしいですか？") ){
        location.href= "zip.php?path="+root;
    }
    return false;
}
function view() {
    var W1;
    W1=window.open("","_blank");
    W1.location.href= "view.php?path="+root;
    //parent.right.location.href=encodeURI("./view.php?path="+root);
    return false;
}

function memo() {
    var W1;
    W1=window.open("","_blank");
    W1.location.href= "data:text/html, <html contenteditable>";
    return false;
}

function logout() {
    $_SESSION = array(); //すべてのセッション変数を初期化
    session_destroy(); //セッションを破棄
    top.window.close();
    location.href=encodeURI("login.php?mode=LOGOUT");
    return true;
}
function sea() {
    msg1 = window.prompt("検索する文字列を入力してください","");
    if( msg1.length>0){
        location.href=encodeURI("<?php echo $me?>?root="+root+"&vaf=1&search="+msg1);
    }
}
// -->
</script>
</head>
<body>
<div class="container">
  <div class="btn-toolbar">
    <div class="btn-group">
    <button class="btn dropdown-toggle btn-small" data-toggle="dropdown">Action <span class="caret"></span></button>
    <ul class="dropdown-menu">
    <li><a href="#" onClick="myreload(0);">再読込</a></li>
    <li><a href="#" onClick="createDir();">フォルダ作成</a></li>
    <li><a href="#" onClick="createFile();">ファイル作成</a></li>
    <li><a href="upload.php?folder=<?php echo  u($root."/") ?>" target="_blank">アップロード</a></li>
    <li><a href="#" onClick="dl();">ダウンロード</a></li>
    <li><a href="#" onClick="view();">グラフィック表示</a></li>
    <li><a href="pawfaliki.php" target="_blank">Ｗｉｋｉ</a></li>
    <li><a href="https://neon.cx/phpMyAdmin/" target="_blank">PHPMyAdmin</a></li>
    <li><a href="env.php" target="_blank">環境変数</a></li>
    <li><a href="#" onClick="myreload(1);">全表示</a></li>
    <li><a href="#" onClick="sea();">検索</a></li>
    <li><a href="#" onClick="expt();return false;">エクスポート</a></li>
    <li><a href="phpinfo.php" target="_blank">PHP情報</a></li>
    <li><a href="#" onClick="memo();return false;">メモ</a></li>
    <li class="divider"></li>
    <li><a href="#" onClick="logout();">ログアウト</a></li>
    </ul>
    </div>
  </div>
  [<?php echo h($root);?>]
  <table border="0">
  <?php
  //親ディレクトリ
  $filePath = dirname($root);
  if ( ($filePath != ""  && (!(strpos($filePath,$base)===false)))) {
      $url = "?root=".u($filePath);
      print "<tr><td><a href=\"{$url}\" class=\"lnk\">[<img src=\"image/up.gif\" border=\"0\">]</a></td><td></td></tr>\n";
  }

  //ディレクトリの場合
  if (is_dir($root)) {
      //ディレクトリ読み込み
      $files = scandir($root,0);
      for( $i=count($files)-1; $i>=0 ;$i--){
          if( $files[$i] == "." || $files[$i] == ".." ){
              array_splice($files,$i,1);
          }
      }
      if( count($files)>0){
          //sort($files);
          //個別フォルダチェック
          foreach ($files as $file) {
            $filePath = "{$root}/{$file}";
              if( is_dir($filePath) ){
                  //リンク作成
                  $title = date("Y/m/d H:i:s", filemtime($filePath));
                  $url1 = "?root=". u($filePath);
                  $url2 = "?root={$filePath}&del={$filePath}";
                  $url3 = "?root={$filePath}&renf={$filePath}";
                  print "<tr><td><a href=\"{$url1}\" class=\"lnk\" title=\"{$title}\">[".h($file)."]</a></td>";
                  //print "<td>".date("m/d H:i:s", filemtime($filePath))."</td>"; 
                  //print "<td></td>";
                  print "<td><a href=\"#\" onClick=deleteFile(\"{$url2}\",\"{$file}\") title=\"Delete Folder\"><img src=\"image/trash.gif\" border=\"0\"/></a></td>";
                  print "<td><a href=\"#\" onClick=renameFile(\"{$url3}\",\"{$file}\") title=\"Rename Folder\"><img src=\"image/rename.gif\" border=\"0\"/></a></td></tr>\n";
              }
          }
          //個別ファイルチェック
          foreach ($files as $file) {
              $filePath = "{$root}/{$file}";
                if( ! is_dir($filePath) ){
                  $data = pathinfo($filePath);
                  $ext = isset($data['extension'])?$data['extension']:""; //拡張子
                  if( $vaf==0 && (in_array(strtolower($ext),array("bak")))){
                      continue;
                  }
                  //リンク作成
                  $title = date("Y/m/d H:i:s", filemtime($filePath))."  ".size_num_read(filesize($filePath));

                  $url1 = "editor.php?path={$filePath}";
                  $url2 = "{$me}?del={$filePath}";
                  $url3 = "{$me}?renf={$filePath}";
                  $url4 = "cpl.php?target={$filePath}";
                  $lnk = "lnk";
                  if( ! in_array($ext,array("swf","jpg","gif","png"))){
                      if( strlen($search)>0){
                          $fil = file_get_contents($filePath);
                          if( strpos($fil,$search)===false){
                          }else{
                              $lnk = "lnkf";
                          }
                      }
                      print "<tr><td><a href=\"#\" class=\"{$lnk}\" title=\"{$title}\" onClick=viewCode(\"{$url1}\") >". h($file)."</a></td>";
                  }else{
                    
                      print "<tr><td><a href=\"#\" class=\"lnk\" title=\"{$title}\" onClick=viewData(\"{$filePath}\") >". h($file)."</a></td>";
                  }
                  //print "<td>".date("m/d H:i:s", filemtime($filePath))."</td>"; 
                  //print "<td align=\"right\">".size_num_read(filesize($filePath))."</td>";
                  print "<td><a href=\"#\" onClick=deleteFile(\"{$url2}\",\"{$file}\") title=\"Delete File\"><img src=\"image/trash.gif\" border=\"0\"/></a></td>";
                  print "<td><a href=\"#\" onClick=renameFile(\"{$url3}\",\"{$file}\") title=\"REname File\"><img src=\"image/rename.gif\" border=\"0\"/></a></td>";
                  if( $ext == "as" ){
                      print "<td><a href=\"#\" onClick=viewCode(\"{$url4}\") >Cpl</a></td>";
                  }
                  print "</tr>\n";
              }
          }
      }
  }
  print "</table>\n";
  print "<hr/>";
  ?>
  <script src="js/jquery.js"></script>
  <script src="js/bootstrap.min.js"></script>
</div>
</body>
</html>
