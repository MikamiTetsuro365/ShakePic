<?php
session_start();
date_default_timezone_set('Asia/Tokyo');

function deleteFile(){

	$timeLimit = strtotime("1 minutes ago");//�����30ʬ��λ��֤����
	$dir = dirname(__FILE__).'/out/';//����оݤΥǥ��쥯�ȥ� 
	
	$listFile = scandir($dir);//����оݤΥǥ��쥯�ȥ���Υե�����򸡺���������֤���
	
	foreach($listFile as $hoge){
		$dirFile = $dir . $hoge;//����ե�����
		if(!is_file($dirFile)){
			continue;//����
		}//���Ĥ��ä��ե����뤬�ե�����Ǥ��뤫���ե�����Ǥʤ���к�����ʤ�( ��_����)
		$timeFile = filemtime($dirFile);//�ե�����κǽ������������
		if($timeFile < $timeLimit){//�ǽ�������
			unlink($dirFile);
		}
	}
}

function animateGif($files , $out , $delay , $loop ){
	
	$imgSize = new Imagick();
	$imgSize -> pingImage($files);
	$imgWidth=$AimgWidth = $imgSize->getImageWidth();
	$imgHeight=$AimgHeight = $imgSize->getImageHeight();

		$file = new Imagick($files);//�����ɤ߹���fu~��
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
	$name= hash('MD5',$time);//�ե�����̾��ϥå����ͤˤ��ƽ��ϥե�����̾�˺���
	$fileName = $name.'.gif';
	
	for($i=0; $i<10; $i++){//files�����Ǥ�file������ƽ����˻���
		
		$randX = rand(0,20);
		$randY = rand(0,20);
		
		$gif->newImage($AimgWidth+20, $AimgHeight+20, new ImagickPixel('white'),'gif');
		
		$file = new Imagick($files);//�����ɤ߹���fu~��
		$file -> setFormat('gif');
		if($imgWidth > 640){
			$file->thumbnailImage(640, 0);
		}else if($imgHeight > 640){
			$file->thumbnailImage(0, 640);
		}
		
		$gif->compositeImage($file, Imagick::COMPOSITE_DEFAULT,$randX,$randY);
		
		$gif->cropImage($AimgWidth-20,$AimgHeight-20, 20 , 20);
		$gif->setImagePage($AimgWidth-20,$AimgHeight-20, 0, 0);//GIF��ꥵ��������Ȥ��Ϥ��������ʤ��ȥꥵ��������ͤ���FUCK
		
		$gif -> setImageDelay($delay);//�쥳�ޤβ�����ɽ�����������
		$gif -> setImageIterations($loop);//����롼�פ����뤫��
		$anime ->addImage($gif);//�����ꥹ�Ȥ˲�����ä���
		$gif -> destroy();
		$file -> destroy();
	
	}
	
	
	$anime -> writeImages("out/$fileName", true); //�����ե�����˽���(�񤭹���) ���˥�GIF�ʤΤ�writeImages
	/*$image = new Imagick('testOut.gif');
	header('Content-type: image/gif');
	header("Content-Disposition: inline; filename=image.gif");
	echo $image;���������������̵�������˥�GIF��ɽ���Ǥ��ʤ�*/
	
	$_SESSION['fuck'] = $fileName;
	
	$anime -> destroy();
	
	//echo ('<img src="out/'.$fileName.'">');//�ѿ�Ÿ��( ��_����)
	
	unlink($files);
	
	
}

deleteFile();//out������פʥե���������
//��������
$inFile = $_FILES['my_img']['tmp_name'];

$name =$_FILES['my_img']["name"];

$size =$_FILES['my_img']["size"];

//$error = $_FILES['my_img']["error"];

$end=0;

// �ե����륢�åץ��ɤν���
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
	
echo '<script type="text/javascript"> alert( "���顼 �ե�����γ�ĥ��(.jpg .png .gif �ϲ�)��ե����륵�����򸫤ʤ����Ƥ�������. " ) </script>';
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
<input id="tweet" type="button" onClick="window.open('http://sima-tetteke.sakura.ne.jp/generator/shakePic/php/login.php', '������ĥ����Ȥ���', 'width=500,height=400'); return false;" value="Tweet" style="width:180px; height:60px; font-size:150%;">
<input type="button" onClick="location.href='http://sima-tetteke.sakura.ne.jp/generator/shakePic/main.html'" value="���" style="width:180px; height:60px; font-size:150%;">
</div>
<p></p>
</div>
<div id="copy"></div>
</body>
</body>

