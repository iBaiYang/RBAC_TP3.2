<?php
/**
 * @author huang.xuting
 *
 */
	header("Content-type: text/html; charset=GBK");
	include_once("netpayclient_config.php");

	//���� netpayclient ���
	include_once("netpayclient.php");
	//���� CURL �����⣬�ÿ��� chinapay �ṩ��������ʹ�� curl ���� HTTP ����
	include_once("lib_curl.php");
	$client_sign = new netpayclient();
	//����˽Կ�ļ�, ����ֵ��Ϊ�����̻��ţ�����15λ
	$merId = $client_sign->buildKey(PRI_KEY);
	if(!$merId) {
		echo "����˽Կ�ļ�ʧ�ܣ�";
		exit;
	}
	
	//�̻����ڣ��������õ������ڣ�����
	$merDate = date('Ymd');
	//������ˮ�ţ�����16λ������������ϣ�һ���ڲ������ظ����������õ�ǰʱ���������
	$merSeqId = "00" . date('YmdHis');
	//�տ��˺ţ�����
	$cardNo = "6225882106897891";
	//�տ�������������
	$usrName = "����";
	//��������,�������ù������У�����
	$openBank = "��������";
	//ʡ��,���������Ϻ�������
	$prov = "�Ϻ�";
	//����,���������Ϻ�������
	$city = "�Ϻ�";
	//�������䳤12λ���Է�Ϊ��λ������
	$transAmt = "000000000001";
	//��;����ѡ
	$purpose = "hxt����";  	
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
	
	
?>
<meta http-equiv="Content-Type" content="text/html; charset=GBK">
<title>���ʽ���</title>
<h1>ORA���ײ���</h1>
<h5><a href="javascript:window.location.reload()">ˢ�±�ҳ�Ըı䶩����</a></h5>

<form action="ORA_OraTransGet_return.php" method="post" target="_blank">
<label>�̻��� </label>
<input type="text" name="merId" value="<?php echo $merId; ?>" readonly/><br/><br/>
<label>�̻����� </label>
<input type="text" name="merDate" value="<?php echo $merDate; ?>" /><br/><br/>
<label>��ˮ��</label>
<input type="text" name="merSeqId" value="<?php echo $merSeqId; ?>" /><br/><br/>
<label>�տ��˺�</label>
<input type="text" name="cardNo" value="<?php echo $cardNo; ?>" /><br/><br/>
<label>�տ�������</label>
<input type="text" name="usrName" value="<?php echo $usrName; ?>" /><br/><br/>
<label>��������</label>
<input type="text" name="openBank" value="<?php echo $openBank; ?>" /><br/><br/>
<label>ʡ��</label>
<input type="text" name="prov" value="<?php echo $prov; ?>" /><br/><br/>
<label>����</label>
<input type="text" name="city" value="<?php echo $city; ?>"/><br/><br/>
<label>���</label>
<input type="text" name="transAmt" value="<?php echo $transAmt; ?>"/><br/><br/>
<label>��;</label>
<input type="text" name="purpose" value="<?php echo $purpose; ?>"/><br/><br/>
<label>֧��</label>
<input type="text" name="subBank" value="<?php echo $subBank; ?>" /><br/><br/>
<label>�����־</label>
<input type="text" name="flag" value="<?php echo $flag; ?>" /><br/><br/>
<label>�汾��</label>
<input type="text" name="version" value="<?php echo $version; ?>" /><br/><br/>
<label>�������</label>
<input type="text" name="termType" value="<?php echo $termType; ?>" /><br/><br/>
<label>ǩ����־</label>
<input type="text" name="signFlag" value="<?php echo $signFlag; ?>" /><br/><br/>
<input type="submit" value="�ύ">
</form>
