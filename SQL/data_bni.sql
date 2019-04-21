-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 21, 2019 at 04:22 AM
-- Server version: 5.7.24-0ubuntu0.16.04.1
-- PHP Version: 7.0.32-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `data_bni`
--

-- --------------------------------------------------------

--
-- Table structure for table `data_calendar`
--

CREATE TABLE `data_calendar` (
  `eventid` bigint(20) NOT NULL,
  `date` varchar(50) NOT NULL,
  `text` varchar(5000) NOT NULL,
  `notified` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `data_contractitems`
--

CREATE TABLE `data_contractitems` (
  `recordid` bigint(15) NOT NULL,
  `contractid` bigint(15) NOT NULL,
  `typeid` bigint(15) NOT NULL,
  `quantity` bigint(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `data_contracts`
--

CREATE TABLE `data_contracts` (
  `contractid` bigint(15) NOT NULL,
  `issuerid` bigint(15) NOT NULL,
  `dateissued` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `data_extractions`
--

CREATE TABLE `data_extractions` (
  `extractionid` varchar(50) NOT NULL,
  `chunkarrivaltime` varchar(50) NOT NULL,
  `extractionstarttime` varchar(50) NOT NULL,
  `moonid` bigint(15) NOT NULL,
  `naturaldecaytime` varchar(50) NOT NULL,
  `structureid` bigint(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `data_login`
--

CREATE TABLE `data_login` (
  `clientid` varchar(32) NOT NULL,
  `description` varchar(50) NOT NULL,
  `clientsecret` varchar(40) NOT NULL,
  `refreshtoken` varchar(500) NOT NULL,
  `lastupdate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `data_members`
--

CREATE TABLE `data_members` (
  `characterid` bigint(15) NOT NULL,
  `charactername` varchar(50) NOT NULL,
  `mainid` bigint(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `data_moons`
--

CREATE TABLE `data_moons` (
  `moonid` bigint(15) NOT NULL,
  `moonname` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `data_notifs`
--

CREATE TABLE `data_notifs` (
  `notificationid` bigint(20) NOT NULL,
  `senderid` bigint(20) NOT NULL,
  `sendertype` varchar(100) NOT NULL,
  `text` varchar(5000) NOT NULL,
  `timestamp` varchar(50) NOT NULL,
  `type` varchar(100) NOT NULL,
  `notified` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `data_observeritems`
--

CREATE TABLE `data_observeritems` (
  `observeritemsid` varchar(100) NOT NULL,
  `observerid` bigint(15) NOT NULL,
  `characterid` bigint(15) NOT NULL,
  `lastupdated` varchar(50) NOT NULL,
  `quantity` bigint(15) NOT NULL,
  `recordedcorporationid` bigint(15) NOT NULL,
  `typeid` bigint(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `data_observers`
--

CREATE TABLE `data_observers` (
  `observerid` bigint(15) NOT NULL,
  `lastupdated` varchar(50) NOT NULL,
  `observertype` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `data_orders`
--

CREATE TABLE `data_orders` (
  `orderid` bigint(15) NOT NULL,
  `locationid` bigint(15) NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `typeid` bigint(15) NOT NULL,
  `volumeremain` bigint(15) NOT NULL,
  `volumetotal` bigint(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `data_structures`
--

CREATE TABLE `data_structures` (
  `structureid` bigint(15) NOT NULL,
  `structurename` varchar(100) NOT NULL,
  `fuelexpires` varchar(50) NOT NULL,
  `systemid` bigint(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `data_systems`
--

CREATE TABLE `data_systems` (
  `systemid` bigint(15) NOT NULL,
  `systemname` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `data_types`
--

CREATE TABLE `data_types` (
  `typeid` bigint(15) NOT NULL,
  `typename` varchar(100) NOT NULL,
  `volume` decimal(15,3) NOT NULL DEFAULT '0.000',
  `Tritanium` decimal(15,3) NOT NULL DEFAULT '0.000',
  `Pyerite` decimal(15,3) NOT NULL DEFAULT '0.000',
  `Mexallon` decimal(15,3) NOT NULL DEFAULT '0.000',
  `Isogen` decimal(15,3) NOT NULL DEFAULT '0.000',
  `Nocxium` decimal(15,3) NOT NULL DEFAULT '0.000',
  `Zydrine` decimal(15,3) NOT NULL DEFAULT '0.000',
  `Megacyte` decimal(15,3) NOT NULL DEFAULT '0.000',
  `Atmospheric_Gases` decimal(15,3) NOT NULL DEFAULT '0.000',
  `Evaporite_Deposits` decimal(15,3) NOT NULL DEFAULT '0.000',
  `Hydrocarbons` decimal(15,3) NOT NULL DEFAULT '0.000',
  `Silicates` decimal(15,3) NOT NULL DEFAULT '0.000',
  `Category` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `data_walletjournal`
--

CREATE TABLE `data_walletjournal` (
  `division` int(11) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `balance` decimal(15,2) NOT NULL,
  `date` date NOT NULL,
  `description` varchar(5000) NOT NULL,
  `firstpartyid` bigint(20) NOT NULL,
  `walletjournalid` bigint(20) NOT NULL,
  `reftype` varchar(100) NOT NULL,
  `secondpartyid` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `data_calendar`
--
ALTER TABLE `data_calendar`
  ADD PRIMARY KEY (`eventid`),
  ADD UNIQUE KEY `eventid` (`eventid`);

--
-- Indexes for table `data_contractitems`
--
ALTER TABLE `data_contractitems`
  ADD PRIMARY KEY (`recordid`),
  ADD UNIQUE KEY `recordid` (`recordid`);

--
-- Indexes for table `data_contracts`
--
ALTER TABLE `data_contracts`
  ADD PRIMARY KEY (`contractid`),
  ADD UNIQUE KEY `contractid` (`contractid`);

--
-- Indexes for table `data_extractions`
--
ALTER TABLE `data_extractions`
  ADD PRIMARY KEY (`extractionid`),
  ADD UNIQUE KEY `extractionid` (`extractionid`);

--
-- Indexes for table `data_login`
--
ALTER TABLE `data_login`
  ADD UNIQUE KEY `clientid` (`clientid`);

--
-- Indexes for table `data_members`
--
ALTER TABLE `data_members`
  ADD PRIMARY KEY (`characterid`),
  ADD UNIQUE KEY `characterid` (`characterid`);

--
-- Indexes for table `data_moons`
--
ALTER TABLE `data_moons`
  ADD PRIMARY KEY (`moonid`),
  ADD UNIQUE KEY `moonid` (`moonid`);

--
-- Indexes for table `data_notifs`
--
ALTER TABLE `data_notifs`
  ADD PRIMARY KEY (`notificationid`),
  ADD KEY `notificationid` (`notificationid`);

--
-- Indexes for table `data_observeritems`
--
ALTER TABLE `data_observeritems`
  ADD PRIMARY KEY (`observeritemsid`),
  ADD UNIQUE KEY `observeritemsid` (`observeritemsid`),
  ADD KEY `characterid` (`characterid`),
  ADD KEY `observerid` (`observerid`);

--
-- Indexes for table `data_observers`
--
ALTER TABLE `data_observers`
  ADD PRIMARY KEY (`observerid`),
  ADD UNIQUE KEY `observerid` (`observerid`);

--
-- Indexes for table `data_orders`
--
ALTER TABLE `data_orders`
  ADD PRIMARY KEY (`orderid`),
  ADD UNIQUE KEY `orderid` (`orderid`);

--
-- Indexes for table `data_structures`
--
ALTER TABLE `data_structures`
  ADD PRIMARY KEY (`structureid`),
  ADD UNIQUE KEY `structureid` (`structureid`);

--
-- Indexes for table `data_systems`
--
ALTER TABLE `data_systems`
  ADD PRIMARY KEY (`systemid`),
  ADD UNIQUE KEY `systemid` (`systemid`);

--
-- Indexes for table `data_types`
--
ALTER TABLE `data_types`
  ADD PRIMARY KEY (`typeid`),
  ADD UNIQUE KEY `typeid` (`typeid`);

--
-- Indexes for table `data_walletjournal`
--
ALTER TABLE `data_walletjournal`
  ADD PRIMARY KEY (`walletjournalid`),
  ADD UNIQUE KEY `walletjournalid` (`walletjournalid`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
