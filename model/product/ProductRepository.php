<?php
class ProductRepository extends BaseRepository
{
    // Hàm lấy toàn bộ bản ghi phục vụ mục đích kiểm thử (Test Lab)
    // Gọi hàm này để lấy sạch sành sanh dữ liệu mà không cần truyền điều kiện
    public function getAllRecordsTest()
    {
        return $this->fetchAll(null, null, null);
    }

    protected function fetchAll($condition = null, $sort = null, $limit = null)
    {
        $products = array();

        $sql = "SELECT *, ROUND(IF(discount_percentage IS NULL || discount_from_date > CURRENT_DATE || discount_to_date < CURRENT_DATE , price, price * (1-discount_percentage/100)), -3) AS sale_price FROM product";
        if ($condition) {
            $sql .= " WHERE $condition"; 
        }

        if ($sort) {
            $sql .= " $sort";
        }

        if ($limit) {
            $sql .= " $limit";
        }

        $result = $this->conn->query($sql);

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $product = new Product(
                    $row["id"],
                    $row["name"],
                    $row["barcode"],
                    $row["sku"],
                    $row["price"],
                    $row["discount_percentage"],
                    $row["discount_from_date"],
                    $row["discount_to_date"],
                    $row["sale_price"],
                    $row["featured_image"],
                    $row["inventory_qty"],
                    $row["created_date"],
                    $row["description"],
                    $row["star"],
                    $row["featured"],
                    $row["category_id"],
                    $row["brand_id"]
                );
                $products[] = $product;
            }
        }

        return $products;
    }

    protected function fetchAllNumber($condition = null, $sort = null, $limit = null)
    {
        $products = array();

        $sql = "SELECT count(*) AS number FROM product";
        if ($condition) {
            $sql .= " WHERE $condition"; 
        }

        if ($sort) {
            $sql .= " $sort";
        }

        if ($limit) {
            $sql .= " $limit";
        }
        
        $result = $this->conn->query($sql);
        $row = $result->fetch_assoc();
        $number = $row['number'];
        
        return $number;
    }

    function getAll()
    {
        // Để test nhanh, bạn có thể chuyển hướng hàm getAll gốc gọi thẳng sang hàm lấy hết dữ liệu:
        return $this->getAllRecordsTest();
    }

    function getBy($array_conds = array(), $array_sorts = array(), $page = null, $qty_per_page = null)
    {
        if ($page) {
            $page_index = $page - 1;
        }

        $temp = array();
        foreach ($array_conds as $column => $cond) {
            $type = $cond['type'];
            $val = $cond['val'];
            $str = "$column $type ";
            if (in_array($type, array("BETWEEN", "LIKE"))) {
                $str .= "$val"; 
            } else {
                $str .= "'$val'";
            }
            $temp[] = $str;
        }
        $condition = null;

        if (count($array_conds)) {
            $condition = implode(" AND ", $temp);
        }

        $temp = array();
        foreach ($array_sorts as $key => $sort) {
            $temp[] = "$key $sort";
        }
        $sort = null;

        if (count($array_sorts)) {
            $sort = "ORDER BY " . implode(" , ", $temp);
        }

        $limit = null;
        if ($qty_per_page) {
            $start = $page_index * $qty_per_page;
            $limit = "LIMIT $start, $qty_per_page";
        }

        return $this->fetchAll($condition, $sort, $limit);
    }

    function getByNumber($array_conds = array(), $array_sorts = array(), $page = null, $qty_per_page = null)
    {
        if ($page) {
            $page_index = $page - 1;
        }

        $temp = array();
        foreach ($array_conds as $column => $cond) {
            $type = $cond['type'];
            $val = $cond['val'];
            $str = "$column $type ";
            if (in_array($type, array("BETWEEN", "LIKE"))) {
                $str .= "$val"; 
            } else {
                $str .= "'$val'";
            }
            $temp[] = $str;
        }
        $condition = null;

        if (count($array_conds)) {
            $condition = implode(" AND ", $temp);
        }

        $temp = array();
        foreach ($array_sorts as $key => $sort) {
            $temp[] = "$key $sort";
        }
        $sort = null;

        if (count($array_sorts)) {
            $sort = "ORDER BY " . implode(" , ", $temp);
        }

        $limit = null;
        if ($qty_per_page) {
            $start = $page_index * $qty_per_page;
            $limit = "LIMIT $start, $qty_per_page";
        }

        return $this->fetchAllNumber($condition, $sort, $limit);
    }

    function find($id)
    {
        $condition = "id = $id";
        $products = $this->fetchAll($condition);
        $product = current($products);
        return $product;
    }

    function findByBarcode($barcode)
    {
        $condition = "barcode = '$barcode'";
        $products = $this->fetchAll($condition);
        $product = current($products);
        return $product;
    }

    function save($data)
    {
        $name = $data["name"];
        $barcode = $data["barcode"];
        $sku = $data["sku"];
        $price = $data["price"];
        $discount_percentage = $data["discount_percentage"];
        $discount_from_date = $data["discount_from_date"];
        $discount_to_date = $data["discount_to_date"];
        $featured_image = $data["featured_image"];
        $inventory_qty = $data["inventory_qty"];
        $created_date = $data["created_date"];
        $description = $data["description"];
        $featured = $data["featured"];
        $category_id = $data["category_id"];
        $brand_id = $data["brand_id"];
        $sql = "INSERT INTO product (name, barcode,sku, price, discount_percentage, discount_from_date, discount_to_date, featured_image, inventory_qty, created_date, description, featured, category_id, brand_id) VALUES ('$name', '$barcode', '$sku' ,$price, '$discount_percentage', '$discount_from_date', '$discount_to_date',  '$featured_image', '$inventory_qty', '$created_date', '$description', '$featured', $category_id, $brand_id)";
        if ($this->conn->query($sql) === TRUE) {
            $last_id = $this->conn->insert_id; 
            return $last_id;
        }
        $this->error =  "Error: " . $sql . PHP_EOL . $this->conn->error;
        return false;
    }

    function update(Product $product)
    {
        $id = $product->getId();
        $name = $product->getName();
        $sku = $product->getSku();
        $barcode = $product->getBarcode();
        $price = $product->getPrice();
        $discount_percentage = $product->getDiscountPercentage();
        $discount_from_date = $product->getDiscountFromDate();
        $discount_to_date = $product->getDiscountToDate();
        $featured_image = $product->getFeaturedImage();
        $inventory_qty = $product->getInventoryQty();
        $created_date = $product->getCreatedDate();
        $description = $product->getDescription();
        $star = $product->getStar();
        $featured = $product->getFeatured();
        $category_id = $product->getCategoryId();
        $brand_id = $product->getBrandId();
        $sql = "UPDATE product SET name='$name', barcode='$barcode', sku='$sku', price=$price, discount_percentage=$discount_percentage, discount_from_date='$discount_from_date', discount_to_date='$discount_to_date', featured_image='$featured_image', inventory_qty='$inventory_qty', created_date='$created_date', description='$description', star='$star', featured='$featured', category_id=$category_id, brand_id=$brand_id WHERE id=$id";

        if ($this->conn->query($sql) === TRUE) {
            return true;
        }
        $this->error =  "Error: " . $sql . PHP_EOL . $this->conn->error;
        return false;
    }

    function delete(Product $product)
    {
        $id = $product->getId();
        $sql = "DELETE FROM product WHERE id=$id";
        if ($this->conn->query($sql) === TRUE) 	{
            return true;
        }
        $this->error =  "Error: " . $sql . PHP_EOL . $this->conn->error;
        return false;
    }

    function getByPattern($pattern)
    {
        $condition = "name like '%$pattern%'";
        return $this->fetchAll($condition);
    }
}