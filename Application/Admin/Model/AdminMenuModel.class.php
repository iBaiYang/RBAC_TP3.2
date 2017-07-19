<?php
namespace Admin\Model;

use Think\Model;

/**
 * Admin菜单管理控制器
 * Class AdminMenuModel
 * @package Admin\Model
 */
class AdminMenuModel extends Model
{
    /**
     * 添加菜单
     * @param array $data 菜单数组
     * @return bool|mixed
     */
    public function menuAdd( $data = array() )
    {
        if ( empty($data) ) {
            return false;
        } else {
            $menu_id = $this->add( $data );
            if ( $menu_id ) {
                return $menu_id;
            } else {
                return false;
            }
        }
    }

    /**
     * 获取菜单列表
     * @return array|mixed
     */
    public function getMenuLists()
    {
        $data_lists = $this->select();
        $data_lists = $data_lists ? $data_lists : array();
        return $data_lists;
    }
}