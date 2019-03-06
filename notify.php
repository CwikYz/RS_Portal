<?php

include("core.php");
include("config.php");

header("Content-type: text/html; charset=ISO-8859-1");
echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>";
echo "<!DOCTYPE html PUBLIC \"-//WAPFORUM//DTD XHTML Mobile 1.0//EN\"\"http://www.wapforum.org/DTD/xhtml-mobile10.dtd\">";
echo "<html xmlns=\"http://www.w3.org/1999/xhtml\">";

connectdb();
$action = $_GET["action"];
$sid = $_SESSION["sid"];
$page = $_GET["page"];
$who = $_GET["who"];
$pmid = $_GET["pmid"];
vrh($sid);
if (islogged($sid) == false) {
    echo "<p align=\"center\">";
    echo "You are not logged in<br/>";
    echo "Or Your session has been expired<br/><br/>";
    echo "<a href=\"index.php\">Login</a>";
    echo "</p>";
    exit();
} 
$uid = getuid_sid($sid);
if (isbanned($uid)) {
    echo "<p align=\"center\">";
    echo "<img src=\"images/notok.gif\" alt=\"x\"/><br/>";
    echo "You are <b>Banned</b><br/>";
    $banto = mysql_fetch_array(mysql_query("SELECT timeto FROM fun_penalties WHERE uid='" . $uid . "' AND penalty='1'"));
    $remain = $banto[0] - time();
    $rmsg = gettimemsg($remain);
    echo "Time to finish your penalty: $rmsg<br/><br/>"; 
    // echo "<a href=\"index.php\">Login</a>";
    echo "</p>";
    exit();
} 

if (isset($_GET['main'])) {
    addonline(getuid_sid($sid), "Notifikacije", "");

    echo vrhonline($sid, $uid); 
    // //////////////
    $view = $_GET["view"];
    $pmact = $_GET["pmact"];
    $pact = explode("-", $pmact);
    $pmid = $pact[1];
    $pact = $pact[0];
    // ////////////// 
    // ////ALL LISTS SCRIPT <<
    if ($view == "")$view = "all";
    if ($page == "" || $page <= 0)$page = 1;
    $myid = getuid_sid($sid);
    $doit = false;
    $num_items = getnotifycount($myid, $view); //changable
    $items_per_page = 20;
    $num_pages = ceil($num_items / $items_per_page);
    if ($page > $num_pages)$page = $num_pages;
    $limit_start = ($page-1) * $items_per_page;
    if ($num_items > 0) {
        if ($doit) {
            $exp = "&amp;rwho=$myid";
        } else {
            $exp = "";
        } 
        if ($view == "all") {
            $sql = "SELECT
            a.name, b.id, b.byuid, b.unread, b.starred FROM fun_users a
            INNER JOIN fun_notify b ON a.id = b.byuid
            WHERE b.touid='" . $myid . "'
            ORDER BY b.timesent DESC
            LIMIT $limit_start, $items_per_page
    ";
        } 
        $items = mysql_query($sql);
        echo mysql_error();
        while ($item = mysql_fetch_array($items)) {
            $pminfo = mysql_fetch_array(mysql_query("SELECT text FROM fun_notify WHERE id='" . $item[1] . "'"));
            $pmtext = htmlspecialchars($pminfo[0]);
            $pmdet = substr($pmtext, 0, 1000);
            $tekst = parsepm($pmdet);
            if ($item[3] == "1") {
                $pminfo = mysql_fetch_array(mysql_query("SELECT text, byuid, timesent,touid, reported FROM fun_notify WHERE id='" . $item[1] . "'"));
                if (getuid_sid($sid) == $myid) {
                    mysql_query("UPDATE fun_notify SET unread='0' WHERE id='" . $item[1] . "'");
                } 
                $iml = "<div class=\"comment border_top_light\"><b>&#149;<small>$tekst</small>";
                $iml .= " </b></div>";
            } else {
                $iml = "<div class=\"sett_line border_top\"><small>$tekst</small> <br />";
                $iml .= " </div>";
            } 

            $lnk = "$iml";
            echo "$lnk";
        } 
    } 

    echo dnoonline($sid, $uid);
} else if (isset($_GET['bday'])) 
{
    echo vrhonline($sid, $uid); 
$noi =mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_users where month(`birthday`) = month(curdate()) and dayofmonth(`birthday`) = dayofmonth(curdate());"));
    if($page=="" || $page<=0)$page=1;
    $num_items = $noi[0]; //changable
    $items_per_page= 10;
    $num_pages = ceil($num_items/$items_per_page);
    if(($page>$num_pages)&&$page!=1)$page= $num_pages;
    $limit_start = ($page-1)*$items_per_page;

    //changable sql
        $sql = "SELECT id, name, birthday  FROM fun_users where month(`birthday`) = month(curdate()) and dayofmonth(`birthday`) = dayofmonth(curdate()) ORDER BY name LIMIT $limit_start, $items_per_page";


    if($page>1)
    {
      $ppage = $page-1;
      echo "<div class=\"center\"><a href=\"notify.php?main&amp;page=$ppage\"><img src=\"images/up.png\"></a></div> ";
    }
	
    $items = mysql_query($sql);
    echo mysql_error();
    if(mysql_num_rows($items)>0)
    {
    while ($item = mysql_fetch_array($items))
    {
	 $avlink = getavatar($item[0]);
            if ($avlink != "") {
                $avatar = "<img src=\"$avlink\" class=\"p\" height=\"25\" width=\"25\" alt=\"avatar\"/>";
            } else {
                $avatar = "<img src=\"images/nopic.jpg\" class=\"p\" height=\"25\" width=\"25\" alt=\"avatar\"/>";
            } 
      $uage = getage($item[2]);
      $lnk = "<table class='sett_line' width='100%'><tr><td width='2%'>$avatar</td><td><a href=\"index.php?viewuser&amp;who=$item[0]\">$item[1]</a><br /> <small>Puni $uage godina.</small></td></tr></table>";
      echo "$lnk";
    }
    }
	
    if($page<$num_pages)
    {
      $npage = $page+1;
     echo "<div class=\"center\"><a href=\"notify.php?$action&amp;page=$ppage\"><img src=\"images/down.png\"></a></div> ";
    }
    echo dnoonline($sid, $uid);
}else {
    addonline(getuid_sid($sid), "Izgubljen u notifykacijama.... :D", "");
    echo "Molim vas da se vratite na pocetnu!!!";

    echo dnoonline($sid, $uid);
} 

echo "</body>";
echo "</html>";

?>
