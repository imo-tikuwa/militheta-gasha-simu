DROP TABLE IF EXISTS `cards`;
CREATE TABLE `cards` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `character_id` int NOT NULL COMMENT 'キャラクター',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT 'カード名',
  `rarity` char(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT 'レアリティ',
  `type` char(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT 'タイプ',
  `add_date` date NOT NULL COMMENT '実装日',
  `gasha_include` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'ガシャ対象？',
  `limited` char(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT '限定？',
  `search_snippet` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin COMMENT 'フリーワード検索用のスニペット',
  `created` datetime NOT NULL COMMENT '作成日時',
  `modified` datetime NOT NULL COMMENT '更新日時',
  `deleted` datetime DEFAULT NULL COMMENT '削除日時',
  PRIMARY KEY (`id`),
  KEY `character_id` (`character_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin COMMENT='カード';
