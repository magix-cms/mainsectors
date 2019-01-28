CREATE TABLE IF NOT EXISTS `mc_mainsectors` (
  `id_ms` smallint(3) unsigned NOT NULL AUTO_INCREMENT,
  `id_page` int(11) unsigned NOT NULL,
  `type_ms` enum('category','page') NOT NULL DEFAULT 'category',
  `order_ms` smallint(3) unsigned NOT NULL default 0,
  PRIMARY KEY (`id_ms`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `mc_admin_access` (`id_role`, `id_module`, `view`, `append`, `edit`, `del`, `action`)
  SELECT 1, m.id_module, 1, 1, 1, 1, 1 FROM mc_module as m WHERE name = 'mainsectors';