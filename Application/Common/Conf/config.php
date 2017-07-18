<?php
return array(
	//'配置项'=>'配置值'

    // url模式设置，要配合nginx修改
    'URL_MODEL'					=> 3,

    // 开启路由
//    'URL_ROUTER_ON'				=> true,
    // 路由规则
//    'URL_ROUTE_RULES'			=>array(),

    // 让页面显示追踪日志信息
    'SHOW_PAGE_TRACE'   => true,

    // 伪静态
    'URL_HTML_SUFFIX'			=>'php',

    // 系统默认的变量过滤机制
    'DEFAULT_FILTER'        	=> 'htmlspecialchars',

    // 数据库配置信息
    'DB_TYPE'   				=> 'mysql', // 数据库类型
    'DB_CHARSET'				=> 'utf8', // 字符集
    'DB_HOST'   				=> '127.0.0.1', // 服务器地址
    'DB_NAME'  					=> 'rbac_tp3.2', // 数据库名
    'DB_USER'					=> 'root', // 用户名
    'DB_PWD'					=> '123456', // 密码
    'DB_PORT'					=> 3306, // 端口
    'DB_PREFIX'					=> 'db_', // 数据库表前缀，只在默认配置中有效

    // 模块配置
//    'MODULE_ALLOW_LIST'			=>	array('Home', 'Admin'),
//    'DEFAULT_MODULE'			=>	'Home',

    //在Linux下，注意以下：
    //对控制器严格区分大小写，控制器类名、文件名、URL，三者必须完全一致。
    //开启不区分url大小写后，URL中的控制器，如UserLog被转换成user_log，模板文件名必须小写。
    //默认时，控制器的操作方法中，模板指定输出，如display(setPower)，模板文件名区分大小写，默认输出，如display()，模板文件名必须小写。
    //debug模式下，display()时，模板文件名区分大小写。
    'URL_CASE_INSENSITIVE'		=>	false, // 不区分url大小写，建议在Windows环境下开启。

//     'SESSION_TYPE'				=>	'Db',//session配置

    /**
     * SESSION_OPTIONS
     * 此设置无效，必须在php.ini中设置
     * session.cookie_lifetime：这个代表SessionID在客户端Cookie储存的时间，默认是0，代表浏览器一关闭SessionID就作废
     * session.gc_maxlifetime：这个是Session数据在服务器端储存的时间，如果超过这个时间，那么Session数据就自动删除！
     */
//    'SESSION_OPTIONS'			=>	array('expire' => 3600),

    'LOG_RECORD' => true, // 开启日志记录
    'LOG_LEVEL'  =>'EMERG,ALERT,CRIT,ERR', // 只记录EMERG ALERT CRIT ERR 错误
//    'WEB_DOMAIN' =>	'bigboss.mipanit.net',

    //多语言支持
    'LANG_SWITCH_ON'        => true,   // 默认关闭语言包功能
    'LANG_AUTO_DETECT'      => true,   // 自动侦测语言 开启多语言功能后有效
    'LANG_LIST'             => 'zh-cn,zh-tw,en-us', // 允许切换的语言列表 用逗号分隔
    'VAR_LANGUAGE'          => 'hl',		// 默认语言切换变量

);