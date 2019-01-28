<?php
/*
 # -- BEGIN LICENSE BLOCK ----------------------------------
 #
 # This file is part of MAGIX CMS.
 # MAGIX CMS, The content management system optimized for users
 # Copyright (C) 2008 - 2013 magix-cms.com <support@magix-cms.com>
 #
 # OFFICIAL TEAM :
 #
 #   * Gerits Aurelien (Author - Developer) <aurelien@magix-cms.com> <contact@aurelien-gerits.be>
 #
 # Redistributions of files must retain the above copyright notice.
 # This program is free software: you can redistribute it and/or modify
 # it under the terms of the GNU General Public License as published by
 # the Free Software Foundation, either version 3 of the License, or
 # (at your option) any later version.
 #
 # This program is distributed in the hope that it will be useful,
 # but WITHOUT ANY WARRANTY; without even the implied warranty of
 # MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 # GNU General Public License for more details.

 # You should have received a copy of the GNU General Public License
 # along with this program.  If not, see <http://www.gnu.org/licenses/>.
 #
 # -- END LICENSE BLOCK -----------------------------------

 # DISCLAIMER

 # Do not edit or add to this file if you wish to upgrade MAGIX CMS to newer
 # versions in the future. If you wish to customize MAGIX CMS for your
 # needs please refer to http://www.magix-cms.com for more information.
 */
 /**
 * MAGIX CMS
 * @category   advantage
 * @package    plugins
 * @copyright  MAGIX CMS Copyright (c) 2008 - 2015 Gerits Aurelien,
 * http://www.magix-cms.com,  http://www.magix-cjquery.com
 * @license    Dual licensed under the MIT or GPL Version 3 licenses.
 * @version    2.0
 * Author: Salvatore Di Salvo
 * Date: 17-12-15
 * Time: 10:38
 * @name plugins_advantage_public
 * Le plugin advantage
 */
class plugins_mainsectors_public extends plugins_mainsectors_db{
    /**
     * @var frontend_model_template
     */
    protected $template, $data, $lang, $modelCatalog, $dbCatalog, $modelPage, $dbPage;
    /**
     * Class constructor
     */
    public function __construct(){
        $this->template = new frontend_model_template();
		$this->data = new frontend_model_data($this);
		$this->lang = $this->template->currentLanguage();
		$this->modelCatalog = new frontend_model_catalog($this->template);
		$this->dbCatalog = new frontend_db_catalog();
		$this->modelPage = new frontend_model_pages($this->template);
		$this->dbPage = new frontend_db_pages();
	}

	/**
	 * Assign data to the defined variable or return the data
	 * @param string $type
	 * @param string|int|null $id
	 * @param string $context
	 * @param boolean $assign
	 * @return mixed
	 */
	private function getItems($type, $id = null, $context = null, $assign = true) {
		return $this->data->getItems($type, $id, $context, $assign);
	}

	/**
	 * @param array $params
	 * @return array
	 */
    public function getMss($current){
		$pages = $this->getItems('homeMsp',null,'one',false);
		$cats = $this->getItems('homeMsc',null,'one',false);
		$order = $this->getItems('order',null,'all',false);

		// *** Get and parse pages
		if($pages and $pages['ids'] !== null) {
			$data = $this->modelPage->getData(array(
				'context' => 'one',
				'select' => explode(',',$pages['ids'])
			),$current);
			$msp = $this->data->parseData($data,$this->modelPage,$current);
			$page_arr = array();
			foreach ($msp as $page) {
				$page_arr[$page['id']] = $page;
			}
		}

		// *** Get and parse categories
		if($cats and $cats['ids'] !== null) {
			$data = $this->modelCatalog->getData(array(
				'context' => 'category',
				'select' => explode(',',$cats['ids'])
			),$current);
			$msc = $this->data->parseData($data,$this->modelCatalog,$current);
			$cat_arr = array();
			foreach ($msc as $page) {
				$cat_arr[$page['id']] = $page;
			}
		}

		// *** Mix pages and categories to fit the sectors order
		$arr = array();
		foreach ($order as $item) {
			$ref = array();
			switch ($item['type_ms']) {
				case 'page': $ref = $page_arr; break;
				case 'category': $ref = $cat_arr; break;
			}
			$arr[] = $ref[$item['id_page']];
		}
		return $arr;
    }
}