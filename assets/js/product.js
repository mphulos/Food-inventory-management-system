var url = "http://localhost/inventory/api";
var API_KEY = "inventoryTest123xxx"; //TO BE MOVED TO .env for security

$(document).ready(function() {

    //Load the API key from environment variables
    //const API_KEY = process.env.API_KEY;    
    fetchProducts();
	$('.datepick').datetimepicker({format: "yyyy-MM-DD"}); 
	
    //Add product
      $('#submitProductForm').on('submit', function (e) {
        e.preventDefault();
        $.ajax({
			type: 'post',
			url: url+"/products/",
			data: $('#submitProductForm').serialize(),
			headers: {
			"x-api-key": API_KEY
			},
			success: function () {
				$("#createProductBtn").button('reset');                
				$("#submitProductForm")[0].reset();
				$("html, body, div.modal, div.modal-content, div.modal-body").animate({scrollTop: '0'}, 100);                                                        
				$('#add-product-messages').html('<div class="alert alert-success">'+
					'<button type="button" class="close" data-dismiss="alert">&times;</button>'+
					'<strong><i class="glyphicon glyphicon-ok-sign"></i></strong>Product added successfully.</div>');
				$(".alert-success").delay(500).show(10, function() {
					$(this).delay(3000).hide(10, function() {
						$(this).remove();
					});
				}); 
			fetchProducts();
			$(".text-danger").remove();
			$(".form-group").removeClass('has-error').removeClass('has-success');
			}
        });

      });

      //Product edit
      $('#editProductForm').on('submit', function (e) {
        e.preventDefault();
        $.ajax({
          type: 'post',
          url: url+"/products/",
          data: $('#submitProductForm').serialize(),
          headers: {
            "x-api-key": API_KEY
            },
          success: function () {
                $("#createProductBtn").button('reset');                
                $("#submitProductForm")[0].reset();
                $("html, body, div.modal, div.modal-content, div.modal-body").animate({scrollTop: '0'}, 100);                                                        
                $('#add-product-messages').html('<div class="alert alert-success">'+
        '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
        '<strong><i class="glyphicon glyphicon-ok-sign"></i></strong>Product added successfully.</div>');
                $(".alert-success").delay(500).show(10, function() {
                    $(this).delay(3000).hide(10, function() {
                        $(this).remove();
                    });
                });

                fetchProducts();
                $(".text-danger").remove();
                $(".form-group").removeClass('has-error').removeClass('has-success');
          }
        });

      });
});

function fetchProducts(){
    var settings = {
        "url": url+"/products/",
        "method": "GET",
        "timeout": 0,
        "headers": {
          "x-api-key": API_KEY
        },
      };
      
      $.ajax(settings).done(function (response) {
        $("#products_wrap").html(response);
      })
}

function fetchSeachProducts(){
    var settings = {
        "url": url+"/product_search/",
        "method": "POST",
		data: {
			"search": $("#search").val(),
			"search_category_id": $("#search_category_id").val(),
			},
		"timeout": 0,
        "headers": {
          "x-api-key": API_KEY
        },
      };
      $.ajax(settings).done(function (response) {
        console.log(response);
        $("#products_wrap").html(response);
      })
}

// remove product 
function removeProduct(productId = null) {
    if(productId) {
        $("#removeProductBtn").unbind('click').bind('click', function() {
            $("#removeProductBtn").button('loading');
            $.ajax({
                url: url+'/products/'+productId,
                type: 'DELETE',
                headers: {
                    "x-api-key": API_KEY
                    },
                success:function(response) {
                    $("#removeProductBtn").button('reset');
                        $("#removeProductModal").modal('hide');
                        fetchProducts();
                        $(".remove-messages").html('<div class="alert alert-success">'+
                    '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
                    '<strong><i class="glyphicon glyphicon-ok-sign"></i></strong> Product deleted successfully.</div>');
					$(".alert-success").delay(500).show(10, function() {
						$(this).delay(3000).hide(10, function() {
							$(this).remove();
						});
					}); 
                }
            });
            return false;
        }); 
    }
}

function removeSelectedProducts(){
	$("input:checkbox[name=select_product]:checked").each(function(){
		$.ajax({
			url: url+'/products/'+$(this).val(),
			type: 'DELETE',
			headers: {
				"x-api-key": API_KEY
				},
			success:function(response) {
				fetchProducts()
			}
		});
	})	
}

function editProduct(productId = null) {

	if(productId) {
		$("#productId").remove();		
		$(".text-danger").remove();
		$(".form-group").removeClass('has-error').removeClass('has-success');
		$('.div-loading').removeClass('div-hide');
		$('.div-result').addClass('div-hide');
		$.ajax({
			url: url+'/products/'+productId,
			type: 'get',
            headers: {
                "x-api-key": API_KEY
                },
			dataType: 'json',
			success:function(response) {		
				$('.div-loading').addClass('div-hide');
				$('.div-result').removeClass('div-hide');				
				$("#getProductImage").attr('src', 'assets/images/stock/'+response.picture);					
				$(".editProductFooter").append('<input type="hidden" name="productId" id="productId" value="'+response.id+'" />');				
				$(".editProductPhotoFooter").append('<input type="hidden" name="productId" id="productId" value="'+response.id+'" />');
				$("#editProductName").val(response.name);
				$("#editQuantity").val(response.quantity);
				$("#editPrice").val(response.price);
				$("#editExpirationDate").val(response.expiration_date);
                $("#hiddenProductId").val(response.id);
				$('#editCategoryName option[value='+response.fk_category_id+']').attr('selected','selected');

				$("#editProductForm").unbind('submit').bind('submit', function() {
					var productImage = $("#editProductImage").val();
					var productName = $("#editProductName").val();
					var quantity = $("#editQuantity").val();
					var price = $("#editPrice").val();
					var expirationDate = $("#editExpirationDate").val();
					var categoryName = $("#editCategoryName").val();					

					if(productName == "") {
						$("#editProductName").after('<p class="text-danger">Product Name field is required</p>');
						$('#editProductName').closest('.form-group').addClass('has-error');
					}	else {
						$("#editProductName").find('.text-danger').remove();
						$("#editProductName").closest('.form-group').addClass('has-success');	  	
					}

					if(quantity == "") {
						$("#editQuantity").after('<p class="text-danger">Quantity field is required</p>');
						$('#editQuantity').closest('.form-group').addClass('has-error');
					}	else {
						$("#editQuantity").find('.text-danger').remove();
						$("#editQuantity").closest('.form-group').addClass('has-success');	  	
					}

					if(price == "") {
						$("#editPrice").after('<p class="text-danger">Price field is required</p>');
						$('#editPrice').closest('.form-group').addClass('has-error');
					}	else {
						$("#editPrice").find('.text-danger').remove();
						$("#editPrice").closest('.form-group').addClass('has-success');	  	
					}

					if(expirationDate == "") {
						$("#editExpirationDate").after('<p class="text-danger">Expiration date field is required</p>');
						$('#editExpirationDate').closest('.form-group').addClass('has-error');
					}	else {
						$("#editExpirationDate").find('.text-danger').remove();
						$("#editExpirationDate").closest('.form-group').addClass('has-success');	  	
					}

					if(categoryName == "") {
						$("#editCategoryName").after('<p class="text-danger">Category Name field is required</p>');
						$('#editCategoryName').closest('.form-group').addClass('has-error');
					}	else {
						$("#editCategoryName").find('.text-danger').remove();
						$("#editCategoryName").closest('.form-group').addClass('has-success');	  	
					}

					if(productName && quantity && price && expirationDate && categoryName ) {
						$("#editProductBtn").button('loading');

						var form = $(this);
						var formData = new FormData(this);

                 		$.ajax({
                            type: 'POST',
                            url: url+"/products/"+productId,
                            data: $('#editProductForm').serialize(),
                            headers: {
                                "x-api-key": API_KEY
                                },
							success:function(response) {
                                $("#editProductBtn").button('reset');																		
                                $("html, body, div.modal, div.modal-content, div.modal-body").animate({scrollTop: '0'}, 100);																			
								$('#edit-product-messages').html('<div class="alert alert-success">'+
				            '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
				            '<strong><i class="glyphicon glyphicon-ok-sign"></i></strong>Product updated successfully</div>');
				          $(".alert-success").delay(500).show(10, function() {
										$(this).delay(3000).hide(10, function() {
											$(this).remove();
										});
									});
                                    
									fetchProducts();

									$(".text-danger").remove();
									$(".form-group").removeClass('has-error').removeClass('has-success');								
							} 
						});
					}					

					return false;
				}); 
				$("#updateProductImageForm").unbind('submit').bind('submit', function() {					
					var productImage = $("#editProductImage").val();
					if(productImage == "") {
						$("#editProductImage").closest('.center-block').after('<p class="text-danger">Product Image field is required</p>');
						$('#editProductImage').closest('.form-group').addClass('has-error');
					} else {
						$("#editProductImage").find('.text-danger').remove();
						$("#editProductImage").closest('.form-group').addClass('has-success');	  	
					}

					if(productImage) {
						$("#editProductImageBtn").button('loading');

						var form = $(this);
						var formData = new FormData(this);

						$.ajax({
							url : form.attr('action'),
							type: form.attr('method'),
							data: formData,
							dataType: 'json',
							cache: false,
							contentType: false,
							processData: false,
							success:function(response) {								
								if(response.success == true) {
									$("#editProductImageBtn").button('reset');
									$("html, body, div.modal, div.modal-content, div.modal-body").animate({scrollTop: '0'}, 100);
									$('#edit-productPhoto-messages').html('<div class="alert alert-success">'+
				            '<button type="button" class="close" data-dismiss="alert">&times;</button>'+
				            '<strong><i class="glyphicon glyphicon-ok-sign"></i></strong> '+ response.messages +
				          '</div>');

				          $(".alert-success").delay(500).show(10, function() {
										$(this).delay(3000).hide(10, function() {
											$(this).remove();
										});
									});

									manageProductTable.ajax.reload(null, true);
									$(".fileinput-remove-button").click();

									$.ajax({
										url: 'php_action/fetchProductImageUrl.php?i='+productId,
										type: 'post',
										success:function(response) {
										$("#getProductImage").attr('src', response);		
										}
									});																		

									$(".text-danger").remove();
									$(".form-group").removeClass('has-error').removeClass('has-success');
								} 								
							}
						});
					}
					return false;
				});
			} 
		});
				
	} else {
		alert('error please refresh the page');
	}
} 
