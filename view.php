<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta content="text/html; charset=UTF-8" http-equiv="Content-Type" />
<title>画像一覧</title>
</head>
<body>
 <?php
//画像ファイル一覧を表示するパスを指定する
$path = isset($_GET['path'])?$_GET['path']:".";
$home = explode($_SERVER['DOCUMENT_ROOT'],$path);
$home = "http://{$_SERVER['HTTP_HOST']}{$home[1]}";

//パスの表示
print("ディレクトリ「<b>".$path."</b>」の画像ファイル一覧<br><br>");

//文法  scandir( パス ,[ ソート順 ] )
//scandir
//ソート順が省略された場合は、アルファベットの昇順にソートする
//ソート順に"1"が指定された場合は、アルファベットの降順にソートする
//
//昇順にソートする場合はソート順を省略する
//$array = scandir($path);

//降順にソートする場合はソート順に"1"を指定する
$array = scandir($path,1);

//count命令により配列の個数を取得する
$num = count($array);

//HTML文を出力　テーブルの開始を指定
print("<table><tr>");

//横に並べる画像の最大数を設定する
$max = 5;

//カウント数の初期化
$cnt = 0;

//配列の数だけ繰り返す
for ($i=0;$i<$num;$i++){
	//$filenameにァイル名を設定
	$filename = $home . "/".$array[$i];


	//ファイル名の拡張子が｢gif｣または｢GIF｣または｢jpg｣または｢JPG｣
	//または｢JPEG｣または｢png｣または｢PNG｣の場合は実寸表示の
	//リンク付きで画像を表示する
	
	if  (Eregi('gif$', $filename) OR 
		 Eregi('jpg$', $filename) OR 
		 Eregi('jpeg$',$filename) OR 
		 Eregi('png$', $filename)) {
		print("<td><a href=\"{$filename}\"><img src = \"{$filename}\" title=\"{$filename}\" width=\"128\" border=\"0\"><br/>{$filename}</a></td>");
		
		//カウント数の初期化
		$cnt = $cnt + 1;
		
		//カウント数の判定 最大数以上の場合は改行し、カウントを初期化する
		if ($cnt >= $max) {
			print("</tr><tr>");
			$cnt = 0;
		}
	}
}
//HTML文を出力　テーブルの終了を指定
print("</tr></table>");

?>
</body>
</html>