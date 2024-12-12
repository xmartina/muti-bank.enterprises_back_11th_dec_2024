<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
define("WEB_TITLE","Multi Bank"); // Change Bank Name
define("WEB_URL","https://finance.muti-bank.enterprises/account"); // Change No "/" Ending splash
define("WEB_EMAIL","support@finance.muti-bank.enterprises"); // Change Your Website Email

$web_url = WEB_URL;
function support_plugin(){
    require_once 'support_plugin.php';
}

function dbConnect(){
    $servername = "localhost";
    $username = "questcom_mutibanken";//DATABASE USERNAME
    $password = "mutibanken";//DATABASE PASSWORD
    $database = "questcom_mutibanken";//DATABASE NAME
    $dns = "mysql:host=$servername;dbname=$database";

    try {
        $conn = new PDO($dns, $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
}
//return dbConnect();

function inputValidation($value): string
{
    return trim(htmlspecialchars(htmlentities($value)));
}
