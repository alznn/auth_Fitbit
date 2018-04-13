
<?php
//正確執行版
require('config.php');
//require_once "UserID.php";
echo " $db_account <br/> <br/> <br/> <br/> ";

    //第一階段code認證
    function httpget(){
        // 建立CURL連線
        $ch = curl_init();
        //echo "hello world!";x

        // get Authorization Request
        // 設定擷取的URL網址
        curl_setopt($ch, CURLOPT_URL,"https://www.fitbit.com/login?disableThirdPartyLogin=true&redirect=%2Foauth2%2Fauthorize%3Fclient_id%3D227RLC%26redirect_uri%3Dhttp%253A%252F%252F140.138.77.109%252Ftest_0.php%26response_type%3Dcode%26scope%3Dactivity%2Bnutrition%2Bheartrate%2Blocation%2Bnutrition%2Bprofile%2Bsettings%2Bsleep%2Bsocial%2Bweight%26state&expires_in=31536000");
        //redirect uri

        //curl_setopt($ch, CURLOPT_HEADER, false);

        //將curl_exec()獲取的訊息以string返回
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);

        // 執行
        $temp=curl_exec($ch);
        echo "temp is  ";
        echo $temp;
        curl_close($ch);
        return $temp;

}
    echo "the code is: \n";
    //https://www.fitbit.com/oauth2/authorize
 //   echo httpget("https://www.fitbit.com/oauth2/authorize?response_type=code&client_id=227RLC&redirect_uri=http://140.138.77.109/test_0.php&scope=activity%20nutrition%20heartrate%20location%20nutrition%20profile%20settings%20sleep%20social%20weight");
    $code = $_GET['code'];
    echo $code;


    //第二階段token認證
    date_default_timezone_set('Asia/Taipei');
    $upday= date("Y-m-d");
    $updatetime= date("Y-m-d H:i:s");
    $client_id = '227RLC';
    //$exin = 3600;
    $exin = 2592000;
    $client_secret ='Your client_secret';
    $Auth = $client_id .':'. $client_secret;

    $HTTP_header = array('Authorization: Basic ' . base64_encode($Auth),'Content-Type: application/x-www-form-urlencoded',);
    $POST_data = array( 'client_id' => $client_id,'grant_type' => 'authorization_code','redirect_uri' => 'http://140.138.77.109/test_0.php','code' => $code,'expires_in'=>$exin);
    $POST_options = array( CURLOPT_POST => true,CURLOPT_RETURNTRANSFER => true,CURLOPT_URL => 'https://api.fitbit.com/oauth2/token',CURLOPT_HTTPHEADER => $HTTP_header,CURLOPT_POSTFIELDS => http_build_query($POST_data));

        // get access token and else
        $ch_1 = curl_init();

        // 設定擷取的URL網址
        curl_setopt_array($ch_1, $POST_options);

        // check result
        $json=curl_exec($ch_1);
        curl_close($ch_1);

        echo "<br/>result<br/>";
        //var_dump(json_decode($json , true));

        $json = json_decode($json);

        echo "check out acess token result:     <br/>";
        $access_token = $json->{"access_token"};
        print $access_token;

        echo "<br/>check time:        ";
        $expires_in = $json->{"expires_in"};
        print $expires_in;

        echo "<br/>check refresh token:    ";
        $refresh_token = $json->{"refresh_token"};
        print $refresh_token ;

        echo "<br/>token type:    ";
        $token_type = $json->{"token_type"};
        print $token_type;

        echo"<br/>id    ";
        $myid = $json->{"user_id"};
        print $myid ;

        echo "<br/>date:　";
        print $updatetime;

    //token寫入資料庫
      //connect DB
   $con = mysql_connect($db_host, $db_user, $db_pass);
    mysql_select_db($db_name, $con);

    $query = "SELECT user_id FROM access WHERE user_id = '$myid' ;" ;
    $result = mysql_query($query, $con) or die ( mysql_error());

    if (mysql_num_rows($result) == 1)//ID存在
    {
      	echo '<br/>ID exit already! <br/>';

        $sql = "UPDATE access SET access_token = '$access_token' , refresh_token = '$refresh_token' ,  upday = '$upday', updatetime =' $updatetime' WHERE user_id = '$myid';";
        if (mysql_query($sql)) {//確認ok
            echo "<br/>record update successfully<br/>";
        }
        else {
            echo "Error: " . $sql . "<br>" . $con->error;
        }
    }

    else if (mysql_num_rows($result) == 0)//ID不存在
    {//確認ok
  //'761542858@qq.com','swh941120',
        $query = "SELECT * FROM access WHERE user_id is NULL ;" ;
        $result = mysql_query($query, $con) or die ( mysql_error());

        if (mysql_num_rows($result) == 1)
        {
          $row = mysql_fetch_array($result);
          $M =  $row['account'];
          $P = $row['password'];
          $sql = "UPDATE access SET user_id = '$myid',access_token = '$access_token' , refresh_token = '$refresh_token' ,  upday = '$upday', updatetime =' $updatetime' , expires_in = '$expires_in' , token_type = '$token_type' WHERE account = '$M' AND password = '$P';";
          mysql_query($sql);
          echo "record insert successfully";
          echo $M;
          echo $P;

        }
        else
        {
          echo "帳號有問題";
        }
      }

    else {//多個ID

        echo "多個ID";
    }
//    echo $sql;
    mysql_close($con);

    header('Location: http://140.138.77.109:8181/Hospital_Web/');

?>
