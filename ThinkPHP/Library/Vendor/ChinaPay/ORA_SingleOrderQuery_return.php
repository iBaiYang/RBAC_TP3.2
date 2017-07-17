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
	$merSeqId = $_REQUEST["merSeqId"];
	$merDate = $_REQUEST["merDate"];
	$version = $_REQUEST["version"];
	$signFlag = $_REQUEST["signFlag"];
	
	//��������ϱ�����ϢΪ��ǩ����
	$plain = $merId . $merDate  . $merSeqId . $version;
	//����Base64����
	$data = base64_encode($plain);
	//����ǩ��ֵ������
	$chkValue = sign($data);
	if (!$chkValue) {
		echo "ǩ��ʧ�ܣ�";
		exit;
	}
?>
<title>���ʲ�ѯ</title>
<h1>���ʲ�ѯ</h1>
<?php		
	if(($merSeqId!='')&&($merDate!='')){
	    $http = HttpInit();
		$post_data = "merId=$merId&merDate=$merDate&merSeqId=$merSeqId&version=$version&signFlag=$signFlag&chkValue=$chkValue";
		$output = HttpPost($http, $post_data, QRY_URL);
		
		if($output){
			$output = trim(strip_tags($output));
			
			echo "<h2>��ѯ����</h2>";
			echo htmlspecialchars($output) . "<br/>";
			echo "=================================<br/>";
			//��ʼ��������
			$datas = explode("|",$output);
			foreach($datas as $data){
				echo "$data<br/>";
			}
			
			echo "=================================<br/>";
			
			$dex = strripos($output,"|");
			$plain = substr($output,0,$dex + 1);
			echo "��ǩ���ģ�<br/>" . $plain . "<br/>";
			$plaindata = base64_encode($plain);	
			$resp_code = $data[0];
			$chkValue = substr($output,$dex + 1);
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
		echo "<h3>����д�������ںͶ�����</h3>";
	}
?>