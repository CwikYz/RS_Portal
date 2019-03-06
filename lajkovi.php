<?php

include("core.php");
include("config.php");

header("Content-type: text/html; charset=ISO-8859-1");
echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>";
echo "<!DOCTYPE html PUBLIC \"-//WAPFORUM//DTD XHTML Mobile 1.0//EN\"\"http://www.wapforum.org/DTD/xhtml-mobile10.dtd\">";
echo "<html xmlns=\"http://www.w3.org/1999/xhtml\">";

$bcon = connectdb();
if (!$bcon) {
    echo "<p align=\"center\">";
    echo "<img src=\"images/exit.gif\" alt=\"*\"/><br/>";
    echo "ERROR! cannot connect to database<br/><br/>";
    echo "This error happens usually when backing up the database, please be patient, The site will be up any minute<br/><br/>";

    echo "<b>THANK YOU VERY MUCH</b>";
    echo "</p>";
    exit();
} 
$brws = explode(" ", $HTTP_USER_AGENT);
$ubr = $brws[0];
$uip = getip();
$action = $_GET["action"];
$sid = $_SESSION["sid"];
$page = $_GET["page"];
vrh($sid);

$uid = getuid_sid($sid);

cleardata();
if (isipbanned($uip, $ubr)) {
    if (!isshield(getuid_sid($sid))) {
        echo "<p align=\"center\">";
        echo "<img src=\"images/notok.gif\" alt=\"x\"/><br/>";
        echo "This IP address is blocked<br/>";
        echo "<br/>";
        echo "How ever we grant a shield against IP-Ban for our great users, you can try to see if you are shielded by trying to log-in, if you kept coming to this page that means you are not shielded, so come back when the ip-ban period is over<br/><br/>";
        $banto = mysql_fetch_array(mysql_query("SELECT  timeto FROM fun_penalties WHERE  penalty='2' AND ipadd='" . $uip . "' AND browserm='" . $ubr . "' LIMIT 1 ")); 
        // echo mysql_error();
        $remain = $banto[0] - time();
        $rmsg = gettimemsg($remain);
        echo " IP: $rmsg<br/><br/>";

        echo "</p>";
        echo "<p>";
        echo "<form action=\"login.php\" method=\"get\">";
        echo "username:<br/> <input name=\"loguid\" format=\"*x\" size=\"8\" maxlength=\"30\"/><br/>";
        echo "password:<br/> <input type=\"password\" name=\"logpwd\" size=\"8\" maxlength=\"30\"/><br/>";
        echo "<input type=\"submit\" value=\"login &#187;\"/>";
        echo "</form>";
        echo "</p>";
        exit();
    } 
} 

    $uid = getuid_sid($sid);
    if ((islogged($sid) == false) || ($uid == 0)) {
        echo "<p align=\"center\">";
        echo "<form action=\"login.php\" method=\"get\">";
        echo "Korisnik: \t<input name=\"loguid\" format=\"*x\" size=\"12\" maxlength=\"30\"/><br/>";
        echo "Lozinka:  \t<input type=\"password\" name=\"logpwd\" size=\"12\" maxlength=\"30\"/><br/>";
        echo "<input class=\"button\" type=\"submit\" value=\"Loguj &#187;\"/>";
        echo "</form>";
        echo "</p>";
        echo dnooffline();
        exit();
    } 

// echo isbanned($uid);
if (isbanned($uid)) {
    echo "<p align=\"center\">";
    echo "<img src=\"images/notok.gif\" alt=\"x\"/><br/>";
    echo "You are <b>Banned</b><br/>";
    $banto = mysql_fetch_array(mysql_query("SELECT timeto FROM fun_penalties WHERE uid='" . $uid . "' AND penalty='1'"));
    $banres = mysql_fetch_array(mysql_query("SELECT lastpnreas FROM fun_users WHERE id='" . $uid . "'"));

    $remain = $banto[0] - time();
    $rmsg = gettimemsg($remain);
    echo "Time to finish your penalty: $rmsg<br/><br/>";
    echo "Ban Reason: $banres[0]"; 
    // echo "<a href=\"index.php\">Login</a>";
    echo "</p>";
    exit();
} 
$res = mysql_query("UPDATE fun_users SET browserm='" . $ubr . "', ipadd='" . $uip . "' WHERE id='" . getuid_sid($sid) . "'");
// /////////////////////////////////////////////////// MAIN PAGE
if (isset($_GET['main'])) {
    $who = $_GET["who"];
    addonline(getuid_sid($sid), "lajkovi", "");

    echo vrhonline($sid, $uid);
    echo "<div class=\"section_title\"><div class=\"marker\">Svidja im se</div></div>";
    $lik = "SELECT uid, reqdt FROM fun_shout_like WHERE shid='" . $who . "'";

    $items = mysql_query($lik);
    if (mysql_num_rows($items) > 0) {
        while ($item = mysql_fetch_array($items)) {
            echo "<div class=\"border_top_light\"><div class=\"qn section\" id=\"anchor_fbid_310948112165\"><div class=\"is\">";
            $shnick = getnick_uid($item[0]);
            $avlink = getavatar($item[0]);
            if ($avlink != "") {
                $avatar = "<img src=\"$avlink\" class=\"p\" height=\"25\" width=\"25\" alt=\"avatar\"/>";
            } else {
                $avatar = "<img src=\"images/nopic.jpg\" class=\"p\" height=\"25\" width=\"25\" alt=\"avatar\"/>";
            } 
            echo "$avatar <a href=\"index.php?viewuser&amp;who=$item[0]\">$shnick</a>";
            echo "</div></div></div>";
        } 
    } 
    echo dnoonline($sid, $uid);
} else
if (isset($_GET['profilzid'])) {
    $who = $_GET["who"];
    addonline(getuid_sid($sid), "lajkovi", "");

    echo vrhonline($sid, $uid);
    echo "<div class=\"section_title\"><div class=\"marker\">Svidja im se</div></div>";
    $lik = "SELECT uid, reqdt FROM fun_shout_like_zid WHERE shid='" . $who . "'";

    $items = mysql_query($lik);
    if (mysql_num_rows($items) > 0) {
        while ($item = mysql_fetch_array($items)) {
            echo "<div class=\"border_top_light\"><div class=\"qn section\" id=\"anchor_fbid_310948112165\"><div class=\"is\">";
            $shnick = getnick_uid($item[0]);
            $avlink = getavatar($item[0]);
            if ($avlink != "") {
                $avatar = "<img src=\"$avlink\" class=\"p\" height=\"25\" width=\"25\" alt=\"avatar\"/>";
            } else {
                $avatar = "<img src=\"images/nopic.jpg\" class=\"p\" height=\"25\" width=\"25\" alt=\"avatar\"/>";
            } 
            echo "$avatar <a href=\"index.php?viewuser&amp;who=$item[0]\">$shnick</a>";
            echo "</div></div></div>";
        } 
    } 
    echo dnoonline($sid, $uid);
} else
if (isset($_GET['grupa'])) {
    $who = $_GET["who"];
    addonline(getuid_sid($sid), "lajkovi", "");

    echo vrhonline($sid, $uid);
    echo "<div class=\"section_title\"><div class=\"marker\">Svidja im se</div></div>";
    $lik = "SELECT uid, reqdt FROM fun_shout_like_grupa WHERE shid='" . $who . "'";

    $items = mysql_query($lik);
    if (mysql_num_rows($items) > 0) {
        while ($item = mysql_fetch_array($items)) {
            echo "<div class=\"border_top_light\"><div class=\"qn section\" id=\"anchor_fbid_310948112165\"><div class=\"is\">";
            $shnick = getnick_uid($item[0]);
            $avlink = getavatar($item[0]);
            if ($avlink != "") {
                $avatar = "<img src=\"$avlink\" class=\"p\" height=\"25\" width=\"25\" alt=\"avatar\"/>";
            } else {
                $avatar = "<img src=\"images/nopic.jpg\" class=\"p\" height=\"25\" width=\"25\" alt=\"avatar\"/>";
            } 
            echo "$avatar <a href=\"index.php?viewuser&amp;who=$item[0]\">$shnick</a>";
            echo "</div></div></div>";
        } 
    } 
    echo dnoonline($sid, $uid);
} else {
    // ///////////////////////Main Page Here
    $uid = getuid_sid($sid);
    $whonick = getnick_uid($uid);
    $logoutses = mysql_query("DELETE FROM fun_ses WHERE uid='" . $uid . "'");
    $logoutonline = mysql_query("DELETE FROM fun_online WHERE userid='" . $uid . "'");
    echo "<form action=\"login.php\" method=\"get\">";
    echo "Korisnik: \t<input name=\"loguid\" format=\"*x\" size=\"12\" maxlength=\"30\"/><br/>";
    echo "Lozinka:  \t<input type=\"password\" name=\"logpwd\" size=\"12\" maxlength=\"30\"/><br/>";
    echo "<input class=\"button\" type=\"submit\" value=\"Loguj &#187;\"/>";
    echo "</form>";
    echo dnooffline();
} 

echo "</body>";
echo "</html>";

?>
