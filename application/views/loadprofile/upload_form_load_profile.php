		  <div class="page-content">
	  			<div class="row">
				<div class="col-md-3"><!--
<div class="sidebar content-box" style="display: block;">
                <ul class="nav">
                    <!-- Main menu 
                    <li style="text-align:center;"><a href="#">CSV FILE</a></li>
                      <li class="current"><a href="<?php// echo base_url(); ?>upload/upload_copy/meter_batch.csv"><i>&bull;</i>Download</a></li>
                </ul>
                &nbsp;
             </div>    -->         	
             </div>
					<div class="col-md-6">			
						<div class="panel panel-default">
                          <div class="panel-heading blue-panel"><?=$title;?></div>
			  				<div class="panel-body">
							
                           <?php echo $this->session->flashdata('msg_success'); ?>
                            <?php echo form_open_multipart("admin/do_upload_load_profile/", 'class="form-control-overflow"');?>
									<div class="row">
									<div class="col-md-3">
										&nbsp;
								    </div>									
									<div class="col-md-6">
										<label>Upload</label>
										<input type="file" name="userfile" id="userfile"  class="form-control" size="20" accept=".csv,.CSV.,txt,.TXT" />
								    </div>
							
									<div class="col-md-3">
										&nbsp;
								    </div>
									
									</div>
                                    
									<hr class="user-orange-hr">
								
									<div class="row">									
									<div class="col-md-3">
										&nbsp;
								    </div>
									<div class="col-md-6">
									<input type="submit" value="Upload" class="btn btn-primary"/>		
								    </div>								
									<div class="col-md-3">
										&nbsp;
								    </div>								
									</div>								

								</form>
			  				</div>
                          </div>	  				
                        </div>
	  									
	  			</div>
		  </div>
		</div>
    </div>