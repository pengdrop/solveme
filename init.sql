SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+09:00";

CREATE TABLE IF NOT EXISTS `authlog` (
  `no` int(4) NOT NULL AUTO_INCREMENT,
  `problem_no` int(4) NOT NULL,
  `username` varchar(20) NOT NULL,
  `auth_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`no`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `problem` (
  `no` int(4) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `contents` text NOT NULL,
  `score` int(4) NOT NULL,
  `flag` binary(64) NOT NULL,
  `author` varchar(20) NOT NULL,
  `register_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`no`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `user` (
  `no` int(4) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `email` varchar(320) NOT NULL,
  `password` binary(64) NOT NULL,
  `score` int(4) NOT NULL,
  `join_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`no`),
  UNIQUE KEY `username` (`username`,`email`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;