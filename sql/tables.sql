CREATE TABLE `WORDPRESS_wpdbpt_activity_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `the_action` varchar(64) NOT NULL,
  `the_table` varchar(64) NOT NULL,
  `the_row_count` int(11) NOT NULL,
  `action_output` text NOT NULL,
  `the_moment` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=DB_CHARSET;

CREATE TABLE `WORDPRESS_wpdbpt_backup_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `backup_date` datetime NOT NULL,
  `backup_table` varchar(64) NOT NULL,
  `backup_rows` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=DB_CHARSET;

CREATE TABLE `WORDPRESS_wpdbpt_drop_table_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `drop_date` datetime NOT NULL,
  `table_name` varchar(64) NOT NULL,
  `create_table_sql` text NOT NULL,
  `table_rows` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=DB_CHARSET;

CREATE TABLE `WORDPRESS_wpdbpt_objects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_name` varchar(64) NOT NULL,
  `object_type` varchar(45) NOT NULL,
  `object_status` varchar(45) NOT NULL,
  `display_object` char(5) NOT NULL DEFAULT 'Yes',
  `deactivate_drop` char(4) NOT NULL DEFAULT 'No',
  `object_logged` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=DB_CHARSET;

CREATE TABLE `WORDPRESS_wpdbpt_restore_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `table_name` varchar(64) NOT NULL,
  `backup_date` datetime NOT NULL,
  `restore_date` datetime NOT NULL,
  `row_count` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=DB_CHARSET;

CREATE TABLE `WORDPRESS_wpdbpt_sp_activity_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sp_name` varchar(64) NOT NULL,
  `sp_definition` mediumtext NOT NULL,
  `sp_action` varchar(45) NOT NULL,
  `action_time` varchar(45) NOT NULL DEFAULT 'NOW()',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=DB_CHARSET;

CREATE TABLE `WORDPRESS_wpdbpt_sql_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sql_time` datetime NOT NULL,
  `the_sql` mediumtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=DB_CHARSET;
