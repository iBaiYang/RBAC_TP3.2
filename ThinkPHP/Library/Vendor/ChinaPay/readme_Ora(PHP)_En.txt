===============================================================
chinapay ora interface demo (PHP) instructions
===============================================================
/**
 * @author huang.xuting
 * 2012-04-25
 */

1)The interface needs three PHP expanded libraries' support ¡ª¡ª mcrypt, bcmath and curl, therefore, please confirm that the three libraries are installed and enabled. 
  If you are virtual host user, generally, host provider will install mcrypt and bcmath libraries. You can use phpinfo() to check the PHP configuration information, if they are not installed, please contact your host business to resolve.

2)File description: netpaylclient.php provides library files for chinapay, which includes the signature and signature verified functions. lib_curl.php is the utility file of functions library provided by chinapay,which can make you use curl to send HTTP request conveniently.

3)Copy this demo to any position of your server(if it's external network, it should be accessible, or couldn't receive the transaction response), virtual host user could upload it through FTP directly.

4)After you applying for merId in CP, you will get two testing key files whose suffix called .key, among them, beginning with Mer is private key file,  beginning with Pg is public key file.
  Put the two key files into this demo directory(other position also do, but should make corresponding configuration according to actual position). 
  Open the netpayclient_config.php file, modify PRI_KEY and PUB_KEY according to your situation.


 











