<?php if (!defined('THINK_PATH')) exit();?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0,user-scalable=no" name="viewport" id="viewport" />
<title>微信商城</title>
<link href="/Public/css/web.css" type="text/css" rel="stylesheet">
</head>

<body style="max-width:640px; background-color:#5a93cf">
<div class="top"><a href="/Home/Index/index" title="返回首页"><img src="/Public/images/home.png"></a></div>

 ﻿

<div class="personal_1">
<img src="/Public/images/pic8.jpg">
<p>张佑赫    男<br>
运营董事<br>
13904110810</p>
</div>

	<div class="personal">
		<ul>
			<li><img src="/Public/images/personal3.png">收货地址</li>
		</ul>
	</div>
	<div class="address">
		<form action="add" method="post">

			<dl>
				<dt><img src="/Public/images/contact1.png">张三 <span>13612345678</span></dt>

				<dd>辽宁省大连市沙河口区昌平街61号801室辽宁省大连市沙河口区昌平街61号</dd>

				<input name="pass" type="checkbox" class="checkbox" id="d" value="X" checked>

				<label for="d" class="checkbox">默认地址</label>

				<a href="#"><img src="/Public/images/delete.jpg" height="25" >删除</a>

			</dl>
			<button class="button4">添加新地址</button>	
		</form>		
	</div>



<div class="bottom">
		<ul>
			<li><a href="/Home/Personal/index"><img src="/Public/images/personal.png"></a></li>
			<li><a href="/Home/Order/index"><img src="/Public/images/order.png"></a></li>
			<li><a href="/Home/Cart/index"><img src="/Public/images/shopping.png"></a></li>
			<li><a href="/Home/Classify/index"><img src="/Public/images/classify.png"></a></li>
		</ul>
	</div>
</body>
</html>