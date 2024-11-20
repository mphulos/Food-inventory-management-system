<?php

declare(strict_types=1);

spl_autoload_register(function ($class) {
    require __DIR__ . "/src/$class.php";
});

set_error_handler("ErrorHandler::handleError");
set_exception_handler("ErrorHandler::handleException");
header("Content-type: text/html; charset=UTF-8");

$request_key = $_SERVER["HTTP_X_API_KEY"];

//API Secret keys
//Can be in a database
$secret_api_key = array("inventoryTest123xxx","inventoryTest1234xxx");

if(!in_array($request_key,$secret_api_key)){
    http_response_code(403); // invaild access key
    exit;
}
;
$parts = explode("/", $_SERVER["REQUEST_URI"]);
//Allowed 
$available_endpoints = array("products", "product_search", "categories", "low_stock", "stock_level_prices");
if (!in_array($parts[3],$available_endpoints)) {
    http_response_code(404);
    exit;
}

$id = $parts[4] ?? null;
$database = new Database("localhost", "inventory", "root", "");
$controller = new stdClass();  
   
if(in_array($parts[3],["products", "product_search", "low_stock", "stock_level_prices"])){
    $productObject = new ProductModel($database);
    $controller = new ProductController($productObject);
}elseif($parts[3] == "categories"){
    $categoryObject = new CategoryModel($database);
    $controller = new CategoryController($categoryObject);
}

$controller->processRequest($_SERVER["REQUEST_METHOD"], $id);