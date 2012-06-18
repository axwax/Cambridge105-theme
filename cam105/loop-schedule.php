<?php
/*
File Description: The Loop for schedule page
Built By: GIGX
Theme Version: 0.6.2
*/
?>
<div class="posts">
                  <?php if ( function_exists('yoast_breadcrumb') && $postcount ==0) {
                     yoast_breadcrumb('<div id="breadcrumbs">','</div>');
                  } ?>
                  <?

require_once $_SERVER['DOCUMENT_ROOT']."/schedule_info/Schedule.class.php"; 

$week = $_GET["week"];

if(!$week) $week = 0;

$now = "now";
//$now = "2011-08-01 06:00";
$displayHeight = 60 * 13;

$offset = "";
if($week > 0)
{
	$offset = "+ $week week";
}
elseif($week < 0)
{
	$offset = "$week week";
}
$width = 7 * 100;
$height = 60 * 24;
$startHour = 6;
$titleHeight = 24;
$sImagesPrefix = "";

//$sUrlSchedule = "https://www.google.com/calendar/feeds/u61evjfscniu614cra1mt85r14%40group.calendar.google.com/public/full?singleevents=true&max-results=1000&start-min=".date('Y-m-d', strtotime("last Monday $offset"))."&start-max=".date('Y-m-d', strtotime("this Sunday + 7 days $offset"))."&orderby=starttime&sortorder=a";
// if this week then get the cached url!
if($week == 0)
{
	//$sUrlSchedule = Schedule::GetGoogleScheduleURL(strtotime("last Monday $offset"), strtotime("this Sunday + 7 days $offset"));
	$sUrlSchedule = Schedule::GetCachedScheduleURL();
}
else
{
	$sUrlSchedule = Schedule::GetGoogleScheduleURL(strtotime("- 7 days $offset"), strtotime("+ 7 days $offset"));
}
$sUrlProgrammes = Schedule::GetProgrammesURL();

$sCloseLocation = "/images/x.gif";

/////////////////////////////////////////////////

function draw_box($entry, $index, $sTop, $sLeft, $sHeight)
{
?>
	<a href="<?=$entry["url"]?>">
	<div class="show_box show_<?=$index?>" style="width: <?=$entry["width"]?>px; height: <?=$entry[$sHeight]?>px; top: <?=$entry[$sTop]?>px; left: <?=$entry[$sLeft]?>px">
	<? if($entry["height"] > 25) : ?>
		<div class="time_inner"><?=date("H:i", $entry["start"])." - ".date("H:i", $entry["end"])?></div>
		<div class="title_inner"><?=htmlspecialchars($entry["title"])?></div>
	<? else : ?>
		<div class="time_inner nowrap"><?=date("H:i", $entry["start"])." - ".date("H:i", $entry["end"])?>: <?=htmlspecialchars($entry["title"])?></div>
	<? endif ?>
	</div>
	</a>
<?
}



$schedule = new Schedule($sUrlSchedule, $sUrlProgrammes);
$entries = $schedule->GetEntries();
//$entries = array();

$rawEntries = $entries;

$startTime = sprintf("%02d", $startHour).":00";

$startDate = strtotime(date("Y-m-d", strtotime("$now - $startHour hours"))." $startTime - ".(intval(date("N", strtotime("$now - $startHour hours"))) - 1)." days $offset");

$endDate = strtotime(date("Y-m-d H:i", $startDate). " + 7 days");

$hourHeight = $height / 24;

$boxWidth = $width / 7;

foreach($entries as &$entry)
{
	
	if($entry["end"] > $startDate && $entry["start"] < $endDate)
	{
		// get number of seconds into the day
		$secsStart = $entry["start"] - strtotime(date("Y-m-d", $entry["start"])." ".$startTime);

		$secsEnd = $entry["end"] - strtotime(date("Y-m-d", $entry["start"])." ".$startTime);
		
		$boxTop = round($secsStart / 60 / 60 / 24 * $height);
		$boxBottom = round($secsEnd / 60/ 60 / 24 * $height);
		
		$boxHeight = $boxBottom - $boxTop - 1;
	
		$dayIndex = (date("N", $entry["start"]) - 1);
		if(date("H", $entry["start"]) < $startHour)
		{
			$dayIndex--;
			if($dayIndex == -1) $dayIndex = 6;
			$boxTop += ((24 - $hourStart) * $hourHeight);
		}
		
		$entry["width"] = floor($boxWidth - 1);
		$entry["height"] = $boxHeight;
		$entry["top"] = $boxTop;
		
		$entry["left"] = $boxWidth * $dayIndex;
		
		if($entry["start"] >= $startDate)
		{
			$entry["display"] = true;
		}
		
		$dateOfStart = date("Y-m-d", strtotime(date("Y-m-d H:i", $entry["start"])." - $startHour hours"));
		$dateOfEnd = date("Y-m-d", strtotime(date("Y-m-d H:i", $entry["end"])." - $startHour hours"));

		if($dateOfStart != $dateOfEnd && date("H:i", $entry["end"]) != $startTime)
		{
			$secsStart = 0;
			$secsEnd = $entry["end"] - strtotime(date("Y-m-d", $entry["end"])." ".$startTime);
			
			$boxTop2 = round($secsStart / 60 / 60 / 24 * $height);
			$boxBottom2 = round($secsEnd / 60/ 60 / 24 * $height);	
			$boxHeight2 = $boxBottom2 - $boxTop2 - 1;
		
			$entry["display2"] = true;
			$entry["top2"] = $boxTop2;
			$entry["left2"] = $boxWidth * (date("N", $entry["end"]) - 1);
			$entry["height2"] = $boxHeight2;
		}
	}
	else
	{
		//echo "Not boxing: ".date("Y-m-d H:i", $entry["start"])." - ".date("Y-m-d H:i", $entry["end"]).": {$entry["title"]}<br />";
	}
}

for($i = 0; $i < 24; $i++)
{
	for($j = 0; $j < 7; $j++)
	{
		$secsStart = $i * 60 * 60;
		$secsEnd = ($i + 1) * 60 * 60;
		
		$boxTop = round($secsStart / 60 / 60 / 24 * $height);
		$boxBottom = round($secsEnd / 60/ 60 / 24 * $height);	
		$boxHeight = $boxBottom - $boxTop - 1;	
		$grid_box = array();
		$grid_box["top"] = $boxTop;
		$grid_box["height"] = $boxHeight;
		$grid_box["left"] = $j * $boxWidth;
		$grid_box["width"] = $boxWidth - 1;
		$grid_box["text"] = date("H:i", strtotime("$startTime + $i hours"));
		$grid_box["today"] = date("Y-m-d", strtotime((date("Y-m-d", $startDate)." + $j days"))) == date("Y-m-d", strtotime("$now - $startHour hours"));
		$grid_box["alt"] = ($j % 2) == 1;
		
		$grid_boxes[] = $grid_box;
	}
}

?>
<script type="text/javascript">

var shows = <?=json_encode($rawEntries)?>;
var offset = <?=$startHour?>;
var sImagePrefix = '<?=$sImagesPrefix?>';
var week = <?=$week?>;
var nTimezone = <?=date('Z')?>

</script>
<script type="text/javascript" src="/wp-content/themes/cam105/js/schedule.js"></script>
<link rel="stylesheet" type="text/css" href="/wp-content/themes/cam105/css/schedule.css"/>
<div id="schedule_container">
<a href="?week=<?=($week + 1)?>" id="next_week">Next Week &gt;</a>
<a href="?week=<?=($week - 1)?>" id="last_week">&lt; Last Week</a>
<div style="width: <?=($width + 1 + 26)?>px; height: <?=$titleHeight + 5?>px; position: relative; overflow: hidden">
<?
for($i = 0; $i < 7; $i++)
{
$nDay = strtotime(date("Y-m-d", $startDate)." + $i days");
?>
<div class="day_box <?=(date("Y-m-d", $nDay) == date("Y-m-d", strtotime("$now - $startHour hours"))) ? "today" : ""?>" style="width: <?=($width / 7)?>px; height: <?=$titleHeight?>px; top: 0px; left: <?=($i * ($width / 7))?>px">
<div class="day_box_inner">
<?
echo date("D j/m", $nDay);
?>
</div>
</div>
<?
}
?>
</div>
<div id="schedule" style="width: <?=($width + 1 + 26)?>px; height: <?=($displayHeight + 1)?>px; position: relative; overflow: auto">
<div id="schedule_inner" style="height: <?=$height + 1?>px; overflow: hidden">
<?
foreach($grid_boxes as $grid_box)
{
?>
<div class="grid_box <?=$grid_box["today"] ? "grid_today" : ""?> <?=$grid_box["alt"] ? "grid_box_alt" : ""?>" style="width: <?=$grid_box["width"]?>px; height: <?=$grid_box["height"]?>px; position: absolute; top: <?=$grid_box["top"]?>px; left: <?=$grid_box["left"]?>px">
<?=$grid_box["text"]?>
</div>
<?
}

foreach($entries as $i=>&$entry)
{
	if($entry["display"] && !$entry["nonstop"])
	{
		draw_box($entry, $i, "top", "left", "height");
	}
	
	if($entry["display2"] && !$entry["nonstop"])
	{
		draw_box($entry, $i, "top2", "left2", "height2");
	}
}
?>

<div id="now_line"></div>

<div id="popup" style="display: none">
<h1 id="popup_title"><span id="popup_title_inner"></span><img id="close" src="<?=$sCloseLocation?>" border="0" /></h1>
<div id="popup_image"></div>
<div id="popup_description">
<div id="popup_description_inner"></div>
</div>
</div>

</div>

</div>

</div>

    </div><!-- end of posts div -->