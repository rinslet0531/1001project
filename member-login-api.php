<?php
// {"username" : "owner01", "password" : "123456"}
$data = file_get_contents("php://input", "r");
$mydata = array();
$mydata = json_decode($data, true);

if (isset($mydata["username"]) && isset($mydata["password"])) {
    if ($mydata["username"] != "" && $mydata["password"] != "") {
        $p_username = $mydata["username"];
        $p_password = $mydata["password"];

        require_once("dbtools.php");
        $link = create_connection();
        
        //1. 確認帳號是否存在
        //2. 確認密碼是否正確 password_verify()

        $sql = "SELECT Username,Password FROM member WHERE Username = '$p_username'";
        $result = execute_sql($link, "testdb", $sql);
        if(mysqli_num_rows($result) == 1){
            // 帳號存在'$p_username', 繼續比對密碼
            // mysqli_fetch_assoc($result); 抓取資料表內容
            $row = mysqli_fetch_assoc($result);
            if(password_verify($p_password, $row["Password"])) {
                //密碼比對正確 , 產生uid01並更新至資料
                $uid01 = substr(hash('sha256', uniqid(time())), 0, 16);
                $sql = "UPDATE member SET Uid01 = '$uid01' WHERE Username = '$p_username'";
                if(execute_sql($link, "testdb", $sql)) {
                    $sql = "SELECT Username, Email, Uid01,State, Created_at FROM member WHERE Username = '$p_username'";
                    $result = execute_sql($link, "testdb", $sql);
                    $row = mysqli_fetch_assoc($result);

                echo '{"state" : true, "data" : '. json_encode($row) .', "message" : "登入成功!"}';
                }else{
                    //uid01更新失敗
                    echo '{"state" : false, "message" : "uid更新失敗"}';
                }
            }else{
                echo '{"state" : false, "message" : "帳號或密碼錯誤!"}';
            }
        }else{
            echo '{"state" : false, "message" : "帳號或密碼錯誤!"}';
        }
        mysqli_close($link);
    } else {
        echo '{"state" : false, "message" : "欄位不得為空白"}';
    }
} else {
    '{"state" : false, "message" : "欄位命名錯誤"}';
}