<?php

// Load this library.
require 'TwistOAuth.php';

// Start session.
@session_start();

function redirect_to_main_page() {
    $url = 'http://sima-tetteke.sakura.ne.jp/generator/shakePic/php/main.php';
    header("Location: $url");
    header('Content-Type: text/plain; charset=utf-8');
    exit("Redirecting to $url ...");
}

try {

 
    /* Redirected From Twitter リクエストトークンをもらってくる→アクセストークンを貰う←今ここ*/ 

        // Reinitialize with access_token using oauth_verifier, then set login flag.
        $_SESSION['to'] = $_SESSION['to']->renewWithAccessToken(filter_input(INPUT_GET, 'oauth_verifier'));
        //$_SESSION['logined'] = true;

        // Regenerate session id for security reasons.
        session_regenerate_id(true); /* IMPORTANT */

        // Redirect to the main page.
        redirect_to_main_page();

    

} catch (TwistException $e) { /* Error */

    // Clear session.
    $_SESSION = array();

    // Send HTTP status code and display error message as text. (not HTML)
    // The exception code will be zero when it thrown before accessing Twitter, we need to change it into 500.
    header('Content-Type: text/plain; charset=utf-8', true, $e->getCode() ?: 500);
    exit($e->getMessage());

}
?>