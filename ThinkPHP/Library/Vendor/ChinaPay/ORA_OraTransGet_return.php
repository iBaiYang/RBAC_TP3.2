<?php
/**
 * @author huang.xuting
 *
 */
	// header('Content-type: text/html; charset=GBK');
	include_once("netpayclient_config.php");

	//���� netpayclient ���
	include_once("netpayclient.php");
	//���� CURL �����⣬�ÿ��� chinapay �ṩ��������ʹ�� curl ���� HTTP ����
	include_once("lib_curl.php");
	
	$client_sign = new netpayclient();
	//����˽Կ�ļ�, ����ֵ��Ϊ�����̻��ţ�����15λ
	$merId = $client_sign->buildKey(PRI_KEY);
	if(!$merId) {
		$re['status'] = 0;
		$re['msg']    = '����˽Կ�ļ�ʧ�ܣ�';
 	}else{
 		extract($data);
 		//֧��,��ѡ������ǩ��
		$subBank = "";	
		//�����־,"00"��˽��"01"�Թ����粻����Ĭ��Ϊ��˽
		$flag = "00";
		//�ӿڰ汾�ţ�����֧��Ϊ 20090501������
		$termType = "08";
		//�������ͣ�����
		$version = "20150304";	
		//ǩ����־��ֵ�̶�����������ǩ��
		$signFlag = "1";        
		
		//��������ϱ�����ϢΪ��ǩ����
		$plain = $merId . $merDate  . $merSeqId . $cardNo . $usrName  . $openBank  . $prov  . $city  . $transAmt  . $purpose  . $subBank  . $flag  . $version . $termType;
		//����Base64����
		$data = base64_encode($plain);
		//����ǩ��ֵ������
		$chkValue = $client_sign->sign($data);
		if (!$chkValue) {
			$re['status'] = 0;
			$re['msg']    = 'ǩ��ʧ�ܣ�';
		}else{
			$usrName = urlencode($usrName);         
			$openBank = urlencode($openBank);       
			$prov = urlencode($prov);                
			$city = urlencode($city);                
			$purpose = urlencode($purpose);          
			$subBank = urlencode($subBank);          

			if(($merSeqId!='')&&($merDate!='')){
			    $http = HttpInit();
				$post_data = "merId=$merId&merDate=$merDate&merSeqId=$merSeqId&cardNo=$cardNo&usrName=$usrName&openBank=$openBank&prov=$prov&city=$city&transAmt=$transAmt&purpose=$purpose&subBank=$subBank&flag=$flag&version=$version&termType=$termType&signFlag=$signFlag&chkValue=$chkValue";
				$output = HttpPost($http, $post_data, PAY_URL);
				
				if($output){
					$output = trim(strip_tags($output));
					//��ʼ��������
					$datas = explode("&",$output);
					$dex = strripos($output,"&");
					$plain = substr($output,0,$dex);
					$plaindata = base64_encode($plain);	
					$resp_code = $data[0];
					$chkValue = substr($output,$dex+ 10);
						
					//��ʼ��֤ǩ�������ȵ��빫Կ�ļ�
					$flag = $client_sign->buildKey(PUB_KEY);
					if(!$flag) {
						$re['status'] = 0;
						$re['msg']    = '���빫Կ�ļ�ʧ�ܣ�';
					} else {
						$flag  =  $client_sign->verify($plaindata, $chkValue);
						if($flag) {
							//��֤ǩ���ɹ���
			                $re['status'] = 1;
			                
			                foreach($datas as $data){
			                    $temp = explode("=",$data);
			                    $re[$temp[0]] = $temp[1];
			                }
						//������Լ���Ҫ������߼�д������
						} else {
							$re['status'] = 0;
							$re['msg']    = '��֤ǩ��ʧ�ܣ�';
						}
					}
				} else {
					$re['status'] = 0;
					$re['msg']    = '����ʧ�ܣ�';
				}
				HttpDone($http);
			} else {
				$re['status'] = 0;
				$re['msg']    = '����д�������ںͶ�����';
			}
		}		
 	}
?>