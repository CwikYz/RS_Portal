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
    if (isadmin(getuid_sid($sid))) {
        echo "<div class=\"section_title\"><div class=\"marker\">Aktivni clanovi danas</div></div>";

        $tm24 = time() - (24 * 60 * 60);
        $aut = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_users WHERE lastact>'" . $tm24 . "'"));
        echo mysql_error();
        if ($page == "" || $page <= 0)$page = 1;
        $num_items = $aut[0]; //changable
        $items_per_page = 5;
        $num_pages = ceil($num_items / $items_per_page);
        if ($page > $num_pages)$page = $num_pages;
        $limit_start = ($page-1) * $items_per_page;

        if ($page > 1) {
            $ppage = $page-1;
            echo "<div class='center border_top'><a href=\"index.php?actmem&amp;page=$ppage\"><img src='images/up.png' /></a></div>";
        } 
        $sql = "SELECT name, sex, lastact, id FROM fun_users WHERE lastact>'" . $tm24 . "' ORDER BY lastact DESC LIMIT $limit_start, $items_per_page";

        $items = mysql_query($sql);
        echo mysql_error();
        while ($item = mysql_fetch_array($items)) {
            $avlink = getavatar($item[3]);
            if ($avlink != "") {
                $avatar = "<img src=\"$avlink\" class=\"p\" height=\"25\" width=\"25\" alt=\"avatar\"/>";
            } else {
                $avatar = "<img src=\"images/nopic.jpg\" class=\"p\" height=\"25\" width=\"25\" alt=\"avatar\"/>";
            } 
            $lnk = "<a href=\"index.php?viewuser&amp;who=$item[3]\">$item[0]</a>";
            echo "<div class='border_bottom_light'><table border='0' width='100%' id='download'><tr><td align='left' width='1' height='1'>$avatar</td><td>$lnk<br />";
            echo rating($item[3]);
            echo "</td></tr></table></div>";
        } 

        if ($page < $num_pages) {
            $npage = $page + 1;
            echo "<div class='center border_top'><a href=\"portal.php?main&amp;page=$npage\"><img src='images/down.png' /></a></div>";
        } 
    } else {
        echo "Pristup ovoj strani ima samo administracija...";
    } 
    echo dnoonline($sid, $uid);
} 
else if (isset($_GET['addfotkicu'])) {
    echo vrhonline($sid, $uid);
echo "
	<form action=\"portal.php?addfotkicu&amp;dodaj=add\" method=\"post\">
Adresa: <input type=\"text\" name=\"adr\" value=\"http://\"> <br />
<input type=\"submit\" value=\"Dodaj\">
</form>
";
	$dodaj = $_GET['dodaj'];
	if ($dodaj="add") {
	$adr = $_POST['adr'];
mysql_query("INSERT INTO fotkice SET adr='" . $adr . "'");
echo "Fotkica dodata... :)";
}else{
echo "GRESKA!!!";}
    echo dnoonline($sid, $uid);
    exit();
}
else if (isset($_GET['Statusi'])) {
    echo vrhonline($sid, $uid);
	
        $zipo = "SELECT id FROM fun_clubmembers WHERE uid='" . $uid . "'";
        
    $items = mysql_query($zipo);
    echo mysql_error();
    if (mysql_num_rows($items) > 0) {
        while ($item = mysql_fetch_array($items)) {
	$clid = ($zipo);
	}}
    $who = $_GET["who"]; 
    // ////ALL LISTS SCRIPT <<
    if ($page == "" || $page <= 0)$page = 1;
    if ($who == "") {
        $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_shouts"));
    } else {
        $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_shouts WHERE shouter='" . $who . "'"));
    } 
    $num_items = $noi[0]; //changable
    $items_per_page = 10;
    $num_pages = ceil($num_items / $items_per_page);
    if (($page > $num_pages) && $page != 1)$page = $num_pages;
    $limit_start = ($page-1) * $items_per_page; 
    // changable sql
    if ($who == "") {
        $sql = "SELECT id, shout, shouter, shtime  FROM fun_shouts ORDER BY shtime DESC LIMIT $limit_start, $items_per_page";
    } else {
        $sql = "SELECT id, shout, shouter, shtime  FROM fun_shouts  WHERE shouter='" . $who . "' ORDER BY shtime DESC LIMIT $limit_start, $items_per_page";
    } 
    $items = mysql_query($sql);
    echo mysql_error();
    if (mysql_num_rows($items) > 0) {
        while ($item = mysql_fetch_array($items)) {
            $shnick = getnick_uid($item[2]);
            $sht = parsepm($item[1]); 
            // $razgl = substr($sht,0,300);
            $var1 = date("his", $item[3]);
            $var2 = time ();
            $var21 = date("his", $var2);
            $var3 = $var21 - $var1;
            $var4 = date("s", $var3);
            $remain = time() - $item[3];
            $shdt = gettimemsg($remain);
            $avlink = getavatar($item[2]);
            if ($avlink != "") {
                $avatar = "<a href='index.php?viewuser&amp;who=$item[2]' title='$shnick'><img src='$avlink' alt='$shnick' height='35' width='35' /></a>";
            } else {
                $avatar = "<a href='index.php?viewuser&amp;who=$item[2]' title='$shnick'><img src='images/nopic.jpg' alt='$shnick' height='35' width='35' /></a>";
            } 

            $lnk = "<div class='feed feed_first'>
			<div class='feed_image'>$avatar</div>
			<div class='feed_content'>
			<div> 
			<a href=\"index.php?viewuser&amp;who=$item[2]\">$shnick</a> $sht </div>";
            if (ismod(getuid_sid($sid))) {
                $dlsh = " <a href=\"index.php?main&amp;shid=$item[0]\"><img src='ico/emblem-unreadable.png' /></a>";
            } 

            $who = $item[0];
            $brojkomentara = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_komentari WHERE komowner='" . $who . "'"));
            if ($brojkomentara[0] == 1) {
                $komentari = "<small><a href=\"komentari.php?main&amp;who=$who\"><i>Jedan komentar</i></a></small>";
            } else if ($brojkomentara[0] > 0) {
                $komentara = $brojkomentara[0];
                $komentari = "<small><a href=\"komentari.php?main&amp;who=$who\"><i><b>$komentara</b> Komentar/a</i></a></small>";
			} else {
                $komentari = "<small><a href=\"komentari.php?main&amp;who=$who\"><i>Prokomentarisi</i></a></small>";
            } 
            // ///////
            $brl = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_shout_like WHERE shid='" . $who . "' AND liked='1'"));
            $lajk = mysql_fetch_array(mysql_query("SELECT id FROM fun_shout_like WHERE shid= '" . $who . "' AND uid='".$uid."'"));
		
            if ($lajk[0] == "") {
                $lajkova = "<small><a href=\"komentari.php?main&amp;like=$item[0]&amp;liked=1&amp;who=$who\">Svidja mi se </a></small>";
            } else {
                    $lajkova = "<small><a href=\"komentari.php?main&amp;dislike=$item[0]&amp;liked=0&amp;who=$who\">Ne svidja mi se </a></small>";
                
            } 
			$k_like = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_shout_like WHERE shid='" . $who . "' AND liked='1'"));
			$k_likez = mysql_fetch_array(mysql_query("SELECT uid FROM fun_shout_like WHERE shid='" . $who . "' AND liked='1' ORDER BY reqdt DESC LIMIT 1"));
$k_likes = mysql_fetch_array(mysql_query("SELECT name FROM fun_users where id='".$k_likez[0]."'"));



$za = $k_like[0];
$zaz = $k_like[0] - 1;
if($za==1){
if ($k_likez[0] == $uid) {
$lajkovaoje = "Ti";
$lajkovanjes = "volis ovaj status.";
} else {
$lajkovaoje = $k_likes[0];
$lajkovanjes = "voli ovaj status.";
}
$likeballon = "<div class='feed_like_holder'>
<div class='comment_mini'>
<img src='/ico/vote_yes.png' alt='' class='v_middle'>
<span class='user_profile_link_span'><a href='/index.php?viewuser&amp;who=$k_likez[0]'>$lajkovaoje</a></span> $lajkovanjes</div>";
}
else if($za>=2){
if ($k_likez[0] == $uid) {
$lajkovaoje = "Ti";
} else {
$lajkovaoje = $k_likes[0];
}
if ($zaz>=2) {
$koliko = "a";
$voli = "vole";
} else { $koliko = ""; $voli = "voli";}
$likeballon = "<div class='feed_like_holder'>
<div class='comment_mini'>
<img src='/ico/vote_yes.png' alt='' class='v_middle'>
<span class='user_profile_link_span'><a href='/index.php?viewuser&amp;who=$k_likez[0]'>$lajkovaoje</a><span> i jos <a href='/lajkovi.php?main&amp;who=$who'>$zaz  prijatelj$koliko</a> $voli ovaj status.</div>";
}else { $likeballon =""; }
            // ///////
            echo "$lnk
			<div class='feed_content_info'>
			<span class='feed_time_stamp'>$shdt</span><span> &#183; $komentari</span><span> &#183; $lajkova</span> $dlsh $likeballon
			</div>
			</div>
			</div>
			</div>
			<div class='clear border_bottom'></div>";
        } 
    } 
    echo "";

    if ($page < $num_pages) {
        $npage = $page + 1;
        echo "<a href='portal.php?Statusi&amp;page=$npage' class='view_more'>Pogledaj jos</a>";
    } 
    echo dnoonline($sid, $uid);
    exit();
}
else if (isset($_GET['online'])) {
    echo vrhonline($sid, $uid);
    echo "<div class=\"section border_top\">";
    $onbuds = getonbuds($uid);
    echo "<div class=\"section_title\"><div class=\"marker\">Ko je u kojoj sobi?</div></div>"; 
    // Chat
    $rooms = mysql_query("SELECT id, name, perms, mage, chposts FROM fun_rooms WHERE static='1' AND clubid='0'");
    while ($room = mysql_fetch_array($rooms)) {
        $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_chonline WHERE rid='" . $room[0] . "'"));
        echo "<div class=\"section border_top\"><div class=\"section_title\"><div class=\"marker\"><a href=\"chat.php?sid=$sid&amp;rid=$room[0]\">$room[1]($noi[0])</a></div></div></div>";
        echo "<small>";
        $clan = mysql_query("SELECT uid FROM fun_chonline WHERE rid='" . $room[0] . "'  GROUP BY 1");
        while ($cl = mysql_fetch_array($clan)) {
            $chatter = getnick_uid($cl[0]);
            $avlink = getavatar($cl[0]);
            if ($avlink != "") {
                $avatar = "<img src=\"$avlink\" class=\"p\" height=\"25\" width=\"25\" alt=\"avatar\"/>";
            } else {
                $avatar = "<img src=\"images/nopic.jpg\" class=\"p\" height=\"25\" width=\"25\" alt=\"avatar\"/>";
            } 
            echo "<div class='titlz'>$avatar <br /><a href='index.php?viewuser&amp;who=$cl[0]'>$chatter</a></div>";
        } 
        echo "</small>";
    } 
    // echo "<div class=\"section border_top\"><div class=\"section_title\"><div class=\"marker\">Privatno caskanje</div></div></div>";
    echo dnoonline($sid, $uid);
    exit();
} else if (isset($_GET['links'])) {
    addonline(getuid_sid($sid), "<b>Dodajem link</b>", "");
    echo vrhonline($sid, $uid);

    echo "<div class=\"section_title\"><div class=\"marker\">Prijatelji portala</div></div>";
    echo "<div class='titl'>Ako zelite da se vas link nadje ovde... Kontaktirajte vlasnika CwikYz-a... da se dogovorimo... :)</div>";
    // ////ALL LISTS SCRIPT <<
    if ($page == "" || $page <= 0)$page = 1;
    $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM lib3rtymrc_links"));
    $num_items = $noi[0]; //changable
    $items_per_page = 5;
    $num_pages = ceil($num_items / $items_per_page);
    if (($page > $num_pages) && $page != 1)$page = $num_pages;
    $limit_start = ($page-1) * $items_per_page;

    if ($page > 1) {
        $ppage = $page-1;
        echo "<div class='center border_top'><a href=\"index.php?links&amp;page=$ppage&amp;wiev=$wiev\"><img src='images/up.png' /></a></div>";
    } 

    $query = mysql_query("SELECT url, title,uid,description,timesent FROM lib3rtymrc_links ORDER BY id LIMIT $limit_start, $items_per_page");
    while ($links = mysql_fetch_array($query)) {
        if (isadmin(getuid_sid($sid))) {
            $del = "<a href=\"portal.php?linkdel&amp;link=$links[0]\">[ukloni]</a>";
        } 
        $link = "<table border='0' width='100%' id='download'><tr><td align='center' style='background-color:#999999' width='1' height='1'><img src='img/img_39.png' /></td><td style='background-color:#eeeeee'><a href=\"$links[0]\">$links[1]</a> $del<br /><small>$links[3]</small></td></tr></table>";

        echo "<div class='border_bottom_light'>$link</div>";
    } 

    if ($page < $num_pages) {
        $npage = $page + 1;
        echo "<div class='center border_top'><a href=\"portal.php?links&amp;page=$npage&amp;wiev=$wiev\"><img src='images/up.png' /></a></div>";
    } 

    if (isadmin(getuid_sid($sid))) {
        echo "<div class='center'><a href=\"portal.php?addlink\">Dodaj Link</a></div>";
    } 
    echo dnoonline($sid, $uid);
} else if (isset($_GET['addlinks'])) {
    $url = $_POST["url"];
    $title = $_POST["title"];
    $crdate = time();

    $description = $_POST["description"];
    echo vrhonline($sid, $uid);

    $uid = getuid_sid($sid);
    if (isadmin(getuid_sid($sid))) {
        echo $site;
        $res = mysql_query("INSERT INTO lib3rtymrc_links SET url='" . $url . "', title='" . $title . "', timesent='" . $crdate . "',uid=$uid,description='" . $description . "'");

        if ($res) {
            echo mysql_error();
            echo "Link $title  je uspesno dodat";
        } else {
            echo "Greska u dodavanju Linka";
        } 
    } else {
        echo "OVOM DELU SAJTA IMA PRISTUP SAMO CWIKYZ!!!";
    } 
    echo dnoonline($sid, $uid);
} else if (isset($_GET['linkdel'])) {
    $link = $_GET["link"];
    echo vrhonline($sid, $uid);

    $uid = getuid_sid($sid);
    if (isadmin(getuid_sid($sid))) {
        echo $site;
        $res = mysql_query("DELETE FROM lib3rtymrc_links WHERE url='" . $link . "'");

        if ($res) {
            echo mysql_error();
            echo "Link $link je uspesno uklonjen";
        } else {
            echo "Greska u brisanju Linka";
        } 
    } else {
        echo "OVOM DELU SAJTA IMA PRISTUP SAMO CWIKYZ!!!";
    } 
    echo dnoonline($sid, $uid);
} 
// /////////////// Add link
else if (isset($_GET['addlink'])) {
    addonline(getuid_sid($sid), "<b> Dodajem link HTML</b>", "index.php?$action");
    echo vrhonline($sid, $uid);

    echo "<div class='titl'>Unesi url adresu linka</div>";
    echo "<form action=\"portal.php?addlinks\" method=\"post\">";
    echo "<div class='sett_line'>Adresa sajta";
    echo "<input name=\"url\" value=\"http://\"/></div>";

    echo "<div class='sett_line'>Naziv sajta";
    echo "<input name=\"title\"/></div>";
    echo "<div class='sett_line'>Opis sajta";
    echo "<input name=\"description\"/></div>";

    echo "<br/><input type=\"Submit\" Name=\"Submit\" class='button' Value=\"Dodaj Link\"></form>";
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
