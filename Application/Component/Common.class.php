<?php
namespace Component;

/**
 * 系统可共用组件类
 * Class Common
 * @package Component
 */
class Common
{
    /**
     * 验证码验证
     * @param string $captcha 验证码
     * @param string $id 验证码id
     * @return bool
     */
    public static function checkCaptcha( $captcha = '', $id = '' )
    {
        if ( empty($captcha) ) {
            return false;
        } else {
            $verify = new \Think\Verify();
            if ( !$verify->check($captcha, $id ) ) {
                return false;
            } else {
                return true;
            }
        }
    }
}
