<?php

function display($item){
	if( is_string($item) ){
		return htmlspecialchars($item);
	}
	elseif( is_array($item) ){
		return '<pre>' . print_r($item, true) . '</pre>';
	}
}
