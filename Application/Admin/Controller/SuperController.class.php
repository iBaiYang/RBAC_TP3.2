<?php
namespace Admin\Controller;

use Think\Controller;


/**
 * 超级管理员控制器
 * 操作方法不在RBAC通用中，用驼峰法命名
 * Class SuperController
 * @package Admin\Controller
 */
class SuperController extends Controller
{
    /**
     * 控制器类初始化构造函数
     * SuperController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        if ( session('admin_user_type') !== 'super' ){
            $this->error('你没有该操作权限');
        }
    }

    /**
     * Admin管理员列表
     * 超级管理员用户id为1，角色id为1
     * 普通管理员角色id为2
     */
    public function AdminLv2Lists()
    {

        $map['c.level'] = array('eq', 2);
        $lists = M('admin_user as a')
            ->field('a.*, c.role_name')
            ->join( C('DB_PREFIX').'admin_user_role as b ON a.id = b.user_id' )
            ->join( C('DB_PREFIX').'admin_role as c ON b.role_id = c.id' )
            ->where( $map )
            ->select();

        $this->assign('lists', $lists);
        $this->display();
    }

    /**
     * 增加
     */
    public function RoleAdd()
    {

    }



}