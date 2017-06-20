<?php


ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once ("app/FacebookApp.php");


$fbApp = new FacebookApp();

$fbApp->actionFbAuth();

if (!$fbApp->getAccessStatus())
{
    $permissions = ['email', 'publish_actions', 'manage_pages', 'user_photos', 'user_posts'];
    $fbApp->getLoginUrl($permissions);
} else {
    $fbApp->getLoginInfo();
    //$fbApp->uploadPhoto();
    //$fbApp->postMassegeInTimeline();


    $fbApp->getInfo();


    //$fbApp->getLogoutUrl();

    //session_destroy();
}
?>

<html>
<head>
    <title>Your Website Title</title>
    <!-- You can use Open Graph tags to customize link previews.
    Learn more: https://developers.facebook.com/docs/sharing/webmasters -->
    <meta property="og:url"           content="http://www.your-domain.com/your-page.html"/>
    <meta property="og:type"          content="website"/>
    <meta property="og:title"         content="Test 1"/>
    <meta property="og:description"   content="sfasdfdsfs dasf das fdas f dasf sda f dasf dsf ds f dsf asdf das fdas f dasf dsaf dasf das fdas fdas f dasf sdf das f"/>
    <meta property="og:image"         content="111.jpg"/>
    <meta charset="UTF-8"
</head>
<body>

<!-- Load Facebook SDK for JavaScript -->
<div id="fb-root"></div>
<script>(function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/uk_UA/sdk.js#xfbml=1&version=v2.8&appId=1925419977671868";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>

<!-- Your share button code -->
<div class="fb-share-button" data-href="http://webdevelop.it-dev-lab.com/" data-layout="button" data-size="small" data-mobile-iframe="true"><a class="fb-xfbml-parse-ignore" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Fwebdevelop.it-dev-lab.com%2F&amp;src=sdkpreparse">Поширити</a></div>

</body>
</html>


