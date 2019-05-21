<?php
session_start();



if (!isset($_SESSION["bea.NhQjqJfUA"])) 
		{
			header('Location: login.php');
			exit();
		}
if ($_POST!=null)
{
	$command = "python /home/pi/Apps/Thermostat/set.py 2>&1";
	if (isset($_POST['mode']))
	{
		$command = $command . " m=" . $_POST['mode'];
	}
	if (isset($_POST['targetTemp'])){
		$command = $command . " t=" . $_POST['targetTemp'];
	}
	//echo "$command<br>";
	$output=shell_exec($command);
	//echo "$o<br>";
	
}
else
{
	$output=shell_exec('python /home/pi/Apps/Thermostat/get.py 2>&1');
}
//echo $output;
$info= json_decode($output);
$Current_Mode=$info->{'Mode'};
$Current_Temperature=$info->{'Current_Temperature'};
$Current_Humidity=$info->{'Current_Humidity'};
$Status=$info->{'Status'};
$Target_Temperature=$info->{'Target_Temperature'};
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<title>Home Thermostat</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
	<!-- Optional theme -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap-theme.min.css" integrity="sha384-6pzBo3FDv/PJ8r2KRkGHifhEocL+1X2rVCTTkUfGk7/0pbek5mMa1upzvWbrUbOZ" crossorigin="anonymous">
	
	<link rel="apple-touch-icon" sizes="57x57" href="images/apple-icon-57x57.png">
	<link rel="apple-touch-icon" sizes="60x60" href="images/apple-icon-60x60.png">
	<link rel="apple-touch-icon" sizes="72x72" href="images/apple-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="76x76" href="images/apple-icon-76x76.png">
	<link rel="apple-touch-icon" sizes="114x114" href="images/apple-icon-114x114.png">
	<link rel="apple-touch-icon" sizes="120x120" href="images/apple-icon-120x120.png">
	<link rel="apple-touch-icon" sizes="144x144" href="images/apple-icon-144x144.png">
	<link rel="apple-touch-icon" sizes="152x152" href="images/apple-icon-152x152.png">
	<link rel="apple-touch-icon" sizes="180x180" href="images/apple-icon-180x180.png">
	<link rel="icon" type="image/png" sizes="192x192"  href="images/android-icon-192x192.png">
	<link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="96x96" href="images/favicon-96x96.png">
	<link rel="icon" type="image/png" sizes="16x16" href="images/favicon-16x16.png">
	<link rel="manifest" href="images/manifest.json">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="images/ms-icon-144x144.png">
	<meta name="theme-color" content="#ffffff">
	
	
</head>

<body>

<script>
	function doSubmit(mode)
	{
		if(mode!=null){
			document.getElementById("mode").value=mode
		}
		document.getElementById("form").submit();
	}
</script>	
	
<form method="POST" id="form" action="index.php">
<input type='hidden' value='<?=$Current_Mode?>' name='mode' id='mode'/>
<div class="jumbotron text-center">		
		<div class="container">
			<!-- First Row-->
			<div class="row">
				<div class="col-xs-12">
					<font color='green'><h1><?=$Current_Temperature?>&deg;F </h1></font>
					<font color='green'><h3><span class="glyphicon glyphicon-tint" aria-hidden="true"></span><?=$Current_Humidity?>%</h3></font>
				</div>
			</div>
			<!--Second Row-->
			<div class="row">
				<div class="col-xs-12">
					&nbsp;
				</div>
			</div>
			<!-- Third Row-->
			<div class="row">
				<div class="col-xs-12">
					
					<?php if ($Current_Mode== 'AUTO'){ ?>
						<button type="button" class="btn btn-default btn-lg" id="COOL" value="COOL" onClick="doSubmit('COOL')">COOL</button>
						<button type="button" class="btn btn-default btn-lg" id="OFF" value="OFF" onClick="doSubmit('OFF')">&nbsp;OFF&nbsp;</button>
						<button type="button" class="btn btn-default btn-lg" id="HEAT" value="HEAT" onClick="doSubmit('HEAT')">HEAT</button>
						<button type="button" class="btn btn-success btn-lg active" id="AUTO" value="AUTO">AUTO</button>
					<?php }
					elseif ($Current_Mode== 'COOL'){?>
						<button type="button" class="btn btn-primary btn-lg active" id="COOL" value="COOL">COOL</button>
						<button type="button" class="btn btn-default btn-lg" id="OFF" value="OFF" onClick="doSubmit('OFF')">&nbsp;OFF&nbsp;</button>
						<button type="button" class="btn btn-default btn-lg" id="HEAT" value="HEAT" onClick="doSubmit('HEAT')">HEAT</button>
						<button type="button" class="btn btn-default btn-lg" id="AUTO" value="AUTO" onClick="doSubmit('AUTO')">AUTO</button>
					<?php }
					 elseif ($Current_Mode== 'OFF'){?>
						<button type="button" class="btn btn-default btn-lg" id="COOL" value="COOL" onClick="doSubmit('COOL')">COOL</button>
						<button type="button" class="btn btn-warning btn-lg active" id="OFF" value="OFF">&nbsp;OFF&nbsp;</button>
						<button type="button" class="btn btn-default btn-lg" id="HEAT" value="HEAT" onClick="doSubmit('HEAT')">HEAT</button>
						<button type="button" class="btn btn-default btn-lg" id="AUTO" value="AUTO" onClick="doSubmit('AUTO')">AUTO</button>
					<?php }
					 else if ($Current_Mode== 'HEAT'){?>
						<button type="button" class="btn btn-default btn-lg" id="COOL" value="COOL" onClick="doSubmit('COOL')">COOL</button>
						<button type="button" class="btn btn-default btn-lg" id="OFF" value="OFF" onClick="doSubmit('OFF')">&nbsp;OFF&nbsp;</button>
						<button type="button" class="btn btn-danger btn-lg active" id="HEAT" value="HEAT">HEAT</button>
						<button type="button" class="btn btn-default btn-lg" id="AUTO" value="AUTO" onClick="doSubmit('AUTO')">AUTO</button>
					<?php }?>
					
				</div>
			</div>			
		<?php if ($Current_Mode != 'OFF'){ ?>
			<!-- Forth Row-->
			<div class="row">
				<div class="col-xs-12">
					<h1>
							<?php if ($Status=='HEAT'){ ?>
								<font color='red'><span class="glyphicon glyphicon-fire" aria-hidden="true"></span>
							<?php } elseif ($Status=='COOL'){ ?>
								<font color='blue'><span class="glyphicon glyphicon-asterisk" aria-hidden="true"></span>
							<?php } else { ?>
								<font color='black'><span class="glyphicon glyphicon-off" aria-hidden="true"></span>
							<?php } ?>
							<select name="targetTemp" id="targetTemp" onchange="doSubmit()">
								<?
								for ($c=60;$c<=90;$c++){
									echo "<option";
									if ($c==$Target_Temperature) echo " selected ";
									echo ">$c</option>";
								}
								?>
							</select>&deg;F</font>
					</h1>
				</div>
			</div>
		<? } ?>
		</div>
</div>
</form>
	
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js" integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd" crossorigin="anonymous"></script>
</body>

</html>