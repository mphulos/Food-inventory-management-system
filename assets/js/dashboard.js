var url = "http://localhost/inventory/api";
var API_KEY = "inventoryTest123xxx"; //TO BE MOVED TO .env for security

$(document).ready(function() {
    setProductsNumber();
    setLowStockNumber()
});

function setProductsNumber(){
    var settings = {
        "url": url+"/products/",
        "method": "GET",
        "timeout": 0,
        "headers": {
          "x-api-key": API_KEY
        },
    };      
	$.ajax(settings).done(function (response) {
        response = JSON.parse(response);
		let length =  response.length;
        $("#number_of_product").text(length);
	})
}

function setLowStockNumber(){
    var settings = {
        "url": url+"/low_stock/",
        "method": "POST",
        "data":{low_stock:1},
        "timeout": 0,
        "headers": {
          "x-api-key": API_KEY
        },
    };      
	$.ajax(settings).done(function (response) {
        $("#low_stock").text(response);
	})
}
