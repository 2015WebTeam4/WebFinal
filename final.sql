-- phpMyAdmin SQL Dump
-- version 2.10.3
-- http://www.phpmyadmin.net
-- 
-- 主機: localhost
-- 建立日期: Jun 02, 2015, 02:21 PM
-- 伺服器版本: 5.0.51
-- PHP 版本: 5.2.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- 
-- 資料庫: `final`
-- 

-- --------------------------------------------------------

-- 
-- 資料表格式： `lyrics`
-- 

CREATE TABLE `lyrics` (
  `songid` varchar(32) NOT NULL,
  `title` varchar(32) NOT NULL,
  `content` varchar(640) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- 列出以下資料庫的數據： `lyrics`
-- 


-- --------------------------------------------------------

-- 
-- 資料表格式： `songinfo`
-- 

CREATE TABLE `songinfo` (
  `songid` varchar(16) NOT NULL,
  `userid` varchar(16) NOT NULL,
  `coun` int(32) unsigned NOT NULL default '0',
  `addtof` varchar(16) NOT NULL default 'N'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- 列出以下資料庫的數據： `songinfo`
-- 

