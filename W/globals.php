<?php

// Espace de nom global
namespace {

	/**
	 * print_r/var_dump custom
	 * @param $var La variable a debugger
	 * @param $type Le type de sortie, print ou var_dump
	 */
	function debug($var, $type = 'print')
	{
		print '<pre class="debug" style="position:relative;z-index:1200;padding:10px;font-family:Consolas,Monospace;background-color:#000;color:#FFF;">';
		if($type == 'print'){
			print_r($var);
		}
		else{			
			var_dump($var);
		}
		print '</pre>';
	}

	/** 
	 *Alias de fonction 
	 * @param $var La variable a debugger
	 * @param $type Le type de sortie, print ou var_dump
	 */
	if (!function_exists('dump')){
		function dump($var, $type = 'dump')
		{
			debug($var, $type);
		}
	}

	/** 
	 * Alias de fonction 
	 * @param $var La variable a debugger
	 * @param $type Le type de sortie, print ou var_dump
	 */
	if (!function_exists('dd')){
		function dd($var, $type = 'dump')
		{
			debug($var, $type);
			die;
		}
	}

	/**
	 * Retourne l'instance de l'application depuis l'espace global
	 * @return \W\App L'application
	 */
	function getApp()
	{
		if (!empty($GLOBALS['app'])){
			return $GLOBALS['app'];
		}

		return null;
	}

}