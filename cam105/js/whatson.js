(function($)
{
	var oMap = null;
	var oRadiusCircle = null;
	var oOpenInfoWindow = null;
	var aoMarkers = [];
	var bFirst = true;
	var jsonData = null;

	$(function()
	{		
		$('#viewSelector #mapViewButton').click();
		
		if(jsonData == null)
		{
			getData();
		}
		
		$('.datepicker').datepicker(
		{
			dateFormat: 'd M yy'
		}).change(function()
		{
			if($(this).is('#startDate'))
			{
				$('#endDate').datepicker('option', 'minDate', $(this).datepicker('getDate'));
			}
			else
			{
				$('#startDate').datepicker('option', 'maxDate', $(this).datepicker('getDate'));
			}
			getData();
		});
		
		$('head').append('<link rel="stylesheet" type="text/css" href="/wp-content/themes/cam105/css/smoothness/jquery-ui-1.8.16.custom.css" />');
		
		// work out if placeholder works
		if(supports_input_placeholder() == false)
		{
			$('#nearMe span').append(' (postcode)');
		}
	});
	
	$('#viewSelector .button').live('click', function()
	{
		if($(this).hasClass('button_off'))
		{
			$('#viewSelector .button')
				.removeClass('button_on')
				.addClass('button_off');
				
			$(this).removeClass('button_off');
			$(this).addClass('button_on');
		}
	});
	
	$('#viewSelector #listViewButton').live('click', function()
	{
		$('#map_canvas').hide();
		$('#list_canvas').show();
	});
	
	$('#viewSelector #mapViewButton').live('click', function()
	{
		$('#map_canvas').show();
		$('#list_canvas').hide();
		
		if(oMap == null)
		{
			init();
			if(jsonData != null)
			{
				populateEvents(jsonData);
			}
			else
			{
				getData();
			}
		}
	});
	
	$('#allCategories input').live('click', function()
	{
		if($(this).is(':checked'))
		{
			$('#categories li:not(\'#allCategories\') input').attr('checked', 'checked');
		}
		else
		{
			$('#categories li:not(\'#allCategories\') input').removeAttr('checked');
		}
		
		getData();
	});
	
	$('.categoryItem input').live('click', function()
	{
		if($(this).is(':checked') && $('.categoryItem input').length == $('.categoryItem input:checked').length)
		{
			$('#allCategories input').attr('checked', 'checked');
		}
		else
		{
			$('#allCategories input').removeAttr('checked');
		}
		
		getData();
	});
	
	$('#postcode').live('keyup', function(e)
	{
		if(e.which == 13)
		{
			$('#postcodeGoButton').click();
		}
	});
	
	$('#postcode').live('change', function()
	{
		$('#postcodeGoButton').click();
	});
	
	$('#radius').live('change', function(e)
	{
		$('#postcodeGoButton').click();
	});
	
	$('#postcodeGoButton').live('click', function(e)
	{
		getData();
	});
	
	function init()
	{
		var oLatlng = new google.maps.LatLng(52.205, 0.119); // cambridge
		
		var oOptions = 
		{
		  zoom: 12,
		  center: oLatlng,
		  mapTypeId: google.maps.MapTypeId.ROADMAP,
		  minZoom: 9,
		  maxZoom: 18,
		}
		
		oMap = new google.maps.Map(document.getElementById("map_canvas"), oOptions);
		google.maps.event.addListener(oMap, 'click', function() 
		{
			if(oOpenInfoWindow != null)
			{
				oOpenInfoWindow.close();
			}
		});	
	}

	function getData()
	{
		$('.ajaxloader').removeClass('done');
	
		var oParams = {};

		if(bFirst == false)
		{		
			var asCategories = [];
			var jChecked = $('.categoryItem input:checked');
			if(jChecked.length == 0)
			{
				oParams.Categories = 'None';
			}
			else
			{
				jChecked.each(function()
				{
					asCategories.push($(this).val());
				});
				oParams.Categories = asCategories.join('|');
			}
		}
		bFirst = false;
		
		$('#whatsons input, #whatsons select').attr("disabled", "disabled");
		
		var dtStart = $('#startDate').datepicker('getDate');
		var dtEnd = $('#endDate').datepicker('getDate');
		
		if(dtStart != null)
		{
			oParams.StartDate = $.datepicker.formatDate('yy-mm-dd', dtStart);
		}
		
		if(dtEnd != null)
		{
			oParams.EndDate = $.datepicker.formatDate('yy-mm-dd', dtEnd);
		}
		
		var sPostcode = $('#postcode').val();
		if(sPostcode != '')
		{
			oParams.Postcode = sPostcode;
			oParams.Radius = $('#radius').val();
		}
	
		$.post('/proxy/whatsondata.php', oParams, function(json)
		{
			jsonData = json;
			$('.ajaxloader').addClass('done');
		
			populateDateRange(json.StartDateUnix, json.EndDateUnix);
			populateCategories(json.Categories, json.SelectedCategories);
			populateEvents(json);
			
			if(json.PostcodeError != null)
			{
				$('#error').text(json.PostcodeError).show();
			}
			else
			{
				$('#error').hide();
			}			
			
			$('#whatsons input, #whatsons select').removeAttr("disabled");
		}, 'json');
	}

	function populateDateRange(nStartDateUnix, nEndDateUnix)
	{
		var dtStart = new Date(nStartDateUnix * 1000);
		var dtEnd = new Date(nEndDateUnix * 1000);
		
		$('#startDate').datepicker('setDate', dtStart);
		$('#endDate').datepicker('setDate', dtEnd);
	}
	
	function populateEvents(json)
	{
		var aoEvents = json.Events;
		var nPostcodeLat = json.PostcodeLat;
		var nPostcodeLng = json.PostcodeLong;
		var nRadius = json.Radius;
	
		for(var nCursor = 0; nCursor < aoMarkers.length; nCursor++)
		{
			aoMarkers[nCursor].setMap(null);
		}
		
		if (oRadiusCircle != null) 
		{
			oRadiusCircle.setMap(null);
		}		
		
		aoMarkers = [];
	
		$('#list_canvas').empty();
		
		var sCurrentDate = '';
	
		for(var nCursor = 0; nCursor < aoEvents.length; nCursor++)
		{
			(function()
			{	
				var oEvent = aoEvents[nCursor];
				var oMarker = new google.maps.Marker(
				{
					position: new google.maps.LatLng(oEvent.Latitude,oEvent.Longitude),
					map: oMap,
					title: oEvent.Name
				});
				
				oMarker.setIcon(new google.maps.MarkerImage('/wp-content/themes/cam105/images/marker4.png'));
				
				aoMarkers.push(oMarker);
							
				var sDate = '<p><b>' + oEvent.DateFormatted + '</b></p>';				
				var sAddress = '<p class="address">' + ((oEvent.VenueName != null) ? oEvent.VenueName + '<br />' : '') + oEvent.VenueAddress + '</p>';
				var sTime = '';
				var sPrice = '';
				var sPhone = '';
				
				if(oEvent.Time)
				{
					sTime = '<p><b>When:</b> ' + oEvent.Time + '</p>';
				}
				
				if(oEvent.Price)
				{
					sPrice = '<p><b>Price:</b> ' + oEvent.Price + '</p>';
				}
				
				if(oEvent.Phone)
				{
					sPhone = '<p><b>Phone:</b> ' + oEvent.Phone + '</p>';
				}
				
				var sContent = '<div class="infoWindow"><p class="heading">' + 
									oEvent.Name + 
								'</p>' + 
								sDate + sAddress + sTime + sPrice + sPhone +
								'<div class="description">' +
									oEvent.EventDescription + 
								'</div>' +
								'<div class="moreInfo"><a target="_blank" href="' +
									oEvent.Url +
								'">More info...</a></div></div>';
								
				var sListContent = '<div class="listBox"><p class="heading">' + 
									oEvent.Name + 
								'</p>' + 
								sAddress + sTime + sPrice + sPhone +
								'<div class="description">' +
									oEvent.EventDescription + 
								'</div>' +
								'<div class="moreInfo"><a target="_blank" href="' +
									oEvent.Url +
								'">More info...</a></div></div>';								
				
				var oInfoWindow = new google.maps.InfoWindow({
					content: sContent,
					maxWidth: 400
				});			
					
				google.maps.event.addListener(oMarker, 'click', function() 
				{
					if(oOpenInfoWindow != null)
					{
						oOpenInfoWindow.close();
						oOpenInfoWindow = null;
					}
					oInfoWindow.open(oMap,oMarker);
					oOpenInfoWindow = oInfoWindow;
				});
				
				if(sCurrentDate != oEvent.DateFormatted)
				{
					sCurrentDate = oEvent.DateFormatted;
					$('#list_canvas').append('<h2>' + sCurrentDate + '</h2>');
				}
				
				$('#list_canvas').append(sListContent);
			})();
		}
		
		if(nPostcodeLat && nPostcodeLng)
		{
			var oPos = new google.maps.LatLng(nPostcodeLat, nPostcodeLng);
			var oYouMarker = new google.maps.Marker(
			{
				position: oPos,
				map: oMap,
				title: 'You'
			});
			
			oYouMarker.setIcon('/wp-content/themes/cam105/images/house.png');
			
			aoMarkers.push(oYouMarker);
			
			var nRadiusMetres = nRadius * 1600;
			oRadiusCircle = new google.maps.Circle({
				center: oPos,
				radius: nRadiusMetres,
				strokeColor: "#83AE2C",
				strokeOpacity: 0.8,
				strokeWeight: 2,
				fillColor: "#83AE2C",
				fillOpacity: 0.2,
				map: oMap
			});			
		}		
	}

	function populateCategories(asCategories, asSelected)
	{
		var bAllChecked = asSelected.length == 0 || asCategories.length == asSelected.length;
		var sAllChecked = bAllChecked ? 'checked="checked"' : '';
	
		var divCat = $("#categories");
		divCat.empty();
		var ulCat = $("<ul>");
		divCat.append(ulCat);
		ulCat.append('<li id="allCategories"><input type="checkbox" id="catAll" ' + sAllChecked + '/><label for="catAll"> ALL</label></li>');
		for(var nCursor = 0; nCursor < asCategories.length; nCursor++)
		{
			var sCat = asCategories[nCursor];
			var sChecked = '';
			if(bAllChecked || $.inArray(sCat, asSelected) >= 0)
			{
				sChecked = 'checked="checked"';
			}
			ulCat.append('<li class="categoryItem"><input type="checkbox" id="cat' + nCursor + '" ' + sChecked + ' value="' +  escapeHtml(sCat) + '" /><label for="cat' + nCursor + '"> ' + sCat + '</label></li>');
		}
	}	

})(jQuery);

function escapeHtml(unsafe) {
  return unsafe
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#039;");
}

function supports_input_placeholder() {
  var i = document.createElement('input');
  return 'placeholder' in i;
}