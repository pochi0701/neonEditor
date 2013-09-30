<?php
require_once("req.php");
/**
*    Web Editor
*/

$path = array_get($_REQUEST,'path');
$errMsg = '<!-- no error -->';
//保存処理
if( isset($rb) ){
    $bak = $path.".bak";
    copy($bak,$path);
}
//読み込み処理
if (is_file($path)) {
    $code = file_get_contents($path);
    //$code = mb_convert_encoding($code, "UTF-8", "auto");
}
//ファイルタイプ生成
if (!$fileType = array_get($_POST, 'fileType')) {
    if (preg_match('/\.js$/i', $path)) {
        $fileType = 'ace/mode/javascript';
    } else if (preg_match('/\.css$/i', $path)) {
        $fileType = 'ace/mode/css';
    } else if (preg_match('/\.(|r)htm(|l)$/i', $path)) {
        $fileType = 'ace/mode/html';
    } else if (preg_match('/\.rb$/i', $path)) {
        $fileType = 'ace/mode/ruby';
    } else if (preg_match('/\.as$/i', $path)) {
        $fileType = 'ace/mode/as';
    } else if (preg_match('/\.xml$/i', $path)) {
        $fileType = 'ace/mode/xml';
    } else if (preg_match('/\.php$/i', $path)) {
        $fileType = 'ace/mode/php';
    } else {
        $fileType = 'ace/mode/text';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title><?php echo basename($path)?></title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <style type="text/css">
    <!--
    .hidden-code {display:none;}
    .tab {
        text-align: center;
        text-decoration: none;
        color: #FFF;
        margin: 1px 1px 1px 1px;
        padding: 2px 5px 2px 5px;
        background-color: #a0a0ff;
    }
    .tab:hover {
        background-color: #eaebd8;
    }
    #editor {
        position: relative;
        border: 1px solid lightgray;
        margin: auto;
        padding: auto;
        height: 700px;
        width: 97%;
    }
    //-->
  </style>
  <script type="text/javascript">
    <!--
    var hidden = false;
    var filename;
    function loadCode() {
        pushCode(0);
        var frm = document.getElementsByTagName('form')[0];
        frm.submit();
        parent.left.myreload();
    }
    function saveCode() {
        pushCode(1);
        var frm = document.getElementsByTagName('form')[0];
        frm.code.value = editor.getValue();
        frm.submit();
        parent.left.myreload();
        return false;
    }
    function viewCode() {
        var path = document.forma.path.value;
        var root = "<?php echo $_SERVER['DOCUMENT_ROOT']?>";
        var pos = path.indexOf(path,root);
        path = "http://<?php echo $_SERVER['HTTP_HOST']?>"+path.substring(root.length,path.length);
        myWin = window.open(path);
    }
    function checkCode() {
        parent.tmp.location.href=encodeURI("check.php?target="+document.forma.path.value);
    }
    function popCode(idx){
        pushCode(0);
        var list = parent.up.fileList();
        url = list[idx].url;
        var txt = parent.up.fileData(idx);
        //このコードのタイプを設定
        //alert( url2ext(url));
        editor.getSession().setMode(url2ext(url));
        editor.getSession().setValue( txt );
        
        for( var i = 0 ; i < list.length ; i++ ){
            var pop = document.getElementById("e"+i);
            if( pop ){
                pop.text = url2base(list[i].url)+(list[i].change?"*":"");
                if( i == idx ){
                    pop.setAttribute('style','background-color: #ffa0a0');
                }else{
                    pop.setAttribute('style','background-color: #a0a0ff');
                }
            }
        }
        document.forma.path.value = url;
        return;
    }
    function pushCode(mode)
    {
        //親に渡して
        var editor = ace.edit("editor");
        var txt=editor.getValue();
        if( txt.length ){
            parent.up.saveData(document.forma.path.value,txt,1-mode);
        }
        if( mode == 1 )
        {
            //このコードのタイプを設定
            editor.getSession().setMode("<?php echo $fileType ?>");
        }
    }
    //パスからファイル名のみを抜き出し
    function url2base(url){
        var n = url.lastIndexOf("/");
        url = url.substring(n+1).toLowerCase();
        return url;
    }
    //パスからモードを生成
    function url2ext(url){
        var n = url.lastIndexOf(".");
        url = url.substring(n+1).toLowerCase();
        if( url == "js"){
            url = "javascript";
        }else if ( url == "bat"){
            url = "text";
        }
        return "ace/mode/"+url;
    }
    function myClose()
    {
        pushCode(0);
        var list = parent.up.fileList();
        for( var i in list){
            if( document.forma.path.value == list[i].url && list[i].change ){
                if( ! window.confirm(list[i].url+"は変更されています。破棄してよろしいですか？") ){
                    return false;
                }
            }
        }
        parent.up.removeData(document.forma.path.value);
        list = parent.up.fileList();
        if( list.length == 0 ){
            location.href=encodeURI("pawfaliki.php?page=NeonEditor");
        }else{
            location.href=encodeURI("<?php echo  h($_SERVER['SCRIPT_NAME'])?>?path="+list[0].url);
        }
    }
    //左フレームの表示/非表示
    function mytree(){
        hidden = ! hidden;
        id = 'theFrame';
        if( hidden )
        {
            cols = '0,*';
        }else{
            cols = '20%,*'
        }
        frame = window.top.document.getElementById(id);
        if (!frame) {
            return;
        }
        frame.cols = cols;
    }
    function indent()
    {
        location.href=encodeURI("./indent.php?filename="+document.forma.path.value);
    }
    // -->
    </script>
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="css/bootstrap-responsive.min.css" rel="stylesheet"  media="all" />
  </head>
  <body onLoad="pushCode(1);">
    <span style="color:red;"><?php echo $errMsg?></span>
    <form name="forma" action="save.php" method="post" target="tmp">
      <input type="hidden" name="code" />
      <input type="text" name="path" style="width:20em;" value="<?php echo h($path) ?>" />
      <a href="#" onclick="loadCode();" title="Load File(L)" accesskey="L" /><img src="image/file.gif" border="0" /></a>
      <a href="#" onclick="saveCode();" title="Save File(S)" accesskey="S" /><img src="image/save.gif" border="0" /></a>
      <a href="#" onclick="viewCode();" title="View File(V)" accesskey="V" /><img src="image/disp.gif" border="0"/></a>
      <a href="#" onclick="checkCode();" title="Check File(G)" accesskey="G" /><img src="image/check.gif" border="0"/></a>
      <a href="#" onclick="indent();" title="indent(i)" accesskey="I" /><img src="image/indent.gif" border="0" /></a>
      <a href="#" onclick="myClose();" title="close" accesskey="T" /><img src="image/close.gif" border="0" /></a>
      <a href="#" onclick="mytree();" title="treeView" accesskey="H" /><img src="image/frame.gif" border="0" /></a>
    </form>
    <div id="searchLists"></div>
    <pre id="editor"><?php echo h($code)?></pre>

    <!-- SCRIPTS //-->
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="src-noconflict/ace.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript">
      <!--
      var editor = ace.edit("editor");
      editor.setTheme("ace/theme/textmate");
      //タブを作るために空セーブ
      parent.up.saveData('<?php echo h($path)?>','',0);
      var list = parent.up.fileList();
      /* 親ノード */
      var parentObj=document.getElementById("searchLists");
      for( var i in list){
          /* 要素を生成 */
          var listObj=parentObj.appendChild(document.createElement("A"));
          /* a要素のhref属性にリンク先URLを設定 */
          listObj.setAttribute("href", "#");
          /* a要素のtitle属性にリンクテキストを設定 */
          listObj.setAttribute("title", list[i].url);
          /* a要素のtitle属性にリンクテキストを設定 */
          listObj.setAttribute("id", "e"+i);
          /* a要素のtitle属性にリンクテキストを設定 */
          listObj.setAttribute("class", "tab");
          listObj.setAttribute("onclick", "popCode("+i+")");
          if( list[i].url == '<?php echo h($path)?>' ){
              listObj.setAttribute('style','background-color: #ffa0a0');
          }
          /* a要素のリンクテキストをテキストノードとして追加 */
          listObj.appendChild(document.createTextNode(url2base(list[i].url)));
      }
    //--></script>
  </body>
</html>