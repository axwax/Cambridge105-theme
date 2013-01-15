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
		if(sDesc && show.url)
		{
			sDesc += ' <a href="' + show.url + '">[more info]</a>';
		}
		
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
			$j("#popup_image").css("background-image", "url('" + sImagePrefix + show.image + "')")
				.show();
			
			nAdditionalWidth += 105;
				
			$j("#popup_description_inner").css("margin-left", 105);
		}
		else
		{
			$j("#popup_description_inner").css("margin-left", 0);
			$j("#popup_image").hide();
		}
		
		$j('#popup_title_inner, #popup_image')
			.css('cursor', 'pointer')
			.click(function()
			{
				window.location = show.url;
			});		
		
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
	nNowSecs += nTimezone;
	
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