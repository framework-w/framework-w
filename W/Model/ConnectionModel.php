<?php

namespace W\Model;

use \PDO;
use \PDOException;

/**
 * Gère la connexion à la base de données (Singleton Pattern)
 */
class ConnectionModel
{

	private static $dbh;

	/**
	 * Crée une connexion ou la retourne si présente
	 */
	public static function getDbh()
	{
		if(!self::$dbh){
			self::setNewDbh();
		}
		return self::$dbh;
	}

	/**
	 * Crée une nouvelle connexion à la base
	 */
	public static function setNewDbh()
	{
		$app = getApp();
		
		try {


			 // for retrocompatibility
			$db_charset = 'SET NAMES ';
			$db_charset.= $app->getConfig('db_charset') ?? 'utf8';

			$db_port = $app->getConfig('db_port') ?? 3306;


			//connexion à la base avec la classe PDO et le DSN
			self::$dbh = new PDO('mysql:host='.$app->getConfig('db_host').';dbname='.$app->getConfig('db_name').';port='.$db_port, $app->getConfig('db_user'), $app->getConfig('db_pass'), array(
				PDO::MYSQL_ATTR_INIT_COMMAND => $db_charset, 		//on définit le charset
				PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, 	//on récupère nos données en array associatif par défaut
				PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING         	//on affiche les erreurs. 
			));
		} catch (PDOException $e) { //attrappe les éventuelles erreurs de connexion
			echo 'Erreur de connexion : ' . $e->getMessage();
		}
	}

}