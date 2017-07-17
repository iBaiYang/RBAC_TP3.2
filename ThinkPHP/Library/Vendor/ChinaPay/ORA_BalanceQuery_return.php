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
	
	//�ӿڰ汾�ţ�����֧��Ϊ 20090501������
	$version = "20090501";	
	//ǩ����־��ֵ�̶�����������ǩ��
	$signFlag = "1";

	//��������ϱ�����ϢΪ��ǩ����
	$plain = $merId . $version;
	//����Base64����
	$data = base64_encode($plain);
	//����ǩ��ֵ������
	$chkvalue = sign($data);
	if (!$chkvalue) {
		echo "ǩ��ʧ�ܣ�";
		exit;
	}	
?>
<title>����������ѯ</title>
<h1>����������ѯ</h1>
<?php
	$http = HttpInit();
	$post_data = "merId=$merId&version=$version&signFlag=$signFlag&chkValue=$chkvalue";
	$output = HttpPost($http, $post_data, Balance_URL_QRY);
		
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
	    $plaindata = base64_encode($plain);	;
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
	}else {
	    echo "<h3>HTTP ����ʧ�ܣ�</h3>";
	}
	HttpDone($http);
	
?>