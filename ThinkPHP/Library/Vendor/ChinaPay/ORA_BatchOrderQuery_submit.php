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
<title>�����˵���ѯ</title>
<h1>�����˵���ѯ</h1>
<form action="ORA_BatchOrderQuery_return.php" method="post">
<label>�̻���</label><br/>
<input type="text" name="merId" value="<?php echo $merId; ?>" readonly/><br/>
<label>�汾��</label><br/>
<input type="text" name="version" value="<?php echo $version; ?>" readonly/><br/>
<label>ǩ����־</label><br/>
<input type="text" name="signFlag" value="<?php echo $signFlag; ?>" readonly/><br/>
<label>�˵����ڣ���ʼ��</label><br/>
<input type="text" name="fromDate" value="20100204"><br/>
<label>�˵����ڣ�������</label><br/>
<input type="text" name="toDate" value="20100204"><br/>
<label>״̬</label><br/>
<select name="stat">
<option selected value="6,9">Ĭ��</option>
<option value="6">��ѯ�˵�</option>
<option value="9">�ػ��˵�</option>
</select><br/><br/>
<input type="submit" value="��ѯ">
</form>
