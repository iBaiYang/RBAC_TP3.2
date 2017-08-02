<?php
namespace Admin\Model;

use Think\Model;

/**
 * Admin管理员用户权限管理控制器
 * Class AdminUserPowerModel
 * @package Admin\Model
 */
class AdminUserPowerModel extends Model
{
    /**
     * 获取相应管理员用户权限的列表
     */
    public function getUserMenuPowerLists( $user_id = '' )
    {
        if ( !$user_id ) {
            return [];
        } else {
            $map['b.user_id'] = ['eq', $user_id];
        }

        // 获取菜单
        $menu_lists = M('admin_menu')->order( 'rank ASC' )->select();
        // 循环获取菜单拥有权限
        foreach ( $menu_lists as $key => $value )
        {
            $map['a.menu_id'] = ['eq', $value['id']];
            $menu_lists[$key]['power_lists'] = M('admin_power')->alias('a')
                ->field( 'a.*' )
                ->join( C('DB_PREFIX').'admin_user_power as b ON a.id = b.power_id' )
                ->where( $map )
                ->order( 'a.rank ASC' )
                ->select();
        }
        // 去除无子权限的菜单
        foreach ( $menu_lists as $key => $value )
        {
            if ( empty($value['power_lists']) ) {
                unset($menu_lists[$key]);
            }
        }

        return $menu_lists;
    }

    /**
     * 获取用户权限数组
     * @param string $user_id
     * @return array
     */
    public function getUserPowerArr( $user_id = '' )
    {
        if (!$user_id) {
            return [];
        }

        // 获取权限
        $power_lists = $this->where( 'user_id = '.$user_id )->order( 'power_id ASC' )->select();
        // 处理为一维数组
        foreach ( $power_lists as $key => $value )
        {
            $power_arr[] = $value['power_id'];
        }

        return $power_arr;
    }

}