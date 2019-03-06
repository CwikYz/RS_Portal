<? 

header('Content-type:text/xml');

include("config.php");
include("core.php");

connectdb(); ?>

<rss version="2.0">
<channel>
<title>Raj Sveta Portal</title>
<description>Portal raj sveta... Rss Statusi...</description>
<link>http://secafe.freehostia.com/</link>
<copyright>Raj Sveta Portal 2010</copyright>

<?
$q = "SELECT id, shout, shouter, shtime  FROM fun_shouts ORDER BY shtime DESC";
$doGet = mysql_query($q);

while($result = mysql_fetch_array($doGet)) {
$title = getnick_uid($result[2]);
$description = htmlspecialchars($result[1]);
$avlink = getavatar($result[2]);
            if ($avlink != "") {
                $avatar = "<img src='$avlink' alt='$shnick' height='35' width='35' />";
            } else {
                $avatar = "<img src='images/nopic.jpg' alt='$shnick' height='35' width='35' />";
            } 
?>
			<item>
					<title><? echo $title; ?></title>
					<description><? echo $description; ?></description>
					<link>http://secafe.freehostia.com/rskontakt.php?status</link>
					<image><? echo $avatar; ?></image>
			</item>
<? } ?>

</channel>
</rss>
