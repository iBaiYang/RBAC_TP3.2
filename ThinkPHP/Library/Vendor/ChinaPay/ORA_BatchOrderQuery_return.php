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
	$stat = $_REQUEST["stat"];
	$version = $_REQUEST["version"];
	$signFlag = $_REQUEST["signFlag"];
	
	//��������ϱ�����ϢΪ��ǩ����
	$plain = $merId . $fromDate  . $toDate  . $stat . $version;
	//����Base64����
	$signData = base64_encode($plain);
	//����ǩ��ֵ������
	$chkvalue = sign($signData);
	if (!$chkvalue) {
		echo "ǩ��ʧ�ܣ�";
		exit;
	}
?>
<title>�����˵���ѯ</title>
<h1>�����˵���ѯ</h1>
<?php		
	if(($fromDate!='')&&($toDate!='')){
		$http = HttpInit();
		$post_data = "merId=$merId&fromDate=$fromDate&toDate=$toDate&stat=$stat&version=$version&signFlag=$signFlag&chkValue=$chkvalue";
		$output = HttpPost($http, $post_data, BatchOrder_URL_QRY);
		
		if($output){
			$output = trim(strip_tags($output));
			
			echo "<h2>��ѯ����</h2>";
			echo htmlspecialchars($output) . "<br/>";
			echo "=================================<br/>";
			//��ʼ��������
			$datas = explode("\r\n",$output);
			$i = 1;
			foreach($datas as $data){	
				echo "Line = $i ���ر��ģ�<br/> " . "$data<br/>";
			    $dex = strripos($data,"|");
			    $plain = substr($data,0,$dex + 1);
			    echo "��ǩ���ģ�<br/>" . $plain . "<br/>";
			    $plaindata = base64_encode($plain);	;
			    $chkValue = substr($data,$dex + 1);
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
					$i++;							
				    } else {
					    echo "<h4>��֤ǩ��ʧ�ܣ�</h4>";
					}
					echo "=================================<br/>";	
				}						
			}			
		} else {
			echo "<h3>HTTP ����ʧ�ܣ�</h3>";
		}
		HttpDone($http);
	} else {
		echo "<h3>����д�˵���ʼ����</h3>";
	}
	
?>