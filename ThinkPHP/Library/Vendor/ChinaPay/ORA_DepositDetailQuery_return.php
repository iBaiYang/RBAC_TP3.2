<?php
/**
 * @author huang.xuting
 *
 */
	header('Content-type: text/html; charset=gbk');
	include_once("netpayclient_config.php");

	//���� netpayclient ���
	include_once("netpayclient.php");
	//���� CURL �����⣬�ÿ��� chinapay �ṩ��������ʹ�� curl ���� HTTP ����
	include_once("lib_curl.php");
	
	//����˽Կ�ļ�, ����ֵ��Ϊ�����̻��ţ�����15λ
	$merId = buildKey(PRI_KEY);
	if(!$merId) {
		echo "����˽Կ�ļ�ʧ�ܣ�";
		exit;
	}	
	$fromDate = $_REQUEST["fromDate"];
	$toDate = $_REQUEST["toDate"];
	$type = $_REQUEST["type"];
	$version = $_REQUEST["version"];
	$signFlag = $_REQUEST["signFlag"];
	
	//��������ϱ�����ϢΪ��ǩ����
	$plain = $merId . $fromDate  . $toDate  . $type . $version;
	//����Base64����
	$signData = base64_encode($plain);
	//����ǩ��ֵ������
	$chkvalue = sign($signData);
	if (!$chkvalue) {
		echo "ǩ��ʧ�ܣ�";
		exit;
	}
?>
<title>��������ϸ��ѯ</title>
<h1>��������ϸ��ѯ</h1>
<?php
	if(($fromDate!='')&&($toDate!='')){		
		$http = HttpInit();
		$post_data = "merId=$merId&fromDate=$fromDate&toDate=$toDate&type=$type&version=$version&signFlag=$signFlag&chkValue=$chkvalue";
		$output = HttpPost($http, $post_data, DepositDetail_URL_QRY);
		
		if($output){
			$output = trim(strip_tags($output));
			echo "<h2>��ѯ����</h2>";
			echo htmlspecialchars($output) . "<br/>";
			echo "=================================<br/>";
			//��ʼ��������
			$datas = explode("\r\n",$output);
			$extracted_data = array();
			foreach($datas as $data){
				echo "$data<br/>";
			}
			
			echo "=================================<br/>";
			
			$dex = strlen($output) - 256;
			$plain = substr($output,0,$dex);
			echo "��ǩ���ģ�<br/>" . $plain . "<br/>";
			$plaindata = base64_encode($plain);
			$chkValue = substr($output,$dex);
			echo "chkValueֵ��<br/>" . $chkValue . "<br/>";
				
			//��ʼ��֤ǩ�������ȵ��빫Կ�ļ�
			$flag = buildKey(PUB_KEY);
			if(!$flag) {
				echo "���빫Կ�ļ�ʧ�ܣ�";
			} else {
				$flag  =  verify($plaindata, $chkValue);
				if($flag) {
				//��֤ǩ���ɹ���
				echo "<h4>��֤ǩ���ɹ�</h4>";
				//������Լ���Ҫ������߼�д������
												
				} else {
					echo "<h4>��֤ǩ��ʧ�ܣ�</h4>";
					}
			}
				
		} else {
			echo "<h3>HTTP ����ʧ�ܣ�</h3>";
		}
		HttpDone($http);
	} else {
		echo "<h3>����д��������ϸ��ѯ��ʼ����</h3>";
	}
?>