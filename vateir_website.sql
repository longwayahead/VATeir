-- phpMyAdmin SQL Dump
-- version 4.2.12deb2+deb8u2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 06, 2017 at 11:03 PM
-- Server version: 5.5.55-0+deb8u1
-- PHP Version: 5.6.30-0+deb8u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `vateir_website`
--

-- --------------------------------------------------------

--
-- Table structure for table `airport_list`
--

CREATE TABLE IF NOT EXISTS `airport_list` (
`id` int(11) NOT NULL,
  `icao` varchar(4) NOT NULL,
  `name` varchar(30) NOT NULL,
  `major` tinyint(1) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `airport_list`
--

INSERT INTO `airport_list` (`id`, `icao`, `name`, `major`) VALUES
(1, 'EICK', 'Cork', 0),
(2, 'EICM', 'Galway', 0),
(3, 'EIDL', 'Donegal', 0),
(4, 'EIDW', 'Dublin', 1),
(5, 'EIKN', 'Knock', 0),
(6, 'EIKY', 'Kerry', 0),
(7, 'EINN', 'Shannon', 0),
(8, 'EISG', 'Sligo', 0),
(9, 'EIWF', 'Waterford', 0),
(10, 'EIWT', 'Weston', 0),
(11, 'EISN', 'Shannon Control (High)', 0),
(12, 'EISN', 'Shannon Control (Low)', 0);

-- --------------------------------------------------------

--
-- Table structure for table `airport_validation`
--

CREATE TABLE IF NOT EXISTS `airport_validation` (
`id` int(2) unsigned NOT NULL,
  `airport_list_id` int(2) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `airport_validation`
--

INSERT INTO `airport_validation` (`id`, `airport_list_id`) VALUES
(1, 4);

-- --------------------------------------------------------

--
-- Table structure for table `allowed`
--

CREATE TABLE IF NOT EXISTS `allowed` (
  `cid` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `allowed`
--

INSERT INTO `allowed` (`cid`) VALUES
(1032602);

-- --------------------------------------------------------

--
-- Table structure for table `availability`
--

CREATE TABLE IF NOT EXISTS `availability` (
`id` int(11) NOT NULL,
  `cid` int(11) NOT NULL,
  `date` date NOT NULL,
  `time_from` time NOT NULL,
  `time_until` time NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `session_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `calendar`
--

CREATE TABLE IF NOT EXISTS `calendar` (
`id` int(10) unsigned NOT NULL,
  `cid` int(11) NOT NULL,
  `hash` varchar(10) NOT NULL,
  `events` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'include events?'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cards`
--

CREATE TABLE IF NOT EXISTS `cards` (
`id` int(10) unsigned NOT NULL,
  `cid` int(9) NOT NULL,
  `card_type` int(5) NOT NULL,
  `link_id` int(9) NOT NULL,
  `submitted` datetime NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `card_types`
--

CREATE TABLE IF NOT EXISTS `card_types` (
`id` int(10) unsigned NOT NULL,
  `type` smallint(2) NOT NULL COMMENT '0 = session report. 1 = note',
  `name` varchar(20) NOT NULL,
  `colour` varchar(20) NOT NULL,
  `exam` tinyint(4) NOT NULL DEFAULT '0',
  `sort` smallint(3) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `card_types`
--

INSERT INTO `card_types` (`id`, `type`, `name`, `colour`, `exam`, `sort`, `deleted`) VALUES
(1, 0, 'Live', '#504caf', 0, 1, 0),
(2, 0, 'Sweatbox', ' #795548', 0, 2, 0),
(3, 0, 'Exam', ' #9c27b0', 1, 3, 0),
(4, 1, 'Validated', ' #4caf50', 0, 3, 0),
(5, 1, 'Revoked', '#607D8B', 0, 2, 0),
(6, 1, 'Note', '#009688', 0, 1, 0),
(7, 2, 'No Show', '#C62828', 0, 1, 0),
(8, 2, 'Cancelled', '#FF9800', 0, 2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `config`
--

CREATE TABLE IF NOT EXISTS `config` (
`id` int(3) NOT NULL,
  `config` varchar(25) NOT NULL,
  `setting` tinyint(1) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `config`
--

INSERT INTO `config` (`id`, `config`, `setting`) VALUES
(1, 'login', 1);

-- --------------------------------------------------------

--
-- Table structure for table `controllers`
--

CREATE TABLE IF NOT EXISTS `controllers` (
  `id` int(9) NOT NULL,
  `first_name` varchar(25) NOT NULL,
  `last_name` varchar(25) NOT NULL,
  `email` varchar(50) NOT NULL,
  `rating` smallint(2) NOT NULL,
  `pilot_rating` smallint(3) NOT NULL,
  `pratingstring` varchar(30) NOT NULL,
  `vateir_status` smallint(2) NOT NULL DEFAULT '1',
  `alive` tinyint(1) NOT NULL,
  `regdate_vatsim` datetime NOT NULL,
  `regdate_vateir` datetime NOT NULL,
  `grou` smallint(3) NOT NULL DEFAULT '10',
  `adminPerm` tinyint(2) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `controllers`
--

INSERT INTO `controllers` (`id`, `first_name`, `last_name`, `email`, `rating`, `pilot_rating`, `pratingstring`, `vateir_status`, `alive`, `regdate_vatsim`, `regdate_vateir`, `grou`, `adminPerm`) VALUES
(0, 'SYSTEM', '', '', 0, 0, '', 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 10, 0),
(1032602, 'Cillian', '&#211; L&#250;ing', 'myemail@address.com', 5, 1, 'P1', 1, 1, '2007-10-24 09:30:07', '2015-06-14 12:19:07', 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `crons`
--

CREATE TABLE IF NOT EXISTS `crons` (
`id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `data` text
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `download_categories`
--

CREATE TABLE IF NOT EXISTS `download_categories` (
`id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `sort` smallint(6) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `download_categories`
--

INSERT INTO `download_categories` (`id`, `name`, `sort`) VALUES
(1, 'Controller Downloads', 1),
(2, 'Pilot Downloads', 2);

-- --------------------------------------------------------

--
-- Table structure for table `download_files`
--

CREATE TABLE IF NOT EXISTS `download_files` (
`id` int(10) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `sub_category` smallint(6) NOT NULL,
  `date_added` datetime NOT NULL,
  `added_by` int(9) NOT NULL,
  `file_name` varchar(100) NOT NULL,
  `file_size` int(11) NOT NULL,
  `file_type` varchar(20) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `download_files`
--

INSERT INTO `download_files` (`id`, `name`, `description`, `sub_category`, `date_added`, `added_by`, `file_name`, `file_size`, `file_type`) VALUES
(1, 'IFSD Scenery', 'Freeware Irish Scenery from Irish Flight Sim Design (disbanded). Includes scenery for Cork (EICK), Dublin (EIDW), Galway (EICM), Sligo (EISG), Waterford (EIWF), and Kilkenny (EIKL). Installer and Patch included. Scenery dates from 2002.', 1, '2015-08-09 22:00:00', 1032602, 'ifsd.zip', 31460929, 'application/zip'),
(2, 'IFSD Dublin Bay', 'Coastal scenery in the bay around Dublin including landmarks such as Howth harbour, Dun Laoghaire Harbour, The Bailey lighthouse, Kish lighthouse (complete with landable helicopter pad) and much more. This is a manual zip file install,  please refer to the included "Readme.pdf" document for instructions.  Scenery by Michael Kelly. ', 1, '2015-08-09 22:15:25', 1032602, 'dublinbay.zip', 5442399, 'application/zip'),
(3, '100 Irish Runways', 'Irish grass strips. Ideal for VFR flying.', 1, '2015-08-09 22:22:33', 1032602, '100irish.zip', 136737, 'application/zip'),
(4, 'EIWF AFCAD', 'Waterford AFCADs/scenery based on the real world layout, by Aaron Fraher.', 1, '2015-08-09 22:31:37', 1032602, 'sceneryeiwf.zip', 3598, 'application/zip'),
(5, 'VATeir 1503', 'Euroscope Controller Pack March 2015.', 2, '2016-02-11 23:25:15', 1032602, '1503.zip', 9131000, 'application/zip');

-- --------------------------------------------------------

--
-- Table structure for table `download_sub_categories`
--

CREATE TABLE IF NOT EXISTS `download_sub_categories` (
`id` int(10) unsigned NOT NULL,
  `name` varchar(100) NOT NULL,
  `category` smallint(6) NOT NULL,
  `sort` smallint(6) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `download_sub_categories`
--

INSERT INTO `download_sub_categories` (`id`, `name`, `category`, `sort`) VALUES
(1, 'Scenery', 2, 1),
(2, 'Controller Packs', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `email_unsubscribe`
--

CREATE TABLE IF NOT EXISTS `email_unsubscribe` (
`id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `infocards`
--

CREATE TABLE IF NOT EXISTS `infocards` (
`id` int(11) NOT NULL,
  `session_id` int(11) NOT NULL,
  `card_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `logins`
--

CREATE TABLE IF NOT EXISTS `logins` (
`id` int(9) NOT NULL,
  `cid` int(9) NOT NULL,
  `ip` varchar(45) NOT NULL,
  `datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

CREATE TABLE IF NOT EXISTS `notes` (
`id` int(10) unsigned NOT NULL,
  `student_cid` int(9) NOT NULL,
  `mentor_cid` int(9) NOT NULL,
  `note_type` int(4) NOT NULL,
  `submitted_date` date NOT NULL,
  `subject` varchar(20) NOT NULL,
  `text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE IF NOT EXISTS `notifications` (
`id` int(10) unsigned NOT NULL,
  `type` int(11) NOT NULL,
  `from` int(9) NOT NULL,
  `to_type` tinyint(1) NOT NULL COMMENT '0 = individual, 1 = group',
  `to` int(9) NOT NULL,
  `submitted` datetime NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'notification dealt with/not'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `notifications_comments`
--

CREATE TABLE IF NOT EXISTS `notifications_comments` (
`id` int(11) NOT NULL,
  `notification_id` int(11) NOT NULL,
  `submitted` datetime NOT NULL,
  `submitted_by` int(11) NOT NULL,
  `text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `notification_groups`
--

CREATE TABLE IF NOT EXISTS `notification_groups` (
`id` int(10) unsigned NOT NULL,
  `name` varchar(20) NOT NULL,
  `permission_required` varchar(20) NOT NULL,
  `sort` smallint(6) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `notification_groups`
--

INSERT INTO `notification_groups` (`id`, `name`, `permission_required`, `sort`) VALUES
(1, 'Admin', 'admin', 0),
(2, 'TD Mentors', 'mentor', 1),
(3, 'TD Staff', 'tdstaff', 2),
(4, 'TD Students', 'student', 3);

-- --------------------------------------------------------

--
-- Table structure for table `notification_types`
--

CREATE TABLE IF NOT EXISTS `notification_types` (
`id` int(10) unsigned NOT NULL,
  `group` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `sort` smallint(6) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `notification_types`
--

INSERT INTO `notification_types` (`id`, `group`, `name`, `sort`) VALUES
(1, 2, 'Theory Token Request', 1),
(2, 2, 'Exam Passed', 2);

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE IF NOT EXISTS `permissions` (
`id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `permissions` text NOT NULL,
  `sort` smallint(6) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `permissions`, `sort`) VALUES
(1, 'Unassigned', '', 0),
(2, 'Admin', '{"student": 1,"mentor": 1,"s1mentor": 1,"s2mentor": 1,"s3mentor": 1,"c1mentor": 1,"tdstaff": 1,"operations":1,"admin": 1,"superadmin": 1}', 7),
(10, 'Student', '{"student": 1,"mentor": 0,"s1mentor": 0,"s2mentor": 0,"s3mentor": 0,"c1mentor": 0,"operations":0,"tdstaff": 0,"admin": 0}', 1),
(11, 'S1 Mentor', '{"student": 1,"mentor": 1,"s1mentor": 1,"s2mentor": 0,"s3mentor": 0,"c1mentor": 0,"operations":0,"tdstaff": 0,"admin": 0}', 2),
(12, 'S2 Mentor', '{"student": 1,"mentor": 1,"s1mentor": 1,"s2mentor": 1,"s3mentor": 0,"c1mentor": 0,"operations":0,"tdstaff": 0,"admin": 0}', 3),
(13, 'S3 Mentor', '{"student": 1,"mentor": 1,"s1mentor": 1,"s2mentor": 1,"s3mentor": 1,"c1mentor": 0,"operations":0,"tdstaff": 0,"admin": 0}', 4),
(14, 'C1 Mentor', '{"student": 1,"mentor": 1,"s1mentor": 1,"s2mentor": 1,"s3mentor": 1,"c1mentor": 1,"operations":0,"tdstaff": 0,"admin": 0}', 5),
(15, 'TD Staff', '{"student": 1,"mentor": 1,"s1mentor": 1,"s2mentor": 1,"s3mentor": 1,"c1mentor": 1,"tdstaff": 1,"operations":0 ,"admin": 0}', 6);

-- --------------------------------------------------------

--
-- Table structure for table `permissionsAdmin`
--

CREATE TABLE IF NOT EXISTS `permissionsAdmin` (
  `id` smallint(5) unsigned NOT NULL,
  `name` varchar(20) NOT NULL,
  `sort` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `permissionsAdmin`
--

INSERT INTO `permissionsAdmin` (`id`, `name`, `sort`) VALUES
(0, 'None', 1),
(1, 'Admin', 3),
(2, 'Operations', 2);

-- --------------------------------------------------------

--
-- Table structure for table `position_list`
--

CREATE TABLE IF NOT EXISTS `position_list` (
`id` int(11) NOT NULL,
  `position_type_id` int(3) NOT NULL,
  `airport_list_id` int(4) NOT NULL,
  `callsign` varchar(15) NOT NULL,
  `freq` varchar(7) NOT NULL,
  `name` varchar(30) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `position_list`
--

INSERT INTO `position_list` (`id`, `position_type_id`, `airport_list_id`, `callsign`, `freq`, `name`) VALUES
(1, 4, 1, 'EICK_APP', '119.90', 'Cork Radar'),
(2, 2, 1, 'EICK_GND', '121.85', 'Cork Ground'),
(3, 3, 1, 'EICK_TWR', '119.30', 'Cork Tower'),
(4, 3, 2, 'EICM_TWR', '122.50', 'Galway Tower'),
(5, 3, 3, 'EIDL_TWR', '129.80', 'Donegal Tower'),
(6, 5, 4, 'EIDW_1_CTR', '129.17', 'Dublin Control'),
(7, 5, 4, 'EIDW_2_CTR', '132.57', 'Dublin Control'),
(8, 5, 4, 'EIDW_3_CTR', '124.65', 'Dublin Control'),
(9, 5, 4, 'EIDW_4_CTR', '126.25', 'Dublin Control'),
(11, 4, 4, 'EIDW_APP', '121.10', 'Dublin Director'),
(13, 1, 4, 'EIDW_DEL', '121.87', 'Dublin Delivery'),
(14, 2, 4, 'EIDW_GND', '121.80', 'Dublin Ground'),
(15, 5, 4, 'EIDW_H_APP', '119.55', 'Dublin Holding Control'),
(18, 3, 4, 'EIDW_TWR', '118.60', 'Dublin Tower'),
(20, 3, 5, 'EIKN_TWR', '130.70', 'Knock Tower'),
(22, 3, 6, 'EIKY_TWR', '123.32', 'Kerry Tower'),
(23, 4, 7, 'EINN_APP', '121.40', 'Shannon Approach'),
(24, 2, 7, 'EINN_GND', '121.90', 'Shannon Ground'),
(25, 3, 7, 'EINN_TWR', '118.70', 'Shannon Tower'),
(26, 3, 8, 'EISG_TWR', '122.10', 'Sligo Tower'),
(27, 3, 9, 'EIWF_TWR', '129.85', 'Waterford Tower'),
(29, 3, 10, 'EIWT_TWR', '122.40', 'Weston Tower'),
(30, 6, 11, 'EISN_CTR', '131.15', 'Shannon Control');

-- --------------------------------------------------------

--
-- Table structure for table `position_type`
--

CREATE TABLE IF NOT EXISTS `position_type` (
`id` int(11) NOT NULL,
  `ident` varchar(3) NOT NULL,
  `name_short` varchar(3) NOT NULL,
  `name_long` varchar(30) NOT NULL,
  `sort` int(2) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `position_type`
--

INSERT INTO `position_type` (`id`, `ident`, `name_short`, `name_long`, `sort`) VALUES
(1, 'DEL', 'GMP', 'Clearance Delivery', 1),
(2, 'GND', 'GMC', 'Ground', 2),
(3, 'TWR', 'AIR', 'Tower', 3),
(4, 'APP', 'APP', 'Approach/Final Director', 4),
(5, 'CTR', 'TMA', 'Control', 5),
(6, 'CTR', 'ACC', 'Control', 6);

-- --------------------------------------------------------

--
-- Table structure for table `programs`
--

CREATE TABLE IF NOT EXISTS `programs` (
`id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `ident` varchar(10) NOT NULL,
  `permissions` varchar(20) NOT NULL,
  `sort` int(4) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `programs`
--

INSERT INTO `programs` (`id`, `name`, `ident`, `permissions`, `sort`) VALUES
(1, 'Completed', 'CTD', 'tdstaff', 999),
(2, 'OBS&ndash;S1', 'S1', 's1mentor', 2),
(3, 'S1&ndash;S2', 'S2', 's2mentor', 3),
(4, 'S2&ndash;S3', 'S3', 's3mentor', 4),
(5, 'S3&ndash;C1', 'C1', 'c1mentor', 5);

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE IF NOT EXISTS `ratings` (
  `id` int(11) NOT NULL,
  `long` varchar(20) NOT NULL,
  `short` varchar(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ratings`
--

INSERT INTO `ratings` (`id`, `long`, `short`) VALUES
(-1, 'Inactive', 'INA'),
(0, 'Suspended', 'SUS'),
(1, 'Observer', 'OBS'),
(2, 'Tower Trainee', 'S1'),
(3, 'Tower Controller', 'S2'),
(4, 'TMA Controller', 'S3'),
(5, 'Enroute Controller', 'C1'),
(6, 'Unused', 'UNS'),
(7, 'Senior Controller', 'C3'),
(8, 'Instructor', 'INS'),
(9, 'Unused', 'UNS'),
(10, 'Senior Instructor', 'INS+'),
(11, 'Supervisor', 'SUP'),
(12, 'Administrator', 'ADM');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE IF NOT EXISTS `reports` (
`id` int(10) unsigned NOT NULL,
  `student_cid` int(9) NOT NULL,
  `mentor_cid` int(9) NOT NULL,
  `report_type_id` int(5) NOT NULL,
  `position_id` int(5) NOT NULL,
  `submitted_date` date NOT NULL,
  `session_date` date NOT NULL,
  `text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `report_programs_sectors`
--

CREATE TABLE IF NOT EXISTS `report_programs_sectors` (
`id` int(10) unsigned NOT NULL,
  `program_id` int(5) NOT NULL,
  `sector_id` int(5) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1 COMMENT='This attributes a training program to a control sector';

--
-- Dumping data for table `report_programs_sectors`
--

INSERT INTO `report_programs_sectors` (`id`, `program_id`, `sector_id`) VALUES
(1, 2, 1),
(2, 2, 2),
(3, 3, 3),
(4, 4, 4),
(5, 5, 5),
(6, 5, 6);

-- --------------------------------------------------------

--
-- Table structure for table `report_slider_answers`
--

CREATE TABLE IF NOT EXISTS `report_slider_answers` (
`id` int(10) unsigned NOT NULL,
  `report_id` int(5) NOT NULL,
  `slider_id` int(9) NOT NULL,
  `value` tinyint(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `report_slider_categories`
--

CREATE TABLE IF NOT EXISTS `report_slider_categories` (
`id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `sort` mediumint(9) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `report_slider_categories`
--

INSERT INTO `report_slider_categories` (`id`, `name`, `sort`) VALUES
(1, 'Controller Clients', 1),
(2, 'Management of Flight Data', 2),
(3, 'Meterology', 3),
(4, 'Radiotelephony', 4),
(5, 'Air Traffic Management Concepts', 5),
(6, 'Separation', 6),
(7, 'Aerodrome Control Skills', 7),
(8, 'Approach and Area Control Skills', 8),
(9, 'Forward Planning', 9);

-- --------------------------------------------------------

--
-- Table structure for table `report_slider_questions`
--

CREATE TABLE IF NOT EXISTS `report_slider_questions` (
`id` int(10) unsigned NOT NULL,
  `type` tinyint(1) NOT NULL,
  `category` mediumint(4) NOT NULL,
  `report_type` smallint(2) NOT NULL,
  `program_id` int(5) NOT NULL,
  `text` varchar(50) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=72 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `report_slider_questions`
--

INSERT INTO `report_slider_questions` (`id`, `type`, `category`, `report_type`, `program_id`, `text`, `deleted`) VALUES
(1, 0, 1, 2, 0, 'Controller Clients', 0),
(2, 0, 2, 2, 0, 'Viewing/Editing flight plans', 0),
(3, 0, 2, 2, 2, 'Maintaining squawks', 0),
(4, 0, 3, 2, 2, 'Effects of wind on aircraft', 0),
(5, 0, 3, 2, 2, 'Understanding wind readings', 1),
(6, 0, 3, 2, 2, 'Decoding METAR', 0),
(7, 0, 4, 2, 2, 'R/T Principles', 0),
(8, 0, 4, 2, 2, 'Aerodrome services', 0),
(9, 0, 5, 2, 2, 'Helicopters', 0),
(10, 0, 5, 2, 2, 'Code/Callsign Conversion', 0),
(11, 0, 5, 2, 2, 'ATM Positions', 0),
(12, 0, 6, 2, 2, 'Standards of separation', 0),
(13, 0, 6, 2, 2, 'Technical separation around the aerodrome', 0),
(14, 0, 6, 2, 2, 'Applying technical separation around the aerodrome', 0),
(15, 0, 7, 2, 2, 'Aerodrome Knowledge', 0),
(16, 0, 9, 2, 2, 'Co-ordination', 0),
(17, 0, 9, 2, 2, 'Situational awareness', 0),
(18, 0, 9, 2, 2, 'Emergency handling', 0),
(19, 0, 1, 3, 3, 'Providing an ATIS', 1),
(20, 0, 3, 3, 3, 'Understanding wind', 0),
(21, 0, 3, 3, 3, 'Basic altimetry', 0),
(22, 0, 4, 3, 3, 'Aerodrome Services', 0),
(23, 0, 4, 3, 3, 'Emergency handling', 0),
(24, 0, 5, 3, 3, 'Classifications of airspace', 0),
(25, 0, 5, 3, 3, 'Aircraft performance', 0),
(26, 0, 6, 3, 3, 'Standards of Separation', 0),
(27, 0, 6, 3, 3, 'Technical separation around the aerodrome.', 0),
(28, 0, 6, 3, 3, 'Applying technical separation', 0),
(29, 0, 6, 3, 3, 'Applying procedural separation', 0),
(30, 0, 6, 3, 3, 'Recovering from losses of separation', 0),
(31, 0, 3, 6, 4, 'Meteorology', 0),
(32, 0, 4, 6, 4, 'Radar Terminology', 0),
(33, 0, 4, 6, 4, 'Emergency Handling', 0),
(34, 0, 5, 6, 4, 'Classifications of Airspace', 0),
(35, 0, 5, 6, 4, 'Aircraft Performance', 0),
(36, 0, 6, 6, 4, 'Separation', 0),
(37, 0, 8, 6, 4, 'Approach and Area Control Skills', 0),
(38, 0, 9, 6, 4, 'Forward Planning', 0),
(39, 0, 3, 2, 0, 'Understanding wind', 0),
(40, 0, 3, 2, 0, 'Basic Altimetry', 0),
(41, 0, 3, 4, 0, 'Understanding wind', 1),
(42, 0, 3, 4, 0, 'Understanding wind', 0),
(43, 0, 3, 4, 0, 'Basic Altimetry', 0),
(44, 0, 4, 4, 0, 'Aerodrome Services', 0),
(45, 0, 4, 4, 0, 'Emergency Handling', 0),
(46, 0, 5, 4, 0, 'Aircraft Performance', 0),
(47, 0, 5, 4, 0, 'Classifications of Airspace', 0),
(48, 0, 6, 4, 0, 'Applying technical separation', 0),
(49, 0, 6, 4, 0, 'Recovering from loss of separation', 0),
(50, 0, 6, 4, 0, 'Technical Separation around the aerodrome', 0),
(51, 0, 6, 4, 0, 'Applying procedural separation', 0),
(52, 0, 6, 4, 0, 'Standards of separation', 0),
(53, 1, 1, 7, 0, 'Separation Tools', 0),
(54, 0, 3, 7, 0, 'Meteorology', 0),
(55, 0, 4, 7, 0, 'Emergency Handling', 0),
(56, 0, 4, 7, 0, 'Radar Terminology', 0),
(57, 0, 5, 7, 0, 'Aircraft Performance', 0),
(58, 0, 5, 7, 0, 'Classifications of Airspace', 0),
(59, 0, 6, 7, 0, 'Separation', 0),
(60, 0, 8, 7, 0, 'Approach and Area Control Skills', 0),
(61, 0, 9, 7, 0, 'Forward Planning', 0),
(62, 0, 9, 9, 0, 'Displays situational awareness', 0),
(63, 0, 4, 9, 0, 'Manages communication priority', 0),
(64, 0, 4, 9, 0, 'Uses correct phraseology', 0),
(65, 0, 9, 9, 0, 'Always keeps the "big picture"', 0),
(66, 0, 7, 9, 0, 'Manages Tags and Flight Plans', 0),
(67, 0, 9, 9, 0, 'Coordinates with other ATC when required', 0),
(68, 0, 7, 9, 0, 'Applies correct DEL and GND clearances', 0),
(69, 0, 7, 9, 0, 'Applies correct TWR clearances', 0),
(70, 0, 8, 9, 0, 'Correctly identifies departing aircraft', 0),
(71, 1, 3, 3, 0, 'Providing an ATIS', 0);

-- --------------------------------------------------------

--
-- Table structure for table `report_types`
--

CREATE TABLE IF NOT EXISTS `report_types` (
`id` int(10) unsigned NOT NULL,
  `program_id` int(5) NOT NULL,
  `session_type` int(5) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `report_types`
--

INSERT INTO `report_types` (`id`, `program_id`, `session_type`, `deleted`) VALUES
(1, 2, 1, 0),
(2, 2, 2, 0),
(3, 3, 1, 0),
(4, 3, 2, 0),
(5, 3, 3, 0),
(6, 4, 1, 0),
(7, 4, 2, 0),
(8, 4, 3, 0),
(9, 5, 1, 0),
(10, 5, 2, 0),
(11, 5, 3, 0);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE IF NOT EXISTS `sessions` (
`id` int(11) NOT NULL,
  `student` int(11) NOT NULL,
  `mentor` int(11) NOT NULL,
  `report_id` int(11) DEFAULT NULL,
  `position_id` int(11) NOT NULL,
  `report_type` int(11) NOT NULL,
  `start` datetime NOT NULL,
  `finish` datetime NOT NULL,
  `comment` text,
  `deleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE IF NOT EXISTS `students` (
`id` int(7) NOT NULL,
  `cid` int(9) NOT NULL,
  `program` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `terms_agreed`
--

CREATE TABLE IF NOT EXISTS `terms_agreed` (
`id` int(11) NOT NULL,
  `term_id` int(11) NOT NULL,
  `cid` int(11) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `terms_and_conditions`
--

CREATE TABLE IF NOT EXISTS `terms_and_conditions` (
`id` int(11) NOT NULL,
  `type` smallint(6) NOT NULL COMMENT '0 = website/1=forum',
  `name` varchar(100) NOT NULL,
  `text` text NOT NULL,
  `date` datetime NOT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `terms_and_conditions`
--

INSERT INTO `terms_and_conditions` (`id`, `type`, `name`, `text`, `date`, `deleted`) VALUES
(1, 1, 'Membership', 'Your membership to this forum is concurrent to your membership to the VATSIM network. Only members of the VATSIM network will be entitled to access this forum. Similarly, suspensions of membership to the VATSIM network will result in suspension to this forum.\r\nIn addition to any suspension levied by the VATSIM network, membership to this forum may be revoked at the discretion of the vACC Director. There will be no entitlement to appeal.', '2015-07-13 00:18:51', 0),
(2, 1, 'Conduct', 'By accepting these terms and conditions, you agree that your conduct within these fora will be measured against the VATSIM Code of Conduct and Code of Regulations. \r\nModeration on these for a will be performed by the vACC director and nominated deputies. Any conduct deemed to be inappropriate for this forum will be challenged by the moderating team. Significant misconduct deemed to breach the CoC or CoR will be referred to a network supervisor for action. By agreeing to these terms and conditions, you acknowledge the application of the CoC and CoR to these fora and agree to conduct yourself in an appropriate manner.', '2015-07-13 00:18:51', 0),
(3, 1, 'Moderation', 'Any content produced by members of these fora is subject to moderation by the vACC Director or nominated deputies.\r\nAny action taken by a member of the moderating team is subject to appeal. Any appeal should be sent to the vACC director by e-mail, within 48 hours of the action being performed.\r\nAction taken by a network supervisor may be subject to appeal. Any appeal or queries should be sent to the Divisional Conflict Resolution Manager. The vACC will play no part in this appeals process.', '2015-07-13 00:18:51', 0);

-- --------------------------------------------------------

--
-- Table structure for table `validation_list`
--

CREATE TABLE IF NOT EXISTS `validation_list` (
`id` int(11) NOT NULL,
  `position_list_id` int(5) NOT NULL,
  `cid` int(9) NOT NULL,
  `issued_by` int(9) NOT NULL,
  `valid_from` date NOT NULL,
  `valid_until` date NOT NULL,
  `approved` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `vateir_status`
--

CREATE TABLE IF NOT EXISTS `vateir_status` (
  `id` int(11) NOT NULL,
  `status` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `vateir_status`
--

INSERT INTO `vateir_status` (`id`, `status`) VALUES
(1, 'Home'),
(2, 'Visiting'),
(3, 'Visiting Requested'),
(4, 'Transfer Requested');

-- --------------------------------------------------------

--
-- Table structure for table `visitingCIDs`
--

CREATE TABLE IF NOT EXISTS `visitingCIDs` (
  `cid` int(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `airport_list`
--
ALTER TABLE `airport_list`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `airport_validation`
--
ALTER TABLE `airport_validation`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `allowed`
--
ALTER TABLE `allowed`
 ADD UNIQUE KEY `cid` (`cid`);

--
-- Indexes for table `availability`
--
ALTER TABLE `availability`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `calendar`
--
ALTER TABLE `calendar`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `hash` (`hash`);

--
-- Indexes for table `cards`
--
ALTER TABLE `cards`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `card_types`
--
ALTER TABLE `card_types`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `config`
--
ALTER TABLE `config`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `controllers`
--
ALTER TABLE `controllers`
 ADD UNIQUE KEY `cid` (`id`);

--
-- Indexes for table `crons`
--
ALTER TABLE `crons`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `download_categories`
--
ALTER TABLE `download_categories`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `download_files`
--
ALTER TABLE `download_files`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `download_sub_categories`
--
ALTER TABLE `download_sub_categories`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_unsubscribe`
--
ALTER TABLE `email_unsubscribe`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `infocards`
--
ALTER TABLE `infocards`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `logins`
--
ALTER TABLE `logins`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notes`
--
ALTER TABLE `notes`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications_comments`
--
ALTER TABLE `notifications_comments`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notification_groups`
--
ALTER TABLE `notification_groups`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notification_types`
--
ALTER TABLE `notification_types`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permissionsAdmin`
--
ALTER TABLE `permissionsAdmin`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `position_list`
--
ALTER TABLE `position_list`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `position_type`
--
ALTER TABLE `position_type`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `programs`
--
ALTER TABLE `programs`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `report_programs_sectors`
--
ALTER TABLE `report_programs_sectors`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `report_slider_answers`
--
ALTER TABLE `report_slider_answers`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `report_slider_categories`
--
ALTER TABLE `report_slider_categories`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `report_slider_questions`
--
ALTER TABLE `report_slider_questions`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `report_types`
--
ALTER TABLE `report_types`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `terms_agreed`
--
ALTER TABLE `terms_agreed`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `terms_and_conditions`
--
ALTER TABLE `terms_and_conditions`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `validation_list`
--
ALTER TABLE `validation_list`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `visitingCIDs`
--
ALTER TABLE `visitingCIDs`
 ADD UNIQUE KEY `cid` (`cid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `airport_list`
--
ALTER TABLE `airport_list`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `airport_validation`
--
ALTER TABLE `airport_validation`
MODIFY `id` int(2) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `availability`
--
ALTER TABLE `availability`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `calendar`
--
ALTER TABLE `calendar`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `cards`
--
ALTER TABLE `cards`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `card_types`
--
ALTER TABLE `card_types`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `config`
--
ALTER TABLE `config`
MODIFY `id` int(3) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `crons`
--
ALTER TABLE `crons`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `download_categories`
--
ALTER TABLE `download_categories`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `download_files`
--
ALTER TABLE `download_files`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `download_sub_categories`
--
ALTER TABLE `download_sub_categories`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `email_unsubscribe`
--
ALTER TABLE `email_unsubscribe`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `infocards`
--
ALTER TABLE `infocards`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `logins`
--
ALTER TABLE `logins`
MODIFY `id` int(9) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `notes`
--
ALTER TABLE `notes`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `notifications_comments`
--
ALTER TABLE `notifications_comments`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `notification_groups`
--
ALTER TABLE `notification_groups`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `notification_types`
--
ALTER TABLE `notification_types`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `position_list`
--
ALTER TABLE `position_list`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=31;
--
-- AUTO_INCREMENT for table `position_type`
--
ALTER TABLE `position_type`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `programs`
--
ALTER TABLE `programs`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `report_programs_sectors`
--
ALTER TABLE `report_programs_sectors`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `report_slider_answers`
--
ALTER TABLE `report_slider_answers`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `report_slider_categories`
--
ALTER TABLE `report_slider_categories`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `report_slider_questions`
--
ALTER TABLE `report_slider_questions`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=72;
--
-- AUTO_INCREMENT for table `report_types`
--
ALTER TABLE `report_types`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
MODIFY `id` int(7) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `terms_agreed`
--
ALTER TABLE `terms_agreed`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `terms_and_conditions`
--
ALTER TABLE `terms_and_conditions`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `validation_list`
--
ALTER TABLE `validation_list`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
