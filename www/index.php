<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用入口文件

// 定义HTML头部
header("content-type:text/html;charset=utf-8");

// 检测PHP环境
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');

// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
define('APP_DEBUG', true);

// 自动生成Admin模块并绑定到当前入口文件
//define('BIND_MODULE', 'Admin');
//define('BUILD_CONTROLLER_LIST', 'Index,Site');  // 这里后面字符串中间不能出现空格，不然生成的文件名前有空格
//define('BUILD_MODEL_LIST', 'Admin');

// 定义应用目录
define('APP_PATH', '../Application/');

// 引入ThinkPHP入口文件
require '../ThinkPHP/ThinkPHP.php';

// 亲^_^ 后面不需要任何代码了 就是如此简单
