<?php
echo '
<div style="height:670px;margin:-20px -50px;">
<canvas id="c" ></canvas>
</div>';
?>
<style type="text/css">
* {margin: 0; padding: 0;}
/*adding a black bg to the body to make things clearer*/
body {background: black;}
canvas {display: block;}
	html{height:100%;}
body {
background:#0D343A;
background:-webkit-gradient(linear,0% 0%,0% 100%, from(rgba(13,52,58,1) ), to(#000000)  );
background: -moz-linear-gradient(top, rgba(13,52,58,1) 0%, rgba(0,0,0,1) 100%);

overflow:hidden;}

.drop {
  background:-webkit-gradient(linear,0% 0%,0% 100%, from(rgba(13,52,58,1) ), to(rgba(255,255,255,0.6))  );
  background: -moz-linear-gradient(top, rgba(13,52,58,1) 0%, rgba(255,255,255,.6) 100%);
	width:1px;
	height:89px;
	position: absolute;
	bottom:200px;
	-webkit-animation: fall .63s linear infinite;
  -moz-animation: fall .63s linear infinite;
  
}

/* animate the drops*/
@-webkit-keyframes fall {
	to {margin-top:900px;}
}
@-moz-keyframes fall {
	to {margin-top:900px;}
}
</style>
