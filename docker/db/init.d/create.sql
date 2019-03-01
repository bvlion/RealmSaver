CREATE TABLE `realm_save_data` (
  `user_hash` CHAR(8) NOT NULL COMMENT 'ユーザーHASH',
  `json_data` LONGTEXT NOT NULL COMMENT 'JSONデータ(生JSONで格納)',
  `downloaded` TINYINT(1) NOT NULL DEFAULT '0' COMMENT 'ダウンロード済みフラグ',
  `create_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '登録日時',
  `update_date` DATETIME DEFAULT NULL COMMENT '更新日時',
  PRIMARY KEY (`user_hash`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Realmのバックアップデータ';