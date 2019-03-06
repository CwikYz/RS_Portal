<?php
/*
* Happy Forum Skripta 
* copyright (c) 2007 -2008 Happy Forum TM
*

*/
// » &#187;
// « &#171;
include("config.php");
include("core.php");
header("Content-type: text/html; charset=ISO-8859-1");
echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>";
echo "<!DOCTYPE html PUBLIC \"-//WAPFORUM//DTD XHTML Mobile 1.0//EN\"\"http://www.wapforum.org/DTD/xhtml-mobile10.dtd\">";
echo "<html xmlns=\"http://www.w3.org/1999/xhtml\">";


?>

<?php

$bcon = connectdb();
$uid = getuid_sid($sid);
// $lang = mysql_fetch_array(mysql_query("SELECT lang FROM fun_users WHERE id='".$uid."'"));
// include("language.php");
if (!$bcon) {
    // $pstyle = gettheme1("1");
    // echo xhtmlhead("obavestenje)",$pstyle);
    echo "<p align=\"center\">";
    echo "<img src=\"images/exit.gif\" alt=\"*\"/><br/>";
    // echo "<b>FORUM JE ZATVOREN!</b><br/>"; 
    // echo "<b>Hvala svim clanovima koji su ucestvovali u dosadasnjem radu HAPPY foruma!</b><br/>";
    echo "GRESKA BAZE PODATAKA!<br/>";
    echo "POKUSAJTE PONOVO ZA 5-10 min!<br/>";
    echo "<b>HVALA NA RAZUMEVANJU</b>";
    echo "</p>";
    dnooffline();
    exit();
} 
$brws = explode("/", $_SERVER['HTTP_USER_AGENT']);
$ubr = $brws[0];
$uip = getip();
$action = $_GET["action"];
$sid = $_SESSION["sid"];
$page = $_GET["page"];
$who = $_GET["who"];
vrh($sid);

cleardata();
if (isipbanned($uip, $ubr)) {
    if (!isshield(getuid_sid($sid))) {
        // $pstyle = gettheme1("1");
        // echo xhtmlhead("$nazivsajta",$pstyle);      echo "<p align=\"center\">";
        echo "<img src=\"images/notok.gif\" alt=\"x\"/><br/>";
        echo "Ova IP adresa je blokirana!<br/>";
        echo "<br/>";

        $banto = mysql_fetch_array(mysql_query("SELECT  timeto FROM fun_penalties WHERE  penalty'2' AND ipadd='" . $uip . "' AND browserm='" . $ubr . "' LIMIT 1 ")); 
        // echo mysql_error();
        $remain = $banto[0] - (time() - $timeadjust) ;
        $rmsg = gettimemsg($remain);
        echo "Vreme za koje ce IP biti odblokiran je: $rmsg<br/><br/>";

        echo "</p>";
        echo "<p>";
        echo "<form action=\"login.php\" method=\"get\">";
        echo "Nick:<br/> <input name=\"loguid\" format=\"*x\" size=\"8\" maxlength=\"30\"/><br/>";
        echo "Lozinka:<br/> <input type=\"password\" name=\"logpwd\" size=\"8\" maxlength=\"30\"/><br/>";
        echo "<input type=\"submit\" value=\"Uloguj se\"/>";
        echo "</form>";
        echo "</p>"; 
        // echo xhtmlfoot();
        exit();
    } 
} 

    $uid = getuid_sid($sid);
    if ((islogged($sid) == false) || ($uid == 0)) {
        // $pstyle = gettheme($sid);
        // echo xhtmlhead("$nazivsajta",$pstyle);      echo "<p align=\"center\">";
        echo "Niste ulogovani<br/>";
        echo "ili vam je vreme isteklo!<br/><br/>";
        echo "<a href=\"index.php\">Login</a>";
        echo "</p>"; 
        // echo xhtmlfoot();
        exit();
    } 

// echo isbanned($uid);
if (isbanned($uid)) {
    // $pstyle=gettheme($sid);
    // echo xhtmlhead("Favorit teme",$pstyle); 
    // echo xhtmlhead("$nazivsajta",$pstyle);      echo "<p align=\"center\">";
    echo "<img src=\"images/notok.gif\" alt=\"x\"/><br/>";
    echo "Ti si <b>Banovan/a</b><br/>";
    $banto = mysql_fetch_array(mysql_query("SELECT timeto fun_penalties WHERE uid='" . $uid . "' AND penalty='1'"));
    $banres = mysql_fetch_array(mysql_query("SELECT lastpnreas FROM fun_users WHERE id='" . $uid . "'"));

    $remain = $banto[0] - (time() - $timeadjust) ;
    $rmsg = gettimemsg($remain);
    echo "Vreme za koje istice ban je: $rmsg<br/><br/>";
    echo "Razlog bana: $banres[0]"; 
    // echo "<a href=\"index.php\">Login</a>";
    echo "</p>"; 
    // echo xhtmlfoot();
    exit();
} 
$res = mysql_query("UPDATE fun_users SET browserm='" . $ubr . "', ipadd='" . $uip . "' WHERE id='" . getuid_sid($sid) . "'");

if (isset($_GET['favtpc'])) {
    $uid = getuid_sid($sid);
    // addvisitor();
    addonline(getuid_sid($sid), "<b>Moje oznacene teme</b>", "");
    echo vrhonline($sid, $uid);

    echo "<div class=\"section border_top\">";
    echo "<div class=\"section_title\"><div class=\"marker\">Oznacene teme</div></div>";
    echo "<div class=\"adv\"><small>";

    if ($page == "" || $page <= 0)$page = 1;
    $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_favtopic WHERE uid='" . $uid . "'"));
    $num_items = $noi[0]; //changable
    $items_per_page = 10;
    $num_pages = ceil($num_items / $items_per_page);
    if (($page > $num_pages) && $page != 1)$page = $num_pages;
    $limit_start = ($page-1) * $items_per_page; 
    // changable sql
    $sql = "SELECT id, favtpc FROM fun_favtopic WHERE uid='" . $uid . "'ORDER BY id DESC LIMIT $limit_start, $items_per_page";
    if ($page > 1) {
        $ppage = $page-1;
        echo "<div class='center border_top'><a href=\"favtopic.php?$action&amp;page=$ppage\"><img src='images/up.png' /></a></div>";
    } 
    $items = mysql_query($sql);
    echo mysql_error();
    if (mysql_num_rows($items) > 0) {
        while ($item = mysql_fetch_array($items)) {
            $imeno = mysql_fetch_array(mysql_query("SELECT name FROM fun_topics WHERE id='" . $item[1] . "'"));

            if ($imeno[0] == "") {
                $obri = "Temu je uklonila administracija <a href=\"favtopic.php?delfav&amp;delt=$item[0]\">[X]</a>";
                echo "$obri<br/>";
            } else {
                $ime = mysql_fetch_array(mysql_query("SELECT name FROM fun_topics WHERE id='" . $item[1] . "'"));
                $link = "<a href=\"index.php?viewtpc&amp;tid=$item[1]\">" . htmlspecialchars($ime[0]) . "</a>";
                $del = "<a href=\"favtopic.php?delfav&amp;delt=$item[0]\">[X]</a>";
                echo "$link   $del<br/>";
            } 
        } 
    } else {
        echo "Vasa lista oznacenih tema je prazna... <br /> Kako da oznacim temu? <br /> U svakoj temi na opisu teme vam stoji link `Oznaci temu` za oznaku teme, klikom na link upisujete temu u listu oznacenih...<br /> Tako sebi olaksavate od preturanja foruma u potrazi za temom... :)";
    } 
    echo "</small></div>";

    if ($page < $num_pages) {
        $npage = $page + 1;
        echo "<div class='center border_top'><a href=\"favtopic.php?$action&amp;page=$npage\"><img src='images/down.png' /></a></div>";
    } 
    echo dnoonline($sid, $uid);
} else if (isset($_GET['lfavtpc'])) {
    $who = $_GET["who"];
    $whonick = getnick_uid($who);
    $uid = $_GET["who"];
    // addvisitor();
    addonline(getuid_sid($sid), "<b>$whonick favorit teme</b>", "");
    echo vrhonline($sid, $uid);

    echo "<div class=\"section border_top\">";
    echo "<div class=\"section_title\"><div class=\"marker\">$whonick Oznacene teme</div></div>";
    echo "<div class=\"adv\"><small>";

    if ($page == "" || $page <= 0)$page = 1;
    $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_favtopic WHERE uid='" . $who . "'"));
    $num_items = $noi[0]; //changable
    $items_per_page = 10;
    $num_pages = ceil($num_items / $items_per_page);
    if (($page > $num_pages) && $page != 1)$page = $num_pages;
    $limit_start = ($page-1) * $items_per_page; 
    // changable sql
    $sql = "SELECT id, favtpc FROM fun_favtopic WHERE uid='" . $who . "'ORDER BY id DESC LIMIT $limit_start, $items_per_page";
    if ($page > 1) {
        $ppage = $page-1;
        echo "<div class='center border_top'><a href=\"favtopic.php?$action&amp;page=$ppage\"><img src='images/up.png' /></a></div>";
    } 
    $items = mysql_query($sql);
    echo mysql_error();
    if (mysql_num_rows($items) > 0) {
        while ($item = mysql_fetch_array($items)) {
            $imeno = mysql_fetch_array(mysql_query("SELECT name FROM fun_topics WHERE id='" . $item[1] . "'"));

            if ($imeno[0] == "") {
                $obri = "Temu je uklonika administracija <a href=\"favtopic.php?delfav&amp;delt=$item[0]\">[X]</a>";
                echo "$obri<br/>";
            } else {
                $ime = mysql_fetch_array(mysql_query("SELECT name FROM fun_topics WHERE id='" . $item[1] . "'"));
                $link = "<a href=\"index.php?viewtpc&amp;tid=$item[1]\">" . htmlspecialchars($ime[0]) . "</a>";

                echo "$link<br/>";
            } 
        } 
    } else {
        echo "<div class=\"section border_top\">";
        echo "<div class=\"section_title\"><div class=\"marker\">$whonick Nema oznacenih tema</div></div>";
    } 

    if ($page < $num_pages) {
        $npage = $page + 1;
        echo "<div class='center border_top'><a href=\"favtopic.php?$action&amp;page=$npage\"><img src='images/down.png' /></a></div>";
    } 
    echo dnoonline($sid, $uid);
} else if (isset($_GET['delfav'])) {
    $uid = getuid_sid($sid);
    $delt = $_GET["delt"];
    addonline(getuid_sid($sid), "<b>Brishe favorit teme</b>", "");
    echo vrhonline($sid, $uid);

    $res = mysql_query("DELETE FROM fun_favtopic WHERE id='" . $delt . "'");
    if ($res) {
        echo "Oznaka sa teme " . $slok . " je uspesno oznacena<br />";
    } else {
        echo "Sa teme " . $slnotok . " nije uspelo skidanje oznake<br />";
    } 
    echo "<a href=\"favtopic.php?favtpc\">Nazad u oznacene teme</a><br/>"; 
    // echo "<img src=\"images/forums.gif\" alt=\"*\"/><a href=\"forums.php?main\">Forumi</a><br/>";
    echo dnoonline($sid, $uid);
} else if (isset($_GET['addfav'])) {
    $uid = getuid_sid($sid);
    $tid = $_GET["tid"];
    addonline(getuid_sid($sid), "<b>Dodaje temu u favorite</b>", "");
    echo vrhonline($sid, $uid);

    $res = mysql_query("INSERT INTO fun_favtopic SET uid='" . $uid . "', favtpc='" . $tid . "'");
    if ($res) {
        echo "Tema " . $slok . " je uspesno oznacena<br />";
    } else {
        echo "Tema " . $slnotok . " nije uspela da se oznaci<br />";
    } 
    echo "<a href=\"index.php?viewtpc&amp;tid=$tid&amp;go=last\">Nazad u temu</a><br/>";
    echo "<a href=\"favtopic.php?favtpc\">Moje oznacene teme</a><br/>"; 
    // echo "<img src=\"images/forums.gif\" alt=\"*\"/><a href=\"forums.php?main\">Forumi</a><br/>";
    echo dnoonline($sid, $uid);
} 

?>

</wml>
