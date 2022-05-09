CREATE TABLE `tnotes` ( id INTEGER PRIMARY KEY AUTOINCREMENT );
ALTER TABLE `tnotes` ADD `created_at` NUMERIC;
ALTER TABLE `tnotes` ADD `updated_at` NUMERIC;
ALTER TABLE `tnotes` ADD `timestamp` INTEGER;
ALTER TABLE `tnotes` ADD `name` TEXT;
ALTER TABLE `tnotes` ADD `description` TEXT;
CREATE TABLE `tgroups` ( id INTEGER PRIMARY KEY AUTOINCREMENT );
ALTER TABLE `tgroups` ADD `name` TEXT;
ALTER TABLE `tgroups` ADD `description` TEXT;
CREATE TABLE `tcategories` ( id INTEGER PRIMARY KEY AUTOINCREMENT );
ALTER TABLE `tcategories` ADD `name` TEXT;
ALTER TABLE `tcategories` ADD `description` TEXT;
ALTER TABLE `tcategories` ADD `tgroups_id` INTEGER;
CREATE TEMPORARY TABLE tmp_backup(`id`,`name`,`description`,`tgroups_id`);;
CREATE TABLE `tcategories` ( `id` INTEGER PRIMARY KEY AUTOINCREMENT  ,`name` TEXT ,`description` TEXT ,`tgroups_id` INTEGER    );;
CREATE INDEX index_foreignkey_tcategories_tgroups ON `tcategories` (tgroups_id) ;
CREATE TEMPORARY TABLE tmp_backup(`id`,`name`,`description`,`tgroups_id`);;
CREATE TABLE `tcategories` ( `id` INTEGER PRIMARY KEY AUTOINCREMENT  ,`name` TEXT ,`description` TEXT ,`tgroups_id` INTEGER   , FOREIGN KEY(`tgroups_id`)
						 REFERENCES `tgroups`(`id`)
						 ON DELETE SET NULL ON UPDATE SET NULL );;
CREATE INDEX index_foreignkey_tcategories_tgroups ON `tcategories` (tgroups_id) ;
ALTER TABLE `tcategories` ADD `tcategories_id` INTEGER;
CREATE TEMPORARY TABLE tmp_backup(`id`,`name`,`description`,`tgroups_id`,`tcategories_id`);;
CREATE TABLE `tcategories` ( `id` INTEGER PRIMARY KEY AUTOINCREMENT  ,`name` TEXT ,`description` TEXT ,`tgroups_id` INTEGER ,`tcategories_id` INTEGER   , FOREIGN KEY(`tgroups_id`)
						 REFERENCES `tgroups`(`id`)
						 ON DELETE SET NULL ON UPDATE SET NULL );;
CREATE INDEX index_foreignkey_tcategories_tgroups ON `tcategories` (tgroups_id) ;
CREATE INDEX index_foreignkey_tcategories_tcategories ON `tcategories` (tcategories_id) ;
CREATE TEMPORARY TABLE tmp_backup(`id`,`name`,`description`,`tgroups_id`,`tcategories_id`);;
CREATE TABLE `tcategories` ( `id` INTEGER PRIMARY KEY AUTOINCREMENT  ,`name` TEXT ,`description` TEXT ,`tgroups_id` INTEGER ,`tcategories_id` INTEGER   , FOREIGN KEY(`tgroups_id`)
						 REFERENCES `tgroups`(`id`)
						 ON DELETE SET NULL ON UPDATE SET NULL, FOREIGN KEY(`tcategories_id`)
						 REFERENCES `tcategories`(`id`)
						 ON DELETE SET NULL ON UPDATE SET NULL );;
CREATE INDEX index_foreignkey_tcategories_tcategories ON `tcategories` (tcategories_id) ;
CREATE INDEX index_foreignkey_tcategories_tgroups ON `tcategories` (tgroups_id) ;
CREATE TABLE `tlinks` ( id INTEGER PRIMARY KEY AUTOINCREMENT );
ALTER TABLE `tlinks` ADD `created_at` NUMERIC;
ALTER TABLE `tlinks` ADD `updated_at` NUMERIC;
ALTER TABLE `tlinks` ADD `timestamp` INTEGER;
ALTER TABLE `tlinks` ADD `name` TEXT;
ALTER TABLE `tlinks` ADD `url` TEXT;
ALTER TABLE `tlinks` ADD `description` TEXT;
ALTER TABLE `tlinks` ADD `tcategories_id` INTEGER;
CREATE TEMPORARY TABLE tmp_backup(`id`,`created_at`,`updated_at`,`timestamp`,`name`,`url`,`description`,`tcategories_id`);;
CREATE TABLE `tlinks` ( `id` INTEGER PRIMARY KEY AUTOINCREMENT  ,`created_at` NUMERIC ,`updated_at` NUMERIC ,`timestamp` INTEGER ,`name` TEXT ,`url` TEXT ,`description` TEXT ,`tcategories_id` INTEGER    );;
CREATE INDEX index_foreignkey_tlinks_tcategories ON `tlinks` (tcategories_id) ;
CREATE TEMPORARY TABLE tmp_backup(`id`,`created_at`,`updated_at`,`timestamp`,`name`,`url`,`description`,`tcategories_id`);;
CREATE TABLE `tlinks` ( `id` INTEGER PRIMARY KEY AUTOINCREMENT  ,`created_at` NUMERIC ,`updated_at` NUMERIC ,`timestamp` INTEGER ,`name` TEXT ,`url` TEXT ,`description` TEXT ,`tcategories_id` INTEGER   , FOREIGN KEY(`tcategories_id`)
						 REFERENCES `tcategories`(`id`)
						 ON DELETE SET NULL ON UPDATE SET NULL );;
CREATE INDEX index_foreignkey_tlinks_tcategories ON `tlinks` (tcategories_id) ;
ALTER TABLE `tlinks` ADD `tnotes_id` INTEGER;
CREATE TEMPORARY TABLE tmp_backup(`id`,`created_at`,`updated_at`,`timestamp`,`name`,`url`,`description`,`tcategories_id`,`tnotes_id`);;
CREATE TABLE `tlinks` ( `id` INTEGER PRIMARY KEY AUTOINCREMENT  ,`created_at` NUMERIC ,`updated_at` NUMERIC ,`timestamp` INTEGER ,`name` TEXT ,`url` TEXT ,`description` TEXT ,`tcategories_id` INTEGER ,`tnotes_id` INTEGER   , FOREIGN KEY(`tcategories_id`)
						 REFERENCES `tcategories`(`id`)
						 ON DELETE SET NULL ON UPDATE SET NULL );;
CREATE INDEX index_foreignkey_tlinks_tcategories ON `tlinks` (tcategories_id) ;
CREATE INDEX index_foreignkey_tlinks_tnotes ON `tlinks` (tnotes_id) ;
CREATE TEMPORARY TABLE tmp_backup(`id`,`created_at`,`updated_at`,`timestamp`,`name`,`url`,`description`,`tcategories_id`,`tnotes_id`);;
CREATE TABLE `tlinks` ( `id` INTEGER PRIMARY KEY AUTOINCREMENT  ,`created_at` NUMERIC ,`updated_at` NUMERIC ,`timestamp` INTEGER ,`name` TEXT ,`url` TEXT ,`description` TEXT ,`tcategories_id` INTEGER ,`tnotes_id` INTEGER   , FOREIGN KEY(`tcategories_id`)
						 REFERENCES `tcategories`(`id`)
						 ON DELETE SET NULL ON UPDATE SET NULL, FOREIGN KEY(`tnotes_id`)
						 REFERENCES `tnotes`(`id`)
						 ON DELETE SET NULL ON UPDATE SET NULL );;
CREATE INDEX index_foreignkey_tlinks_tnotes ON `tlinks` (tnotes_id) ;
CREATE INDEX index_foreignkey_tlinks_tcategories ON `tlinks` (tcategories_id) ;
CREATE TABLE `tdomains` ( id INTEGER PRIMARY KEY AUTOINCREMENT );
ALTER TABLE `tdomains` ADD `created_at` NUMERIC;
ALTER TABLE `tdomains` ADD `updated_at` NUMERIC;
ALTER TABLE `tdomains` ADD `timestamp` INTEGER;
ALTER TABLE `tdomains` ADD `name` TEXT;
ALTER TABLE `tdomains` ADD `url` TEXT;
ALTER TABLE `tdomains` ADD `description` TEXT;
CREATE TABLE `tdomains_tlinks` ( id INTEGER PRIMARY KEY AUTOINCREMENT );
ALTER TABLE `tdomains_tlinks` ADD `tlinks_id` INTEGER;
CREATE TEMPORARY TABLE tmp_backup(`id`,`tlinks_id`);;
CREATE TABLE `tdomains_tlinks` ( `id` INTEGER PRIMARY KEY AUTOINCREMENT  ,`tlinks_id` INTEGER    );;
CREATE INDEX index_foreignkey_tdomains_tlinks_tlinks ON `tdomains_tlinks` (tlinks_id) ;
CREATE TEMPORARY TABLE tmp_backup(`id`,`tlinks_id`);;
CREATE TABLE `tdomains_tlinks` ( `id` INTEGER PRIMARY KEY AUTOINCREMENT  ,`tlinks_id` INTEGER   , FOREIGN KEY(`tlinks_id`)
						 REFERENCES `tlinks`(`id`)
						 ON DELETE CASCADE ON UPDATE CASCADE );;
CREATE INDEX index_foreignkey_tdomains_tlinks_tlinks ON `tdomains_tlinks` (tlinks_id) ;
ALTER TABLE `tdomains_tlinks` ADD `tdomains_id` INTEGER;
CREATE TEMPORARY TABLE tmp_backup(`id`,`tlinks_id`,`tdomains_id`);;
CREATE TABLE `tdomains_tlinks` ( `id` INTEGER PRIMARY KEY AUTOINCREMENT  ,`tlinks_id` INTEGER ,`tdomains_id` INTEGER   , FOREIGN KEY(`tlinks_id`)
						 REFERENCES `tlinks`(`id`)
						 ON DELETE CASCADE ON UPDATE CASCADE );;
CREATE INDEX index_foreignkey_tdomains_tlinks_tlinks ON `tdomains_tlinks` (tlinks_id) ;
CREATE INDEX index_foreignkey_tdomains_tlinks_tdomains ON `tdomains_tlinks` (tdomains_id) ;
CREATE TEMPORARY TABLE tmp_backup(`id`,`tlinks_id`,`tdomains_id`);;
CREATE TABLE `tdomains_tlinks` ( `id` INTEGER PRIMARY KEY AUTOINCREMENT  ,`tlinks_id` INTEGER ,`tdomains_id` INTEGER   , FOREIGN KEY(`tlinks_id`)
						 REFERENCES `tlinks`(`id`)
						 ON DELETE CASCADE ON UPDATE CASCADE, FOREIGN KEY(`tdomains_id`)
						 REFERENCES `tdomains`(`id`)
						 ON DELETE CASCADE ON UPDATE CASCADE );;
CREATE INDEX index_foreignkey_tdomains_tlinks_tdomains ON `tdomains_tlinks` (tdomains_id) ;
CREATE INDEX index_foreignkey_tdomains_tlinks_tlinks ON `tdomains_tlinks` (tlinks_id) ;
CREATE TEMPORARY TABLE tmp_backup(`id`,`tlinks_id`,`tdomains_id`);;
CREATE TABLE `tdomains_tlinks` ( `id` INTEGER PRIMARY KEY AUTOINCREMENT  ,`tlinks_id` INTEGER ,`tdomains_id` INTEGER   , FOREIGN KEY(`tdomains_id`)
						 REFERENCES `tdomains`(`id`)
						 ON DELETE CASCADE ON UPDATE CASCADE, FOREIGN KEY(`tlinks_id`)
						 REFERENCES `tlinks`(`id`)
						 ON DELETE CASCADE ON UPDATE CASCADE );;
CREATE INDEX index_foreignkey_tdomains_tlinks_tlinks ON `tdomains_tlinks` (tlinks_id) ;
CREATE INDEX index_foreignkey_tdomains_tlinks_tdomains ON `tdomains_tlinks` (tdomains_id) ;
CREATE UNIQUE INDEX UQ_tdomains_tlinkstlinks_id__tdomains_id ON `tdomains_tlinks` (`tlinks_id`,`tdomains_id`);
CREATE TABLE `ttags` ( id INTEGER PRIMARY KEY AUTOINCREMENT );
ALTER TABLE `ttags` ADD `created_at` NUMERIC;
ALTER TABLE `ttags` ADD `updated_at` NUMERIC;
ALTER TABLE `ttags` ADD `timestamp` INTEGER;
ALTER TABLE `ttags` ADD `name` TEXT;
CREATE TABLE `ttagstoobjectss` ( id INTEGER PRIMARY KEY AUTOINCREMENT );
ALTER TABLE `ttagstoobjectss` ADD `content_id` INTEGER;
CREATE TEMPORARY TABLE tmp_backup(`id`,`content_id`);;
CREATE TABLE `ttagstoobjectss` ( `id` INTEGER PRIMARY KEY AUTOINCREMENT  ,`content_id` INTEGER    );;
CREATE INDEX index_foreignkey_ttagstoobjectss_content ON `ttagstoobjectss` (content_id) ;
ALTER TABLE `ttagstoobjectss` ADD `content_type` TEXT;
ALTER TABLE `ttagstoobjectss` ADD `ttags_id` INTEGER;
CREATE TEMPORARY TABLE tmp_backup(`id`,`content_id`,`content_type`,`ttags_id`);;
CREATE TABLE `ttagstoobjectss` ( `id` INTEGER PRIMARY KEY AUTOINCREMENT  ,`content_id` INTEGER ,`content_type` TEXT ,`ttags_id` INTEGER    );;
CREATE INDEX index_foreignkey_ttagstoobjectss_content ON `ttagstoobjectss` (content_id) ;
CREATE INDEX index_foreignkey_ttagstoobjectss_ttags ON `ttagstoobjectss` (ttags_id) ;
CREATE TEMPORARY TABLE tmp_backup(`id`,`content_id`,`content_type`,`ttags_id`);;
CREATE TABLE `ttagstoobjectss` ( `id` INTEGER PRIMARY KEY AUTOINCREMENT  ,`content_id` INTEGER ,`content_type` TEXT ,`ttags_id` INTEGER   , FOREIGN KEY(`ttags_id`)
						 REFERENCES `ttags`(`id`)
						 ON DELETE SET NULL ON UPDATE SET NULL );;
CREATE INDEX index_foreignkey_ttagstoobjectss_ttags ON `ttagstoobjectss` (ttags_id) ;
CREATE INDEX index_foreignkey_ttagstoobjectss_content ON `ttagstoobjectss` (content_id) ;
