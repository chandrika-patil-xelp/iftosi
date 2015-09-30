-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Sep 30, 2015 at 08:35 AM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `db_iftosi`
--
create database db_iftosi_test;
use db_iftosi;
-- --------------------------------------------------------

--
-- Table structure for table `tbl_attributes_mapping`
--

CREATE TABLE IF NOT EXISTS `tbl_attributes_mapping` (
  `category_id` int(11) NOT NULL DEFAULT '0',
  `attr_id` int(10) unsigned NOT NULL DEFAULT '0',
  `attr_display_flag` tinyint(4) DEFAULT '1',
  `attr_display_position` int(11) DEFAULT '999',
  `attr_filter_flag` tinyint(4) DEFAULT '1',
  `attr_filter_position` int(11) DEFAULT '999',
  `active_flag` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`category_id`,`attr_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_brandid_generator`
--

CREATE TABLE IF NOT EXISTS `tbl_brandid_generator` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) DEFAULT NULL,
  `category_name` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `brand_category` (`name`,`category_name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_categoryid_generator`
--

CREATE TABLE IF NOT EXISTS `tbl_categoryid_generator` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`category_id`),
  UNIQUE KEY `category_name` (`category_name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_city_master`
--

CREATE TABLE IF NOT EXISTS `tbl_city_master` (
  `cityid` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'city identity',
  `cityname` varchar(250) CHARACTER SET utf8 NOT NULL,
  `state_name` varchar(250) NOT NULL,
  `country_name` varchar(250) NOT NULL,
  `cdt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `udt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedby` varchar(100) NOT NULL COMMENT 'CMS USER',
  `active_flag` bit(1) NOT NULL COMMENT '1-Active | 0-Inactive',
  PRIMARY KEY (`cityid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_designer_product_mapping`
--

CREATE TABLE IF NOT EXISTS `tbl_designer_product_mapping` (
  `designer_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Auto incremented designer id',
  `product_id` bigint(20) unsigned NOT NULL COMMENT 'product_code',
  `logmobile` bigint(20) unsigned NOT NULL COMMENT 'designer''s registered number',
  `desname` varchar(250) NOT NULL COMMENT 'Designer''s Name',
  `udt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'update date',
  `cdt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'created date',
  `active_flag` bit(1) NOT NULL COMMENT '{ 0-Inactive| 1-Active }',
  PRIMARY KEY (`product_id`,`logmobile`,`designer_id`),
  KEY `designer_id` (`designer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_lineage`
--

CREATE TABLE IF NOT EXISTS `tbl_lineage` (
  `catid` bigint(20) DEFAULT '0' COMMENT 'Auto Incremented',
  `p_catid` bigint(20) DEFAULT '0' COMMENT 'parent category id',
  `cat_name` varchar(255) DEFAULT NULL COMMENT 'category name specifi',
  `cat_lvl` tinyint(4) DEFAULT '0' COMMENT 'category depth level',
  `lineage` text COMMENT 'lineage hierarchy',
  `product_flag` tinyint(4) DEFAULT '0' COMMENT '1-product'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_prd_cat_mapping`
--

CREATE TABLE IF NOT EXISTS `tbl_prd_cat_mapping` (
  `product_id` bigint(20) NOT NULL,
  `category_id` bigint(20) NOT NULL,
  `pflag` bit(1) NOT NULL,
  `cdt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `udt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_productid_generator`
--

CREATE TABLE IF NOT EXISTS `tbl_productid_generator` (
  `product_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `product_name` varchar(255) NOT NULL DEFAULT '',
  `product_brand` varchar(255) DEFAULT '',
  PRIMARY KEY (`product_id`),
  UNIQUE KEY `name_brand` (`product_name`,`product_brand`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product_attributes`
--

CREATE TABLE IF NOT EXISTS `tbl_product_attributes` (
  `product_id` bigint(20) unsigned NOT NULL,
  `attribute_id` int(4) unsigned NOT NULL,
  `category_id` bigint(20) unsigned NOT NULL,
  `value` varchar(250) CHARACTER SET utf8 DEFAULT NULL,
  `active_flag` bit(1) NOT NULL,
  `updatedby` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  PRIMARY KEY (`product_id`,`attribute_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_registration`
--

CREATE TABLE IF NOT EXISTS `tbl_registration` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(100) NOT NULL COMMENT 'Name of User',
  `password` varchar(255) NOT NULL COMMENT 'Password for login',
  `mobile` bigint(15) NOT NULL COMMENT 'Mobile number of User',
  `email` varchar(255) NOT NULL COMMENT 'Email of user',
  `is_vendor` bit(1) NOT NULL DEFAULT b'0' COMMENT '1-Vendor, 0-Not Vendor',
  `is_active` tinyint(2) NOT NULL DEFAULT '1' COMMENT '1-Active, 0-Non Active',
  `date_time` datetime NOT NULL COMMENT 'Date and Time of registration',
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'updated at',
  `updated_by` varchar(100) NOT NULL COMMENT 'Details of the user or vendor who updated details',
  PRIMARY KEY (`id`),
  KEY `idx_user_name` (`user_name`),
  KEY `idx_mobile` (`mobile`),
  KEY `idx_is_active` (`is_active`),
  KEY `idx_date_time` (`date_time`),
  KEY `idx_update_time` (`update_time`),
  KEY `idx_updated_by` (`updated_by`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_registration_status`
--

CREATE TABLE IF NOT EXISTS `tbl_registration_status` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `usermob` bigint(15) unsigned NOT NULL COMMENT 'way of user interact',
  `is_completed` tinyint(2) NOT NULL DEFAULT '0' COMMENT '1-complete,2-Form 1 complete, 0-Not Complete',
  `date_time` datetime NOT NULL COMMENT 'Date and Time of registration',
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'updated at',
  `updated_by` varchar(100) NOT NULL COMMENT 'Details of the user or vendor who updated details',
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`usermob`),
  KEY `idx_is_completed` (`is_completed`),
  KEY `idx_date_time` (`date_time`),
  KEY `idx_update_time` (`update_time`),
  KEY `idx_updated_by` (`updated_by`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_vendor_master`
--

CREATE TABLE IF NOT EXISTS `tbl_vendor_master` (
  `vendor_id` int(11) NOT NULL AUTO_INCREMENT,
  `vendor_name` varchar(255) DEFAULT '',
  `email` text COMMENT 'fields separated by |~|',
  `contact_mobile` varchar(12) NOT NULL,
  `mobile` text COMMENT 'fields separated by |~|',
  `landline` text COMMENT 'fields separated by |~|',
  `area` varchar(255) DEFAULT '',
  `address1` text,
  `address2` text,
  `city` varchar(255) DEFAULT '',
  `postal_code` varchar(255) DEFAULT '',
  `state` varchar(255) DEFAULT '',
  `country` varchar(255) DEFAULT '',
  `active_flag` tinyint(4) DEFAULT '1',
  `updatedby` varchar(50) DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `backendupdate` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_complete` bit(1) DEFAULT b'0' COMMENT '0-Not complete | 1-business Det Complete | 2-Complete',
  PRIMARY KEY (`vendor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_vendor_product_mapping`
--

CREATE TABLE IF NOT EXISTS `tbl_vendor_product_mapping` (
  `vendor_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `vendormobile` bigint(15) NOT NULL,
  `vendor_price` decimal(20,2) DEFAULT '0.00',
  `vendor_quantity` int(11) DEFAULT '0',
  `vendor_currency` varchar(10) DEFAULT 'Rs',
  `vendor_remarks` varchar(255) DEFAULT NULL,
  `active_flag` tinyint(4) DEFAULT '0',
  `updatedby` varchar(50) DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `backendupdate` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`vendor_id`,`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_wishlist`
--

CREATE TABLE IF NOT EXISTS `tbl_wishlist` (
  `wid` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Auto Incremented ',
  `uid` bigint(15) unsigned NOT NULL COMMENT 'user id ',
  `pid` bigint(20) unsigned NOT NULL COMMENT 'product id',
  `vid` bigint(15) unsigned DEFAULT NULL COMMENT 'vendor id',
  `wf` bit(1) NOT NULL DEFAULT b'0' COMMENT 'wish flag',
  `cdt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'created date',
  `udt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'updated date',
  PRIMARY KEY (`wid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tb_attribute_mapping`
--

CREATE TABLE IF NOT EXISTS `tb_attribute_mapping` (
  `category_id` int(11) NOT NULL,
  `attribute_id` int(4) unsigned NOT NULL,
  `attr_display_flag` tinyint(4) DEFAULT NULL,
  `attr_display_position` int(11) DEFAULT '999',
  `attr_filter_flag` tinyint(4) DEFAULT NULL,
  `attr_filter_position` int(11) DEFAULT '999',
  `active_flag` tinyint(4) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tb_attribute_master`
--

CREATE TABLE IF NOT EXISTS `tb_attribute_master` (
  `attr_id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `attr_name` varchar(250) CHARACTER SET utf8 DEFAULT NULL,
  `attr_display_name` varchar(250) CHARACTER SET utf8 DEFAULT NULL,
  `attr_unit` varchar(10) CHARACTER SET utf8 DEFAULT NULL,
  `attr_type_flag` tinyint(4) DEFAULT NULL COMMENT '1-Text Only | 2-Textarea | 3-Numeric | 4-Decimal | 5-DropDown | 6 Date | 7-Checkbox | 8-RadioBtn | 9-Use Pre Defined List',
  `attr_unit_pos` tinyint(4) DEFAULT '0' COMMENT '0-prefix 1-postfix',
  `attr_values` varchar(250) CHARACTER SET utf8 NOT NULL COMMENT 'list of all possible values separated by |~|',
  `attr_range` text CHARACTER SET utf8 COMMENT 'range of numric value it can attain LB and UB separated by |~|',
  `use_list` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `active_flag` bit(1) DEFAULT b'1',
  PRIMARY KEY (`attr_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tb_master_prd`
--

CREATE TABLE IF NOT EXISTS `tb_master_prd` (
  `product_id` bigint(20) unsigned NOT NULL,
  `barcode` varchar(8) CHARACTER SET utf8 NOT NULL,
  `lotref` varchar(12) CHARACTER SET utf8 NOT NULL,
  `lotno` int(4) unsigned NOT NULL,
  `product_name` varchar(250) CHARACTER SET utf8 NOT NULL,
  `product_display_name` varchar(250) CHARACTER SET utf8 NOT NULL COMMENT 'name displayed on web',
  `product_model` varchar(250) CHARACTER SET utf8 NOT NULL,
  `product_brand` varchar(250) CHARACTER SET utf8 NOT NULL,
  `prd_price` decimal(20,2) NOT NULL,
  `lineage` varchar(250) DEFAULT NULL COMMENT 'lineage ids',
  `product_currency` varchar(10) CHARACTER SET utf8 NOT NULL DEFAULT 'INR',
  `product_keyword` varchar(250) CHARACTER SET utf8 DEFAULT NULL,
  `product_desc` text CHARACTER SET utf8,
  `prd_wt` decimal(7,3) NOT NULL,
  `prd_img` text,
  `category_id` int(11) DEFAULT NULL,
  `product_warranty` varchar(10) CHARACTER SET utf8 DEFAULT NULL,
  `updatedby` varchar(50) DEFAULT NULL,
  `updatedon` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `cdt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `viewlog`
--

CREATE TABLE IF NOT EXISTS `viewlog` (
  `umob` bigint(15) NOT NULL COMMENT 'for user record',
  `userName` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `email` varchar(250) CHARACTER SET utf8 NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `vendormobile` bigint(15) unsigned NOT NULL,
  `updatedby` varchar(50) CHARACTER SET utf8 NOT NULL,
  `udt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `cdt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
