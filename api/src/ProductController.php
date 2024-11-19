<?php
/*
Product controller
*/
class ProductController
{
    public function __construct(private ProductModel $productObject)
    {
    }
    
    public function processRequest(string $method, ?string $id): void
    {
        if ($id) {            
            $this->processResourceRequest($method, $id);            
        } else {            
            $this->processCollectionRequest($method);            
        }
    }
    
    private function processResourceRequest(string $method, string $id): void
    {
        $product = $this->productObject->get($id);
        
        if ( ! $product) {
            http_response_code(404);
            echo json_encode(["message" => "Product not found"]);
            return;
        }
        
        switch ($method) {
            case "GET":
                echo json_encode($product);
            break;

            case "POST":
                $data = $_POST;                
                $errors = $this->getValidationErrors($data, false);                
                if ( ! empty($errors)) {
                    http_response_code(422);
                    echo json_encode(["errors" => $errors]);
                    break;
                }                
                $rows = $this->productObject->update($product, $data);                
                echo json_encode([
                    "message" => "Product $id updated",
                    "rows" => $rows
                ]);
            break;

            case "PATCH":
                $data = $_POST;                
                $errors = $this->getValidationErrors($data, false);                
                if ( ! empty($errors)) {
                    http_response_code(422);
                    echo json_encode(["errors" => $errors]);
                    break;
                }                
                $rows = $this->productObject->update($product, $data);                
                echo json_encode([
                    "message" => "Product $id updated",
                    "rows" => $rows
                ]);
            break;
            
            case "DELETE":
                $message = $this->productObject->delete($id);                
                echo json_encode($message);
            break;
                
            default:
                http_response_code(405);
                header("Allow: GET, POST, DELETE");
        }
    }
    
    private function processCollectionRequest(string $method): void
    {
        switch ($method) {
            case "GET":
                $results = $this->productObject->getAll();
                $html = '<table class="table">
                            <thead>
                                <tr>
                                    <th style="width:15%;">Picture</th>							
                                    <th>Product Name</th>
                                    <th>Quantity</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Expiration date</th>
                                    <th style="width:17%;"></th>
                                </tr>
                            </thead>
                            <tbody>';
                
                foreach($results AS $result){
                   $html .= '<tr>
                                <td><img src="assets/images/stock/'.$result["picture"].'" width="50px"/></td>
                                <td><input type="checkbox" id="product'.$result["id"].'" name="select_product" value="'.$result["id"].'">
                                    '.$result["name"].'</td>
                                <td>'.$result["quantity"].'</td>
                                <td>'.$result["category"].'</td>
                                <td>'.$result["price"].'</td>
                                <td>'.$result["expiration_date"].'</td>
                                <td>
                                    <a class="btn btn-primary" type="button" data-toggle="modal" id="editProductModalBtn" data-target="#editProductModal" onclick="editProduct('.$result["id"].')"> <i class="glyphicon glyphicon-edit"></i> Edit</a>
                                    <a class="btn btn-danger" type="button" data-toggle="modal" data-target="#removeProductModal" id="removeProductModalBtn" onclick="removeProduct('.$result["id"].')"> <i class="glyphicon glyphicon-trash"></i> Remove</a>    
                                </td>
                            </tr>';
                }

                $html .= '</tbody>
                        </table>';
                echo $html;
            break;
                
            case "POST":
                $data =  $_POST;            
                if(isset($data["search"]) || isset($data["search_category_id"])){
                    if(!isset($data["search_category_id"])){
                        $data["search_category_id"] = 0;
                    }
                    if(!isset($data["search"])){
                        $data["search"] = "";
                    }
                    $results =  $this->productObject->search($data["search"], $data["search_category_id"]);

                    $html = '<table class="table">
                                <thead>
                                    <tr>
                                        <th style="width:15%;">Picture</th>							
                                        <th>Product Name</th>
                                        <th>Quantity</th>
                                        <th>Category</th>
                                        <th>Price</th>
                                        <th>Expiration date</th>
                                        <th style="width:17%;"></th>
                                    </tr>
                                </thead>
                                <tbody>';
                    
                    foreach($results AS $result){
                    $html .= '<tr>
                                    <td><img src="assets/images/stock/'.$result["picture"].'" width="50px"/></td>
                                    <td><input type="checkbox" id="product'.$result["id"].'" name="select_product" value="'.$result["id"].'">
                                    '.$result["name"].'</td>
                                    <td>'.$result["quantity"].'</td>
                                    <td>'.$result["category"].'</td>
                                    <td>'.$result["price"].'</td>
                                    <td>'.$result["expiration_date"].'</td>
                                    <td>
                                        <a class="btn btn-primary" type="button" data-toggle="modal" id="editProductModalBtn" data-target="#editProductModal" onclick="editProduct('.$result["id"].')"> <i class="glyphicon glyphicon-edit"></i> Edit</a>
                                        <a class="btn btn-danger" type="button" data-toggle="modal" data-target="#removeProductModal" id="removeProductModalBtn" onclick="removeProduct('.$result["id"].')"> <i class="glyphicon glyphicon-trash"></i> Remove</a>    
                                    </td>
                                </tr>';
                    }

                    $html .= '</tbody>
                            </table>';                    
                    echo $html;
                    if(!sizeof($results)){
                        echo "<p>No matching product found</p>";
                    }

                }else{
                    $data["picture"] = "photo_default.png";//$_FILES["picture"]["name"];                 
                    $errors = $this->getValidationErrors($data);                    
                    if ( ! empty($errors)) {
                        http_response_code(422);
                        echo json_encode(["errors" => $errors]);
                        break;
                    }                    
                    $this->productObject->create($data);                    
                    http_response_code(201);
                    echo json_encode([
                        "message" => "Product created"
                    ]);
                }                       
            break;  

            default:
                http_response_code(405);
                header("Allow: GET, POST");
        }
    }
    
    private function getValidationErrors(array $data, bool $is_new = true): array
    {
        $errors = [];        
        if ($is_new && empty($data["name"])) {
            $errors[] = "name is required";
        }
        return $errors;
    }
}