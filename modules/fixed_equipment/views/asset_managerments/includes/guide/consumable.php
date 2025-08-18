						<?php 
						$file_header = array();
						$file_header[] = '<span class="text-danger">*</span>'._l('fe_consumables_name');
						$file_header[] = _l('fe_model_no');
						$file_header[] = _l('fe_item_no');
						$file_header[] = _l('fe_order_number');
						$file_header[] = _l('fe_purchase_cost'); 
						$file_header[] = _l('fe_purchase_date').' (mm/dd/YYYY)';
						$file_header[] = '<span class="text-danger">*</span>'._l('fe_quantity');
						$file_header[] = _l('fe_min_quantity');
						$file_header[] = '<span class="text-danger">*</span>'._l('fe_category_id');
						$file_header[] = _l('fe_manufacturer_id');
						$file_header[] = _l('fe_location_id');
						?>					
						<div class="table-responsive no-dt">
							<table class="table table-hover table-bordered">
								<thead>
									<tr>
										<?php
										$total_fields = 0;

										for($i=0;$i<count($file_header);$i++){
											?>
											<th class="bold">
												<?php echo fe_htmldecode($file_header[$i]) ?> 
											</th>
											<?php
											$total_fields++;
										}

										?>

									</tr>
								</thead>
								<tbody>
									<?php for($i = 0; $i<1;$i++){
										echo '<tr>';
										for($x = 0; $x<count($file_header);$x++){
											echo '<td>- </td>';
										}
										echo '</tr>';
									}
									?>
								</tbody>
							</table>
						</div>
						<div class="alert alert-warning">
							<h4 class="alert-heading"><?php echo _l('fe_notes'); ?></h4>
							<p>
								<?php echo '1. '._l('fe_fields_marked_with_are_required_to_enter'); ?><br>
								<?php echo '2. '._l('fe_for_columns_like_category_id_manufacturer_id_supplier_id_location_id'); ?><br>
							</p>
							<hr class="mtop10 mbot10">
							<p>
								<?php echo _l('fe_format_the_excel_file'); ?>
							</p>
						</div>