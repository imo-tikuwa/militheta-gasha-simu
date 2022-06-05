DROP TABLE IF EXISTS `gashas`;
CREATE TABLE `gashas` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `start_date` date NOT NULL COMMENT 'ガシャ開始日',
  `end_date` date NOT NULL COMMENT 'ガシャ終了日',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT 'ガシャタイトル',
  `ssr_rate` int NOT NULL COMMENT 'SSRレート',
  `sr_rate` int NOT NULL COMMENT 'SRレート',
  `search_snippet` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin COMMENT 'フリーワード検索用のスニペット',
  `created` datetime NOT NULL COMMENT '作成日時',
  `modified` datetime NOT NULL COMMENT '更新日時',
  `deleted` datetime DEFAULT NULL COMMENT '削除日時',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin COMMENT='ガシャ';
