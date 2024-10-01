<?php
// {"id" : "xxx","level": "100"}

// {"state" : true, "message" : "會員等級已更新!"}
// {"state" : false, "message" : "會員等級更新失敗!"}

    $data = file_get_contents("php://input", "r");
    $mydata = array();
    $mydata = json_decode($data, true);
    
    if(isset($mydata["id"]) && isset($mydata["state"])){
        if($mydata["id"] != "" && $mydata["state"] != ""){
            $p_id = $mydata["id"];
            $p_state = $mydata["state"];

            require_once("dbtools.php");
            $link = create_connection();

            $sql = "UPDATE member SET State = '$p_state' WHERE ID = '$p_id'";
            if(execute_sql($link, "testdb", $sql)){
                echo '{"state" : true, "message" : "更新成功!"}';
            }else{
                echo '{"state" : false, "message" : "更新失敗!"}';
            }
            mysqli_close($link);
        }else{
            echo '{"state" : false, "message" : "欄位不得為空白"}';
        }
    }else{
        echo '{"state" : false, "message" : "欄位命名錯誤"}';
    }
?>