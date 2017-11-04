CREATE TABLE `solveme_authlog` (
  `no` int(4) NOT NULL,
  `problem_no` int(4) NOT NULL,
  `username` varchar(20) NOT NULL,
  `auth_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `solveme_problem` (
  `no` int(4) NOT NULL,
  `title` varchar(50) NOT NULL,
  `contents` text NOT NULL,
  `score` int(4) NOT NULL,
  `flag` binary(64) NOT NULL,
  `author` varchar(20) NOT NULL,
  `register_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `solveme_user` (
  `no` int(4) NOT NULL,
  `username` varchar(20) NOT NULL,
  `email` varchar(320) NOT NULL,
  `password` binary(64) NOT NULL,
  `comment` varchar(30) NOT NULL,
  `score` int(4) NOT NULL,
  `join_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

ALTER TABLE `solveme_authlog`
  ADD PRIMARY KEY (`no`);

ALTER TABLE `solveme_problem`
  ADD PRIMARY KEY (`no`);

ALTER TABLE `solveme_user`
  ADD PRIMARY KEY (`no`),
  ADD UNIQUE KEY `username` (`username`,`email`);
