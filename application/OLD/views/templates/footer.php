<!--
<script type="text/javascript">
$(document).ready(function() {
	function read_meter_1(){

				$.ajax({
					type: "GET",
					url: "<?php// echo base_url(); ?>" + "index.php/admin_post/date_time_details" ,
					success : function(csv) { 
					readings = csv.split(/\n/g);
						date_info = readings[0];
						time_info = readings[1];
						$('#date_time_info').text(date_info);
					}
				});	
			}	
	read_meter_1(); // This will run on page load
	setInterval(function(){
	   read_meter_1() // this will run after every 5 seconds
	}, 1000); 
		 });
		 
</script>
-->
<style>
#date_time_info {
    font-size: 14px;
    font-weight: bold;
    color: #FFF;
    background-color: #167354;
    padding: 10px;
    position: fixed;
    right: 0;
    bottom: 0;
    border-bottom-left-radius: 30px;
    border-top-left-radius: 30px;
}
</style>

<!--<div id="date_time_info"></div>-->
    </body>
    </html>