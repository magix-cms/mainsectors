<?php
class plugins_mainsectors_db
{
	/**
	 * @param $config
	 * @param bool $params
	 * @return mixed|null
	 * @throws Exception
	 */
	public function fetchData($config, $params = false)
	{
		if (!is_array($config)) return '$config must be an array';

		$sql = '';

		if ($config['context'] === 'all') {
			switch ($config['type']) {
				case 'mss':
					$sql = "SELECT 
								ms.id_ms,
								ms.id_page, 
								ms.type_ms,
								COALESCE(pc.name_pages, cc.name_cat) as name_ms
							FROM mc_mainsectors as ms
							LEFT JOIN mc_cms_page as p ON ms.id_page = p.id_pages AND ms.type_ms = 'page'
							LEFT JOIN mc_cms_page_content as pc ON p.id_pages = pc.id_pages AND pc.id_lang = :lang_p
							LEFT JOIN mc_catalog_cat as c ON ms.id_page = c.id_cat AND ms.type_ms = 'category'
							LEFT JOIN mc_catalog_cat_content as cc ON c.id_cat = cc.id_cat AND cc.id_lang = :lang_c
							ORDER BY ms.order_ms ASC";
					break;
				case 'pages':
					$sql = 'SELECT * FROM (
							SELECT p.id_pages AS id, p.id_parent AS parent, pc.name_pages AS name
							FROM mc_cms_page AS p
							LEFT JOIN mc_cms_page_content AS pc
							USING ( id_pages ) 
							LEFT JOIN mc_lang AS l ON pc.id_lang = l.id_lang
							WHERE p.menu_pages =1
							AND pc.published_pages =1
							ORDER BY p.id_pages ASC , l.default_lang DESC
							) as pt
							GROUP BY pt.id';
					break;
				case 'categories':
					$sql = 'SELECT * FROM (
							SELECT p.id_cat as id, p.id_parent as parent, pc.name_cat as name
							FROM mc_catalog_cat as p
							LEFT JOIN mc_catalog_cat_content as pc
							USING(id_cat)
							LEFT JOIN mc_lang AS l ON pc.id_lang = l.id_lang
							WHERE pc.published_cat =1
							ORDER BY p.id_cat ASC , l.default_lang DESC
							) as pt
							GROUP BY pt.id';
					break;
				case 'order':
					$sql = 'SELECT
								id_page,
								type_ms,
								order_ms
							FROM mc_mainsectors ORDER BY order_ms ASC';
					break;
			}

			return $sql ? component_routing_db::layer()->fetchAll($sql, $params) : null;
		}
		elseif ($config['context'] === 'one') {
			switch ($config['type']) {
				case 'newMs':
					$sql = "SELECT 
								ms.id_ms,
								ms.id_page, 
								ms.type_ms,
								COALESCE(pc.name_pages, cc.name_cat) as name_ms
							FROM mc_mainsectors as ms
							LEFT JOIN mc_cms_page as p ON ms.id_page = p.id_pages AND ms.type_ms = 'page'
							LEFT JOIN mc_cms_page_content as pc ON p.id_pages = pc.id_pages AND pc.id_lang = :lang_p
							LEFT JOIN mc_catalog_cat as c ON ms.id_page = c.id_cat AND ms.type_ms = 'category'
							LEFT JOIN mc_catalog_cat_content as cc ON c.id_cat = cc.id_cat AND cc.id_lang = :lang_c
							ORDER BY ms.order_ms DESC LIMIT 0,1";
					break;
				case 'homeMsp':
					$sql = "SELECT 
								GROUP_CONCAT(`id_page` SEPARATOR ',') as ids
						  	FROM mc_mainsectors WHERE type_ms = 'page'";
					break;
				case 'homeMsc':
					$sql = "SELECT 
								GROUP_CONCAT(`id_page` SEPARATOR ',') as ids
							FROM mc_mainsectors WHERE type_ms = 'category'";
					break;
			}

			return $sql ? component_routing_db::layer()->fetch($sql, $params) : null;
		}
	}

	/**
	 * @param $config
	 * @param array $params
	 * @return bool|string
	 */
	public function insert($config, $params = array())
	{
		if (!is_array($config)) return '$config must be an array';

		$sql = '';

		switch ($config['type']) {
			case 'ms_p':
				$sql = 'INSERT INTO mc_mainsectors (id_page, type_ms, order_ms)  
						SELECT :id, :type, COUNT(order_ms) FROM mc_mainsectors';
				break;
		}

		if($sql === '') return 'Unknown request asked';

		try {
			component_routing_db::layer()->insert($sql,$params);
			return true;
		}
		catch (Exception $e) {
			return 'Exception reçue : '.$e->getMessage();
		}
	}

	/**
	 * @param $config
	 * @param array $params
	 * @return bool|string
	 */
	public function update($config, $params = array())
	{
		if (!is_array($config)) return '$config must be an array';

		$sql = '';

		switch ($config['type']) {
			case 'order':
				$sql = 'UPDATE mc_mainsectors 
						SET order_ms = :order_ms
						WHERE id_ms = :id_ms';
				break;
		}

		if($sql === '') return 'Unknown request asked';

		try {
			component_routing_db::layer()->update($sql,$params);
			return true;
		}
		catch (Exception $e) {
			return 'Exception reçue : '.$e->getMessage();
		}
	}

	/**
	 * @param $config
	 * @param array $params
	 * @return bool|string
	 */
	public function delete($config, $params = array())
	{
		if (!is_array($config)) return '$config must be an array';
			$sql = '';

			switch ($config['type']) {
				case 'ms':
					$sql = 'DELETE FROM mc_mainsectors
							WHERE id_ms = :id';
					break;
			}

		if($sql === '') return 'Unknown request asked';

		try {
			component_routing_db::layer()->delete($sql,$params);
			return true;
		}
		catch (Exception $e) {
			return 'Exception reçue : '.$e->getMessage();
		}
	}
}