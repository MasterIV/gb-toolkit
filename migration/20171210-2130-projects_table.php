<?php

function install() {
	db()->query("CREATE TABLE `projects` ( 
			`id` INT UNSIGNED NOT NULL AUTO_INCREMENT , 
			`name` VARCHAR(200) NOT NULL , 
			`description` TEXT NOT NULL , 
			`owner` INT UNSIGNED NOT NULL , 
			PRIMARY KEY (`id`)
		) ENGINE = InnoDB;");

	db()->query("ALTER TABLE `projects`
			ADD FOREIGN KEY (`owner`) REFERENCES `user_data` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;");

	db()->query("CREATE TABLE `collaborators` ( 
			`id` INT UNSIGNED NOT NULL AUTO_INCREMENT , 
			`project` INT UNSIGNED NOT NULL , 
			`user` INT UNSIGNED NOT NULL , 
			`rights` INT UNSIGNED NOT NULL ,
			PRIMARY KEY (`id`)
		) ENGINE = InnoDB;");

	db()->query("ALTER TABLE `collaborators`
			ADD FOREIGN KEY (`user`) REFERENCES `user_data` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
			ADD FOREIGN KEY (`project`) REFERENCES `projects` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;");

	db()->query("CREATE TABLE `files` ( 
			`id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
			`name` VARCHAR(200) NOT NULL , 
			`type` INT UNSIGNED NOT NULL , 
			`project` INT UNSIGNED NOT NULL , 
			`create_date` int(10) unsigned DEFAULT NULL,
			`create_by` int(10) unsigned DEFAULT NULL,
			`update_date` int(10) unsigned DEFAULT NULL,
			`update_by` int(10) unsigned DEFAULT NULL,
			 PRIMARY KEY (`id`)
		 ) ENGINE = InnoDB;");

	db()->query("");

	db()->query("");

	db()->query("");
}

function remove() {
	db()->query("DROP TABLE `collaborators`;");
	db()->query("DROP TABLE `files`;");
	db()->query("DROP TABLE `projects`;");
//	db()->query("DROP TABLE ``;");
//	db()->query("DROP TABLE ``;");
}
