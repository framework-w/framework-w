<?php

namespace W\Controller;

use W\Security\AuthentificationModel;
use W\Security\AuthorizationModel;

/**
 * Le contrôleur de base à étendre
 */
class Controller 
{

	/**
	 * Constante du chemin du dossier des vues
	 */
	const PATH_VIEWS = '../app/Views';


	/**
	 * Permet l'ajout de données additionnelles aux templates
	 */
	protected $addDataViews = [];

	/**
	 * Génère l'URL correspondant à une route nommée
	 * @param  string $routeName Le nom de route
	 * @param  mixed  $params    Tableau de paramètres optionnel de cette route
	 * @param  boolean $absolute Retourne une url absolue si true (relative si false)
	 * @return L'URL correspondant à la route
	 */
	public static function generateUrl($routeName, $params = array(), $absolute = false)
	{
		$params = (empty($params)) ? array() : $params;

		$app = getApp();
		$router = $app->getRouter();
		$routeUrl = $router->generate($routeName, $params);
		$url = $routeUrl;
		if($absolute){
			// Définit le protocol
			$baseUrl = 'http';
			if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
				$baseUrl.= 's';
			}
			elseif(isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https'){
				$baseUrl.= 's';
			}
			$baseUrl.= '://'.$_SERVER['HTTP_HOST'];

			// On récupère le port si existant
			if(isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] != '80') {
				$baseUrl.= ':'. (int) $_SERVER['SERVER_PORT'];
			}

			$url = $baseUrl . $routeUrl;
		}
		return $url;
	}

	/**
	 * Redirige vers une URI
	 * @param  string $uri URI vers laquelle rediriger
	 */
	public function redirect($uri)
	{
		header("Location: $uri");
		die();	
	}

	/**
	 * Redirige vers une route nommée
	 * @param  string $routeName Le nom de route vers laquelle rediriger
	 * @param  array  $params    Tableau de paramètres optionnel de cette route
	 */
	public function redirectToRoute($routeName, array $params = array())
	{
		$uri = $this->generateUrl($routeName, $params);
		$this->redirect($uri);
	}

	/** 
	 * Affiche un flash message
	 * @param string $message Le message que l'on souhaite afficher
	 * @param string $level Le type de message flash (default, notice, info, success, danger, warning)
	 */
	public function flash($message, $level = 'info'){

		$allowLevel = ['default', 'notice', 'info', 'success', 'danger', 'warning'];

		if(!in_array($level, $allowLevel)){
			$level = 'info';
		}

		$_SESSION['flash'][] = [
			'message' 	=> (!isset($message) || empty($message)) ? 'No message defined' : ucfirst($message),
			'level'	 	=> $level,
		];

		return;
	}

	/**
	 * Alias de méthode pour ressembler
	 * @info Paramètres inversés
	 * @param string $level Le type de message flash (default, notice, info, success, danger, warning)
	 * @param string $message Le message que l'on souhaite afficher
	 */
	public function addFlash($level = 'info', $message = '')
	{
		$this->flash($message, $level);
	}


	/**
	 * Affiche un template
	 * @param string $file Chemin vers le template, relatif à app/Views/
	 * @param array  $data Données à rendre disponibles à la vue
	 */
	public function show($file, array $data = array())
	{
		//incluant le chemin vers nos vues
		$engine = new \League\Plates\Engine(self::PATH_VIEWS);

		//charge nos extensions (nos fonctions personnalisées)
		$engine->loadExtension(new \W\View\Plates\PlatesExtensions());

		// le flash message
		$flash_message = (isset($_SESSION['flash']) && !empty($_SESSION['flash'])) ? $_SESSION['flash'] : null;

		// 
		$app = getApp();		

		// Rend certaines données disponibles à tous les vues
		// accessible avec $w_user & $w_current_route dans les fichiers de vue
		$engine->addData(
			[
				'w_user' 		  => $this->getUser(),
				'w_current_route' => $app->getCurrentRoute(),
				'w_site_name'	  => $app->getConfig('site_name'),
				'w_flash_message' => $flash_message,
			]
		);

		// Ajoute des données additionnelles à tous les templates
		if(!empty($this->addDataViews) && is_array($this->addDataViews)){
			$engine->addData($this->addDataViews);
		}

		// Retire l'éventuelle extension .php
		$file = str_replace('.php', '', $file);

		// Affiche le template
		echo $engine->render($file, $data);
		
		// Supprime les messages flash pour qu'ils n'apparaissent qu'une fois
		if(isset($_SESSION['flash'])) {
			unset($_SESSION['flash']);
		}
		die();
	}

	/**
	 * Alias de méthode
	 */
	public function render($file, array $data = array())
	{
		$this->show($file, $data);
	}

	/**
	 * Affiche une page 403
	 */
	public function showForbidden($error_message = null)
	{
		header('HTTP/1.0 403 Forbidden');

		$file = self::PATH_VIEWS.'/w_errors/403.php';
		if(file_exists($file)){
			$this->show('w_errors/403', ['error_message' => $error_message ?? '']);
		}
		else {
			die('403');
		}
	}

	/**
	 * Alias de fonction 
	 */
	public function httpForbidden($error_message = null)
	{
		$this->showForbidden($error_message);
	}

	/**
	 * Affiche une page 404
	 */
	public function showNotFound($error_message = null)
	{
		header('HTTP/1.0 404 Not Found');

		$file = self::PATH_VIEWS.'/w_errors/404.php';
		if(file_exists($file)){
			$this->show('w_errors/404', ['error_message' => $error_message ?? '']);
		}
		else {
			die('404');
		}
	}

	/**
	 * Alias de fonction 
	 */
	public function httpNotFound($error_message = null)
	{
		$this->showNotFound($error_message);
	}


	/**
	 * Récupère l'utilisateur actuellement connecté
	 */
	public function getUser()
	{
		$authenticationModel = new AuthentificationModel();
		$user = $authenticationModel->getLoggedUser();
		return $user;
	}

	/**
	 * Autorise l'accès à un ou plusieurs rôles
	 * @param mixed $roles Tableau de rôles, ou chaîne pour un seul
	 * @param $route_to_redirect La route de redirection
	 * @param $session_key La clé de la session
	 * @param $error_message Le message d'erreur à transmettre à la vue
	 */
	public function allowTo($roles, $route_to_redirect = null, $session_key = 'user', $error_message = null)
	{
		if (!is_array($roles)){
			$roles = [$roles];
		}
		$authorizationModel = new AuthorizationModel($session_key);
		foreach($roles as $role){
			if ($authorizationModel->isGranted($role, $route_to_redirect)){
				return true;
			}
		}

		$this->showForbidden($error_message);
	}


	/**
	 * Retourne une réponse JSON au client
	 * @param mixed $data Les données à retourner
	 * @return les données au format json
	 */
	public function showJson($data)
	{
		header('Content-type: application/json');
		$json = json_encode($data, JSON_PRETTY_PRINT);
		if($json){
			die($json);
		}
		else {
			die('Error in json encoding');
		}
	}

	/**
	 * Alias de méthode
	 */
	public function json($data)
	{
		$this->showJson($data);
	}


	/**
	 * Retourne l'URL relative d'un asset
	 * @param string $path Le chemin vers le fichier, relatif à public/assets/
	 * @return string L'URL relative vers le fichier
	 */
	public static function assetUrl($path)
	{
		$app = getApp();
		return $app->getBasePath() . '/assets/' . ltrim($path, '/');
	}

}
