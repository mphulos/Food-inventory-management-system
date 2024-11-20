<?php
/*
Product model
*/
class ProductModel
{
    private PDO $conn;
    
    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();
    }
    
    public function getAll(): array
    {
        $stored_procedure = "CALL getAllProducts()";                
        $stmt = $this->conn->query($stored_procedure);        
        $data = [];        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }        
        return $data;
    }
    
    public function create(array $data): string
    {
        $stored_procedure = "CALL insertProduct(:name, :fk_category_id, :picture, :price, :quantity, :expiration_date)";
        $stmt = $this->conn->prepare($stored_procedure);        
        $stmt->bindValue(":name", $data["name"], PDO::PARAM_STR);
        $stmt->bindValue(":fk_category_id", $data["fk_category_id"] ?? 5, PDO::PARAM_INT);
        $stmt->bindValue(":picture", $data["picture"], PDO::PARAM_STR);
        $stmt->bindValue(":quantity", $data["quantity"] ?? 0, PDO::PARAM_INT);
        $stmt->bindValue(":price", $data["price"] ?? 0, PDO::PARAM_STR);
        $stmt->bindValue(":expiration_date", $data["expiration_date"], PDO::PARAM_STR);            
        $stmt->execute();        
        return $this->conn->lastInsertId();
    }
    
    public function get(string $id): array | false
    {
        $stored_procedure = "CALL getProductById(:id)";                
        $stmt = $this->conn->prepare($stored_procedure);        
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);        
        $stmt->execute();        
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data;
    }

    public function update(array $current, array $new): int
    {
        $stored_procedure = "CALL updateProduct(:productId ,:productName, :categoryId, :productPrice, :productQuantity, :productExpiryDate )";
        $stmt = $this->conn->prepare($stored_procedure);
        $stmt->bindValue(":productName", $new["editProductName"] ?? $current["name"], PDO::PARAM_STR);
        $stmt->bindValue(":categoryId", $new["editCategoryId"] ?? $current["fk_category_id"], PDO::PARAM_INT);
        $stmt->bindValue(":productQuantity", $new["editQuantity"] ?? $current["quantity"], PDO::PARAM_INT);
        $stmt->bindValue(":productPrice", $new["editPrice"] ?? $current["price"], PDO::PARAM_STR);
        $stmt->bindValue(":productExpiryDate", $new["editExpirationDate"] ?? $current["expiration_date"], PDO::PARAM_STR);       
        $stmt->bindValue(":productId", $current["id"], PDO::PARAM_INT);       
        $stmt->execute();
        return $stmt->rowCount();
    }
    
    public function updateProductPicture(array $current, array $new): int
    {
        $stored_procedure = "CALL updateProductPicture(:productId, :productPicture)";
        $stmt = $this->conn->prepare($stored_procedure);
        $stmt->bindValue(":productPicture", $new["editProductPicture"] ?? $current["picture"], PDO::PARAM_STR);     
        $stmt->bindValue(":productId", $current["id"], PDO::PARAM_INT);       
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function delete(string $id)
    {       
        $stored_procedure = "CALL deleteProduct(:productId)";
        $stmt = $this->conn->prepare($stored_procedure);        
        $stmt->bindValue(":productId", $id, PDO::PARAM_INT);        
        $stmt->execute();
        $valid['success'] = array('success' => false, 'messages' => array());
        if($stmt->rowCount()) {
            $valid['success'] = true;
           $valid['messages'] = "Successfully Removed";		
        } else {
            $valid['success'] = false;
            $valid['messages'] = "Error while remove the product";
        }               
        return $valid;
    }

    public function search(string $search, int $fk_category_id): array 
    {
        $stored_procedure = "";
        if(trim($search) != "" && $fk_category_id > 0){
            $stored_procedure = "CALL getProductByNameAndCategory(:productName, :categoryId)";
            $stmt = $this->conn->prepare($stored_procedure);
            $stmt->bindValue(":productName", $search, PDO::PARAM_STR);
            $stmt->bindValue(":categoryId", $fk_category_id, PDO::PARAM_STR);
            $stmt->execute(); 
            $data = [];            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }                    
            return $data;
        }elseif(trim($search) != ""){
            $stored_procedure = "CALL getProductByName(:productName)";
            $stmt = $this->conn->prepare($stored_procedure);
            $stmt->bindValue(":productName", $search, PDO::PARAM_STR);
            $stmt->execute(); 
            $data = [];            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }                    
            return $data;
        }elseif($fk_category_id > 0){
            $stored_procedure = "CALL getProductByCategory(:productCategoryId)";
            $stmt = $this->conn->prepare($stored_procedure);
            $stmt->bindValue(":productCategoryId", $fk_category_id, PDO::PARAM_INT);
            $stmt->execute();
            $data = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        }else{
            $stored_procedure = "CALL getAllProducts()";                
            $stmt = $this->conn->query($stored_procedure);        
            $data = [];        
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }        
            return $data;
        }      
    } 
    
    function low_stock(){
        $stored_procedure = "CALL getProductsLowStock()";                
        $stmt = $this->conn->query($stored_procedure);        
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if($data != null){
            return $data['low_stock'];
        }else{
            return 0;
        }
    }

    public function stock_level_prices(): array | false
    {
        $stored_procedure = "CALL getStocklevelsAndPrice()";                
        $stmt = $this->conn->prepare($stored_procedure);        
        $stmt->execute();      

        $categories = [];
        $stockLevels = [];
        $prices = [];
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $categories[] = $row['category_name'];
            $stockLevels[] = $row['total_stock'];
            $prices[] = $row['avg_price'];
        }
        
        $data = [];
        $data["categories"] = $categories;
        $data["stockLevels"] = $stockLevels;
        $data["prices"] = $prices;

        return $data;
    }
}