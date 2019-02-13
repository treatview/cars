/*
Navicat MySQL Data Transfer
Source Host     : localhost:3306
Source Database : ershouche
Target Host     : localhost:3306
Target Database : ershouche
Date: 2019-01-19 14:06:09
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for esc_link
-- ----------------------------
DROP TABLE IF EXISTS `esc_link`;
CREATE TABLE `esc_link` (
  `link_id` int(10) NOT NULL AUTO_INCREMENT,
  `link_name` varchar(255) NOT NULL,
  `link_src` varchar(255) NOT NULL,
  `link_ifshow` tinyint(1) NOT NULL,
  PRIMARY KEY (`link_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of esc_link
-- ----------------------------
INSERT INTO `esc_link` VALUES ('1', '百度1', 'www.baidu.com', '1');
