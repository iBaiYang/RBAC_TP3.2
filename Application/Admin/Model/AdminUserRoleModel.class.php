<?php
namespace Admin\Model;

use Think\Model;
use Think\Page;

/**
 * Admin管理员角色模型类
 * Class AdminUserRoleModel
 * @package Admin\Model
 */
class AdminUserRoleModel extends Model
{
    /**
     * 获取用户角色数组
     * @return array|mixed
     */
    public function getUserRoleArr( $user_id = '' )
    {
        $data_arr = array();
        if ( !empty($user_id) ) {
            $map['user_id'] = array('eq', $user_id);
            $order = 'id ASC';
            $data_lists = $this->where( $map )->order( $order )->select();

            foreach ( $data_lists as $key => $value )
            {
                $data_arr[] = $value['role_id'];
            }
        }

        $data_arr = $data_arr ? $data_arr : array();

        return $data_arr;
    }

    /**
     * 获取用户角色列表
     * @param string $user_id
     * @return array
     */
    public function getUserRoleLists( $user_id = '' )
    {
        $data_lists = array();
        if ( !empty($user_id) ) {
            $map['a.user_id'] = array('eq', $user_id);
            $data_lists = $this->alias('a')
                ->field( 'a.role_id, b.level as role_lv' )
                ->where( $map )
                ->join( C('DB_PREFIX').'admin_role as b ON a.role_id = b.id' )
                ->select();
        }

        $data_lists = $data_lists ? $data_lists : array();

        return $data_lists;
    }
}