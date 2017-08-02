<?php
namespace Admin\Model;

use Think\Model;
use Think\Page;

/**
 * Admin角色模型
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
        if ( empty($data) || empty($data['role_pid']) ) {
            return false;
        } else {
            $p_level = $this->getRoleLevel( $data['role_pid'] );
            if ( !$p_level ) {
                return false;
            }
            $data['level'] = $p_level + 1;

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
        $map['a.role_pid'] = array('eq', $role_pid);
        $data_lists = $this->alias('a')
            ->field('a.*, b.role_name as role_p_name')
            ->join( 'LEFT JOIN db_admin_role as b ON a.role_pid = b.id' )
            ->where( $map )
            ->order( 'a.rank ASC' )
            ->select();

        $data_lists = $data_lists ? $data_lists : array();

        return $data_lists;
    }

    /**
     * 获取角色级别
     * @param string $role_id
     * @return bool|mixed
     */
    public function getRoleLevel( $role_id = '' )
    {
        if ( empty($role_id) ) {
            return false;
        } else {
            $map['id'] = array('eq', $role_id);
            $role_lv = $this->where( $map )->getField('level');
            if ( empty($role_lv) ) {
                return false;
            } else {
                return $role_lv;
            }
        }
    }

    /**
     * 获取角色的父id
     * @param string $role_id
     * @return bool|mixed
     */
    public function getRolePid( $role_id = '' )
    {
        if ( empty($role_id) ) {
            return false;
        } else {
            $map['id'] = array('eq', $role_id);
            $role_pid = $this->where( $map )->getField('role_pid');
            return $role_pid;
        }
    }

    /**
     * 获取子角色列表
     * @param array $user_role_arr
     * @return array
     */
    public function getRoleSonsLists( $user_role_arr = array() )
    {
        if ( empty($user_role_arr) ) {
            return array();
        }

        $data_lists = [];
        foreach ( $user_role_arr as $key => $value  )
        {
            $son_lists = $this->getRoleSonsListsByRecursion( $value );
            $data_lists = array_merge($data_lists, $son_lists);
        }

        return $data_lists;
    }

    /**
     * 递归获取子角色列表
     * @param $role_pid
     * @return array|mixed
     */
    public function getRoleSonsListsByRecursion( $role_pid )
    {
        $map['role_pid'] = array('eq', $role_pid);
        $data_lists = $this->where( $map )->select();

        foreach ( $data_lists as $key => $value )
        {
            $son_lists = $this->getRoleSonsListsByRecursion( $value['id'] );
            $data_lists = array_merge($data_lists, $son_lists);
        }

        return $data_lists;
    }

    /**
     * 角色详情
     * @param $role_id
     * @return mixed
     */
    public function getRoleInfo( $role_id )
    {
        $map['id'] = array('eq', $role_id);
        $data_info = $this->where( $map )->find();
        return $data_info;
    }

    /**
     * 角色信息修改
     * @param array $data
     * @return bool|mixed
     */
    public function roleEdit( $data = array() )
    {
        if ( empty($data) || empty($data['role_pid']) ) {
            return false;
        } else {
            $p_level = $this->getRoleLevel( $data['role_pid'] );
            if ( !$p_level ) {
                return false;
            }
            $data['level'] = $p_level + 1;

            $result = $this->save( $data );
            if ( $result ) {
                return true;
            } else {
                return false;
            }
        }
    }



}