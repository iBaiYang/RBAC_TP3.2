<?php
namespace Admin\Model;

use Think\Model;

/**
 * 管理员用户模型类
 * Class AdminUserModel
 * @package Admin\Model
 */
class AdminUserModel extends Model
{
    /**
     * 添加管理员
     * @param array $data
     * @return bool|mixed
     */
    public function userAdd( $data = array() )
    {
        if ( empty($data['user_name']) || empty($data['password']) ) {
            return false;
        } else {
            $data['password'] = md5( $data['password'] );
            $data['create_time'] = $data['update_time'] = time();
            $user_id = $this->add( $data );
            if ( $user_id ) {
                return $user_id;
            } else {
                return false;
            }
        }
    }

    /**
     * 获取管理员列表
     * @param array $map
     * @return array|mixed
     */
    public function getUserLists( $map = array() )
    {
        $data_lists = $this->where( $map )->select();

        $data_lists = $data_lists ? $data_lists : array();

        return $data_lists;
    }

    /**
     * 获取用户信息
     * @param $user_id
     * @return array|mixed
     */
    public function getUserInfo( $user_id )
    {
        $user_info = array();
        if ( !empty($user_id) ) {
            $map['id'] = array('eq', $user_id);
            $user_info = $this->where( $map )->find();
        }

        return $user_info;
    }

    /**
     * 获取用户名
     * @param $user_id
     * @return mixed|string
     */
    public function getUserName( $user_id )
    {
        $user_name = '';
        if ( !empty($user_id) ) {
            $map['id'] = array('eq', $user_id);
            $user_name = $this->where( $map )->getField('user_name');
        }

        return $user_name;
    }

    /**
     * 用户名密码登录验证
     * @param string $user_name
     * @param string $password
     * @return bool
     */
    public function checkUserLogin( $user_name = '', $password = '' )
    {
        if ( empty($user_name) || empty($password) ) {
            return false;
        } else {
            $map['user_name'] = array('eq', $user_name);
            $map['password'] = array('eq', md5($password) );
            $user_info = $this->where( $map )->find();
            if ( empty($user_info) ) {
                return false;
            } else {
                return $user_info;
            }
        }
    }
}