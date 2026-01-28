<?php 
class BaseRepository {
	protected $conn;
	protected $error;

	function __construct() {
		global $conn;
		$this->conn = $conn;
	}

	function getError() {
		return $this->error;
	}
}
?>