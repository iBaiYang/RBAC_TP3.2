<?php
	require_once("php_rsa.php");  //请修改参数为php_rsa.php文件的实际位置
	$merchantId=$_POST["merchantId"];
	$version=$_POST['version'];
	$language=$_POST['language'];
	$signType=$_POST['signType'];
	$payType=$_POST['payType'];
	$issuerId=$_POST['issuerId'];
	$mchtOrderId=$_POST['mchtOrderId'];
	$orderNo=$_POST['orderNo'];
	$orderDatetime=$_POST['orderDatetime'];
	$orderAmount=$_POST['orderAmount'];
	$payDatetime=$_POST['payDatetime'];
	$ext1=$_POST['ext1'];
	$ext2=$_POST['ext2'];
	$payResult=$_POST['payResult'];
	$signMsg=$_POST["signMsg"];
	
	$bufSignSrc="";
	if($merchantId != "")
	$bufSignSrc=$bufSignSrc."merchantId=".$merchantId."&";		
	if($version != "")
	$bufSignSrc=$bufSignSrc."version=".$version."&";		
	if($language != "")
	$bufSignSrc=$bufSignSrc."language=".$language."&";		
	if($signType != "")
	$bufSignSrc=$bufSignSrc."signType=".$signType."&";		
	if($payType != "")
	$bufSignSrc=$bufSignSrc."payType=".$payType."&";
	if($issuerId != "")
	$bufSignSrc=$bufSignSrc."issuerId=".$issuerId."&";
	if($mchtOrderId != "")
	$bufSignSrc=$bufSignSrc."mchtOrderId=".$mchtOrderId."&";
	if($orderNo != "")
	$bufSignSrc=$bufSignSrc."orderNo=".$orderNo."&";
	if($orderDatetime != "")
	$bufSignSrc=$bufSignSrc."orderDatetime=".$orderDatetime."&";
	if($orderAmount != "")
	$bufSignSrc=$bufSignSrc."orderAmount=".$orderAmount."&";
	if($payDatetime != "")
	$bufSignSrc=$bufSignSrc."payDatetime=".$payDatetime."&";
	if($ext1 != "")
	$bufSignSrc=$bufSignSrc."ext1=".$ext1."&";
	if($ext2 != "")
	$bufSignSrc=$bufSignSrc."ext2=".$ext2."&";
	if($payResult != "")
	$bufSignSrc=$bufSignSrc."payResult=".$payResult;
	
	//验签
	//解析publickey.txt文本获取公钥信息
	$publickeyfile = VENDOR_PATH.'OpenePay/publickey-prd.txt';
	$publickeycontent = file_get_contents($publickeyfile);
	//echo "<br>".$content;
	$publickeyarray = explode(PHP_EOL, $publickeycontent);
	$publickey = explode('=',$publickeyarray[0]);
	
	//去掉publickey[1]首尾可能的空字符
	$publickey[1]=trim($publickey[1]);
	$modulus = explode('=',$publickeyarray[1]);
	//去掉modulus[1]首尾可能的空字符
	$modulus[1]=trim($modulus[1]);
	
	$keylength = 2048;
	//验签结果
 	$verifyResult = rsa_verify($bufSignSrc,$signMsg, $publickey[1], $modulus[1], $keylength,"sha1");
 
	if($verifyResult){
		if($payResult == 1){
			D('KhJoinjin')->kh_joinjin_cal(array('out_trade_no'=>$orderNo, 'total_fee'=>$orderAmount));
			echo 'success';
		}
	}