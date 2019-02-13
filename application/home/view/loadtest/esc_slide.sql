/*
Navicat MySQL Data Transfer
Source Host     : localhost:3306
Source Database : ershouche
Target Host     : localhost:3306
Target Database : ershouche
Date: 2019-01-19 14:06:23
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for esc_slide
-- ----------------------------
DROP TABLE IF EXISTS `esc_slide`;
CREATE TABLE `esc_slide` (
  `slide_id` int(10) NOT NULL AUTO_INCREMENT,
  `slide_pic` varchar(255) NOT NULL,
  `slide_ifshow` tinyint(1) NOT NULL,
  `slide_desc` varchar(255) NOT NULL,
  PRIMARY KEY (`slide_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of esc_slide
-- ----------------------------
INSERT INTO `esc_slide` VALUES ('2', '/uploads/20190118/6640dbee8bf0827cb000de1d5e660d6a.jpg', '1', 'sfwef');
