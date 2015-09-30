-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Sep 30, 2015 at 07:20 AM
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
create database db_iftosi;
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=100013 ;

--
-- Dumping data for table `tbl_brandid_generator`
--

INSERT INTO `tbl_brandid_generator` (`id`, `name`, `category_name`) VALUES
(100009, '', ''),
(100012, 'Jewel', ''),
(100011, 'orra', '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_categoryid_generator`
--

CREATE TABLE IF NOT EXISTS `tbl_categoryid_generator` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`category_id`),
  UNIQUE KEY `category_name` (`category_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `tbl_categoryid_generator`
--

INSERT INTO `tbl_categoryid_generator` (`category_id`, `category_name`) VALUES
(4, 'jwellery');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_city_master`
--

CREATE TABLE IF NOT EXISTS `tbl_city_master` (
  `cityid` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'city identity',
  `cityname` varchar(250) CHARACTER SET utf8 NOT NULL,
  `state_name` varchar(250) NOT NULL,
  `country_name` varchar(250) NOT NULL,
  `cdt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `udt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedby` varchar(100) NOT NULL COMMENT 'CMS USER',
  `active_flag` bit(1) NOT NULL COMMENT '1-Active | 0-Inactive',
  PRIMARY KEY (`cityid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=59 ;

--
-- Dumping data for table `tbl_city_master`
--

INSERT INTO `tbl_city_master` (`cityid`, `cityname`, `state_name`, `country_name`, `cdt`, `udt`, `updatedby`, `active_flag`) VALUES
(54, 'Chandigarh', 'Punjab', 'India', '2015-09-14 17:23:26', '2015-09-14 17:23:26', '', b'0'),
(55, 'Chandigarh', 'Haryana', 'India', '2015-09-14 17:24:21', '2015-09-14 17:24:21', '', b'0'),
(56, 'Delhi', 'del', 'In', '2015-09-14 17:24:52', '2015-09-14 17:24:52', '', b'0'),
(57, 'Lucknow', 'Uttar Pradesh', 'India', '2015-09-14 17:25:22', '2015-09-14 17:25:22', '', b'0'),
(58, 'lahore', 'Punjab', 'Pakistan', '2015-09-17 16:42:06', '2015-09-17 16:42:06', '', b'0');

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

--
-- Dumping data for table `tbl_lineage`
--

INSERT INTO `tbl_lineage` (`catid`, `p_catid`, `cat_name`, `cat_lvl`, `lineage`, `product_flag`) VALUES
(10000, 0, 'Fashion & Accessories', 1, '', 0),
(10001, 10000, 'Glasses', 2, '~10000~', 0),
(10002, 10001, 'Sunglasses', 3, '~10000~10001~', 0),
(10003, 10001, 'Eyeglasses', 3, '~10000~10001~', 0),
(10004, 10001, 'Contact Lenses', 3, '~10000~10001~', 0),
(100000, 10002, 'Vincent Chase', 4, '~10000~10001~10002~', 0),
(100001, 10002, 'Parim', 4, '~10000~10001~10002~', 0),
(100002, 10002, 'John Jacobs', 4, '~10000~10001~10002~', 0),
(100003, 10002, 'Chhota Bheem', 4, '~10000~10001~10002~', 0),
(100004, 10002, 'Killer', 4, '~10000~10001~10002~', 0),
(100005, 10002, 'Bultaco', 4, '~10000~10001~10002~', 0),
(100006, 10002, 'Baolulai', 4, '~10000~10001~10002~', 0),
(100007, 10002, 'Fastrack', 4, '~10000~10001~10002~', 0),
(100008, 10002, 'Panache', 4, '~10000~10001~10002~', 0),
(1000443, 100000, 'Vincent Chase Colorato VC 5155 Matte Black Grey Gradient 1111/52 Aviator Light Weight Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000496, 100000, 'Vincent Chase VC 5148 Matte Black Grey Gradient 1111/52 Wayfarer Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000491, 100000, 'Vincent Chase VC 5147/P Matte Black Grey 1111/50 Wayfarer Polarized Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000487, 100000, 'Vincent Chase VC 5147 Matte Grey Transparent Grey Gradient A1A1/52 Wayfarer Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000545, 100000, 'Vincent Chase VC 5167 Matte Black Grey 1111V2 Wayfarer Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000492, 100000, 'Vincent Chase VC 5147/P Shiny Black Grey 1212/50 Wayfarer Polarized Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000481, 100000, 'Vincent Chase VC 5147 Matte Blue Grey Gradient 23D1/52 Wayfarer Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000478, 100000, 'Vincent Chase VC 5147 Matte Black Blue Mirror 11A1/No Wayfarer Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000521, 100000, 'Vincent Chase VC 5158/P Black Grey Gradient 1112/VO Aviator Polarized Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000475, 100000, 'Vincent Chase VC 5147 Matte Black Blue Gradient 1111/27 Wayfarer Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000499, 100000, 'Vincent Chase VC 5148/P Shiny Black Grey 1212/50 Wayfarer Polarized Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000392, 100001, 'Parim 3406 Purple Blue V1 Sunglasses', 5, '~10000~10001~10002~100001~', 1),
(1000484, 100000, 'Vincent Chase VC 5147 Matte Blue Transparent Blue Gradient D1D1/27 Wayfarer Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000445, 100000, 'Vincent Chase Colorato VC 5156 Black Grey 1112/VO Aviator Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000486, 100000, 'Vincent Chase VC 5147 Matte Grey Transparent Blue Mirror A1A1/No Wayfarer Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000407, 100001, 'Parim 9301 Grey Green Grey Gradient G1 Women''s Sunglasses', 5, '~10000~10001~10002~100001~', 1),
(1000441, 100000, 'Vincent Chase Colorato VC 5155 Aqua Blue Blue Gradient 2323/52 Aviator Light Weight Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000389, 100001, 'Parim 3403 Brown Brown Gradient B1 Women''s Sunglasses', 5, '~10000~10001~10002~100001~', 1),
(1000479, 100000, 'Vincent Chase VC 5147 Matte Black Grey Gradient 11B1/52 Wayfarer Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000390, 100001, 'Parim 3403 Maroon Blue Gradient V1 Women''s Sunglasses', 5, '~10000~10001~10002~100001~', 1),
(1000408, 100001, 'Parim 9301 Purple Grey Gradient V1 Sunglasses', 5, '~10000~10001~10002~100001~', 1),
(1000406, 100001, 'Parim 9301 Grey Brown Grey Gradient S1 Sunglasses', 5, '~10000~10001~10002~100001~', 1),
(1000489, 100000, 'Vincent Chase VC 5147 S. Black Amber Yellow 1212/E1 Wayfarer Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000518, 100000, 'Vincent Chase VC 5158/P Black Blue Gradient 1112/27 Aviator Polarized Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000391, 100001, 'Parim 3406 Pink Grey Gradient S2 Women''s Sunglasses', 5, '~10000~10001~10002~100001~', 1),
(1000395, 100001, 'Parim 9207 Pink Pink Gradient R1 Women''s Sunglasses', 5, '~10000~10001~10002~100001~', 1),
(1000397, 100001, 'Parim 9208 Black Purple Pink Gradient V1 Women''s Sunglasses', 5, '~10000~10001~10002~100001~', 1),
(1000548, 100000, 'Vincent Chase VC 5167/P Black Green 1212SO Wayfarer Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000506, 100000, 'Vincent Chase VC 5158 Black Blue Mirror 1112/NO Aviator Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000404, 100001, 'Parim 9213 Green Brown Brown Gradient B1 Women''s Polarized Sunglasses', 5, '~10000~10001~10002~100001~', 1),
(1000449, 100000, 'Vincent Chase Colorato VC 5157 Aqua Green Blue Grey Gradient 23D1/52 Wayfarer Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000428, 100000, 'Vincent Chase Chennai Express VC 5135-A Silver Green Aviator Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000505, 100000, 'Vincent Chase VC 5158 Black Blue Gradient 1112/27 Aviator Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000549, 100000, 'Vincent Chase VC 5167/P Light Blue Grey D1D1V2 Wayfarer Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000458, 100000, 'Vincent Chase Platinum VC 1025 G Gunmetal Green C200 Aviator Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000403, 100001, 'Parim 9213 Brown Pink Gradient V1 Women''s Polarized Sunglasses', 5, '~10000~10001~10002~100001~', 1),
(1000533, 100000, 'Vincent Chase VC 5165 Matte Sky Blue Blue Gradient D1D127 Wayfarer Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000539, 100000, 'Vincent Chase VC 5166 Matte Black Grey Gradient 1111B2 Wayfarer Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000393, 100001, 'Parim 9101 Grey Blue Pink Gradient V1 Women''s Polarized Sunglasses', 5, '~10000~10001~10002~100001~', 1),
(1000360, 100002, 'John Jacobs JJ 5142 Black Blue Gradient 101027 Sunglasses', 5, '~10000~10001~10002~100002~', 1),
(1000381, 100002, 'John Jacobs JJ 5148 Black Gunmetal Blue Gradient 1010B2 Wayfarer Sunglasses', 5, '~10000~10001~10002~100002~', 1),
(1000396, 100001, 'Parim 9208 Black Pink Pink Gradient V1 Women''s Polarized Sunglasses', 5, '~10000~10001~10002~100001~', 1),
(1000516, 100000, 'Vincent Chase VC 5158 Matte Black Blue Grey 1120/10 Aviator Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000446, 100000, 'Vincent Chase Colorato VC 5156 Gunmetal Grey AO12/21 Aviator Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000265, 100003, 'Chhota Bheem BOB333 Black Grey Wayfarer Kids'' Sunglasses', 5, '~10000~10001~10002~100003~', 1),
(1000273, 100003, 'Chhota Bheem BOB361 Black Grey Kids'' Sunglasses', 5, '~10000~10001~10002~100003~', 1),
(1000363, 100002, 'John Jacobs JJ 5143 Black Blue Gradient 1010B2 Kids'' Sunglasses', 5, '~10000~10001~10002~100002~', 1),
(1000368, 100002, 'John Jacobs JJ 5144 Grey Blue Gradient A2A2B2 Wayfarer Sunglasses', 5, '~10000~10001~10002~100002~', 1),
(1000357, 100002, 'John Jacobs JJ 5141 Black Blue Gradient 101027 Wayfarer Sunglasses', 5, '~10000~10001~10002~100002~', 1),
(1000334, 100002, 'John Jacobs by Prosun 4179 MG Matt Grey Green Polarized Sunglasses', 5, '~10000~10001~10002~100002~', 1),
(1000373, 100002, 'John Jacobs JJ 5146 Black Grey Gradient 10P1/B2 Wayfarer Sunglasses', 5, '~10000~10001~10002~100002~', 1),
(1000359, 100002, 'John Jacobs JJ 5141 Matte Black Blue Gradient 1111B2 Wayfarer Sunglasses', 5, '~10000~10001~10002~100002~', 1),
(1000323, 100002, 'John Jacobs by Prosun 1908 D Gunmetal Green Men''s Sunglasses', 5, '~10000~10001~10002~100002~', 1),
(1000495, 100000, 'Vincent Chase VC 5148 Matte Black Blue Mirror 11N1/No Wayfarer Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000367, 100002, 'John Jacobs JJ 5144 Black Blue Gradient 1010/27 Wayfarer Sunglasses', 5, '~10000~10001~10002~100002~', 1),
(1000274, 100003, 'Chhota Bheem BOB361 Matte Black Grey Kids'' Sunglasses', 5, '~10000~10001~10002~100003~', 1),
(1000332, 100002, 'John Jacobs by Prosun 41004 M Matt Black Green Polarized Sunglasses', 5, '~10000~10001~10002~100002~', 1),
(1000497, 100000, 'Vincent Chase VC 5148 Matte Blue Transparent Grey Gradient 23D1/52 Wayfarer Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000511, 100000, 'Vincent Chase VC 5158 Black Yellow Mirror 1130/V1 Aviator Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000524, 100000, 'Vincent Chase VC 5158/P Gunmetal Grey Gradient DO12/VO Aviator Polarized Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000328, 100002, 'John Jacobs by Prosun 3905 M Matt Black Grey Men''s Sunglasses', 5, '~10000~10001~10002~100002~', 1),
(1000519, 100000, 'Vincent Chase VC 5158/P Black Green 1112/SO Aviator Polarized Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000527, 100000, 'Vincent Chase VC 5165 Matte Blue Blue Mirror O2O2P2 Wayfarer Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000476, 100000, 'Vincent Chase VC 5147 Matte Black Blue Gradient 1123/27 Wayfarer Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000409, 100001, 'Parim 9301 Purple Pink Grey Gradient V1 Sunglasses', 5, '~10000~10001~10002~100001~', 1),
(1000507, 100000, 'Vincent Chase VC 5158 Black Blue Mirror 1120/U1 Aviator Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000448, 100000, 'Vincent Chase Colorato VC 5157 Aqua Blue Blue D1D1/27 Wayfarer Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000326, 100002, 'John Jacobs by Prosun 3903 D Gunmetal Grey Polarized Men''s Sunglasses', 5, '~10000~10001~10002~100002~', 1),
(1000463, 100000, 'Vincent Chase VC 5102 Matte Black Green Gradient Aviator Men''s Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000471, 100000, 'Vincent Chase VC 5129 Matte Black Blue Gradient 1150 Men''s Polarized Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000325, 100002, 'John Jacobs by Prosun 31012 MD Black Gunmetal Grey Gradient Aviator Polarized Men''s Sunglasses', 5, '~10000~10001~10002~100002~', 1),
(1000372, 100002, 'John Jacobs JJ 5145 Grey Blue N1N1/20 Aviator Sunglasses', 5, '~10000~10001~10002~100002~', 1),
(1000402, 100001, 'Parim 9213 Brown Green Grey Gradient S1 Women''s Polarized Sunglasses', 5, '~10000~10001~10002~100001~', 1),
(1000378, 100002, 'John Jacobs JJ 5147 Black Transparent Silver Blue A2DOB2 Sunglasses', 5, '~10000~10001~10002~100002~', 1),
(1000510, 100000, 'Vincent Chase VC 5158 Black Purple Mirror 1125/W1 Aviator Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000370, 100002, 'John Jacobs JJ 5145 Black Green Gradient 1010/81 Aviator Sunglasses', 5, '~10000~10001~10002~100002~', 1),
(1000371, 100002, 'John Jacobs JJ 5145 Blue Blue Gradient C2C2/27 Aviator Sunglasses', 5, '~10000~10001~10002~100002~', 1),
(1000493, 100000, 'Vincent Chase VC 5148 Matte Black Blue Gradient 1111/27 Wayfarer Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000353, 100002, 'John Jacobs JJ 3244 Black Green Aviator Polarized Sunglasses', 5, '~10000~10001~10002~100002~', 1),
(1000330, 100002, 'John Jacobs by Prosun 41003 M Matt Black Green Polarized Sunglasses', 5, '~10000~10001~10002~100002~', 1),
(1000285, 100003, 'Chhota Bheem BOB516 Black Yellow Grey Kids'' Sunglasses', 5, '~10000~10001~10002~100003~', 1),
(1000387, 100004, 'Killer KL3012 Gunmetal Smoke Aviator Polarized Sunglasses', 5, '~10000~10001~10002~100004~', 1),
(1000329, 100002, 'John Jacobs by Prosun 41002 MG Matt Grey Green Polarized Sunglasses', 5, '~10000~10001~10002~100002~', 1),
(1000522, 100000, 'Vincent Chase VC 5158/P Golden Blue Gradient 90EO/21 Aviator Polarized Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000490, 100000, 'Vincent Chase VC 5147 Shiny Black Grey Gradient 1211/52 Wayfarer Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000379, 100002, 'John Jacobs JJ 5147 Blue Blue Gradient D2D2/B2 Wayfarer Sunglasses', 5, '~10000~10001~10002~100002~', 1),
(1000382, 100002, 'John Jacobs JJ 5148 Blue Gunmetal Blue Gradient D2D227 Wayfarer Sunglasses', 5, '~10000~10001~10002~100002~', 1),
(1000309, 100003, 'Chhota Bheem S806 P Black Blue CE18 Aviator Kids'' Sunglasses', 5, '~10000~10001~10002~100003~', 1),
(1000520, 100000, 'Vincent Chase VC 5158/P Black Green 1212/SO Aviator Polarized Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000485, 100000, 'Vincent Chase VC 5147 Matte Brown Brown Gradient Popo/Io Wayfarer Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000317, 100002, 'John Jacobs 1509 Black Silver Blue Gradient C1 Aviator Sunglasses', 5, '~10000~10001~10002~100002~', 1),
(1000525, 100000, 'Vincent Chase VC 5158/P Silver Blue Gradient AO12/21 Aviator Polarized Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000513, 100000, 'Vincent Chase VC 5158 Golden Green 90EO/SO Aviator Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000318, 100002, 'John Jacobs 1509 Golden Brown Gradient C2 Aviator Sunglasses', 5, '~10000~10001~10002~100002~', 1),
(1000327, 100002, 'John Jacobs by Prosun 3905 AC Brown Brown Gradient Polarized Sunglasses', 5, '~10000~10001~10002~100002~', 1),
(1000512, 100000, 'Vincent Chase VC 5158 Golden Blue Gradient 90EO/21 Aviator Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000532, 100000, 'Vincent Chase VC 5165 Matte Pink Pink Gradient J2J2Y0 Wayfarer Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000376, 100002, 'John Jacobs JJ 5146 Transparent Blue Gradient A2A227 Wayfarer Sunglasses', 5, '~10000~10001~10002~100002~', 1),
(1000447, 100000, 'Vincent Chase Colorato VC 5156 Silver Grey AO12/50 Aviator Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000364, 100002, 'John Jacobs JJ 5143 Matte Black Blue Gradient 1111/27 Kids'' Sunglasses', 5, '~10000~10001~10002~100002~', 1),
(1000385, 100002, 'John Jacobs S515 Black Grey C01 Aviator Sunglasses', 5, '~10000~10001~10002~100002~', 1),
(1000473, 100000, 'Vincent Chase VC 5131 Matte Black Blue Gradient 1150 Aviator Men''s Polarized Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000523, 100000, 'Vincent Chase VC 5158/P Gunmetal Blue Gradient Do12/27 Aviator Polarized Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000528, 100000, 'Vincent Chase VC 5165 Matte Brown Yellow Mirror K2K2L2 Wayfarer Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000551, 100000, 'Vincent Chase VC 5167/P Matte Grey Transparent Grey A1A1B2 Wayfarer Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000313, 100003, 'Chhota Bheem S830 P Matte Black Grey CE13 Wayfarer Kids'' Sunglasses', 5, '~10000~10001~10002~100003~', 1),
(1000279, 100003, 'Chhota Bheem BOB396 Black Grey Grey Kids'' Sunglasses', 5, '~10000~10001~10002~100003~', 1),
(1000324, 100002, 'John Jacobs by Prosun 31007 B Black Grey Gradient Polarized Men''s Sunglasses', 5, '~10000~10001~10002~100002~', 1),
(1000535, 100000, 'Vincent Chase VC 5165 Transparent Purple Purple Gradient F2F2H2 Wayfarer Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000438, 100000, 'Vincent Chase Colorato VC 5153 Matte Blue Grey Gradient 2727/52 Polarized Light Weight Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000514, 100000, 'Vincent Chase VC 5158 Gunmetal Blue Gradient DO12/27 Aviator Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000257, 100005, 'Bultaco BTC 3048 Black Grey Col1 Sunglasses', 5, '~10000~10001~10002~100005~', 1),
(1000543, 100000, 'Vincent Chase VC 5166 Matte Grey Transparent Grey Gradient A1A1B2 Wayfarer Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000466, 100000, 'Vincent Chase VC 5127 Golden Brown Gradient 9040 Men''s Polarized Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000508, 100000, 'Vincent Chase VC 5158 Black Green 1212/SO Aviator Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000530, 100000, 'Vincent Chase VC 5165 Matte Green Green Gradient J1J181 Wayfarer Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000440, 100000, 'Vincent Chase Colorato VC 5154 Aqua Blue Blue Gradient D1D1/52 Wayfarer Polarized Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000547, 100000, 'Vincent Chase VC 5167 Matte Brown Grey Brown Gradient U2U2I0 Wayfarer Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000362, 100002, 'John Jacobs JJ 5142 Matte Grey Green Mirror N1N127 Sunglasses', 5, '~10000~10001~10002~100002~', 1),
(1000405, 100001, 'Parim 9214 Purple Brown Green Grey Gradient V1 Women''s Polarized Sunglasses', 5, '~10000~10001~10002~100001~', 1),
(1000515, 100000, 'Vincent Chase VC 5158 Gunmetal Grey Gradient DO12/VO Aviator Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000345, 100002, 'John Jacobs JJ 1508 Brown Brown Gradient C3 Aviator Sunglasses', 5, '~10000~10001~10002~100002~', 1),
(1000331, 100002, 'John Jacobs by Prosun 41003 MG Matt Grey Green Sunglasses', 5, '~10000~10001~10002~100002~', 1),
(1000442, 100000, 'Vincent Chase Colorato VC 5155 Aqua Green Grey Gradient D1D1/52 Aviator Light Weight Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000361, 100002, 'John Jacobs JJ 5142 Matte Grey Blue Mirror Z1Z127 Sunglasses', 5, '~10000~10001~10002~100002~', 1),
(1000465, 100000, 'Vincent Chase VC 5106 Matte Black Blue Gradient Aviator Men''s Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000531, 100000, 'Vincent Chase VC 5165 Matte Pink Blue Gradient G2G2H2 Wayfarer Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000488, 100000, 'Vincent Chase VC 5147 Matte Red Transparent Grey Gradient C1D1/52 Wayfarer Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000517, 100000, 'Vincent Chase VC 5158 Silver Grey Gradient AO12/VO Aviator Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000468, 100000, 'Vincent Chase VC 5127 Matte Black Grey Gradient 1150 Aviator Men''s Polarized Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000426, 100000, 'Vincent Chase A 2305 Black Grey Aviator Polarized Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000342, 100002, 'John Jacobs JJ 1506 Brown Brown Gradient C3 Sunglasses', 5, '~10000~10001~10002~100002~', 1),
(1000398, 100001, 'Parim 9209 Maroon Pink Gradient R1 Women''s Polarized Sunglasses', 5, '~10000~10001~10002~100001~', 1),
(1000498, 100000, 'Vincent Chase VC 5148 Matte Brown Brown Gradient Popo/Io Wayfarer Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000339, 100002, 'John Jacobs JJ 1503 Golden Brown C3 Sunglasses', 5, '~10000~10001~10002~100002~', 1),
(1000336, 100002, 'John Jacobs by Prosun 4185 MG Matt Grey Green Polarized Sunglasses', 5, '~10000~10001~10002~100002~', 1),
(1000354, 100002, 'John Jacobs JJ 3244 Gunmetal Grey Green Aviator Polarized Sunglasses', 5, '~10000~10001~10002~100002~', 1),
(1000380, 100002, 'John Jacobs JJ 5147 Tortoise Brown Gradient A2E2/IO Wayfarer Sunglasses', 5, '~10000~10001~10002~100002~', 1),
(1000526, 100000, 'Vincent Chase VC 5158/P Silver Grey Gradient AO12/VO Aviator Polarized Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000341, 100002, 'John Jacobs JJ 1506 Black Gradient C1 Sunglasses', 5, '~10000~10001~10002~100002~', 1),
(1000482, 100000, 'Vincent Chase VC 5147 Matte Blue Grey Gradient 23H1/52 Wayfarer Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000435, 100000, 'Vincent Chase Colorato 04 Black Pink Wayfarer Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000346, 100002, 'John Jacobs JJ 1508 Gunmetal C2 Aviator Sunglasses', 5, '~10000~10001~10002~100002~', 1),
(1000295, 100003, 'Chhota Bheem BOB546 Black Grey Kids'' Sunglasses', 5, '~10000~10001~10002~100003~', 1),
(1000480, 100000, 'Vincent Chase VC 5147 Matte Black Grey Gradient 11J1/50 Wayfarer Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000477, 100000, 'Vincent Chase VC 5147 Matte Black Blue Gradient 11F1/27 Wayfarer Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000544, 100000, 'Vincent Chase VC 5167 Matte Black Blue Gradient 1111P2 Wayfarer Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000355, 100002, 'John Jacobs JJ 3249 Black Green Aviator Polarized Sunglasses', 5, '~10000~10001~10002~100002~', 1),
(1000509, 100000, 'Vincent Chase VC 5158 Black Grey 1112/10 Aviator Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000460, 100000, 'Vincent Chase VC 1301 Cream Brown Gradient Wayfarer Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000454, 100000, 'Vincent Chase Colorato VC P03 Matte Black Pink Grey Gradient BLK/PNK Wayfarer Polarized Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000529, 100000, 'Vincent Chase VC 5165 Matte Cream Brown Gradient I2I2IO Wayfarer Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000412, 100000, 'Vincent Chase 60173 Black Grey Polarized Men''s Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000472, 100000, 'Vincent Chase VC 5131 Golden Brown Gradient 9040 Aviator Men''s Polarized Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000337, 100002, 'John Jacobs JJ 1501 Brown Brown Gradient C3 Aviator Sunglasses', 5, '~10000~10001~10002~100002~', 1),
(1000297, 100003, 'Chhota Bheem BOB556 Black White Silver Mirror Kids'' Sunglasses', 5, '~10000~10001~10002~100003~', 1),
(1000338, 100002, 'John Jacobs JJ 1501 Gunmetal Grey Gradient C2 Aviator Sunglasses', 5, '~10000~10001~10002~100002~', 1),
(1000321, 100002, 'John Jacobs by Prosun 11028 D Gunmetal Green Polarized Men''s Sunglasses', 5, '~10000~10001~10002~100002~', 1),
(1000369, 100002, 'John Jacobs JJ 5144 Tortoise Brown Gradient Y1Y1/I0 Wayfarer Sunglasses', 5, '~10000~10001~10002~100002~', 1),
(1000366, 100002, 'John Jacobs JJ 5143 Tortoise Brown Gradient Y1Y1/IO Wayfarer Kids'' Sunglasses', 5, '~10000~10001~10002~100002~', 1),
(1000358, 100002, 'John Jacobs JJ 5141 Brown Brown Gradient Y1Y1IO Wayfarer Sunglasses', 5, '~10000~10001~10002~100002~', 1),
(1000305, 100003, 'Chhota Bheem BOB558 Matte Sky Blue Blue Mirror Gradient Kids'' Sunglasses', 5, '~10000~10001~10002~100003~', 1),
(1000302, 100003, 'Chhota Bheem BOB558 Matte Black Silver Mirror Kids'' Sunglasses', 5, '~10000~10001~10002~100003~', 1),
(1000383, 100002, 'John Jacobs JJ 5148 Brown Tortoise Golden Brown Gradient Y1E2IO Wayfarer Sunglasses', 5, '~10000~10001~10002~100002~', 1),
(1000374, 100002, 'John Jacobs JJ 5146 Brown Tortoise Brown Y1Y140 Wayfarer Sunglasses', 5, '~10000~10001~10002~100002~', 1),
(1000432, 100000, 'Vincent Chase Colorato 03 Black Yellow Wayfarer Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000542, 100000, 'Vincent Chase VC 5166 Matte Grey Grey Gradient N1N1B2 Wayfarer Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000437, 100000, 'Vincent Chase Colorato VC 5134 Black Grey Silver Mirror 50J0 Wayfarer Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000503, 100000, 'Vincent Chase VC 5150 Matte Blue Grey Gradient D1D1/52 Wayfarer Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000537, 100000, 'Vincent Chase VC 5166 Matte Black Blue Mirror 1111P2 Wayfarer Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000394, 100001, 'Parim 9207 Grey Design Grey Gradient S1 Polarized Sunglasses', 5, '~10000~10001~10002~100001~', 1),
(1000434, 100000, 'Vincent Chase Colorato 04 Black Green Wayfarer Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000540, 100000, 'Vincent Chase VC 5166 Matte Blue Blue Gradient O2O227 Wayfarer Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000538, 100000, 'Vincent Chase VC 5166 Matte Black Brown Gradient 111110 Wayfarer Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000344, 100002, 'John Jacobs JJ 1508 Black Grey Gradient C1 Aviator Sunglasses', 5, '~10000~10001~10002~100002~', 1),
(1000421, 100000, 'Vincent Chase A 2254 Gunmetal Grey Polarized Men''s Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000431, 100000, 'Vincent Chase Colorato 02 Blue Yellow Wayfarer Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000436, 100000, 'Vincent Chase Colorato 04 Black Purple Wayfarer Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000433, 100000, 'Vincent Chase Colorato 03 Pink Wayfarer Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000419, 100000, 'Vincent Chase A 2227 Black Grey Aviator Polarized Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000474, 100000, 'Vincent Chase VC 5147 M. Turquoise Transparent Grey Gradient G1G1/52 Wayfarer Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000375, 100002, 'John Jacobs JJ 5146 Transparent Blue Gradient A2A2/27 Wayfarer Sunglasses', 5, '~10000~10001~10002~100002~', 1),
(1000452, 100000, 'Vincent Chase Colorato VC P01 Matte Black Yellow Blue Gradient BLK/YLW Wayfarer Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000278, 100003, 'Chhota Bheem BOB395 Black Orange Grey Kids'' Sunglasses', 5, '~10000~10001~10002~100003~', 1),
(1000494, 100000, 'Vincent Chase VC 5148 Matte Black Blue Gradient 1123/27 Wayfarer Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000292, 100003, 'Chhota Bheem BOB534 Black Yellow Grey Kids'' Sunglasses', 5, '~10000~10001~10002~100003~', 1),
(1000456, 100000, 'Vincent Chase Colorato VC P04 Pink Grey PNK Wayfarer Polarized Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000365, 100002, 'John Jacobs JJ 5143 Matte Black Blue Gradient 1111/27 Wayfarer Kids'' Sunglasses', 5, '~10000~10001~10002~100002~', 1),
(1000263, 100003, 'Chhota Bheem BOB245 Matte Black Silver Mirror Kids'' Sunglasses', 5, '~10000~10001~10002~100003~', 1),
(1000461, 100000, 'Vincent Chase VC 1301 Maroon Transparent Grey Gradient Wayfarer Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000420, 100000, 'Vincent Chase A 2227 Gunmetal Grey Aviator Polarized Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000483, 100000, 'Vincent Chase VC 5147 Matte Blue Grey Gradient 23I1/52 Wayfarer Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000457, 100000, 'Vincent Chase M 1049 Gunmetal Grey Polarized Men''s Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000377, 100002, 'John Jacobs JJ 5147 Black Tortoise Golden Brown Gradient A2E2IO Sunglasses', 5, '~10000~10001~10002~100002~', 1),
(1000459, 100000, 'Vincent Chase VC 1301 Black Tortoise Grey Gradient Wayfarer Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000340, 100002, 'John Jacobs JJ 1503 Silver C1 Sunglasses', 5, '~10000~10001~10002~100002~', 1),
(1000451, 100000, 'Vincent Chase Colorato VC 5157 Aqua Green White Grey Gradient 2311/52 Wayfarer Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000356, 100002, 'John Jacobs JJ 3249 Gunmetal Green Aviator Polarized Sunglasses', 5, '~10000~10001~10002~100002~', 1),
(1000418, 100000, 'Vincent Chase A 2131 Gunmetal Grey Polarized Men''s Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000311, 100003, 'Chhota Bheem S830 P Black Grey CE11 Wayfarer Kids'' Sunglasses', 5, '~10000~10001~10002~100003~', 1),
(1000534, 100000, 'Vincent Chase VC 5165 Matte Sky Blue Blue Mirror M2M2N2 Wayfarer Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000307, 100003, 'Chhota Bheem BOB559 Matte Black Silver Mirror Kids'' Sunglasses', 5, '~10000~10001~10002~100003~', 1),
(1000306, 100003, 'Chhota Bheem BOB559 Black Grey Kids'' Sunglasses', 5, '~10000~10001~10002~100003~', 1),
(1000500, 100000, 'Vincent Chase VC 5150 Matte Black Blue Gradient 1111/27 Wayfarer Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000351, 100002, 'John Jacobs JJ 3239 Black Green Aviator Polarized Sunglasses', 5, '~10000~10001~10002~100002~', 1),
(1000430, 100000, 'Vincent Chase Colorato 02 Black Green Wayfarer Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000352, 100002, 'John Jacobs JJ 3239 Gunmetal Green Aviator Polarized Sunglasses', 5, '~10000~10001~10002~100002~', 1),
(1000541, 100000, 'Vincent Chase VC 5166 Matte Grey Blue Mirror N1N110 Wayfarer Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000455, 100000, 'Vincent Chase Colorato VC P03 Matte Black Purple Grey Gradient BLK/PUR Wayfarer Polarized Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000281, 100003, 'Chhota Bheem BOB512 Black Red Grey Wayfarer Kids'' Sunglasses', 5, '~10000~10001~10002~100003~', 1),
(1000422, 100000, 'Vincent Chase A 2300 Gunmetal Green Aviator Polarized Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000415, 100000, 'Vincent Chase 9324 Black Grey Aviator Polarized Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000288, 100003, 'Chhota Bheem BOB531 Black Transparent Grey Wayfarer Kids'' Sunglasses', 5, '~10000~10001~10002~100003~', 1),
(1000322, 100002, 'John Jacobs by Prosun 11029 D Gunmetal Green Polarized Men''s Sunglasses', 5, '~10000~10001~10002~100002~', 1),
(1000410, 100001, 'Parim 9302 Maroon Transparent Grey Pink Gradient R1 Women''s Sunglasses', 5, '~10000~10001~10002~100001~', 1),
(1000450, 100000, 'Vincent Chase Colorato VC 5157 Aqua Green Grey Gradient T1T1/52 Wayfarer Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000536, 100000, 'Vincent Chase VC 5166 Brown Grey Brown Gradient U2U2I0 Wayfarer Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000424, 100000, 'Vincent Chase A 2302 Black Grey Polarized Men''s Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000268, 100003, 'Chhota Bheem BOB333 Matte Black Grey Wayfarer Kids'' Sunglasses', 5, '~10000~10001~10002~100003~', 1),
(1000258, 100003, 'Chhota Bheem BOB216 Black Grey Aviator Kids'' Sunglasses', 5, '~10000~10001~10002~100003~', 1),
(1000411, 100000, 'Vincent Chase 60167 Black Grey Aviator Polarized Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000256, 100005, 'Bultaco BTC 3045 Black Silver Line Grey Col2 Sunglasses', 5, '~10000~10001~10002~100005~', 1),
(1000464, 100000, 'Vincent Chase VC 5104 Golden Brown Gradient Men''s Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000453, 100000, 'Vincent Chase Colorato VC P03 Blue Yellow Grey BLU/YLW Wayfarer Polarized Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000301, 100003, 'Chhota Bheem BOB558 Black Grey Kids'' Sunglasses', 5, '~10000~10001~10002~100003~', 1),
(1000439, 100000, 'Vincent Chase Colorato VC 5153 Matte Brown Gradient POPO/IO Light Weight Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000413, 100000, 'Vincent Chase 60173 Gunmetal Green Polarized Men''s Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000272, 100003, 'Chhota Bheem BOB359 Black Orange Grey Kids'' Sunglasses', 5, '~10000~10001~10002~100003~', 1),
(1000280, 100003, 'Chhota Bheem BOB396 Blue Grey Grey Kids'' Sunglasses', 5, '~10000~10001~10002~100003~', 1),
(1000417, 100000, 'Vincent Chase A 2131 Black Grey Polarized Men''s Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000469, 100000, 'Vincent Chase VC 5128 Gunmetal Blue Gradient DO50 Aviator Men''s Polarized Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000347, 100002, 'John Jacobs JJ 3205 Black Green Polarized Sunglasses', 5, '~10000~10001~10002~100002~', 1),
(1000290, 100003, 'Chhota Bheem BOB532 Blue Yellow Grey Kids'' Sunglasses', 5, '~10000~10001~10002~100003~', 1),
(1000401, 100001, 'Parim 9213 Brown Green Blue Gradient S1 Women''s Polarized Sunglasses', 5, '~10000~10001~10002~100001~', 1),
(1000425, 100000, 'Vincent Chase A 2304 Gunmetal Grey Aviator Polarized Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000304, 100003, 'Chhota Bheem BOB558 Matte Orange Yellow Mirror Kids'' Sunglasses', 5, '~10000~10001~10002~100003~', 1),
(1000470, 100000, 'Vincent Chase VC 5129 Copper Brown Gradient 4040 Men''s Polarized Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000423, 100000, 'Vincent Chase A 2300 Gunmetal Grey Aviator Polarized Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000414, 100000, 'Vincent Chase 60173 Gunmetal Grey Polarized Men''s Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000308, 100003, 'Chhota Bheem BOB810 Light Pink Pink Kids'' Sunglasses', 5, '~10000~10001~10002~100003~', 1),
(1000550, 100000, 'Vincent Chase VC 5167/P Matte Blue Grey Gradient O2O2B2 Wayfarer Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000429, 100000, 'Vincent Chase Colorato 01 Blue Yellow Wayfarer Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000552, 100000, 'Vincent Chase VC S001 Matte Black Green Men''s Polarized Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000467, 100000, 'Vincent Chase VC 5127 Gunmetal Grey Brown Gradient D050 Aviator Men''s Polarized Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000462, 100000, 'Vincent Chase VC 1301 Matte Brown Gradient Wayfarer Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000386, 100002, 'John Jacobs S888 Matte Golden Grey Gradient C4 Aviator Sunglasses', 5, '~10000~10001~10002~100002~', 1),
(1000255, 100006, 'Baolulai BLL008 Black Grey Unisex Sunglasses', 5, '~10000~10001~10002~100006~', 1),
(1000416, 100000, 'Vincent Chase 9324 Gunmetal Grey Aviator Polarized Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000502, 100000, 'Vincent Chase VC 5150 Matte Black Grey Gradient 1111/52 Wayfarer Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000348, 100002, 'John Jacobs JJ 3205 Gunmetal Grey Green Polarized Sunglasses', 5, '~10000~10001~10002~100002~', 1),
(1000427, 100000, 'Vincent Chase A 2325 Gunmetal Grey Polarized Men''s Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000343, 100002, 'John Jacobs JJ 1506 Gunmetal Grey Gradient C2 Sunglasses', 5, '~10000~10001~10002~100002~', 1),
(1000335, 100002, 'John Jacobs by Prosun 4185 M Matt Black Green Sunglasses', 5, '~10000~10001~10002~100002~', 1),
(1000312, 100003, 'Chhota Bheem S830 P Blue Yellow Grey CE12 Wayfarer Kids'' Sunglasses', 5, '~10000~10001~10002~100003~', 1),
(1000277, 100003, 'Chhota Bheem BOB363 Matte Black Grey Kids'' Sunglasses', 5, '~10000~10001~10002~100003~', 1),
(1000269, 100003, 'Chhota Bheem BOB333 Red Pink Wayfarer Kids'' Sunglasses', 5, '~10000~10001~10002~100003~', 1),
(1000293, 100003, 'Chhota Bheem BOB534 Blue Yellow Grey Kids'' Sunglasses', 5, '~10000~10001~10002~100003~', 1),
(1000314, 100007, 'Fastrack P185BR1F Black Green 04Y Unisex Sunglasses', 5, '~10000~10001~10002~100007~', 1),
(1000333, 100002, 'John Jacobs by Prosun 4179 M Matt Black Green Sunglasses', 5, '~10000~10001~10002~100002~', 1),
(1000320, 100002, 'John Jacobs 9201 Golden Brown Gradient B1 Polarized Sunglasses', 5, '~10000~10001~10002~100002~', 1),
(1000349, 100002, 'John Jacobs JJ 3234 Black Green Aviator Polarized Sunglasses', 5, '~10000~10001~10002~100002~', 1),
(1000319, 100002, 'John Jacobs 3313 Gunmetal Grey Gradient A1 Women''s Sunglasses', 5, '~10000~10001~10002~100002~', 1),
(1000264, 100003, 'Chhota Bheem BOB245 White Silver Mirror Kids'' Sunglasses', 5, '~10000~10001~10002~100003~', 1),
(1000400, 100001, 'Parim 9212 Black Tortoise Grey Gradient S1 Women''s Polarized Sunglasses', 5, '~10000~10001~10002~100001~', 1),
(1000310, 100003, 'Chhota Bheem S806 P Matte Black Blue CE21 Aviator Kids'' Sunglasses', 5, '~10000~10001~10002~100003~', 1),
(1000270, 100003, 'Chhota Bheem BOB339 Black Grey Kids'' Sunglasses', 5, '~10000~10001~10002~100003~', 1),
(1000294, 100003, 'Chhota Bheem BOB534 Grey Yellow Grey Kids'' Sunglasses', 5, '~10000~10001~10002~100003~', 1),
(1000259, 100003, 'Chhota Bheem BOB216 Grey Grey Aviator Kids'' Sunglasses', 5, '~10000~10001~10002~100003~', 1),
(1000260, 100003, 'Chhota Bheem BOB216 Orange Brown Aviator Kids'' Sunglasses', 5, '~10000~10001~10002~100003~', 1),
(1000275, 100003, 'Chhota Bheem BOB361 Silver Grey Kids'' Sunglasses', 5, '~10000~10001~10002~100003~', 1),
(1000296, 100003, 'Chhota Bheem BOB546 Silver Grey Kids'' Sunglasses', 5, '~10000~10001~10002~100003~', 1),
(1000298, 100003, 'Chhota Bheem BOB556 Green White Silver Mirror Kids'' Sunglasses', 5, '~10000~10001~10002~100003~', 1),
(1000501, 100000, 'Vincent Chase VC 5150 Matte Black Blue Gradient 1123/27 Wayfarer Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000399, 100001, 'Parim 9212 Black Tortoise Blue Gradient S1 Women''s Polarized Sunglasses', 5, '~10000~10001~10002~100001~', 1),
(1000286, 100003, 'Chhota Bheem BOB516 Blue Yellow Grey Kids'' Sunglasses', 5, '~10000~10001~10002~100003~', 1),
(1000267, 100003, 'Chhota Bheem BOB333 Light Blue Blue Gradient Wayfarer Kids'' Sunglasses', 5, '~10000~10001~10002~100003~', 1),
(1000300, 100003, 'Chhota Bheem BOB556 Red White Silver Mirror Kids'' Sunglasses', 5, '~10000~10001~10002~100003~', 1),
(1000299, 100003, 'Chhota Bheem BOB556 Pink White Silver Mirror Kids'' Sunglasses', 5, '~10000~10001~10002~100003~', 1),
(1000444, 100000, 'Vincent Chase Colorato VC 5155 Matte Brown Brown Gradient POPO/10 Aviator Light Weight Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000350, 100002, 'John Jacobs JJ 3234 Gunmetal Green Aviator Polarized Sunglasses', 5, '~10000~10001~10002~100002~', 1),
(1000262, 100003, 'Chhota Bheem BOB245 Grey Silver Mirror Kids'' Sunglasses', 5, '~10000~10001~10002~100003~', 1),
(1000303, 100003, 'Chhota Bheem BOB558 Matte Green Blue Mirror Kids'' Sunglasses', 5, '~10000~10001~10002~100003~', 1),
(1000282, 100003, 'Chhota Bheem BOB512 Cream Red Brown Wayfarer Kids'' Sunglasses', 5, '~10000~10001~10002~100003~', 1),
(1000283, 100003, 'Chhota Bheem BOB512 Light Green White Blue Gradient Wayfarer Kids'' Sunglasses', 5, '~10000~10001~10002~100003~', 1),
(1000289, 100003, 'Chhota Bheem BOB531 Pink Transparent Pink Wayfarer Kids'' Sunglasses', 5, '~10000~10001~10002~100003~', 1),
(1000315, 100007, 'Fastrack PC002BR7 Yellow Black Brown Wayfarer Sunglasses', 5, '~10000~10001~10002~100007~', 1),
(1000504, 100000, 'Vincent Chase VC 5154 Matte Brown Gradient POPO/IO Wayfarer Polarized Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000287, 100003, 'Chhota Bheem BOB516 Grey Red Grey Kids'' Sunglasses', 5, '~10000~10001~10002~100003~', 1),
(1000266, 100003, 'Chhota Bheem BOB333 Grey Grey Wayfarer Kids'' Sunglasses', 5, '~10000~10001~10002~100003~', 1),
(1000291, 100003, 'Chhota Bheem BOB532 Grey Red Grey Kids'' Sunglasses', 5, '~10000~10001~10002~100003~', 1),
(1000271, 100003, 'Chhota Bheem BOB339 Pink Maroon Design Pink Kids'' Sunglasses', 5, '~10000~10001~10002~100003~', 1),
(1000276, 100003, 'Chhota Bheem BOB363 Black Grey Kids'' Sunglasses', 5, '~10000~10001~10002~100003~', 1),
(1000284, 100003, 'Chhota Bheem BOB512 Pink White Pink Wayfarer Kids'' Sunglasses', 5, '~10000~10001~10002~100003~', 1),
(1000546, 100000, 'Vincent Chase VC 5167 Matte Black Yellow Mirror 1111W2 Wayfarer Sunglasses', 5, '~10000~10001~10002~100000~', 1),
(1000261, 100003, 'Chhota Bheem BOB245 Black Silver Mirror Kids'' Sunglasses', 5, '~10000~10001~10002~100003~', 1),
(1000316, 100007, 'Fastrack Sunglasses Summer - Model:P117WH3', 5, '~10000~10001~10002~100007~', 1),
(1000388, 100008, 'Panache New Age Black White Blue Gradient C1-1 Aviator Sunglasses', 5, '~10000~10001~10002~100008~', 1),
(1000384, 100002, 'John Jacobs S371 Golden Tortoise Brown Gradient C6 Aviator Sunglasses', 5, '~10000~10001~10002~100002~', 1),
(10005, 0, 'Entertainment', 1, '', 0),
(10006, 10005, 'Events', 2, '~10005~', 0);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `tbl_productid_generator`
--

INSERT INTO `tbl_productid_generator` (`product_id`, `product_name`, `product_brand`) VALUES
(1, '', ''),
(7, 'bluediamond', 'orra'),
(9, 'helvic bridal ring', 'Jewel'),
(8, 'Nakshatra', 'orra');

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

--
-- Dumping data for table `tbl_product_attributes`
--

INSERT INTO `tbl_product_attributes` (`product_id`, `attribute_id`, `category_id`, `value`, `active_flag`, `updatedby`, `updatedon`) VALUES
(7, 111, 0, 'green', b'1', 'CMS_USER', '2015-09-10 16:49:19'),
(8, 111, 0, 'green', b'1', 'CMS_USER', '2015-09-14 10:28:56'),
(9, 21, 0, '32', b'1', 'CMS_USER', '2015-09-14 14:09:57');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=57 ;

--
-- Dumping data for table `tbl_registration`
--

INSERT INTO `tbl_registration` (`id`, `user_name`, `password`, `mobile`, `email`, `is_vendor`, `is_active`, `date_time`, `update_time`, `updated_by`) VALUES
(51, 'shubham123', 'd41d8cd98f00b204e9800998ecf8427e', 9975887206, 'shubham.bajapi@xelpmoc.in', b'1', 1, '2015-09-09 20:40:46', '2015-09-17 10:06:02', 'shubham123'),
(53, 'Shushrut Kumar', 'dca0104e3bf09491716c319e00a20f12', 7309290529, 'shubham.bajpai@xelpmoc.in', b'0', 1, '2015-09-12 19:04:48', '2015-09-14 09:49:55', 'Shushrut Kumar'),
(54, 'Musheer zaidi', 'f2b3a7dd46f20b724688338d9559fa20', 7878787878, 'musheerzaidi@gmail.com', b'1', 0, '2015-09-14 10:40:25', '2015-09-14 05:22:36', 'musheer'),
(55, 'Shushrut Kumar', 'f2b3a7dd46f20b724688338d9559fa20', 7309290529, 'shubham.bajpai@xelpmoc.in', b'1', 0, '2015-09-17 15:01:28', '2015-09-17 09:31:28', 'Shushrut Kumar'),
(56, 'Shushrut Kumar', 'f2b3a7dd46f20b724688338d9559fa20', 9090969587, 'shubham.bajpai@xelpmoc.in', b'1', 0, '2015-09-17 15:02:06', '2015-09-17 09:32:24', 'Shushrut Kumar');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `tbl_registration_status`
--

INSERT INTO `tbl_registration_status` (`id`, `usermob`, `is_completed`, `date_time`, `update_time`, `updated_by`) VALUES
(1, 3, 0, '2015-06-30 14:57:01', '2015-06-30 09:27:01', 'shubhamgupta'),
(2, 4, 0, '2015-06-30 15:49:12', '2015-06-30 10:19:12', 'guptashubham'),
(3, 5, 0, '2015-06-30 15:51:35', '2015-06-30 10:21:35', 'gupta_shubham');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=52 ;

--
-- Dumping data for table `tbl_vendor_master`
--

INSERT INTO `tbl_vendor_master` (`vendor_id`, `vendor_name`, `email`, `contact_mobile`, `mobile`, `landline`, `area`, `address1`, `address2`, `city`, `postal_code`, `state`, `country`, `active_flag`, `updatedby`, `updatedon`, `backendupdate`, `is_complete`) VALUES
(46, 'Shushrut Kumar', 'shubham.bajapi@xelpmoc.in', '9975887206', '7457464635', '5222362707', 'janakpuri', 'pahadganj', 'groverbaug', 'Delhi', '232322', 'delhi', 'india', 1, 'Shushrut Kumar', '2015-09-09 20:40:46', '2015-09-17 10:06:02', b'0'),
(48, 'Shushrut Kumar', 'shubham.bajpai@xelpmoc.in', '7309290529', '7457464635', '5222362707', 'janakpuri', 'pahadganj', 'groverbaug', 'Delhi', '232322', 'delhi', 'india', 0, 'Shushrut Kumar', '2015-09-12 19:04:48', '2015-09-17 09:37:17', b'0'),
(49, 'Shushrut Kumar', 'ankurup1232yahoo.com', '7878787878', '7457464635', '5222362707', 'janakpuri', 'pahadganj', 'groverbaug', 'Delhi', '232322', 'delhi', 'india', 0, 'Shushrut Kumar', '2015-09-14 10:40:25', '2015-09-17 09:37:17', b'1'),
(50, 'Shushrut Kumar', 'shubham.bajpai@xelpmoc.in', '7309290529', '7457464635', '5222362707', 'janakpuri', 'pahadganj', 'groverbaug', 'Delhi', '232322', 'delhi', 'india', 0, 'Shushrut Kumar', '2015-09-17 15:01:28', '2015-09-17 09:37:17', b'0'),
(51, 'Shushrut Kumar', 'shubham.bajpai@xelpmoc.in', '90909695', '7457464635', '5222362707', 'janakpuri', 'pahadganj', 'groverbaug', 'Delhi', '232322', 'delhi', 'india', 0, 'Shushrut Kumar', '2015-09-17 15:02:06', '2015-09-17 09:37:17', b'0');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1217 ;

--
-- Dumping data for table `tbl_vendor_product_mapping`
--

INSERT INTO `tbl_vendor_product_mapping` (`vendor_id`, `product_id`, `vendormobile`, `vendor_price`, `vendor_quantity`, `vendor_currency`, `vendor_remarks`, `active_flag`, `updatedby`, `updatedon`, `backendupdate`) VALUES
(1214, 8, 9975887206, '3300023.00', 3, 'INR', 'Excellent', 1, 'vendor', '2015-09-12 19:40:14', '2015-09-21 10:06:53'),
(1216, 7, 9975887206, '4423432.00', 3, 'USD', 'Excellent', 1, 'vendor', '2015-09-17 15:48:41', '2015-09-21 09:11:16');

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

--
-- Dumping data for table `tb_attribute_mapping`
--

INSERT INTO `tb_attribute_mapping` (`category_id`, `attribute_id`, `attr_display_flag`, `attr_display_position`, `attr_filter_flag`, `attr_filter_position`, `active_flag`) VALUES
(2, 100012, 1, 999, 1, 999, 1);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=100013 ;

--
-- Dumping data for table `tb_attribute_master`
--

INSERT INTO `tb_attribute_master` (`attr_id`, `attr_name`, `attr_display_name`, `attr_unit`, `attr_type_flag`, `attr_unit_pos`, `attr_values`, `attr_range`, `use_list`, `active_flag`) VALUES
(100012, 'flurocent', 'luminous', '10', 1, 2, '{10,20,30,40,50,60,70}', '10', NULL, b'1');

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
  `updatedon` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `cdt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_master_prd`
--

INSERT INTO `tb_master_prd` (`product_id`, `barcode`, `lotref`, `lotno`, `product_name`, `product_display_name`, `product_model`, `product_brand`, `prd_price`, `lineage`, `product_currency`, `product_keyword`, `product_desc`, `prd_wt`, `prd_img`, `category_id`, `product_warranty`, `updatedby`, `updatedon`, `cdt`) VALUES
(7, 'qw211111', '1123', 1133, 'bluediamond', 'marveric blue silver diamond', 'rw231', 'orra', '12211223.02', NULL, 'INR', 'blue,silver,diamond', 'a clear cut solitaire diamond in the vault', '223.210', 'abc.jpeg', 4, '1 year', 'CMS USER', '2015-09-17 17:31:09', '2015-09-17 12:01:09'),
(8, 'qw211111', '1123', 1133, 'bNakshatra', 'nakshatra blue ring', 'rw231', 'orra', '12211223.02', NULL, 'INR', 'blue,silver,diamond', 'a clear cut solitaire diamond in the vault', '223.210', 'abc.jpeg', 1, '1 year', 'CMS USER', '2015-09-14 10:28:56', '2015-09-14 04:58:56'),
(9, 'asf5sf', '233', 6454, 'helvic bridal ring', 'sfsaf iabvibf aidbvdibv anvvnf', 'twe32ew', 'Jewel', '42242312.02', NULL, 'INR', 'helvic', 'wefwfa ewfw ffw ef wfwe fwferf gewrg ewe', '3.010', 'c.png', 2, '2.3 year', 'CMS USER', '2015-09-14 14:09:57', '2015-09-14 08:39:57');

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

--
-- Dumping data for table `viewlog`
--

INSERT INTO `viewlog` (`umob`, `userName`, `email`, `product_id`, `vendormobile`, `updatedby`, `udt`, `cdt`) VALUES
(9975887206, 'shubham123', 'shubham.bajapi@xelpmoc.in', 7, 7878787878, 'customer', '2015-09-14 09:44:49', '2015-09-14 15:14:49'),
(7309290529, 'Shushrut Kumar', 'shubham.bajpai@xelpmoc.in', 7, 7878787878, 'customer', '2015-09-14 09:50:10', '2015-09-14 15:20:10'),
(9975887206, 'shubham123', 'shubham.bajapi@xelpmoc.in', 8, 7309290529, 'customer', '2015-09-14 09:56:57', '2015-09-14 15:26:57'),
(9975887206, 'shubham123', 'shubham.bajapi@xelpmoc.in', 7, 7878787878, 'customer', '2015-09-17 10:07:27', '2015-09-17 15:37:27');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
