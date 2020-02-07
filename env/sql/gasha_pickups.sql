DROP TABLE IF EXISTS `gasha_pickups`;
CREATE TABLE `gasha_pickups` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `gasha_id` int(11) DEFAULT NULL COMMENT 'ガシャID',
  `card_id` int(11) DEFAULT NULL COMMENT 'カードID',
  `created` datetime DEFAULT NULL COMMENT '作成日時',
  `modified` datetime DEFAULT NULL COMMENT '更新日時',
  `delete_flag` char(1) NOT NULL DEFAULT '0' COMMENT '削除フラグ',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='ピックアップ情報';
