	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta http-equiv="Content-Language" content="zh-CN"/>
		<meta http-equiv="Expires" content="0" />        
		<title>开联通互联网支付平台-商户接口范例-单笔代付查询确认</title>
		<link href="css.css" rel="stylesheet" type="text/css" />
	</head>
	<body>
	<center> <font size=16><strong>单笔代付查询确认</strong></font></center>
	<?PHP
	require_once("./php_rsa.php");
	require_once("./util.php");
	$serverUrl = $_POST["serverUrl"];
	$serverIP = $_POST["serverIP"];
	$merchantPrivateCertificatePassword = $_POST["merchantPrivateCertificatePassword"];
	$version = $_POST["version"];
	$charset=$_POST["charset"];
	$mchtId=$_POST["mchtId"];
	$mchtBatchNo=$_POST["mchtBatchNo"];
	$mchtOrderNo = $_POST["mchtOrderNo"];
	$paymentBusinessType=$_POST["paymentBusinessType"];
	$signType = $_POST["signType"];
	$certificate=$_POST["certificate"];
	$orderDatetime = $_POST["orderDatetime"];
	$queryDatetime = $_POST["queryDatetime"];
    //var_dump($charset);exit;

	$xml_str=Array
   (
	   'request'=>Array(
		   'envelope' => Array                  //注意：envelope内各元素的顺序必须固定如下
		   (
			   'head' => Array
			   (
				   'version' => $version,    
				   'charset' => $charset,       //字符集，固定值:UTF-8
			   ),
   
			   'body' => Array
			   (   
				   
				   'mchtId' => $mchtId,     		//商户号
				   'mchtOrderNo'=>$mchtOrderNo,
				   'mchtBatchNo'=>$mchtBatchNo,
				   'paymentBusinessType'=>$paymentBusinessType
			   )
		   ),
		   'sign' => Array
		   (
			   'signType' => '1', //签名方式，固定值，可取值：1代表数字证书
			   'certificate' =>'',// 预留字段暂不使用，不用填写
			   'signContent' =>'',        //签名信息
		   )
	   )
   );

   $str=arr_to_xml($xml_str['request']['envelope']);
  
   $envelope=trim(substr($str,39,strlen($str))); 
   
   //echo "envelope:".$envelope;echo "<br/>";
   //$str2= str_replace("<","&lt;",$envelope);
   //$str2= trim(str_replace(">","&gt;",$str2));
   //echo "【envelope原文】".$str2."<br/>";
   //签名
   $backend="./test-rsa.pfx";
   $signMsg = private_sign($envelope,$backend,$merchantPrivateCertificatePassword);
   //echo $signMsg;exit;WEl1XkJLgRpMmlWir4Ah+E9Dx+tI22PBKPMC4xQS8KMojqHud2Blc94eamb00ovj/XOKTc8w7k4RvQt+oF6xFI3dev5D0C274VDqwdsLmKUt4j2d0hLC5gDPl9TAlWS0c1U4wYZtSkZ+tBz5MaqTU26FHZjFuhyX6ai32TFffzY=
   $xml_str['request']['sign']['signContent']=$signMsg;
   $request_str=arr_to_xml($xml_str['request']);
   $request_str=trim(substr($request_str,strpos($request_str,"<request>")));
   //var_dump($request_str);
   //$str2= str_replace("<","&lt;",$request_str);
   //$str2= trim(str_replace(">","&gt;",$str2));
   //echo "【request原文】".$str2."<br/>";
   $send_str=base64_encode($request_str);
   $send_str="reqMsg=".urlencode($send_str);
//var_dump($send_str);
   $header = array(
	   "content-type: application/x-www-form-urlencoded;
	   charset=UTF-8"
   );

   $curl = curl_init();
   curl_setopt($curl, CURLOPT_URL,$serverUrl);
   curl_setopt($curl, CURLOPT_HTTPHEADER,$header);
   curl_setopt($curl, CURLOPT_POST, true);
   curl_setopt($curl, CURLOPT_POSTFIELDS, $send_str);
   curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
   $return_str = curl_exec($curl);
   
//var_dump($return_str);
   $return_str =base64_decode($return_str);

 
 $isError=false;
 $errorCode='';
 $errorMsg='';
 
 $envelope=substr($return_str, strpos($return_str, "<envelope>"), strpos($return_str, "</envelope>")+1);
 //var_dump($return_str);
 $return_arry=xml_to_array($return_str);
 //var_dump($return_arry);exit;
 $verifyMsg = trim($return_arry['response']['sign']['signContent']);
 //注意：php读取的公钥格式要65个字符一换行
 //echo $envelope,'<br >',$verifyMsg;exit;
 $verify = public_verify($envelope,"./public-rsa.cer",$verifyMsg);
 //var_dump($verify);exit;
 if(!$verify){ 	 	
 	echo "验签未通过";
 }
 
	?>
		<table class="table_box" width="90%" align=center>
		   <tr class="tit_bar">
		      <td colspan="2" class="tit_bar">提交的查询表单参数</td>
		   </tr>
		     
		   <tr><td>1</td><td>接口版本号: <?=$version?></td>
		   </tr>
		   <tr>
		      <td>2</td><td>字符集: <?=$charset?></td>
		   </tr>
		   <tr>
		      <td>3</td><td>商户号: <?=$mchtId?></td>
		   </tr>
		   <tr>
		      <td>4</td><td>商户批次号: <?=$mchtBatchNo?></td>
		   </tr>
		   <tr>
	        	<td>5</td><td>商户订单号: <?=$mchtOrderNo?></td>
		   </tr>
		   <tr>
				<td>6</td><td>签名方式: <?=$signType?></td>
		   </tr>
		    <tr>
				<td>7</td><td>预留字段: <?=$certificate?></td>
		   </tr>
		   <tr>
				<td>8</td><td>商户订单提交时间: <?=$orderDatetime ?></td>
		   </tr>
		   <tr>
				<td>9</td><td>商户提交查询时间: <?=$queryDatetime?></td>
		   </tr> 
		   <tr>
				<td>10</td><td>签名串: <?=$signMsg?></td></tr>
		</table>
		

<!-- div main start -->
<DIV id="main">

<form name="form1" action="" method="post" class="formsty">
<input type="hidden" id="live1" name="live1" value="live"/>
<table cellpadding="3">
  <tr>
	<td  valign="top">
	<table  cellpadding="2"> 		
		<tr>
			<td colspan="2" bgcolor="#cccccc"> ◇ 订单信息</td>
		</tr>
	 	<tr>
	  		<td><textarea name="reqMsg" rows="20" cols="110">
	  		<?php 
	  			print $request_str;
	  		?>
	  		
	  		</textarea></td>
	  	</tr>	
	  	<tr>
			<td  colspan="2" bgcolor="#cccccc"> ◇ 返回信息</td>
		</tr>
 		<tr>
	  		<td><textarea name="resMsg" rows="20" cols="110">
	  		<?php 
	  		 	if(!$isError)
	  			print $return_str;
	  		?>
	  		</textarea>
	  		</td>
	  	</tr>
	  	<tr>
			<td  colspan="2" bgcolor="#cccccc"> ◇ 错误信息</td>
		</tr>
	  	<tr>
	  		<td>
	  		<?php 
	  			if($isError){
	  				print $errorCode;
	  				print $errorMsg;
	  			}
	  		?>
	  		</td>
	  	</tr>
	</table>
  </tr>
  
  </tr>
</table>
</form>
</DIV>
<center>
	<P>开联通网络技术服务有限公司 </P>
</center>
