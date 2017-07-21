/*
Navicat MySQL Data Transfer

Source Server         : 127.0.0.1
Source Server Version : 50636
Source Host           : 127.0.0.1:3306
Source Database       : rbac_tp3.2

Target Server Type    : MYSQL
Target Server Version : 50636
File Encoding         : 65001

Date: 2017-07-21 18:49:43
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for db_admin_menu
-- ----------------------------
DROP TABLE IF EXISTS `db_admin_menu`;
CREATE TABLE `db_admin_menu` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `menu_name` varchar(20) NOT NULL COMMENT '菜单名',
  `controllers` varchar(200) NOT NULL COMMENT '包含的控制器',
  `remark` varchar(200) NOT NULL COMMENT '菜单说明',
  `rank` int(11) NOT NULL COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='大类菜单表';

-- ----------------------------
-- Records of db_admin_menu
-- ----------------------------
INSERT INTO `db_admin_menu` VALUES ('1', '个人中心', 'self', '个人的信息管理', '10');
INSERT INTO `db_admin_menu` VALUES ('2', '菜单与权限管理', 'menu,power', '菜单与权限的相关信息', '20');
INSERT INTO `db_admin_menu` VALUES ('3', '角色管理', 'role', '角色管理', '30');
INSERT INTO `db_admin_menu` VALUES ('4', '管理员管理', 'adminer', '管理员管理', '40');

-- ----------------------------
-- Table structure for db_admin_power
-- ----------------------------
DROP TABLE IF EXISTS `db_admin_power`;
CREATE TABLE `db_admin_power` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `menu_id` int(3) NOT NULL COMMENT '菜单id, menu表的主键',
  `power_name` varchar(20) NOT NULL COMMENT '权限名称',
  `type` int(2) NOT NULL COMMENT '权限类型： 1 菜单类别 ； 2 操作控制； 3 其他类别',
  `mca` varchar(100) NOT NULL DEFAULT '' COMMENT 'module-controller-action',
  `remark` varchar(120) DEFAULT '' COMMENT '权限备注',
  `rank` int(2) NOT NULL COMMENT '排序',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mca` (`mca`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='权限表';

-- ----------------------------
-- Records of db_admin_power
-- ----------------------------
INSERT INTO `db_admin_power` VALUES ('1', '1', '个人信息', '1', 'admin-self-index', '个人信息', '10');
INSERT INTO `db_admin_power` VALUES ('2', '1', '修改密码', '1', 'admin-self-change_pwd', '修改密码', '20');
INSERT INTO `db_admin_power` VALUES ('3', '2', '添加菜单', '1', 'admin-menu-add', '添加菜单', '10');
INSERT INTO `db_admin_power` VALUES ('4', '2', '菜单列表', '1', 'admin-menu-lists', '菜单列表', '20');
INSERT INTO `db_admin_power` VALUES ('5', '2', '添加权限', '2', 'admin-power-add', '添加权限', '30');

-- ----------------------------
-- Table structure for db_admin_role
-- ----------------------------
DROP TABLE IF EXISTS `db_admin_role`;
CREATE TABLE `db_admin_role` (
  `id` int(4) NOT NULL AUTO_INCREMENT COMMENT '角色主键',
  `level` int(3) NOT NULL COMMENT '角色级别',
  `role_pid` int(4) NOT NULL DEFAULT '0' COMMENT '上级角色id',
  `role_name` varchar(14) NOT NULL COMMENT '角色名称',
  `remark` varchar(200) NOT NULL COMMENT '角色说明',
  `rank` int(2) NOT NULL COMMENT '角色排序',
  PRIMARY KEY (`id`),
  UNIQUE KEY `role_name` (`role_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='管理员角色表';

-- ----------------------------
-- Records of db_admin_role
-- ----------------------------
INSERT INTO `db_admin_role` VALUES ('1', '1', '0', '超级管理员', '拥有所有权限', '10');

-- ----------------------------
-- Table structure for db_admin_role_power
-- ----------------------------
DROP TABLE IF EXISTS `db_admin_role_power`;
CREATE TABLE `db_admin_role_power` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(4) NOT NULL COMMENT '管理员角色id',
  `power_id` int(6) NOT NULL COMMENT '权限表id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='管理员角色权限表';

-- ----------------------------
-- Records of db_admin_role_power
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
-- Table structure for db_admin_user_power
-- ----------------------------
DROP TABLE IF EXISTS `db_admin_user_power`;
CREATE TABLE `db_admin_user_power` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '管理员用户id',
  `power_id` int(6) NOT NULL COMMENT '权限表id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='管理员用户权限表';

-- ----------------------------
-- Records of db_admin_user_power
-- ----------------------------

-- ----------------------------
-- Table structure for db_admin_user_role
-- ----------------------------
DROP TABLE IF EXISTS `db_admin_user_role`;
CREATE TABLE `db_admin_user_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '管理员用户ID, admin_user表的主键',
  `role_id` int(4) NOT NULL COMMENT '管理员角色id, admin_role表的主键',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='管理员用户所属角色表';

-- ----------------------------
-- Records of db_admin_user_role
-- ----------------------------
SET FOREIGN_KEY_CHECKS=1;
