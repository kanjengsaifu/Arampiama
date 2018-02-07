<?php
include "../config/function.php";
session_start();
Connect_to_database();
echo json_encode(Check_login($_POST['username'],$_POST['password']));

?>