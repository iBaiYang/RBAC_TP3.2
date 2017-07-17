<?php
/**
 * @author huang.xuting
 *
 */
	// header('Content-type: text/html; charset=GBK');
	include_once("netpayclient_config.php");

	//加载 netpayclient 组件
	include_once("netpayclient.php");
	//加载 CURL 函数库，该库由 chinapay 提供，方便您使用 curl 发送 HTTP 请求
	include_once("lib_curl.php");
	
	$client_sign = new netpayclient();
	//导入私钥文件, 返回值即为您的商户号，长度15位
	$merId = $client_sign->buildKey(PRI_KEY);
	if(!$merId) {
		$re['status'] = 0;
		$re['msg']    = '导入私钥文件失败！';
 	}else{
 		extract($data);
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
		
		//按次序组合报文信息为待签名串
		$plain = $merId . $merDate  . $merSeqId . $cardNo . $usrName  . $openBank  . $prov  . $city  . $transAmt  . $purpose  . $subBank  . $flag  . $version . $termType;
		//进行Base64编码
		$data = base64_encode($plain);
		//生成签名值，必填
		$chkValue = $client_sign->sign($data);
		if (!$chkValue) {
			$re['status'] = 0;
			$re['msg']    = '签名失败！';
		}else{
			$usrName = urlencode($usrName);         
			$openBank = urlencode($openBank);       
			$prov = urlencode($prov);                
			$city = urlencode($city);                
			$purpose = urlencode($purpose);          
			$subBank = urlencode($subBank);          

			if(($merSeqId!='')&&($merDate!='')){
			    $http = HttpInit();
				$post_data = "merId=$merId&merDate=$merDate&merSeqId=$merSeqId&cardNo=$cardNo&usrName=$usrName&openBank=$openBank&prov=$prov&city=$city&transAmt=$transAmt&purpose=$purpose&subBank=$subBank&flag=$flag&version=$version&termType=$termType&signFlag=$signFlag&chkValue=$chkValue";
				$output = HttpPost($http, $post_data, PAY_URL);
				
				if($output){
					$output = trim(strip_tags($output));
					//开始解析数据
					$datas = explode("&",$output);
					$dex = strripos($output,"&");
					$plain = substr($output,0,$dex);
					$plaindata = base64_encode($plain);	
					$resp_code = $data[0];
					$chkValue = substr($output,$dex+ 10);
						
					//开始验证签名，首先导入公钥文件
					$flag = $client_sign->buildKey(PUB_KEY);
					if(!$flag) {
						$re['status'] = 0;
						$re['msg']    = '导入公钥文件失败！';
					} else {
						$flag  =  $client_sign->verify($plaindata, $chkValue);
						if($flag) {
							//验证签名成功，
			                $re['status'] = 1;
			                
			                foreach($datas as $data){
			                    $temp = explode("=",$data);
			                    $re[$temp[0]] = $temp[1];
			                }
						//请把您自己需要处理的逻辑写在这里
						} else {
							$re['status'] = 0;
							$re['msg']    = '验证签名失败！';
						}
					}
				} else {
					$re['status'] = 0;
					$re['msg']    = '请求失败！';
				}
				HttpDone($http);
			} else {
				$re['status'] = 0;
				$re['msg']    = '请填写订单日期和订单号';
			}
		}		
 	}
?>