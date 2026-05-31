<?php
class CategoryRepository extends BaseRepository{
	
	protected function fetchAll($condition = null)
	{
		// Dùng $this->conn kế thừa từ BaseRepository
		$categories = array();
		$sql = "SELECT * FROM category";
		if ($condition) 
		{
			$sql .= " WHERE  $condition";//SELECT * FROM category WHERE id =1
		}

		$result = $this->conn->query($sql);

		if ($result->num_rows > 0) 
		{
			while ($row = $result->fetch_assoc()) 
			{
				$category = new Category($row["id"], $row["name"]);
				$categories[] = $category;
			}
		}
		return $categories;
	}

	function getAll() {
		return $this->fetchAll();
	}

	function find($id) {
		// Dùng fetchAll của chính class này (đã override)
		$condition = "id = $id";
		$categories = $this->fetchAll($condition);
		$category = current($categories);
		return $category;
	}

	function save($data) {
		$name = $data["name"];
		$sql = "INSERT INTO category (name) VALUES ('$name')";
		if ($this->conn->query($sql) === TRUE) {
			$last_id = $this->conn->insert_id;//chỉ cho auto increment
		    return $last_id;
		} 
		$this->error = "Error: " . $sql . PHP_EOL . $this->conn->error;
		return false;
	}

	function update($category) {
		$name = $category->getName();
		$id = $category->getId();
		$sql = "UPDATE category SET name='$name' WHERE id=$id";

		if ($this->conn->query($sql) === TRUE) {
		    return true;
		} 
		$this->error = "Error: " . $sql . PHP_EOL . $this->conn->error;
		return false;
	}

	function delete($category) {
		$id = $category->getId();
		$sql = "DELETE FROM category WHERE id=$id";
		if ($this->conn->query($sql) === TRUE) {
		    return true;
		} 
		$this->error = "Error: " . $sql . PHP_EOL . $this->conn->error;
		return false;
	}
}