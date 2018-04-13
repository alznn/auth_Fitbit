<?php

include("config_cc.php");

$account  = $_POST["account"];
$password = $_POST["password"];
$name = $_POST["realname"];
$nick = $_POST["nickname"];

if(isExistAccount($account)){
  echo json_encode(array("msg"=>"error: account exit"));

}
else{


    $sql = "INSERT INTO User (account,password,userName,account_ID)" .
                      "VALUES('" . $account . "','" . $password . "','" . $name . "','" . $nick . "')";

    $result = mysql_query($sql);
    if($result === TRUE)
        echo json_encode(array("msg"=>"success"));
    else
        echo json_encode(array("msg"=>"error: " . mysql_error()));

}

mysql_close($con);

function isExistAccount($account){

    $sql = "SELECT * FROM User WHERE account='" . $account . "';";
    $result = mysql_query($sql);
    return mysql_num_rows($result)==1;
}
?>

