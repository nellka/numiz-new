27.11.2015
alter table user modify `userlogin` varchar(100) NOT NULL DEFAULT '';

CREATE TABLE `nominals` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `name` varchar(100) DEFAULT NULL,
 PRIMARY KEY (`id`) 
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


CREATE TABLE `metals` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `name` varchar(100) DEFAULT NULL,
 PRIMARY KEY (`id`) 
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

CREATE TABLE `conditions` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `name` varchar(100) DEFAULT NULL,
 PRIMARY KEY (`id`) 
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

CREATE TABLE `themes` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `coins_id` int(11) NOT NULL,
 `name` varchar(100) DEFAULT NULL,
 PRIMARY KEY (`id`) ,
 UNIQUE KEY `ids` (`id`,`coins_id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;


alter table `shopcoins` add column nominal_id int(11) NOT NULL default 0;
alter table `shopcoins` add column metal_id int(11) NOT NULL default 0;
alter table `shopcoins` add column condition_id int(11) NOT NULL default 0;

alter table `shopcoins` add key `nominal_id`(`nominal_id`);
alter table `shopcoins` add key `metal_id`(`metal_id`);
alter table `shopcoins` add key `condition_id`(`condition_id`);

alter table `user` add column vip_discoint int(11) NOT NULL default 0;

update user set vip_discoint = (SELECT sum FROM `coupon` where type=2 and user.user = coupon.user order by dateend desc limit 1)
# протестировать на дубли
SELECT count( user ) AS u, coupon . * FROM `coupon` WHERE TYPE =2 AND sum >0 GROUP BY user HAVING u >1 LIMIT 0 , 30

CREATE TABLE `shopcoinsseotext` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `materialtype`  int(11) NOT NULL,
 `group_id`  int(11) NOT NULL default 0,
 `nominal_id`  int(11) NOT NULL default 0,
 `title` varchar(255),
` text` text,
 `dateinsert`  int(11) NOT NULL default 0,
 `active` tinyint(4),
PRIMARY KEY (`id`),
KEY  `group_id` (`group_id`),
KEY `nominal_id` (`nominal_id`)
);

alter table shopcoins add column novelty int(11)  default 0;
alter table shopcoins  add key `novelty`(`novelty`);

