DROP TABLE IF EXISTS `gasha_pickups`;
CREATE TABLE `gasha_pickups` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `gasha_id` int NOT NULL COMMENT 'ガシャID',
  `card_id` int NOT NULL COMMENT 'カードID',
  `search_snippet` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin COMMENT 'フリーワード検索用のスニペット',
  `created` datetime NOT NULL COMMENT '作成日時',
  `modified` datetime NOT NULL COMMENT '更新日時',
  `deleted` datetime DEFAULT NULL COMMENT '削除日時',
  PRIMARY KEY (`id`),
  KEY `gasha_id` (`gasha_id`),
  KEY `card_id` (`card_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin COMMENT='ピックアップ情報';
