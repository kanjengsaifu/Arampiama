<?php
  session_start();
?>
<html>
<head>
<title>Admin Mamber</title>
<meta http-equiv="Cache-control" content="no-cache">

<style>
  body{
    background:#ECF0F1;
    font-family: sans-serif;
  font-family: Tahoma;
  text-align: center; 
}         

a:link,a:visited {
  color:#265180;
}
a:hover {
  color: #FF6600;
  text-decoration:none;
}
h2 {   
  font: normal 120% Georgia;
  line-height: 200%;
  color: #265180;
  background-color: transparent;
  border-bottom: 1px dotted #265180;
}
table {
  font-family: Tahoma; 
  font-size: 8pt;
  border-width: 1px;
  border-style: solid;
  border-color: #999999;
  border-collapse: collapse;
  margin: 10px 0px;
}
th{
  color: #FFFFFF;
  font-size: 7pt;
  text-transform: uppercase;
  text-align: center;
  padding: 0.5em;
  border-width: 1px;
  border-style: solid;
  border-color: #969BA5;
  border-collapse: collapse;
  background-color: #265180;
}
td{
  padding: 0.5em;
  vertical-align: top;
  border-width: 1px;
  border-style: solid;
  border-color: #969BA5;
  border-collapse: collapse;
}
input,textarea,select{
  font-family: Tahoma; 
  font-size: 8pt;
}
#paging{
  font-family: Tahoma; 
  font-size: 8pt; 
}
#footer{
  clear :both;
  padding: 20px 0 10px 255px;
  font-size: 70%;
  color: #FFFFFF;
  background-color: #265180;
}

#kotak{
    width: 520px;
    height: 250px;
    background: #fff;
    margin: 150px auto 100px auto;
    color:#2ECC71;
}
#kotakbawah{
    width: 520px;
    height: 125px;
    background: #fff;
    margin: 150px auto 100px auto;
    color:#2ECC71;
}
#atas{
    height: 35px;
    width: 520px;
    text-align: center;
    font-size: 15pt;
    padding-top:20px;
}
#bawah{
    height: 200px;
    width: 520px;
    
}
.masuk{
    width: 400px;
    height:40px;
    margin-top:10px;
    /*margin-left: 60px;*/
    font-size: 12pt;
    border: 1px solid #2ECC71;
    padding-left:10px;
    color:#2ECC71;
}
.masuk:focus{
    width: 400px;
    height:40px;
    margin-top:10px;
    /*margin-left: 60px;*/
    font-size: 12pt;
    padding-left:10px;
    color:#1ABC9C;
    outline: none;
    box-shadow: 0 0 5px #2ECC71;
}

#tombol{
    width: 400px;
    height:40px;
    margin-top:10px;
    /*margin-left: 60px;*/
    background: #2ECC71;
    border:none;
    color:#fff;
    font-size: 14pt;
    outline:none;
}

#tombollogout{
    width: 400px;
    height:40px;
    margin-top:10px;
    margin-left: 0px;
    background: #2ECC71;
    border:none;
    color:#fff;
    font-size: 14pt;
    outline:none;
}

.navbar-nav li a {
    font-size: large;
}
.navbar-brand span {
    font-size: 2em;
}        
</style>

<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
<script src="asset/js/jquery-2.0.0.js"></script>
<script src="asset/js/bootstrap.min.js"></script>
        <script language="javascript">
        function validasi(form){
          if (form.username.value == ""){
            alert("Anda belum mengisikan Username.");
            form.username.focus();
            return (false);
          }
             
          if (form.password.value == ""){
            alert("Anda belum mengisikan Password.");
            form.password.focus();
            return (false);
          }
          return (true);
        }
        </script>

</head>

<body OnLoad="document.login.focus();">
<div id="kotak">
    <div id="atas">
        <p>LOGIN ADMIN MEMBER</p>
    </div>
    <div id="bawah">  
    <form name="login" action="config/cek_login.php" method="POST" onSubmit="return validasi(this)">
      <input class="masuk" type="text" autocomplete="off" placeholder="Username .." name="username"><br/>
      <input class="masuk" type="password" autocomplete="off" placeholder="Password .." name="password"><br/>
      <input id="tombol" type="submit" value="Login">
    </form>
    </div>
<p>&nbsp;</p>
  <div style="text-align:center;">
      Copyright &copy; 2016 by Media Tech Indonesia. All rights reserved.
  </div>
</div>

</body>
</html>


