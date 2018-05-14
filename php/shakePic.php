<?php
session_start();
date_default_timezone_set('Asia/Tokyo');

function deleteFile(){

	$timeLimit = strtotime("1 minutes ago");//今より30分後の時間を取得
	$dir = dirname(__FILE__).'/out/';//削除対象のディレクトリ 
	
	$listFile = scandir($dir);//削除対象のディレクトリ内のファイルを検索し配列で返す。
	
	foreach($listFile as $hoge){
		$dirFile = $dir . $hoge;//削除ファイル
		if(!is_file($dirFile)){
			continue;//次へ
		}//見つかったファイルがファイルであるか？ファイルでなければ削除しない( ´_ゝ｀)
		$timeFile = filemtime($dirFile);//ファイルの最終更新日を取得
		if($timeFile < $timeLimit){//最終更新日
			unlink($dirFile);
		}
	}
}

function animateGif($files , $out , $delay , $loop ){
	
	$imgSize = new Imagick();
	$imgSize -> pingImage($files);
	$imgWidth=$AimgWidth = $imgSize->getImageWidth();
	$imgHeight=$AimgHeight = $imgSize->getImageHeight();

		$file = new Imagick($files);//画像読み込みfu~↑
		$file -> setFormat('gif');
		if($imgWidth > 640){
			$file->thumbnailImage(640, 0);
			$AimgHeight = $imgHeight * (640/$imgWidth);
			$AimgWidth  =640;			
		}else if($imgHeight > 640){
			$file->thumbnailImage(0, 640);
			$AimgWidth = $imgWidth * (640/$imgHeight);
			$AimgHeight  =640;	
		}

	$anime = new Imagick();
	$anime -> setFormat('gif');
	$gif = new Imagick();
	
	$time = date("YmjHi");
	$name= hash('MD5',$time);//ファイル名をハッシュ値にして出力ファイル名に採用
	$fileName = $name.'.gif';
	
	for($i=0; $i<10; $i++){//filesの要素をfileに入れて処理に使用
		
		$randX = rand(0,20);
		$randY = rand(0,20);
		
		$gif->newImage($AimgWidth+20, $AimgHeight+20, new ImagickPixel('white'),'gif');
		
		$file = new Imagick($files);//画像読み込みfu~↑
		$file -> setFormat('gif');
		if($imgWidth > 640){
			$file->thumbnailImage(640, 0);
		}else if($imgHeight > 640){
			$file->thumbnailImage(0, 640);
		}
		
		$gif->compositeImage($file, Imagick::COMPOSITE_DEFAULT,$randX,$randY);
		
		$gif->cropImage($AimgWidth-20,$AimgHeight-20, 20 , 20);
		$gif->setImagePage($AimgWidth-20,$AimgHeight-20, 0, 0);//GIFをリサイズするときはこれを入れないとリサイズされねぇ！FUCK
		
		$gif -> setImageDelay($delay);//一コマの画像を表示させる時間
		$gif -> setImageIterations($loop);//何回ループさせるか？
		$anime ->addImage($gif);//画像リストに画像を加える
		$gif -> destroy();
		$file -> destroy();
	
	}
	
	
	$anime -> writeImages("out/$fileName", true); //画像ファイルに出力(書き込む) アニメGIFなのでwriteImages
	/*$image = new Imagick('testOut.gif');
	header('Content-type: image/gif');
	header("Content-Disposition: inline; filename=image.gif");
	echo $image;こういうやり方は無理。アニメGIFを表示できない*/
	
	$_SESSION['fuck'] = $fileName;
	
	$anime -> destroy();
	
	//echo ('<img src="out/'.$fileName.'">');//変数展開( ´_ゝ｀)
	
	unlink($files);
	
	
}

deleteFile();//out内の不要なファイルを一掃
//ここから
$inFile = $_FILES['my_img']['tmp_name'];

$name =$_FILES['my_img']["name"];

$size =$_FILES['my_img']["size"];

//$error = $_FILES['my_img']["error"];

$end=0;

// ファイルアップロードの処理
if($size <= 512000 )
{


$ext = substr($name, -4);
$fileName= substr($inFile,-6);
if ( $ext == '.png' || $ext == '.jpg' ) {
	$filePath = "img/{$fileName}{$ext}";
	move_uploaded_file($inFile, $filePath);
	
	$changeImg = new Imagick();

	$changeImg -> readImage($filePath);

	$changeImg -> setImageFormat("gif");
	
	$changeImg -> writeImage("img/{$fileName}.gif");
	
	unlink($filePath);
	
}else if($ext == '.gif'){
	$filePath = "img/{$fileName}{$ext}";
	move_uploaded_file($inFile, $filePath);
}else {
	
	$end=1;
	
}

if($end == 1){
	
echo '<script type="text/javascript"> alert( "エラー ファイルの拡張子(.jpg .png .gif は可)やファイルサイズを見なおしてください. " ) </script>';
echo '<script type="text/javascript"> location.href = "http://sima-tetteke.sakura.ne.jp/generator/shakePic/main.html"; </script>';
	
}else{

$files = "img/{$fileName}.gif";
$delay = 10;
$loop  = 0;
animateGif($files , $out , $delay , $loop );

}

}

?><head>
<META http-equiv="Content-Type" content="text/html; charset=euc-jp">
<link href="../css/style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="../css/jquery.Jcrop.css" type="text/css" />
<META NAME="robots" CONTENT="noindex">
</head>
<body>
<div id="content2">
<p></p>
<div align="center">
<?php
	$fileName = $_SESSION['fuck'];
	echo ('<img src="out/'.$fileName.'">');
	$_SESSION['fuck'] = $fileName;
?>
</div>
<p></p>
<div align="center">
<input id="tweet" type="button" onClick="window.open('http://sima-tetteke.sakura.ne.jp/generator/shakePic/php/login.php', '画像をツイートする', 'width=500,height=400'); return false;" value="Tweet" style="width:180px; height:60px; font-size:150%;">
<input type="button" onClick="location.href='http://sima-tetteke.sakura.ne.jp/generator/shakePic/main.html'" value="戻る" style="width:180px; height:60px; font-size:150%;">
</div>
<p></p>
</div>
<div id="copy"></div>
</body>
</body>

