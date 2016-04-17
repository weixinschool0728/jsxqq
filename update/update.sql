alter table `tp_member_card_use_record` change    `expense`  `expense` varchar(50) NOT NULL DEFAULT '0';
alter table `tp_member_card_set` add     `is_jfdhhb` int(11) NOT NULL DEFAULT '1';
CREATE TABLE IF NOT EXISTS `tp_member_card_jfdhhb` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `cardid` int(11) NOT NULL DEFAULT '0',
  `integral` int(11) NOT NULL DEFAULT '0',
  `hongbao` int(11) NOT NULL DEFAULT '0',
  `display` int(2) unsigned NOT NULL DEFAULT '1',
  `addtime` int(11) NOT NULL DEFAULT '0',
  `starttime` int(11) NOT NULL DEFAULT '0',
  `endtime` int(11) NOT NULL DEFAULT '0',
  `count` int(11) NOT NULL DEFAULT '0',
  `send_name` varchar(200) NOT NULL,
  `wishing` varchar(200) NOT NULL,
  `act_name` varchar(200) NOT NULL,
  `remark` varchar(300) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `token` (`token`),
  KEY `hongbao` (`hongbao`),
  KEY `integral` (`integral`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;

CREATE TABLE IF NOT EXISTS `tp_member_card_jfdhhb_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `wecha_id` varchar(100) NOT NULL,
  `cardid` int(11) NOT NULL DEFAULT '0',
  `jid` int(11) NOT NULL DEFAULT '0',
  `integral` int(11) NOT NULL DEFAULT '0',
  `hongbao` int(11) NOT NULL DEFAULT '0',
  `wechaname` varchar(100) DEFAULT NULL,
  `tel` varchar(50) DEFAULT NULL,
  `addtime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `token` (`token`),
  KEY `wechaname` (`wechaname`),
  KEY `integral` (`integral`),
  KEY `hongbao` (`hongbao`),
  KEY `tel` (`tel`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;