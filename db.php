<?php
class plugins_mainsectors_db {
    /**
     * @var debug_logger $logger
     */
    protected debug_logger $logger;

    /**
     * @param array $config
     * @param array $params
     * @return array|bool
     */
    public function fetchData(array $config, array $params = []) {
		if ($config['context'] === 'all') {
			switch ($config['type']) {
				case 'mss':
					$query = "SELECT 
								ms.id_ms,
								ms.id_page, 
								ms.type_ms,
								COALESCE(pc.name_pages, cc.name_cat) as name_ms
							FROM mc_mainsectors as ms
							LEFT JOIN mc_cms_page as p ON ms.id_page = p.id_pages AND ms.type_ms = 'page'
							LEFT JOIN mc_cms_page_content as pc ON p.id_pages = pc.id_pages AND pc.id_lang = :lang_p
							LEFT JOIN mc_catalog_cat as c ON ms.id_page = c.id_cat AND ms.type_ms = 'category'
							LEFT JOIN mc_catalog_cat_content as cc ON c.id_cat = cc.id_cat AND cc.id_lang = :lang_c
							ORDER BY ms.order_ms";
					break;
				case 'pages':
					/*$query = 'SELECT * FROM (
								SELECT p.id_pages AS id, p.id_parent AS parent, pc.name_pages AS name
								FROM mc_cms_page AS p
								LEFT JOIN mc_cms_page_content AS pc USING ( id_pages )
								LEFT JOIN mc_lang AS l ON pc.id_lang = l.id_lang
								WHERE p.menu_pages = 1
								AND pc.published_pages = 1
								ORDER BY p.id_pages , l.default_lang DESC
							) as pt
							GROUP BY pt.id';*/
					$query = 'SELECT 
								mcp.id_pages AS id, 
								mcp.id_parent AS parent, 
								mcpc.name_pages AS name
							FROM mc_cms_page AS mcp
							LEFT JOIN mc_cms_page_content AS mcpc USING ( id_pages ) 
							LEFT JOIN mc_lang AS ml ON (mcpc.id_lang = ml.id_lang AND ml.default_lang = 1)
							WHERE mcp.menu_pages = 1
							AND mcpc.published_pages = 1
							ORDER BY mcp.id_pages';
					break;
				case 'categories':
					/*$query = 'SELECT * FROM (
							SELECT p.id_cat as id, p.id_parent as parent, pc.name_cat as name
							FROM mc_catalog_cat as p
							LEFT JOIN mc_catalog_cat_content as pc
							USING(id_cat)
							LEFT JOIN mc_lang AS l ON pc.id_lang = l.id_lang
							WHERE pc.published_cat =1
							ORDER BY p.id_cat , l.default_lang DESC
							) as pt
							GROUP BY pt.id';*/
					$query = 'SELECT 
								mcc.id_cat as id, 
								mcc.id_parent as parent, 
								mccc.name_cat as name
							FROM mc_catalog_cat as mcc
							LEFT JOIN mc_catalog_cat_content as mccc USING(id_cat)
							LEFT JOIN mc_lang AS ml ON (mccc.id_lang = ml.id_lang AND ml.default_lang = 1)
							WHERE mccc.published_cat =1
							ORDER BY mcc.id_cat';
					break;
				case 'order':
					$query = 'SELECT
								id_page,
								type_ms,
								order_ms
							FROM mc_mainsectors ORDER BY order_ms';
					break;
                default:
                    return false;
            }

            try {
                return component_routing_db::layer()->fetchAll($query, $params);
            }
            catch (Exception $e) {
                if(!isset($this->logger)) $this->logger = new debug_logger(MP_LOG_DIR);
                $this->logger->log('statement','db',$e->getMessage(),$this->logger::LOG_MONTH);
            }
		}
		elseif ($config['context'] === 'one') {
			switch ($config['type']) {
				case 'newMs':
					$query = "SELECT 
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
					$query = "SELECT 
								GROUP_CONCAT(`id_page` ORDER BY order_ms SEPARATOR ',') as ids
						  	FROM mc_mainsectors WHERE type_ms = 'page'";
					break;
				case 'homeMsc':
					$query = "SELECT 
								GROUP_CONCAT(`id_page` ORDER BY order_ms SEPARATOR ',') as ids
							FROM mc_mainsectors WHERE type_ms = 'category'";
					break;
                default:
                    return false;
            }

            try {
                return component_routing_db::layer()->fetch($query, $params);
            }
            catch (Exception $e) {
                if(!isset($this->logger)) $this->logger = new debug_logger(MP_LOG_DIR);
                $this->logger->log('statement','db',$e->getMessage(),$this->logger::LOG_MONTH);
            }
        }
        return false;
	}

    /**
     * @param array $config
     * @param array $params
     * @return bool
     */
    public function insert(array $config, array $params = []): bool {
		switch ($config['type']) {
			case 'ms_p':
				$query = 'INSERT INTO mc_mainsectors (id_page, type_ms, order_ms)  
						SELECT :id, :type, COUNT(order_ms) FROM mc_mainsectors';
				break;
            default:
                return false;
        }

        try {
            component_routing_db::layer()->insert($query,$params);
            return true;
        }
        catch (Exception $e) {
			if(!isset($this->logger)) $this->logger = new debug_logger(MP_LOG_DIR);
			$this->logger->log('statement','db',$e->getMessage(),$this->logger::LOG_MONTH);
			return false;
        }
	}

    /**
     * @param array $config
     * @param array $params
     * @return bool
     */
    public function update(array $config, array $params = []): bool {
		switch ($config['type']) {
			case 'order':
				$query = 'UPDATE mc_mainsectors 
						SET order_ms = :order_ms
						WHERE id_ms = :id_ms';
				break;
            default:
                return false;
        }

        try {
            component_routing_db::layer()->update($query,$params);
            return true;
        }
        catch (Exception $e) {
			if(!isset($this->logger)) $this->logger = new debug_logger(MP_LOG_DIR);
			$this->logger->log('statement','db',$e->getMessage(),$this->logger::LOG_MONTH);
			return false;
        }
	}

	/**
	 * @param array $config
	 * @param array $params
	 * @return bool
	 */
	public function delete(array $config, array $params = []): bool {
		switch ($config['type']) {
			case 'ms':
				$query = 'DELETE FROM mc_mainsectors
						WHERE id_ms = :id';
				break;
			default:
				return false;
		}

		try {
			component_routing_db::layer()->delete($query,$params);
			return true;
		}
		catch (Exception $e) {
			if(!isset($this->logger)) $this->logger = new debug_logger(MP_LOG_DIR);
			$this->logger->log('statement','db',$e->getMessage(),$this->logger::LOG_MONTH);
			return false;
		}
	}
}