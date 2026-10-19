<nav class="navbar navbar-default navbar-fixed-top">
 <div class="container">
<div class="navbar-header">
<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
<span class="sr-only">Toggle navigation</span>
<span class="icon-bar"></span>
<span class="icon-bar"></span>
<span class="icon-bar"></span>
 </button>
 <a class="navbar-brand navbar-brand-style" href="#">
 <div align="center">
 <img src="<?php echo base_url(); ?>templates/layouts/images/logo-navs.png" alt="" width="50px">
 <br />
 <!----><b>Dychitan Electronics Corporation</b>
 </div>
 </a>
</div>

<div id="navbar" class="navbar-collapse collapse">
<style>
.menu-width{
width: 100px !important;
text-align: center;
}

</style>
 <ul class="nav navbar-nav">
 <!--<li><a href="<!?php echo base_url(); ?>index.php/load_profile/history_log"><div class="menu-width">History Log</div></a></li>-->
 <li><a href="<?php echo base_url(); ?>index.php/load_profile/load_profile_log"><div class="menu-width">Load Profile</div></a></li>
<li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><div class="menu-width">Report<span class="caret"></span></div></a>
    <ul class="dropdown-menu">
    <li><a href="<?php echo base_url(); ?>index.php/load_profile/load_profile_report"><div class="menu-width">Load Profile Logs</div></a></li><li role="separator" class="divider"></li>  
    <li><a href="<?php echo base_url(); ?>index.php/load_profile/load_profile_status">Load Profile Status</a></li>
    <li role="separator" class="divider"></li> 
    </ul>
    </li>
<li class="dropdown">
	<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Hi <?=$_SESSION['user_name'];?>!&nbsp;&nbsp;<span style="color:red; font-weight:bold; font-size:18px">|</span>&nbsp;&nbsp;<span class="glyphicon glyphicon-cog"></span>&nbsp;<!--<span class="caret"></span>--></a>
    <ul class="dropdown-menu"> 
    <li><a href="<?php echo base_url()."index.php/Login/logout"; ?>">Logout</a></li>
    <li role="separator" class="divider"></li>
    </ul>
</li>
 </ul>
</div><!--/.nav-collapse -->
 </div>
</nav>
<br /><br /><br />