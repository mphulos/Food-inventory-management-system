# Food-inventory-management-system
Food inventory management system built in PHP

API endpoints
Request Headers(For all API requests)
x-api-key: inventoryTest123xxx (ONLY FOR DEMO PURPOSE)

GET - Get all products
http://localhost/inventory/api/products/

GET - Get product by id
http://localhost/inventory/api/products/1{id}

DELETE - Delete product
http://localhost/inventory/api/products/1{id}

POST - Update product
http://localhost/inventory/api/products/14{id}
Body-form-data
editProductName:Updated
editCategoryId:4
editQuantity:44
editPrice:444.00
editExpirationDate:2024-02-02

POST - Create product
http://localhost/inventory/api/products/
Body - form-data
name:New product
fk_category_id:1
price:200.60
quantity:6
expiration_date:2024-12-12

POST - Products search
http://localhost/inventory/api/product_search
Body - form-data
search:item
search_category_id:1

POST - low stock
http://localhost/inventory/api/low_stock
Body - form-data
low_stock:1

POST - GET Stock levels and price by category
http://localhost/inventory/api/stock_level_prices
Body - form-data
low_stock: 1
