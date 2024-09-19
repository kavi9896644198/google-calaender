<!Doctype html>
<html lang="en">
<head>
	<title>Table 05</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link href='https://fonts.googleapis.com/css?family=Roboto:400,100,300,700' rel='stylesheet' type='text/css'>

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

	<link rel="stylesheet" href="<?=get_stylesheet_directory_uri();?>/includes/css/style.css">

</head>
<?php 
global $wpdb;
$table_name =  $wpdb->prefix.'subscribers_waiting_list';
$results = $wpdb->get_results( "SELECT * FROM $table_name"); 
$x = 1;
?>
<body>
	<section class="ftco-section">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-6 text-center mb-5">
					<h2 class="heading-section">Waiting List</h2>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="table-wrap">
						<table class="table table-responsive-xl">
							<thead>
								<tr>
									<th><strong>Index</strong></th>
									<th><strong>Email</strong></th>
									<th><strong>Username</strong></th>
									<th><strong>Status</strong></th>
									<th><strong>Expiration Date</strong></th>
									<th><strong>Generate Redeem Code</strong></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach($results as $data){ 
									$start_date = $data->date_activated;
									$plan = $data->plan;
									if($data->status == "Active"){
										if($plan == "Weekly Subscription"){
											$date = strtotime($start_date);
											$expiration_date = strtotime("+7 day", $date);
										}
										elseif($plan == "Monthly Subscription"){
											$date = strtotime($start_date);
											$expiration_date = strtotime("+1 month", $date);
										}
										else{
											$date = strtotime($start_date);
											$expiration_date = strtotime("+1 year", $date);
										}
									}
									?>
									<tr class="alert" role="alert">
										<td>
											<div class="col-6 col-md-3">
												<?=$x++;?>.
											</div>
										</td>
										<td class="d-flex align-items-center">
											<div class="pl-3 email">
												<span><?=$data->email;?></span>
												<span>Added: <?=$data->date_created;?></span>
											</div>
										</td>
										<td><?=$data->name;?></td>
										<td class="status">
											<?php if($data->status):?>
												<span class="active">Active</span></td>
												<?php else: ?>
													<span class="waiting">Inactive</span>
												<?php endif; ?>
											</td>
											<td>
												<div class="col-6 col-md-3"><?php if($data->status):echo date('d/m/Y', $expiration_date); else: echo"-"; endif;?></div>
											</td>
											<td>
												<button class="btn btn-success btn-block generate_code" <?php if(($data->redeem_code)||($data->status)): echo 'disabled'; endif;?> href="#" data-user_email ='<?php echo $data->email;?>'>Generate/Send Redeem Code</button>
											</td>
										</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</section>

		<script src="<?=get_stylesheet_directory_uri();?>/includes/js/jquery.min.js"></script>
		<script src="<?=get_stylesheet_directory_uri();?>/includes/js/popper.js"></script>
		<script src="<?=get_stylesheet_directory_uri();?>/includes/js/bootstrap.min.js"></script>
		<script src="<?=get_stylesheet_directory_uri();?>/includes/js/main.js"></script>

	</body>
	</html>
