<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
require_once 'vendor/autoload.php';
define('root', $_SERVER['DOCUMENT_ROOT']);

if (isset($_POST['name'], $_POST['location'])) {
    echo "hey, what do you know..";
}