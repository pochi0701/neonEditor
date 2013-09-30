<?php
if( ! isset($_SESSION) ){
    session_start();
}
if( strlen(SID) ){
    $SD1=SID."&";
    $SD2="?".SID;
    $SD3="?".SID."&dmy=1/";
    //print "[aaa$SD2]";
}else{
    $SD1="";//session_name()."=".session_id()."?";
    $SD2="";//"?".session_name()."=".session_id();
    $SD3="";
}
// DB接続（ここで接続したDBのクラスを以下の処理で使用してから切断処理をする）
//-----------------------------
// 設定項目
//-----------------------------
$script1 = "/main/index.php";//htmlspecialchars($_SERVER['PHP_SELF']);             // スクリプト名
if( (! isset($_SESSION['id'])) ||  (! isset($_SESSION['name'])) ){
    //新たにセッションが書き込まれた
    if( isset($_REQUEST['ss'])){
        $ss = $_REQUEST['ss'];
        $session = unserialize(decode($ss));
        $id = $session['id'];
        $name = $session['name'];
        $_SESSION['id'] = $id;
        $_SESSION['name'] = $name;
    }
    //セッションがない。または日切れ
    if( ! isset($id) ) {
        $_SESSION = Array();
        session_destroy();
        header("Location: ./login.php?{$SD1}LP=".urlencode("http://".h($_SERVER['HTTP_HOST']).h($_SERVER['PHP_SELF'])));
        exit;
    }
}else{
    $id = $_SESSION['id'];
    $name = $_SESSION['name'];
}

///////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////
//リクエスト文字列取得
function array_get($arr, $key) {
    return (array_key_exists($key, $arr)) ? trim($arr[$key]) : null;
}
///////////////////////////////////////////////////////////////////////////////////////////////////
function h($str){
    if(is_array($str)){
        return array_map("h",$str);
    }else{
        return htmlspecialchars($str,ENT_QUOTES,'UTF-8');
    }
}
///////////////////////////////////////////////////////////////////////////////////////////////////
//  decode
///////////////////////////////////////////////////////////////////////////////////////////////////
function decode($result){
    // 空白除去（右左両方）
    $result = strtr($result," ","+");
    $work = trim( base64_decode($result) );
    
    $enq = "hiyokopyokopyoko3pyokopyokoawasetepyokopyoko6pyokopyoko";
    $enqlen = strlen($enq);
    
    for( $i = 0 ; $i < strlen( $work ) ; $i++ ){
        //番号の取得
        $num = (ord($work[$i])-ord($enq[$i%$enqlen])+256)%256;
        $work[$i]=chr($num);
    }
    return $work;
}
///////////////////////////////////////////////////////////////////////////////////////////////////
//	encode
///////////////////////////////////////////////////////////////////////////////////////////////////
function	encode($pass){
    $enq	= "hiyokopyokopyoko3pyokopyokoawasetepyokopyoko6pyokopyoko";
    $enqlen     = strlen($enq);
    $work	= $pass;
    for($i=0;$i<strlen(	$pass);$i++){
        $work[$i]=chr((ord($pass[$i])+ord($enq[$i%$enqlen]))%256);
    }
    return	urlencode(base64_encode($work));
}
///////////////////////////////////////////////////////////////////////////////////////////////////
// ランダムな文字列を生成する。
// @param int $nLengthRequired 必要な文字列長。省略すると 8 文字
// @return String ランダムな文字列
///////////////////////////////////////////////////////////////////////////////////////////////////
function getRandomString($nLengthRequired = 4){
    $sCharList = "0123456789";
    $sRes = "";
    for($i = 0; $i < $nLengthRequired; $i++)
    $sRes .= $sCharList{mt_rand(0, strlen($sCharList) - 1)};
    return $sRes;
}
//DB_Connect();
//DB_Execute($SQL);
//for($cnt=0;$data = mysql_fetch_object($GLOBALS["dbexe"]);$cnt++) {
//}
//DB_DisConnect();
?>