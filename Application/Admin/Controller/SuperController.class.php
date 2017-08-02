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

            $model_role = D('AdminRole');
            $power_id = $model_role->roleAdd( $data );
            if ( !$power_id ) {
                $this->error('角色添加失败，请重新提交');
            } else {
                $this->success('角色添加成功');
            }
        }
    }

    /**
     * 角色修改
     */
    public function RoleEdit()
    {
        $role_id = trim(I('post.role_id','','int'));
        $post_do = trim(I('post.do','','string'));
        if ( $post_do == 'get' ) {
            $model_role = D('AdminRole');
            $role_info = $model_role->getRoleInfo( $role_id );

            $data['status'] = 1;
            $data['info'] = '获取成功';
            $data['data'] = $role_info;

            $this->ajaxReturn( $data );
        } elseif ( $post_do == 'save' ) {
            $data['role_pid'] = 1;

            $role_id = trim(I('post.role_id','','string'));
            if ( empty($role_id) ) {
                $this->error('角色id不能为空');
            } else {
                $data['id'] = $role_id;
            }

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
            $result = $model_role_menu->roleEdit( $data );
            if ( !$result ) {
                $this->error('角色修改失败，请重新提交');
            } else {
                $this->success('角色修改成功');
            }
        }
    }

    /**
     * 设定角色权限
     */
    public function setRolePower()
    {
        $role_id = trim(I('param.role_id','','int'));
        if ( !$role_id ) {
            $this->error('未选择角色');
        }

        if ( !$_POST ) {
            // 获取角色现有权限
            $role_power_arr = $this->getRolePowerLists( $role_id );
            $this->assign('role_power', $role_power_arr);
            // 获取所有权限
            $all_power_lists = $this->getAllPowerLists();
            $this->assign('all_power', $all_power_lists);

            $this->assign('role_id', $role_id);
            $this->display();
        } else {
            $power_ids = trim(I('post.power_ids','','string'));
            $power_arr = explode(',', $power_ids);
            array_pop($power_arr);  // 去除最后一个空元素

            $model_admin_role_power = M('admin_role_power');
            // 删除角色原有权限
            $try_1 = $model_admin_role_power->where( 'role_id = '.$role_id )->delete();
            // 新增角色权限
            foreach ( $power_arr as $key => $value ) {
                $model_admin_role_power->add(['role_id'=>$role_id, 'power_id'=>$value]);
            }
            $this->success('角色权限修改成功');
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

    /**
     * 设定管理员权限
     */
    public function setAdminerPower()
    {
        $adminer_id = trim(I('param.adminer_id','','int'));
        if ( !$adminer_id ) {
            $this->error('未选择管理员');
        }

        if ( !$_POST ) {
            // 获取角色现有权限
            $role_power_arr = $this->getAdminerPowerLists( $adminer_id );
            $this->assign('adminer_power', $role_power_arr);
            // 获取所有权限
            $all_power_lists = $this->getAllPowerLists();
            $this->assign('all_power', $all_power_lists);

            $this->assign('adminer_id', $adminer_id);
            $this->display();
        } else {
            $power_ids = trim(I('post.power_ids','','string'));
            $power_arr = explode(',', $power_ids);
            array_pop($power_arr);  // 去除最后一个空元素

            $model_admin_user_power = M('admin_user_power');
            // 删除角色原有权限
            $try_1 = $model_admin_user_power->where( 'user_id = '.$adminer_id )->delete();
            // 新增角色权限
            foreach ( $power_arr as $key => $value ) {
                $model_admin_user_power->add(['user_id'=>$adminer_id, 'power_id'=>$value]);
            }
            $this->success('管理员权限修改成功');
        }
    }

    /**
     * 获取相应管理员权限的列表
     * @param $adminer_id
     * @return array
     */
    public function getAdminerPowerLists( $adminer_id )
    {
        if ( empty($adminer_id) ) {
            return [];
        }

        // 获取权限
        $power_lists = M('admin_user_power')->where( 'user_id = '.$adminer_id )->order( 'power_id ASC' )->select();
        // 处理为一维数组
        foreach ( $power_lists as $key => $value )
        {
            $power_arr[$value['power_id']] = 1;
        }

        return $power_arr;
    }





}