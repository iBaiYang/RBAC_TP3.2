<?php 
	require_once(".\php_rsa.php");
	require_once(".\util.php");
	$serverUrl=$_POST["serverUrl"];
	$charset=$_POST["charset"];
	$version=$_POST["version"];
	$mchtId=$_POST["mchtId"];
	$mchtOrderNo=$_POST["mchtOrderNo"];
	$orderDateTime=$_POST["orderDateTime"];
	$accountNo=$_POST["accountNo"];
	$accountName=$_POST["accountName"];
	$accountType=$_POST["accountType"];
	$bankNo=$_POST["bankNo"];
	$bankName=$_POST["bankName"];
	$amt=$_POST["amt"];
	$remark=$_POST["remark"];
	$notifyUrl = $_POST["notifyUrl"];
	$certificate =$_POST["certificate"];
	$password = '123456';
	
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
				   'mchtOrderNo' => $mchtOrderNo,   //商户订单号，不可重复                        
				   'accountNo' => $accountNo,  
				   'accountName' => $accountName,  
				   'accountType' => $accountType, 
				   'bankNo' => $bankNo,
				   'bankName' => $bankName, 
				   'amt' => $amt,  
				   'remark' => $remark,
				   'notifyUrl' => $notifyUrl, 
				   'orderDateTime' => $orderDateTime,
			   )
		   ),
		   'sign' => Array
		   (
			   'signType' => '1',          //签名方式，固定值，可取值：1代表数字证书
			   'certificate' =>$certificate,        // 预留字段暂不使用，不用填写
			   'signContent' => '',        //签名信息
		   ),
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
   $signMsg = private_sign($envelope,$backend,$password);
  // echo $signMsg;exit;WEl1XkJLgRpMmlWir4Ah+E9Dx+tI22PBKPMC4xQS8KMojqHud2Blc94eamb00ovj/XOKTc8w7k4RvQt+oF6xFI3dev5D0C274VDqwdsLmKUt4j2d0hLC5gDPl9TAlWS0c1U4wYZtSkZ+tBz5MaqTU26FHZjFuhyX6ai32TFffzY=
   $xml_str['request']['sign']['signContent']=$signMsg;
   $request_str=arr_to_xml($xml_str['request']);
   $request_str=trim(substr($request_str,strpos($request_str,"<request>")));
   //$str2= str_replace("<","&lt;",$request_str);
   //$str2= trim(str_replace(">","&gt;",$str2));
   //echo "【request原文】".$str2."<br/>";
   $send_str=base64_encode($request_str);
   $send_str="reqMsg=".urlencode($send_str);

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
   $return_str =base64_decode($return_str);


 
 $isError=false;
 $errorCode='';
 $errorMsg='';
 
 $envelope=substr($return_str, strpos($return_str, "<envelope>"), strpos($return_str, "</envelope>")+1);

 $return_arry=xml_to_array($return_str);

 $verifyMsg = trim($return_arry['response']['sign']['signContent']);
 //注意：php读取的公钥格式要65个字符一换行
 //echo $envelope,'<br >',$verifyMsg;exit;
 $verify = public_verify($envelope,"./ops-test.cer",$verifyMsg);
 //var_dump($verify);exit;
 if(!$verify){ 	 	
 	echo "验签未通过";
 }
 
 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta http-equiv="Content-Language" content="zh-CN"/>
	<meta http-equiv="Expires" content="0" />        
	<meta http-equiv="Cache-Control" content="no-cache" />        
	<meta http-equiv="Pragma" content="no-cache" />
	<title>填写付款信息-开联代付</title>
	<!-- css 引用开始 -->
	<link href="style.css?v201105.css" rel="stylesheet" type="text/css" />
	<!-- css 引用结束 -->
</head>
<BODY>
<!-- div main start -->
<DIV id="main">
<center>
<center> <h2>单笔实时代付 - 联机交易测试</h2></center>
<font face="Verdana, Arial, Helvetica, sans-serif" size="+1"><a href="singlepayApply.html">单笔实时代付</a></font>
<font face="Verdana, Arial, Helvetica, sans-serif" size="+1"><a href="singlePayQuery.html">单笔代付查询</a></font>
</center>

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
</BODY>
</HTML>