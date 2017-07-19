<?php
namespace Admin\Controller;

use Component\AdminController;


/**
 * 菜单管理控制器
 * Class MenuController
 * @package Admin\Controller
 */
class MenuController extends AdminController
{
    /**
     * 菜单列表
     */
    public function lists()
    {
        $model_admin_menu = D('AdminMenu');
        $lists = $model_admin_menu->getMenuLists();
        $this->assign('lists', $lists);
        $this->display();
    }

    /**
     * 菜单添加
     */
    public function add()
    {
        if ( !$_POST ) {
            $this->display();
        } else {
            $menu_name = trim(I('post.menu_name','','string'));
            if ( empty($menu_name) ) {
                $this->error('菜单名称不能为空');
            } else {
                $data['menu_name'] = $menu_name;
            }

            $controllers = trim(I('post.controllers','','string'));
            if ( empty($controllers) ) {
                $this->error('菜单所属控制器不能为空');
            } else {
                $data['controllers'] = $controllers;
            }

            $remark = trim(I('post.remark','','string'));
            $data['remark'] = $remark ? $remark : '';

            $rank = trim(I('post.rank','','string'));
            if ( empty($rank) ) {
                $this->error('菜单排序不能为空');
            } else {
                $data['rank'] = $rank;
            }

            $model_admin_menu = D('AdminMenu');
            $menu_id = $model_admin_menu->menuAdd( $data );
            if ( !$menu_id ) {
                $this->error('菜单添加失败，请刷新页面后重新添加');
            } else {
                $this->success('菜单添加成功');
            }
        }
    }

    /**
     * 菜单修改
     */
    public function edit()
    {
        if ( !$_POST ) {
            $this->display();
        } else {

        }
    }

}