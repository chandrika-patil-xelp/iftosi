-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Oct 08, 2015 at 12:21 PM
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
CREATE DATABASE IF NOT EXISTS `db_iftosi` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `db_iftosi`;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_attribute_mapping`
--

CREATE TABLE IF NOT EXISTS `tbl_attribute_mapping` (
  `category_id` bigint(20) NOT NULL,
  `attribute_id` bigint(20) unsigned NOT NULL,
  `attr_display_flag` tinyint(4) DEFAULT NULL,
  `attr_display_position` int(11) DEFAULT '999',
  `attr_filter_flag` tinyint(4) DEFAULT NULL,
  `attr_filter_position` int(11) DEFAULT '999',
  `active_flag` tinyint(2) DEFAULT '1' COMMENT '1-Active,0-Inactive',
  KEY `category_id` (`category_id`),
  KEY `attribute_id` (`attribute_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_attribute_master`
--

CREATE TABLE IF NOT EXISTS `tbl_attribute_master` (
  `attr_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `attr_name` varchar(250) CHARACTER SET utf8 DEFAULT NULL COMMENT 'full name',
  `attr_display_name` varchar(250) CHARACTER SET utf8 DEFAULT NULL COMMENT 'visible name',
  `attr_unit` varchar(10) CHARACTER SET utf8 DEFAULT NULL COMMENT '|~| kg,gms,gb,kb,mb,mm',
  `attr_type_flag` tinyint(4) DEFAULT NULL COMMENT '1-Text Only | 2-Textarea | 3-Numeric | 4-Decimal | 5-DropDown | 6 Date | 7-Checkbox | 8-RadioBtn | 9-Use Pre Defined List',
  `attr_unit_pos` tinyint(4) DEFAULT '0' COMMENT '0-prefix 1-postfix',
  `attr_values` varchar(250) CHARACTER SET utf8 NOT NULL COMMENT 'list of all possible values separated by |~|',
  `attr_range` text CHARACTER SET utf8 COMMENT '|~|range of numeric value it can attain LB and UB',
  `use_list` varchar(50) CHARACTER SET utf8 DEFAULT NULL COMMENT 'table name to use',
  `active_flag` tinyint(2) NOT NULL DEFAULT '1' COMMENT '1-Active,0-Inactive',
  PRIMARY KEY (`attr_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=36 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_brandid_generator`
--

CREATE TABLE IF NOT EXISTS `tbl_brandid_generator` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) DEFAULT NULL,
  `category_name` varchar(255) NOT NULL DEFAULT '',
  `cdt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `udt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `aflg` tinyint(2) NOT NULL DEFAULT '1' COMMENT '1-Active,0-Inactive',
  PRIMARY KEY (`id`),
  UNIQUE KEY `brand_category` (`name`,`category_name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_categoryid_generator`
--

CREATE TABLE IF NOT EXISTS `tbl_categoryid_generator` (
  `category_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `category_name` varchar(255) NOT NULL DEFAULT '',
  `cdt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `udt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `aflg` tinyint(2) NOT NULL DEFAULT '1' COMMENT '1-Active | 0-Inactive | 2-Delete',
  PRIMARY KEY (`category_id`),
  UNIQUE KEY `category_name` (`category_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10007 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_city_master`
--

CREATE TABLE IF NOT EXISTS `tbl_city_master` (
  `cityid` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'city identity',
  `cityname` varchar(250) CHARACTER SET utf8 NOT NULL,
  `state_name` varchar(250) NOT NULL,
  `country_name` varchar(250) NOT NULL,
  `lng` decimal(17,15) NOT NULL,
  `lat` decimal(17,15) NOT NULL,
  `cdt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updatedby` varchar(100) NOT NULL COMMENT 'CMS USER',
  `udt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `active_flag` tinyint(2) NOT NULL DEFAULT '1' COMMENT '1-Active | 0-Inactive',
  PRIMARY KEY (`cityid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1095 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_contactus_master`
--

CREATE TABLE IF NOT EXISTS `tbl_contactus_master` (
  `cid` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'contact id',
  `uid` bigint(20) unsigned NOT NULL,
  `logmobile` bigint(15) unsigned NOT NULL,
  `cname` varchar(250) DEFAULT NULL COMMENT 'customer name',
  `cemail` varchar(300) DEFAULT NULL COMMENT 'customer email',
  `cquery` text NOT NULL COMMENT 'customre query',
  `dflag` tinyint(2) NOT NULL DEFAULT '1' COMMENT 'display flag{0-Inacive | 1-Active}',
  `cdt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'created date',
  `udt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'update date',
  PRIMARY KEY (`cid`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=600 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_designer_product_mapping`
--

CREATE TABLE IF NOT EXISTS `tbl_designer_product_mapping` (
  `designer_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Auto incremented designer id',
  `product_id` bigint(20) unsigned NOT NULL COMMENT 'product_code',
  `desname` varchar(250) NOT NULL COMMENT 'Designer''s Name',
  `udt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'update date',
  `cdt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'created date',
  `active_flag` tinyint(2) NOT NULL DEFAULT '1' COMMENT '{ 0-Inactive| 1-Active }',
  PRIMARY KEY (`product_id`,`designer_id`),
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
  `product_flag` tinyint(2) DEFAULT '1' COMMENT '1-product',
  KEY `catid` (`catid`),
  KEY `p_catid` (`p_catid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_newsletter_master`
--

CREATE TABLE IF NOT EXISTS `tbl_newsletter_master` (
  `newsid` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'auto incremented Newsid',
  `name` varchar(250) DEFAULT NULL COMMENT 'news heading',
  `descr` text COMMENT 'news description',
  `content` longtext COMMENT 'content',
  `dflag` tinyint(2) NOT NULL DEFAULT '1' COMMENT '{1-Active | 0-Inactive}',
  `cdt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'created date',
  `udt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'updated date',
  PRIMARY KEY (`newsid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=700 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_news_user_map`
--

CREATE TABLE IF NOT EXISTS `tbl_news_user_map` (
  `user_id` bigint(20) unsigned NOT NULL COMMENT 'user id',
  `newsid` bigint(20) unsigned NOT NULL COMMENT 'newletter id',
  `dflag` tinyint(2) NOT NULL DEFAULT '1' COMMENT '1-Active,0-Inactive',
  `cdt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'created date',
  `udt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'update date',
  `dpos` tinyint(4) unsigned NOT NULL DEFAULT '111' COMMENT 'priority{111-low| 999-high}',
  KEY `newsid` (`newsid`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_offer_master`
--

CREATE TABLE IF NOT EXISTS `tbl_offer_master` (
  `offid` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'offer id',
  `offername` varchar(100) NOT NULL COMMENT 'name of the offer',
  `des` text NOT NULL COMMENT 'description',
  `amdp` decimal(3,2) NOT NULL COMMENT 'amount discount percentage',
  `valid` varchar(30) NOT NULL COMMENT 'validity in days',
  `vdesc` text NOT NULL COMMENT 'voucher description',
  `active_flag` tinyint(2) NOT NULL DEFAULT '1' COMMENT '{0-Active | 1-Inactive }',
  `cdt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'created date ',
  `udt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'update date',
  PRIMARY KEY (`offid`),
  UNIQUE KEY `offid` (`offid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_offer_user_mapping`
--

CREATE TABLE IF NOT EXISTS `tbl_offer_user_mapping` (
  `offerid` bigint(20) unsigned NOT NULL COMMENT 'offer id used by user',
  `uid` bigint(20) unsigned NOT NULL COMMENT 'user id',
  `display_flag` tinyint(2) NOT NULL COMMENT '1-active 0-inactive',
  `cdt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'created date',
  `udt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'updated date',
  `active_flag` tinyint(2) NOT NULL DEFAULT '1' COMMENT '1-Active | 0-Inactive',
  PRIMARY KEY (`offerid`,`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_productid_generator`
--

CREATE TABLE IF NOT EXISTS `tbl_productid_generator` (
  `product_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_name` varchar(255) DEFAULT NULL,
  `product_brand` varchar(255) DEFAULT '',
  PRIMARY KEY (`product_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12079 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product_category_mapping`
--

CREATE TABLE IF NOT EXISTS `tbl_product_category_mapping` (
  `product_id` bigint(20) NOT NULL,
  `category_id` bigint(20) NOT NULL,
  `price` decimal(20,3) NOT NULL,
  `rating` decimal(2,2) NOT NULL DEFAULT '0.00',
  `display_flag` tinyint(2) NOT NULL DEFAULT '1' COMMENT '1-Active,0-Inactive',
  `cdt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `udt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY `product_id` (`product_id`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='for lineage and category handling';

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product_master`
--

CREATE TABLE IF NOT EXISTS `tbl_product_master` (
  `product_id` bigint(20) unsigned NOT NULL,
  `barcode` varchar(8) CHARACTER SET utf8 DEFAULT NULL,
  `lotref` varchar(12) CHARACTER SET utf8 DEFAULT NULL,
  `lotno` int(4) unsigned DEFAULT NULL,
  `product_name` varchar(250) CHARACTER SET utf8 DEFAULT NULL,
  `product_display_name` varchar(250) CHARACTER SET utf8 DEFAULT NULL COMMENT 'name displayed on web',
  `product_model` varchar(250) CHARACTER SET utf8 DEFAULT NULL,
  `product_brand` varchar(250) CHARACTER SET utf8 DEFAULT NULL,
  `prd_price` decimal(20,2) DEFAULT NULL,
  `lineage` varchar(250) DEFAULT NULL COMMENT 'lineage ids',
  `product_currency` varchar(10) CHARACTER SET utf8 DEFAULT NULL,
  `product_keyword` varchar(250) CHARACTER SET utf8 DEFAULT NULL,
  `product_desc` text CHARACTER SET utf8,
  `prd_wt` decimal(7,3) DEFAULT NULL,
  `prd_img` text,
  `product_warranty` text CHARACTER SET utf8,
  `updatedon` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `cdt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `desname` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product_search`
--

CREATE TABLE IF NOT EXISTS `tbl_product_search` (
  `product_id` bigint(20) unsigned NOT NULL,
  `color` varchar(100) NOT NULL DEFAULT '',
  `carats` decimal(5,3) NOT NULL DEFAULT '0.000',
  `cert` varchar(20) DEFAULT NULL,
  `shape` varchar(20) DEFAULT NULL,
  `cut` varchar(26) DEFAULT NULL,
  `cla` varchar(4) DEFAULT NULL,
  `base` int(5) DEFAULT NULL,
  `tabl` decimal(4,2) DEFAULT NULL,
  `val` decimal(9,2) DEFAULT NULL,
  `p_disc` int(3) DEFAULT NULL,
  `prop` varchar(4) DEFAULT NULL,
  `pol` varchar(2) DEFAULT NULL,
  `sym` varchar(4) DEFAULT NULL,
  `fluo` varchar(4) DEFAULT NULL,
  `td` decimal(4,2) DEFAULT NULL,
  `measurement` varchar(16) DEFAULT NULL,
  `cert1_no` varchar(11) DEFAULT NULL,
  `pa` decimal(4,2) DEFAULT NULL,
  `cr_hgt` decimal(4,2) DEFAULT NULL,
  `cr_ang` decimal(4,2) DEFAULT NULL,
  `girdle` decimal(3,2) DEFAULT NULL,
  `pd` decimal(4,2) DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL,
  `metal` varchar(20) DEFAULT NULL,
  `purity` decimal(5,2) DEFAULT '0.00',
  `nofd` int(4) DEFAULT '0',
  `dwt` decimal(7,3) DEFAULT '0.000',
  `gemwt` decimal(7,3) DEFAULT '0.000',
  `quality` varchar(15) DEFAULT NULL,
  `goldwt` decimal(7,3) DEFAULT '0.000',
  `rating` decimal(2,1) NOT NULL DEFAULT '0.0',
  KEY `product_id` (`product_id`),
  KEY `product_id_2` (`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_registration`
--

CREATE TABLE IF NOT EXISTS `tbl_registration` (
  `user_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_name` varchar(100) NOT NULL COMMENT 'Name of User',
  `password` varchar(255) NOT NULL COMMENT 'Password for login',
  `logmobile` bigint(15) NOT NULL COMMENT 'Mobile number of User',
  `email` varchar(255) NOT NULL COMMENT 'Email of user',
  `is_vendor` tinyint(2) NOT NULL DEFAULT '0' COMMENT '1-Vendor, 0-Not Vendor',
  `is_active` tinyint(2) NOT NULL DEFAULT '1' COMMENT '1-Active, 0-Non Active',
  `date_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Date and Time of registration',
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'updated at',
  `updated_by` varchar(100) NOT NULL COMMENT 'Details of the user or vendor who updated details',
  `subscribe` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '1-Active | 0-Inactive',
  PRIMARY KEY (`user_id`),
  KEY `idx_user_name` (`user_name`),
  KEY `idx_mobile` (`logmobile`),
  KEY `idx_is_active` (`is_active`),
  KEY `idx_date_time` (`date_time`),
  KEY `idx_update_time` (`update_time`),
  KEY `idx_updated_by` (`updated_by`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=800 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_speak_master`
--

CREATE TABLE IF NOT EXISTS `tbl_speak_master` (
  `sid` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'auto incremented id',
  `name` varchar(50) NOT NULL COMMENT 'user name',
  `city` varchar(50) DEFAULT NULL,
  `email` varchar(50) NOT NULL COMMENT 'user email id',
  `mobile` bigint(20) NOT NULL COMMENT 'user mobile number',
  `pimage` text NOT NULL COMMENT 'purchased prd image',
  `opinion` text NOT NULL COMMENT 'users opinion',
  `final_opinion` text NOT NULL COMMENT 'users final opinion',
  `active_flag` tinyint(2) DEFAULT '1' COMMENT '0 - InActive, 1 - Active,2-Delete',
  `upload_time` datetime NOT NULL COMMENT 'testimonial upload time',
  `updated_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `uid` bigint(20) unsigned NOT NULL COMMENT 'user id',
  `dqd` date DEFAULT '0000-00-00',
  PRIMARY KEY (`sid`),
  KEY `idx_city` (`city`),
  KEY `idx_mob` (`mobile`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=100 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user_info`
--

CREATE TABLE IF NOT EXISTS `tbl_user_info` (
  `user_id` bigint(20) unsigned NOT NULL COMMENT 'Auto increment registration key',
  `userName` varchar(250) CHARACTER SET latin7 NOT NULL COMMENT 'Name of the user',
  `gender` tinyint(2) DEFAULT '0' COMMENT 'title{0-Male|1-Female}',
  `logmobile` bigint(20) unsigned NOT NULL COMMENT 'Mobile registered',
  `email` varchar(255) NOT NULL,
  `alt_email` varchar(300) CHARACTER SET latin7 DEFAULT NULL COMMENT 'alternate email address',
  `dob` date DEFAULT NULL COMMENT 'date of birth',
  `working_phone` bigint(20) unsigned NOT NULL,
  `landline` text,
  `address1` text CHARACTER SET latin7 COMMENT 'street|landmark|area',
  `address2` text,
  `area` varchar(250) DEFAULT NULL,
  `pincode` int(6) unsigned DEFAULT NULL COMMENT 'postal area code',
  `cityname` varchar(150) CHARACTER SET latin7 DEFAULT NULL COMMENT 'cityname which has already state and country',
  `state` varchar(250) DEFAULT NULL,
  `country` varchar(250) DEFAULT NULL,
  `id_type` varchar(100) CHARACTER SET latin7 DEFAULT NULL COMMENT 'identity type',
  `id_proof_no` varchar(200) CHARACTER SET latin7 DEFAULT NULL COMMENT 'idproof number',
  `lat` decimal(17,15) DEFAULT NULL,
  `lng` decimal(17,15) DEFAULT NULL,
  `cdt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Date of creation',
  `udt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'date of updation',
  `updatedby` varchar(100) NOT NULL,
  `is_complete` tinyint(2) NOT NULL DEFAULT '1',
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='registration for every user';

-- --------------------------------------------------------

--
-- Table structure for table `tbl_vendor_master`
--

CREATE TABLE IF NOT EXISTS `tbl_vendor_master` (
  `vendor_id` bigint(20) NOT NULL,
  `orgName` varchar(255) DEFAULT NULL COMMENT 'organisation name',
  `fulladdress` text COMMENT 'company address',
  `address1` text,
  `area` varchar(255) DEFAULT NULL,
  `postal_code` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `telephones` text COMMENT 'telephone numbers of company',
  `alt_email` text COMMENT 'alternate email addresses',
  `officecity` text COMMENT 'offices in different cities',
  `ofifcecountry` text COMMENT 'offices in different countries',
  `contact_person` varchar(250) DEFAULT NULL COMMENT 'contact person name',
  `position` varchar(15) DEFAULT NULL COMMENT 'person designation',
  `contact_mobile` bigint(15) DEFAULT NULL COMMENT 'person''s mobile number',
  `email` text COMMENT 'email address of contact person',
  `memship_Cert` text NOT NULL COMMENT 'membership certificate',
  `bdbc` text COMMENT 'bharat diamond bourse certificate',
  `other_bdbc` text,
  `vatno` int(11) NOT NULL COMMENT 'Value added tax',
  `website` text COMMENT 'website address',
  `landline` text COMMENT 'fields separated by |~|',
  `mdbw` text COMMENT 'membership of other diamond bourses in world',
  `banker` text COMMENT 'different bank supports',
  `pancard` varchar(12) NOT NULL COMMENT 'pan car number',
  `turnover` varchar(20) NOT NULL COMMENT 'company turnover',
  `lng` decimal(17,15) NOT NULL DEFAULT '0.000000000000000' COMMENT 'longitutde',
  `lat` decimal(17,15) NOT NULL DEFAULT '0.000000000000000' COMMENT 'latitude',
  `updatedon` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedby` varchar(50) DEFAULT NULL,
  `cdt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `is_complete` tinyint(2) DEFAULT '0' COMMENT '0-Not complete | 1-business Det Complete | 2-Complete',
  KEY `vendor_id` (`vendor_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_vendor_product_mapping`
--

CREATE TABLE IF NOT EXISTS `tbl_vendor_product_mapping` (
  `vendor_id` bigint(20) NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `vendor_price` decimal(20,2) DEFAULT '0.00',
  `vendor_quantity` int(11) DEFAULT '0',
  `vendor_currency` varchar(10) DEFAULT 'Rs',
  `vendor_remarks` varchar(255) DEFAULT NULL,
  `active_flag` tinyint(2) DEFAULT '1' COMMENT '1-Active,0-Inactive',
  `updatedby` varchar(50) DEFAULT NULL,
  `updatedon` datetime DEFAULT NULL,
  `backendupdate` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `vendor_id` (`vendor_id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_viewlog`
--

CREATE TABLE IF NOT EXISTS `tbl_viewlog` (
  `uid` bigint(20) NOT NULL COMMENT 'for user record',
  `userName` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `email` varchar(250) CHARACTER SET utf8 NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `vid` bigint(20) unsigned NOT NULL,
  `updatedby` varchar(50) CHARACTER SET utf8 NOT NULL,
  `udt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `cdt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  KEY `vid` (`vid`),
  KEY `uid` (`uid`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_wishlist`
--

CREATE TABLE IF NOT EXISTS `tbl_wishlist` (
  `wid` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Auto Incremented ',
  `uid` bigint(15) unsigned NOT NULL COMMENT 'user id ',
  `pid` bigint(20) unsigned NOT NULL COMMENT 'product id',
  `vid` bigint(20) unsigned DEFAULT NULL COMMENT 'vendor id',
  `wf` tinyint(2) NOT NULL DEFAULT '1' COMMENT '1-Active,0-Inactive',
  `cdt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'created date',
  `udt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'updated date',
  PRIMARY KEY (`wid`),
  KEY `uid` (`uid`),
  KEY `pid` (`pid`),
  KEY `vid` (`vid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
