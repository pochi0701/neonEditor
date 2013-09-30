<?php
   session_start();
    if( strlen(SID) ){
        $SD1=SID."&";
        $SD2="?".SID;
    }else{
        $SD1="";
        $SD2="";
    }
//---------------------------------------------------------------------------------------
//name,PWDの正当性を調べる
function CheckLogin($name,$pwd){
    $id = NULL;
    if( $name=="guest" && $pwd=="guest"){
        $id = 1;
    }
    return $id;
}
//------------------------------
//  encode                      
//------------------------------
function encode($pass){
        $enq = "jyugemujyugemu";
        $enqlen = strlen($enq);
        $work = $pass;
        for($i = 0 ; $i < strlen( $pass ) ; $i++ ){
            $work[$i] = chr((ord($pass[$i])+ord($enq[$i%$enqlen]))%256);
            //$pass{$i} = $pass{$i};
	}
        return urlencode(base64_encode($work));
}
//------------------------------
//  decode                      
//------------------------------
function decode($result){
	// 空白除去（右左両方）
	$work = trim( base64_decode(urldecode($result)) );

        $enq = "jyugemujyugemu";
        $enqlen = strlen($enq);

	for( $i = 0 ; $i < strlen( $work ) ; $i++ ){
	    //番号の取得
	    $num = (ord($work[$i])-ord($enq[$i%$enqlen])+256)%256;
	    $work[$i]=chr($num);
	}
	return $work;
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
/*
 * 半角英数文字を数へ変換
 * @param $x : 半角英数文字
 */
function atox( $x )
{
	if ('a'<=$x && $x<='f') return (ord($x)-ord('a')+10);
	if ('A'<=$x && $x<='F') return (ord($x)-ord('A')+10);
	if ('0'<=$x && $x<='9') return (ord($x)-ord('0'));
	return 0;
}
function h($str){
    if(is_array($str)){
        return array_map("h",$str);
    }else{
        return htmlspecialchars($str,ENT_QUOTES,'UTF-8');
    }
}
//---------------------------------------------------------------------------------------
    $LP=isset($_REQUEST['LP'])?$_REQUEST['LP']:'index.php';
    $LV=isset($_REQUEST['LV'])?$_REQUEST['LV']:'';
    $ss = NULL;

    //modeとリターンが入ってたらログアウト
    if( isset($_GET['mode']) ){
        if( $_GET['mode'] == "LOGOUT" ){
           if( isset($LP) && strlen($LP)>0 && isset($_SESSION['id']) ){
               $_SESSION = Array();
               session_destroy();
               header("Location: $LP$SD2$LV");
               exit;
           }
        }
    }
    //打ち返す！
    if( isset($_SESSION['id']) && isset($LP) && strlen($LP)>0 ){
        $session['id'] = $_SESSION['id'];
        $session['name'] = $_SESSION['name'];
        $session['dt']   = $_SESSION['dt'];
        $ss = encode(serialize($session));
        header("Location: $LP?{$SD1}ss=$ss$LV");
        exit;
    }
    $submit   = isset($_POST['submit'])  ? $_POST['submit']   :  NULL;
    $name     = isset($_POST['name'])    ? $_POST['name']     :  NULL;
    $pwd      = isset($_POST['pwd'])     ? $_POST['pwd']      :  NULL;
    $name     = preg_replace(array('/[~;\'\"]/','/--/'),'',$name);
    $pwd      = preg_replace(array('/[~;\'\"]/','/--/'),'',$pwd);

    if($submit){
        //ログイン成功なら
        $id = CheckLogin($name,$pwd);
        if( $id>0 ){
            $_SESSION['id']       = $id;
            $_SESSION['name']     = $name;
            //あっちにセッションは作れないが、名前をわたせば済むこと
            if( isset($LP) && strlen($LP)>0 ){
                $session['id'] = $_SESSION['id'];
                $session['name'] = $_SESSION['name'];
                $ss = encode(serialize($session));
                if( ! strpos(dirname($LP),dirname($_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'])) ){
                    header("Location: $LP?{$SD1}ss=$ss$LV");
                    exit;
                }else{
                    header("Location: $LP$SD2$LV");
                    exit;
                }
            }
        }else{
            //失敗なら
            $msg1 = "ＩＤまたはパスワードが違います。<br/>\n";
            $msg2 = "ＩＤ、パスワードを正確に入力して 『ログイン』 ボタンを押してください。<br/>\n";
            $ng=1;
        }
    }
//---------------------------------------------------------------------------------------
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <title>LOGIN</title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body onload="document.form.name.focus();">
<BR/>
  <CENTER>
    <a href="./">TOP</a>
    <br/><br/>
    <form name="form" method="post" action="<?php echo htmlspecialchars($_SERVER['SCRIPT_NAME'])?>">
    <table width="320" border="0" cellpadding="3">
      <tr bgcolor="#808080"><th colspan=2 align=center>
        <font color="black">ログイン</font>
      </th></tr>
      <tr> 
        <td width="130"> 
         <div align="right">ＩＤ</div>
        </td>
        <td> 
         <input type="text" name="name" maxlength="20" size="20" value="<?php echo $name?>">
        </td>
      </tr>
      <tr> 
        <td width="130"> 
          <div align="right">パスワード</div>
        </td>
        <td>
         <input type="password" name="pwd" maxlength="20" size="20">
        </td>
      </tr>
      <tr>
        <td colspan="2"> 
          <div align="center">
            <input type="submit" name="submit" value="ログイン">
            <input type="reset" name="reset" value="リセット">
          </div>
        </td>
      </tr>
      <tr>
        <td colspan="2"><hr></td>
      </tr>
    </table>
    <?
       if( isset($ng) && $ng ){
            print $msg1;
            print $msg2;
       }
    ?>
    <input type = "hidden" name="LP" value="<?php echo $LP?>">
    <input type = "hidden" name="LV" value="<?php echo $LV?>">
    </form>
  </CENTER>
</body>
</html>