<?php
	header('Content-type: text/html; charset=gbk');
?>
<html>
	<head>
		<title>ChinaPay-ORA���ʽӿ���ʾ����</title>
	</head>
<body>
<div style="text-align:center">
	<h1>ChinaPay-ORA���ʽӿ���ʾ����</h1>
	<h2><a href="ORA_OraTransGet_submit.php" target="_blank">����</a></h2>
	<h2><a href="ORA_SingleOrderQuery_submit.php" target="_blank">���ʲ�ѯ</a></h2>
	<h2><a href="ORA_BatchOrderQuery_submit.php" target="_blank">�����˵���ѯ</a></h2>
	<h2><a href="ORA_BalanceQuery_submit.php" target="_blank">���ݽ�����ѯ</a></h2>
	<h2><a href="ORA_DepositDetailQuery_submit.php" target="_blank">��������ϸ��ѯ</a></h2>
	<hr>
<?php
	include_once("netpayclient_config.php");
	echo "<h2><font color='red'>���ӿ���Ҫ mcrypt, bcmath �� curl ��չ��֧�֣���鿴<a href='phpinfo.php' target='_blank'>PHP����</a>��ȷ�ϰ�װ����������չ�⡣</font></h2>";
	
	echo "<h2>��ǰ��Կ���ã�(<font color='red'>�밴������ʵ������� netpayclient_config.php �����ʵ��޸�</font>)</h2>";
	echo "<h4>[˽Կ�ļ�·����".PRI_KEY."]</h4>";
	echo "<h4>[��Կ�ļ�·����".PUB_KEY."]</h4>";
	
	echo "<h2>��ʾ����װλ�ã�</h2>";
	echo "<h4>[������ʵ�ַ��$site_url]</h4>";
	echo "<h4>[����������·����$_SERVER[DOCUMENT_ROOT]]</h4>";
	
	echo "<h2>������IP��ַ��<font color='green'>[$_SERVER[SERVER_ADDR]]</font></h2>";
	
?>
</div>
</body>
</html>