<?php require_once 'includes/db_connect.php' ?>
<?php require_once 'includes/header.php'; ?>
<div class="row">
	<div class="col-md-12">
		<ol class="breadcrumb">
		  <li><a href="dashboard.php">Home</a></li>		  
		  <li class="active">Product</li>
		</ol>
		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="page-heading"> <i class="glyphicon glyphicon-edit"></i> Manage Inventory</div>
			</div>
			<div class="panel-body">
				<div class="remove-messages"></div>
				<div class="div-action pull pull-right" style="padding-bottom:20px;">
					<button class="btn btn-success button1" data-toggle="modal" id="addProductModalBtn" data-target="#addProductModal"> <i class="glyphicon glyphicon-plus-sign"></i> Add Product </button>
				</div>		
				<div id="products_search_wrap">
					<form method="POST"> 
						<div class="row">
							<div class="col-md-4">			
								<input type="text" class="form-control" placeholder="Search" name="search" id="search"/>
							</div>
							<div class="col-md-4">		
								<select class="form-control" name="search_category_id" id="search_category_id" onchange="fetchSeachProducts()">
									<option value="0">Select categoy</option>
									<option value="1">Dairy</option>
									<option value="2">Drinks</option>
									<option value="3">Fruits and Veg</option>
									<option value="4">Meat</option>
									<option value="5">Other</option>
								</select>
							</div>	
							<div class="col-md-1">							
								<a class="btn btn-primary" onclick="fetchSeachProducts()" ><span class="glyphicon glyphicon-search"></span></a>
							</div>
						</div>
					</form>
				</div>
				<div id="products_wrap"></div>
				<button class="btn btn-danger button1" id="bulkDeleteProduct" onclick="removeSelectedProducts()"> 
					<i class="glyphicon glyphicon-trash"></i> Delete selected products </button>
			</div>
		</div>	
	</div>
</div>

<div class="modal fade" id="addProductModal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
    	<form class="form-horizontal" id="submitProductForm" method="POST" enctype="multipart/form-data">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title"><i class="fa fa-plus"></i> Add Product</h4>
	      </div>
	      <div class="modal-body" style="max-height:450px; overflow:auto;">
	      	<div id="add-product-messages"></div>
	      	<div class="form-group">
	        	<label for="productImage" class="col-sm-3 control-label">Product Image: </label>
	        	
				    <div class="col-sm-8">
							<div id="kv-avatar-errors-1" class="center-block" style="display:none;"></div>							
					    <div class="kv-avatar center-block">					        
					        <input type="file" class="form-control" id="productImage" placeholder="Product Name" name="picture" class="file-loading" style="width:auto;"/>
					    </div>
				      
				    </div>
	        </div> 
	        <div class="form-group">
	        	<label for="productName" class="col-sm-3 control-label">Product Name: </label>
	        	
				    <div class="col-sm-8">
				      <input type="text" class="form-control" id="productName" placeholder="Product Name" name="name" autocomplete="off" required>
				    </div>
	        </div>    

	        <div class="form-group">
	        	<label for="quantity" class="col-sm-3 control-label">Quantity: </label>
	        	
				<div class="col-sm-8">
					<input type="number" class="form-control" id="quantity" placeholder="Quantity" name="quantity" autocomplete="off" required>
				</div>
	        </div>
	        <div class="form-group">
	        	<label for="price" class="col-sm-3 control-label">Price: </label>
	        	
				<div class="col-sm-8">
					<input type='number' step='0.01' value='0.00' placeholder='0.00' class="form-control" id="price" name="price" autocomplete="off">
				</div>
	        </div> 
	        <div class="form-group">
	        	<label for="categoryName" class="col-sm-3 control-label">Category Name: </label>
	        	
				    <div class="col-sm-8">
				      <select type="text" class="form-control" id="categoryName" placeholder="Product Name" name="fk_category_id" >				      
							<option value="0">Select categoy</option>
							<option value="1">Dairy</option>
							<option value="2">Drinks</option>
							<option value="3">Fruits and Veg</option>
							<option value="4">Meat</option>
							<option value="5">Other</option>
				      </select>
				    </div>
	        </div>		
	        <div class="form-group required">
	        	<label for="productStatus" class="col-sm-3 control-label">Expiration date: </label>
	        	
				<div class="col-sm-8">
					<input type="text" class="form-control datepick" name="expiration_date" id="expiration_date" required>					
				</div>
	        </div>
	      </div>	      
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal"> <i class="glyphicon glyphicon-remove-sign"></i> Close</button>	        
	        <button type="submit" class="btn btn-primary" id="createProductBtn" data-loading-text="Loading..." autocomplete="off"> <i class="glyphicon glyphicon-ok-sign"></i> Save Changes</button>
	      </div>      
     	</form>     
    </div> 
  </div>
</div> 
<!-- add product -->


<!-- edit product -->
<div class="modal fade" id="editProductModal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
    	    	
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title"><i class="fa fa-edit"></i> Edit Product</h4>
	      </div>
	      <div class="modal-body" style="max-height:450px; overflow:auto;">

	      	<div class="div-loading">
	      		<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
						<span class="sr-only">Loading...</span>
	      	</div>

	      	<div class="div-result">
				  <ul class="nav nav-tabs" role="tablist">
				    <li role="presentation" class="active"><a href="#productInfo" aria-controls="profile" role="tab" data-toggle="tab">Product Info</a></li>    
				  </ul>
				  <div class="tab-content">				  	
				   
			         
				    <div role="tabpanel" class="tab-pane active" id="productInfo">
				    	<form class="form-horizontal" id="editProductForm" method="POST">				    
				    	<br />

				    	<div id="edit-product-messages"></div>

				    	<div class="form-group">
			        	<label for="editProductName" class="col-sm-3 control-label">Product Name: </label>
			        	
						    <div class="col-sm-8">
						      <input type="text" class="form-control" id="editProductName" placeholder="Product Name" name="editProductName" autocomplete="off">
						    </div>
			        </div>    

			        <div class="form-group">
			        	<label for="editQuantity" class="col-sm-3 control-label">Quantity: </label>
			        	
						    <div class="col-sm-8">
						      <input type="text" class="form-control" id="editQuantity" placeholder="Quantity" name="editQuantity" autocomplete="off">
						    </div>
			        </div>        	 

			        <div class="form-group">
			        	<label for="editPrice" class="col-sm-3 control-label">Price: </label>
			        	
						    <div class="col-sm-8">
						      <input type="text" class="form-control" id="editPrice" placeholder="Price" name="editPrice" autocomplete="off">
						    </div>
			        </div>     	        

			        <div class="form-group">
			        	<label for="editCategoryName" class="col-sm-3 control-label">Category Name: </label>
			        	
						    <div class="col-sm-8">
						      <select type="text" class="form-control" id="editCategoryName" name="editCategoryName" >
							  	<option value="0">Select categoy</option>
								<option value="1">Dairy</option>
								<option value="2">Drinks</option>
								<option value="3">Fruits and Veg</option>
								<option value="4">Meat</option>
								<option value="5">Other</option>
						      </select>
						    </div>
			        </div> 					        	         	       

			        <div class="form-group">
			        	<label for="editExpirationDate" class="col-sm-3 control-label">Expiration date: </label>
			        	
						    <div class="col-sm-8">
							<input type="text" class="form-control" id="editExpirationDate" placeholder="Expiration date" name="editExpirationDate" autocomplete="off">

						    </div>
			        </div>         	        
					<input type="hidden"  id="hiddenProductId" name="hiddenProductId" value="">

			        <div class="modal-footer editProductFooter">
				        <button type="button" class="btn btn-default" data-dismiss="modal"> <i class="glyphicon glyphicon-remove-sign"></i> Close</button>
				        
				        <button type="submit" class="btn btn-success" id="editProductBtn" data-loading-text="Loading..."> <i class="glyphicon glyphicon-ok-sign"></i> Save Changes</button>
				      </div>			     
			        </form>			     	
				    </div> 
				  </div>
				</div>	      	
	      </div>
    </div>
  </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="removeProductModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><i class="glyphicon glyphicon-trash"></i> Remove Product</h4>
      </div>
      <div class="modal-body">

      	<div class="removeProductMessages"></div>

        <p>Do you really want to remove ?</p>
      </div>
      <div class="modal-footer removeProductFooter">
        <button type="button" class="btn btn-default" data-dismiss="modal"> <i class="glyphicon glyphicon-remove-sign"></i> Close</button>
        <button type="button" class="btn btn-primary" id="removeProductBtn" data-loading-text="Loading..."> <i class="glyphicon glyphicon-ok-sign"></i> Save changes</button>
      </div>
    </div>
  </div>
</div>



<script src="assets/js/product.js?v=<?=time()?>"></script>

<?php require_once 'includes/footer.php'; ?>