<?php
require_once('database.php');
require_once('shortener.class.php');
$shortener = new Shortener();
$shortCode = $_GET['c'];
try
{
    $url = $shortener->shortCodeToUrl($shortCode);
    header("Location: ".$url);
    exit;
}
catch(Exception $e)
{
    echo $e->getMessage();
}
?>