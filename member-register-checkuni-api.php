<?php
// {"username" : "owner01"}
$data = file_get_contents("php://input", "r");
$mydata = array();
$mydata = json_decode($data, true);

if (isset($mydata["username"])) {
    if ($mydata["username"] != "") {
        $p_username = $mydata["username"];

        require_once("dbtools.php");
        $link = create_connection();

        $sql = "SELECT Username FROM member WHERE Username = '$p_username'";
        $result = execute_sql($link, "testdb", $sql);
        if(mysqli_num_rows($result) == 0){
            echo '{"state" : true, "message" : "可以註冊!"}';
        }else{
            echo '{"state" : false, "message" : "帳號已存在!"}';
        }
        mysqli_close($link);
    } else {
        echo '{"state" : false, "message" : "欄位不得為空白"}';
    }
} else {
    '{"state" : false, "message" : "欄位命名錯誤"}';
}