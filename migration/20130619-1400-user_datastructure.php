<?php

function install() {
	db()->query("CREATE TABLE IF NOT EXISTS `user_data` (
			`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			`type` tinyint(4) NOT NULL,
			`name` varchar(32) NOT NULL,
			`pass_format` tinyint(4) NOT NULL DEFAULT 0,
			`pass_hash` varchar(255) DEFAULT NULL,
			`pass_salt` varchar(255) DEFAULT NULL,
			`email` varchar(255) DEFAULT NULL,
			`last_login` int(10) unsigned DEFAULT NULL,
			`last_refresh` int(10) unsigned DEFAULT NULL,
			`last_ip` varchar(15) DEFAULT NULL,
			`create_date` int(10) unsigned DEFAULT NULL,
			`create_by` int(10) unsigned DEFAULT NULL,
			`update_date` int(10) unsigned DEFAULT NULL,
			`update_by` int(10) unsigned DEFAULT NULL,
			PRIMARY KEY (`id`),
			KEY `name` (`name`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8;");

	db()->query("CREATE TABLE IF NOT EXISTS `user_blocked` (
			`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			`type` enum('name','email') NOT NULL,
			`pattern` varchar(250) NOT NULL,
			PRIMARY KEY (`id`),
			KEY `type` (`type`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;");

	db()->query("CREATE TABLE IF NOT EXISTS `user_groups` (
			`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			`name` varchar(200) NOT NULL,
			`rights` text DEFAULT NULL,
			PRIMARY KEY (`id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8;");

	db()->query("CREATE TABLE IF NOT EXISTS `user_group_owner` (
			`group` int(10) unsigned NOT NULL,
			`user` int(10) unsigned NOT NULL,
			`start_date` int(10) unsigned NOT NULL DEFAULT 0,
			`end_date` int(10) unsigned DEFAULT 0,
			PRIMARY KEY (`user`,`group`),
			KEY `group` (`group`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

	db()->query("CREATE TABLE IF NOT EXISTS `user_profil` (
			`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			`name` varchar(32) NOT NULL,
			`caption` varchar(32) NOT NULL,
			`type` varchar(32) NOT NULL,
			`options` varchar(32) DEFAULT NULL,
			`create_date` int(10) unsigned DEFAULT NULL,
			`create_by` int(10) unsigned DEFAULT NULL,
			PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

	db()->query("ALTER TABLE `user_group_owner`
			ADD FOREIGN KEY (`user`) REFERENCES `user_data` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
			ADD FOREIGN KEY (`group`) REFERENCES `user_groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;");
}

function remove() {
	db()->query("DROP TABLE `user_group_owner`;");
	db()->query("DROP TABLE `user_profil`;");
	db()->query("DROP TABLE `user_groups`;");
	db()->query("DROP TABLE `user_blocked`;");
	db()->query("DROP TABLE `user_data`;");
}
