<?php

$year = $_GET["year"];
if (!$year) {
$year = 2018;
}
$page = "http://chrismoyles.net/wiki/index.php?title=The_Chris_Moyles_Show/Downloads/" . $year;

$content = fopen($page,"r");
$titles[] = "date";
$titles[] = "link";
$titles[] = "description";
$count = 0;
$overall = 0;
while ($line = fgets($content,4096)) {
	$line = trim($line);
	if (substr($line,0,4) == "<td>") {
		$out = trim(strip_tags($line));
		if ($count == 1) {
			$link = substr($line,strpos($line,'href="')+6,strlen($line));
			$link = substr($link,0,strpos($link,'"'));
			$item[$overall][$titles[$count]] = $link;
		} else {
			$item[$overall][$titles[$count]] = $out;
		}
		$count++;
		if ($count == 3) {
			$count = 0;
			$overall++;
		}
	}
}
output($item);

function output($item) {
echo '<rss xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd" version="2.0">
  <channel>
    <title>The Chris Moyles Show</title>
    <link>http://www.radiox.co.uk</link>
    <description>The Chris Moyles Show on Radio X</description>
    <itunes:author>The Chris Moyles Show</itunes:author>
    <copyright>© Chris Moyles</copyright>
    <language>en-us</language>
    <pubDate>Sun, 31 Jan 2016 16:49:07 UTC</pubDate>
    <lastBuildDate>'.date('r') .'</lastBuildDate>
    <itunes:category text="Entertainment"/>
    <itunes:category text="Comedy"/>
    <itunes:explicit>No</itunes:explicit>
    <itunes:image href="https://upload.wikimedia.org/wikipedia/en/e/eb/Chris_Moyles_Radio_X_promotional_image.jpg"/>
    <itunes:subtitle>The Chris Moyles Show</itunes:subtitle>
    <itunes:summary>The Chris Moyles Show on Radio X</itunes:summary>
    <itunes:owner>
      <itunes:name>Radio X</itunes:name>
      <itunes:email>http://www.radiox.co.uk</itunes:email>
    </itunes:owner>';
$item = array_reverse($item);
for($i=0;$i<20;$i++) {
$headers = get_headers($item[$i]["link"],1);	
echo '<item>
      <itunes:author>The Chris Moyles Show</itunes:author>
      <itunes:keywords/>
      <itunes:duration>123:00</itunes:duration>
      <title>'.$item[$i]["date"].'</title>
      <guid isPermaLink="true">'.$item[$i]["link"].'</guid>
      <description>'.$item[$i]["description"].'</description>
      <category>Comedy</category>
      <pubDate>'.$headers["Last-Modified"].'</pubDate>
      <enclosure length="'.$headers["Content-Length"].'" url="'.$item[$i]["link"].'" type="audio/mpeg"/>
    </item>';
}
echo '</channel>
</rss>';
}

?>
