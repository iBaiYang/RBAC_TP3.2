<?php
namespace Admin\Controller;

use Think\Controller;

/**
 * 登录类
 * Class SiteController
 * @package Admin\Controller
 */
class SiteController extends Controller
{
    /**
     * Admin后台登录
     */
    public function login()
    {
        if ( !$_POST ) {
            $this->display();
        } else {
            $user_name = trim(I('post.user_name','','string'));
            $password = trim(I('post.password','','string'));
            $captcha = trim(I('post.captcha','','string'));

            // 验证码获取验证
            if ( !\Component\Common::checkCaptcha( $captcha ) ) {
                $this->error('验证码错误');
            }

//            $model_admin_user = new \Admin\Model\AdminUserModel();
            $model_admin_user = D('AdminUser');
            $user_info = $model_admin_user->checkUserLogin( $user_name, $password );
            if ( empty($user_info) ) {
                $this->error('登录异常，请刷新页面后重新登录');
            } else {
                session('admin_user_id', $user_info['id'] );
                session('admin_user_name', $user_info['user_name'] );
                if ( $user_info['id'] == 1 ) {
                    session('admin_user_type', 'super' );
                }
                $this->success('登录成功');
            }
        }
    }

    public function logout()
    {
        session('admin_user_id', null);
        /*session(null);  // 清空当前的session
        session('[destroy]');  // 销毁session*/
        header("location:".U('Site/login'));
    }

    /**
     * 制作专门方法实现验证码生成
     */
    function verifyImg()
    {
        // 走自动加载Think.class.php  autoload()
        $config = array(
            'imageH'    => 24,              // 验证码图片高度
            'imageW'    => 105,             // 验证码图片宽度
            'fontSize'  => 14,              // 验证码字体大小
            'codeSet'   => '0123456789',
            'fontttf'   => '4.ttf',         // 验证码字体，不设置随机获取
            'length'    => 4,               // 验证码位数
            'useNoise'  => false,           // 关闭验证码杂点
            'useImgBg'  => true,            // 开启验证码背景图片功能
//            'useZh'     => true,            // 使用中文验证码，'fontttf'   => '5.ttf',
        );
        $verify = new \Think\Verify($config);
        $verify->entry();
    }
}