CREATE TABLE IF NOT EXISTS `#__rseventspro_cards` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ids` int(11) NOT NULL DEFAULT '0',
  `card_number` text NOT NULL,
  `card_csc` text NOT NULL,
  `card_exp` varchar(10) NOT NULL DEFAULT '',
  `card_fname` varchar(255) NOT NULL DEFAULT '',
  `card_lname` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__rseventspro_config` (
  `name` varchar(255) NOT NULL DEFAULT '',
  `value` text NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__rseventspro_confirmed` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ids` int(11) NOT NULL DEFAULT '0',
  `code` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `ids` (`ids`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__rseventspro_countries` (
  `name` varchar(255) NOT NULL DEFAULT '',
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__rseventspro_coupons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ide` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `from` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `to` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `usage` int(10) NOT NULL DEFAULT '0',
  `discount` float NOT NULL DEFAULT '0',
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `action` tinyint(1) NOT NULL DEFAULT '0',
  `groups` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__rseventspro_coupon_codes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idc` int(11) NOT NULL DEFAULT '0',
  `code` varchar(255) NOT NULL DEFAULT '',
  `used` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idc` (`idc`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__rseventspro_discounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `code` varchar(255) NOT NULL DEFAULT '',
  `from` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `to` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `usage` int(11) NOT NULL DEFAULT '0',
  `used` int(11) NOT NULL DEFAULT '0',
  `type` int(11) NOT NULL DEFAULT '0',
  `value` float NOT NULL DEFAULT '0',
  `apply_to` tinyint(2) NOT NULL DEFAULT '0',
  `events` text NOT NULL,
  `groups` text NOT NULL,
  `discounttype` tinyint(2) NOT NULL DEFAULT '0',
  `same_tickets` int(11) NOT NULL DEFAULT '0',
  `different_tickets` int(11) NOT NULL DEFAULT '0',
  `cart_tickets` int(3) NOT NULL DEFAULT '0',
  `total` tinyint(2) NOT NULL DEFAULT '0',
  `totalvalue` float NOT NULL DEFAULT '0',
  `payment` tinyint(2) NOT NULL DEFAULT '0',
  `paymentvalue` varchar(255) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__rseventspro_emails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lang` varchar(20) NOT NULL DEFAULT '',
  `type` varchar(100) NOT NULL DEFAULT '',
  `enable` tinyint(1) NOT NULL DEFAULT '0',
  `mode` tinyint(1) NOT NULL DEFAULT '0',
  `parent` int(11) NOT NULL DEFAULT '0',
  `subject` varchar(255) NOT NULL DEFAULT '',
  `message` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__rseventspro_events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `start` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `end` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `description` text NOT NULL,
  `small_description` text NOT NULL,
  `location` int(2) NOT NULL DEFAULT '0',
  `owner` int(11) NOT NULL DEFAULT '0',
  `URL` varchar(500) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `phone` varchar(255) NOT NULL DEFAULT '',
  `metaname` varchar(255) NOT NULL DEFAULT '',
  `metakeywords` varchar(500) NOT NULL DEFAULT '',
  `metadescription` varchar(200) NOT NULL DEFAULT '',
  `recurring` tinyint(1) NOT NULL DEFAULT '0',
  `registration` tinyint(1) NOT NULL DEFAULT '0',
  `comments` tinyint(1) NOT NULL DEFAULT '0',
  `icon` varchar(255) NOT NULL DEFAULT '',
  `repeat_interval` int(3) NOT NULL DEFAULT '0',
  `repeat_type` tinyint(1) NOT NULL DEFAULT '0',
  `repeat_end` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `repeat_also` text NOT NULL,
  `repeat_on_type` tinyint(1) NOT NULL DEFAULT '0',
  `repeat_on_day` tinyint(2) NOT NULL DEFAULT '0',
  `repeat_on_day_order` tinyint(1) NOT NULL DEFAULT '0',
  `repeat_on_day_type` varchar(25) NOT NULL DEFAULT '',
  `exclude_dates` text NOT NULL,
  `start_registration` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `end_registration` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `unsubscribe_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `payments` text NOT NULL,
  `max_tickets` tinyint(1) NOT NULL DEFAULT '0',
  `max_tickets_amount` int(11) NOT NULL DEFAULT '0',
  `notify_me` tinyint(1) NOT NULL DEFAULT '0',
  `notify_me_unsubscribe` tinyint(1) NOT NULL DEFAULT '0',
  `overbooking` tinyint(1) NOT NULL DEFAULT '0',
  `overbooking_amount` int(11) NOT NULL DEFAULT '0',
  `show_registered` tinyint(1) NOT NULL DEFAULT '0',
  `automatically_approve` tinyint(1) NOT NULL DEFAULT '0',
  `paypal_email` varchar(255) NOT NULL DEFAULT '',
  `discounts` tinyint(1) NOT NULL DEFAULT '0',
  `form` int(11) NOT NULL DEFAULT '0',
  `early_fee` float NOT NULL DEFAULT '0',
  `early_fee_type` tinyint(1) NOT NULL DEFAULT '0',
  `early_fee_end` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `late_fee` float NOT NULL DEFAULT '0',
  `late_fee_type` tinyint(1) NOT NULL DEFAULT '0',
  `late_fee_start` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `options` text NOT NULL,
  `archived` tinyint(1) NOT NULL DEFAULT '0',
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `completed` tinyint(1) NOT NULL DEFAULT '0',
  `approved` tinyint(1) NOT NULL DEFAULT '0',
  `ticket_pdf` tinyint(1) NOT NULL DEFAULT '0',
  `ticket_pdf_layout` text NOT NULL,
  `properties` varchar(255) NOT NULL DEFAULT '',
  `gallery_tags` text NOT NULL,
  `sync` tinyint(1) NOT NULL DEFAULT '0',
  `sid` varchar(255) NOT NULL DEFAULT '',
  `allday` tinyint(1) NOT NULL DEFAULT '0',
  `ticketsconfig` tinyint(1) NOT NULL DEFAULT '0',
  `featured` tinyint(1) NOT NULL DEFAULT '0',
  `hits` int(11) NOT NULL DEFAULT '0',
  `timezone` varchar(255) NOT NULL DEFAULT '',
  `aspectratio` tinyint(1) NOT NULL DEFAULT '0',
  `itemid` int(11) NOT NULL DEFAULT '0',
  `rsvp` tinyint(2) NOT NULL DEFAULT '0',
  `rsvp_quota` int(11) NOT NULL DEFAULT '0',
  `rsvp_guests` tinyint(2) NOT NULL DEFAULT '0',
  `rsvp_start` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `rsvp_end` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `rsvp_going` tinyint(2) NOT NULL DEFAULT '0',
  `rsvp_interested` tinyint(2) NOT NULL DEFAULT '0',
  `rsvp_notgoing` tinyint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `location` (`location`),
  KEY `owner` (`owner`),
  KEY `completed` (`completed`),
  KEY `published` (`published`),
  KEY `published_2` (`published`, `completed`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__rseventspro_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ide` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `location` varchar(255) NOT NULL DEFAULT '',
  `permissions` varchar(6) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__rseventspro_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `jgroups` text NOT NULL,
  `jusers` text NOT NULL,
  `can_add_locations` tinyint(1) NOT NULL DEFAULT '0',
  `can_create_categories` tinyint(1) NOT NULL DEFAULT '0',
  `can_delete_events` tinyint(1) NOT NULL DEFAULT '0',
  `can_download` tinyint(1) NOT NULL DEFAULT '0',
  `can_edit_events` tinyint(1) NOT NULL DEFAULT '0',
  `can_edit_locations` tinyint(1) NOT NULL DEFAULT '0',
  `can_post_events` tinyint(1) NOT NULL DEFAULT '0',
  `can_register` tinyint(1) NOT NULL DEFAULT '0',
  `can_repeat_events` tinyint(1) NOT NULL DEFAULT '0',
  `can_unsubscribe` tinyint(1) NOT NULL DEFAULT '0',
  `can_upload` tinyint(1) NOT NULL DEFAULT '0',
  `event_moderation` tinyint(1) NOT NULL DEFAULT '0',
  `tag_moderation` tinyint(1) NOT NULL DEFAULT '0',
  `can_approve_events` int(11) NOT NULL DEFAULT '0',
  `can_approve_tags` int(11) NOT NULL DEFAULT '0',
  `can_change_options` tinyint(1) NOT NULL DEFAULT '0',
  `event` text NOT NULL,
  `restricted_categories` text NOT NULL,
  `can_select_speakers` tinyint(2) NOT NULL DEFAULT '1',
  `can_add_speaker` tinyint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__rseventspro_locations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `address` varchar(255) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `coordinates` varchar(255) NOT NULL DEFAULT '',
  `marker` varchar(255) NOT NULL DEFAULT '',
  `gallery_tags` text NOT NULL,
  `ordering` int(11) NOT NULL DEFAULT '0',
  `published` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__rseventspro_payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `details` text NOT NULL,
  `tax_type` tinyint(1) NOT NULL DEFAULT '0',
  `tax_value` float NOT NULL DEFAULT '0',
  `redirect` varchar(550) NOT NULL DEFAULT '',
  `published` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__rseventspro_rating` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ide` int(11) NOT NULL DEFAULT '0',
  `value` int(11) NOT NULL DEFAULT '0',
  `ip` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `ide` (`ide`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__rseventspro_reports` (
  `id` INT NOT NULL AUTO_INCREMENT, 
  `ide` INT NOT NULL DEFAULT '0',
  `idu` INT NOT NULL DEFAULT '0',
  `ip` VARCHAR(15) NOT NULL DEFAULT '',
  `text` TEXT NOT NULL,
  PRIMARY KEY (`id`), 
  INDEX (`ide`, `idu`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__rseventspro_rsvp_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ide` int(11) NOT NULL DEFAULT '0',
  `uid` int(11) NOT NULL DEFAULT '0',
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `rsvp` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `ide` (`ide`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__rseventspro_rules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `payment` varchar(255) NOT NULL DEFAULT '',
  `status` int(11) NOT NULL DEFAULT '0',
  `interval` int(11) NOT NULL DEFAULT '0',
  `rule` int(11) NOT NULL DEFAULT '0',
  `mid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__rseventspro_speakers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `image` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `facebook` varchar(255) NOT NULL DEFAULT '',
  `twitter` varchar(255) NOT NULL DEFAULT '',
  `linkedin` varchar(255) NOT NULL DEFAULT '',
  `phone` varchar(255) NOT NULL DEFAULT '',
  `published` tinyint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__rseventspro_sync` (
  `id` varchar(150) NOT NULL DEFAULT '',
  `ide` int(11) NOT NULL DEFAULT '0',
  `from` varchar(50) NOT NULL DEFAULT '',
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__rseventspro_sync_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(50) NOT NULL DEFAULT '',
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `eid` int(11) NOT NULL DEFAULT '0',
  `importid` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `imported` int(2) NOT NULL DEFAULT '0',
  `message` varchar(255) NOT NULL DEFAULT '',
  `page` varchar(255) NOT NULL DEFAULT '',
  `from` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `eid` (`eid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__rseventspro_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `published` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__rseventspro_taxonomy` (
  `type` varchar(50) NOT NULL DEFAULT '',
  `ide` int(11) NOT NULL DEFAULT '0',
  `id` int(11) NOT NULL DEFAULT '0',
  `extra` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`type`,`ide`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__rseventspro_tickets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ide` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `price` DECIMAL(20, 3) NOT NULL DEFAULT '0',
  `seats` int(10) NOT NULL DEFAULT '0',
  `user_seats` int(10) NOT NULL DEFAULT '0',
  `description` text NOT NULL,
  `position` text NOT NULL,
  `groups` text NOT NULL,
  `attach` tinyint(1) NOT NULL DEFAULT '0',
  `layout` longtext NOT NULL,
  `from` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `to` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `ide` (`ide`),
  KEY `price` (`price`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__rseventspro_tmp` (
  `hash` varchar(32) NOT NULL DEFAULT '',
  `table` varchar(32) NOT NULL DEFAULT '',
  `old` int(11) NOT NULL DEFAULT '0',
  `new` int(11) NOT NULL DEFAULT '0',
  UNIQUE KEY `hash` (`hash`,`table`,`old`),
  KEY `new` (`new`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__rseventspro_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ide` int(11) NOT NULL DEFAULT '0',
  `idu` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `state` tinyint(1) NOT NULL DEFAULT '0',
  `verification` varchar(255) NOT NULL DEFAULT '',
  `SubmissionId` int(11) NOT NULL DEFAULT '0',
  `gateway` varchar(255) NOT NULL DEFAULT '',
  `ip` varchar(20) NOT NULL DEFAULT '',
  `URL` varchar(255) NOT NULL DEFAULT '',
  `params` text NOT NULL,
  `discount` float NOT NULL DEFAULT '0',
  `early_fee` float NOT NULL DEFAULT '0',
  `late_fee` float NOT NULL DEFAULT '0',
  `tax` float NOT NULL DEFAULT '0',
  `log` text NOT NULL,
  `lang` varchar(10) NOT NULL DEFAULT '',
  `coupon` varchar(255) NOT NULL DEFAULT '',
  `ideal` varchar(100) NOT NULL DEFAULT '',
  `confirmed` tinyint(1) NOT NULL DEFAULT '0',
  `create_user` tinyint(1) NOT NULL DEFAULT '0',
  `hash` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `ide` (`ide`,`idu`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__rseventspro_user_info` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `image` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__rseventspro_user_tickets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ids` int(11) NOT NULL DEFAULT '0',
  `idt` int(11) NOT NULL DEFAULT '0',
  `quantity` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `ids` (`ids`,`idt`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__rseventspro_user_seats` (
  `id` INT NOT NULL AUTO_INCREMENT, 
  `ids` INT NOT NULL DEFAULT '0',
  `idt` INT NOT NULL DEFAULT '0',
  `seat` INT NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`), 
  INDEX (`ids`, `idt`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;


INSERT IGNORE INTO `#__rseventspro_emails` (`id`, `lang`, `type`, `enable`, `mode`, `parent`, `subject`, `message`) VALUES(1, 'en-GB', 'registration', 1, 1, 0, 'Registration to {EventName}', '<p>Hello {user},</p>\r\n<p>You have been subscribed to {EventName} that will start on {EventStartDate}.</p>');
INSERT IGNORE INTO `#__rseventspro_emails` (`id`, `lang`, `type`, `enable`, `mode`, `parent`, `subject`, `message`) VALUES(2, 'en-GB', 'activation', 1, 1, 0, 'Activation email for {EventName}', '<p>Hello {user},</p>\r\n<p>Your request for participation to {EventName} has been approved.</p>');
INSERT IGNORE INTO `#__rseventspro_emails` (`id`, `lang`, `type`, `enable`, `mode`, `parent`, `subject`, `message`) VALUES(3, 'en-GB', 'unsubscribe', 1, 1, 0, 'Unsubscribe from {EventName}', '<p>Hello {user},</p>\r\n<p>You have been unsubscribed from {EventName}.</p>');
INSERT IGNORE INTO `#__rseventspro_emails` (`id`, `lang`, `type`, `enable`, `mode`, `parent`, `subject`, `message`) VALUES(4, 'en-GB', 'denied', 1, 1, 0, 'Subscription denied', '<p>Hello {user},</p>\r\n<p>We regret to inform you but your subscription to {EventName} was denied.</p>');
INSERT IGNORE INTO `#__rseventspro_emails` (`id`, `lang`, `type`, `enable`, `mode`, `parent`, `subject`, `message`) VALUES(5, 'en-GB', 'invite', 1, 1, 0, 'Invitation to {EventName}', '<p>Hello {user},</p>\r\n<p>This is an invitation to the event {EventName} that is starting on {EventStartDate}.</p>\r\n<p>{message}</p>');
INSERT IGNORE INTO `#__rseventspro_emails` (`id`, `lang`, `type`, `enable`, `mode`, `parent`, `subject`, `message`) VALUES(6, 'en-GB', 'reminder', 1, 1, 0, 'Reminder for {EventName}', '<p>Hello {user},</p>\r\n<p>This is a reminder for {EventName} stating on {EventStartDate}.</p>');
INSERT IGNORE INTO `#__rseventspro_emails` (`id`, `lang`, `type`, `enable`, `mode`, `parent`, `subject`, `message`) VALUES(7, 'en-GB', 'preminder', 1, 1, 0, 'Event {EventName} has finished.', '<p>Hello {user},</p>\r\n<p>Thank you for your participation on {EventName}.</p>');
INSERT IGNORE INTO `#__rseventspro_emails` (`id`, `lang`, `type`, `enable`, `mode`, `parent`, `subject`, `message`) VALUES(8, 'en-GB', 'moderation', 1, 1, 0, 'Event {EventName} requires moderation.', '<p>A new event requires moderation. <br /> In order to approve it please click this <a href="{EventApprove}">link</a> or you can view it <a href="{EventLink}">here</a>.</p>');
INSERT IGNORE INTO `#__rseventspro_emails` (`id`, `lang`, `type`, `enable`, `mode`, `parent`, `subject`, `message`) VALUES(9, 'en-GB', 'tag_moderation', 1, 1, 0, 'Some tags require moderation.', '<p>The following tags require moderation:</p>\r\n<p>{TagsApprove}</p>\r\n<p>They have been added on the following event: {EventName} by: {OwnerUsername}</p>');
INSERT IGNORE INTO `#__rseventspro_emails` (`id`, `lang`, `type`, `enable`, `mode`, `parent`, `subject`, `message`) VALUES(10, 'en-GB', 'notify_me', 1, 1, 0, 'You have a new subscription for {EventName} from {SubscriberName}!', '<p>Hello {OwnerName},</p>\r\n<p>a new subscription to your event {EventName} has been made.</p>\r\n<p><strong>Subscriber info:</strong></p>\r\n<ul>\r\n<li>Date: {SubscribeDate}</li>\r\n<li>Username: {SubscriberUsername}</li>\r\n<li>Name: {SubscriberName}</li>\r\n<li>Email: {SubscriberEmail}</li>\r\n<li>IP: {SubscriberIP}</li>\r\n</ul>\r\n<p><strong>Payment related info (if available):</strong></p>\r\n<ul>\r\n<li>Gateway: {PaymentGateway}</li>\r\n<li>Tickets: {TicketInfo}</li>\r\n<li>Total: {TicketsTotal}</li>\r\n<li>Discount: {TicketsDiscount}</li>\r\n</ul>');
INSERT IGNORE INTO `#__rseventspro_emails` (`id`, `lang`, `type`, `enable`, `mode`, `parent`, `subject`, `message`) VALUES('', 'en-GB', 'report', 0, 1, 0, 'New report for {EventName}', '<p>Hello,</p>\r\n<p>A new report for <strong>{EventName}</strong> has been added. Here are the details for this report:</p>\r\n<p>User: {ReportUser}</p>\r\n<p>IP: {ReportIP}</p>\r\n<p>Message: {ReportMessage}</p>');
INSERT IGNORE INTO `#__rseventspro_emails` (`id`, `lang`, `type`, `enable`, `mode`, `parent`, `subject`, `message`) VALUES('', 'en-GB', 'approval', 1, 1, 0, 'Your event ''{EventName}'' has been approved.', '<p>Hello {Owner},</p>\r\n<p>Your event {EventName} has been approved by one of our staff members. You can view your event by clicking <a href="{EventLink}">here</a>.</p>');
INSERT IGNORE INTO `#__rseventspro_emails` (`id`, `lang`, `type`, `enable`, `mode`, `parent`, `subject`, `message`) VALUES('', 'en-GB', 'rsvpgoing', 1, 1, 0, 'Going to {EventName}', '<p>Hello {user},</p>\r\n<p>Thank you for your participation to <strong>{EventName}</strong> that will start on {EventStartDate}.</p>');
INSERT IGNORE INTO `#__rseventspro_emails` (`id`, `lang`, `type`, `enable`, `mode`, `parent`, `subject`, `message`) VALUES('', 'en-GB', 'rsvpinterested', 1, 1, 0, 'Interested in going to {EventName}', '<p>Hello {user},</p>\r\n<p>Thank you for your interest in the <strong>{EventName}</strong> event. We hope to see you at this event.</p>\r\n<p>A quick reminder: this event starts on {EventStartDate} and ends on {EventEndDate}.</p>');
INSERT IGNORE INTO `#__rseventspro_emails` (`id`, `lang`, `type`, `enable`, `mode`, `parent`, `subject`, `message`) VALUES('', 'en-GB', 'rsvpnotgoing', 1, 1, 0, 'Not going to {EventName}', '<p>Hello {user},</p>\r\n<p>We are sorry to see that you cannot come to the <strong>{EventName}</strong> event. Hope you will change your mind.</p>');


INSERT IGNORE INTO `#__rseventspro_locations` (`id`, `name`, `url`, `address`, `description`, `coordinates`, `gallery_tags`, `ordering`, `published`) VALUES(1, 'RSEvents!Pro Location', 'http://www.rsjoomla.com', 'Colorado, USA', '<p>This is the location description.</p>', '39.5500507,-105.7820674', '', 0, 1);

INSERT IGNORE INTO `#__rseventspro_payments` (`id`, `name`, `details`, `tax_type`, `tax_value`, `redirect`, `published`) VALUES(1, 'Wire Transfer', '<p><strong>Bank name:</strong> Your bank name</p>\r\n<p><strong>Bank Address:</strong> your bank address</p>\r\n<p><strong>Bank Account Number:</strong></p>\r\n<p><strong>Swift BIC Number:</strong> <strong>Beneficiary:</strong></p>', 0, 0, '', 1);

INSERT IGNORE INTO `#__rseventspro_groups` (`id`, `name`, `jgroups`, `jusers`, `can_add_locations`, `can_create_categories`, `can_delete_events`, `can_download`, `can_edit_events`, `can_edit_locations`, `can_post_events`, `can_register`, `can_repeat_events`, `can_unsubscribe`, `can_upload`, `event_moderation`, `tag_moderation`, `can_approve_events`, `can_approve_tags`) VALUES(1, 'Public', '{"0":"1"}', '', 0, 0, 0, 1, 0, 0, 0, 1, 0, 1, 0, 1, 1, 0, 0);

INSERT IGNORE INTO `#__rseventspro_config` (`name`, `value`) VALUES('global_code', '');
INSERT IGNORE INTO `#__rseventspro_config` (`name`, `value`) VALUES('global_date', 'F d, Y');
INSERT IGNORE INTO `#__rseventspro_config` (`name`, `value`) VALUES('global_time', 'H:i');
INSERT IGNORE INTO `#__rseventspro_config` (`name`, `value`) VALUES('enable_google_maps', '1');
INSERT IGNORE INTO `#__rseventspro_config` (`name`, `value`) VALUES('google_maps_center', '44.4237437,26.0780860');
INSERT IGNORE INTO `#__rseventspro_config` (`name`, `value`) VALUES('time_format', '0');
INSERT IGNORE INTO `#__rseventspro_config` (`name`, `value`) VALUES('enable_buttons', '1');
INSERT IGNORE INTO `#__rseventspro_config` (`name`, `value`) VALUES('descr_length', '255');
INSERT IGNORE INTO `#__rseventspro_config` (`name`, `value`) VALUES('dashboard_upcoming', '1');
INSERT IGNORE INTO `#__rseventspro_config` (`name`, `value`) VALUES('dashboard_upcoming_nr', '5');
INSERT IGNORE INTO `#__rseventspro_config` (`name`, `value`) VALUES('dashboard_subscribers', '1');
INSERT IGNORE INTO `#__rseventspro_config` (`name`, `value`) VALUES('dashboard_subscribers_nr', '5');
INSERT IGNORE INTO `#__rseventspro_config` (`name`, `value`) VALUES('dashboard_comments', '1');
INSERT IGNORE INTO `#__rseventspro_config` (`name`, `value`) VALUES('dashboard_comments_nr', '5');
INSERT IGNORE INTO `#__rseventspro_config` (`name`, `value`) VALUES('dashboard_sync', '1');
INSERT IGNORE INTO `#__rseventspro_config` (`name`, `value`) VALUES('google_map_directions', '1');
INSERT IGNORE INTO `#__rseventspro_config` (`name`, `value`) VALUES('google_map_zoom', '12');
INSERT IGNORE INTO `#__rseventspro_config` (`name`, `value`) VALUES('default_payment', 'none');
INSERT IGNORE INTO `#__rseventspro_config` (`name`, `value`) VALUES('payment_type', '1');
INSERT IGNORE INTO `#__rseventspro_config` (`name`, `value`) VALUES('payment_currency', 'EUR');
INSERT IGNORE INTO `#__rseventspro_config` (`name`, `value`) VALUES('payment_thousands', ',');
INSERT IGNORE INTO `#__rseventspro_config` (`name`, `value`) VALUES('payment_decimal', '.');
INSERT IGNORE INTO `#__rseventspro_config` (`name`, `value`) VALUES('payment_decimals', '2');
INSERT IGNORE INTO `#__rseventspro_config` (`name`, `value`) VALUES('email_from', 'from@yoursite.com');
INSERT IGNORE INTO `#__rseventspro_config` (`name`, `value`) VALUES('email_fromname', 'RSEvents!Pro');
INSERT IGNORE INTO `#__rseventspro_config` (`name`, `value`) VALUES('email_replyto', 'reply@yoursite.com');
INSERT IGNORE INTO `#__rseventspro_config` (`name`, `value`) VALUES('email_replytoname', 'RSEvents!Pro');
INSERT IGNORE INTO `#__rseventspro_config` (`name`, `value`) VALUES('email_cc', '');
INSERT IGNORE INTO `#__rseventspro_config` (`name`, `value`) VALUES('email_bcc', '');
INSERT IGNORE INTO `#__rseventspro_config` (`name`, `value`) VALUES('export_headers', '0');
INSERT IGNORE INTO `#__rseventspro_config` (`name`, `value`) VALUES('auto_postreminder', '0');
INSERT IGNORE INTO `#__rseventspro_config` (`name`, `value`) VALUES('errors', '0');
INSERT IGNORE INTO `#__rseventspro_config` (`name`, `value`) VALUES('event_owner_profile', '0');
INSERT IGNORE INTO `#__rseventspro_config` (`name`, `value`) VALUES('email_invite_message', '1');
INSERT IGNORE INTO `#__rseventspro_config` (`name`, `value`) VALUES('email_reminder_days', '3');
INSERT IGNORE INTO `#__rseventspro_config` (`name`, `value`) VALUES('email_reminder_run', '1');
INSERT IGNORE INTO `#__rseventspro_config` (`name`, `value`) VALUES('google_username', '');
INSERT IGNORE INTO `#__rseventspro_config` (`name`, `value`) VALUES('google_password', '');
INSERT IGNORE INTO `#__rseventspro_config` (`name`, `value`) VALUES('google_location', '0');
INSERT IGNORE INTO `#__rseventspro_config` (`name`, `value`) VALUES('google_category', '8');
INSERT IGNORE INTO `#__rseventspro_config` (`name`, `value`) VALUES('facebook_appid', '340486642645761');
INSERT IGNORE INTO `#__rseventspro_config` (`name`, `value`) VALUES('facebook_secret', 'fea413f9a085e01555de0e93848c2c4a');
INSERT IGNORE INTO `#__rseventspro_config` (`name`, `value`) VALUES('facebook_token', '');
INSERT IGNORE INTO `#__rseventspro_config` (`name`, `value`) VALUES('facebook_category', '8');
INSERT IGNORE INTO `#__rseventspro_config` (`name`, `value`) VALUES('facebook_location', '0');
INSERT IGNORE INTO `#__rseventspro_config` (`name`, `value`) VALUES('incomplete', '0');
INSERT IGNORE INTO `#__rseventspro_config` (`name`, `value`) VALUES('event_moderation_emails', '');
INSERT IGNORE INTO `#__rseventspro_config` (`name`, `value`) VALUES('tags_moderation_emails', '');
INSERT IGNORE INTO `#__rseventspro_config` (`name`, `value`) VALUES('auto_archive', '1');
INSERT IGNORE INTO `#__rseventspro_config` (`name`, `value`) VALUES('archive_days', '5');
INSERT IGNORE INTO `#__rseventspro_config` (`name`, `value`) VALUES('event_comment', '0');
INSERT IGNORE INTO `#__rseventspro_config` (`name`, `value`) VALUES('icon_small_width', '100');
INSERT IGNORE INTO `#__rseventspro_config` (`name`, `value`) VALUES('icon_big_width', '200');
INSERT IGNORE INTO `#__rseventspro_config` (`name`, `value`) VALUES('extensions', 'zip,gif,jpg,txt');
INSERT IGNORE INTO `#__rseventspro_config` (`name`, `value`) VALUES('multi_registration', '1');
INSERT IGNORE INTO `#__rseventspro_config` (`name`, `value`) VALUES('create_user', '1');
INSERT IGNORE INTO `#__rseventspro_config` (`name`, `value`) VALUES('multi_tickets', '0');
INSERT IGNORE INTO `#__rseventspro_config` (`name`, `value`) VALUES('must_login', '0');
INSERT IGNORE INTO `#__rseventspro_config` (`name`, `value`) VALUES('user_display', '0');
INSERT IGNORE INTO `#__rseventspro_config` (`name`, `value`) VALUES('user_avatar', 'gravatar');
INSERT IGNORE INTO `#__rseventspro_config` (`name`, `value`) VALUES('user_profile', '0');
INSERT IGNORE INTO `#__rseventspro_config` (`name`, `value`) VALUES('event_owner', '0');
INSERT IGNORE INTO `#__rseventspro_config` (`name`, `value`) VALUES('payment_currency_sign', '€');
INSERT IGNORE INTO `#__rseventspro_config` (`name`, `value`) VALUES('incomplete_minutes', '5');
INSERT IGNORE INTO `#__rseventspro_config` (`name`, `value`) VALUES('incomplete_minutes_check', '');
INSERT IGNORE INTO `#__rseventspro_config` (`name`, `value`) VALUES('archive_check', '');
INSERT IGNORE INTO `#__rseventspro_config` (`name`, `value`) VALUES('modal', '0');
INSERT IGNORE INTO `#__rseventspro_config` (`name`, `value`) VALUES('payment_paypal', '0');
INSERT IGNORE INTO `#__rseventspro_config` (`name`, `value`) VALUES('jsactivity', '1');
INSERT IGNORE INTO `#__rseventspro_config` (`name`, `value`) VALUES('hideyear', '0');
INSERT IGNORE INTO `#__rseventspro_config` (`name`, `value`) VALUES('postreminder_hash', '2437ec0d9cd9392705cd34c09a3a73c5');
INSERT IGNORE INTO `#__rseventspro_config` (`name`, `value`) VALUES('gallery_params', '{"thumb_resolution":"w,280","full_resolution":"w,600","use_original":"1","ordering":"title","direction":"ASC","limit":"","show_title":"1","show_description":"1"}');
INSERT IGNORE INTO `#__rseventspro_config` (`name`, `value`) VALUES('payment_mask', '%p %c');
INSERT IGNORE INTO `#__rseventspro_config` (`name`, `value`) VALUES('rules_check', '');
INSERT IGNORE INTO `#__rseventspro_config` (`name`, `value`) VALUES ('active_events', '1');
INSERT IGNORE INTO `#__rseventspro_config` (`name`, `value`) VALUES ('reports', '0');
INSERT IGNORE INTO `#__rseventspro_config` (`name`, `value`) VALUES ('reports_guests', '0');
INSERT IGNORE INTO `#__rseventspro_config` (`name`, `value`) VALUES ('report_to', '');
INSERT IGNORE INTO `#__rseventspro_config` (`name`, `value`) VALUES ('report_to_owner', '1');
INSERT IGNORE INTO `#__rseventspro_config` (`name`, `value`) VALUES ('featured', '0');
INSERT IGNORE INTO `#__rseventspro_config` (`name`, `value`) VALUES ('color', '0');
INSERT IGNORE INTO `#__rseventspro_config` (`name` ,`value`) VALUES ('facebook_pages', '');
INSERT IGNORE INTO `#__rseventspro_config` (`name` ,`value`) VALUES ('seats_width', '1280');
INSERT IGNORE INTO `#__rseventspro_config` (`name` ,`value`) VALUES ('seats_height', '800');
INSERT IGNORE INTO `#__rseventspro_config` (`name` ,`value`) VALUES ('yahoo_appid', '');
INSERT IGNORE INTO `#__rseventspro_config` (`name` ,`value`) VALUES ('yahoo_key', '');
INSERT IGNORE INTO `#__rseventspro_config` (`name` ,`value`) VALUES ('yahoo_secret', '');
INSERT IGNORE INTO `#__rseventspro_config` (`name` ,`value`) VALUES ('facebook_admins', '');
INSERT IGNORE INTO `#__rseventspro_config` (`name` ,`value`) VALUES ('facebook_order_by', 'social');
INSERT IGNORE INTO `#__rseventspro_config` (`name` ,`value`) VALUES ('facebook_color_scheme', 'light');
INSERT IGNORE INTO `#__rseventspro_config` (`name` ,`value`) VALUES ('facebook_num_posts', '5');
INSERT IGNORE INTO `#__rseventspro_config` (`name` ,`value`) VALUES ('facebook_width', '650');
INSERT IGNORE INTO `#__rseventspro_config` (`name` ,`value`) VALUES ('facebook_app_id', '340486642645761');
INSERT IGNORE INTO `#__rseventspro_config` (`name` ,`value`) VALUES ('disqus_shortname', '');
INSERT IGNORE INTO `#__rseventspro_config` (`name` ,`value`) VALUES ('backendlist', '0');
INSERT IGNORE INTO `#__rseventspro_config` (`name` ,`value`) VALUES ('jquery', '0');
INSERT IGNORE INTO `#__rseventspro_config` (`name` ,`value`) VALUES ('adminjquery', '1');
INSERT IGNORE INTO `#__rseventspro_config` (`name` ,`value`) VALUES ('bootstrap', '0');
INSERT IGNORE INTO `#__rseventspro_config` (`name` ,`value`) VALUES ('google_key', '');
INSERT IGNORE INTO `#__rseventspro_config` (`name` ,`value`) VALUES ('postreminder', '0');
INSERT IGNORE INTO `#__rseventspro_config` (`name` ,`value`) VALUES ('hideseconds', '0');
INSERT IGNORE INTO `#__rseventspro_config` (`name` ,`value`) VALUES ('google_client_id', '');
INSERT IGNORE INTO `#__rseventspro_config` (`name` ,`value`) VALUES ('google_secret', '');
INSERT IGNORE INTO `#__rseventspro_config` (`name` ,`value`) VALUES ('captcha', '1');
INSERT IGNORE INTO `#__rseventspro_config` (`name` ,`value`) VALUES ('recaptcha_site_key', '');
INSERT IGNORE INTO `#__rseventspro_config` (`name` ,`value`) VALUES ('recaptcha_secret_key', '');
INSERT IGNORE INTO `#__rseventspro_config` (`name` ,`value`) VALUES ('recaptcha_theme', 'light');
INSERT IGNORE INTO `#__rseventspro_config` (`name` ,`value`) VALUES ('recaptcha_type', 'image');
INSERT IGNORE INTO `#__rseventspro_config` (`name` ,`value`) VALUES ('modal_width', '800');
INSERT IGNORE INTO `#__rseventspro_config` (`name` ,`value`) VALUES ('modal_heigth', '600');
INSERT IGNORE INTO `#__rseventspro_config` (`name` ,`value`) VALUES ('timezone', '0');
INSERT IGNORE INTO `#__rseventspro_config` (`name` ,`value`) VALUES ('fontawesome', '1');
INSERT IGNORE INTO `#__rseventspro_config` (`name` ,`value`) VALUES ('canonical', '1');
INSERT IGNORE INTO `#__rseventspro_config` (`name` ,`value`) VALUES ('default_image', 'blank.png');
INSERT IGNORE INTO `#__rseventspro_config` (`name` ,`value`) VALUES ('google_map_api', '');
INSERT IGNORE INTO `#__rseventspro_config` (`name` ,`value`) VALUES ('enable_gallery', '1');
INSERT IGNORE INTO `#__rseventspro_config` (`name` ,`value`) VALUES ('facebook_check_owner', '1');
INSERT IGNORE INTO `#__rseventspro_config` (`name` ,`value`) VALUES ('google_access_token', '');
INSERT IGNORE INTO `#__rseventspro_config` (`name` ,`value`) VALUES ('content_prepare', '1');
INSERT IGNORE INTO `#__rseventspro_config` (`name` ,`value`) VALUES ('seo_title', '1');
INSERT IGNORE INTO `#__rseventspro_config` (`name` ,`value`) VALUES ('google_expired', '1');
INSERT IGNORE INTO `#__rseventspro_config` (`name` ,`value`) VALUES ('user_icon_width', '150');
INSERT IGNORE INTO `#__rseventspro_config` (`name` ,`value`) VALUES ('facebook_expired', '1');
INSERT IGNORE INTO `#__rseventspro_config` (`name` ,`value`) VALUES ('facebook_profile', '1');
INSERT IGNORE INTO `#__rseventspro_config` (`name` ,`value`) VALUES ('facebook_check_owner_profile', '1');
INSERT IGNORE INTO `#__rseventspro_config` (`name` ,`value`) VALUES ('facebook_recurring', '1');
INSERT IGNORE INTO `#__rseventspro_config` (`name` ,`value`) VALUES ('filter_from', 'events');
INSERT IGNORE INTO `#__rseventspro_config` (`name` ,`value`) VALUES ('filter_condition', 'is');
INSERT IGNORE INTO `#__rseventspro_config` (`name` ,`value`) VALUES ('speaker_icon_width', '100');
INSERT IGNORE INTO `#__rseventspro_config` (`name` ,`value`) VALUES ('speaker_icon_height', '150');

INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Afghanistan');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Akrotiri');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Albania');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Algeria');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('American Samoa');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Andorra');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Angola');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Anguilla');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Antarctica');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Antigua and Barbuda');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Argentina');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Armenia');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Aruba');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Ashmore and Cartier Islands');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Australia');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Austria');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Azerbaijan');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Bahamas, The');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Bahrain');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Bangladesh');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Barbados');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Bassas da India');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Belarus');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Belgium');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Belize');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Benin');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Bermuda');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Bhutan');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Bolivia');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Bosnia and Herzegovina');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Botswana');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Bouvet Island');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Brazil');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('British Indian Ocean Territory');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('British Virgin Islands');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Brunei');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Bulgaria');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Burkina Faso');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Burma');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Burundi');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Cambodia');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Cameroon');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Canada');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Cape Verde');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Cayman Islands');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Central African Republic');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Chad');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Chile');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('China');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Christmas Island');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Clipperton Island');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Cocos (Keeling) Islands');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Colombia');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Comoros');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Congo, Democratic Republic of the');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Congo, Republic of the');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Cook Islands');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Coral Sea Islands');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Costa Rica');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Cote d''Ivoire');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Croatia');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Cuba');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Cyprus');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Czech Republic');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Denmark');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Dhekelia');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Djibouti');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Dominica');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Dominican Republic');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Ecuador');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Egypt');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('El Salvador');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Equatorial Guinea');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Eritrea');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Estonia');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Ethiopia');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Europa Island');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Falkland Islands (Islas Malvinas)');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Faroe Islands');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Fiji');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Finland');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('France');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('French Guiana');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('French Polynesia');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('French Southern and Antarctic Lands');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Gabon');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Gambia, The');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Gaza Strip');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Georgia');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Germany');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Ghana');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Gibraltar');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Glorioso Islands');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Greece');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Greenland');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Grenada');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Guadeloupe');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Guam');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Guatemala');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Guernsey');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Guinea');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Guinea-Bissau');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Guyana');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Haiti');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Heard Island and McDonald Islands');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Holy See (Vatican City)');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Honduras');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Hong Kong');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Hungary');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Iceland');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('India');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Indonesia');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Iran');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Iraq');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Ireland');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Isle of Man');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Israel');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Italy');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Jamaica');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Jan Mayen');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Japan');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Jersey');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Jordan');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Juan de Nova Island');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Kazakhstan');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Kenya');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Kiribati');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Korea, North');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Korea, South');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Kuwait');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Kyrgyzstan');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Laos');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Latvia');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Lebanon');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Lesotho');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Liberia');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Libya');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Liechtenstein');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Lithuania');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Luxembourg');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Macau');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Macedonia');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Madagascar');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Malawi');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Malaysia');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Maldives');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Mali');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Malta');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Marshall Islands');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Martinique');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Mauritania');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Mauritius');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Mayotte');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Mexico');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Micronesia, Federated States of');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Moldova');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Monaco');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Mongolia');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Montserrat');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Morocco');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Mozambique');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Namibia');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Nauru');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Navassa Island');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Nepal');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Netherlands');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Netherlands Antilles');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('New Caledonia');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('New Zealand');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Nicaragua');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Niger');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Nigeria');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Niue');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Norfolk Island');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Northern Mariana Islands');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Norway');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Oman');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Pakistan');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Palau');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Panama');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Papua New Guinea');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Paracel Islands');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Paraguay');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Peru');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Philippines');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Pitcairn Islands');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Poland');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Portugal');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Puerto Rico');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Qatar');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Reunion');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Romania');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Russia');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Rwanda');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Saint Helena');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Saint Kitts and Nevis');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Saint Lucia');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Saint Pierre and Miquelon');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Saint Vincent and the Grenadines');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Samoa');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('San Marino');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Sao Tome and Principe');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Saudi Arabia');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Senegal');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Serbia and Montenegro');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Seychelles');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Sierra Leone');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Singapore');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Slovakia');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Slovenia');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Solomon Islands');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Somalia');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('South Africa');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('South Georgia and the South Sandwich Islands');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Spain');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Spratly Islands');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Sri Lanka');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Sudan');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Suriname');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Svalbard');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Swaziland');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Sweden');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Switzerland');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Syria');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Taiwan');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Tajikistan');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Tanzania');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Thailand');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Timor-Leste');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Togo');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Tokelau');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Tonga');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Trinidad and Tobago');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Tromelin Island');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Tunisia');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Turkey');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Turkmenistan');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Turks and Caicos Islands');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Tuvalu');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Uganda');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Ukraine');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('United Arab Emirates');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('United Kingdom');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('United States');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Uruguay');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Uzbekistan');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Vanuatu');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Venezuela');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Vietnam');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Virgin Islands');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Wake Island');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Wallis and Futuna');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('West Bank');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Western Sahara');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Yemen');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Zambia');
INSERT IGNORE INTO `#__rseventspro_countries` (`name`) VALUES('Zimbabwe');