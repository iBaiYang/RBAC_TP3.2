/*
Navicat MySQL Data Transfer

Source Server         : 127.0.0.1
Source Server Version : 50636
Source Host           : 127.0.0.1:3306
Source Database       : rbac_tp3.2

Target Server Type    : MYSQL
Target Server Version : 50636
File Encoding         : 65001

Date: 2017-07-18 21:15:24
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for db_admin_group
-- ----------------------------
DROP TABLE IF EXISTS `db_admin_group`;
CREATE TABLE `db_admin_group` (
  `id` int(4) NOT NULL AUTO_INCREMENT COMMENT '用户组主键',
  `group_name` varchar(14) NOT NULL COMMENT '用户组名称',
  `remark` varchar(200) NOT NULL COMMENT '用户组说明',
  `rank` int(2) NOT NULL COMMENT '用户组排序',
  PRIMARY KEY (`id`),
  UNIQUE KEY `group_name` (`group_name`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='管理员用户组表';

-- ----------------------------
-- Records of db_admin_group
-- ----------------------------

-- ----------------------------
-- Table structure for db_admin_group_power
-- ----------------------------
DROP TABLE IF EXISTS `db_admin_group_power`;
CREATE TABLE `db_admin_group_power` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL COMMENT '管理员用户组id',
  `power_id` int(11) NOT NULL COMMENT '权限表id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='管理员用户组权限表';

-- ----------------------------
-- Records of db_admin_group_power
-- ----------------------------

-- ----------------------------
-- Table structure for db_admin_menu
-- ----------------------------
DROP TABLE IF EXISTS `db_admin_menu`;
CREATE TABLE `db_admin_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_name` varchar(20) NOT NULL COMMENT '菜单名',
  `controllers` varchar(200) NOT NULL COMMENT '包含的控制器',
  `remark` varchar(200) NOT NULL COMMENT '菜单说明',
  `rank` int(11) NOT NULL COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='大类菜单表';

-- ----------------------------
-- Records of db_admin_menu
-- ----------------------------

-- ----------------------------
-- Table structure for db_admin_power
-- ----------------------------
DROP TABLE IF EXISTS `db_admin_power`;
CREATE TABLE `db_admin_power` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_id` int(3) NOT NULL COMMENT '菜单id, menu表的主键',
  `power_name` varchar(20) NOT NULL COMMENT '权限名称',
  `type` int(2) NOT NULL COMMENT '操作类型： 1 菜单类别 ； 2 操作控制； 3 其他类别',
  `mca` varchar(100) NOT NULL DEFAULT '' COMMENT 'module-controller-action',
  `rank` int(2) NOT NULL COMMENT '排序',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mca` (`mca`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='权限表';

-- ----------------------------
-- Records of db_admin_power
-- ----------------------------

-- ----------------------------
-- Table structure for db_admin_user
-- ----------------------------
DROP TABLE IF EXISTS `db_admin_user`;
CREATE TABLE `db_admin_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '管理员主键',
  `user_name` varchar(20) NOT NULL COMMENT '用户名',
  `password` varchar(32) NOT NULL COMMENT '密码',
  `last_time` int(11) DEFAULT NULL COMMENT '最近登录时间',
  `last_ip` varchar(20) DEFAULT NULL COMMENT '最近登录IP',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_name` (`user_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='管理员用户表';

-- ----------------------------
-- Records of db_admin_user
-- ----------------------------
INSERT INTO `db_admin_user` VALUES ('1', 'admin', '123456', null, null);

-- ----------------------------
-- Table structure for db_admin_user_group
-- ----------------------------
DROP TABLE IF EXISTS `db_admin_user_group`;
CREATE TABLE `db_admin_user_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '管理员用户ID, admin_user表的主键',
  `group_id` int(11) NOT NULL COMMENT '管理员用户组id, admin_group表的主键',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='管理员用户所属用户组表';

-- ----------------------------
-- Records of db_admin_user_group
-- ----------------------------

-- ----------------------------
-- Table structure for db_admin_user_power
-- ----------------------------
DROP TABLE IF EXISTS `db_admin_user_power`;
CREATE TABLE `db_admin_user_power` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '管理员用户id',
  `power_id` int(11) NOT NULL COMMENT '权限表id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='管理员用户权限表';

-- ----------------------------
-- Records of db_admin_user_power
-- ----------------------------
SET FOREIGN_KEY_CHECKS=1;
