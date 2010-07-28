
CREATE TABLE IF NOT EXISTS `#__jlvotes_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content_id` int(11) NOT NULL,
  `user` varchar(32) NOT NULL,
  `votetype` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user` (`user`,`content_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;


CREATE TABLE IF NOT EXISTS `#__jlvotes_settings` (
  `name` varchar(50) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT IGNORE INTO `#__jlvotes_settings` (`name`, `value`) VALUES
('add2all', '0'),
('allow_guest', '1'),
('allow_revote', '1');
