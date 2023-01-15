<?php

/* Settings */

//Language
$language = "en";
include "lang/lang.$language.php";

//Twitch Username
$twitch_user = "chrischicken1992";

//Database
$usedatabase = true;
if($usedatabase == true) {
	include "config.php";
}

//Date Format
$date_format = "d.m.Y";
$time_format = date('H:i:s') . $text['VIEWER_LAST_REFRESH_CLOCK'];

//Empty Variables
$zuschauer = "";
$newuser_mods = "";
$newuser_vips = "";
$newuser_viewer = "";

?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo $text['VIEWER_TITLE']; ?> (<?php echo $twitch_user; ?>)</title>
	<link href="css/viewer.white.css" rel="stylesheet">
	<link href="css/viewer.dark.css" rel="stylesheet" id="dark" disabled>
	<!-- load jQuery and tablesorter scripts -->
	<script type="text/javascript" src="js/jquery-3.6.3.min.js"></script>
	<script type="text/javascript" src="js/jquery.tablesorter.js"></script>
	<script type="text/javascript" src="js/widgets/widget-filter.min.js"></script>
	<script type="text/javascript" src="js/widgets/widget-cssStickyHeaders.min.js"></script>
	<!-- Autorefesh after 300 Seconds (5 Minutes) -->
	<meta http-equiv="refresh" content="300">
</head>
<body onload="scroll()">
<?php

// Fetch chatters
$url = "http://tmi.twitch.tv/group/user/$twitch_user/chatters";  // Don't use https here
$users = json_decode(file_get_contents($url), true)['chatters'];
$count_viewer = json_decode(file_get_contents($url), true)['chatter_count'];

//Dont count myself
if (empty($users['broadcaster'])) {
	$me = 0;
} else {
	$me = 1;
}

//Mods
foreach ($users['moderators'] as $viewer) {
	if($usedatabase == true) {
		//Check, if User exits in Database
		$sql_user = "SELECT * FROM twitch_chatuser WHERE tc_user = '$viewer'";
		$stmt_user = $pdo_twitch -> prepare($sql_user);
		$stmt_user -> execute();			
			if ($data_user = $stmt_user -> fetch()){
				//If User is added today, then is still new User today
				if(date('Ymd', strtotime($data_user['tc_added'])) == date('Ymd')) {
					$newuser_mods = "<font color='red'><b>(" . $text['VIEWER_NEWVIEWER'] . ")</b></font>";
				} else {					
					$newuser_mods = "(" . $text['VIEWER_SINCE'] . " " . date($date_format, strtotime($data_user['tc_added'])) . ")";
				}
			} else {
				//Add User in Database with Datetime
				$added = date('Y-m-d H:i:s');
				$sql_ins = "insert into twitch_chatuser (tc_user, tc_added) values (:tc_user, :tc_added)";
				if ($stmt_ins = $pdo_twitch->prepare($sql_ins)) {
					$param = array(
						':tc_user' => $viewer,
						':tc_added' => $added
					);
				$stmt_ins -> execute($param);
				}
				$newuser_mods = "<font color='red'><b>(" . $text['VIEWER_NEWVIEWER'] . ")</b></font>";
			}
	}
	$zuschauer .= "<tr id='$viewer'><td><button class='remove' title='" . $text['VIEWER_TITLE_HIDE'] . "'>❌</button></td><td colspan='2'><a href='https://twitch.tv/$viewer' class='link' target='_blank'><span title='" . $text['VIEWER_TITLE_MOD'] . "'>⚔️</span> " . $viewer . "</a> $newuser_mods</td></tr>";
}

//VIPs
foreach ($users['vips'] as $viewer) {
	if($usedatabase == true) {
		$sql_user = "SELECT * FROM twitch_chatuser WHERE tc_user = '$viewer'";
		$stmt_user = $pdo_twitch -> prepare($sql_user);
		$stmt_user -> execute();			
			if ($data_user = $stmt_user -> fetch()){
				if(date('Ymd', strtotime($data_user['tc_added'])) == date('Ymd')) {
					$newuser_vips = "<font color='red'><b>(" . $text['VIEWER_NEWVIEWER'] . ")</b></font>";
				} else {					
					$newuser_vips = "(" . $text['VIEWER_SINCE'] . " " . date($date_format, strtotime($data_user['tc_added'])) . ")";
				}
			} else {
				$added = date('Y-m-d H:i:s');
				$sql_ins = "insert into twitch_chatuser (tc_user, tc_added) values (:tc_user, :tc_added)";
				if ($stmt_ins = $pdo_twitch->prepare($sql_ins)) {
					$param = array(
						':tc_user' => $viewer,
						':tc_added' => $added
					);
				$stmt_ins -> execute($param);
				}
				$newuser_vips = "<b><font color='red'>(" . $text['VIEWER_NEWVIEWER'] . ")</b></font>";
			}
	}
	$zuschauer .= "<tr id='$viewer'><td><button class='remove' title='" . $text['VIEWER_TITLE_HIDE'] . "'>❌</button></td><td colspan='2'><a href='https://twitch.tv/$viewer' class='link' target='_blank'><span title='" . $text['VIEWER_TITLE_VIP'] . "'>💎</span> " . $viewer . "</a> $newuser_vips</td></tr>";
}

//Viewer
foreach ($users['viewers'] as $viewer) {
	if($usedatabase == true) {
		$sql_user = "SELECT * FROM twitch_chatuser WHERE tc_user = '$viewer'";
		$stmt_user = $pdo_twitch -> prepare($sql_user);
		$stmt_user -> execute();			
			if ($data_user = $stmt_user -> fetch()){
				if(date('Ymd', strtotime($data_user['tc_added'])) == date('Ymd')) {
					$newuser_viewer = "<font color='red'><b>(" . $text['VIEWER_NEWVIEWER'] . ")</b></font>";
				} else {					
					$newuser_viewer = "(" . $text['VIEWER_SINCE'] . " " . date($date_format, strtotime($data_user['tc_added'])) . ")";
				}
			} else {
				$added = date('Y-m-d H:i:s');
				$sql_ins = "insert into twitch_chatuser (tc_user, tc_added) values (:tc_user, :tc_added)";
				if ($stmt_ins = $pdo_twitch->prepare($sql_ins)) {
					$param = array(
						':tc_user' => $viewer,
						':tc_added' => $added
					);
				$stmt_ins -> execute($param);
				}
				$newuser_viewer = "<font color='red'><b>(" . $text['VIEWER_NEWVIEWER'] . ")</b></font>";
			}
	}
	$zuschauer .= "<tr id='$viewer'><td><button class='remove' title='" . $text['VIEWER_TITLE_HIDE'] . "'>❌</button></td><td colspan='2'><a href='https://twitch.tv/$viewer' class='link' target='_blank'>" . $viewer . "</a> $newuser_viewer</td></tr>";
}

?>
<div id="tabelle" class="wrapper">

	<table class="customTable" id="tablesorter">
		<thead>
			<tr>
				<th width="2%" data-sorter="false">
					<a href="#" onclick="showAll();" id="auge" style="text-decoration:none;" title="<?php echo $text['VIEWER_TITLE_EYE']; ?>"></a>
				</th>
				<th width="96%">
					<?php echo $text['VIEWER_TABLE_HEADER']; ?> (<?php echo $count_viewer-$me; ?>)<br />
					<input class="search" type="search" data-column="1" placeholder="<?php echo $text['VIEWER_TABLE_FILTER_PLACEHOLDER']; ?>">
				</th>
				<th width="2%" data-sorter="false">
					<a href="javascript:document.location.reload(true);" style="text-decoration:none;" title="<?php echo $text['VIEWER_TITLE_REFRESH']; ?>">🔄</a>
				</th>
			</tr>
		</thead>
		<tbody>
			<?php echo $zuschauer; ?>
			
			<!-- Debug 
			
				<tr><td colspan="3">Test1</td></tr>
				<tr><td colspan="3">Test1</td></tr>
				<tr><td colspan="3">Test1</td></tr>
				<tr><td colspan="3">Test1</td></tr>
				<tr><td colspan="3">Test1</td></tr>
				<tr><td colspan="3">Test1</td></tr>
				<tr><td colspan="3">Test1</td></tr>
				<tr><td colspan="3">Test1</td></tr>
				<tr><td colspan="3">Test1</td></tr>
				<tr><td colspan="3">Test1</td></tr>
				<tr><td colspan="3">Test1</td></tr>
				<tr><td colspan="3">Test1</td></tr>
				<tr><td colspan="3">Test1</td></tr>
				<tr><td colspan="3">Test1</td></tr>
				
			-->
				
		</tbody>
	</table>

</div>

<div id="footer-left"><?php echo $text['VIEWER_LAST_REFRESH']; ?> <?php echo $time_format; ?>

	<div id="footer-right">
		<!-- Deactivate Autoscroller -->
		<a href='#' onclick='scroll_on_off();' style="text-decoration:none;" title="<?php echo $text['VIEWER_TITLE_AUTOSCROLLER']; ?>">⬇️</a>
		<!-- Toggle Dark Mode -->
		<a href='#' id='dark-mode-text' onclick='changeDarkMode();' style="text-decoration:none;" title="<?php echo $text['VIEWER_TITLE_DARKMODE']; ?>">🌙</a>
	</div>

</div>

	<script type="text/javascript" src="js/viewer.js"></script>

</body>
</html>