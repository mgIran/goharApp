/*
Navicat MySQL Data Transfer

Source Server         : local
Source Server Version : 50616
Source Host           : localhost:3306
Source Database       : gohar-app

Target Server Type    : MYSQL
Target Server Version : 50616
File Encoding         : 65001

Date: 2017-03-14 17:38:24
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for iw_app_transactions
-- ----------------------------
DROP TABLE IF EXISTS `iw_app_transactions`;
CREATE TABLE `iw_app_transactions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'شناسه',
  `amount` double unsigned DEFAULT NULL COMMENT 'مقدار',
  `date` varchar(20) DEFAULT NULL COMMENT 'تاریخ',
  `status` enum('unpaid','paid','deleted') DEFAULT 'unpaid' COMMENT 'وضعیت',
  `description` varchar(200) CHARACTER SET utf8 COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'توضیحات',
  `order_id` int(12) DEFAULT NULL,
  `ref_id` varchar(50) DEFAULT NULL,
  `res_code` int(5) DEFAULT NULL,
  `sale_reference_id` varchar(50) DEFAULT NULL,
  `settle` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `model_name` varchar(50) DEFAULT NULL,
  `model_id` int(11) unsigned DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL COMMENT 'کاربر',
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_id` (`order_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of iw_app_transactions
-- ----------------------------
