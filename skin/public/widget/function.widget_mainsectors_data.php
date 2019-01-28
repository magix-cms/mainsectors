<?php
function smarty_function_widget_mainsectors_data($params, $template){
	$modelSystem = new frontend_model_core();
    $collection = new plugins_mainsectors_public();

	$current = $modelSystem->setCurrentId();
    $template->assign('mss',$collection->getMss($current));;
}