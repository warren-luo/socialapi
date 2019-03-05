<?php
// for Debugging
ini_set("display_errors","On");
error_reporting(E_ALL);

// Set Timezone
date_default_timezone_set('Asia/Shanghai');

// Import Config
require ("./config.php");

// Connect Server
$link = @mysql_connect(HOST, USER, PASS) or die("Failed to connect database!");

// Select Database
mysql_select_db(DBNAME, $link);

// Set CHARSET
mysql_set_charset("utf8");

// Get POST
$captcha = $_POST['captcha'];
$orgid   = $_POST['orgid'];
$admin   = $_POST['admin'];
$phone   = $_POST['phone'];
$email   = $_POST['email'];
$company = $_POST['company'];
$zipcode = $_POST['zipcode'];

// Verify CAPTCHA
$sql_captcha = "SELECT expiration FROM captcha WHERE captcha='{$captcha}' ORDER BY id DESC LIMIT 1";
$result      = mysql_query($sql_captcha);
if(mysql_num_rows($result)==1){
    while($row = mysql_fetch_array($result)){
        if(strtotime($row[0])>time()){
            $captchaValid = "True";
        }else{
            $captchaValid = "False";
        }
    }
}else{
    $captchaValid = "False";
}

// Insert DATA
if($captchaValid=="True"){
    $sql_insert = "INSERT INTO authorizer VALUES(null,'{$captcha}','{$orgid}','{$admin}','{$phone}','{$email}','{$company}','{$zipcode}')";
    mysql_query($sql_insert, $link);
    if(mysql_insert_id($link) > 0){
        echo "Successfully added!";
    } else {
        echo "Failed to add!";
    }
}else{
    echo "Invalid CAPTCHA!";
}

// Disconnect Server
mysql_close($link);