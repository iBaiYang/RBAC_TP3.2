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
	if(!$merId) {
		echo "导入私钥文件失败！";
		exit;
	}	
	$fromDate = $_REQUEST["fromDate"];
	$toDate = $_REQUEST["toDate"];
	$type = $_REQUEST["type"];
	$version = $_REQUEST["version"];
	$signFlag = $_REQUEST["signFlag"];
	
	//按次序组合报文信息为待签名串
	$plain = $merId . $fromDate  . $toDate  . $type . $version;
	//进行Base64编码
	$signData = base64_encode($plain);
	//生成签名值，必填
	$chkvalue = sign($signData);
	if (!$chkvalue) {
		echo "签名失败！";
		exit;
	}
?>
<title>备付金明细查询</title>
<h1>备付金明细查询</h1>
<?php
	if(($fromDate!='')&&($toDate!='')){		
		$http = HttpInit();
		$post_data = "merId=$merId&fromDate=$fromDate&toDate=$toDate&type=$type&version=$version&signFlag=$signFlag&chkValue=$chkvalue";
		$output = HttpPost($http, $post_data, DepositDetail_URL_QRY);
		
		if($output){
			$output = trim(strip_tags($output));
			echo "<h2>查询返回</h2>";
			echo htmlspecialchars($output) . "<br/>";
			echo "=================================<br/>";
			//开始解析数据
			$datas = explode("\r\n",$output);
			$extracted_data = array();
			foreach($datas as $data){
				echo "$data<br/>";
			}
			
			echo "=================================<br/>";
			
			$dex = strlen($output) - 256;
			$plain = substr($output,0,$dex);
			echo "验签明文：<br/>" . $plain . "<br/>";
			$plaindata = base64_encode($plain);
			$chkValue = substr($output,$dex);
			echo "chkValue值：<br/>" . $chkValue . "<br/>";
				
			//开始验证签名，首先导入公钥文件
			$flag = buildKey(PUB_KEY);
			if(!$flag) {
				echo "导入公钥文件失败！";
			} else {
				$flag  =  verify($plaindata, $chkValue);
				if($flag) {
				//验证签名成功，
				echo "<h4>验证签名成功</h4>";
				//请把您自己需要处理的逻辑写在这里
												
				} else {
					echo "<h4>验证签名失败！</h4>";
					}
			}
				
		} else {
			echo "<h3>HTTP 请求失败！</h3>";
		}
		HttpDone($http);
	} else {
		echo "<h3>请填写备付金明细查询起始日期</h3>";
	}
?>