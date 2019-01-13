<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

$uri = $_GET['uri'];

if(strpos($uri,'api') === 0){
    //api;
    include_once ($_SERVER['DOCUMENT_ROOT'].'/server/routes.php');

}else{
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <title>02_task1</title>
    </head>
    <body>
    <div id="app"></div>
    <script src="/dist/build.js"></script>
    </body>
    </html>
<?php
}


