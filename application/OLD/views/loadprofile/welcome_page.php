		  <div class="page-content">
	  			<div class="row">
				<div class="col-md-3">
               
             	</div>
					<div class="col-md-6">			
						<div class="panel panel-default">
                          <div class="panel-heading blue-panel"><?=$title;?></div>
<div class="panel-body">
					            <div class="panel-title"><div style="font-size:16px; height:30px !important; font-weight:bold; text-align:center;">Welcome! <?php echo $_SESSION['user_name'] ?></div></div>
			  				<p><b style="font-size:16px;">Status</b></p>
							<div  style="margin-left: 50px; margin-top: 10px;">
							
							
							<?php 

							$limit =  (count($logs_row))-1;
							
							$n = 1;
							foreach ($logs_row as $logs_rows):
							@$log_col = explode(": ", $logs_rows);
							echo "<p align='left'><b>" . @$log_col[0] . " : </b> " . @$log_col[1] . "</p>";
							if ($n++ == $limit) break; 
							endforeach;
							?>
                            </div>
                            </div>                        </div>	  				
                        </div>
	  									
	  			</div>
		  </div>
		</div>
    </div>