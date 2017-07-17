<?php 
ini_set('date.timezone','Asia/Shanghai');
$orderid = I('get.orderid');
$orderamt = I('get.orderamt');
if(!$orderid || !$orderamt){
	$this->redirect('Index/index');
}
//error_reporting(E_ERROR);
$PATH = explode('example', dirname(__FILE__)) ;
$PATH = str_replace('\\', '/', $PATH[0]);

require_once $PATH."lib/WxPay.Api.php";
require_once "WxPay.JsApiPay.php";
require_once 'log.php';

//初始化日志
$logHandler= new CLogFileHandler("../logs/".date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);

//打印输出数组信息
// function printf_info($data)
// {
//     foreach($data as $key=>$value){
//         echo "<font color='#00ff55;'>$key</font> : $value <br/>";
//     }
// }

//①、获取用户openid
$tools = new JsApiPay();
$openId = $tools->GetOpenid($data);

//②、统一下单
$input = new WxPayUnifiedOrder();
$input->SetBody("test");
$input->SetAttach("test");
$input->SetOut_trade_no($orderid); // 商户订单号
$input->SetTotal_fee($orderamt * 100);   // 商户订单金额（元转为分）
$input->SetTime_start(date("YmdHis"));
$input->SetTime_expire(date("YmdHis", time() + 600));
$input->SetGoods_tag("test");
$input->SetNotify_url($_SERVER['SERVER_NAME'].U('wx_pay_cal')); // 微信支付回调页面
$input->SetTrade_type("JSAPI");
$input->SetOpenid($openId);
$order = WxPayApi::unifiedOrder($input);
// echo '<font color="#f00"><b>统一下单支付单信息</b></font><br/>';
// printf_info($order);
$jsApiParameters = $tools->GetJsApiParameters($order);

//获取共享收货地址js函数参数
$editAddress = $tools->GetEditAddressParameters();

//③、在支持成功回调通知中处理成功之后的事宜，见 notify.php
/**
 * 注意：
 * 1、当你的回调地址不可访问的时候，回调通知会失败，可以通过查询订单来确认支付是否成功
 * 2、jsapi支付时需要填入用户openid，WxPay.JsApiPay.php中有获取openid流程 （文档可以参考微信公众平台“网页授权接口”，
 * 参考http://mp.weixin.qq.com/wiki/17/c0f37d5704f0b64713d5d2c37b468d75.html）
 */
?>

<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/> 
    <title>微信支付</title>
    <style>
		body,a,h1,ul,li,em,i{
			margin:0;
			padding:0;
		}
		h1{
			font-size:16px;
			font-weight: normal;
			color:#222;
			text-align: center;
			padding-top:20px;
			padding-bottom:10px;
		}
		b{
			display:block;
			text-align: center;
			font-size:30px;
		}
		.dt{
			width:100%;
			padding:0 10px;
			box-sizing: border-box;
			overflow: hidden;
			margin-top:30px;
			padding-bottom:8px;
			border-bottom:1px solid #ccc;
		}
		.dt em{
			display:block;
			float: left;
			font-style: normal;
			color:#666;
			font-size:14px;
		}
		.dt i{
			display:block;
			float: right;
			font-style: normal;
			color:#444;
			font-size:14px;
		}
		a{
			display:block;
			width:90%;
			margin-left:5%;
			text-align: center;
			line-height: 40px;
			background-color: #46bf1a;
			color:white;
			border-radius: 4px;
			margin-top:20px;
			font-size:18px;
		}
	</style>
    <script type="text/javascript">
	//调用微信JS api 支付
	function jsApiCall()
	{
		WeixinJSBridge.invoke(
			'getBrandWCPayRequest',
			<?php echo $jsApiParameters; ?>,
			function(res){
				// WeixinJSBridge.log(res.err_msg);
				// alert(res.err_code+res.err_desc+res.err_msg);
				window.location.href='http://'+"<?php echo $_SERVER['SERVER_NAME'].U('User/personal')?>";
			}
		);
	}

	function callpay()
	{
		if (typeof WeixinJSBridge == "undefined"){
		    if( document.addEventListener ){
		        document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
		    }else if (document.attachEvent){
		        document.attachEvent('WeixinJSBridgeReady', jsApiCall); 
		        document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
		    }
		}else{
		    jsApiCall();
		}
	}
	</script>
	<script type="text/javascript">
	//获取共享地址
	function editAddress()
	{
		WeixinJSBridge.invoke(
			'editAddress',
			<?php echo $editAddress; ?>,
			function(res){
				var value1 = res.proviceFirstStageName;
				var value2 = res.addressCitySecondStageName;
				var value3 = res.addressCountiesThirdStageName;
				var value4 = res.addressDetailInfo;
				var tel = res.telNumber;
				
				alert(value1 + value2 + value3 + value4 + ":" + tel);
			}
		);
	}
	
	window.onload = function(){
		// if (typeof WeixinJSBridge == "undefined"){
		//     if( document.addEventListener ){
		//         document.addEventListener('WeixinJSBridgeReady', editAddress, false);
		//     }else if (document.attachEvent){
		//         document.attachEvent('WeixinJSBridgeReady', editAddress); 
		//         document.attachEvent('onWeixinJSBridgeReady', editAddress);
		//     }
		// }else{
		// 	editAddress();
		// }
	};
	
	</script>
</head>
<body>
	<h1>大富翁</h1>
	<b>￥ <?php echo sprintf("%.2f", $_GET['orderamt']);?></b>
	<div class="dt">
		<em>商品</em><i>购买<?php echo $_GET['orderamt'] * C('RMB2DIAM_RATIO') ; ?>钻石，附赠<?php echo $_GET['orderamt'] * C('RMB2DIAM_RATIO') * C('DIAM2JD_RATIO')  ?>金豆</i>
	</div>
	<a onclick="callpay()">立即支付</a>
</body>
</html>