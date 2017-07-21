<?php
namespace Admin\Model;

use Think\Model;
use Think\Page;

/**
 * Admin角色管理控制器
 * Class AdminRoleModel
 * @package Admin\Model
 */
class AdminRoleModel extends Model
{
    /**
     * 添加角色
     * @param array $data 角色数组
     * @return bool|mixed
     */
    public function roleAdd( $data = array() )
    {
        if ( empty($data) ) {
            return false;
        } else {
            if ( !empty($data['role_pid']) ) {
                $p_level = M('')->where( '' )->getField('level');
                $data['level'] = $p_level + 1;
            }
            $role_id = $this->add( $data );
            if ( $role_id ) {
                return $role_id;
            } else {
                return false;
            }
        }
    }

    /**
     * 获取角色列表
     * @return array|mixed
     */
    public function getRoleLists( $role_pid = '' )
    {
        if ( empty($role_pid) ) {
            $data_lists = $this->select();
        } else {
            $map['role_id'] = array('eq', $role_pid);
            $data_lists = $this->alias('a')
                ->field('a.*, b.role_name as role_p_name')
                ->where( $map )
                ->join( 'db_admin_role as b ON a.role_pid = b.id' )
                ->order( 'a.rank ASC' )
                ->select();
        }

        $data_lists = $data_lists ? $data_lists : array();

        return $data_lists;
    }
}