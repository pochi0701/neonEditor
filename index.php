<?php
require_once("req.php");
?>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=2,user-scalable=yes">
<meta name="format-detection" content="telephone=no" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<title>NEON EDITOR</title>
</head>
<frameset rows="0,0,*" border="0">
<frame src="blank.html" name="tmp" border="0">
<frame src="data.html" name="up" border="0">
<frameset cols="20%,*" id="theFrame" bordercolor="#000" border="2">
<frame src="tree.php"  name="left">
<frame src="pawfaliki.php?page=NeonEditor" name="right">
</frameset>
</frameset>
<noframes>
このページはフレーム対応のブラウザでご覧ください。
<a href="tree.php<?php echo $SD2?>">ノーフレーム</a>
</noframes>
</html>
