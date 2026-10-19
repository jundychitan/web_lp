<br />
<br />
<script type="text/javascript"> 
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


// Ajax post
$(document).ready(function() {
$("#viewalllogs").click(function(event) {
event.preventDefault();

var date_start = $("#date_start").val();
var date_end = $("#date_end").val();
var date_order = $("#date_order").val();
var meter = $("#company_id").val();
 
jQuery.ajax({
type: "POST",
url: "<?php echo base_url(); ?>" + "index.php/admin_load_data/meter_load_profile_report_post",
dataType: 'html',
data: {'date_start': date_start,'date_end': date_end, 'meter': meter, 'date_order': date_order },
success : function(result) {
$('#report_result').html(result);
},
beforeSend:function()
{
 $("#report_result").html("<b align='center'>* * * Loading * * *</b>")
}
});
});
});

$(document).ready(function() {
try{
initialize();
}catch (e){
alert(e);
}
} );

function initialize(){
try{
$('#date_start').val($.datepicker.formatDate('yy-mm-dd', new Date())+' 00:00:00');
$('#date_end').val($.datepicker.formatDate('yy-mm-dd', new Date())+' 23:59:59');

}catch(e){
alert(e);
}
}

$(function () {

var startDateTextBox = $('#date_start');
var endDateTextBox = $('#date_end');

$('#date_start').datetimepicker({format: 'YYYY-MM-DD HH:mm:ss',});
$('#date_end').datetimepicker({
format: 'YYYY-MM-DD HH:mm:ss',
useCurrent: false //Important! See issue #1075
});
$("#date_start").on("dp.change", function (e) {
$('#date_end').data("DateTimePicker").minDate(e.date);
});
$("#date_end").on("dp.change", function (e) {
$('#date_start').data("DateTimePicker").maxDate(e.date);
});
});

</script>
 <div class="col-md-12"> 
<div class="row">
<div class="col-md-12">
<div class="panel panel-default">
<div class="panel-heading blue-panel"><?php echo $title; ?></div>
<div class="panel-body panel-body-custom">
<!--<div align="right" style="font-size:14px; font-weight:bold; color:#e61111 !important;">
<//?=$back_prev_page;?> 
  </div>-->
<div class="col-md-3"> 

<div id="mobile_view_option">

<form action="" method="get" class="" style="">

<div class="row">           
<div class="col-md-12">

<div class="form-group">
<label>Date Start</label><br />
<input type="text"class="form-control" id="date_start" name="date_start">
</div>

</div>
</div>

<div class="row">           
<div class="col-md-12">

<div class="form-group">
<label>Date End</label><br />
<input type="text"class="form-control" id="date_end" name="date_end">
</div>

</div>
</div>

<div class="row">           
<div class="col-md-12">
<!--LOAD METER LIST -->
<div class="form-group">
<label>Sort Date </label><br />

<select class="form-control" id="date_order" name="date_order">
<option value="ASC">Ascending</option>
<option value="DESC">Descending</option>
</select>
</div>

</div>
</div>

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
</div><div class="row">           
<div class="col-md-12">
<!--LOAD METER LIST -->
<div id="display_meter_list">
<label>Company/Meter ID</label>
<input type="text" class="form-control" id="company_id" name="company_id" list="meter_list" size="20" autocomplete="off" onchange="load_company_file()"> 


</div> 

</div>
</div>
<div class="row">           
<div class="col-md-12">

<div class="form-group">
<button type="submit" name="submit" id="viewalllogs" class="btn btn-primary" style="margin-top:29px;">Submit</button>
</div>

</div>
</div>
</form>

</div>
</div>

<div class="col-md-9">
<div id="report_result">
<div id="mobile_view">
<a href="javascript:hideshow(document.getElementById('mobile_view_option'))" style="background-color:rgba(123, 66, 9, 0.89); width:50px; height:50px; border-top-right-radius:50px; bottom:0; left: 0;position:fixed; z-index:9999;"></a>
</div>
<script type="text/javascript">
function hideshow(which){
if (!document.getElementById)
return
if (which.style.display=="none")
which.style.display="block"
else
which.style.display="none"
}
</script>
</div> 
</div>
</div>
</div>
</div>
</div>
</div>