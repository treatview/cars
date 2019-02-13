/*
Navicat MySQL Data Transfer
Source Host     : localhost:3306
Source Database : ershouche
Target Host     : localhost:3306
Target Database : ershouche
Date: 2019-01-19 14:43:33
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for esc_admin_user
-- ----------------------------
DROP TABLE IF EXISTS `esc_admin_user`;
CREATE TABLE `esc_admin_user` (
  `admin_id` int(30) NOT NULL AUTO_INCREMENT,
  `admin_name` varchar(255) NOT NULL,
  `admin_password` varchar(255) DEFAULT NULL,
  `admin_email` varchar(255) NOT NULL,
  `admin_phone` varchar(255) NOT NULL,
  `admin_sex` varchar(255) NOT NULL,
  `admin_level` int(10) NOT NULL,
  PRIMARY KEY (`admin_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of esc_admin_user
-- ----------------------------
INSERT INTO `esc_admin_user` VALUES ('1', 'zhang', '202cb962ac59075b964b07152d234b70', '1', '1', '男', '0');
INSERT INTO `esc_admin_user` VALUES ('4', 'zhangs', '9cbf8a4dcb8e30682b927f352d6559a0', 'zhangs2016@13.com', '18251839582', '男', '0');
