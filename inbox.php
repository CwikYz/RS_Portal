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

if (isset($_GET['sendpm'])) {
    addonline(getuid_sid($sid), "Sending PM", "");

    echo vrhonline($sid, $uid);

    $whonick = getnick_uid($who);
    echo "Poruka za $whonick<br/><br/>";
    echo "<form action=\"inbxproc.php?sendpm&amp;who=$who\" method=\"post\">";
    echo "<textarea name=\"pmtext\" rows='5'/></textarea><br/>";
    echo "<input type=\"submit\" class='button' value=\"Posalji\"/>";
    echo "</form>";

    echo dnoonline($sid, $uid);
} else if (isset($_GET['sendto'])) {
    addonline(getuid_sid($sid), "Sending PM", "");

    echo vrhonline($sid, $uid);
    $whonick = getnick_uid($who);
    echo "<form action=\"inbxproc.php?sendto\" method=\"post\">";
    echo "Poruka za: <input name=\"who\" format=\"*x\" maxlength=\"100\"/><br/>";
    echo "Tekst: <br /><textarea name=\"pmtext\" rows='5'/></textarea><br/>";

    echo "<input type=\"submit\" class='button' value=\"Posalji\"/>";
    echo "</form>";

    echo dnoonline($sid, $uid);
} else if (isset($_GET['main'])) {
    addonline(getuid_sid($sid), "User Inbox", "");

    echo vrhonline($sid, $uid); 
    // //////////////
    $view = $_GET["view"];
    $pmact = $_GET["pmact"];
    $pact = explode("-", $pmact);
    $pmid = $pact[1];
    $pact = $pact[0];

    $pminfo = mysql_fetch_array(mysql_query("SELECT text, byuid, touid, reported FROM fun_private WHERE id='" . $pmid . "'"));

    if ($pact == "del") {
        addonline(getuid_sid($sid), "Deleting PM", "");
        if (getuid_sid($sid) == $pminfo[2]) {
            if ($pminfo[3] == "1") {
                echo "Poruka je na inspekciji kod administracije i nju nemozete obrisati!!!";
            } else {
                $del = mysql_query("DELETE FROM fun_private WHERE id='" . $pmid . "' ");
                if ($del) {
                    echo "Poruka je uspesno izbrisana!";
                } else {
                    echo "Poruka nije obrisana!";
                } 
            } 
        } else {
            echo "GRESKA";
        } 
    } else if ($pact == "str") {
        addonline(getuid_sid($sid), "Starring PM", "");
        if (getuid_sid($sid) == $pminfo[2]) {
            $str = mysql_query("UPDATE fun_private SET starred='1' WHERE id='" . $pmid . "' ");
            if ($str) {
                echo "Poruka je uspesno oznacena!";
            } else {
                echo "Nemogu da oznacim poruku!";
            } 
        } else {
            echo "Greska";
        } 
    } else if ($pact == "ust") {
        addonline(getuid_sid($sid), "Unstarring PM", "");
        if (getuid_sid($sid) == $pminfo[2]) {
            $str = mysql_query("UPDATE fun_private SET starred='0' WHERE id='" . $pmid . "' ");
            if ($str) {
                echo "Oznaka skinuta!";
            } else {
                echo "Nemogu da skinem oznaku!";
            } 
        } else {
            echo "GRESKA";
        } 
    } else if ($pact == "rpt") {
        addonline(getuid_sid($sid), "Reporting PM", "");
        if (getuid_sid($sid) == $pminfo[2]) {
            if ($pminfo[3] == "0") {
                $str = mysql_query("UPDATE fun_private SET reported='1' WHERE id='" . $pmid . "' ");
                if ($str) {
                    echo "Administracija je obavestena!";
                } else {
                    echo "GRESKA";
                } 
            } else {
                echo "Administracija je vec obavestena!";
            } 
        } else {
            echo "Greska";
        } 
    } 
    echo "<div id=\"search\" class=\"summary border_bottom\">";
    echo "<form action=\"inbox.php?snbx&amp;sin=1&amp;sor=1\" method=\"post\">";
    echo "<input name=\"stext\" maxlength=\"30\"/>";
    echo "<input class=\"button\" type=\"submit\" value=\"Search\"/>";
    echo "</form>";
    echo "</div>";
    echo "<small><a href=\"inbox.php?sendto\">Posalji Poruku</a></small> <br />";

    if ($page > 1) {
        $ppage = $page-1;
        echo "<div class=\"section border_top\">";
        echo "<div class=\"center\"><a href=\"inbox.php?main&amp;page=$ppage&amp;view=$view$exp\"><img src=\"images/up.png\"></a></div>";
        echo "</div>";
    } 
    // //////////////
    // ////ALL LISTS SCRIPT <<
    if ($view == "")$view = "all";
    if ($page == "" || $page <= 0)$page = 1;
    $myid = getuid_sid($sid);
    $doit = false;
    $num_items = getpmcount($myid, $view); //changable
    $items_per_page = 10;
    $num_pages = ceil($num_items / $items_per_page);
    if ($page > $num_pages)$page = $num_pages;
    $limit_start = ($page-1) * $items_per_page;
    if ($num_items > 0) {
        if ($doit) {
            $exp = "&amp;rwho=$myid";
        } else {
            $exp = "";
        } 
        // changable sql
        if ($view == "all") {
            $sql = "SELECT
            a.name, b.id, b.byuid, b.unread, b.starred FROM fun_users a
            INNER JOIN fun_private b ON a.id = b.byuid
            WHERE b.touid='" . $myid . "'
            ORDER BY b.timesent DESC
            LIMIT $limit_start, $items_per_page
    ";
        } else if ($view == "snt") {
            $sql = "SELECT
            a.name, b.id, b.touid, b.unread, b.starred FROM fun_users a
            INNER JOIN fun_private b ON a.id = b.touid
            WHERE b.byuid='" . $myid . "'
            ORDER BY b.timesent DESC
            LIMIT $limit_start, $items_per_page
    ";
        } else if ($view == "str") {
            $sql = "SELECT
            a.name, b.id, b.byuid, b.unread, b.starred FROM fun_users a
            INNER JOIN fun_private b ON a.id = b.byuid
            WHERE b.touid='" . $myid . "' AND b.starred='1'
            ORDER BY b.timesent DESC
            LIMIT $limit_start, $items_per_page
    ";
        } else if ($view == "urd") {
            $sql = "SELECT
            a.name, b.id, b.byuid, b.unread, b.starred FROM fun_users a
            INNER JOIN fun_private b ON a.id = b.byuid
            WHERE b.touid='" . $myid . "' AND b.unread='1'
            ORDER BY b.timesent DESC
            LIMIT $limit_start, $items_per_page
    ";
        } 

        $items = mysql_query($sql);
        echo mysql_error();
        while ($item = mysql_fetch_array($items)) {
            $pminfo = mysql_fetch_array(mysql_query("SELECT text FROM fun_private WHERE id='" . $item[1] . "'"));
            $pmtext = htmlspecialchars($pminfo[0]);
            $pmdet = substr($pmtext, 0, 50);
            if ($item[3] == "1") {
                $iml = "<b><div id=\"comments_468323575248\" class=\"section border_bottom\"><div class=\"comment\"><table width=\"100%\"><tr><td align=\"left\" style=\"width:50%\"><a href=\"inbox.php?readpm&amp;pmid=$item[1]\">&#149; $item[0]</a></b></td><td align=\"right\" style=\"width:50%\"><a href=\"inbox.php?readpm&amp;pmid=$item[1]\"><img src=\"images/right.png\"></a></td></tr></table><small>$pmdet...</small> <br />";
                $iml .= "<small><a href=\"inbox.php?main&amp;pmact=del-$item[1]\">Izbrisi</a> | ";
                if (isstarred($item[1])) {
                    $iml .= "<a href=\"inbox.php?main&amp;pmact=ust-$item[1]\">Skini oznaku</a> | ";
                } else {
                    $iml .= "<a href=\"inbox.php?main&amp;pmact=str-$item[1]\">Oznaci</a> | ";
                } 
                $iml .= "<a href=\"inbox.php?main&amp;pmact=rpt-$item[1]\">Prijavi</a></small>";

                $iml .= " </div></div>";
            } else {
                if ($item[4] == "1") {
                    $iml = "<div class=\"sett_line\"><table width=\"100%\"><tr><td align=\"left\" style=\"width:50%\"> <img src=\"img/img_14.png\"> <a href=\"inbox.php?readpm&amp;pmid=$item[1]\"> $item[0]</a></td><td align=\"right\" style=\"width:50%\"><a href=\"inbox.php?readpm&amp;pmid=$item[1]\"><img src=\"images/right.png\"></a></td></tr></table><small>$pmdet...</small> <br />";
                    $iml .= "<small><a href=\"inbox.php?main&amp;pmact=del-$item[1]\">Izbrisi</a> | ";
                    if (isstarred($item[1])) {
                        $iml .= "<a href=\"inbox.php?main&amp;pmact=ust-$item[1]\">Skini oznaku</a> | ";
                    } else {
                        $iml .= "<a href=\"inbox.php?main&amp;pmact=str-$item[1]\">Oznaci</a> | ";
                    } 
                    $iml .= "<a href=\"inbox.php?main&amp;pmact=rpt-$item[1]\">Prijavi</a></small>";

                    $iml .= " </div>";
                } else {
                    $iml = "<div class=\"sett_line\"><table width=\"100%\"><tr><td align=\"left\" style=\"width:50%\"><a href=\"inbox.php?readpm&amp;pmid=$item[1]\"> $item[0]</a></td><td align=\"right\" style=\"width:50%\"><a href=\"inbox.php?readpm&amp;pmid=$item[1]\"><img src=\"images/right.png\"></a></td></tr></table><small>$pmdet...</small> <br />";
                    $iml .= "<small><a href=\"inbox.php?main&amp;pmact=del-$item[1]\">Izbrisi</a> | ";
                    if (isstarred($item[1])) {
                        $iml .= "<a href=\"inbox.php?main&amp;pmact=ust-$item[1]\">Skini oznaku</a> | ";
                    } else {
                        $iml .= "<a href=\"inbox.php?main&amp;pmact=str-$item[1]\">Oznaci</a> | ";
                    } 
                    $iml .= "<a href=\"inbox.php?main&amp;pmact=rpt-$item[1]\">Prijavi</a></small>";
                    $iml .= " </div>";
                } 
            } 
            $lnk = "$iml";
            echo "<div class=\"border_top_light\"><div class=\"qn section\" id=\"anchor_fbid_310948112165\"><div class=\"is\">$lnk";
            echo "</div></div></div>";
        } 

        if ($page < $num_pages) {
            echo "<div class=\"section border_top\">";
            $npage = $page + 1;
            echo "<div class=\"center\"><a href=\"inbox.php?main&amp;page=$npage&amp;view=$view$exp\"><img src=\"images/down.png\"></a></div>";
            echo "</div>";
        } 

        echo "<div class=\"section border_top\">";

        echo "<a href=\"inbox.php?main&amp;view=all\">Sve poruke</a> <br />";
        echo "<a href=\"inbox.php?main&amp;view=snt\">Poslate poruke</a> <br />";
        echo "<a href=\"inbox.php?main&amp;view=str\">Arhivirane poruke</a> <br />";
        echo "<a href=\"inbox.php?main&amp;view=urd\">Neprocitane poruke</a>";

        echo "</div>";

        echo "<div class=\"section border_top\">";
        echo "<form action=\"inbxproc.php?proall\" method=\"post\">";
        echo "Obrisi: <select name=\"pmact\">";
        echo "<option value=\"ust\">Neoznacene</option>";
        echo "<option value=\"red\">Procitane</option>";
        echo "<option value=\"all\">Sve</option>";
        echo "</select>";
        echo "<input type=\"submit\" class=\"button\" value=\"OK\"/>";
        echo "</form>";

        echo "</div>";
    } else {
        if ($view == "all") {
            echo "<div class=\"border_top_light\"><div class=\"qn section\" id=\"anchor_fbid_310948112165\"><div class=\"is\"><small>Nemate primljenih poruka.</small></div></div></div>";
        } 
        if ($view == "snt") {
            echo "<div class=\"border_top_light\"><div class=\"qn section\" id=\"anchor_fbid_310948112165\"><div class=\"is\"><small>Nemate poslatih poruka.</small></div></div></div>";
        } 
        if ($view == "str") {
            echo "<div class=\"border_top_light\"><div class=\"qn section\" id=\"anchor_fbid_310948112165\"><div class=\"is\"><small>Nemate arhiviranih poruka.</small></div></div></div>";
        } 
        if ($view == "urd") {
            echo "<div class=\"border_top_light\"><div class=\"qn section\" id=\"anchor_fbid_310948112165\"><div class=\"is\"><small>Nemate neprocitanih poruka.</small></div></div></div>";
        } 
    } 
    // //// UNTILL HERE >>
    echo dnoonline($sid, $uid);
} else if (isset($_GET['readpm'])) {
    addonline(getuid_sid($sid), "Reading PM", "");
    echo vrhonline($sid, $uid);

    $pminfo = mysql_fetch_array(mysql_query("SELECT text, byuid, timesent,touid, reported FROM fun_private WHERE id='" . $pmid . "'"));
    if (getuid_sid($sid) == $pminfo[3]) {
        $chread = mysql_query("UPDATE fun_private SET unread='0' WHERE id='" . $pmid . "'");
    } 

    if (($pminfo[3] == getuid_sid($sid)) || ($pminfo[1] == getuid_sid($sid))) {
        $tmstamp = $pminfo[2];
        $tmdt = date("d m Y - H:i:s", $tmstamp);
        $ptxt = "Poruka od: ";

        $bylnk = "<a href=\"index.php?viewuser&amp;who=$pminfo[1]\">" . getnick_uid($pminfo[1]) . "</a>";

        $ptxtz = "Za: ";

        $bylnkz = "<a href=\"index.php?viewuser&amp;who=$pminfo[3]\">" . getnick_uid($pminfo[3]) . "</a><br />";
        $noi = mysql_fetch_array(mysql_query("SELECT timesent FROM fun_private WHERE id='" . $pmid . "'"));
        $var1 = date("his", $noi[0]);
        $var2 = time ();
        $var21 = date("his", $var2);
        $var3 = $var21 - $var1;
        $var4 = date("s", $var3);
        $remain = time() - $noi[0];
        $idle = gettimemsg($remain);

        echo "$ptxt $bylnk<br /> <br />$ptxtz $bylnkz<br /><br/>";

        $pmtext = parsepm($pminfo[0], $sid);
        $pmtext = str_replace("/llfaqs", "", $pmtext);
        $pmtext = str_replace("/reader", getnick_uid($pminfo[3]), $pmtext);
        if (isspam($pmtext)) {
            if (($pminfo[4] == "0") && ($pminfo[1] != 1)) {
                mysql_query("UPDATE fun_private SET reported='1' WHERE id='" . $pmid . "'");
            } 
        } 
        echo "<div class=\"titl\"><div class='section border_bottom'>Poslato $idle</div>" . $pmtext . "</div>";

        echo "<br/>Odgovor: ";
        echo "<form action=\"inbxproc.php?sendpm&amp;who=$pminfo[1]\" method=\"post\">";
        echo "<textarea name=\"pmtext\" rows=\"4\" value=\"\"></textarea><br />";
        echo "<input type=\"submit\" class=\"button\" value=\"Odgovori\"/><br />";
        echo "<small><a href=\"inbox.php?main&amp;pact=del-$pmid\">Izbrisi</a> | ";
        if (isstarred($pmid)) {
            echo "<a href=\"inbox.php?main&amp;pact=ust-$pmid\">Skini oznaku</a> | ";
        } else {
            echo "<a href=\"inbox.php?main&amp;pact=str-$pmid\">Oznaci</a> | ";
        } 
        echo "<a href=\"inbox.php?main&amp;pact=rpt-$pmid\">Prijavi</a></small> <br /> <br />";

        if ($page == "" || $page <= 0)$page = 1;
        $myid = getuid_sid($sid);
        $pms = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_private WHERE (byuid=$pminfo[1] AND touid=$pminfo[3]) OR (byuid=$pminfo[3] AND touid=$pminfo[1]) ORDER BY timesent"));
        echo mysql_error();
        $num_items = $pms[0]; //changable
        $items_per_page = 7;
        $num_pages = ceil($num_items / $items_per_page);
        if ($page > $num_pages)$page = $num_pages;
        $limit_start = ($page-1) * $items_per_page;
        if ($num_items > 0) {
            if ($page > 1) {
                $ppage = $page-1;
                echo "<div class='section border_top center'><a href=\"inbox.php?readpm&amp;page=$ppage&amp;pmid=$pmid&amp;who=$who\"><img src='images/up.png'></a></div>";
            } 
            $pms = mysql_query("SELECT byuid, text, timesent FROM fun_private WHERE (byuid=$pminfo[1] AND touid=$pminfo[3]) OR (byuid=$pminfo[3] AND touid=$pminfo[1]) ORDER BY timesent DESC LIMIT $limit_start, $items_per_page");
            while ($pm = mysql_fetch_array($pms)) {
                $var1 = date("his", $pm[2]);
                $var2 = time ();
                $var21 = date("his", $var2);
                $var3 = $var21 - $var1;
                $var4 = date("s", $var3);
                $remain = time() - $pm[2];
                $shdt = gettimemsg($remain);
                $postar = getnick_uid($pm[0]);
                $avlink = getavatar($pm[0]);
                if ($avlink != "") {
                    $avatar = "<img src=\"$avlink\" class=\"p\" height=\"25\" width=\"25\" alt=\"avatar\"/>";
                } else {
                    $avatar = "<img src=\"images/nopic.jpg\" class=\"p\" height=\"25\" width=\"25\" alt=\"avatar\"/>";
                } 
                $bylnk = "<a href=\"index.php?viewuser&amp;who=$pm[0]\">$postar</a>";
                echo "<div class='border_top_light'><table border='0' width='100%' id='download'><tr><td align='left' width='1' height='1'>$avatar</td><td>$bylnk";

                echo " <br/><small>$shdt</td></tr></table>";

                echo parsepm($pm[1], $sid);

                echo "</small></div>";
            } 

            if ($page < $num_pages) {
                $npage = $page + 1;
                echo "<div class='section border_top center'><a href=\"inbox.php?readpm&amp;page=$npage&amp;pmid=$pmid&amp;who=$who\"><img src='images/down.png'></a></div>";
            } 
        } 
    } else {
        echo "Ovo nije vasa poruka";
    } 
    echo dnoonline($sid, $uid);
    echo "</card>";
} else if (isset($_GET['dialog'])) {
    addonline(getuid_sid($sid), "Viewing PM Dialog", "");
    echo vrhonline($sid, $uid);
    $uid = getuid_sid($sid);
    if ($page == "" || $page <= 0)$page = 1;
    $myid = getuid_sid($sid);
    $pms = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_private WHERE (byuid='" . $uid . "' AND touid='" . $who . "') OR (byuid='" . $who . "' AND touid='" . $uid . "') ORDER BY timesent"));
    echo mysql_error();
    $num_items = $pms[0]; //changable
    $items_per_page = 7;
    $num_pages = ceil($num_items / $items_per_page);
    if ($page > $num_pages)$page = $num_pages;
    $limit_start = ($page-1) * $items_per_page;
    if ($num_items > 0) {
        echo "<div>";
        $pms = mysql_query("SELECT byuid, text, timesent FROM fun_private WHERE (byuid='" . $uid . "' AND touid='" . $who . "') OR (byuid='" . $who . "' AND touid='" . $uid . "') ORDER BY timesent LIMIT $limit_start, $items_per_page");
        while ($pm = mysql_fetch_array($pms)) {
            if (isonline($pm[0])) {
                $iml = "<img src=\"images/onl.gif\" alt=\"+\"/>";
            } else {
                $iml = "<img src=\"images/ofl.gif\" alt=\"-\"/>";
            } 
            $bylnk = "<a href=\"index.php?viewuser&amp;who=$pm[0]\">$iml" . getnick_uid($pm[0]) . "</a>";
            echo $bylnk;
            $tmopm = date("d m y - h:i:s", $pm[2]);
            echo " $tmopm<br/>";

            echo parsepm($pm[1], $sid);

            echo "<br/>--------------<br/>";
        } 
        echo "</div><div class=\"center\">";
        if ($page > 1) {
            $ppage = $page-1;
            echo "<a href=\"inbox.php?dialog&amp;page=$ppage&amp;who=$who\">&#171;PREV</a> ";
        } 
        if ($page < $num_pages) {
            $npage = $page + 1;
            echo "<a href=\"inbox.php?dialog&amp;page=$npage&amp;who=$who\">Next&#187;</a>";
        } 
        echo "<br/>$page/$num_pages<br/>";
        if ($num_pages > 2) {
            $rets = "<form action=\"inbox.php\" method=\"get\">";
            $rets .= "Jump to page<input name=\"page\" format=\"*N\" size=\"3\"/>";
            $rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
            $rets .= "<input type=\"hidden\" name=\"ses\" value=\"$ses\"/>";
            $rets .= "<input type=\"hidden\" name=\"who\" value=\"$who\"/>";
            $rets .= "<input type=\"submit\" value=\"GO\"/>";
            $rets .= "</form>";
            echo $rets;
        } 
    } else {
        echo "<div class=\"center\">";
        echo "NO DATA";
    } 
    echo "<a href=\"inbox.php?main&amp;ses=$ses\">Back to inbox</a><br/>";
    echo "<a href=\"index.php?main&amp;ses=$ses\"><img src=\"images/home.gif\" alt=\"*\"/>";
    echo "Home</a>";
    echo "</div>";
    echo dnoonline($sid, $uid);
} else if (isset($_GET['snbx'])) {
    $stext = $_POST["stext"];
    $sin = $_GET["sin"];
    $sor = $_GET["sor"];
    addonline(getuid_sid($sid), "Inbox search", "");

    echo vrhonline($sid, $uid);

    $myid = getuid_sid($sid);
    if (trim($stext) == "") {
        echo "<small>Pretraga nije uspesna.</small>";
    } else {
        // begin search
        if ($page == "" || $page < 1)$page = 1;
        if ($sin == 1) {
            $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*)  FROM fun_private  WHERE text LIKE '%" . $stext . "%' AND touid='" . $myid . "'"));
        } else if ($sin == 2) {
            $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*)  FROM fun_private  WHERE text LIKE '%" . $stext . "%' AND byuid='" . $myid . "'"));
        } else {
            $stext = getuid_nick($stext);
            $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*)  FROM fun_private  WHERE byuid ='" . $stext . "' AND touid='" . $myid . "'"));
        } 
        $num_items = $noi[0];
        $items_per_page = 10;
        $num_pages = ceil($num_items / $items_per_page);
        if (($page > $num_pages) && $page != 1)$page = $num_pages;
        $limit_start = ($page-1) * $items_per_page;

        if ($sin == "1") {
            /*
            $where_table = "fun_blogs";
            $cond = "btext";
            $select_fields = "id, bname";*/

            if ($sor == "1") {
                // $ord_fields = "bname";
                $sql = "SELECT
            a.name, b.id, b.byuid, b.unread, b.starred FROM fun_users a
            INNER JOIN fun_private b ON a.id = b.byuid
            WHERE b.touid='" . $myid . "' AND b.text like '%" . $stext . "%'
            ORDER BY b.timesent DESC
            LIMIT $limit_start, $items_per_page"; 
                // echo $sql;
            } else if ($sor == "2") {
                $sql = "SELECT
            a.name, b.id, b.byuid, b.unread, b.starred FROM fun_users a
            INNER JOIN fun_private b ON a.id = b.byuid
            WHERE b.touid='" . $myid . "' AND b.text like '%" . $stext . "%'
            ORDER BY b.timesent 
            LIMIT $limit_start, $items_per_page";
            } else {
                $sql = "SELECT
            a.name, b.id, b.byuid, b.unread, b.starred FROM fun_users a
            INNER JOIN fun_private b ON a.id = b.byuid
            WHERE b.touid='" . $myid . "' AND b.text like '%" . $stext . "%'
            ORDER BY a.name
            LIMIT $limit_start, $items_per_page";
            } 
        } else if ($sin == "2") {
            if ($sor == "1") {
                // $ord_fields = "bname";
                $sql = "SELECT
            a.name, b.id, b.touid, b.unread, b.starred FROM fun_users a
            INNER JOIN fun_private b ON a.id = b.byuid
            WHERE b.byuid='" . $myid . "' AND b.text like '%" . $stext . "%'
            ORDER BY b.timesent DESC
            LIMIT $limit_start, $items_per_page"; 
                // echo $sql;
            } else if ($sor == "2") {
                $sql = "SELECT
            a.name, b.id, b.touid, b.unread, b.starred FROM fun_users a
            INNER JOIN fun_private b ON a.id = b.byuid
            WHERE b.byuid='" . $myid . "' AND b.text like '%" . $stext . "%'
            ORDER BY b.timesent 
            LIMIT $limit_start, $items_per_page";
            } else {
                $sql = "SELECT
            a.name, b.id, b.touid, b.unread, b.starred FROM fun_users a
            INNER JOIN fun_private b ON a.id = b.touid
            WHERE b.byuid='" . $myid . "' AND b.text like '%" . $stext . "%'
            ORDER BY a.name
            LIMIT $limit_start, $items_per_page";
            } 
        } else if ($sin == "3") {
            if ($sor == "1") {
                // $ord_fields = "bname";
                $sql = "SELECT
            a.name, b.id, b.byuid, b.unread, b.starred FROM fun_users a
            INNER JOIN fun_private b ON a.id = b.byuid
            WHERE b.touid='" . $myid . "' AND b.byuid ='" . $stext . "'
            ORDER BY b.timesent DESC
            LIMIT $limit_start, $items_per_page";
            } else if ($sor == "2") {
                $sql = "SELECT
            a.name, b.id, b.byuid, b.unread, b.starred FROM fun_users a
            INNER JOIN fun_private b ON a.id = b.byuid
            WHERE b.touid='" . $myid . "' AND b.byuid ='" . $stext . "'
            ORDER BY b.timesent
            LIMIT $limit_start, $items_per_page";
            } else {
                $sql = "SELECT
            a.name, b.id, b.byuid, b.unread, b.starred FROM fun_users a
            INNER JOIN fun_private b ON a.id = b.byuid
            WHERE b.touid='" . $myid . "' AND b.byuid ='" . $stext . "'
            ORDER BY a.name
            LIMIT $limit_start, $items_per_page";
            } 
        } 

        $items = mysql_query($sql);
        echo mysql_error();
        while ($item = mysql_fetch_array($items)) {
            if ($item[3] == "1") {
                $iml = "&#149;";
            } else {
                if ($item[4] == "1") {
                    $iml = "*";
                } else {
                    $iml = "";
                } 
            } 
            $pminfo = mysql_fetch_array(mysql_query("SELECT text FROM fun_private WHERE id='" . $item[1] . "'"));
            $pmtext = htmlspecialchars($pminfo[0]);
            $pmdet = substr($pmtext, 0, 50);
            $lnk = "<a href=\"inbox.php?readpm&amp;pmid=$item[1]\">" . getnick_uid($item[2]) . "</a>";
            echo "<div class=\"border_top_light\"><div class=\"qn section\" id=\"anchor_fbid_310948112165\"><div class=\"is\">$lnk <br/> <small>$pmdet</small></div></div></div>";
        } 
        if ($page > 1) {
            $ppage = $page-1;
            $rets = "<form action=\"search.php?$action&amp;page=$ppage\" method=\"post\">";
            $rets .= "<input type=\"submit\" value=\"Prev\"/>";

            $rets .= "<input type=\"hidden\" name=\"stext\" value=\"$stext\"/>";
            $rets .= "<input type=\"hidden\" name=\"sin\" value=\"$sin\"/>";
            $rets .= "<input type=\"hidden\" name=\"sor\" value=\"$sor\"/>";
            $rets .= "</form> ";

            echo $rets;
        } 
        if ($page < $num_pages) {
            $npage = $page + 1;
            $rets = "<form action=\"search.php?$action&amp;page=$ppage\" method=\"post\">";
            $rets .= "<input type=\"submit\" value=\"Next\"/>";

            $rets .= "<input type=\"hidden\" name=\"stext\" value=\"$stext\"/>";
            $rets .= "<input type=\"hidden\" name=\"sin\" value=\"$sin\"/>";
            $rets .= "<input type=\"hidden\" name=\"sor\" value=\"$sor\"/>";
            $rets .= "</form> ";

            echo $rets;
        } 
        echo "<br/>$page/$num_pages<br/>";
        if ($num_pages > 2) {
            $rets = "<form action=\"search.php?$action&amp;page=$page\" method=\"post\">";
            $rets .= "Jump to page<input name=\"page\" format=\"*N\" size=\"3\"/>";
            $rets .= "<input type=\"submit\" value=\"GO\"/>";
            $rets .= "<input type=\"hidden\" name=\"stext\" value=\"$stext\"/>";
            $rets .= "<input type=\"hidden\" name=\"sin\" value=\"$sin\"/>";
            $rets .= "<input type=\"hidden\" name=\"sor\" value=\"$sor\"/>";
            $rets .= "</form>";

            echo $rets;
        } 
    } 

    echo dnoonline($sid, $uid);
} else {
    addonline(getuid_sid($sid), "Lost in inbox lol", "");
    echo "Molim vas da se vratite na pocetnu!!!";
    echo dnoonline($sid, $uid);
} 

echo "</body>";
echo "</html>";

?>
