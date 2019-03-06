<?php

$notc = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_topics"));
$nops = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_posts"));
$nofs = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_forums"));
echo "<div class='section border_top'></div>
<div class=\"section_title\"><div class=\"marker\"><img src='img/img_43.png' /><a href='rskontakt.php?forum'>Forum</a> [$nofs[0]/$notc[0]/$nops[0]]</div></div>";
// //////////////////////////////
$chs = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_chonline"));
echo "<div class='section border_top'></div>
<div class=\"section_title\"><div class=\"marker\"><img src='img/img_49.png' /><a href='rskontakt.php?chat'>Chat</a> ($chs[0])</div></div>";
// //////////////////////////////
$status = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_shouts"));
echo "<div class='section border_top'></div>
<div class=\"section_title\"><div class=\"marker\"><img src='img/img_14.png' /><a href='rskontakt.php?status'>Statusi</a> ($status[0]) <font color='red'>.v3rc</font></div></div>";
// //////////////////////////////
$download = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_download"));
echo "<div class='section border_top'></div>
<div class=\"section_title\"><div class=\"marker\"><img src='img/img_50.png' /><a href='rskontakt.php?download'>Download</a> ($download[0])</div></div>";
// //////////////////////////////
$ispisa = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM knjiga"));
echo "<div class='section border_top'></div>
<div class=\"section_title\"><div class=\"marker\"><img src='img/img_44.png' /><a href='rskontakt.php?gb'>Knjiga gostiju (GB)</a> ($ispisa[0])</div></div>";
// //////////////////////////////
echo "<div class='section border_top'></div>
<div class=\"section_title\"><div class=\"marker\"><img src='img/img_10.png' /><a href='rskontakt.php?zahvalnica'>Zahvalnica</a></div></div>";
// //////////////////////////////
echo "
<div class='section border_top'></div>
<div class=\"section_title\"><div class=\"marker\">Prisutni clanovi:</div></div>";
$sql = "SELECT
            a.name, b.place, b.userid FROM fun_users a
            INNER JOIN fun_online b ON a.id = b.userid
            GROUP BY 1,2
    ";
$items = mysql_query($sql);
echo mysql_error();
while ($item = mysql_fetch_array($items)) {
    $avlink = getavatar($item[2]);
    if ($avlink != "") {
        $avatar = "<img src=\"$avlink\" class=\"p\" height=\"25\" width=\"25\" alt=\"avatar\"/>";
    } else {
        $avatar = "<img src=\"images/nopic.jpg\" class=\"p\" height=\"25\" width=\"25\" alt=\"avatar\"/>";
    } 
    $lnk = "$item[0]";
    echo "$lnk,";
} 
// //////////////////////////////
echo "
<div class='section border_top'></div>
<div class=\"section_title\"><div class=\"marker\">Prijatelji portala</div></div>";
$query = mysql_query("SELECT url, title FROM lib3rtymrc_links ORDER BY id");
while ($links = mysql_fetch_array($query)) {
    $link = "<a href=\"$links[0]\">$links[1]</a>";
    echo "<small>$link |</small>";
} 

?>
