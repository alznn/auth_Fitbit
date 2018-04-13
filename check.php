<?php
require('config.php');
//$email = $_POST['email'];
//$password = $_POST['password'];
//$refer = $_POST['refer'];
require_once "UserID.php";
echo '<br\>';


date_default_timezone_set('Asia/Taipei');
//  $upday= date("Y-m-d");
$updatetime= date("Y-m-d H:i:s");

echo "MY PW is " ;
echo $nowPW;
echo "MY account is " ;
echo $nowMail;



//UNI DB
$unicon = mysql_connect($db_host, $db_user, $db_pass);
mysql_select_db($unidb_name, $unicon);

$query = "SELECT UpdateTime FROM User WHERE User_ID = '$nowMail';" ;
$result = mysql_query($query, $unicon) or die ( mysql_error());
echo mysql_num_rows($result);


if (mysql_num_rows($result) == 1)//Account存在，檢查ID存在
{
  echo "UNI ACCOUNT exit";
/*
  mysql_select_db($unidb_name, $con);
  $myquery = "UPDATE User SET updateTime = '$updatetime'  WHERE User_ID = '$nowMail';" ;
  mysql_query($myquery, $con) or die ( mysql_error());

  mysql_select_db($db_name, $con);
*/

  $myquery = "UPDATE User SET UpdateTime = '$updatetime' WHERE User_ID = '$nowMail';" ;
  $result = mysql_query($myquery, $unicon) or die ( mysql_error());
}

else if(mysql_num_rows($result) == 0){  //帳號不存在     //確認ok
  $sql = "INSERT INTO User(User_ID , UpdateTime ) VALUES( '$nowMail' , '$updatetime');";
  $result = mysql_query($sql, $unicon) or die ( mysql_error());
}
else {
  echo "多個帳號";
  echo "Error: " . $sql . "<br>" . $unicon->error;
}
mysql_close($unicon);


//fitbit DB
$con = mysql_connect($db_host, $db_user, $db_pass);
mysql_select_db($db_name, $con);

$query = "SELECT account , password FROM access WHERE account = '$nowMail' AND password = '$nowPW' ;" ;
$result = mysql_query($query, $con) or die ( mysql_error());
echo mysql_num_rows($result);

if (mysql_num_rows($result) == 1)//Account存在，檢查ID存在
{
  echo "ACCOUNT exit";
/*
  mysql_select_db($unidb_name, $con);
  $myquery = "UPDATE User SET updateTime = '$updatetime'  WHERE User_ID = '$nowMail';" ;
  mysql_query($myquery, $con) or die ( mysql_error());

  mysql_select_db($db_name, $con);
*/

  $myquery = "SELECT user_id FROM access WHERE account = '$nowMail' AND password = '$nowPW' ;" ;
  $result = mysql_query($myquery, $con) or die ( mysql_error());
  $row = mysql_fetch_array($result);

  if( $row['user_id'] == ' '){//確認ok
      echo "ID is NULL";
      $myquery = "DELETE FROM access WHERE account = '$nowMail' AND password = '$nowPW' ;" ;
      $result = mysql_query($myquery, $con) or die ( mysql_error());
      echo mysql_num_rows($result);
      header("Location: https://www.fitbit.com/login?disableThirdPartyLogin=true&redirect=%2Foauth2%2Fauthorize%3Fclient_id%3D227RLC%26redirect_uri%3Dhttp%253A%252F%252F140.138.77.109%252Ftest_0.php%26response_type%3Dcode%26scope%3Dactivity%2Bnutrition%2Bheartrate%2Blocation%2Bnutrition%2Bprofile%2Bsettings%2Bsleep%2Bsocial%2Bweight%26state");
  }
  else {
    echo $row['user_id'];
    require('refresh.php');
  }
}

else if(mysql_num_rows($result) == 0){  //帳號不存在     //確認ok

/*
  mysql_select_db($unidb_name, $con);
  $myquery = "INSERT INTO User (User_ID , updateTime) VALUES ('$nowMail','$updatetime');" ;
  mysql_query($myquery, $con));
*/
  $sql = "INSERT INTO access(account , password ) VALUES( '$nowMail' , '$nowPW');";
  if (mysql_query($sql)) {
      echo "new account insert!";
      header('Location: https://www.fitbit.com/login?disableThirdPartyLogin=true&redirect=%2Foauth2%2Fauthorize%3Fclient_id%3D227RLC%26redirect_uri%3Dhttp%253A%252F%252F140.138.77.109%252Ftest_0.php%26response_type%3Dcode%26scope%3Dactivity%2Bnutrition%2Bheartrate%2Blocation%2Bnutrition%2Bprofile%2Bsettings%2Bsleep%2Bsocial%2Bweight%26state');
  }
}
else {
  echo "多個帳號";
  echo "Error: " . $sql . "<br>" . $con->error;
}
mysql_close($con);
?>
