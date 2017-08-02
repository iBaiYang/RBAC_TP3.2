<?php
namespace Admin\Model;

use Think\Model;

/**
 * Admin角色权限管理控制器
 * Class AdminRolePowerModel
 * @package Admin\Model
 */
class AdminRolePowerModel extends Model
{
    /**
     * 获取相应角色权限的列表
     */
    public function getRolePowerLists( $role_id = '' )
    {
        if ( !$role_id ) {
            return [];
        }
        // 获取权限
        $power_lists = $this->where( 'role_id = '.$role_id )->order( 'power_id ASC' )->select();
        // 处理为一维数组
        foreach ( $power_lists as $key => $value )
        {
            $power_arr[$value['power_id']] = 1;
        }

        return $power_arr;
    }

    /**
     * 设定角色权限
     * @param string $role_id
     * @param array $power_arr
     * @return bool
     */
    public function setRolePower( $role_id = '', $power_arr = [] )
    {
        if ( !$role_id ) {
            return false;
        }

        $try_1 = $this->where( 'role_id = '.$role_id )->delete();
        // 新增角色权限
        foreach ( $power_arr as $key => $value ) {
            $this->add(['role_id'=>$role_id, 'power_id'=>$value]);
        }

        return true;
    }
}