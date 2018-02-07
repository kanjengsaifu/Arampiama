<?php
/**
 * Informasi untuk koneksi database
 */
// Database details
$db_server   = 'localhost';
$db_username = 'root';
$db_password = '';
$db_name     = 'programtoko';

$conn = mysqli_connect($db_server, $db_username, $db_password, $db_name) or die("Connection failed: " . mysqli_connect_error());