<?php
/**
 * @author huang.xuting
 *
 */
	header("Content-type: text/html; charset=GBK");
	include_once("netpayclient_config.php");

	//加载 netpayclient 组件
	include_once("netpayclient.php");
	//加载 CURL 函数库，该库由 chinapay 提供，方便您使用 curl 发送 HTTP 请求
	include_once("lib_curl.php");
	$client_sign = new netpayclient();
	//导入私钥文件, 返回值即为您的商户号，长度15位
	$merId = $client_sign->buildKey(PRI_KEY);
	if(!$merId) {
		echo "导入私钥文件失败！";
		exit;
	}
	
	//商户日期，本例采用当天日期，必填
	$merDate = date('Ymd');
	//生成流水号，定长16位，任意数字组合，一天内不允许重复，本例采用当前时间戳，必填
	$merSeqId = "00" . date('YmdHis');
	//收款账号，必填
	$cardNo = "6225882106897891";
	//收款人姓名，必填
	$usrName = "测试";
	//开户银行,本例采用工商银行，必填
	$openBank = "工商银行";
	//省份,本例采用上海，必填
	$prov = "上海";
	//城市,本例采用上海，必填
	$city = "上海";
	//订单金额，变长12位，以分为单位，必填
	$transAmt = "000000000001";
	//用途，可选
	$purpose = "hxt测试";  	
	//支行,可选，参与签名
	$subBank = "";	
	//付款标志,"00"对私，"01"对公，如不填则默认为对私
	$flag = "00";
	//接口版本号，境内支付为 20090501，必填
	$termType = "08";
	//渠道类型，必填
	$version = "20150304";	
	//签名标志，值固定，但不参与签名
	$signFlag = "1";
	
	
?>
<meta http-equiv="Content-Type" content="text/html; charset=GBK">
<title>单笔交易</title>
<h1>ORA交易测试</h1>
<h5><a href="javascript:window.location.reload()">刷新本页以改变订单号</a></h5>

<form action="ORA_OraTransGet_return.php" method="post" target="_blank">
<label>商户号 </label>
<input type="text" name="merId" value="<?php echo $merId; ?>" readonly/><br/><br/>
<label>商户日期 </label>
<input type="text" name="merDate" value="<?php echo $merDate; ?>" /><br/><br/>
<label>流水号</label>
<input type="text" name="merSeqId" value="<?php echo $merSeqId; ?>" /><br/><br/>
<label>收款账号</label>
<input type="text" name="cardNo" value="<?php echo $cardNo; ?>" /><br/><br/>
<label>收款人姓名</label>
<input type="text" name="usrName" value="<?php echo $usrName; ?>" /><br/><br/>
<label>开户银行</label>
<input type="text" name="openBank" value="<?php echo $openBank; ?>" /><br/><br/>
<label>省份</label>
<input type="text" name="prov" value="<?php echo $prov; ?>" /><br/><br/>
<label>城市</label>
<input type="text" name="city" value="<?php echo $city; ?>"/><br/><br/>
<label>金额</label>
<input type="text" name="transAmt" value="<?php echo $transAmt; ?>"/><br/><br/>
<label>用途</label>
<input type="text" name="purpose" value="<?php echo $purpose; ?>"/><br/><br/>
<label>支行</label>
<input type="text" name="subBank" value="<?php echo $subBank; ?>" /><br/><br/>
<label>付款标志</label>
<input type="text" name="flag" value="<?php echo $flag; ?>" /><br/><br/>
<label>版本号</label>
<input type="text" name="version" value="<?php echo $version; ?>" /><br/><br/>
<label>渠道编号</label>
<input type="text" name="termType" value="<?php echo $termType; ?>" /><br/><br/>
<label>签名标志</label>
<input type="text" name="signFlag" value="<?php echo $signFlag; ?>" /><br/><br/>
<input type="submit" value="提交">
</form>
