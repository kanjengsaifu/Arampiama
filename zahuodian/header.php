<?php
include "config/koneksi.php";
$text='';
$query="SELECT * FROM `mainmenu` 
        WHERE id_mainmenu in (".$_SESSION['akses'].") and root=0 AND aktif = 'Y' order by no asc ";
while ( $r   = Select_database($query)):
    if($r['root'] == $r['id_mainmenu']):
        $text.=' <li>
                    <a href="?module='.$r['link_modul'].'">
                        <span class="fa-stack">
                            <i class="'. $r['icon'] .'"></i>
                        </span>
                        <i>'. $r['nama_mainmenu'] .'</i>
                    </a>
                </li>';
            else: 
        $text.=' <li>
                    <a href="" class="dropdown-toggle" data-toggle="dropdown">
                        <span class="fa-stack fa-sm"><i class="'. $r['icon'] .'"></i></span>
                        <b>'. $r['nama_mainmenu'] .'</b>
                    </a>
                    <ul class="dropdown-menu">';
                  $root=Select_database($query);
                   $chil = mysql_query("SELECT * FROM `mainmenu` WHERE root=$r[id_mainmenu] AND aktif = 'Y'  order BY `no` asc");
                    while ( $p   = mysql_fetch_array($chil)):
                    $_ck = (array_search($p['id_mainmenu'], $_arrNilai) === false)? '' : 'hidden'; 
                         if ($_ck=='hidden'):
                            echo'<li><a href="?module='.$p['link_modul'].'"><i class="fa fa-angle-double-right"></i> '.$p['nama_mainmenu'].'</a></li>';
                         endif;
                    endwhile;
                echo'</ul></li>';
            endif ;
        endwhile;
?>
<nav class="navbar navbar-default">
  <div class="container-fluid">
    <ul class="nav navbar-nav">

<?php echo $text; ?>
   

    </ul>
                             <ul class="nav navbar-nav navbar-right">
 <li>
            <a href="" class="dropdown-toggle" data-toggle="dropdown">
                <span class="fa-stack fa-sm"><i class="lyphicon glyphicon-user"></i></span>
                <b><?= str_replace('_', ' ', $_GET['module'])?> <input type="hidden" id="module" value="<?= $_GET['module'] ?>"></b>
            </a>
            <ul class="dropdown-menu">
                <li><a href="?module=user"><i class="fa fa-angle-double-right"></i> <?php echo $_SESSION['username'];?></a></li>
                <li><a href="?module=user"><i class="fa fa-angle-double-right"></i> Log Out</a></li>
            </ul>
  </div>
</nav>




<?php include 'content.php';?>

