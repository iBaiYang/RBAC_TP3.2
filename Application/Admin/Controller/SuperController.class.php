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
     * Admin2级角色2级列表
     * 角色等级为2
     */
    public function RoleLv2Lists()
    {
        // 2级角色2级列表
        $map['a.level'] = array('eq', 2);
        $lists = M('admin_role as a')
            ->field('a.*, b.role_name as role_p_name')
            ->join( C('DB_PREFIX').'admin_role as b ON a.role_pid = b.id' )
            ->where( $map )
            ->select();
        $this->assign('lists', $lists);

        $this->display();
    }

    /**
     * 2级角色添加
     */
    public function RoleAdd()
    {
        if ( !$_POST ) {
            $this->display();
        } else {
            $data['role_pid'] = 1;

            $role_name = trim(I('post.role_name','','string'));
            if ( empty($role_name) ) {
                $this->error('角色名称不能为空');
            } else {
                $data['role_name'] = $role_name;
            }

            $remark = trim(I('post.remark','','string'));
            $data['remark'] = $remark ? $remark : '';

            $rank = trim(I('post.rank','','int'));
            if ( empty($rank) ) {
                $this->error('排序不能为空');
            } else {
                $data['rank'] = $rank;
            }

            $model_role_menu = D('AdminRole');
            $power_id = $model_role_menu->roleAdd( $data );
            if ( !$power_id ) {
                $this->error('角色添加失败，请重新提交');
            } else {
                $this->success('角色添加成功');
            }
        }
    }

    /**
     * 设定角色权限
     */
    public function setRolePower()
    {
        $role_id = trim(I('get.role_id','','int'));
        if ( !$role_id ) {
            $this->error('未选择角色');
        }

        if ( !$_POST ) {
            // 获取角色现有权限
            $role_power_arr = $this->getRolePowerLists( $role_id );var_dump($role_power_arr);
            $this->assign('role_power', $role_power_arr);
            // 获取所有权限
            $all_power_lists = $this->getAllPowerLists();var_dump($all_power_lists);
            $this->assign('all_power', $all_power_lists);

            $this->display();
        } else {

        }
    }

    /**
     * 获取相应角色权限的列表
     */
    public function getRolePowerLists( $role_id = '' )
    {
        if ( !$role_id ) {
            return [];
        }
        // 获取权限
        $power_lists = M('admin_role_power')->where( 'role_id = '.$role_id )->order( 'power_id ASC' )->select();
        // 处理为一维数组
        foreach ( $power_lists as $key => $value )
        {
            $power_arr[$value['power_id']] = 1;
        }

        return $power_arr;
    }

    /**
     * 获取所有权限的列表
     */
    public function getAllPowerLists()
    {
        // 获取菜单
        $menu_lists = M('admin_menu')->order( 'rank ASC' )->select();
        // 循环获取菜单拥有权限
        foreach ( $menu_lists as $key => $value )
        {
            $menu_lists[$key]['power_lists'] = M('admin_power')->where( 'menu_id = '.$value['id'] )->order( 'rank ASC' )->select();
        }

        return $menu_lists;
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

    public function getUserPowerLists()
    {
        $user_id = trim(I('post.user_id', '', 'int'));
        if ( empty($user_id) ) {
            $this->error('页面错误，请刷新页面后重新操作');
        }

        $power_lists = M('admin_user_power')->where( 'user_id = '.$user_id )->select();

        $data['status'] = 1;
        $data['info'] = '获取成功';
        $data['data'] = $power_lists;

        $this->ajaxReturn( $data );
    }





}