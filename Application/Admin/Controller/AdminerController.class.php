<?php
namespace Admin\Controller;

use Component\AdminController;

/**
 * 管理员管理控制器
 * Class AdminerController
 * @package Admin\Controller
 */
class AdminerController extends AdminController
{
    /**
     * 管理员列表
     */
    public function lists()
    {
        if ( !$_POST ) {
            $map = array();
        } else {
            $map = array();
        }

        $model_admin_user = D('AdminUser');
        $lists = $model_admin_user->getUserLists( $map );

        $this->assign('lists', $lists);
        $this->display();
    }

    /**
     * 管理员添加
     */
    public function add()
    {
        if ( !$_POST ) {
            $user_role_arr = D('AdminUserRole')->getUserRoleArr( session('admin_user_id') );
            $role_lists = D('AdminRole')->getRoleSonsLists( $user_role_arr );
            $this->assign('role_lists', $role_lists);
            $this->display();
        } else {
            $user_name = trim(I('post.user_name','','string'));
            if ( empty($user_name) ) {
                $this->error('用户名不能为空');
            } else {
                $data['user_name'] = $user_name;
            }

            $password = trim(I('post.password','','string'));
            if ( empty($password) ) {
                $this->error('密码不能为空');
            } else {
                $data['password'] = $password;
            }

            $role_id = trim(I('post.role_id','','int'));
            if ( !empty($role_id) ) {
                $data['role_id'] = $role_id;
            }

            $model_admin_user = D('AdminUser');
            $user_id = $model_admin_user->userAdd( $data );
            if ( !$user_id ) {
                $this->error('管理员添加失败，请重新提交');
            } else {
                $this->success('管理员添加成功');
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