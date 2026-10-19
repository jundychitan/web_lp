<div class="page-content">
<div class="row">
<div class="col-md-4">
<div class="panel panel-default">
<div class="panel-heading blue-panel"><?=$title_1;?></div>
<div class="panel-body panel-body-custom" style="overflow:hidden;">
<div class="row"> 
<div class="col-md-12">
                <label>Select Site</label><div id="sub_group_message"></div>
                <select class="form-control" id="location_id" name="location_id" onchange="selected_location()"/>
                <option>Please Select Site</option>
                <?php foreach ($site_list as $site_list_cols):?>
                <option value="<?=$site_list_cols['meter_site_id'];?>"><?=$site_list_cols['site_code'];?> | <?=$site_list_cols['site_name'];?></option>
                <?php endforeach;?> 
                </select>
</div>
</div>
<div class="row">
<div class="col-md-8">
<div class="form-group">
<div id="display_meter_list">
<label>Company/Meter ID</label>
<input type="text" class="form-control" id="company_id" name="company_id" list="meter_list" size="20" autocomplete="off" onchange="load_company_file()"> 


</div>
</div>
</div>
<div class="col-md-4">
<label>Page</label>
<input type="number" id="page_no" class="form-control" name="page_no" onchange="load_company_file()" value="1" min="1">
</div>
</div>
<div class="row">
<div class="col-md-12">
<div id="company_file_table"></div>
</div>
</div>
</div>
</div>
 </div>
<div class="col-md-8">
<div class="panel panel-default">
<div class="panel-heading blue-panel"><?=$title_2;?></div>
<div class="panel-body panel-body-custom" style="overflow:hidden;">

<div id="table_data"></div>

</div>
</div>
</div>

</div> 
</div>
</div>
</div>
<script type="application/javascript">
function selected_location()
{
var location_id = $("#location_id").val();
jQuery.ajax({
type: "POST",
url: "<?php echo base_url(); ?>" + "index.php/admin_load_data/select_location_meter_list",
dataType: 'html',
data: {'location_id': location_id },
success : function(result) { 

$('#display_meter_list').html(result);


},
beforeSend:function()
{
 $("#display_meter_list").html("<label>Meter</label><br /><input type='text'class='form-control' id='meter' name='meter' list='meter_list' size='20' placeholder='***Loading***'>")
}
});
}


function selected_file(id)
{
/*event.preventDefault();*/
jQuery.ajax({
type: "POST",
url: "<?php echo base_url(); ?>" + "index.php/admin_load_data/load_profile_view",
dataType: 'html',
data: {'id': id},
success : function(result) { 
$('#table_data').html(result);
},
beforeSend:function()
{
$("#table_data").html("<div align='center'><b>* * * Loading * * *</b></div>")
}
});

}

function load_company_file() {

company_id=$("#company_id").val();
page_no=$("#page_no").val();

if (window.XMLHttpRequest) {
/* code for IE7+, Firefox, Chrome, Opera, Safari*/
xmlhttp = new XMLHttpRequest();
} else {
/* code for IE6, IE5*/
xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
}
xmlhttp.onreadystatechange = function() {
if (this.readyState == 4 && this.status == 200) {
document.getElementById("company_file_table").innerHTML = this.responseText;
}
};
xmlhttp.open("GET","<?php echo base_url(); ?>" + "index.php/admin_load_data/load_company_file_lp?company_id="+company_id+"&offset="+page_no,true);
xmlhttp.send();
}
/*window.onload = show_switch_status_table;*/

</script>
