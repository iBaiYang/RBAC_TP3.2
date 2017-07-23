<?php
namespace Admin\Controller;

use Component\AdminController;

/**
 * 角色管理控制器
 * Class RoleController
 * @package Admin\Controller
 */
class RoleController extends AdminController
{
    /**
     * 角色列表
     */
    public function children_lists()
    {
        // 获取当前角色id
        $role_pid = trim(I('get.pid','','int'));

        // 权限判断及处理
        $self_role_arr = D('AdminUserRole')->getUserRoleArr( session('admin_user_id') );
        if ( empty($self_role_arr) ) {
            $this->error('你尚未被分配角色，请分配角色后再来');
        }
        if ( empty($role_pid) ) {
            $role_pid = $self_role_arr['0'];
        } else {
            if ( $role_pid < $self_role_arr['0'] ) {
                $role_pid = $self_role_arr['0'];
            }
        }

        // 前端页面赋值role_pid
        $this->assign('role_pid', $role_pid);

        // 角色模型类
        $model_admin_role = D('AdminRole');

        // 当前角色的父id
        $back_role_pid = $model_admin_role->getRolePid( $role_pid );
        $back_role_pid = ( $back_role_pid !== false ) ? $back_role_pid : $role_pid;
        $this->assign('back_role_pid', $back_role_pid);

        // 角色列表数据
        $lists = $model_admin_role->getRoleLists( $role_pid );
        $this->assign('lists', $lists);

        $this->display();
    }

    /**
     * 角色添加
     */
    public function add()
    {
        if ( !$_POST ) {
            $this->assign('role_pid', 0);
            $this->display();
        } else {
            $role_pid = trim(I('post.role_pid','','int'));
            if ( empty($role_pid) ) {
                $ajax_data['status'] = 2;
                $ajax_data['info'] = '参数错误，即将重新载入该页面';
                $ajax_data['url'] = U('Role/add');
                $this->ajaxReturn( $ajax_data );
            } else {
                $data['role_pid'] = $role_pid;
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
            $power_id = $model_role_menu->roleAdd( $data );
            if ( !$power_id ) {
                $this->error('角色添加失败，请重新提交');
            } else {
                $this->success('角色添加成功');
            }
        }
    }

    /**
     * 权限修改
     */
    public function edit()
    {
        if ( !$_POST ) {
            $this->display();
        } else {

        }
    }

}