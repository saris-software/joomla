CREATE TABLE IF NOT EXISTS `#__jefaqpro_faq` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `questions` longtext NOT NULL,
  `answers` longtext NOT NULL,
  `catid` int(11) NOT NULL,
  `published` tinyint(3) NOT NULL,
  `access` tinyint(3) NOT NULL,
  `ordering` int(11) NOT NULL,
  `language` char(7) NOT NULL,
  `uid` int(11) NOT NULL,
  `posted_by` varchar(255) NOT NULL,
  `posted_date` datetime NOT NULL,
  `posted_email` varchar(255) NOT NULL,
  `modified_by` varchar(255) NOT NULL,
  `modified_date` datetime NOT NULL,
  `hits` int(11) NOT NULL,
  `email_status` tinyint(3) NOT NULL,
  `checked_out` int(10) NOT NULL,
  `checked_out_time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Used to Store faqs';

CREATE TABLE IF NOT EXISTS `#__jefaqpro_responses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `faqid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `response_yes` int(11) NOT NULL,
  `response_no` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Used to Store Responses from the Users';

CREATE TABLE IF NOT EXISTS `#__jefaqpro_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `theme` int(11) NOT NULL,
  `date_format` varchar(200) NOT NULL,
  `orderby` varchar(100) NOT NULL,
  `sortby` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Used to Store Settings for JE FAQPro';

CREATE TABLE IF NOT EXISTS `#__jefaqpro_themes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `themes` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Used to Store themes';

CREATE TABLE IF NOT EXISTS `#__jefaqpro_tempcat` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `oldcatid` int(10) NOT NULL,
  `newcatid` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Used to Store category temporary for Joomla V1.5 FAQs Import';

CREATE TABLE IF NOT EXISTS `#__jefaqpro_tempfaq` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `oldfaqid` int(10) NOT NULL,
  `newfaqid` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Used to Store FAQ temporary for Joomla V1.5 FAQs Import';