<?php
class Database  extends PDO {
	private static 
	$db_host = 'localhost',
	$db_name = 'ping_pong',
	$db_user = 'root',
	$db_pass = '';
	
	public function __construct() {
		try	{
			parent::__construct('mysql:host='.self::$db_host.';dbname='.self::$db_name, self::$db_user, self::$db_pass);
		}
		catch (Exception $e) {
			die('Erreur : ' . $e->getMessage());
		}
	}	
}