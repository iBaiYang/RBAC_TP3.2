<?php
/**
 * @author huang.xuting
 *
 */
	header('Content-type: text/html; charset=gbk');
	include_once("netpayclient_config.php");

	//加载 netpayclient 组件
	include_once("netpayclient.php");
	//加载 CURL 函数库，该库由 chinapay 提供，方便您使用 curl 发送 HTTP 请求
	include_once("lib_curl.php");
	
	//导入私钥文件, 返回值即为您的商户号，长度15位
	$merId = buildKey(PRI_KEY);	
	//接口版本号，境内支付为 20090501，必填
	$version = "20090501";	
	//签名标志，值固定，但不参与签名
	$signFlag = "1";
?>
<title>单笔查询</title>
<h1>单笔查询</h1>
<form action="ORA_SingleOrderQuery_return.php" method="post">
<label>商户号</label><br/>
<input type="text" name="merId" value="<?php echo $merId; ?>" readonly/><br/>
<label>版本号</label><br/>
<input type="text" name="version" value="<?php echo $version; ?>" readonly/><br/>
<label>签名标志</label><br/>
<input type="text" name="signFlag" value="<?php echo $signFlag; ?>" readonly/><br/>
<label>原订单日期</label><br/>
<input type="text" name="merDate" value="20120301"><br/>
<label>原流水号</label><br/>
<input type="text" name="merSeqId" value="0020120301105637"><br/>
<input type="submit" value="查询">
</form>