DROP TABLE IF EXISTS `gashas`;
CREATE TABLE `gashas` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `start_date` date DEFAULT NULL COMMENT 'ガシャ開始日',
  `end_date` date DEFAULT NULL COMMENT 'ガシャ終了日',
  `title` varchar(255) DEFAULT NULL COMMENT 'ガシャタイトル',
  `ssr_rate` int DEFAULT NULL COMMENT 'SSRレート',
  `sr_rate` int DEFAULT NULL COMMENT 'SRレート',
  `search_snippet` mediumtext DEFAULT NULL COMMENT 'フリーワード検索用のスニペット',
  `created` datetime DEFAULT NULL COMMENT '作成日時',
  `modified` datetime DEFAULT NULL COMMENT '更新日時',
  `deleted` datetime DEFAULT NULL COMMENT '削除日時',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='ガシャ';
