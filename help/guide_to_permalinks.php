<?php
	$changeWidth 	= true;
	$x				= 700;
	$y				= 600;
	$title = "How to Use Permalinks";
	$content = "<h2>What are they?</h2>
			<p>Permalinks are often seen on Weblogs to permantly link to an ondividual post. On this website they link to an individual view of the map with certain features loaded.</p>
<p>To create a permanant link to a single map view just click the <strong>Permalink</strong> link to the top left of the web page, the page will reload at the same view but with an absolute URL for that view which you can bookmark. It will remember the centre location, and the zoom level. 
<img style=\"display:block;\" src=\"images/perm_guide/where2.png\" width=\"479\" height=\"378\" />
</p>
<p>In the <strong>both versions</strong>  of the site you can currently get it to remember if you have clicked open a live departure board marker, or a weather marker.</p>
<p>e.g the weather in telford is:</p>
<p><a onclick=\"javascript:top.opener.location.href='http://bbc.blueghost.co.uk/site4.php?type=weather&marker=0&day=0&lon=-2.46&lat=52.66&zl=13';\"><strong>Click Here</strong></a></p>
<p>e.g the train running times at telford central are:</p>
<p><a onclick=\"javascript:top.opener.location.href='http://bbc.blueghost.co.uk/site4.php?type=rail&ldb=y&marker=0&lon=-2.44111&lat=52.681052&zl=12';\"><strong>Click Here</strong></a></p>
<p>Further ideas you could use:</p>
<ul>
  <li>Want to see if there are any major delays on your local commute, just zoom to the appropriate level, choose the relevant overlay and click permalink: you could then bookmark this page and look at it in the morning:
    <ul>
      <li>e.g Local rail problems between Shrewsbury and Wolverhampton
        <ul>
          <li><a onclick=\"javascript:top.opener.location.href='http://bbc.blueghost.co.uk/site4.php?type=rail&lon=-2.2683334350585938&lat=52.63868847906381&zl=11';\"><strong>Click Here</strong></a></li>
		  </ul>
      </li>
      <li>Station markers for between Shrewsbury and Wolverhampton, click on one to see if there are any other problems
        <ul>
			<li><a onclick=\"javascript:top.opener.location.href='http://bbc.blueghost.co.uk/site4.php?type=rail&ldb=y&lon=-2.2683334350585938&lat=52.63868847906381&zl=11';\"><strong>Click Here</strong></a></li>
        </ul>
      </li>
      <li>Road problems between Shrewsbury and Wolverhampton (doesn't currently automatically show delays, need to click on the white county markers and select <strong>Show Delays</strong>)
        <ul>
          <li><a onclick=\"javascript:top.opener.location.href='http://bbc.blueghost.co.uk/site4.php?type=traffic&lon=-2.2683334350585938&lat=52.63868847906381&zl=11';\"><strong>Click Here</strong></a></li>
		  </ul>
      </li>
      <li>Do i need to take the umbrella in to work (no doubt yes:) ):
        <ul>
          <li><a onclick=\"javascript:top.opener.location.href='http://bbc.blueghost.co.uk/site4.php?type=weather&day=0&lon=-2.448577880859375&lat=52.68803760683345&zl=10';\"><strong>Click Here</strong></a></li>
		</ul>
      </li>
      </ul>
  </li>
  </ul>";
	$links	= "<ul><li><a href=\"help.php\" title=\"Go back to the index\">Help Index</a></li></uk>";
?>