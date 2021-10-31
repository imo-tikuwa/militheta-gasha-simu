DROP TABLE IF EXISTS `cards`;
CREATE TABLE `cards` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `character_id` int(11) DEFAULT NULL COMMENT 'キャラクター',
  `name` varchar(255) DEFAULT NULL COMMENT 'カード名',
  `rarity` char(2) DEFAULT NULL COMMENT 'レアリティ',
  `type` char(2) DEFAULT NULL COMMENT 'タイプ',
  `add_date` date DEFAULT NULL COMMENT '実装日',
  `gasha_include` tinyint(1) DEFAULT 1 COMMENT 'ガシャ対象？',
  `limited` char(2) DEFAULT NULL COMMENT '限定？',
  `search_snippet` mediumtext DEFAULT NULL COMMENT 'フリーワード検索用のスニペット',
  `created` datetime DEFAULT NULL COMMENT '作成日時',
  `modified` datetime DEFAULT NULL COMMENT '更新日時',
  `deleted` datetime DEFAULT NULL COMMENT '削除日時',
  PRIMARY KEY (`id`),
  INDEX `character_id` (`character_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='カード';
