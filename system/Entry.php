<?php
class Entry  extends Model {
	public function __construct($id, $table_name) {
		parent::__construct($table_name);
		if ($id != 0) {
    		$data = $this->requestDb($id);
    		foreach ($data as $key => $value) {
    			if (!is_numeric($key) ) {
    				$this->$key = $value;
    			}
    		}
		}
	}

	public function save() {
	    if (isset($this->id)) {
	    	$attr = get_object_vars($this);
    		unset($attr['id'], $attr['table_name']);
    		$cols = array_keys($attr);
    		$args = array_values($attr);
    		$this->saveToDb($this->id, $cols, $args);
	    }
	}

	public function delete() {
	    if (isset($this->id)) {
    		$this->removeFromDb($this->id);
    		$this->__destruct();
	    }
	}

	public function __destruct(){}
}