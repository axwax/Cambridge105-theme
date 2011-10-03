<?php

// error_reporting(E_ERROR);
// ini_set('display_errors', '1');

/*
File Description: The Loop
Built By: GIGX
Theme Version: 0.5.11
*/
?>

  	<?php wp_nav_menu( array( 'theme_location' => 'above-posts', 'sort_column' => 'menu_order', 'fallback_cb' => 'header_menu', 'container_class' => 'header-menu' ) ); ?>
    <?php if ( is_active_sidebar( 'above_posts_widgets' ) ) : // Widgets Above Posts ?>
    	<div id="above-posts-widgets">
    		<?php dynamic_sidebar('above_posts_widgets'); ?>
    	</div>  
    <?php endif; ?>
      
    <div class="posts">
<?

require_once $_SERVER['DOCUMENT_ROOT']."/schedule_info/Schedule.class.php"; 

$week = $_GET["week"];

if(!$week) $week = 0;

$now = "now";
//$now = "2011-08-01 06:00";
$displayHeight = 60 * 13;
$offset = "$week week";
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
	$sUrlSchedule = Schedule::GetGoogleScheduleURL(strtotime("last Monday $offset"), strtotime("this Sunday + 7 days $offset"));
}
$sUrlProgrammes = Schedule::GetProgrammesURL();
$sCloseLocation = "/images/x.gif";

/////////////////////////////////////////////////

function draw_box($entry, $index, $sTop, $sLeft, $sHeight)
{
?>
	<!--<a href="/shows/<?=$entry["pid"]?>">-->
	<a href="#">
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

var $j = jQuery;

$j(function()
{
	$j("#popup").click(function(event)
	{
		event.stopPropagation();
	}).disableSelection();
	
	$j("#close").click(function()
	{
		$j(".selected").removeClass("selected");
		$j("#popup").hide();
	});

	$j(".show_box").each(function(i, o)
	{
		$j(o).unwrap();
	});
		
	$j(".show_box").click(function(event)
	{
		event.stopPropagation();
	
		$j(".selected").removeClass("selected");
		$j(this).addClass("selected");
	
		var regex = /show_([0-9]+)/gi;
		var oMatch = regex.exec($j(this).attr("class"));
		var show = shows[oMatch[1]];
		
		$j("#popup_title_inner").text(show.start_text + " - " + show.end_text + ": " + show.title);
		
		var sDesc = show.desc;
		if(sDesc && show.email_user)
		{
			sDesc += '<br /><br />';
		}
		if(show.email_user)
		{
			var sEmail = show.email_user + '@' + show.email_domain;
			sDesc += 'Email: <a href="mailto:' + sEmail + '">' + sEmail + '</a>';
		}
		
		$j("#popup_description_inner").html(sDesc);
		var nAdditionalWidth = 0;
		if(show.image != null)
		{
			$j("#popup_image").css("background-image", "url('" + sImagePrefix + show.image + "')");
			$j("#popup_image").show();
			nAdditionalWidth += 105;
			$j("#popup_description_inner").css("margin-left", 105);
		}
		else
		{
			$j("#popup_description_inner").css("margin-left", 0);
			$j("#popup_image").hide();
		}
		var nWidth = 275 + nAdditionalWidth;
		var nTop =  parseInt($j(this).css("top")) + 0;
		var nCentre = parseInt($j(this).css("left")) + (parseInt($j(this).css("width")) / 2);
		
		$j("#popup").css({ top: nTop, left: nCentre, width: 0 }).show();
		
		var nTitleWidth = $j("#popup_title_inner").width() + 10;
		if(nWidth < nTitleWidth)
		{
			nWidth = nTitleWidth;
		}
		nWidth += 25;
		var nLeft = nCentre - (nWidth / 2) + 0;
		
		var nEdge = $j("#schedule").width() - 27;	
		
		if(nLeft + nWidth > nEdge)
		{
			nLeft = (nEdge - nWidth);
		}
		
		if(nLeft < 0)
		{
			nLeft = 0;
		}
		
		$j("#popup_description_inner").width(nWidth - nAdditionalWidth - 10);
		$j("#popup").animate({ top: nTop, left: nLeft, width: nWidth });
	}).disableSelection();
	
	$j(document).click(function(event)
	{
		$j("#close").click();
	});
	
	$j(".grid_box").disableSelection();
	
	$j("#popup").hide();
	
	if(week == 0)
	{
		$j('#now_line').css('opacity', 0.4);
		update_now_line();
		$j("#schedule")[0].scrollTop = parseInt($j('#now_line').css('top')) - 150;
	}
	else
	{
		$j("#now_line").hide();
	}
	
	var asShows = [];
	for(i = 0; i < shows.length; i++)
	{
		var oShow = shows[i];
		if(oShow.image != null && asShows[oShow.image] == null)
		{
			asShows[oShow.image] = 1;
			$j("#schedule").append('<img src="' + sImagePrefix + oShow.image + '" style="display: none" />');
		}
	}
});

function update_now_line()
{
	var dtNow = new Date();
	
	var nNowSecs = dtNow.getHours() * 60 * 60 + dtNow.getMinutes() * 60;
	nNowSecs -= offset * 60 * 60;
	nNowSecs += dtNow.getTimezoneOffset() * 60;
	nNowSecs += <?=date('Z')?>;
	
	if(nNowSecs < 0)
	{
		nNowSecs += (24 * 60 * 60);
	}
	
	var htmlLastBox = $j(".grid_box").last();
	var dPxPerSec = (parseInt(htmlLastBox.css('top')) + htmlLastBox.height()) / 60 / 60 / 24;
	
	$j('#now_line').css({ top: parseInt(dPxPerSec * nNowSecs), width: $j("#schedule").width() - 20 });
	
	$j(".show_box").removeClass("show_box_current");
	for(var i = 0; i < shows.length; i++)
	{
		var oShow = shows[i];
		var dtStart = new Date(oShow.start_utc);
		var dtEnd = new Date(oShow.end_utc);
		
		if(dtNow >= dtStart && dtNow < dtEnd)
		{
			$j(".show_" + i).addClass("show_box_current");
		}
	}
	
	setTimeout(update_now_line, 60000);
}

$j.fn.disableSelection = function() {
    $j(this).attr('unselectable', 'on')
           .css('-moz-user-select', 'none')
           .each(function() { 
               this.onselectstart = function() { return false; };
            });
};

</script>
<style type="text/css">
#schedule_container
{
	padding: 0px;
	margin: 0px;
	font-family: sans-serif;
	font-size: 10px;
	line-height: 13px;	
}

div.time_inner
{
	padding: 2px;
	background-color: #486D25;
	color: white;
}

div.title_inner
{
	font-weight: bold;
	padding: 0px 2px 2px;
}

div.show_box
{
	overflow: hidden; 
	background-color: white; 
	border: 1px solid gray; 
	position: absolute; 
	border-radius: 5px 5px 5px 5px;
	-moz-border-radius: 5px;
	cursor: pointer;
}

div.show_box_current
{
	background-color: #E2F59F;
}

div.selected
{
	background-color: #f9f9f9;
}

div.day_box
{
	overflow: hidden; 
	background-color: white; 	
	position: absolute; 
	border: 1px solid gray; 
	text-align: center;
}

div.day_box_inner
{
	padding: 5px;
}

div.grid_box
{
	border: 1px solid silver;
	background-color: #eee;
	color: #ddd;
}

div.grid_box_alt
{
	background-color: #E8E8E8;
}

div#popup
{
	position: absolute;
	background-color: white;
	border: 1px solid black; 
	-moz-border-radius: 15px 15px 15px 15px;	
	border-radius: 15px 15px 15px 15px;	
	overflow: hidden;
}

h1#popup_title
{
	margin-top: 0px;
	margin-bottom: 0px;
	background-color: #486D25;
	font-size: 14px !important;
	color: white;
	padding: 5px;
	-moz-border-radius: 15px 15px 0px 0px;
	border-radius: 15px 15px 0px 0px;	
}

#popup_title_inner
{
	white-space: nowrap;
	font-size: 14px !important;
}

#popup_description
{
	padding: 5px;
}

#popup_description_inner
{
	font-size: 10px;
}

#popup_image
{
	margin: 5px;
	float: left;
	-moz-border-radius: 5px 5px 5px 5px;
	border-radius: 5px 5px 5px 5px;
	width: 100px;
	height: 100px;
	background-repeat: no-repeat;
}

.nowrap
{
	white-space: nowrap;
}

#now_line
{
	background-color: red;
	height: 3px;
	position: absolute;
}

#schedule_inner
{
	position: relative;
}

.today
{
	background-color: #FFE491 !important;
}

.grid_today
{
	background-color: #FEFFDE !important;
}

#close
{
	position: absolute;
	right: 10px;
	top: 7px;
	cursor: pointer;
}
#last_week, #next_week
{
	font-size: 12px;
	font-weight: bold;
	display: inline-block;
	margin-bottom: 5px;
}

#next_week
{
	float: right;
}

</style>
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
	//if($entry["display"])
	{
		draw_box($entry, $i, "top", "left", "height");
	}
	
	if($entry["display2"] && !$entry["nonstop"])
	//if($entry["display2"])
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