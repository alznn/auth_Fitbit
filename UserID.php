<?php
require('config.php');
echo "USERID";
//require('.php');
date_default_timezone_set('Asia/Taipei');
//$upday= date("Y-m-d");
//$updatetime= date("Y-m-d H:i:s");

$nowPW =  $_POST['password'];
$nowMail = trim($_POST['email']);
$refer = $_POST['refer'];

if($nowPW == '' || $nowMail == ''){
  echo "no input";
}

else{
  /*$con = mysql_connect($db_host, $db_user, $db_pass);
  mysql_select_db($db_name, $con);

  $query = "SELECT User_ID FROM access WHERE account = '$nowMail' ;" ;
  $result = mysql_query($query, $con) or die ( mysql_error());
  echo mysql_num_rows($result);

  if (mysql_num_rows($result) == 1)//Account存在，檢查ID存在
  {
    $myquery = "UPDATE access SET upday = '$upday', updateTime = '$updatetime'  WHERE account = '$nowMail';" ;
    $result = mysql_query($myquery, $con) or die ( mysql_error());
    if(mysql_query($myquery, $con)){
      echo "update!";
    }
    else{
      echo "error!";
    }
  }
  else if(mysql_num_rows($result) == 0){
    $myquery = "INSERT into access (User_ID , updateTime) VALUES ('$nowMail','$updatetime');" ;
    if(mysql_query($myquery, $con)){
      echo "inser!";
    }
    else{
      echo "error!";
    }
  }

  mysql_close($con);
*/
  require('check.php');
}
?>
