<?php
	/*�밴������ʵ������������¸�����*/
	$PATH = explode('example', dirname(__FILE__)) ;
	$PATH = str_replace('\\', '/', $PATH[0]);
	
	//˽Կ�ļ�����CHINAPAY�����̻���ʱ��ȡ������Ӧ�޸Ĵ˴����������·������ͬ
	define("PRI_KEY", $PATH."/MerPrK_808080211304910_20170324150134.key");
	//��Կ�ļ���ʾ�����Ѿ�����
	define("PUB_KEY", $PATH."/PgPubk.key");
	
	/*��������������Կ�����޸��������ã�Ĭ��Ϊ���Ի���*/
	
	//ORA���ʽ��������ַ(����)
	// define("PAY_URL","http://sfj-test.chinapay.com/dac/SinPayServletGBK");
	//ORA���ʽ��������ַ(����)
	define("PAY_URL","http://sfj.chinapay.com/dac/SinPayServletGBK");
	
	
	//���ʲ�ѯ�����ַ(����)
	define("QRY_URL","http://sfj-test.chinapay.com/dac/SinPayQueryServletGBK");
	//���ʲ�ѯ�����ַ(����)
	//define("QRY_URL","http://ora.chinapay.com/oraquery/SingleOrderQuery");
	
	//�����˵���ѯ�����ַ(����)
	define("BatchOrder_URL_QRY","http://sfj-test.chinapay.com/dac/FailureTradeQueryGBK");
	//�����˵���ѯ�����ַ(����)
	//define("BatchOrder_URL_QRY","http://ora.chinapay.com/oraquery/BatchOrderQuery");
	
	//����������ѯ�����ַ(����)
	define("Balance_URL_QRY","http://http://sfj-test.chinapay.com/dac/BalanceQueryGBK");
	//����������ѯ�����ַ(����)
	//define("Balance_URL_QRY","http://ora.chinapay.com/oraquery/BalanceQuery");
	
	//��������ϸ��ѯ�����ַ(����)
	define("DepositDetail_URL_QRY","http://sfj-test.chinapay.com/dac/DepositDetailQueryGBK");
	//��������ϸ��ѯ�����ַ(����)
	//define("DepositDetail_URL_QRY","http://ora.chinapay.com/oraquery/DepositDetailQuery");
	

	function getcwdOL(){
    $total = $_SERVER[PHP_SELF];
    $file = explode("/", $total);
    $file = $file[sizeof($file)-1];
    return substr($total, 0, strlen($total)-strlen($file)-1);
	}
	
	function getSiteUrl(){
		$host = $_SERVER[SERVER_NAME];
		$port = ($_SERVER[SERVER_PORT]=="80")?"":":$_SERVER[SERVER_PORT]";
		return "http://" . $host . $port . getcwdOL();
	}
	
	function traceLog($file, $log){
		$f = fopen($file, 'a'); 
		if($f){
			fwrite($f, date('Y-m-d H:i:s') . " => $log\n");
            fclose($f);
		} 
	}
	
	//ȡ�ñ�ʾ����װλ��
	$site_url = getSiteUrl();
	
	
	

?>