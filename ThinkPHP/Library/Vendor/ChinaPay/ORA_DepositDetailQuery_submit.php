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
?>
<title>��������ϸ��ѯ</title>
<h1>��������ϸ��ѯ</h1>
<form action="ORA_DepositDetailQuery_return.php" method="post">
<label>�̻���</label><br/>
<input type="text" name="merId" value="<?php echo $merId; ?>" readonly/><br/>
<label>�汾��</label><br/>
<input type="text" name="version" value="<?php echo $version; ?>" readonly/><br/>
<label>ǩ����־</label><br/>
<input type="text" name="signFlag" value="<?php echo $signFlag; ?>" readonly/><br/>
<label>��ʼ����</label><br/>
<input type="text" name="fromDate" value="20100402"><br/>
<label>��������</label><br/>
<input type="text" name="toDate" value="20100402"><br/>
<label>��ѯ���</label><br/>
<select name="type">
<option selected value="00">ֱ�ӻ���</option>
<option value="01">����֧��������</option>
<option value="02">ת��</option>
</select><br/><br/>
<input type="submit" value="��ѯ">
</form>
