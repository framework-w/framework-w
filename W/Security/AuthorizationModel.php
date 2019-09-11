<?php

namespace W\Security;

use \W\Security\AuthentificationModel;

/**
 * Gère l'accès aux pages en fonction des droits utilisateurs
 */
class AuthorizationModel
{
	/**
	 * @var string La clé de session
	 */ 
	private $session_key;

	/**
	 * Constructeur
	 * @param string session_key 

	 */
	public function __construct(string $session_key = 'user')
	{
		$this->session_key = $session_key;
	}

	/**
	 * Vérifie les droits d'accès de l'utilisateur en fonction de son rôle
	 * @param  string  	$role Le rôle pour lequel on souhaite vérifier les droits d'accès
	 * @return boolean 	true si droit d'accès, false sinon
	 */
	public function isGranted($role, $route_to_redirect = null)
	{
		$app = getApp();
		$roleProperty = $app->getConfig('security_role_property');

		//récupère les données en session sur l'utilisateur
		$authentificationModel = new AuthentificationModel($this->session_key);
		$loggedUser = $authentificationModel->getLoggedUser();

		// Si utilisateur non connecté
		if (!$loggedUser){
			// Redirige vers le login
			if(!empty($route_to_redirect)){
				$this->redirectToRoute($route_to_redirect);
			}
			else {
				$this->redirectToLogin();
			}
		}

		if (!empty($loggedUser[$roleProperty]) && $loggedUser[$roleProperty] === $role){
			return true;
		}

		return false;
	}

	/**
	 * Redirige vers la page choisie
	 */
	public function redirectToRoute($route_name = '')
	{
		$controller = new \W\Controller\Controller();
		$controller->redirectToRoute($route_name);
	}

	/**
	 * Redirige vers la page login
	 */
	public function redirectToLogin()
	{
		$app = getApp();
		$this->redirectToRoute($app->getConfig('security_login_route_name'));
	}

}