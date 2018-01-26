<?php

function install() {
	db()->query("CREATE TABLE `projects` ( 
			`id` INT UNSIGNED NOT NULL AUTO_INCREMENT , 
			`name` VARCHAR(200) NOT NULL , 
			`description` TEXT NOT NULL , 
			`settings` TEXT NOT NULL , 
			`owner` INT UNSIGNED NULL , 
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
			ADD FOREIGN KEY (`user`) REFERENCES `user_data` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
			ADD FOREIGN KEY (`project`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;");
}

function remove() {
	db()->query("DROP TABLE `collaborators`;");
	db()->query("DROP TABLE `projects`;");
}
