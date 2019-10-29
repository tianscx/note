/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50711
Source Host           : localhost:3306
Source Database       : note

Target Server Type    : MYSQL
Target Server Version : 50711
File Encoding         : 65001

Date: 2019-09-16 14:33:47
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for note
-- ----------------------------
DROP TABLE IF EXISTS `note`;
CREATE TABLE `note` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `user_note_id` int(6) DEFAULT NULL,
  `collect` tinyint(1) DEFAULT NULL,
  `label` int(5) NOT NULL,
  `time` varchar(14) NOT NULL,
  `updateTime` varchar(14) NOT NULL,
  `content` varchar(21000) CHARACTER SET utf8 COLLATE utf8_unicode_520_ci DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `user_id` int(5) DEFAULT NULL,
  `device_id` int(5) DEFAULT NULL,
  `islock` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `test` (`user_id`),
  KEY `test1` (`user_note_id`,`user_id`,`device_id`) USING BTREE,
  KEY `test2` (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=204 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `password` varchar(32) NOT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `email` varchar(32) DEFAULT NULL,
  `label_arr` varchar(5000) DEFAULT NULL,
  `safe_password` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `test` (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
