<?php

// Load this library.
require 'TwistOAuth.php';




// Prepare simple wrapper function for htmlspecialchars.
function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

// Start session.
@session_start();
//imgout
$fileName = $_SESSION['fuck'];

// Set default HTTP status code.
$code = 200;

// Get user input.
// (I recommend you not to use $_POST. Use filter_input instead.)
$text = filter_input(INPUT_POST, 'text');

if ($text !== null) {

    try {

		
        // Update status.
        $_SESSION['to']->postMultipart('statuses/update_with_media', array('status' => $text , '@media[]' => "out/{$fileName}" ));

        // Set message.
        $message = array('green', 'Successfully tweeted.');

    
		
		unlink("out/out.png");
		
		
		//ウィンドウを閉じる♂
		echo '<script type="text/javascript"> window.close(); </script>';
		

    } catch (TwistException $e) {

        // Set error message.
        $message = array('red', $e->getMessage());

        // Overwrite HTTP status code.
        // The exception code will be zero when it thrown before accessing Twitter, we need to change it into 500.
        $code = $e->getCode() ?: 500;

    }

}

// Send charset and HTTP status code to your browser.
header('Content-Type: text/html; charset=UTF-8', true, $code);



?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<style>
h1{
	font-weight:normal;
	font-size:20px;	
	border-left:20px solid #4169e1;
	background-color:#ededed;
	color:#000;
	padding-left:10px;
	margin: 10px 0px 10px 0px;
	line-height: 1.5em;	
	}
</style>
</head>
<body>
  <h1>Tweet</h1>
  <div align="center">
  <form action="" method="post">
    <textarea name="text" rows="8" cols="50">&#13;| #画像強調ツイート http://sima-tetteke.sakura.ne.jp/generator/shakePic/main.html</textarea><br>
    <input type="submit" value="Tweet"  style="width:450px; height:60px; font-size:150%;">
  </form>
  
  <input type="button" value="ウィンドウを閉じる"  style="width:450px; height:60px; font-size:150%;" onClick="window.close();">
  <p>テキストボックス内の初期メッセージは邪魔なら消してしまってください.</p>
  <p>ツイートに少々時間がかかる場合があります.</p>
  <p>ツイートに成功すると, このウィンドウは自動的に閉じられます.</p>
  
  </div>
<?php if (isset($message)): ?>
  <p style="color:<?=$message[0]?>;"><?=h($message[1])?></p>
<?php endif; ?>
</body>
</html>