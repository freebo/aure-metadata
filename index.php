<?php
// Based on alphamusk's AWS metadata page htip
// Enable better php debugging
ini_set('display_errors', 'On');
//error_reporting(E_ALL | E_STRICT);
error_reporting(E_ALL);
// Credit
$author_name = 'Mike Freeman';
$author_version = 'v0.91';
$author_email = 'freeman.mj@gmail.com';
$author_project = 'Azure Metadata PHP Page';

$curl_cmd = 'curl -H Metadata:true';
$meta_host = '169.254.169.254';


$json_meta =  exec( $curl_cmd." \"http://".$meta_host."/metadata/instance?api-version=2017-04-02\"");
$metad = json_decode($json_meta, true);


$load = sys_getloadavg();

//Get the system uptime
$str   = @file_get_contents('/proc/uptime');
$num   = floatval($str);
$secs  = $num %60; $num = intdiv($num, 60);
$mins  = $num % 60;      $num = intdiv($num, 60);
$hours = $num % 24;      $num = intdiv($num, 24);
$days  = $num;

$server_name = $_SERVER['SERVER_NAME'];
$server_ip = $metad['network']['interface'][0]['ipv4']['ipAddress'][0]['publicIpAddress'];
$server_software = $_SERVER['SERVER_SOFTWARE'];
$client_ip = $_SERVER['REMOTE_ADDR'];
$client_agent = $_SERVER['HTTP_USER_AGENT'];
$page_title =  'Azure - ' . $server_name;
$php_self = $_SERVER['SCRIPT_NAME'];
$git_url = 'https://github.com/freebo/aure-metadata';


/** Check for page refresh, defaults to 5 mins **/
if (empty($_GET['refresh'])) {
	 $page_refresh = 300;
   } else {
	 $page_refresh = $_GET['refresh'];
}
/** See if stress is running to set the toggle switch **/
exec("pgrep stress", $output, $return);
if ($return == 0) {
	$stressed = "yes";
}
else {
	$stressed = "no";
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<script type='text/javascript' src='//code.jquery.com/jquery-1.10.1.js'></script>

<head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
<title><?php echo $author_project.' '.$author_version; ?></title>
<meta http-equiv="refresh" content="<?php echo $page_refresh; ?>" />
<meta http-equiv="Content-Language" content="en-us" />

<meta http-equiv="imagetoolbar" content="no" />
<meta name="MSSmartTagsPreventParsing" content="true" />

<meta name="description" content="Description" />
<meta name="keywords" content="Keywords" />

<meta name="author" content="<?php echo $author_name; ?>" />

<style type="text/css" media="all">@import "css/master.css";</style>

</head>

<body class="about">
<div id="page-container">
	<div id="header">
		<div id="logo">
			<h1>Microsoft Azure</h1>		
		</div>
	</div>
	<div id="main-nav">
		<div id="links">
			<ul>
				<li><span>Refresh</span></li>
				<li><a href="<?php echo $php_self.'?refresh=3'; ?>">3s</a></li>
				<li><a href="<?php echo $php_self.'?refresh=5'; ?>">5s</a></li>
				<li><a href="<?php echo $php_self.'?refresh=30'; ?>">30s</a></li>
				<li><a href="<?php echo $php_self.'?refresh=60'; ?>">1m</a></li>
				<li><a href="<?php echo $php_self.'?refresh=300'; ?>">5m</a></li>
			</ul>
		</div>
	</div>
	<div id="sidebar-a">
		<div class="padding">
			<h2>Azure - Region</h2>
			<h4><p><?php echo strtoupper($metad['compute']['location']); ?></p><br></h4>
			<h3>Fault Domain</h3>
			<h4><p><?php echo $metad['compute']['platformFaultDomain']; ?></p><br></h4>
            <h3>Update Domain</h3>
			<h4><p><?php echo $metad['compute']['platformUpdateDomain']; ?></p><br></h4>
			<h3>Information</h3>
			<p>Server: <?php echo $server_software.'<br>Public IP: ';?><a href="http://<?php echo $server_ip; ?>"><?php echo $server_ip; ?></a></p>
			<p>Client: <?php echo $client_agent.'<br>IP: '.$client_ip; ?></p>
			<div id="button">
				<div class="onoffswitch">
					<input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="myonoffswitch";" 
							<?php if($stressed == 'yes') echo 'checked="checked"';?> >
    					<label class="onoffswitch-label" for="myonoffswitch">
        				<span class="onoffswitch-inner"></span>
        				<span class="onoffswitch-switch"></span>
    					</label>
				</div>
			</div>
		</div>
	</div> <!-- End sidebar-a -->
	
	<div id="content">
		<div class="padding">
			<h2>Azure Metadata</h2>
			<?php
			    //metadata table
			    echo '<table border="0" bgcolor="#ffffff" cellpadding="5" cellspacing="0" width="100%">';
			    echo '<tr><th align="left">Metadata</th><th align="left">Value</th></tr>';

			        echo '<tr>';
			    	echo '<td nowrap><span class="key">'. "VM Name" . '</span></td>';
			            echo '<td no wrap><span class="value">'. $metad['compute']['name'] . '</span></td>';
			        echo '</tr>';
                   
			        echo '<tr>';
			    	
                    echo '<td nowrap><span class="key">'. "VM Size" . '</span></td>';
			            echo '<td no wrap><span class="value">'. $metad['compute']['vmSize'] . '</span></td>';
			        echo '</tr>';

                    echo '<tr>';

			        echo '<td nowrap><span class="key">'. "OS" . '</span></td>';
			            echo '<td no wrap><span class="value">'. $metad['compute']['osType'] . '</span></td>';
			        echo '</tr>';

                    echo '<td nowrap><span class="key">'. "VM ID" . '</span></td>';
			            echo '<td no wrap><span class="value">'. $metad['compute']['vmId'] . '</span></td>';
			        echo '</tr>';

                    echo '<td nowrap><span class="key">'. "Public IP" . '</span></td>';
			            echo '<td no wrap><span class="value">'.$metad['network']['interface'][0]['ipv4']['ipAddress'][0]['publicIpAddress'] . '</span></td>';
			        echo '</tr>';

                    echo '<td nowrap><span class="key">'. "Private IP" . '</span></td>';
			            echo '<td no wrap><span class="value">'. $metad['network']['interface'][0]['ipv4']['ipAddress'][0]['privateIpAddress'] . '</span></td>';
			        echo '</tr>';

					echo '<td nowrap><span class="key">'. "Load Average (1 5 15 min)" . '</span></td>';
			            echo '<td no wrap><span class="value">'. $load[0] . " " . $load[1] . " " . $load[2] . '</span></td>';
			        echo '</tr>';

					echo '<td nowrap><span class="key">'. "Uptime (d:h:m:s)" . '</span></td>';
						echo '<td no wrap><span class="value">'. $days . ":" . $hours . ":" . $mins . ":" . $secs . '</span></td>';
			        echo '</tr>';

			    echo '</table>';
		    	?>
		</div>
		

	</div> <!-- End Content -->


	
	<div id="footer">
		<div id="altnav">
			<a href="<?php echo $git_url; ?>/blob/master/README.md">Readme</a> | 
			<a href="<?php echo $git_url; ?>">Source</a> | 
			<a href="mailto:<?php echo $author_email; ?>?subject=<?php echo $author_project;?>">Contact</a> | 
			<a href="<?php echo $git_url; ?>/blob/master/LICENSE">License</a>
		</div>
		<div id="copyleft">Copyleft &copy; <a href="<?php echo $git_url; ?>"><?php echo $author_project.' '.$author_version;?></a><br />
		Powered by <a href="http://www.php.net/">PHP5</a> and <a href="mailto:<?php echo $author_email;?>?subject=<?php echo $author_project;?>"><?php echo $author_name.' Development'; ?></a>
		</div>
	</div> <!-- End Footer -->

</div> <!-- End Page Container -->
</body>
<!-- Script to post to a script which invokes stress process if toggle clicked -->
<script type='text/javascript'>
$('#myonoffswitch').click(function(){
 $.ajax({
 type: "POST",
 url: "stress.php",
 data: ""

 });
}); 
</script>
</html>