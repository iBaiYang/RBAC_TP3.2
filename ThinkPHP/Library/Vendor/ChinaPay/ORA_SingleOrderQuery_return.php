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
	$merSeqId = $_REQUEST["merSeqId"];
	$merDate = $_REQUEST["merDate"];
	$version = $_REQUEST["version"];
	$signFlag = $_REQUEST["signFlag"];
	
	//按次序组合报文信息为待签名串
	$plain = $merId . $merDate  . $merSeqId . $version;
	//进行Base64编码
	$data = base64_encode($plain);
	//生成签名值，必填
	$chkValue = sign($data);
	if (!$chkValue) {
		echo "签名失败！";
		exit;
	}
?>
<title>单笔查询</title>
<h1>单笔查询</h1>
<?php		
	if(($merSeqId!='')&&($merDate!='')){
	    $http = HttpInit();
		$post_data = "merId=$merId&merDate=$merDate&merSeqId=$merSeqId&version=$version&signFlag=$signFlag&chkValue=$chkValue";
		$output = HttpPost($http, $post_data, QRY_URL);
		
		if($output){
			$output = trim(strip_tags($output));
			
			echo "<h2>查询返回</h2>";
			echo htmlspecialchars($output) . "<br/>";
			echo "=================================<br/>";
			//开始解析数据
			$datas = explode("|",$output);
			foreach($datas as $data){
				echo "$data<br/>";
			}
			
			echo "=================================<br/>";
			
			$dex = strripos($output,"|");
			$plain = substr($output,0,$dex + 1);
			echo "验签明文：<br/>" . $plain . "<br/>";
			$plaindata = base64_encode($plain);	
			$resp_code = $data[0];
			$chkValue = substr($output,$dex + 1);
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
		echo "<h3>请填写订单日期和订单号</h3>";
	}
?>