<?php
require_once "UserID.php";
require('config.php');
//token寫入資料庫

echo "MY PW is<br/>" ;
echo $nowPW;
echo "Refresh.php <br/>" ;

//connect DB
$con = mysql_connect($db_host, $db_user, $db_pass);
mysql_select_db($db_name, $con);

$mySQL = "SELECT refresh_token FROM access WHERE account = '$nowMail' AND password = '$nowPW' ;";
if ($result = mysql_query($mySQL)) {
    echo "result <br/>   ";
    $row = mysql_fetch_assoc($result);
    print $row['refresh_token'];
    $rftoken = $row['refresh_token'];
}
else {
    echo "Error: " . $mySQL . "<br>" . $con->error;
}

mysql_close($con);


    //第二階段token認證
    date_default_timezone_set('Asia/Taipei');
    $upday= date("Y-m-d");
    $updatetime= date("Y-m-d H:i:s");
    $client_id = '227RLC';
    $exin = 2592000;
                    //
    $client_secret = 'Your client_secret';
    $Auth = $client_id .':'. $client_secret;

    $HTTP_header = array('Authorization: Basic ' . base64_encode($Auth),'Content-Type: application/x-www-form-urlencoded',);
    $POST_data = array('grant_type' => 'refresh_token','refresh_token' => $rftoken ,'expires_in' => $exin);
    $POST_options = array( CURLOPT_POST => true,CURLOPT_RETURNTRANSFER => true,CURLOPT_URL => 'https://api.fitbit.com/oauth2/token',CURLOPT_HTTPHEADER => $HTTP_header,CURLOPT_POSTFIELDS => http_build_query($POST_data));

    echo "<br/>";
    echo  base64_encode($Auth);
    echo "<br/>";

        // get access token and else
        $ch_1 = curl_init();

        // 設定擷取的URL網址
        curl_setopt_array($ch_1, $POST_options);

        // check result
        $json=curl_exec($ch_1);
        curl_close($ch_1);

        $susccessful = True;
        echo "result<br/>";
        print $json;
        //var_dump(json_decode($json , true));
        echo "<br/>";

        $myjson = json_decode($json);

        echo "<br/>check out acess token result:     ";
        $access_token = $myjson->{"access_token"};
        print $access_token;
        echo "<br/>check out acess token result:     ";
        $refresh_token = $myjson->{"refresh_token"};
        print $refresh_token;

        if(array_key_exists('success',$myjson)){
          echo "refresh fail ! <br/>";
//          print $json;
          return 0;
        }
        else{
          echo "refresh success ! <br/>";

          //token寫入資料庫
          //connect DB
          $con = mysql_connect($db_host, $db_user, $db_pass);
          mysql_select_db($db_name, $con);

          $sql = "UPDATE access SET access_token = '$access_token' , refresh_token = '$refresh_token' ,  upday = '$upday', updatetime ='$updatetime' WHERE account = '$nowMail' AND password = '$nowPW';";

          if (mysql_query($sql)) {
            echo "<br/>record update successfully<br/>";
          }
          else {
            echo "Error: " . $sql . "<br>" . $con->error;
          }

          mysql_close($con);
          header('Location: http://140.138.77.109:8181/Hospital_Web/');
          return ;
        }

?>
