<div class="page-content">
<div class="row">
<div class="col-md-4">
<div class="panel panel-default">
<div class="panel-heading blue-panel"><?=$title_1;?></div>
<div class="panel-body" style="height:515px; overflow:hidden">
<div class="row">
<div class="col-md-8">
<label>Company</label>
<select id="company_id" class="form-control" name="company_id" onchange="load_company_file()">
<option value="NA">Pls. Select</option>
<?=$company_id_name_list;?> 
</select>
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
<div class="panel-body" style="height:515px; overflow:hidden">

<div id="table_data"></div>

</div>
</div>
</div>

</div>
</div>
</div>
</div>
<script type="application/javascript">
function selected_file(id)
{
/*event.preventDefault();*/
jQuery.ajax({
type: "POST",
url: "<?php echo base_url(); ?>" + "index.php/admin_load_data/load_profile_import_to_tb",
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
