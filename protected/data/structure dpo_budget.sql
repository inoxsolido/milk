-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 03, 2015 at 03:32 AM
-- Server version: 5.5.39
-- PHP Version: 5.4.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `dpo_budget`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_account`
--

CREATE TABLE IF NOT EXISTS `tb_account` (
`acc_id` int(11) NOT NULL COMMENT 'รหัสบัญชี ',
  `acc_number1` int(11) NOT NULL,
  `acc_number2` int(11) NOT NULL,
  `acc_number3` int(11) NOT NULL,
  `acc_number4` int(11) NOT NULL,
  `acc_name` varchar(100) NOT NULL,
  `group_id` int(11) NOT NULL,
  `parent_acc_id` int(11) DEFAULT NULL,
  `acc_erp` varchar(8) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=99 ;

-- --------------------------------------------------------

--
-- Table structure for table `tb_acc_year`
--

CREATE TABLE IF NOT EXISTS `tb_acc_year` (
  `year` year(4) NOT NULL,
  `acc_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_division`
--

CREATE TABLE IF NOT EXISTS `tb_division` (
`division_id` int(11) NOT NULL COMMENT 'รหัสฝ่าย',
  `division_name` varchar(50) NOT NULL,
  `parent_division` int(11) DEFAULT NULL,
  `office_id` varchar(2) NOT NULL,
  `erp_id` varchar(5) DEFAULT NULL COMMENT 'รหัส ERP',
  `isposition` tinyint(1) NOT NULL,
  `enable` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=149 ;

-- --------------------------------------------------------

--
-- Table structure for table `tb_group`
--

CREATE TABLE IF NOT EXISTS `tb_group` (
`group_id` int(11) NOT NULL COMMENT 'รหัสหมวด',
  `group_name` varchar(50) NOT NULL COMMENT 'ชื่อหมวด',
  `type_id` int(11) NOT NULL COMMENT 'รหัสประเภท'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `tb_month`
--

CREATE TABLE IF NOT EXISTS `tb_month` (
`month_id` int(11) NOT NULL COMMENT 'ลำดับเดือน',
  `month_name` varchar(10) NOT NULL COMMENT 'ชื่อเดือน',
  `month_name_simple` varchar(5) NOT NULL COMMENT 'ชื่อย่อเดือน',
  `month_name_erp` varchar(7) NOT NULL,
  `quarter` tinyint(1) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- Table structure for table `tb_month_goal`
--

CREATE TABLE IF NOT EXISTS `tb_month_goal` (
`month_goal_id` int(11) NOT NULL COMMENT 'รหัสเป้าหมายรายเดือน',
  `acc_id` int(11) NOT NULL COMMENT 'รหัสบัญชี',
  `value` decimal(10,2) NOT NULL COMMENT 'ยอด',
  `month_id` int(11) NOT NULL COMMENT 'ลำดับเดือน',
  `year` year(4) NOT NULL COMMENT 'ปี',
  `user_id` int(11) NOT NULL COMMENT 'หมายเลขผู้ใช้งาน',
  `division_id` int(11) NOT NULL,
  `version` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'เวอร์ชั่นไฟล์',
  `approve1_lv` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'ระดับยืนยันข้อมูลรอบแรก [0-3]',
  `approve2_lv` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=162 ;

-- --------------------------------------------------------

--
-- Table structure for table `tb_position`
--

CREATE TABLE IF NOT EXISTS `tb_position` (
`position_id` int(11) NOT NULL COMMENT 'รหัสตำแหน่ง',
  `position_name` varchar(20) NOT NULL COMMENT 'ชื่อตำแหน่ง'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `tb_profile_fill`
--

CREATE TABLE IF NOT EXISTS `tb_profile_fill` (
  `owner_div_id` int(11) NOT NULL,
  `division_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tb_type`
--

CREATE TABLE IF NOT EXISTS `tb_type` (
`type_id` int(11) NOT NULL COMMENT 'รหัสประเภท',
  `type_name` varchar(50) NOT NULL COMMENT 'ประเภท'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `tb_user`
--

CREATE TABLE IF NOT EXISTS `tb_user` (
`user_id` int(11) NOT NULL COMMENT 'รหัสสมาชิก',
  `username` varchar(20) NOT NULL COMMENT 'Username',
  `password` varchar(40) NOT NULL COMMENT 'Password',
  `fname` varchar(30) NOT NULL COMMENT 'ชื่อ',
  `lname` varchar(30) NOT NULL COMMENT 'นามสกุล',
  `gender` enum('ชาย','หญิง') NOT NULL COMMENT 'เพศ',
  `person_id` varchar(13) NOT NULL COMMENT 'รหัสประจำตัวประชาชน',
  `division_id` int(11) DEFAULT NULL COMMENT 'รหัสฝ่าย',
  `position_id` int(11) NOT NULL COMMENT 'รหัสตำแหน่ง',
  `enable` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'กำหนดให้ใช้งาน'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

-- --------------------------------------------------------

--
-- Table structure for table `tb_version`
--

CREATE TABLE IF NOT EXISTS `tb_version` (
  `month_goal_id` int(11) NOT NULL,
  `value` decimal(10,2) NOT NULL,
  `version` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_account`
--
ALTER TABLE `tb_account`
 ADD PRIMARY KEY (`acc_id`), ADD KEY `group_id` (`group_id`), ADD KEY `parent_acc_id` (`parent_acc_id`);

--
-- Indexes for table `tb_division`
--
ALTER TABLE `tb_division`
 ADD PRIMARY KEY (`division_id`), ADD KEY `parent_division` (`parent_division`);

--
-- Indexes for table `tb_group`
--
ALTER TABLE `tb_group`
 ADD PRIMARY KEY (`group_id`), ADD KEY `type_id` (`type_id`);

--
-- Indexes for table `tb_month`
--
ALTER TABLE `tb_month`
 ADD PRIMARY KEY (`month_id`);

--
-- Indexes for table `tb_month_goal`
--
ALTER TABLE `tb_month_goal`
 ADD PRIMARY KEY (`acc_id`,`month_id`,`year`,`user_id`,`division_id`), ADD UNIQUE KEY `month_goal_id` (`month_goal_id`), ADD KEY `month_id` (`month_id`), ADD KEY `user_id` (`user_id`), ADD KEY `division_id` (`division_id`);

--
-- Indexes for table `tb_position`
--
ALTER TABLE `tb_position`
 ADD PRIMARY KEY (`position_id`);

--
-- Indexes for table `tb_profile_fill`
--
ALTER TABLE `tb_profile_fill`
 ADD PRIMARY KEY (`owner_div_id`,`division_id`), ADD KEY `division_id` (`division_id`);

--
-- Indexes for table `tb_type`
--
ALTER TABLE `tb_type`
 ADD PRIMARY KEY (`type_id`);

--
-- Indexes for table `tb_user`
--
ALTER TABLE `tb_user`
 ADD PRIMARY KEY (`user_id`), ADD KEY `division_id` (`division_id`), ADD KEY `position_id` (`position_id`);

--
-- Indexes for table `tb_version`
--
ALTER TABLE `tb_version`
 ADD PRIMARY KEY (`month_goal_id`,`version`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_account`
--
ALTER TABLE `tb_account`
MODIFY `acc_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสบัญชี ',AUTO_INCREMENT=99;
--
-- AUTO_INCREMENT for table `tb_division`
--
ALTER TABLE `tb_division`
MODIFY `division_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสฝ่าย',AUTO_INCREMENT=149;
--
-- AUTO_INCREMENT for table `tb_group`
--
ALTER TABLE `tb_group`
MODIFY `group_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสหมวด',AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `tb_month`
--
ALTER TABLE `tb_month`
MODIFY `month_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ลำดับเดือน',AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `tb_month_goal`
--
ALTER TABLE `tb_month_goal`
MODIFY `month_goal_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสเป้าหมายรายเดือน',AUTO_INCREMENT=162;
--
-- AUTO_INCREMENT for table `tb_position`
--
ALTER TABLE `tb_position`
MODIFY `position_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสตำแหน่ง',AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `tb_type`
--
ALTER TABLE `tb_type`
MODIFY `type_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสประเภท',AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `tb_user`
--
ALTER TABLE `tb_user`
MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสสมาชิก',AUTO_INCREMENT=15;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
