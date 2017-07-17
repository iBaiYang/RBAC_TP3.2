<?PHP
	//页面编码要与参数inputCharset一致，否则服务器收到参数值中的汉字为乱码而导致验证签名失败。	
	$re['serverUrl']='https://pg.openepay.com/gateway/index.do'; // 提交地址
	$re['inputCharset']=1; // 字符集
	$re['pickupUrl']=$_SERVER['HTTP_ORIGIN'].U('User/info');  // 支付成功跳转页面
	$re['receiveUrl']='http://kpm.shbiaowei.cn'.U('cal_inprice_OpenePay'); // 支付成功异步通知页面
	$re['version']='v1.0'; // 版本号
	$re['language']=1; // 语言
	$re['signType']=1; // 签名类型
	$re['merchantId']='102900170426010'; // 商户号
	$re['payerName']=''; // 付款人姓名
	$re['payerEmail']=''; // 付款人联系email
	$re['payerTelephone']=''; // 付款人电话
	$re['orderNo']=$data['sn']; // 商户系统订单号
	$re['orderAmount']=$data['price'] * 100; // 订单金额(单位分)
	$re['orderDatetime']=date('YmdHis', $data['add_time']); // 商户订单提交时间*
	$re['orderCurrency']=156; // 订单金额币种类型
	$re['orderExpireDatetime']=''; // 过期时间
	$re['productName']='积分充值'; // 商品名称
	$re['productId']=001; // 商品标识
	$re['productPrice']=$data['price']; // 商品单价
	$re['productNum']=1; // 商品数量
	$re['productDesc']='购买商品积分'; // 商品描述
	$re['ext1']=''; // 拓展字段1
	$re['ext2']=''; // 拓展字段2
	$re['extTL']=''; // 业务拓展字段
	$re['payType']=0; // 字符方式   不能为空，必须放在表单中提交。*
	$re['issuerId']=''; //发卡方代码 直联时不为空，必须放在表单中提交。
	
	$key='kuku0427'; // 用于计算signMsg的key值*

	// 生成签名字符串。
	$bufSignSrc=""; 
	if($re['inputCharset'] != "")
	$bufSignSrc=$bufSignSrc."inputCharset=".$re['inputCharset']."&";		
	if($re['pickupUrl'] != "")
	$bufSignSrc=$bufSignSrc."pickupUrl=".$re['pickupUrl']."&";		
	if($re['receiveUrl'] != "")
	$bufSignSrc=$bufSignSrc."receiveUrl=".$re['receiveUrl']."&";		
	if($re['version'] != "")
	$bufSignSrc=$bufSignSrc."version=".$re['version']."&";		
	if($re['language'] != "")
	$bufSignSrc=$bufSignSrc."language=".$re['language']."&";		
	if($re['signType'] != "")
	$bufSignSrc=$bufSignSrc."signType=".$re['signType']."&";		
	if($re['merchantId'] != "")
	$bufSignSrc=$bufSignSrc."merchantId=".$re['merchantId']."&";		
	if($re['payerName'] != "")
	$bufSignSrc=$bufSignSrc."payerName=".$re['payerName']."&";		
	if($re['payerEmail'] != "")
	$bufSignSrc=$bufSignSrc."payerEmail=".$re['payerEmail']."&";		
	if($re['payerTelephone'] != "")
	$bufSignSrc=$bufSignSrc."payerTelephone=".$re['payerTelephone']."&";			
		
	if($re['orderNo'] != "")
	$bufSignSrc=$bufSignSrc."orderNo=".$re['orderNo']."&";
	if($re['orderAmount'] != "")
	$bufSignSrc=$bufSignSrc."orderAmount=".$re['orderAmount']."&";
	if($re['orderCurrency'] != "")
	$bufSignSrc=$bufSignSrc."orderCurrency=".$re['orderCurrency']."&";
	if($re['orderDatetime'] != "")
	$bufSignSrc=$bufSignSrc."orderDatetime=".$re['orderDatetime']."&";
	if($re['orderExpireDatetime'] != "")
	$bufSignSrc=$bufSignSrc."orderExpireDatetime=".$re['orderExpireDatetime']."&";
	if($re['productName'] != "")
	$bufSignSrc=$bufSignSrc."productName=".$re['productName']."&";
	if($re['productPrice'] != "")
	$bufSignSrc=$bufSignSrc."productPrice=".$re['productPrice']."&";
	if($re['productNum'] != "")
	$bufSignSrc=$bufSignSrc."productNum=".$re['productNum']."&";
	if($re['productId'] != "")
	$bufSignSrc=$bufSignSrc."productId=".$re['productId']."&";
	if($re['productDesc'] != "")
	$bufSignSrc=$bufSignSrc."productDesc=".$re['productDesc']."&";
	if($re['ext1'] != "")
	$bufSignSrc=$bufSignSrc."ext1=".$re['ext1']."&";
	if($re['ext2'] != "")
	$bufSignSrc=$bufSignSrc."ext2=".$re['ext2']."&";
	if($re['extTL'] != "")
	$bufSignSrc=$bufSignSrc."extTL".$re['extTL']."&";
	if($re['payType'] !== "")
	$bufSignSrc=$bufSignSrc."payType=".$re['payType']."&";		
	if($re['issuerId'] != "")
	$bufSignSrc=$bufSignSrc."issuerId=".$re['issuerId']."&";
	
	$bufSignSrc=$bufSignSrc."key=".$key; //key为MD5密钥，密钥是在开联通支付网关商户服务网站上设置。
	
	//签名，设为signMsg字段值。
	$re['signMsg'] = strtoupper(md5($bufSignSrc));
	return $re;