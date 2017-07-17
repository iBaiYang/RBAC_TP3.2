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
	$stat = $_REQUEST["stat"];
	$version = $_REQUEST["version"];
	$signFlag = $_REQUEST["signFlag"];
	
	//按次序组合报文信息为待签名串
	$plain = $merId . $fromDate  . $toDate  . $stat . $version;
	//进行Base64编码
	$signData = base64_encode($plain);
	//生成签名值，必填
	$chkvalue = sign($signData);
	if (!$chkvalue) {
		echo "签名失败！";
		exit;
	}
?>
<title>批量退单查询</title>
<h1>批量退单查询</h1>
<?php		
	if(($fromDate!='')&&($toDate!='')){
		$http = HttpInit();
		$post_data = "merId=$merId&fromDate=$fromDate&toDate=$toDate&stat=$stat&version=$version&signFlag=$signFlag&chkValue=$chkvalue";
		$output = HttpPost($http, $post_data, BatchOrder_URL_QRY);
		
		if($output){
			$output = trim(strip_tags($output));
			
			echo "<h2>查询返回</h2>";
			echo htmlspecialchars($output) . "<br/>";
			echo "=================================<br/>";
			//开始解析数据
			$datas = explode("\r\n",$output);
			$i = 1;
			foreach($datas as $data){	
				echo "Line = $i 返回报文：<br/> " . "$data<br/>";
			    $dex = strripos($data,"|");
			    $plain = substr($data,0,$dex + 1);
			    echo "验签明文：<br/>" . $plain . "<br/>";
			    $plaindata = base64_encode($plain);	;
			    $chkValue = substr($data,$dex + 1);
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
					$i++;							
				    } else {
					    echo "<h4>验证签名失败！</h4>";
					}
					echo "=================================<br/>";	
				}						
			}			
		} else {
			echo "<h3>HTTP 请求失败！</h3>";
		}
		HttpDone($http);
	} else {
		echo "<h3>请填写退单起始日期</h3>";
	}
	
?>