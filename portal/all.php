<?php

echo "<div class=\"section border_top\"><div class=\"section_title\"><div class=\"marker\"><a href='notify.php?main'>Obavestenja</a></div></div></div>";
echo "<div class=\"section border_top\">";
$onbuds = getonbuds($uid);
echo "<div class=\"section_title\"><div class=\"marker\"><a href=\"index.php?caskanje\">Caskanje</a> ($onbuds)</div></div></div>";
echo "<div class=\"section border_top\">";
echo "<div class=\"section_title\"><div class=\"marker\"><a href=\"index.php?igraonica\">IGRAONICA</a> (PC)</div></div></div>";
echo "<div class=\"section border_top\">";
echo "<div class=\"section_title\"><div class=\"marker\">Poslednji komentar u forumu</div></div></div>";
    $lpt = mysql_fetch_array(mysql_query("SELECT id, name FROM fun_topics ORDER BY lastpost DESC LIMIT 0,1"));
    $nops = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_posts"));
    if ($nops[0] == 0) {
        $pinfo = mysql_fetch_array(mysql_query("SELECT authorid FROM fun_topics"));
        $tluid = $pinfo[0];
    } else {
        $pinfo = mysql_fetch_array(mysql_query("SELECT uid FROM fun_posts ORDER BY dtpost DESC LIMIT 0,1"));

        $tluid = $pinfo[0];
    } 
$tlnm = htmlspecialchars($lpt[1]);
$tlnick = getnick_uid($tluid);
$tpclnk = "<a href=\"index.php?viewtpc&amp;tid=$lpt[0]&amp;go=last\">$tlnm</a>";
$vulnk = "<a href=\"index.php?viewuser&amp;who=$tluid\">$tlnick</a>";
echo "<div class=\"sett_line\"><small>$tpclnk. Od $vulnk.</small></div>";

$notc = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_topics"));
$nops = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_posts"));
$nofs = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_forums"));
echo "<div class=\"section border_top\"><div class=\"section_title\"><div class=\"marker\"><a href=\"index.php?Forumi\">Forum</a> [$nofs[0]/$notc[0]/$nops[0]]</div></div></div>";
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_favtopic WHERE uid='" . $uid . "'"));
echo "<div class=\"section border_top\"><div class=\"section_title\"><div class=\"marker\"><a href=\"favtopic.php?favtpc\">Oznacene teme</a> ($noi[0])</div></div></div>";
echo "<div class=\"section border_top\"><div class=\"section_title\"><div class=\"marker\"><a href='grupa.php?main'>Grupe</a></div></div></div>";
$dld = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_download"));
echo "<div class=\"section border_top\"><div class=\"section_title\"><div class=\"marker\"><a href='download.php?main'>Download</a> ($dld[0])</div></div></div>";
$chs = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_chonline"));
echo "<div class=\"section border_top\"><div class=\"section_title\"><div class=\"marker\"><a href=\"index.php?online\">Prisutni: </a> <b>[<a href=\"index.php?online\">" . getnumonline() . "</a> /<a href='portal.php?online'>$chs[0]</a>]</b></div></div></div>";
$sit = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM lib3rtymrc_links"));
echo "<div class=\"section border_top\"><div class=\"section_title\"><div class=\"marker\"><a href='portal.php?links'>Prijatelji portala</a> ($sit[0])</div></div></div>";

if (ismod(getuid_sid($sid))) {
    echo "<div class=\"section border_top\"><div class=\"section_title\"><div class=\"marker\"><a href=\"modcp.php?main\">RS MOD TOOLS</a></div></div></div>";
} 

if (isadmin(getuid_sid($sid))) {
    echo "<div class=\"section border_top\"><div class=\"section_title\"><div class=\"marker\"><b><a href=\"index.php?admincp\">RS ADMIN TOOLS</a></b></div></div></div>";
} 

?>
