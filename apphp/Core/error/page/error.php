<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>很抱歉，此页面暂时找不到！</title>

<style type="text/css">
body {margin: 0px; padding:0px; font-family:"微软雅黑", Arial, "Trebuchet MS", Verdana, Georgia,Baskerville,Palatino,Times; font-size:16px;}
div{margin-left:auto; margin-right:auto;}
a {text-decoration: none; color: #1064A0;}
a:hover {color: #0078D2;}
img { border:none; }
h1,h2,h3,h4 {
/*	display:block;*/
	margin:0;
	font-weight:normal; 
	font-family: "微软雅黑", Arial, "Trebuchet MS", Helvetica, Verdana ; 
}
h1{font-size:25px; color:#0188DE; padding:20px 0px 10px 0px;}
h2{color:#0188DE; font-size:16px; padding:10px 0px 40px 0px;}

#page{width:910px; padding:20px 20px 40px 20px; margin-top:80px;}
.button{width:180px; height:28px; margin-left:0px; margin-top:10px; background:#009CFF; border-bottom:4px solid #0188DE; text-align:center;}
.button a{width:180px; height:28px; display:block; font-size:14px; color:#fff; }
.button a:hover{ background:#5BBFFF;}
</style>

</head>
<body>


<div id="page" style="border-style:dashed;border-color:#e4e4e4;line-height:30px;background:url(sorry.png) no-repeat right;">
	<h1><?php echo $errNo.':'.$errMsg; ?></h1>
	<h2>行数：<?php echo $errFile . " 中的 " . $errLine; ?></h2>
	<font color="#666666"><?php echo \apphp\error\error::getError($errNo); ?></font><br><br>
	<div class="button">
		<a href="" title="返回上页">返回上页</a>
	</div>
</div>





</body></html>