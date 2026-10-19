<br />
<br />
<script>
function show_sub_group_table() {		
	
		var location_id =  $("#location_id").val();

        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("report_result").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET","<?php echo base_url(); ?>" + "index.php/admin_load_data/meter_load_profile_status_site_post/"+location_id,true);
        xmlhttp.send();
    }
	//window.onload = show_sub_group_table;
</script>					





 <div class="col-md-12"> 
<div class="row">
<div class="col-md-12">
<div class="panel panel-default">
<div class="panel-heading blue-panel"><?php echo $title; ?></div>
<div class="panel-body panel-body-custom">


<div class="col-md-12">
<div class="row">           

<div class="col-md-2">
<!--LOAD METER LIST -->
				<form action="" method="get" class="" style="">
                <label>Select Site</label><div id="sub_group_message"></div>
                <select class="form-control" id="location_id" name="location_id" onchange="show_sub_group_table()"/>
                <option value="NA">Please Select Site</option>
                <?php foreach ($get_location_list as $site_list_cols):?>
                <option value="<?=$site_list_cols['site_code'];?>"><?=$site_list_cols['site_code'];?> | <?=$site_list_cols['site_name'];?></option>
                <?php endforeach;?> 
                </select>
                </form>

</div>
<div class="col-md-6">
</div>
<div class="col-md-4">
</div>
</div>

<div id="report_result">

</div> 
</div>
</div>
</div>
</div>
</div>
</div>