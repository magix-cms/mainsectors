<?php
require_once ('db.php');
/*
 # -- BEGIN LICENSE BLOCK ----------------------------------
 #
 # This file is part of MAGIX CMS.
 # MAGIX CMS, The content management system optimized for users
 # Copyright (C) 2008 - 2023 magix-cms.com <support@magix-cms.com>
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
 #
 # You should have received a copy of the GNU General Public License
 # along with this program.  If not, see <http://www.gnu.org/licenses/>.
 #
 # -- END LICENSE BLOCK -----------------------------------
 #
 # DISCLAIMER
 #
 # Do not edit or add to this file if you wish to upgrade MAGIX CMS to newer
 # versions in the future. If you wish to customize MAGIX CMS for your
 # needs please refer to http://www.magix-cms.com for more information.
 */
 /**
 * MAGIX CMS
 * @category plugins
 * @package advantage
 * @copyright  MAGIX CMS Copyright (c) 2008 - 2015 Gerits Aurelien, http://www.magix-cms.com,  http://www.magix-cjquery.com
 * @license Dual licensed under the MIT or GPL Version 3 licenses.
 * @version 2.0
 * @author: Salvatore Di Salvo
 * @name plugins_mainsectors_admin
 */
class plugins_mainsectors_admin extends plugins_mainsectors_db {
	/**
	 * @var backend_model_template $template
	 * @var backend_model_data $data
	 * @var component_core_message $message
	 * @var backend_model_language $modelLanguage
	 * @var component_collections_language $collectionLanguage
	 * @var backend_model_setting $settings
	 */
	protected backend_model_template $template;
	protected backend_model_data $data;
	protected component_core_message $message;
	protected backend_model_language $modelLanguage;
	protected component_collections_language $collectionLanguage;
	protected backend_model_setting $settings;

	/**
	 * @var array $setting
	 */
	protected array $setting;

	/**
	 * @var integer $edit
	 * @var integer $id
	 */
	public int
		$edit,
		$id;

	/**
	 * @var string $action
	 * @var string $tabs
	 * @var string $content
	 * @var string $type_ms
	 */
	public string
		$action,
		$tabs,
		$content,
		$type_ms = 'page';

	/**
	 * @var array $page
	 */
	public array $page;

	public function __construct() {
		$this->template = new backend_model_template();
		$this->data = new backend_model_data($this);
		$this->message = new component_core_message($this->template);
		$this->modelLanguage = new backend_model_language($this->template);
		$this->collectionLanguage = new component_collections_language();
		$this->settings = new backend_model_setting();
		$this->setting = $this->settings->getSetting();

		$formClean = new form_inputEscape();

		if (http_request::isRequest('action')) $this->action = $formClean->simpleClean($_REQUEST['action']);

		// --- GET
		if (http_request::isGet('edit')) $this->edit = $formClean->numeric($_GET['edit']);
		if (http_request::isGet('tabs')) $this->tabs = $formClean->simpleClean($_GET['tabs']);
		if (http_request::isGet('content')) $this->content = $formClean->simpleClean($_GET['content']);

		// --- ADD or EDIT
		if (http_request::isPost('pages_id')) $this->id = intval($formClean->numeric($_POST['pages_id']));
		if (http_request::isPost('id')) $this->id = intval($formClean->simpleClean($_POST['id']));
		if (http_request::isPost('type_ms')) $this->type_ms = $formClean->simpleClean($_POST['type_ms']);

		// --- Order
		if (http_request::isPost('page')) $this->page = $formClean->arrayClean($_POST['page']);
	}

	/**
	 * Method to override the name of the plugin in the admin menu
	 * @return string
	 */
	public function getExtensionName(): string {
		return $this->template->getConfigVars('mainsectors_plugin');
	}

	/**
	 * @param array $pages
	 * @return array
	 */
	private function setPagesTree(array $pages): array {
		$childs = [];

		foreach($pages as &$item) {
			$k = $item['parent'] == null ? 'root' : $item['parent'];
			$childs[$k][] = &$item;
		}
		unset($item);

		foreach($pages as &$item) {
			if (isset($childs[$item['id']])) {
				$item['child'] = $childs[$item['id']];
			}
		}

		return $childs['root'] ?: [];
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
	 * Insert data
	 * @param array $config
	 */
	private function add(array $config) {
		switch ($config['type']) {
			case 'ms_p':
				parent::insert(
					['type' => $config['type']],
					$config['data']
				);
				break;
		}
	}

	/**
	 * Delete a record
	 * @param array $config
	 */
	private function del(array $config) {
		switch ($config['type']) {
			case 'ms':
				parent::delete(
					['type' => $config['type']],
					$config['data']
				);
				$this->message->json_post_response(true,'delete',array('id' => $this->id));
				break;
		}
	}

	/**
	 * Update order
	 */
	public function order() {
		$p = $this->page;
		for ($i = 0; $i < count($p); $i++) {
			parent::update(
				['type' => 'order'],
				[
					'id_ms'    => $p[$i],
					'order_ms' => $i
				]
			);
		}
	}

	/**
	 * Execute the plugin
	 */
	public function run() {
		if(isset($this->action)) {
			switch ($this->action) {
				case 'add':
					$this->add([
						'type' => 'ms_p',
						'data' => [
							'id' => $this->id,
							'type' => $this->type_ms
						]
					]);
					$defaultLanguage = $this->collectionLanguage->fetchData(['context'=>'one','type'=>'default']);
					$this->getItems('newMs',[
						'lang_p' => $defaultLanguage['id_lang'],
						'lang_c' => $defaultLanguage['id_lang']
					],'one','ms');
					$this->modelLanguage->getLanguage();
					$display = $this->template->fetch('loop/pages.tpl');
					$this->message->json_post_response(true,'add',$display);
					break;
				case 'delete':
					if(!empty($this->id)) {
						$this->del([
							'type' => 'ms',
							'data' => ['id' => $this->id]
						]);
					}
					break;
				case 'order':
					if (!empty($this->page)) {
						$this->order();
					}
					break;
				case 'get':
					if(!empty($this->content)) {
						$data = $this->getItems($this->content,null,'all',false);
						$pages = empty($data) ? [] : $this->setPagesTree($data);
						$this->template->assign('pages', $pages);
						$this->template->display('loop/page.tpl');
					}
					break;
			}
		}
		else {
			$this->modelLanguage->getLanguage();
			$defaultLanguage = $this->collectionLanguage->fetchData(['context'=>'one','type'=>'default']);
			$this->getItems('mss',[
				'lang_p' => $defaultLanguage['id_lang'],
				'lang_c' => $defaultLanguage['id_lang']
			],'all');
			$this->template->display('index.tpl');
		}
	}
}