<%@ page contentType="text/html; charset=UTF-8" import="com.smartpay.ops.client.PaymentResult"%>
<%
			System.out.println("Server Receive Confirm...");
			request.setCharacterEncoding("UTF-8");
			String result = "";
			String cert = "/opsapp/config/ops-test.cer"; //测试
			//cert = "/opsapp/config/ops-prod.cer";		 //生产
			String key = "1234567890";//根据实际情况修改
			System.out.println("receive cert " + cert);
			PaymentResult paymentResult = new PaymentResult();
			paymentResult.setMerchantId(request.getParameter("merchantId"));
			System.out.println("merchantId=" + request.getParameter("merchantId"));
			paymentResult.setVersion(request.getParameter("version"));
			System.out.println("version=" + request.getParameter("version"));
			paymentResult.setLanguage(request.getParameter("language"));
			System.out.println("language=" + request.getParameter("language"));
			paymentResult.setSignType(request.getParameter("signType"));
			System.out.println("signType=" + request.getParameter("signType"));
			paymentResult.setPayType(request.getParameter("payType"));
			System.out.println("payType=" + request.getParameter("payType"));
			paymentResult.setIssuerId(request.getParameter("issuerId"));
			System.out.println("issuerId=" + request.getParameter("issuerId"));
			paymentResult.setMchtOrderId(request.getParameter("mchtOrderId"));
			System.out.println("mchtOrderId=" + request.getParameter("mchtOrderId"));
			paymentResult.setOrderNo(request.getParameter("orderNo"));
			System.out.println("orderNo=" + request.getParameter("orderNo"));
			paymentResult.setOrderDatetime(request.getParameter("orderDatetime"));
			System.out.println("orderDatetime=" + request.getParameter("orderDatetime"));
			paymentResult.setOrderAmount(request.getParameter("orderAmount"));
			System.out.println("orderAmount=" + request.getParameter("orderAmount"));
			paymentResult.setPayDatetime(request.getParameter("payDatetime"));
			System.out.println("payDatetime=" + request.getParameter("payDatetime"));
			paymentResult.setExt1(request.getParameter("ext1"));
			System.out.println("ext1=" + request.getParameter("ext1"));
			paymentResult.setExt2(request.getParameter("ext2"));
			System.out.println("ext2=" + request.getParameter("ext2"));
			paymentResult.setPayResult(request.getParameter("payResult"));
			System.out.println("payResult=" + request.getParameter("payResult"));
			paymentResult.setKey(key); 
			paymentResult.setSignMsg(request.getParameter("signMsg"));
			System.out.println("signMsg=" + request.getParameter("signMsg"));
			paymentResult.setCertPath(cert);

			boolean verifyResult = paymentResult.verify();
			System.out.println("receive verifyResult :====" + verifyResult);
			if (verifyResult) {
				
				if("1".equals(paymentResult.getPayResult())){//判断订单状态 1为支付成功
					//——请根据您的业务逻辑来编写程序
					
					System.out.println("验证完成,支付成功" + verifyResult);
					
					
					out.println("success"); //请不要修改或删除
				}
			} else {
				
				out.println("fail");
			}

%>