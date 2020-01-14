DROP TABLE IF EXISTS `gasha`;
CREATE TABLE `gasha` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `start_date` date DEFAULT NULL COMMENT 'ガシャ開始日',
  `end_date` date DEFAULT NULL COMMENT 'ガシャ終了日',
  `title` varchar(255) DEFAULT NULL COMMENT 'ガシャタイトル',
  `ssr_rate` int DEFAULT NULL COMMENT 'SSRレート',
  `sr_rate` int DEFAULT NULL COMMENT 'SRレート',
  `ssr_pickup_rate` double(4, 3) DEFAULT NULL COMMENT 'SSRピックアップレート',
  `sr_pickup_rate` double(4, 3) DEFAULT NULL COMMENT 'SRピックアップレート',
  `created` datetime DEFAULT NULL COMMENT '作成日時',
  `modified` datetime DEFAULT NULL COMMENT '更新日時',
  `delete_flag` char(1) NOT NULL DEFAULT '0' COMMENT '削除フラグ',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='ガシャ';
