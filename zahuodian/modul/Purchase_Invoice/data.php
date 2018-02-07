<?php
 include "../../config/coneksi.php";

// Get job (and id)
$job = '';
$id  = '';
if (isset($_GET['job'])){
  $job = $_GET['job'];
  if ($job == 'get_companies' ||
      $job == 'get_company'   ||
      $job == 'add_company'   ||
      $job == 'edit_company'  ||
      $job == 'delete_company'){
    if (isset($_GET['id'])){
      $id = $_GET['id'];
      if (!is_numeric($id)){
        $id = '';
      }
    }
  } else {
    $job = '';
  }
}

// Prepare array
$mysql_data = array();

// Valid job found
if ($job != ''){
  
  // Connect to database
  $db_connection = mysqli_connect($db_server, $db_username, $db_password, $db_name);
  if (mysqli_connect_errno()){
    $result  = 'error';
    $message = 'Failed to connect to database: ' . mysqli_connect_error();
    $job     = '';
  }
  
  // Execute job
  if ($job == 'get_companies'){
    
    // Get companies
  $query = "SELECT * FROM trans_pur_order";
    $query = mysqli_query($db_connection, $query);
    if (!$query){
      $result  = 'error';
      $message = 'query error';
    } else {
      $result  = 'success';
      $message = 'query success';
      $no = 1;
      while ($company = mysqli_fetch_array($query)){
        $functions  = '<div class="function_buttons"><ul>';
        $functions .= '<li class="function_edit"><a data-id="'   . $company['id_pur_order'] . '" data-name="' . $company['id_supplier'] . '"><span>Edit</span></a></li>';
        $functions .= '<li class="function_delete"><a data-id="' . $company['id_pur_order'] . '" data-name="' . $company['id_supplier'] . '"><span>Delete</span></a></li>';
        $functions .= '</ul></div>';
        $mysql_data[] = array(
          "no"          => $no,
          "kodepo"  => $company['id_pur_order'],
          "supplier"    => $company['id_supplier'],
          "total"       => $company['total'],
          "status"   => $company['status'],
          "tanggal"     => $company['tgl_update'],
          "functions"     => $functions
        );
        $no++;
      }
    }
    
  } elseif ($job == 'get_company'){
    
    // Get company
    if ($id == ''){
      $result  = 'error';
      $message = 'id missing';
    } else {
       $query = "SELECT * FROM trans_pur_order WHERE id_pur_order = '" . mysqli_real_escape_string($db_connection, $id) . "'";
      $query = mysqli_query($db_connection, $query);
      if (!$query){
        $result  = 'error';
        $message = 'query error';
      } else {
        $result  = 'success';
        $message = 'query success';
        while ($company = mysqli_fetch_array($query)){
          $mysql_data[] = array(
          "no"          => '1',
          "kodepo"  => $company['id_pur_order'],
          "supplier"    => $company['id_supplier'],
          "total"       => $company['total'],
          "status"   => $company['status'],
          "tanggal"     => $company['tgl_update'],
          "functions"     => $functions
          );
        }
      }
    }
  
  } elseif ($job == 'add_company'){
    
    // Add company
    $query = "INSERT INTO trans_pur_order SET ";
    if (isset($_GET['kodepo'])) { $query .= "kodepo = '" . mysqli_real_escape_string($db_connection, $_GET['kodepo']) . "', "; }
    if (isset($_GET['supplier']))   { $query .= "supplier   = '" . mysqli_real_escape_string($db_connection, $_GET['supplier'])   . "', "; }
    if (isset($_GET['total']))      { $query .= "total      = '" . mysqli_real_escape_string($db_connection, $_GET['total'])      . "', "; }
    if (isset($_GET['status']))  { $query .= "status  = '" . mysqli_real_escape_string($db_connection, $_GET['status'])  . "', "; }
    if (isset($_GET['tanggal']))    { $query .= "tanggal    = '" . mysqli_real_escape_string($db_connection, $_GET['tanggal'])    . "', "; }
    $query = mysqli_query($db_connection, $query);
    if (!$query){
      $result  = 'error';
      $message = 'query error';
    } else {
      $result  = 'success';
      $message = 'query success';
    }
  
  } elseif ($job == 'edit_company'){
    
    // Edit company
    if ($id == ''){
      $result  = 'error';
      $message = 'id missing';
    } else {
      $query = "UPDATE trans_pur_order SET ";
      if (isset($_GET['no']))         { $query .= "no         = '" . mysqli_real_escape_string($db_connection, $_GET['no'])         . "', "; }
      if (isset($_GET['kodepo'])) { $query .= "kodepo = '" . mysqli_real_escape_string($db_connection, $_GET['kodepo']) . "', "; }
      if (isset($_GET['supplier']))   { $query .= "supplier   = '" . mysqli_real_escape_string($db_connection, $_GET['supplier'])   . "', "; }
      if (isset($_GET['total']))      { $query .= "total      = '" . mysqli_real_escape_string($db_connection, $_GET['total'])      . "', "; }
      if (isset($_GET['status']))  { $query .= "status  = '" . mysqli_real_escape_string($db_connection, $_GET['status'])  . "', "; }
      if (isset($_GET['tanggal']))    { $query .= "tanggal    = '" . mysqli_real_escape_string($db_connection, $_GET['tanggal'])    . "', "; }
      $query .= "WHERE id_pur_order = '" . mysqli_real_escape_string($db_connection, $id) . "'";
      $query  = mysqli_query($db_connection, $query);
      if (!$query){
        $result  = 'error';
        $message = 'query error';
      } else {
        $result  = 'success';
        $message = 'query success';
      }
    }
    
  } elseif ($job == 'delete_company'){
  
    // Delete company
    if ($id == ''){
      $result  = 'error';
      $message = 'id missing';
    } else {
      $query = "DELETE FROM trans_pur_order WHERE id_pur_order = '" . mysqli_real_escape_string($db_connection, $id) . "'";
      $query = mysqli_query($db_connection, $query);
      if (!$query){
        $result  = 'error';
        $message = 'query error';
      } else {
        $result  = 'success';
        $message = 'query success';
      }
    }
  
  }
  
  // Close database connection
  mysqli_close($db_connection);

}

// Prepare data
$data = array(
  "result"  => $result,
  "message" => $message,
  "data"    => $mysql_data
);

// Convert PHP array to JSON array
$json_data = json_encode($data);
print $json_data;
?>