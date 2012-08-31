<script type="text/javascript" src="/wp-content/themes/cam105/js/jquery-ui-1.8.9.custom.min.js"></script>
<script type="text/javascript" src="/wp-content/themes/cam105/js/whatson.js"></script>
<!--<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>-->

<div class="posts">
                  <?php if ( function_exists('yoast_breadcrumb') && $postcount ==0) {
                     yoast_breadcrumb('<div id="breadcrumbs">','</div>');
                  } ?>
				  
				  <style type="text/css">

.ui-datepicker
{
	font-size: 0.8em;
	z-index: 100 !important;
}
				  
#whatsons
{
	font-family: Arial, Tahoma, sans-serif;
	font-size: 10pt;
	width: 740px;
	margin-bottom: 10px;
}

#whatsons .ajaxloader
{
	float: right;
	width: 16px;
	height: 16px;
}

#whatsons .done
{
	display: none;
}

#whatsons #viewSelector,
#whatsons #map_canvas,
#whatsons #list_canvas,
#whatsons #top
{
	width: 520px;
}

#whatsons #map_canvas,
#whatsons #list_canvas
{
	height: 535px;
	float: left;
	border: 1px solid black;
}

#whatsons #list_canvas
{
	overflow: auto;
}

#whatsons #categories
{
	float: left;
	margin-left: 5px;
	width: 200px;
	background-color: #fafafa;
	padding-right: 10px;
}

#whatsons #allCategories
{
	font-weight: bold;
	margin-bottom: 12px;
}

#whatsons .categoryItem
{
	margin-bottom: 4px;
}

#whatsons .clear
{
	clear: both;
}

#whatsons .infoWindow
{
	margin-right: 10px;
	height: 300px;
}

#whatsons .infoWindow p.address
{
	margin-top: 0px;
	margin-bottom: 5px;
}

#whatsons .infoWindow p
{
	margin: 0px;
}

#whatsons .infoWindow p.heading,
#whatsons .listBox p.heading
{
	font-weight: bold;
	margin-bottom: 5px;
}

#whatsons .infoWindow div.description,
#whatsons .infoWindow div.moreInfo
{
	margin-top: 5px;
	font-size: 0.9em;
}

#whatsons ul,
#whatsons li
{
	margin: 0px;
	padding: 0px;
	list-style-type: none;
}

#whatsons #dates
{
	float: left;
}

#whatsons #dates input 
{
	width: 100px;
	margin-left: 0px;
	text-align: center;
}

#whatsons #dates span,
#whatsons #nearMe span
{
	font-weight: bold;
	font-size: 0.9em;
}

#whatsons #nearMe
{
	float: right;
}

#whatsons #nearMe input
{
	margin-left: 0px;
	width: 100px;
}

#whatsons #nearMe img
{
	vertical-align: middle;
	margin-bottom: 5px;
}

#whatsons #top
{
	margin-bottom: 5px;
}

#whatsons #viewSelector
{
	margin-top: 15px;
	margin-bottom: 10px;
	text-align: center;
}

#whatsons .button
{
	padding: 2px 5px;
	color: white;
	font-weight: bold;
	font-size: 0.8em;
	width: 100px;
	height: 25px;	
	cursor: pointer;
}

#whatsons .button_off
{

	background: #aad581;
	background: -moz-linear-gradient(top,  #aad581 0%, #a7cb83 50%, #a5c884 51%, #a2b98b 100%);
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#aad581), color-stop(50%,#a7cb83), color-stop(51%,#a5c884), color-stop(100%,#a2b98b));
	background: -webkit-linear-gradient(top,  #aad581 0%,#a7cb83 50%,#a5c884 51%,#a2b98b 100%);
	background: -o-linear-gradient(top,  #aad581 0%,#a7cb83 50%,#a5c884 51%,#a2b98b 100%);
	background: -ms-linear-gradient(top,  #aad581 0%,#a7cb83 50%,#a5c884 51%,#a2b98b 100%);
	background: linear-gradient(to bottom,  #aad581 0%,#a7cb83 50%,#a5c884 51%,#a2b98b 100%);
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#aad581', endColorstr='#a2b98b',GradientType=0 );

}

#whatsons .button_on
{

	background: #56822c;
	background: -moz-linear-gradient(top,  #56822c 0%, #4f6f2f 50%, #4c6a30 51%, #445533 100%);
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#56822c), color-stop(50%,#4f6f2f), color-stop(51%,#4c6a30), color-stop(100%,#445533));
	background: -webkit-linear-gradient(top,  #56822c 0%,#4f6f2f 50%,#4c6a30 51%,#445533 100%);
	background: -o-linear-gradient(top,  #56822c 0%,#4f6f2f 50%,#4c6a30 51%,#445533 100%);
	background: -ms-linear-gradient(top,  #56822c 0%,#4f6f2f 50%,#4c6a30 51%,#445533 100%);
	background: linear-gradient(to bottom,  #56822c 0%,#4f6f2f 50%,#4c6a30 51%,#445533 100%);
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#56822c', endColorstr='#445533',GradientType=0 );

}

#whatsons #listViewButton
{
	-webkit-border-top-left-radius: 4px;
	-webkit-border-bottom-left-radius: 4px;
	-moz-border-radius-topleft: 4px;
	-moz-border-radius-bottomleft: 4px;
	border-top-left-radius: 4px;
	border-bottom-left-radius: 4px;
	border: 1px solid black;
}
	
#whatsons #mapViewButton
{
	-webkit-border-top-right-radius: 4px;
	-webkit-border-bottom-right-radius: 4px;
	-moz-border-radius-topright: 4px;
	-moz-border-radius-bottomright: 4px;
	border-top-right-radius: 4px;
	border-bottom-right-radius: 4px;	
	border-top: 1px solid black;
	border-right: 1px solid black;
	border-bottom: 1px solid black;
}

#whatsons .listBox
{
	margin: 10px 10px 10px 10px;
	padding: 10px 0px;
	
	border-bottom: 1px solid silver;
	
}

#whatsons #postcodeGoButton,
#whatsons #postcodeClearButton
{
	cursor: pointer;
}

#whatsons #list_canvas h2
{
	background: #56822C;
	color: white;
	display: block;
	padding: 5px;
	margin: 5px 5px 0px 5px;
	font-size: 1.3em;
}

#whatsons #error
{
	font-weight: bold;
	color: red;
	margin-top: 10px;
}

#whatsons .logos
{
	margin-top: 15px;
	text-align: right;
}

</style>

</head>

<body>

<div id="whatsons">

<?php //echo '<p style="color: red; font-weight: bold">The What\'s On page is currently undergoing maintenance.</p>'; ?>

<img src="/wp-content/themes/cam105/images/loading.gif" class="ajaxloader done" />

<div id="top">
<div id="dates">
	<span>Date Range</span><br />
	<input class="datepicker" type="text" id="startDate" value="" /> to <input class="datepicker" type="text" id="endDate" value="" />
</div>

<div id="nearMe">
	<span>Near Me</span><br />
	<input type="text" id="postcode" value="" placeholder="Postcode..." />
	<select id="radius">
		<option value="0.5">within &frac12; mile</option>
		<option value="1">within 1 mile</option>
		<option value="2" selected="selected">within 2 miles</option>
		<option value="3">within 3 miles</option>
		<option value="5">within 5 miles</option>
		<option value="10">within 10 miles</option>
		<option value="15">within 15 miles</option>
	</select>
	<img src="/wp-content/themes/cam105/images/go.png" id="postcodeGoButton" />
	<img src="/wp-content/themes/cam105/images/round_delete.png" id="postcodeClearButton" />
</div>

<div class="clear"></div>
</div>

<div id="error">
</div>

<div id="viewSelector">
	<span id="listViewButton" class="button button_off">List View</span><span id="mapViewButton" class="button button_off">Map View</span>
</div>

<noscript>
Please enable Javascript to enhance your What's On experience!
</noscript>

<div id="list_canvas">
	<?
	
	$data = file_get_contents("http://www.cambridge105.fm/proxy/whatsondata.php");
	$json = json_decode($data, true);
	
	foreach($json["Events"] as $event)
	{
		echo '<div class="listBox">' .
			'<p class="heading">' .
				$event["Name"] .
			'</p>' . 
			'<div class="description">' .
				$event["EventDescription"] . 
			'</div>' .
			'<div class="moreInfo"><a target="_blank" href="' .
				$event["Url"] .
			'">More info...</a></div>' .
		'</div>'."\r\n\r\n";
	}
	
	?>
</div>
<div id="map_canvas"></div>
<div id="categories"></div>

<div class="clear"></div>

<div>
If you have an event you would like us to publish, <a href="http://cambridge105.fm/whatson-form/">send us your What's On</a>.
</div>

<div class="logos">

<a target="_blank" href="http://www.localsecrets.com"><img src="/wp-content/themes/cam105/images/localsecrets50.png" border="0" /></a>

</div>

</div>
				  
</div>