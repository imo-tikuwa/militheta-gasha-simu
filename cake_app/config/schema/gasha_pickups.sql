DROP TABLE IF EXISTS `gasha_pickups`;
CREATE TABLE `gasha_pickups` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `gasha_id` int(11) DEFAULT NULL COMMENT 'ガシャID',
  `card_id` int(11) DEFAULT NULL COMMENT 'カードID',
  `search_snippet` mediumtext DEFAULT NULL COMMENT 'フリーワード検索用のスニペット',
  `created` datetime DEFAULT NULL COMMENT '作成日時',
  `modified` datetime DEFAULT NULL COMMENT '更新日時',
  `deleted` datetime DEFAULT NULL COMMENT '削除日時',
  PRIMARY KEY (`id`),
  INDEX `gasha_id` (`gasha_id`),
  INDEX `card_id` (`card_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='ピックアップ情報';
