<?php
class Model {
	protected $table_name;
	protected static $bdd;

	protected function __construct($table_name) {
		global $bdd;
		self::$bdd = $bdd;
		$this->table_name = $table_name;
	}

	public function getAll() {
		$req = self::$bdd->query("SELECT * FROM $this->table_name ORDER BY id");
		return $req;
	}

	protected function requestDb($id) {
		$req = self::$bdd->prepare("SELECT * FROM $this->table_name WHERE id = ?");
		$req->execute(array($id));
		$data = $req->fetch();
		$req->closeCursor();
		return $data;
	}

	public function insertIntoDb($args) {
		$q_args = array();
		foreach ($args as $a) {
			$q_args[] = '?';
		}
		$str = implode(', ', $q_args);
		$req = self::$bdd->prepare("INSERT INTO $this->table_name VALUES('', $str)");
		$req->execute($args);
		return self::$bdd->lastInsertId();
	}

	public function saveToDb($id, $cols, $args) {
		$q_args = array();
		foreach ($cols as $c) {
			$q_args[] = $c.'=?';
		}
		$str = implode(', ', $q_args);
		array_push($args, $id);
		$req = self::$bdd->prepare("UPDATE $this->table_name SET $str WHERE id=?");
		$req->execute($args);
	}

	public function removeFromDb($id) {
		$req = self::$bdd->prepare("DELETE FROM $this->table_name WHERE id = ?");
		$req->execute(array($id));
	}
}