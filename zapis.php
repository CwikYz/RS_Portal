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
    echo vrhonline($sid, $uid);

    $uid = getuid_sid($sid);
    $who = $_GET["who"];
    $tnick = getnick_uid($who); 
    // ////ALL LISTS SCRIPT <<
    if ($page == "" || $page <= 0)$page = 1;
    $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_blogs WHERE bowner='" . $who . "'"));
    $num_items = $noi[0]; //changable
    $items_per_page = 10;
    $num_pages = ceil($num_items / $items_per_page);
    if (($page > $num_pages) && $page != 1)$page = $num_pages;
    $limit_start = ($page-1) * $items_per_page;

    $sql = "SELECT id, bname FROM fun_blogs WHERE bowner='" . $who . "' ORDER BY bgdate DESC LIMIT $limit_start, $items_per_page";

    if ($page > 1) {
        $ppage = $page-1;
        echo "<div class='center border_top'><a href=\"zapis.php?main&amp;page=$ppage&amp;who=$who\"><img src='images/up.png' /></a></div>";
    } 

    $items = mysql_query($sql);
    echo mysql_error();
    if (mysql_num_rows($items) > 0) {
        while ($item = mysql_fetch_array($items)) {
            $bname = htmlspecialchars($item[1]);
            if (candelbl($uid, $item[0])) {
                $dl = "<a href=\"genproc.php?delbl&amp;bid=$item[0]\">[X]</a>";
            } else {
                $dl = "";
            } 
            $lnk = "<img src='download/ostalo.png' /> <a href=\"zapis.php?viewblog&amp;bid=$item[0]\">$bname</a>";
            $lnkz = "<a href=\"zapis.php?viewblog&amp;bid=$item[0]\">Citaj dalje...</a>";
            $komentara = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_blogcomment WHERE blogowner='" . $item[0] . "'"));

            $tekst = mysql_fetch_array(mysql_query("SELECT btext FROM fun_blogs WHERE id='" . $item[0] . "'"));
            $izvuceno = parsepm($tekst[0]);
            $pastovano = substr($izvuceno, 0, 100);
            echo "<div class='comment comm_adv'>$lnk $dl<br /><div class='titl'>$pastovano...</div> <br /> Broj komentara: $komentara[0]</div>";
        } 
    } 

    if ($page < $num_pages) {
        $npage = $page + 1;
        echo "<div class='center border_top'><a href=\"zapis.php?main&amp;page=$npage&amp;who=$who\"><img src='images/down.png' /></a></div>";
    } 

    if ($who == $uid) {
        echo "<div class='sett_line'><img src='img/img_44.png' /><a href=\"zapis.php?dodaj\">";
        echo "Dodaj zapis</a></div>";
    } 

    echo dnoonline($sid, $uid);
} else if (isset($_GET['dodaj'])) {
    addonline(getuid_sid($sid), "Adding a blog", "");

    echo vrhonline($sid, $uid);
    echo "<div class='titl'>Sve lepe, price... posvete... bilo sta... zapisite... da se ne zaborave... :)<br /> Pricu o najlepsoj ljubavi... Prica o slomljenom srcu... Sve... PISITE... ;)</div>";
    echo "<form action=\"zapis.php?addblg\" method=\"post\">";
    echo "<div class='sett_line'> Naziv: <input name=\"btitle\" maxlength=\"30\"/></div>";
    echo "<div class='sett_line'> Tekst: <br /> <textarea name=\"msgtxt\" rows='4' /></textarea></div>";
    echo "<input type=\"submit\" class='button' value=\"Sacuvaj\"/>";
    echo "</form>";
    $bnick = getnick_uid($uid);
    echo "<div class='comment comm_adv'><img src='img/img_44.png' /> <a href=\"zapis.php?blogs&amp;who=$uid\">$bnick zapisi</a></div>";

    echo dnoonline($sid, $uid);
} else if (isset($_GET['addblg'])) {
    $btitle = $_POST["btitle"];
    $msgtxt = $_POST["msgtxt"]; 
    // $qut = $_POST["qut"];
    addonline(getuid_sid($sid), "Adding a blog", "");

    echo vrhonline($sid, $uid);

    $crdate = time(); 
    // $uid = getuid_sid($sid);
    $res = false;

    if ((trim($msgtxt) != "") && (trim($btitle) != "")) {
        $res = mysql_query("INSERT INTO fun_blogs SET bowner='" . $uid . "', bname='" . $btitle . "', bgdate='" . $crdate . "', btext='" . $msgtxt . "'");
    } 
    if ($res) {
        echo "Zapis uspesno sacuvan...";
    } else {
        echo "Zapis uspesno sacuvan...";
    } 
    $bnick = getnick_uid($uid);
    echo "<div class='comment comm_adv'><img src='img/img_44.png' /> <a href=\"zapis.php?blogs&amp;who=$uid\">$bnick zapisi</a></div>";

    echo dnoonline($sid, $uid);
} else if (isset($_GET['viewblog'])) {
    $bid = $_GET["bid"];
    $msgtxt = $_POST["msgtxt"];
    addonline(getuid_sid($sid), "Viewing Users Blog", "");

    echo vrhonline($sid, $uid); 
    // /////////////////////////////
    $crdate = time(); 
    // $uid = getbid_sid($sid);
    $res = false;

    if ($msgtxt == "") {
    } else {
        $res = mysql_query("INSERT INTO fun_blogcomment SET blogowner='" . $bid . "', blogsigner='" . $uid . "', dtime='" . $crdate . "', blogmsg='" . $msgtxt . "'");

        if ($res) {
            echo "<div class=\"pad\"><div class=\"notif border_bottom\">Vasa poruka je uspesno postavljena!!!</div>";
            $vlasnik = mysql_fetch_array(mysql_query("SELECT bowner FROM fun_blogs WHERE id='" . $bid . "'"));
            $zzz = getnick_uid($uid);

            $msg = "[user=" . $uid . "]" . $zzz . "[/user] je prokomentarisao/la vas [blog=" . $bid . "]zapis[/blog].";
            mysql_query("INSERT INTO fun_notify SET text='" . $msg . "', byuid='" . $uid . "', touid='" . $vlasnik[0] . "', unread='1', timesent='" . time() . "'");
        } else {
            echo "<div class=\"pad\"><div class=\"error border_bottom\">Vasa poruka nije postavljena! Mogucnost da je greska u bazi, pa vas molimo da pokusate kasnije!</div>";
        } 
    } 
    // ///////////////////////////////
    $pminfo = mysql_fetch_array(mysql_query("SELECT btext, bname, bgdate, bowner, id FROM fun_blogs WHERE id='" . $bid . "'"));
    $bttl = htmlspecialchars($pminfo[1]);
    $btxt = parsemsg($pminfo[0], $sid);
    $bnick = getnick_uid($pminfo[3]);
    $vbbl = "<a href=\"index.php?viewuser&amp;who=$pminfo[3]\">$bnick</a><br/>";
    $avlink = getavatar($pminfo[3]);
    if ($avlink != "") {
        $avatar = "<img src=\"$avlink\" class=\"p\" height=\"25\" width=\"25\" alt=\"avatar\"/>";
    } else {
        $avatar = "<img src=\"images/nopic.jpg\" class=\"p\" height=\"25\" width=\"25\" alt=\"avatar\"/>";
    } 

    $tmstamp = $pminfo[2];
    $var1 = date("his", $tmstamp);
    $var2 = time ();
    $var21 = date("his", $var2);
    $var3 = $var21 - $var1;
    $var4 = date("s", $var3);
    $remain = time() - $tmstamp;
    $tmdt = gettimemsg($remain);

    echo "<div class='sett_line'><table border='0' width='100%' id='download'><tr><td align='center' width='1' height='1'>$avatar</td><td>$vbbl$tmdt</td></tr></table><b>$bttl</b></div>";
    echo "<div class='adv'><small>$btxt</small></div>";

    echo "<div class=\"section comment comm_adv\"><form action=\"zapis.php?$action&amp;bid=$bid\" method=\"post\"><div>Dodaj komentar</div>";
    echo "<textarea name=\"msgtxt\" rows=\"3\"></textarea><br/>";
    echo "<input class=\"button\" type=\"submit\" value=\"Upisi komentar\"/>";
    echo "</form></div>";
    // //////////////////////////////////////////
    if ($page == "" || $page <= 0)$page = 1;
    $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_blogcomment WHERE blogowner='" . $bid . "'"));
    $num_items = $noi[0]; //changable
    $items_per_page = 5;
    $num_pages = ceil($num_items / $items_per_page);
    if (($page > $num_pages) && $page != 1)$page = $num_pages;
    $limit_start = ($page-1) * $items_per_page;

    $sql = "SELECT blogowner, blogsigner, blogmsg, dtime, id FROM fun_blogcomment WHERE blogowner='" . $bid . "' ORDER BY dtime DESC LIMIT $limit_start, $items_per_page";

    if ($page > 1) {
        $ppage = $page-1;
        echo "<div class='center border_top'><a href=\"zapis.php?$action&page=$ppage&sid=$sid&bid=$bid\"><img src='images/up.png' /></a></div>";
    } 

    $items = mysql_query($sql);
    echo mysql_error();
    if (mysql_num_rows($items) > 0) {
        while ($item = mysql_fetch_array($items)) {
            $avlink = getavatar($item[1]);
            if ($avlink != "") {
                $avatar = "<img src=\"$avlink\" class=\"p\" height=\"25\" width=\"25\" alt=\"avatar\"/>";
            } else {
                $avatar = "<img src=\"images/nopic.jpg\" class=\"p\" height=\"25\" width=\"25\" alt=\"avatar\"/>";
            } 
            $snick = getnick_uid($item[1]);
            $lnk = "<a href=\"index.php?viewuser&who=$item[1]&sid=$sid\">$snick</a>";
            $var1 = date("his", $item[3]);
            $var2 = time ();
            $var21 = date("his", $var2);
            $var3 = $var21 - $var1;
            $var4 = date("s", $var3);
            $remain = time() - $item[3];
            $bs = gettimemsg($remain);
            echo "<div class='comment comm_adv'><table border='0' width='100%' id='download'><tr><td align='center' width='1' height='1'>$avatar</td><td> $lnk<br/>$bs</td></tr></table><small>";

            $text = parsepm($item[2], $sid);
            echo "$text";
            echo "</small></div>";
        } 
    } 

    if ($page < $num_pages) {
        $npage = $page + 1;
        echo "<div class='center border_top'><a href=\"zapis.php?$action&page=$npage&sid=$sid&bid=$bid\"><img src='images/down.png' /></a></div>";
    } 
    // //////////////////////////////////////////
    $bnick = getnick_uid($pminfo[3]);
    echo "<div class='sett_line'><img src='img/img_44.png' /> <a href=\"zapis.php?blogs&amp;who=$uid\">$bnick zapisi</a></div>";

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
