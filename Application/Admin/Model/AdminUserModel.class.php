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
            if ( $user_name = 'admin' && $password = '123456' ) {
                return true;
            } else {
                return false;
            }
        }
    }
}