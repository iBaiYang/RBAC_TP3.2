<?php
namespace Admin\Model;

use Think\Model;
use Think\Page;

/**
 * Admin权限管理控制器
 * Class AdminPowerModel
 * @package Admin\Model
 */
class AdminPowerModel extends Model
{
    /**
     * 添加权限
     * @param array $data 权限数组
     * @return bool|mixed
     */
    public function powerAdd( $data = array() )
    {
        if ( empty($data) ) {
            return false;
        } else {
            if ( !empty($data['mca']) ) {
                $data['mca'] = strtolower( $data['mca'] );
            }
            $power_id = $this->add( $data );
            if ( $power_id ) {
                return $power_id;
            } else {
                return false;
            }
        }
    }

    /**
     * 获取权限列表
     * @return array|mixed
     */
    public function getPowerLists( $menu_id = '' )
    {
        if ( empty($menu_id) ) {
            $data_lists = $this->select();
        } else {
            $map['menu_id'] = array('eq', $menu_id);
            $data_lists = $this->alias('a')
                ->field('a.*, b.menu_name')
                ->where( $map )
                ->join( 'db_admin_menu as b ON a.menu_id = b.id' )
                ->order( 'a.rank ASC' )
                ->select();
        }

        $data_lists = $data_lists ? $data_lists : array();
        foreach ( $data_lists as $key => $value )
        {
            switch ( $value['type'] )
            {
                case 1:
                    $data_lists[$key]['type_txt'] = '菜单类别';
                    break;
                case 2:
                    $data_lists[$key]['type_txt'] = '操作控制';
                    break;
                case 3:
                    $data_lists[$key]['type_txt'] = '其他类别';
                    break;
                default:
                    $data_lists[$key]['type_txt'] = '';
            }
        }

        return $data_lists;
    }
}