<?php require_once 'includes/header.php'; ?>
<div class="row">
	<div class="col-md-4">
		<div class="cardContainer dash">
		    <p><?php echo date('l') .' '.date('d').', '.date('Y'); ?></p>
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
							<span class="badge pull pull-right" id="number_of_product"></span>	
						</a>				
					</div>
				</div>
			</div>
			
			<div class="col-md-4">
				<div class="panel panel-danger">
					<div class="panel-heading dash">
						<a href="product.php" style="text-decoration:none;color:black;">
							Low Stock
							<span class="badge pull pull-right" id="low_stock"></span>	
						</a>				
					</div>
				</div>
			</div>	
	<?php } ?>  	
	<div class="col-md-12" style="height:350px;">
		<h2 class="text-center">Stock Levels and Price by Category</h2>
		<canvas id="stockPriceChart" style="margin: auto;"></canvas>
	</div>
</div>

<?php
 
 $stored_procedure = "CALL getStocklevelsAndPrice()";                
 $query = $connect->query($stored_procedure);        

 $categories = [];
 $stockLevels = [];
 $prices = [];
 
 while ($row = $query->fetch_assoc()) {
     $categories[] = $row['category_name'];
     $stockLevels[] = $row['total_stock'];
     $prices[] = $row['avg_price'];
 }
 
?>

<script>
    const ctx = document.getElementById('stockPriceChart').getContext('2d');

    const categories = <?php echo json_encode($categories); ?>;
    const stockLevels = <?php echo json_encode($stockLevels); ?>;
    const prices = <?php echo json_encode($prices); ?>;

    const stockPriceChart = new Chart(ctx, {
      type: 'bar', 
	  options: {
		responsive: true,
		maintainAspectRatio: false
	  },
      data: {
        labels: categories,
        datasets: [
          {
            label: 'Stock Levels',
            data: stockLevels,
            backgroundColor: 'rgba(54, 162, 235, 0.5)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1,
            yAxisID: 'y-axis-1',
          },
          {
            label: 'Price',
            data: prices,
            backgroundColor: 'rgba(255, 99, 132, 0.5)',
            borderColor: 'rgba(255, 99, 132, 1)',
            borderWidth: 1,
            type: 'line',
            fill: false,
            tension: 0.4,
            yAxisID: 'y-axis-2',
          },
        ],
      },
      options: {
        responsive: true,
        scales: {
          yAxes: [
            {
              id: 'y-axis-1',
              type: 'linear',
              position: 'left',
              ticks: {
                beginAtZero: true,
              },
              scaleLabel: {
                display: true,
                labelString: 'Stock Levels (Units)',
              },
            },
            {
              id: 'y-axis-2',
              type: 'linear',
              position: 'right',
              ticks: {
                beginAtZero: true,
              },
              scaleLabel: {
                display: true,
                labelString: 'Price ($)',
              },
            },
          ],
        },
        tooltips: {
          mode: 'index',
          intersect: false,
        },
        legend: {
          position: 'top',
        },
      },
    });
	$('#navDashboard').addClass('active');
  </script>
<script src="assets/js/dashboard.js?v=<?=time()?>"></script>

<?php require_once 'includes/footer.php'; ?>