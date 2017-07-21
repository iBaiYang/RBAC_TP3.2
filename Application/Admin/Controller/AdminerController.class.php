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
     * 权限列表
     */
    public function children_lists()
    {
        $menu_id = trim(I('get.pid','','int'));
        if ( empty($menu_id) ) {
            $lists = array();
        } else {
            $model_power_menu = D('AdminPower');
            $lists = $model_power_menu->getPowerLists( $menu_id );
        }

        $this->assign('menu_id', $menu_id);
        $this->assign('lists', $lists);
        $this->display();
    }

    /**
     * 管理员添加
     */
    public function add()
    {
        if ( !$_POST ) {
            $this->display();
        } else {
            $menu_id = trim(I('post.menu_id','','int'));
            if ( empty($menu_id) ) {
                $ajax_data['status'] = 2;
                $ajax_data['info'] = '参数错误，请重新进入该页面再次添加';
                $ajax_data['url'] = U('Menu/lists');
                $this->ajaxReturn( $ajax_data );
            } else {
                $data['menu_id'] = $menu_id;
            }

            $power_name = trim(I('post.power_name','','string'));
            if ( empty($power_name) ) {
                $this->error('权限名称不能为空');
            } else {
                $data['power_name'] = $power_name;
            }

            $type = trim(I('post.type','','int'));
            if ( empty($type) ) {
                $this->error('请选择权限类型');
            } else {
                $data['type'] = $type;
            }

            $mca = trim(I('post.mca','','string'));
            if ( empty($mca) ) {
                $this->error('权限MCA不能为空');
            } else {
                $data['mca'] = $mca;
            }


            $remark = trim(I('post.remark','','string'));
            $data['remark'] = $remark ? $remark : '';

            $rank = trim(I('post.rank','','int'));
            if ( empty($rank) ) {
                $this->error('权限排序不能为空');
            } else {
                $data['rank'] = $rank;
            }

            $model_power_menu = D('AdminPower');
            $power_id = $model_power_menu->powerAdd( $data );
            if ( !$power_id ) {
                $this->error('权限添加失败，请重新提交');
            } else {
                $this->success('权限添加成功');
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