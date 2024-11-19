<?php require_once 'includes/header.php'; ?>

<?php 

$sql = "SELECT * FROM product WHERE 1 = 1";
$query = $connect->query($sql);
$countProduct = $query->num_rows;


$lowStockSql = "SELECT * FROM product WHERE quantity <= 3";
$lowStockQuery = $connect->query($lowStockSql);
$countLowStock = $lowStockQuery->num_rows;

$connect->close();

?>


<style type="text/css">
	.ui-datepicker-calendar {
		display: none;
	}
</style>

<!-- fullCalendar 2.2.5-->
    <link rel="stylesheet" href="assets/plugins/fullcalendar/fullcalendar.min.css">
    <link rel="stylesheet" href="assets/plugins/fullcalendar/fullcalendar.print.css" media="print">


<div class="row">
	<div class="col-md-4">
		<div class="card">
		  <div class="cardContainer dash">
		    <p><?php echo date('l') .' '.date('d').', '.date('Y'); ?></p>
		  </div>
		</div> 
		<br/>
	</div>	
	<?php  
		if(isset($_SESSION['userId']) && $_SESSION['userId']==1) { ?>
			<div class="col-md-4">
				<div class="panel panel-success">
					<div class="panel-heading dash">				
						<a href="product.php" style="text-decoration:none;color:black;">
							Total Product
							<span class="badge pull pull-right"><?php echo $countProduct; ?></span>	
						</a>				
					</div>
				</div>
			</div>
			
			<div class="col-md-4">
				<div class="panel panel-danger">
					<div class="panel-heading dash">
						<a href="product.php" style="text-decoration:none;color:black;">
							Low Stock
							<span class="badge pull pull-right"><?php echo $countLowStock; ?></span>	
						</a>				
					</div>
				</div>
			</div>	
	<?php } ?>  	
	
</div>
<script type="text/javascript">
	$(function () {
		$('#navDashboard').addClass('active');
  	 });
</script>
<?php require_once 'includes/footer.php'; ?>