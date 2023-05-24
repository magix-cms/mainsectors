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
    protected $template, $data, $lang;
    /**
     * Class constructor
     */
    public function __construct($t = null){
        $this->template = $t instanceof frontend_model_template ? $t : new frontend_model_template();
		$this->data = new frontend_model_data($this,$this->template);
		$this->lang = $this->template->lang;
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

    private function setPagesData(array $rawData): array {
        $hc = [];
        if (!empty($rawData)) {
            foreach ($rawData as $key => $value) {
                if (isset($value['id_ms'])) {
                    $hc[$key]['id_ms'] = $value['id_ms'];
                }
            }
        }
        return $hc;
    }
    /**
     * @param array $data
     * @return array
     */
    public function extendListPages(array $data): array {
        return $this->setPagesData($data);
    }
    /**
     * @param array $filter
     * @return array
     */
    public function getPagesList(array $filter = []): array {
        if(http_request::isGet('controller')) $this->controller = form_inputEscape::simpleClean($_GET['controller']);
        $extend = [];
        if(!isset($this->controller)) {
            $hcs = $this->getItems('homeMsp',NULL,'one', false);
            if (!empty($hcs) && isset($hcs['ids'])) {
                /*$extend['extendQueryParams'] = [
                    'select' => [
                        'msp.id_ms'
                    ],
                    'join' => [
                        ['type' => 'LEFT JOIN',
                            'table' => 'mc_mainsectors',
                            'as' => 'msp',
                            'on' => [
                                'table' => 'p',
                                'key' => 'id_pages',
                                'newkey' => 'id_page'
                            ]
                        ]

                    ],
                    'where' => [
                        [
                            'type' => 'AND',
                            'condition' => 'p.id_pages IN (' . $hcs['ids'] . ')'
                        ]
                    ],
                    'order' => [
                        'hc.order_hc ASC'
                    ]
                ];*/
                /*$extend['extendQueryParams']['filter'] = [[
                    'type' => 'AND',
                    'condition' => 'p.id_pages IN (' . $hcs['ids'] . ')'
                ]];*/
                //AND p.id_product IN ('.$ids['listids'].')
                $extend['newRow'] = ['mainsector' => 'mainsector'];
                $extend['collection'] = 'mainsector';
                $extend['type'] = 'tree';
                //print_r($extend);
            }
        }
        return $extend;
    }
    /**
     * @param array $data
     * @return array
     */
    public function extendListCategory(array $data): array {
        return $this->setPagesData($data);
    }
    /**
     * @param array $filter
     * @return array
     */
    public function getCategoryList(array $filter = []): array {
        if(http_request::isGet('controller')) $this->controller = form_inputEscape::simpleClean($_GET['controller']);
        $extend = [];
        if(!isset($this->controller)) {
            $hcs = $this->getItems('homeMsc',NULL,'one', false);
            if (!empty($hcs) && isset($hcs['ids'])) {
                $extend['extendQueryParams'] = [
                    'select' => [
                        'msc.id_ms'
                    ],
                    'join' => [
                        ['type' => 'LEFT JOIN',
                            'table' => 'mc_mainsectors',
                            'as' => 'msc',
                            'on' => [
                                'table' => 'cat',
                                'key' => 'id_cat',
                                'newkey' => 'id_page'
                            ]
                        ]

                    ],
                    'where' => [
                        [
                            'type' => 'AND',
                            'condition' => 'cat.id_cat IN (' . $hcs['ids'] . ')'
                        ]
                    ],/*
                    'order' => [
                        'hc.order_hc ASC'
                    ]*/
                ];
                /*$extend['extendQueryParams']['filter'] = [[
                    'type' => 'AND',
                    'condition' => 'p.id_pages IN (' . $hcs['ids'] . ')'
                ]];*/
                //AND p.id_product IN ('.$ids['listids'].')
                $extend['newRow'] = ['mainsector' => 'mainsector'];
                $extend['collection'] = 'mainsector';
                $extend['type'] = 'tree';
                //print_r($extend);
            }
        }
        return $extend;
    }
    public function getMss(){
        $order = $this->getItems('order',null,'all',false);
        // *** Mix pages and categories to fit the sectors order
        $arr = [];
        foreach ($order as $item) {
            $ref = [];
            switch ($item['type_ms']) {
                case 'page':
                    $page = new frontend_controller_pages();
                    $ref = $page->getPagesList();
                    break;
                case 'category': //$ref = $cat_arr; break;
                    $cat = new frontend_controller_catalog();
                    $ref = $cat->getCategoryList();
                    break;
            }
            $arr[] = $ref[$item['id_page']];
        }
        return $arr;
    }
}