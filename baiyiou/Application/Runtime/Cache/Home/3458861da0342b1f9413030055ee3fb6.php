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

 

<a href="demo.php">登录</a>
<h1>大苹果购物网</h1>
<div>
    <a href="#">浏览商品</a>&nbsp;&nbsp;
    <a href="ViewAccount.php">查看账户</a>&nbsp;&nbsp;
    <!--将商品总价传到购物车页面-->
    <a href="ViewCart.php?strpice=<?php echo $strPice ?>&ids=<?php echo $ids ?>">查看购物车</a>
</div>
<div>
    购物车中有<span id="spnum"><?php echo count($spattr); ?></span>种商品，总价格：<span id="sppice"><?php echo $strPice; ?></span>元。
</div>

<table width="100%" border="1">
    <tr>
        <th>代号</th>
        <th>水果名称</th>
        <th>水果价格</th>
        <th>原产地</th>
        <th>货架</th>
        <th>库存量</th>
        <th>操作</th>
    </tr>
    <?php foreach($flag as $k=>$v): ?>
            <tr>
                <td><?php echo $v['ids']; ?></td>
                <td><?php echo $v['name']; ?></td>
                <td><?php echo $v['price']; ?></td>
                <td><?php echo $v['source']; ?></td>
                <td><?php echo $v['stack']; ?></td>
                <td><?php echo $v['numbers']; ?></td>
                <td><form action="add/uid=<?php echo $_SESSION['tel'] ?>" method="post">
                    <input type="hidden" name="ids"
                    value="<?php echo $v[0]; ?>">
                    <button>购买</button>
                    
                </form></td>
            </tr>
        <?php endforeach;?>
   <!--  <span><?php echo $_GET["kc"] ?></span> -->
</table>

<!--
  <div class="addWrap"> 
   <div class="swipe" id="mySwipe"> 
    <div class="swipe-wrap"> 
     <div>
      <a href="javascript:;"><img class="img-responsive" src="/Public/images/pic5.jpg" /></a>
     </div> 
     <div>
      <a href="javascript:;"><img class="img-responsive" src="/Public/images/pic5.jpg" /></a>
     </div> 
     <div>
      <a href="javascript:;"><img class="img-responsive" src="/Public/images/pic5.jpg" /></a>
     </div> 
    </div> 
   </div> 
   <ul id="position"> 
    <li class="cur"></li> 
    <li></li> 
    <li></li> 
   </ul> 
  </div>
  <!--/addWrap--> 
  <!--<script src="/Public/js/hhSwipe.js" type="text/javascript"></script> 
  <script type="text/javascript">
var bullets = document.getElementById('position').getElementsByTagName('li');

var banner = Swipe(document.getElementById('mySwipe'), {
  auto: 4000,
  continuous: true,
  disableScroll:false,
  callback: function(pos) {
    var i = bullets.length;
    while (i--) {
      bullets[i].className = ' ';
    }
    bullets[pos].className = 'cur';
  }
})
</script> 
  <div class="product"> 

   <h1><?php echo ($flag["title"]); ?></h1> 
   <h2>￥<?php echo ($flag["price"]); ?></h2> 

    请选择产品规格
   </div> 
   <div class="product2"> 
    <ul> 
    <?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vi): $mod = ($i % 2 );++$i;?><li class="cur"><a href="<?php echo ($vi["id"]); ?>"><?php echo ($vi["type"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
    <!--  <li><a href="product?id=27&amp;ptype=68">60*200cm套装</a></li>    <div class="product1">

     <li><a href="#">80*150cm套装</a></li> 
     <li><a href="#">120*200cm套装</a></li> 
     <li><a href="#">120*300cm套装</a></li> 
     <li><a href="#">单膜60*200cm</a></li> 
     <li><a href="#">单膜80*150cm</a></li> 
     <li><a href="#">单膜60*120cm</a></li> 
     <li><a href="#">单膜120*300cm</a></li> 
     <li><a href="#">涂鸦笔芯12色</a></li>  -->

   <!-- </ul> 
   </div> 
   <div class="product3">
     购买数量 
    <span class="input-number-decrement">–</span>
    <input class="input-number" type="text" value="1" min="0" />
    <span class="input-number-increment">+</span> 
    <button class="button1">立刻购买</button>
    <!-- <a class="button2" href="/Home/Cart/index/price/<?php echo ($flag["price"]); ?>&id/<?php echo ($flag["id"]); ?>">加入购物车</a>  -->
  <!--  <a class="button2" href="">加入购物车</a> 
    <script src="http://www.zaole.net/sliding.js"></script> 
    <script>
    (function() {
  window.inputNumber = function(el) {
    var min = el.attr('min') || false;
    var max = el.attr('max') || false;
    var els = {};
    els.dec = el.prev();
    els.inc = el.next();
    el.each(function() {
      init($(this));
    });
    function init(el) {
      els.dec.on('click', decrement);
      els.inc.on('click', increment);
      function decrement() {
        var value = el[0].value;
        value--;
        if(!min || value >= min) {
          el[0].value = value;
        }
      }
      function increment() {
        var value = el[0].value;
        value++;
        if(!max || value <= max) {
          el[0].value = value++;
        }
      }
    }
  }
})();
inputNumber($('.input-number'));
    //@ sourceURL=pen.js
  </script> 
   </div> 
   <div class="product4"> 
    <h1>产品参数</h1> 
    <p><img src="/Public/images/pic6.jpg" /></p> 
    <p>采用优质环保的PVC+PET合成材料制成。</p> 
    <p>国际标准护眼绿色，不反光；</p> 
   </div> 
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