<?php 
/*
Template Name: Report Template
*/
show_admin_bar(false);
get_header();
?>
<style type="text/css">
#the-menu{display:none;}

body{
	background:#FFF;	
}
#top-content{
	background:#FFF;	
}
#header-logo{
	height:120px;
}
#header-text h2{
	line-height:120px;	
}
@media print{@page {size: portrate}}
</style>

<div class="container-fluid home-special-template">
	<div class="row">
    	<div class="col-md-4" id="header-logo">
        	<img src="<?php echo get_stylesheet_directory_uri() ?>/images/logo.png" class="img-fluid flex-center">
        </div>
        <div class="col-md-8" id="header-text">
        	<h2>Auto Service Center &amp; General Merchandise</h2>
        </div>
    </div>
   
	<div class="row">
    	<div class="col-md-12 nopadding">
		<?php
		if(isset($_GET['secure'])&&$_GET['secure']&&wp_verify_nonce($_GET['secure'], "secureGenerateCollection")){
			$chrono = $_GET['chrono'];
			$date_from = date("Y-m-d", strtotime($_GET['date_from']));
			$date_to = date("Y-m-d", strtotime($_GET['date_to']));
			?>
            
            <div class="row">
                <div class="col-md-12">
                    <h5>Collection Report <?php echo str_replace("'", "", ($_GET['chrono']=="custom" ?
                                                                "as of ".$_GET['date_from']." to ".$_GET['date_to'] : 
                                                                gen_as_of($_GET['chrono']))); ?></h5>
                </div>
            </div>
            <?php
			
			
			
			if($error==0){
				if($chrono=="custom"){
					if($_GET['payment_collector']&&isset($_GET['payment_collector'])&&$_GET['payment_collector']!="all"){
						$sql = "Select `id`,`loan_id`,`client_id`,`payment_date`,`date_added`,`last_update`,`collector`, sum(amount) as amount 
								From gm_tbl_payments Where collector=".$_GET['payment_collector']." and date_added BETWEEN '".$date_from."' and '".$date_to."' GROUP by date_added, loan_id, client_id";
					}else{
						$sql = "Select `id`,`loan_id`,`client_id`,`payment_date`,`date_added`,`last_update`,`collector`, sum(amount) as amount 
								From gm_tbl_payments Where date_added BETWEEN '".$date_from."' and '".$date_to."' GROUP by date_added, loan_id, client_id";
					}
				}elseif($chrono=="all_time"){
					if($_GET['payment_collector']&&isset($_GET['payment_collector'])&&$_GET['payment_collector']!="all"){
						$sql = "Select `id`,`loan_id`,`client_id`,`payment_date`,`date_added`,`last_update`,`collector`, sum(amount) as amount 
								From gm_tbl_payments collector=".$_GET['payment_collector']." and GROUP by date_added, loan_id, client_id";
					}else{
						$sql = "Select `id`,`loan_id`,`client_id`,`payment_date`,`date_added`,`last_update`,`collector`, sum(amount) as amount 
								From gm_tbl_payments GROUP by date_added, loan_id, client_id";
		
					}
				}else{
					if($_GET['payment_collector']&&isset($_GET['payment_collector'])&&$_GET['payment_collector']!="all"){
						$sql = "Select `id`,`loan_id`,`client_id`,`payment_date`,`date_added`,`last_update`,`collector`, sum(amount) as amount 
								From gm_tbl_payments Where date_added ".gen_report_date($chrono)." GROUP by date_added, loan_id, client_id";	
					}else{
						$sql = "Select `id`,`loan_id`,`client_id`,`payment_date`,`date_added`,`last_update`,`collector`, sum(amount) as amount 
								From gm_tbl_payments Where date_added ".gen_report_date($chrono)." GROUP by date_added, loan_id, client_id";	
		
					}
				}
				global $wpdb;
				$check_payments = $wpdb->get_results($sql);
				if($check_payments){
				echo '<table class="table table-condensed table-striped table-hover">';
					echo '<thead class="blue-grey">';
						echo '<th>#</th>';
						echo '<th>Account Number</th>';
						echo '<th>Account Name</th>';
						echo '<th>Collector</th>';
						echo '<th>Collection Date</th>';
						echo '<th>Payment</th>';
					echo '</thead>';
					$a = 0;
					$total_collection = 0;
					foreach($check_payments as $payments){
					$a++;
					$total_collection += $payments->amount;
					$collector_info = get_user_by("ID", $payments->collector);
					echo '<tr>';
					echo '<td>'.$a.'</td>';
					echo '<td>'.show_clientinfo_by("id", $payments->client_id)->account_number.'</td>';
					echo '<td>'.show_clientinfo_by("id", $payments->client_id)->first_name.' 
							  '.show_clientinfo_by("id", $payments->client_id)->middle_name.' 
							  '.show_clientinfo_by("id", $payments->client_id)->last_name.' 
							  '.show_clientinfo_by("id", $payments->client_id)->suffix.' </td>';
						echo '<td>'.$collector_info->first_name.' '.$collector_info->last_name.'</td>';
					echo '<td>'.date("F d, Y", strtotime($payments->date_added)).'</td>';
					echo '<td>Php '.number_format($payments->amount,2).'</td>';
					echo '</tr>';
					}
					echo '<tr><td colspan="5"></td><td><strong>Total Collection:</strong> Php '.number_format($total_collection,2).'</td></tr>';
				
				echo '</table>';
				}else{
					echo '<table class="table table-condensed table-striped table-hover">';
					echo '<thead class="blue-grey">';
						echo '<th>#</th>';
						echo '<th>Account Number</th>';
						echo '<th>Account Name</th>';
						echo '<th>Collector</th>';
						echo '<th>Collection Date</th>';
						echo '<th>Payment</th>';
					echo '</thead>';
					echo "<tr><td colspan='6'>No Result Found.</td></tr>";
				
				echo '</table>';
						
				}
			}else{
				echo create_error_msg($error_msg);	
			}
		}elseif(isset($_GET['secure'])&&$_GET['secure']&&wp_verify_nonce($_GET['secure'], "secureGenerateSales")){
			$chrono = $_GET['chrono'];
			$date_from = date("Y-m-d", strtotime($_GET['date_from']));
			$date_to = date("Y-m-d", strtotime($_GET['date_to']));
			?>
            <div class="row">
                <div class="col-md-12">
                    <h5>Sales Report <?php echo str_replace("'", "", ($_GET['chrono']=="custom" ?
                                                                "as of ".$_GET['date_from']." to ".$_GET['date_to'] : 
                                                                gen_as_of($_GET['chrono']))); ?></h5>
                </div>
            </div>
            <?php
			if($error==0){
				if($chrono=="custom"){
					$sql = "Select * From gm_tbl_loans Where release_date BETWEEN '".$date_from."' and '".$date_to."'";
				}elseif($chrono=="all_time"){
					$sql = "Select * From gm_tbl_loans";
				}else{
					$sql = "Select * From gm_tbl_loans Where release_date ".gen_report_date($chrono)."";	
				}
				global $wpdb;
				$check_sales = $wpdb->get_results($sql);
				if($check_sales){
				?>
				<table class="table table-condensed table-striped table-hover" id="released-loans">
					<thead class="blue-grey">
						<th>#</th>
						<th>Release Date</th>
						<th>Account Number</th>
						<th>Account Name</th>
						<th>Terms</th>
						<th>Interest</th>
						<th>Status</th>
						<th>Loan Amount</th>
					</thead>
					<?php
		
					$total_sales = 0;
					$ctr =0;
					foreach($check_sales as $loan_app){
						$total_sales += $loan_app->amount;
						$ctr++;
						echo '<tr>';
							echo '<td>'.$ctr.'</td>';
							echo '<td>'.date("F d, Y", strtotime($loan_app->release_date)).'</td>';
							echo '<td>
									  '.show_clientinfo_by("id", $loan_app->client_id)->account_number.'
									
								  </td>';
							echo '<td>
									  '.show_clientinfo_by("id", $loan_app->client_id)->first_name.'
									  '.show_clientinfo_by("id", $loan_app->client_id)->middle_name.'
									  '.show_clientinfo_by("id", $loan_app->client_id)->last_name.'
									  '.show_clientinfo_by("id", $loan_app->client_id)->suffix.'
								  </td>';
							echo '<td>'.$loan_app->terms.'</td>';
							echo '<td>'.$loan_app->interest.'%</td>';
							echo '<td>'.$loan_app->status.'</td>';
							echo '<td>'.number_format($loan_app->amount, 2).'</td>';
						echo '</tr>';
					}	
						echo '<tr><td colspan="6"></td>
								  <td><strong><strong>Count: </strong></strong> '.$ctr.'</td></td>
								  <td><strong><strong>Total Sales: </strong></strong>Php '.number_format($total_sales, 2).'</td></tr>';
					
					?>
				</table>
				<?php
				}else{
				?>
				<table class="table table-condensed table-striped table-hover" id="released-loans">
					<thead class="blue-grey">
						<th>#</th>
						<th>Release Date</th>
						<th>Account Number</th>
						<th>Account Name</th>
						<th>Terms</th>
						<th>Interest</th>
						<th>Status</th>
						<th>Loan Amount</th>
					</thead>
					<?php
		
			
						echo '<tr><td colspan="8">No Result Found.</td></tr>';
					
					?>
				</table>
				<?php	
				}
			}else{
				echo create_error_msg($error_msg);	
			}
		}elseif(isset($_GET['secure'])&&$_GET['secure']&&wp_verify_nonce($_GET['secure'], "secureGenAccountsToCollect")){
			$collector_info = get_user_by("ID", $_GET['payment_collector']);
			
			?>
            <div class="row">
            	<div class="col-md-12">
                	<h6>Collection for <?php echo date("F d, Y", strtotime($_GET['collection_date'])) ?> to be collected by 
                    <?php print_r($collector_info ? $collector_info->first_name.' '.$collector_info->last_name : "All Collectors"); ?>
                    </h6>
                </div>
            </div>
            <?php
			
			
			if($_GET['payment_collector']!="all"){
				$loans = $wpdb->get_results($wpdb->prepare(
				"Select * From gm_tbl_loans Where assigned_collector=%d and status=%s", $_GET['payment_collector'], "Active"
				));
			}else{
				$loans = $wpdb->get_results($wpdb->prepare(
				"Select * From gm_tbl_loans Where status=%s", "Active"
				));
		
			}
			
			if(!$loans){
				$error += 1;
				$error_msg .= "Error: No collection found for this date";	
			}
			
			if($error==0){
				if($_GET['type']=="Simplified"){
				?>
                <style type="text/css">
				table td, table th{
					padding:0 !important;
					font-size:10px !important;	
				}
				@media print{@page {size: landscape}}
				</style>
				<table class="table table-condensed table-striped table-bordered">
				<thead>
					<th>Name</th>
					<th>D.O.R</th>
					<th>Term</th>
					<th>P.N</th>
					<th>M.O.P</th>
					<th>Daily</th>
					<th>Behind / Advance</th>
                    <th>LPD</th>
					<th>Beg. Bal</th>
					<th>Due</th>
					<th>O. Due</th>
					<th>Due+O.Due</th>
					<th>Amt. Paid</th>
					<th>End Bal.</th>
					<th>Lapsed</th>
					<th>Payment</th>
				</thead>
				<?php
				}
				
				$collection_date = date("Y-m-d", strtotime($_GET['collection_date']));
				$a = 0;
				foreach($loans as $loan){
					$days_to_pay = date_diff(date_create(date("Y-m-d", strtotime("+1 day", strtotime($loan->release_date)))), date_create(date("Y-m-d", strtotime($loan->payment_lastday))));
					$loan_divisor = $days_to_pay->days / $loan->terms;
					switch($loan->mop){
						case "Daily":
							$basta = "1 day";
							break;
						case "Weekly":
							$basta = "1 week";
							break;
						case "Monthly":
							$basta = "1 month";
							break;
						case "Quaterly":
							$basta = "3 months";
							break;
						case "Semi-Annual":
							$basta = "6 months";
							break;
						case "Annual":
							$basta = "1 year";
							break;
						default:
							$basta = "1 day";
					}
					
					
					$sql = "Select `id`,`loan_id`,`client_id`,`payment_date`,`date_added`,`last_update`,`collector`, sum(amount) as amount 
						From gm_tbl_payments Where loan_id=".$loan->id." and payment_date='".$collection_date."' GROUP by payment_date, loan_id, client_id";
					$check_payments = $wpdb->get_results($sql);
					
					$last_paymet = $wpdb->get_row(
					"Select `id`,`loan_id`,`client_id`,`payment_date`,`date_added`,`last_update`,`collector`, sum(amount) as amount 
						From gm_tbl_payments Where loan_id=".$loan->id." GROUP by payment_date, loan_id, client_id ORDER by id DESC LIMIT 1"
					);
					
					$first_day_of_payment = date("Y-m-d", strtotime("+".$basta."", strtotime($loan->release_date)));
					
					
					
					
					
					$now = date_create(date("Y-m-d", strtotime($collection_date)));
					$payment_lastday = date_create(date("Y-m-d", strtotime($loan->payment_lastday)));
					
					//---get if payment already lapsed and remaining days to pay
					$payment_lapsed = date_diff(
					$now,
					$payment_lastday
					)->invert==0 ? 
					$payment_lapsed = date_diff(
					$now,
					$payment_lastday
					)->days." Days Remaining" :
					$payment_lapsed = date_diff(
					$now,
					$payment_lastday
					)->days." Payment Lapsed";
					//------------------------------
					
					$a++;
					
					//get get daily payment
					$interest = (100 + $loan->interest) / 100;
					$daily_payment = (($loan->amount / $loan->terms) * $interest);
					//----------------
					
					
					//get assigned collector info
					$assigned_collector = get_user_by("ID", $loan->assigned_collector);
					//----------------------------
					
					
					///get behind or advanced dates
					
					
					$last_payment_date = date_create(date("Y-m-d", strtotime($last_paymet ? date("Y-m-d", strtotime("+".$basta."", strtotime($last_paymet->payment_date))) : date("Y-m-d", strtotime("+".$basta."", strtotime($loan->release_date))))));
					$behind_payment = date_diff($now, $last_payment_date)->invert==1 ? 
									  round((date_diff($now, $last_payment_date)->days / $loan_divisor)):
									  0;
									  
					$advance_payment = date_diff($now, $last_payment_date)->invert==0 ? 
									  round((date_diff($now, $last_payment_date)->days / $loan_divisor)):
									  0;
					$mod_pay = str_replace("Dai", "Day", str_replace("ly", "/s", $loan->mop));				  
					$advance_behind = $behind_payment ? $behind_payment." ".$mod_pay." Behind" : $advance_payment." ".$mod_pay." Advance";
					//------------------------------				  
					
					
					
					
					//get outstand balance	  
					$sum_payments = $wpdb->get_row(
					"Select `id`,`loan_id`,`client_id`,`payment_date`,`date_added`,`last_update`,`collector`, sum(amount) as amount 
						From gm_tbl_payments Where loan_id=".$loan->id." and payment_date BETWEEN '".$first_day_of_payment."' and '".$collection_date."'"
					);
					
					$total_due = $loan->amount * $interest;
					$outstanding_balance = $total_due - $sum_payments->amount;
					//---------------
					
					
					$last_payment_remaining_due = $last_paymet ? ($last_paymet->amount>=$daily_payment ? 0 : $daily_payment-$last_paymet->amount) : 0;
					$over_due_dates_due = date_diff($now, $last_payment_date)->days;
					
					//$due_plus_overdue = ($daily_payment-number_format($payments->amount,2)) + date_diff($now, $last_payment_date)->invert==0 ? 0 : ($daily_payment * (str_replace("-", "", $behind_payment)));
					
					if(strtotime($first_day_of_payment)<=strtotime($collection_date)){
						if(!$check_payments){
							$over_due = (($daily_payment * ($behind_payment)) + $last_payment_remaining_due);
							if(number_format($sum_payments->amount,2)!=0){
								$last_payment_paid = $daily_payment - $last_payment_remaining_due;
							}else{
								$last_payment_paid = 0;
							}
								?>
								<?php
								if($_GET['type']=="Detailed"){
								?>
								<div class="row" id="to-collect">
									<div class="col-md-12">
										<div class="row blue-grey">
											<div class="col-md-1">#<?php echo $a; ?></div>
											<div class="col-md-5">Account Number: <?php echo show_clientinfo_by("id", $loan->client_id)->account_number; ?></div>
											<div class="col-md-6">Account Name: <?php 
											echo ''.show_clientinfo_by("id", $loan->client_id)->first_name.' 
												  '.show_clientinfo_by("id", $loan->client_id)->middle_name.' 
												  '.show_clientinfo_by("id", $loan->client_id)->last_name.' 
												  '.show_clientinfo_by("id", $loan->client_id)->suffix.'';
											?>
											</div>
										</div>
										<div class="row">
											<div class="col-md-4">Release Date: <?php echo date("F d, Y", strtotime($loan->release_date)); ?></div>
											<div class="col-md-4">Last Payment: <?php echo $last_paymet->payment_date; ?></div>
											<div class="col-md-4">Lapsed/Remaining: <?php echo $payment_lapsed; ?></div>
										</div>
										<div class="row">
											<div class="col-md-4">Collection Date: <?php echo date("F d, Y", strtotime($collection_date)); ?></div>
											<div class="col-md-4">Assigned Collector: 
											<?php echo ''.$assigned_collector->first_name.' 
														'.$assigned_collector->last_name.''; ?>
											</div>
											<div class="col-md-4">Behind/Advance: <?php echo $advance_behind; ?></div>
										</div>
										<div class="row">
											<div class="col-md-3">Outstanding Balance: <?php echo number_format($outstanding_balance,2); ?></div>
											<div class="col-md-3">Daily Payment: <?php echo number_format($daily_payment,2); ?></div>
											<div class="col-md-3">Overdue: <?php echo number_format($over_due, 2); ?></div>
											<div class="col-md-3">Status: Awaiting Payment</div>
										</div>
										<div class="row">
											<div class="col-md-3">Last Payment Paid: <?php echo number_format($last_payment_paid,2); ?></div>
											<div class="col-md-3">Due: <?php echo number_format(($advance_payment ? 0 : $daily_payment)+$over_due,2); ?></div>
											<div class="col-md-3">Payment: </div>
											<div class="col-md-3">Signature: </div>
										</div>
									</div>
								</div>
								<?php
								}else{
								?>
								
									<tr>
										<td>
											<?php 
											echo ''.show_clientinfo_by("id", $loan->client_id)->first_name.' 
												  '.show_clientinfo_by("id", $loan->client_id)->middle_name.' 
												  '.show_clientinfo_by("id", $loan->client_id)->last_name.' 
												  '.show_clientinfo_by("id", $loan->client_id)->suffix.'';
											?>
										</td>
										<td>
											<?php echo date("m/d/Y", strtotime($loan->release_date)); ?>
										</td>
										<td>
											<?php echo $loan->terms." ".$mod_pay; ?>
										</td>
										<td>
											<?php echo number_format($total_due, 2); ?>
										</td>
										<td>
											<?php echo $loan->mop; ?>
										</td>
										<td>
											<?php echo number_format($daily_payment,2); ?>
										</td>
										<td>
											<?php echo $advance_behind; ?>
										</td>
                                        <td>
											<?php echo $last_paymet ? date("m/d/Y", strtotime($last_paymet->date_added)) : "None"; ?>
                                        </td>
										<td>
                                        	<?php echo number_format($outstanding_balance,2); ?>
										</td>
										<td>
											<?php echo number_format($advance_payment ? 0 : $daily_payment,2); ?>
										</td>
										<td>
											<?php echo number_format($over_due, 2); ?>
										</td>
										<td>
											<?php echo number_format(($advance_payment ? 0 : $daily_payment)+$over_due,2); ?>
										</td>
										
										<td>
											<?php echo number_format($sum_payments->amount,2); ?>
										</td>
										<td>
											<?php echo number_format($outstanding_balance-(($advance_payment ? 0 : $daily_payment)+$over_due),2); ?>
										</td>
										<td>
											<?php echo $payment_lapsed; ?>
										</td>
										<td></td>
									</tr>
								<?php	
								}
						}else{
							if($_GET['type']=="Detailed"){
							foreach($check_payments as $payments){
								$payment_status = number_format($payments->amount,2)>=$daily_payment ? "PAID" : "Incomplete";
								if($payment_status=='PAID'){
									$over_due = 0;
									$last_payment_paid = number_format($payments->amount,2);
								}else{
									$over_due = 0;
									$due = $daily_payment-number_format($payments->amount,2);
									$last_payment_paid = $daily_payment - $last_payment_remaining_due;	
								}
								?>
								<div class="row" id="to-collect">
									<div class="col-md-12">
										<div class="row blue-grey">
											<div class="col-md-1">#<?php echo $a; ?></div>
											<div class="col-md-5">Account Number: <?php echo show_clientinfo_by("id", $loan->client_id)->account_number; ?></div>
											<div class="col-md-6">Account Name: <?php 
											echo ''.show_clientinfo_by("id", $loan->client_id)->first_name.' 
												  '.show_clientinfo_by("id", $loan->client_id)->middle_name.' 
												  '.show_clientinfo_by("id", $loan->client_id)->last_name.' 
												  '.show_clientinfo_by("id", $loan->client_id)->suffix.'';
											?>
											</div>
										</div>
										<div class="row">
											<div class="col-md-4">Release Date: <?php echo date("F d, Y", strtotime($loan->release_date)); ?></div>
											<div class="col-md-4">Last Payment: <?php echo $last_paymet->payment_date; ?></div>
											<div class="col-md-4">Lapsed/Remaining: <?php echo $payment_lapsed; ?></div>
										</div>
										<div class="row">
											<div class="col-md-4">Collection Date: <?php echo date("F d, Y", strtotime($collection_date)); ?></div>
											<div class="col-md-4">Assigned Collector: 
											<?php echo ''.$assigned_collector->first_name.' 
														'.$assigned_collector->last_name.''; ?>
											</div>
											<div class="col-md-4">Behind/Advance: <?php echo $advance_behind; ?></div>
										</div>
										<div class="row">
											<div class="col-md-3">Outstanding Balance: <?php echo number_format($outstanding_balance,2); ?></div>
											<div class="col-md-3">Daily Payment: <?php echo number_format($daily_payment,2); ?></div>
											<div class="col-md-3">Overdue: <?php echo number_format($over_due, 2); ?></div>
											<div class="col-md-3">Status: <?php echo $payment_status; ?></div>
										</div>
										<div class="row">
											<div class="col-md-3">Last Payment Paid: <?php echo number_format($last_payment_paid,2); ?></div>
											<div class="col-md-3">Due: <?php echo number_format($due,2); ?></div>
											<div class="col-md-3">Payment: </div>
											<div class="col-md-3">Signature: </div>
										</div>
									</div>
								</div>
								<?php
								
							}
							}else{
							
								foreach($check_payments as $payments){
									$payment_status = number_format($payments->amount,2)>=$daily_payment ? "PAID" : "Incomplete";
									if($payment_status=='PAID'){
										$over_due = 0;
										$last_payment_paid = number_format($payments->amount,2);
									}else{
										$over_due = 0;
										$due = $daily_payment-number_format($payments->amount,2);
										$last_payment_paid = $daily_payment - $last_payment_remaining_due;	
									}
									
									?>
									
										<tr>
											<td>
												<?php 
												echo ''.show_clientinfo_by("id", $loan->client_id)->first_name.' 
													  '.show_clientinfo_by("id", $loan->client_id)->middle_name.' 
													  '.show_clientinfo_by("id", $loan->client_id)->last_name.' 
													  '.show_clientinfo_by("id", $loan->client_id)->suffix.'';
												?>
											</td>
											<td>
												<?php echo date("m/d/Y", strtotime($loan->release_date)); ?>
											</td>
											<td>
												<?php echo $loan->terms." ".$mod_pay; ?>
											</td>
											<td>
												<?php echo number_format($total_due, 2); ?>
											</td>
											<td>
												<?php echo $loan->mop; ?>
											</td>
											<td>
												<?php echo number_format($daily_payment,2); ?>
											</td>
											<td>
												<?php echo $advance_behind; ?>
											</td>
                                            <td>
												<?php echo $last_paymet ? date("m/d/Y", strtotime($last_paymet->date_added)) : "None"; ?>
                                            </td>
											<td>
												<?php echo number_format($outstanding_balance,2); ?>
											</td>
											<td>
												<?php echo number_format($advance_payment ? 0 : $daily_payment,2); ?>
											</td>
											<td>
												<?php echo number_format($over_due, 2); ?>
											</td>
											<td>
												<?php echo number_format(($advance_payment ? 0 : $daily_payment)+$over_due,2); ?>
											</td>
											
											<td>
												<?php echo number_format($sum_payments->amount,2); ?>
											</td>
											<td>
                                            	<?php echo number_format($outstanding_balance-(($advance_payment ? 0 : $daily_payment)+$over_due),2); ?>
											</td>
											<td>
												<?php echo $payment_lapsed; ?>
											</td>
											<td></td>
										</tr>
									<?php	
									
									
								}
									
							}
						}
						
					}
				}
				if($_GET['type']=="Simplified"){
				?>
				</table>
				<?php
				}
			}else{
				echo create_error_msg($error_msg);	
			}
		}elseif(isset($_GET['secure'])&&$_GET['secure']&&wp_verify_nonce($_GET['secure'], "secureViewLedger")){
		?>
		<div class="row">
            <div class="col-md-12"><h3><i class="fa fa-table"></i> Collection Ledger</h3></div>
        </div>
        <hr />
		<?php
			if(isset($_GET['act'])&&$_GET['act']&&$_GET['act']=='view_ledger'){
				if(isset($_GET['loan_id'])&&$_GET['loan_id']){
					$released_loan_info = $wpdb->get_row($wpdb->prepare(
					"Select * From gm_tbl_loans Where id=%d", stripslashes($_GET['loan_id'])
					));
					$loan_info = $wpdb->get_row($wpdb->prepare(
					"Select * From gm_tbl_loan_app Where id=%d", $released_loan_info->application_id
					));
					$client_id = $loan_info->client_id;
					$get_record = $wpdb->get_row($wpdb->prepare(
					"Select * From gm_tbl_clientinfo Where id=%d", $client_id
					));
					$mod_pay = str_replace("Dai", "Day", str_replace("ly", "/s", $released_loan_info->mop));
					?>
                    <div class="row">
                        <div class="col-md-12"><h5><i class="fa fa-list"></i> Loan Details</h5></div>
                    </div>
                    
                    <div class="row" id="loan-info">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6">Account Number: <?php echo $get_record->account_number; ?></div>
                                <div class="col-md-6">Account Name: <?php echo $get_record->first_name.' '.$get_record->middle_name.' '.$get_record->last_name.' '.$get_record->suffix; ?></div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">Application Date: <?php echo date("M d, Y", strtotime($loan_info->date_added)); ?></div>
                                <div class="col-md-4">Release Date: <?php echo date("M d, Y", strtotime($released_loan_info->release_date)); ?></div>
                                <div class="col-md-4">Amount: <?php echo number_format($loan_info->amount,2) ?></div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">Terms: <?php echo $loan_info->terms." ".$mod_pay; ?></div>
                                <div class="col-md-3">Interest: <?php echo $loan_info->interest; ?>%</div>
                                <div class="col-md-3">Processing Fee: <?php echo number_format($loan_info->processing_fee,2); ?></div>
                                <div class="col-md-3">Status: <span id="status"><?php echo $loan_info->status; ?></span></div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">Co-Maker: </div>
                            </div>
                            <div class="row">
                                <div class="col-md-5">Name: <?php echo $loan_info->cm_first_name.' '.$loan_info->cm_middle_name.' '.$loan_info->cm_last_name.' '.$loan_info->cm_suffix; ?></div>
                                <div class="col-md-3">Birthdate: <?php echo date("M d, Y", strtotime($loan_info->cm_bdate)); ?></div>
                                <div class="col-md-4">Contact: <?php echo $loan_info->cm_contact; ?></div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">Address: <?php echo $loan_info->cm_address; ?></div>
                            </div>
                            
                        </div>
                    </div>
                    <br /><hr />
                    
                    <div class="row" id="payment-collections">
                        <div class="col-md-12">
                        <?php
                       
                        echo '<table class="table table-condensed table-striped table-hover">';
                        echo '<thead class="blue-grey">';
                            echo '<th>#</th>';
                            echo '<th>Account Number</th>';
                            echo '<th>Account Name</th>';
                            echo '<th>Collection Date</th>';
                            echo '<th>Balance</th>';
                            echo '<th>Payment</th>';
                        echo '</thead>';
                
                            $check_payments = $wpdb->get_results(
                            "Select `id`,`loan_id`,`client_id`,`payment_date`,`date_added`,`last_update`,`collector`, sum(amount) as amount 
                            From gm_tbl_payments Where loan_id='".$released_loan_info->id."' GROUP by date_added, loan_id, client_id");
                            $a = 0;
                            $total_collection = 0;
                            foreach($check_payments as $payments){
                            $a++;
                            
                            $interest = (100 + $released_loan_info->interest) / 100;
                            $daily_payment = number_format((($released_loan_info->amount / $released_loan_info->terms) * $interest), 2);
                            
                            $total_due = $released_loan_info->amount * $interest;
                            
                            $outstanding_balance = $total_due - $total_collection;
                            echo '<tr>';
                            echo '<td>'.$a.'</td>';
                            echo '<td>'.show_clientinfo_by("id", $payments->client_id)->account_number.'</td>';
                            echo '<td>'.show_clientinfo_by("id", $payments->client_id)->first_name.' 
                                      '.show_clientinfo_by("id", $payments->client_id)->middle_name.' 
                                      '.show_clientinfo_by("id", $payments->client_id)->last_name.' 
                                      '.show_clientinfo_by("id", $payments->client_id)->suffix.' </td>';
                            echo '<td>'.date("F d, Y", strtotime($payments->date_added)).'</td>';
                            echo '<td>'.number_format($outstanding_balance, 2).'</td>';
                            echo '<td>Php '.number_format($payments->amount,2).'</td>';
                            echo '</tr>';
                            
                            $total_collection += $payments->amount;
                            }
                            echo '<tr><td colspan="4"></td>
                                      <td><strong>Outstanding Balance:</strong> Php '.number_format($total_due - $total_collection,2).'</td>
                                      <td><strong>Total Collection:</strong> Php '.number_format($total_collection,2).'</td>
                                  </tr>';
                        echo '</table>';
                        ?>	
                            
                            
                        </div>
                    </div>
                    <?php
				}else{
					echo 'Loan id not defined!';	
				}
			}
		
		}elseif(isset($_GET['secure'])&&$_GET['secure']&&wp_verify_nonce($_GET['secure'], "securePrintCommission")){
		global $wpdb;
		$check_loan = $wpdb->get_row($wpdb->prepare(
		"Select * From gm_tbl_loans Where id=%d and (status=%s or status=%s)", $_GET['loan_id'], "Active", "PAID"
		));
			if(!$check_loan){
				echo create_error_msg("Error: Loan id not found!;");	
			}else{
			$commissioner_info = get_user_by("ID", $check_loan->assigned_collector);
			$client_info = show_clientinfo_by("id", $check_loan->client_id);
			$interest = (100 + $check_loan->interest) / 100;
			$commission_amount = (($check_loan->amount * $interest) - $check_loan->amount) * .30;
			$commission_info = $wpdb->get_row($wpdb->prepare(
			"Select * From gm_tbl_commissions Where loan_id=%d and commissioner_id=%d",
			$check_loan->id, $check_loan->assigned_collector
			));
			$prepared_by = get_user_by("ID", get_current_user_id());
			?>
				<div class="row justify-content-md-center">
					<div class="col-md-4"><center><h4>Commission Receipt</h4></center></div>
				</div>
				<hr />
				
                <div class="row justify-content-md-center">
					<div class="col-md-9"><?php echo date("M d, Y", strtotime($commission_info->date_granted)) ?></div>
				</div>
                
                <br />
                
                
                <div class="row justify-content-md-center">
                	<div class="col-md-9">Commission was granted to <?php echo ucfirst($commissioner_info->first_name).' '.ucfirst($commissioner_info->last_name) ?>
                    amounting <?php echo number_format($commission_amount,2) ?> Pesos for his/her commission from the loan released for 
					<?php echo ucfirst($client_info->first_name).' '.ucfirst($client_info->last_name).' '.ucfirst($client_info->suffix) ?> with released amount of 
					<?php echo number_format($check_loan->amount,2) ?> Pesos.
                    </div>
                </div>
				<br /><br />
                
                
                <div class="row justify-content-md-center">
					<div class="col-md-3 offset-md-6 text-center">Prepared By</div>
				</div>
                <div class="row justify-content-md-center">
					<div class="col-md-3 offset-md-6 text-center"><?php echo ucfirst($prepared_by->first_name).' '.ucfirst($prepared_by->last_name) ?></div>
				</div>
                
                <br/><br/>
                
                
                <div class="row justify-content-md-center">
					<div class="col-md-3 offset-md-6 text-center">Granted To</div>
				</div>
                <div class="row justify-content-md-center">
					<div class="col-md-3 offset-md-6 text-center"><?php echo ucfirst($commissioner_info->first_name).' '.ucfirst($commissioner_info->last_name) ?></div>
				</div>
				
				
			<?php
			}
		}elseif(isset($_GET['secure'])&&$_GET['secure']&&wp_verify_nonce($_GET['secure'], "secureGenJoinReport")){
			if($error==0){
			$chrono = $_GET['chrono'];
			$date_from = date("Y-m-d", strtotime($_GET['date_from']));
			$date_to = date("Y-m-d", strtotime($_GET['date_to']));
			$collector = get_user_by("ID", $_GET['payment_collector']);
			?>
            <h4 align="center">Joint Sales &amp; Collection Report</h4>
            <hr />
            Date : <?php echo $chrono=="all_time" ? "From the beginning to Present" : ($chrono=="custom" ? "as of ".date("F d, Y", strtotime($date_from))." to ".date("F d, Y", strtotime($date_to))."" : gen_as_of($chrono)) ?>
            <hr />
			<div class="row">
				<div class="col-md-12"><h5><i class="fa fa-money"></i> Sales</h5></div>
			</div>
			<?php
				
			
			
				if($chrono=="custom"){
					if($_GET['payment_collector']&&isset($_GET['payment_collector'])&&$_GET['payment_collector']!="all"){
						$sql = "Select * From gm_tbl_loans Where assigned_collector='".$_GET['payment_collector']."' and release_date BETWEEN '".$date_from."' and '".$date_to."'";
						
					}else{
						$sql = "Select * From gm_tbl_loans Where release_date BETWEEN '".$date_from."' and '".$date_to."'";
					}
				}elseif($chrono=="all_time"){
					if($_GET['payment_collector']&&isset($_GET['payment_collector'])&&$_GET['payment_collector']!="all"){
						$sql = "Select * From gm_tbl_loans Where assigned_collector='".$_GET['payment_collector']."'";
					}else{
						$sql = "Select * From gm_tbl_loans";
					}
					
				}else{
					if($_GET['payment_collector']&&isset($_GET['payment_collector'])&&$_GET['payment_collector']!="all"){
						$sql = "Select * From gm_tbl_loans Where assigned_collector='".$_GET['payment_collector']."' and release_date ".gen_report_date($chrono)."";
					}else{
						$sql = "Select * From gm_tbl_loans Where release_date ".gen_report_date($chrono)."";
					}
				}
				global $wpdb;
				$check_sales = $wpdb->get_results($sql);
				if($check_sales){
				?>
				<table class="table table-condensed table-striped table-hover table-bordered">
					<thead>
						<th>Name</th>
						<th>Loan Amount</th>
						<th>Term</th>
						<th>Interest</th>
						<th>PN Amount</th>
						<th>M.O.P</th>
					</thead>
					<?php
		
					$total_sales = 0;
					$ctr = 0;
					foreach($check_sales as $loan_app){
						$ctr++;
						$total_sales += $loan_app->amount;
						$interest = (100 + $loan_app->interest) / 100;
						echo '<tr>';
							echo '<td>
									  '.show_clientinfo_by("id", $loan_app->client_id)->first_name.'
									  '.show_clientinfo_by("id", $loan_app->client_id)->middle_name.'
									  '.show_clientinfo_by("id", $loan_app->client_id)->last_name.'
									  '.show_clientinfo_by("id", $loan_app->client_id)->suffix.'
								  </td>';
							echo '<td>'.number_format($loan_app->amount, 2).'</td>';
							echo '<td>'.$loan_app->terms.'</td>';
							echo '<td>'.$loan_app->interest.'%</td>';
							echo '<td>'.number_format($loan_app->amount * $interest, 2).'</td>';
							echo '<td>Daily</td>';
						echo '</tr>';
					}	
						echo '<tr><td colspan="4"></td>
								  <td><strong><strong>Count: </strong></strong> '.$ctr.'</td></td>
								  <td><strong><strong>Total Sales: </strong></strong>Php '.number_format($total_sales, 2).'</td></tr>';
					
					?>
				</table>
				<?php
				}else{
				?>
				<table class="table table-condensed table-striped table-hover" id="released-loans">
					<thead>
						<th>Name</th>
						<th>Loan Amount</th>
						<th>Term</th>
						<th>Interest</th>
						<th>PN Amount</th>
						<th>M.O.P</th>
					</thead>
					<?php
		
			
						echo '<tr><td colspan="8">No Result Found.</td></tr>';
					
					?>
				</table>
				<?php	
				}
			?>
			<div class="row">
				<div class="col-md-12"><h5><i class="fa fa-university"></i> Collection</h5></div>
			</div>
			<table class="table table-condensed table-striped table-bordered">
				 <thead class="blue-grey">
					<th>Collection Date</th>
					<th>Name</th>
					<th>D.O.R</th>
					<th>Term</th>
					<th>P.N</th>
					<th>M.O.P</th>
					<th>Daily</th>
					<th>Beg. Bal</th>
					<th>Due</th>
					<th>O. Due</th>
					<th>Due+O.Due</th>
					<th>Amt. Paid</th>
					<th>End Bal.</th>
					<th>Lapsed</th>
					<th>Payment</th>
				</thead>
				
			<?php
			if($chrono=="custom"){
				if($_GET['payment_collector']&&isset($_GET['payment_collector'])&&$_GET['payment_collector']!="all"){
					$payments_sql = "Select `id`,`loan_id`,`client_id`,`payment_date`,`date_added`,`last_update`,`collector`, sum(amount) as amount 
							From gm_tbl_payments Where collector=".$_GET['payment_collector']." and date_added BETWEEN '".$date_from."' and '".$date_to."' GROUP by date_added, loan_id, client_id";
				}else{
					$payments_sql = "Select `id`,`loan_id`,`client_id`,`payment_date`,`date_added`,`last_update`,`collector`, sum(amount) as amount 
							From gm_tbl_payments Where date_added BETWEEN '".$date_from."' and '".$date_to."' GROUP by date_added, loan_id, client_id";
				}
			}elseif($chrono=="all_time"){
				if($_GET['payment_collector']&&isset($_GET['payment_collector'])&&$_GET['payment_collector']!="all"){
					$payments_sql = "Select `id`,`loan_id`,`client_id`,`payment_date`,`date_added`,`last_update`,`collector`, sum(amount) as amount 
							From gm_tbl_payments Where collector=".$_GET['payment_collector']." GROUP by date_added, loan_id, client_id";
				}else{
					$payments_sql = "Select `id`,`loan_id`,`client_id`,`payment_date`,`date_added`,`last_update`,`collector`, sum(amount) as amount 
							From gm_tbl_payments GROUP by date_added, loan_id, client_id";
		
				}
			}else{
				if($_GET['payment_collector']&&isset($_GET['payment_collector'])&&$_GET['payment_collector']!="all"){
					$payments_sql = "Select `id`,`loan_id`,`client_id`,`payment_date`,`date_added`,`last_update`,`collector`, sum(amount) as amount 
							From gm_tbl_payments Where date_added ".gen_report_date($chrono)." GROUP by date_added, loan_id, client_id";	
				}else{
					$payments_sql = "Select `id`,`loan_id`,`client_id`,`payment_date`,`date_added`,`last_update`,`collector`, sum(amount) as amount 
							From gm_tbl_payments Where date_added ".gen_report_date($chrono)." GROUP by date_added, loan_id, client_id";	
		
				}
			}
			$check_payments = $wpdb->get_results($payments_sql);
			if($check_payments){
				$total_payments = 0;
				foreach($check_payments as $payments){
					$mod_pay = str_replace("Dai", "Day", str_replace("ly", "/s", show_loan_detail_by("id", $payments->loan_id)->mop));
					$interest = (100 + show_loan_detail_by("id", $payments->loan_id)->interest) / 100;
					$pn = show_loan_detail_by("id", $payments->loan_id)->amount * $interest;
					$daily_payment = $pn / show_loan_detail_by("id", $payments->loan_id)->terms;
					
					
					
					
					
					//get prev bal
					$last_payment = $wpdb->get_row(
					"Select `id`,`loan_id`,`client_id`,`payment_date`,`date_added`,`last_update`,`collector`, sum(amount) as amount 
						From gm_tbl_payments Where loan_id=".$payments->loan_id." and date_added<'".$payments->date_added."' GROUP by payment_date, loan_id, client_id ORDER by id DESC LIMIT 1"
					);
					$last_payment_remaining_due = $last_payment ? ($last_payment->amount>=$daily_payment ? 0 : $daily_payment-$last_payment->amount) : 0;
					$now = date_create(date("Y-m-d", strtotime($payments->date_added)));
					$last_payment_date = date_create(date("Y-m-d", strtotime($last_payment ? date("Y-m-d", strtotime("+1 days", strtotime($last_payment->payment_date))) : date("Y-m-d", strtotime("+1 days", strtotime(show_loan_detail_by("id", $payments->loan_id)->release_date))))));
					$behind_payment = date_diff($now, $last_payment_date)->invert==1 ? 
									  date_diff($now, $last_payment_date)->days:
									  0;
					
					$over_due = (($daily_payment * ($behind_payment)) + $last_payment_remaining_due);
					
					$due_plus_overdue = $daily_payment + $over_due;
					
					$total_payments_before_this_date = $wpdb->get_row(
					"Select `id`,`loan_id`,`client_id`,`payment_date`,`date_added`,`last_update`,`collector`, sum(amount) as amount 
						From gm_tbl_payments Where loan_id=".$payments->loan_id." and date_added<'".$payments->date_added."'"
					);
					
					
					$payment_lastday = date_create(date("Y-m-d", strtotime(show_loan_detail_by("id", $payments->loan_id)->payment_lastday)));
					
					
					$current_payment = $wpdb->get_row(
					"Select `id`,`loan_id`,`client_id`,`payment_date`,`date_added`,`last_update`,`collector`, sum(amount) as amount 
						From gm_tbl_payments Where loan_id=".$payments->loan_id." and date_added='".$payments->date_added."'"
					);
					
					//---get if payment already lapsed and remaining days to pay
					$payment_lapsed = date_diff(
					$now,
					$payment_lastday
					)->invert==0 ? 
					"NO" :
					$payment_lapsed = date_diff(
					$now,
					$payment_lastday
					)->days." Payment Lapsed";
					
					
					
					echo '<tr>';
						echo '<td>'.date("m/d/y", strtotime($payments->date_added)).'</td>';
						echo '<td>
							  '.show_clientinfo_by("id", $payments->client_id)->first_name.' 
							  '.show_clientinfo_by("id", $payments->client_id)->middle_name.' 
							  '.show_clientinfo_by("id", $payments->client_id)->last_name.' 
							  '.show_clientinfo_by("id", $payments->client_id)->suffix.'
							  </td>';
						echo '<td>
							 '.show_loan_detail_by("id", $payments->loan_id)->release_date.'
							  </td>';
						echo '<td>
							 '.show_loan_detail_by("id", $payments->loan_id)->terms.' '.$mod_pay.'
							  </td>';
						echo '<td>
							 '.number_format($pn,2).'
							  </td>';
						echo '<td>'.show_loan_detail_by("id", $payments->loan_id)->mop.'</td>';
						echo '<td>
							 '.number_format($daily_payment,2).'
							  </td>';
						echo '<td>
							 '.number_format($pn-$total_payments_before_this_date->amount, 2).'
							  </td>';
						echo '<td>
							 '.number_format($daily_payment,2).'
							  </td>';
						echo '<td>'.number_format($over_due, 2).'</td>';
						echo '<td>'.number_format($due_plus_overdue, 2).'</td>';
						echo '<td>'.number_format($total_payments_before_this_date->amount, 2).'</td>';
						echo '<td>'.number_format(($pn-$total_payments_before_this_date->amount)-$current_payment->amount, 2).'</td>';
						echo '<td>'.$payment_lapsed.'</td>';
						echo '<td>'.number_format($current_payment->amount,2).'</td>';
					echo '</tr>';
					$total_payments += $current_payment->amount;
				}
				echo '<tr><td colspan="15" align="right"><strong>Grant Total: </strong> '.number_format($total_payments,2).'</td></tr>';
			}
			?>
			
			</table>
			<?php
			
			}else{
				echo create_error_msg($error_msg);	
			}
		}elseif(isset($_GET['secure'])&&$_GET['secure']&&wp_verify_nonce($_GET['secure'], "secureDailyViewLedger")){
			
		if(isset($_GET['loan_id'])&&$_GET['loan_id']){
			$released_loan_info = $wpdb->get_row($wpdb->prepare(
			"Select * From gm_tbl_loans Where id=%d", stripslashes($_GET['loan_id'])
			));
			$loan_info = $wpdb->get_row($wpdb->prepare(
			"Select * From gm_tbl_loan_app Where id=%d", $released_loan_info->application_id
			));
			$client_id = $loan_info->client_id;
			$get_record = $wpdb->get_row($wpdb->prepare(
			"Select * From gm_tbl_clientinfo Where id=%d", $client_id
			));
			
			$mod_pay = str_replace("Dai", "Day", str_replace("ly", "/s", $released_loan_info->mop));
			$interest = (100 + $released_loan_info->interest) / 100;
			$loan_with_interest = $released_loan_info->amount * $interest;
			$daily_payment = $loan_with_interest / $released_loan_info->terms;
			?>
            <style type="text/css">
			.collum-ko {
				-webkit-box-flex: 0;
				-ms-flex: 0 0 20%;
				flex: 0 0 20%;
				max-width: 20%;
			  }
			  .collum-ko table {
				margin:0px auto;
				width:90%;
			  }
            @media print{@page {size: landscape}}
            </style>
			<h5 align="center">Daily View Ledger</h5>
			<hr />
			<div class="row justify-content-md-center" id="loan-info">
				<div class="col-md-8">
					<div class="row justify-content-md-left">
						<div class="col-md-6">Name: <?php echo $get_record->first_name.' '.$get_record->middle_name.' '.$get_record->last_name.' '.$get_record->suffix; ?></div>
                        <div class="col-md-6">Terms: <?php echo $released_loan_info->terms." ".$mod_pay; ?></div>
					</div>
					<div class="row justify-content-md-left">
						<div class="col-md-6">Address: <?php echo $get_record->first_address.' '.$get_record->barangay.' '.$get_record->town_city.' '.$get_record->province; ?></div>
                        <div class="col-md-6">Daily Payment: <?php echo number_format($daily_payment, 2); ?></div>
					</div>
                    <div class="row justify-content-md-left">
						<div class="col-md-6">Loan of Payment with Interest: <?php echo number_format($loan_with_interest, 2) ?></div>
                        <div class="col-md-6">Payment Start on: <?php echo date("F d, Y", strtotime("+1 day", strtotime($released_loan_info->release_date))); ?></div>
					</div>
                    <div class="row justify-content-md-left">
						<div class="col-md-6">Date Released: <?php echo date("F d, Y", strtotime($released_loan_info->release_date)); ?></div>
                        <div class="col-md-6">Signature: </div>
					</div>
                    <div class="row justify-content-md-left">
						<div class="col-md-6">Valid I.D. Presented: </div>
                        <div class="col-md-6">Valid I.D. # </div>
					</div>
					
				</div>
			</div><hr />
            <div class="row">
            	<div class="col-md-12">
                <?php
				switch($released_loan_info->mop){
					case "Daily":
						$basta = "1 day";
						break;
					case "Weekly":
						$basta = "1 week";
						break;
					case "Monthly":
						$basta = "1 month";
						break;
					case "Quaterly":
						$basta = "3 months";
						break;
					case "Semi-Annual":
						$basta = "6 months";
						break;
					case "Annual":
						$basta = "1 year";
						break;
					default:
						$basta = "1 day";
				}
				$payment_start = date_create(date("Y-m-d", strtotime("+".$basta."", strtotime($released_loan_info->release_date))));
				$payment_end = date_create(date("Y-m-d", strtotime($released_loan_info->payment_lastday)));
				$diff = date_diff($payment_end, $payment_start);
				$terms_col = $released_loan_info->terms / 5;
				$balance = $loan_with_interest;
				$total_paid = 0;
				
				$adv_payments = $wpdb->get_results($wpdb->prepare(
				"Select `id`,`loan_id`,`client_id`,`payment_date`,`date_added`,`last_update`,`collector`, sum(amount) as amount 
				From gm_tbl_payments Where loan_id=%d and client_id=%d and date_added<%s GROUP by date_added",
				$released_loan_info->id, $released_loan_info->client_id, date("Y-m-d", strtotime(date_format($payment_start, "M d, Y")))
				));
				$overdue_payments = $wpdb->get_results($wpdb->prepare(
				"Select `id`,`loan_id`,`client_id`,`payment_date`,`date_added`,`last_update`,`collector`, sum(amount) as amount 
				From gm_tbl_payments Where loan_id=%d and client_id=%d and date_added>%s GROUP by date_added",
				$released_loan_info->id, $released_loan_info->client_id, date("Y-m-d", strtotime(date_format($payment_end, "M d, Y")))
				));
				
				if($adv_payments){
					echo '<h6>Advanced Payments</h6>';
					echo '<table border="1" width="100%">';
					echo '<thead class="blue-grey">';
						echo '<th>Date</th>';
						echo '<th>Paid</th>';
						echo '<th>Applied</th>';
						echo '<th>Balance</th>';
					echo '</thead>';
					foreach($adv_payments as $adv){
						
						$balance -= $adv->amount;
						echo '<tr>';
						echo '<td>'.date("m/d/Y", strtotime($adv->date_added)).'</td>';
						echo '<td>'.number_format($adv->amount,2).'</td>';
						echo '<td></td>';
						echo '<td>'.number_format($balance,2).'</td>';
						echo '</tr>';	
					}
					echo '</table><br/>';
					
				}
				
				
				
				
				echo '<div class="row">';
				$ctrko = 0;
				$prev_date = "";
				$next_date = "";
				for($c=1;$c<=5;$c++){
					echo '<div class="collum-ko">';
					echo '<table border="1">';
					echo '<thead class="blue-grey">';
						echo '<th>Date</th>';
						echo '<th>Paid</th>';
						echo '<th>Applied</th>';
						echo '<th>Balance</th>';
					echo '</thead>';
						$aaa = 0;
						$b = 0;
						
						
						
						
						
						for($a=$ctrko;$a<=($released_loan_info->terms-1);$a++){
							switch($released_loan_info->mop){
								case "Daily":
									$basta2 = "".$b+$ctrko." day";
									break;
								case "Weekly":
									$basta2 = "".$b+$ctrko." week";
									break;
								case "Monthly":
									$basta2 = "".$b+$ctrko." month";
									break;
								case "Quaterly":
									$basta2 = "".($b+$ctrko*3)." months";
									break;
								case "Semi-Annual":
									$basta2 = "".($b+$ctrko*6)." months";
									break;
								case "Annual":
									$basta2 = "".$b+$ctrko." year";
									break;
								default:
									$basta2 = "".$b+$ctrko." day";
							}
							
							
							
							
							$check_payments = $wpdb->get_row($wpdb->prepare(
							"Select `id`,`loan_id`,`client_id`,`payment_date`,`date_added`,`last_update`,`collector`, sum(amount) as amount 
							From gm_tbl_payments Where loan_id=%d and client_id=%d and date_added=%s GROUP by date_added",
							$released_loan_info->id, $released_loan_info->client_id, date("Y-m-d", strtotime("+".$basta2."", strtotime(date_format($payment_start, "M d, Y"))))
							));
							
							
							
							$paid_amount = $check_payments->amount;
							$balance -= $paid_amount;
							$balance_empty = $check_payments->amount>0 ? number_format($balance,2) : "-";
							$status = ($daily_payment==$paid_amount&&$check_payments) ? "PAID" : ($check_payments ? "Incomplete" : "Awaiting Payment");
							$paid_amount_str = $paid_amount>0 ? number_format($paid_amount,2) : "-";
							$d_1 = date("m/d/Y", strtotime("+".$basta2."", strtotime(date_format($payment_start, "M d, Y"))));
							
							$d_a = date("m/d/Y", strtotime("+".$basta."", strtotime(date_format($payment_start, "M d, Y"))));
							$d_b = date("m/d/Y", strtotime("+".$basta2."", strtotime($d_a)));
							
							$payments = $wpdb->get_row($wpdb->prepare(
							"Select `id`,`loan_id`,`client_id`,`payment_date`,`date_added`,`last_update`,`collector`, sum(amount) as amount 
							From gm_tbl_payments Where loan_id=%d and client_id=%d and payment_date=%s GROUP by payment_date",
							$released_loan_info->id, $released_loan_info->client_id, date("Y-m-d", strtotime($d_1))
							));
							
							$paid_applied_str = $payments->amount>0 ? number_format($payments->amount,2) : "-";
							echo '<tr>';
							echo '<td>'.$d_1.'</td>';
							echo '<td>'.$paid_amount_str.'</td>';
							echo '<td>'.$paid_applied_str.'</td>';
							echo '<td>'.$balance_empty.'</td>';
							echo '</tr>';
							$aaa++;
							$b += 1;
							
							$check_payments_2 = $wpdb->get_results($wpdb->prepare(
							"Select `id`,`loan_id`,`client_id`,`payment_date`,`date_added`,`last_update`,`collector`, sum(amount) as amount 
							From gm_tbl_payments Where loan_id=%d and client_id=%d and date_added BETWEEN '%s' and '%s' GROUP by date_added",
							$released_loan_info->id, $released_loan_info->client_id, date("Y-m-d", strtotime(date("Y-m-d",strtotime("+1 day", strtotime($d_1))))), date("Y-m-d", strtotime(date("Y-m-d", strtotime("-1 day", strtotime($d_b)))))
							));
							
							/*printf("Select `id`,`loan_id`,`client_id`,`payment_date`,`date_added`,`last_update`,`collector`, sum(amount) as amount 
							From gm_tbl_payments Where loan_id=%d and client_id=%d and date_added BETWEEN '%s' and '%s' GROUP by date_added",
							$released_loan_info->id, $released_loan_info->client_id, date("Y-m-d", strtotime(date("Y-m-d",strtotime("+1 day", strtotime($d_1))))), date("Y-m-d", strtotime(date("Y-m-d", strtotime("-1 day", strtotime($d_b)))))
							);*/
							
							if($check_payments_2){
								foreach($check_payments_2 as $cp2){
									if($released_loan_info->payment_lastday>=$cp2->date_added){
										$balance -= $cp2->amount;
										echo '<tr>';
										echo '<td>'.date("m/d/Y", strtotime($cp2->date_added)).'</td>';
										echo '<td>'.number_format($cp2->amount,2).'</td>';
										echo '<td></td>';
										echo '<td>'.number_format($balance,2).'</td>';
										echo '</tr>';	
									}
								}	
							}
							
							
							
							
							if($aaa>=$terms_col){
								$ctrko += $aaa;
								break;
							}
							
						}
					
					echo '</table>';
					
					echo '</div>';
				}
				echo '</div>';
				if($overdue_payments){
					echo '<br/>';
					echo '<h6>Over Due Payments</h6>';
					echo '<table border="1" width="100%">';
					echo '<thead class="blue-grey">';
						echo '<th>Date</th>';
						echo '<th>Paid</th>';
						echo '<th>Applied</th>';
						echo '<th>Balance</th>';
					echo '</thead>';
					foreach($overdue_payments as $odp){
						
						$balance -= $odp->amount;
						echo '<tr>';
						echo '<td>'.date("m/d/Y", strtotime($odp->date_added)).'</td>';
						echo '<td>'.number_format($odp->amount,2).'</td>';
						echo '<td></td>';
						echo '<td>'.number_format($balance,2).'</td>';
						echo '</tr>';	
					}
					echo '</table><br/>';
					
				}
				?>
                
                </div>
            </div>
			<?php
			}
		}else{
			echo create_error_msg("Access Denied!");
		}
		?>
        </div>
    </div>
</div>
<script language="javascript" src="<?php echo get_template_directory_uri() ?>/mdb-new/js/mdb.min.js"></script>
