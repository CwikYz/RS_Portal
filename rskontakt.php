<?php
include("core.php");
include("config.php");

?>
<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns=\"http://www.w3.org/1999/xhtml\">

<?php
$bcon = connectdb();
if (!$bcon) {

    ?>
	Greska<br />
	Sajt nije uspeo da se poveze sa bazom....<br />
	Molim vas da osvezite staranu...
	<?php

} 
$action = $_GET['action'];
$id = $_GET["id"];
vrh($sid);
if (isset($_GET['zahvalnica'])) {

    ?>
Kreirao: Darko Cvijovic <br />
Kontakt: cwikyz@live.com<br />
Sajt: <a href="http://secafe.freehostia.com">http://secafe.freehostia.com</a><br />
FB: <a href='http://facebook.com/cwikyz'>http://facebook.com/cwikyz</a><br />
Sajt nastao: 04.Jun.2010<br />
<br />
Ako vas zanima nesto oko skripte (nekog dela)<br />
kako napraviti, ili stilizovatai...<br />
Kako raditi sa lavom i njenim funkcijama...<br />
Kako raditi sa php-om i mysql-om...<br />
Sve to mozete da me pitate na chatu-forumu...<br />
Na facebooku ili na email koji je naveden gore...<br />
Potrdi cu se da vam odgovorim u najkracem roku...<br />
Naravno ako mogu u toj oblasti da vam pomognem...<br />
Molim vas da mi ne trazite skriptu...<br />
Jer je necete dobiti...<br />
Jer mi je trebalo vise od godinu dana da je napravim...<br />
Ako vam je to smesno koliko mi je trebalo...<br />
Onda uzmite skriptu lavalair_xhtml.zip i pocnite...<br />
Napravite nesto sa puno opcija... kao ja...<br />
Bez tudje pomoci... ustvari moze 1 dacu vam ja...<br />
kako da vidite u inboxu delic poruke, pre nego je otvorite...<br />
Zato sto sam ja tu opciju dobio od meni jedne veoma drage osobe...<br />
Od srecnice sa HAPPY FRUMA...<br />
---------------=============-----------------<br />
Hocu da kazem svim onim Retardima... Necu grubo da se izrazavam...<br />
Koji traze skripte, pomoc, sta god... I neko im pomogne...<br />
A oni na kraju ni hvala ne umeju da kazu...<br />
Nego ispljuju do kraja... I onda se hvale kako su sve sami uradili...<br />
Niko nije rodjen naucen da sve moze da uradi bez icije pomoci...<br />
Ali budi fer i reci makar HVALA...<br />
Da, da... Sace pola vas da kaze rekao sam hvala svima...<br />
Ali cekaj... Sta je sa onima koji su ti pomogli...<br />
Ali ti nisu dali svoju skriptu, ili neki prost kod iz nje...<br />
A ti ih ispljuvao ko dzubre u prolazu...<br />
Da ima vas... Znam za to... Necu nikome da sudim...<br />
Ali samo kazem... Kad nesto uzmete od nekoga...<br />
Mogli bi da u tom fajlu, strani...<br />
Stavite makar hvala...<br />
Ili kao ja napravite zasebnu stranu...<br />
Na kojoj ce stojati Zahvalnica svima onima koji su mi pomogle...<br />
Da rs kontakt postane danas ono sto jeste...<br />
Sajt sa dobrim drustvom, ekipa je mala... ali se nadam da ce nas biti vise...<br />
SVI VI KOJI STE MI POMOGLI A I DAN DANAS MI POMAZETE...<br />
=========----------HVALA VAM----------=========<br />
<br />
Linkovi sajtova sa kojih sam dobio pomoc:<br />
<?php include ("portal/linkovi.php");
    ?>
<br />
<?php

} else
if (isset($_GET['status'])) {
    $page = $_GET["page"];
    $who = $_GET["who"]; 
	 $shid = $_GET["shid"];
    $todo = $_GET["todo"];
    $ko = $_GET["ko"];
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
                $avatar = "<a href='#' title='$shnick'><img src='$avlink' alt='$shnick' height='35' width='35' /></a>";
            } else {
                $avatar = "<a href='#' title='$shnick'><img src='images/nopic.jpg' alt='$shnick' height='35' width='35' /></a>";
            } 

            $lnk = "<div class='feed feed_first'>
			<div class='feed_image'>$avatar</div>
			<div class='feed_content'>
			<div> 
			<a href=\"#\">$shnick</a> $sht </div>";
            if (ismod(getuid_sid($sid))) {
                $dlsh = " <a href=\"#\"><img src='ico/emblem-unreadable.png' /></a>";
            } 

            $who = $item[0];
            $brojkomentara = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_komentari WHERE komowner='" . $who . "'"));
            if ($brojkomentara[0] == 1) {
                $komentari = "<small><a href=\"#\"><i>Jedan komentar</i></a></small>";
            } else if ($brojkomentara[0] > 0) {
                $komentara = $brojkomentara[0];
                $komentari = "<small><a href=\"#\"><i><b>$komentara</b> Komentar/a</i></a></small>";
			} else {
                $komentari = "<small><a href=\"#\"><i>Prokomentarisi</i></a></small>";
            } 
            // ///////
            $brl = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_shout_like WHERE shid='" . $who . "' AND liked='1'"));
            $lajk = mysql_fetch_array(mysql_query("SELECT id FROM fun_shout_like WHERE shid= '" . $who . "' AND uid='".$uid."'"));
		
            if ($lajk[0] == "") {
                $lajkova = "<small><a href=\"#\">Svidja mi se </a></small>";
            } else {
                    $lajkova = "<small><a href=\"#\">Ne svidja mi se </a></small>";
                
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
<span class='user_profile_link_span'><a href='#'>$lajkovaoje</a></span> $lajkovanjes</div>";
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
<span class='user_profile_link_span'><a href='#'>$lajkovaoje</a><span> i jos <a href='#'>$zaz  prijatelj$koliko</a> $voli ovaj status.</div>";
}else { $likeballon =""; }
            // ///////
            echo "$lnk
			<div class='feed_content_info'>
			<span class='feed_time_stamp'>$shdt</span><span> &#183; $komentari</span><span> &#183; $lajkova</span> $dlsh $likeballon
			</div>
			</div>
			</div>
			<div class='clear border_bottom'></div>
			</div>";
        } 
    } 
    echo "";

    if ($page < $num_pages) {
        $npage = $page + 1;
        echo "<a href='rskontakt.php?status&amp;page=$npage' class='view_more'>Citaj dalje</a>";
    } 
} else
if (isset($_GET['chat'])) {
    echo "<div class=\"section border_top\">";
    $chs = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_chonline"));
    echo "<div class=\"section_title\"><div class=\"marker\">Na chatu ($chs[0])</div></div>";
    echo "<div class=\"adv\"><small>";
    $rooms = mysql_query("SELECT id, name, perms, mage, chposts FROM fun_rooms WHERE static='1' AND clubid='0'");
    while ($room = mysql_fetch_array($rooms)) {
        $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_chonline WHERE rid='" . $room[0] . "'"));
        echo "<a href=\"rskontakt.php?chatsoba&amp;rid=$room[0]\">$room[1]($noi[0])</a><br/>";
    } 
    echo "</small></div></div>";
} else if (isset($_GET['chatsoba'])) {
    // start of main card
    $rid = $_GET["rid"];

    $message = $_POST["message"];
    $who = $_POST["who"];
    $rinfo = mysql_fetch_array(mysql_query("SELECT censord, freaky FROM fun_rooms WHERE id='" . $rid . "'"));

    $chats = mysql_query("SELECT chatter, who, timesent, msgtext, exposed FROM fun_chat WHERE rid='" . $rid . "' ORDER BY timesent DESC, id DESC");
    $counter = 0;

    while ($chat = mysql_fetch_array($chats)) {
        $var1 = date("his", $chat[2]);
        $var2 = time ();
        $var21 = date("his", $var2);
        $var3 = $var21 - $var1;
        $var4 = date("s", $var3);
        $remain = time() - $chat[2];
        $ds = gettimemsg($remain);
        $avlink = getavatar($chat[0]);
        if ($avlink != "") {
            echo "<div class=\"comment comm_adv\"><table border='0' width='100%' id='download'><tr><td align='left' width='1' height='1'><img src=\"$avlink\" height=\"25\" width=\"25\" alt=\"avatar\"/>";
        } else {
            echo "<div class=\"comment comm_adv\"><table border='0' width='100%' id='download'><tr><td align='left' width='1' height='1'><img src=\"../images/nopic.jpg\" height=\"25\" width=\"25\" alt=\"avatar\"/>";
        } 
        $korisnik = getnick_uid($chat[0]);
        echo "</td><td>$korisnik  $ds<br /> $chat[3] </td></tr></table>";
        echo $tosay . "</div>";
    } 

    $counter++;

    echo "<div class=\"border_top_light\">";
    echo "<div class=\"titlz\"><a href=\"rskontakt.php?chatsoba&amp;time=";
    echo date('dmHis');
    echo "&amp;rid=$rid";
    echo "\">refresh</a></div>";
    $chatters = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_chonline where rid='" . $rid . "'"));
    echo "<div class=\"titlz\"><a href=\"rskontakt.php?inside&amp;rid=$rid\">U sobi($chatters[0])</a></div>";
} 
// //////////////////////////////////////////
// ////////////////////////////inside//////////
else if (isset($_GET['inside'])) {
    $rid = $_GET["rid"];
    $inside = mysql_query("SELECT DISTINCT * FROM fun_chonline WHERE rid='" . $rid . "' and uid IS NOT NULL");

    while ($ins = mysql_fetch_array($inside)) {
        $avlink = getavatar($ins[1]);
        if ($avlink != "") {
            $avatar = "<div class='titl'><table border='0' width='100%' id='download'><tr><td align='left' width='1' height='1'><img src=\"$avlink\" height=\"25\" width=\"25\" alt=\"avatar\"/>";
        } else {
            $avatar = "<div class='titl'><table border='0' width='100%' id='download'><tr><td align='left' width='1' height='1'><img src=\"../images/nopic.jpg\" height=\"25\" width=\"25\" alt=\"avatar\"/></td><td>";
        } 
        $unick = getnick_uid($ins[1]);
        $userl = "$avatar $unick";
        $rejting = rating($ins[1]);
        echo "$userl $rejting";
        echo "</td></tr></table></div>";
    } 
    echo " <br/>";
    echo "<div class=\"titlz\"><a href=\"rskontakt.php?chatsoba&amp;rid=$rid\">Vrati se u sobu</a></div>";
} else
if (isset($_GET['forum'])) {
    echo "<div class=\"titl\"><b>Random tema:</b></div>";
    $lpt = mysql_fetch_array(mysql_query("SELECT id, name FROM fun_topics ORDER BY RAND() LIMIT 1"));
    $tlnm = htmlspecialchars($lpt[1]);
    $tpclnk = "<a
href=\"rskontakt.php?viewtpc&amp;tid=$lpt[0]&amp;go=last\">$tlnm</a>";
    echo "<div class=\"comment\"><div class=\"comm_adv\">$tpclnk</div></div>"; 
    // ////////////////////////////////////////////////////////////////////////
    // ////
    $lpt = mysql_fetch_array(mysql_query("SELECT id, name FROM fun_topics ORDER BY lastpost DESC LIMIT 0,1"));
    $nops = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_posts"));
    if ($nops[0] == 0) {
        $pinfo = mysql_fetch_array(mysql_query("SELECT authorid FROM fun_topics"));
        $tluid = $pinfo[0];
    } else {
        $pinfo = mysql_fetch_array(mysql_query("SELECT  uid  FROM fun_posts ORDER BY dtpost DESC LIMIT 0, 1"));

        $tluid = $pinfo[0];
    } 
    $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_users WHERE perm>'0' AND lastact>'" . $timeon . "'"));
    $tlnm = htmlspecialchars($lpt[1]);
    $tlnick = getnick_uid($tluid);
    $tpclnk = "<a href=\"rskontakt.php?viewtpc&amp;tid=$lpt[0]&amp;go=last\">$tlnm</a>";

    echo "<div class=\"titl\">Zadnjih 5 postova u temama</div>";
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
    $tpclnk = "<a href=\"rskontakt.php?viewtpc&amp;tid=$lpt[0]&amp;go=last\">$tlnm</a>";
    echo "<div class=\"comment\"><div class=\"comm_adv\"><small>1: $tpclnk</small></div></div>";

    $lpt = mysql_fetch_array(mysql_query("SELECT id, name FROM fun_topics ORDER BY lastpost DESC LIMIT 1,2"));
    $nops = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_posts"));
    if ($nops[0] == 0) {
        $pinfo = mysql_fetch_array(mysql_query("SELECT authorid FROM fun_topics"));
        $tluid = $pinfo[0];
    } else {
        $pinfo = mysql_fetch_array(mysql_query("SELECT uid FROM fun_posts ORDER BY dtpost DESC LIMIT 1,2"));

        $tluid = $pinfo[0];
    } 
    $tlnm = htmlspecialchars($lpt[1]);
    $tlnick = getnick_uid($tluid);
    $tpclnk = "<a href=\"rskontakt.php?viewtpc&amp;tid=$lpt[0]&amp;go=last\">$tlnm</a>";
    echo "<div class=\"comment\"><div class=\"comm_adv\"><small>2: $tpclnk</small></div></div>";

    $lpt = mysql_fetch_array(mysql_query("SELECT id, name FROM fun_topics ORDER BY lastpost DESC LIMIT 2,3"));
    $nops = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_posts"));
    if ($nops[0] == 0) {
        $pinfo = mysql_fetch_array(mysql_query("SELECT authorid FROM fun_topics"));
        $tluid = $pinfo[0];
    } else {
        $pinfo = mysql_fetch_array(mysql_query("SELECT uid FROM fun_posts ORDER BY dtpost DESC LIMIT 2,3"));

        $tluid = $pinfo[0];
    } 
    $tlnm = htmlspecialchars($lpt[1]);
    $tlnick = getnick_uid($tluid);
    $tpclnk = "<a href=\"rskontakt.php?viewtpc&amp;tid=$lpt[0]&amp;go=last\">$tlnm</a>";
    echo "<div class=\"comment\"><div class=\"comm_adv\"><small>3: $tpclnk</small></div></div>";

    $lpt = mysql_fetch_array(mysql_query("SELECT id, name FROM fun_topics ORDER BY lastpost DESC LIMIT 3,4"));
    $nops = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_posts"));
    if ($nops[0] == 0) {
        $pinfo = mysql_fetch_array(mysql_query("SELECT authorid FROM fun_topics"));
        $tluid = $pinfo[0];
    } else {
        $pinfo = mysql_fetch_array(mysql_query("SELECT uid FROM fun_posts ORDER BY dtpost DESC LIMIT 3,4"));

        $tluid = $pinfo[0];
    } 
    $tlnm = htmlspecialchars($lpt[1]);
    $tlnick = getnick_uid($tluid);
    $tpclnk = "<a href=\"rskontakt.php?viewtpc&amp;tid=$lpt[0]&amp;go=last\">$tlnm</a>";
    echo "<div class=\"comment\"><div class=\"comm_adv\"><small>4: $tpclnk</small></div></div>";

    $lpt = mysql_fetch_array(mysql_query("SELECT id, name FROM fun_topics ORDER BY lastpost DESC LIMIT 4,5"));
    $nops = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_posts"));
    if ($nops[0] == 0) {
        $pinfo = mysql_fetch_array(mysql_query("SELECT authorid FROM fun_topics"));
        $tluid = $pinfo[0];
    } else {
        $pinfo = mysql_fetch_array(mysql_query("SELECT uid FROM fun_posts ORDER BY dtpost DESC LIMIT 4,5"));

        $tluid = $pinfo[0];
    } 
    $tlnm = htmlspecialchars($lpt[1]);
    $tlnick = getnick_uid($tluid);
    $tpclnk = "<a href=\"rskontakt.php?viewtpc&amp;tid=$lpt[0]&amp;go=last\">$tlnm</a>";
    echo "<div class=\"comment\"><div class=\"comm_adv\"><small>5: $tpclnk</small></div></div>";
    echo "<small><div class=\"titlz\"><a href=\"rskontakt.php?teme\">Najnovije teme</a></div>";
    echo "<div class=\"titlz\"><a href=\"rskontakt.php?postovi\">Najnoviji postovi</a></div></small>"; 
    // /////////////////////////////////////////////////////////////////////////////
    $fcats = mysql_query("SELECT id, name FROM fun_fcats ORDER BY position, id");
    while ($fcat = mysql_fetch_array($fcats)) {
        $topics = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_topics a INNER JOIN fun_forums b ON a.fid = b.id WHERE b.cid='" . $fcat[0] . "'"));
        $posts = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_posts a INNER JOIN fun_topics b ON a.tid = b.id INNER JOIN fun_forums c ON b.fid = c.id WHERE c.cid='" . $fcat[0] . "'"));
        $catlink = "<div class=\"sett_line\"><a href=\"rskontakt.php?viewcat&amp;cid=$fcat[0]\">$fcat[1]</a><br /> Otvorenih tema: <div class=\"titl\">$topics[0]</div> <br /> Napisanih poruka: <div class=\"titl\">$posts[0]</div></div>";
        $notp = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_topics WHERE fid='" . $forum[0] . "'")); 
        // echo "<div class=\"border_top_light\"><div class=\"qn section\" id=\"anchor_fbid_310948112165\"><div class=\"is\">";
        echo "$catlink"; 
        // echo "</div></div></div>";
        $forums = mysql_query("SELECT id, name FROM fun_forums WHERE cid='" . $fcat[0] . "' AND clubid='0' ORDER BY position, id, name");
        if (getfview() == 0) {
            echo "<br/><small>";
            while ($forum = mysql_fetch_array($forums)) {
                if (canaccess(getuid_sid($sid), $forum[0])) {
                    echo "$iml <a href=\"rskontakt.php?viewfrm&amp;fid=$forum[0]\">$forum[1]</a> ";
                } 
            } 
            echo "</small>";
        } else if (getfview() == 5) {
            echo "<br/><small>Forumi:</small><br/><select name=\"fid\">";
            while ($forum = mysql_fetch_array($forums)) {
                if (canaccess(getuid_sid($sid), $forum[0])) {
                    $notp = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_topics WHERE fid='" . $forum[0] . "'"));
                    echo "<option value=\"$forum[0]\">$forum[1]($notp[0])</option>";
                } 
            } 
            echo "</select>";
        } 
    } 
} else if (isset($_GET['teme'])) {
    $sql = "SELECT id, name, crdate FROM fun_topics ORDER BY id DESC LIMIT 0 , 20";

    $items = mysql_query($sql);
    echo mysql_error();
    if (mysql_num_rows($items) > 0) {
        while ($item = mysql_fetch_array($items)) {
            if (canaccess(getuid_sid($sid), getfid_tid($item[0]))) {
                echo "<div class=\"sett_line\"><a href=\"rskontakt.php?viewtpc&amp;tid=$item[0]&amp;go=last\">" . htmlspecialchars($item[1]) . "</a> <small>" . date("d m y-H:i:s", $item[2]) . "</small></div>";
            } 
        } 
    } 
} else if (isset($_GET['postovi'])) {
    
    $sql = "SELECT a.name, b.uid, b.tid
       FROM fun_topics a
       INNER JOIN fun_posts b ON a.id = b.tid
       ORDER BY b.id DESC
       LIMIT 0 , 20";
    $items = mysql_query($sql);
    echo mysql_error();
    while ($item = mysql_fetch_array($items)) {
        $a = htmlspecialchars($item[0]);
        $b = getnick_uid($item[1]);
        $c = "<a href=\"rskontakt.php?viewtpc&amp;tid=$item[2]&amp;go=last\">$a</a>";
        $d = "$b";
        echo "<div class=\"sett_line\">$c Od $d</div>";
    } 
    
} else if (isset($_GET['viewcat'])) {
    $cid = $_GET["cid"];
    $cinfo = mysql_fetch_array(mysql_query("SELECT name from fun_fcats WHERE id='" . $cid . "'"));

    $forums = mysql_query("SELECT id, name FROM fun_forums WHERE cid='" . $cid . "' AND clubid='0' ORDER BY position, id, name");

    while ($forum = mysql_fetch_array($forums)) {
        if (canaccess(getuid_sid($sid), $forum[0])) {
            $notp = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_topics WHERE fid='" . $forum[0] . "'"));
            $nops = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_posts a INNER JOIN fun_topics b ON a.tid = b.id WHERE b.fid='" . $forum[0] . "'"));
            echo "<div class=\"sett_line\"><a href=\"rskontakt.php?viewfrm&amp;fid=$forum[0]\">$forum[1]($notp[0]/$nops[0])</a><br />";
            $lpt = mysql_fetch_array(mysql_query("SELECT id, name FROM fun_topics WHERE fid='" . $forum[0] . "' ORDER BY lastpost DESC LIMIT 0,1"));
            $nops = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_posts WHERE tid='" . $lpt[0] . "'"));
            if ($nops[0] == 0) {
                $pinfo = mysql_fetch_array(mysql_query("SELECT authorid FROM fun_topics WHERE id='" . $lpt[0] . "'"));
                $tluid = $pinfo[0];
            } else {
                $pinfo = mysql_fetch_array(mysql_query("SELECT  uid  FROM fun_posts WHERE tid='" . $lpt[0] . "' ORDER BY dtpost DESC LIMIT 0, 1"));

                $tluid = $pinfo[0];
            } 
            $tlnm = htmlspecialchars($lpt[1]);
            $tlnick = getnick_uid($tluid);
            $tpclnk = "<a href=\"rskontakt.php?viewtpc&amp;tid=$lpt[0]&amp;go=last\">$tlnm</a>";
            $vulnk = "$tlnick";
            echo "Poslednja poruka u: $tpclnk <br /> Koju je napisao/la: $vulnk</div>";
        } 
    } 
} else if (isset($_GET['viewfrm'])) {
    $fid = $_GET["fid"];
    $view = $_GET["view"];
    $finfo = mysql_fetch_array(mysql_query("SELECT name from fun_forums WHERE id='" . $fid . "'"));
    $fnm = htmlspecialchars($finfo[0]);

    $norf = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_rss WHERE fid='" . $fid . "'"));
    if ($norf[0] > 0) {
        echo "<a href=\"rwrss.php?showfrss&amp;fid=$fid\"><img src=\"img/img_30.png\" alt=\"rss\"/>$finfo[0] RSS</a><br/>";
    } 

    if ($page > 1) {
        $ppage = $page-1;
        echo "<div class=\"section border_top\">";
        echo "<div class=\"center\"><a href=\"rskontakt.php?viewfrm&amp;page=$ppage&amp;fid=$fid&amp;view=$view\"><img src=\"images/up.png\"></a></div>";
        echo "</div>";
    } 

    if ($page == "" || $page <= 0)$page = 1;
    if ($page == 1) {
        // /////////pinned topics
        $topics = mysql_query("SELECT id, name, closed, views, pollid FROM fun_topics WHERE fid='" . $fid . "' AND pinned='1' ORDER BY lastpost DESC, name, id LIMIT 0,5");
        while ($topic = mysql_fetch_array($topics)) {
            $iml = "<img src=\"img/img_44.png\">";
            $iml = "<img src=\"img/img_33.png\">";
            $atxt = "";
            if ($topic[2] == '1') {
                // closed
                $atxt = "<img src=\"img/img_41.png\">";
            } 
            if ($topic[4] > 0) {
                $pltx = "<img src=\"img/img_32.png\">";
            } else {
                $pltx = "<img src=\"img/img_31.png\">";
            } 
            $tnm = htmlspecialchars($topic[1]);
            $nop = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_posts WHERE tid='" . $topic[0] . "'"));
            echo "<div class=\"comment comm_adv\"><a href=\"rskontakt.php?viewtpc&amp;tid=$topic[0]\">$iml$pltx$tnm($nop[0])$atxt</a></div>";
        } 
    } 
    $uid = getuid_sid($sid);
    if ($view == "new") {
        $ulv = mysql_fetch_array(mysql_query("SELECT lastvst FROM fun_users WHERE id='" . $uid . "'"));
        $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_topics WHERE fid='" . $fid . "' AND pinned='0' AND lastpost >='" . $ulv[0] . "'"));
    } else if ($view == "myps") {
        $noi = mysql_fetch_array(mysql_query("SELECT COUNT(DISTINCT a.id) FROM fun_topics a INNER JOIN fun_posts b ON a.id = b.tid WHERE a.fid='" . $fid . "' AND a.pinned='0' AND b.uid='" . $uid . "'"));
    } else {
        $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_topics WHERE fid='" . $fid . "' AND pinned='0'"));
    } 
    $num_items = $noi[0]; //changable
    $items_per_page = 10;
    $num_pages = ceil($num_items / $items_per_page);
    if ($page > $num_pages)$page = $num_pages;
    $limit_start = ($page-1) * $items_per_page;
    if ($limit_start < 0)$limit_start = 0;
    if ($view == "new") {
        $ulv = mysql_fetch_array(mysql_query("SELECT lastvst FROM fun_users WHERE id='" . $uid . "'"));
        $topics = mysql_query("SELECT id, name, closed, views, moved, pollid FROM fun_topics WHERE fid='" . $fid . "' AND pinned='0' AND lastpost >='" . $ulv[0] . "' ORDER BY lastpost DESC, name, id LIMIT $limit_start, $items_per_page");
    } else if ($view == "myps") {
        $topics = mysql_query("SELECT a.id, a.name, a.closed, a.views, a.moved, a.pollid FROM fun_topics a INNER JOIN fun_posts b ON a.id = b.tid WHERE a.fid='" . $fid . "' AND a.pinned='0' AND b.uid='" . $uid . "' GROUP BY a.id ORDER BY a.lastpost DESC, a.name, a.id  LIMIT $limit_start, $items_per_page");
    } else {
        $topics = mysql_query("SELECT id, name, closed, views, moved, pollid FROM fun_topics WHERE fid='" . $fid . "' AND pinned='0' ORDER BY lastpost DESC, name, id LIMIT $limit_start, $items_per_page");
    } while ($topic = mysql_fetch_array($topics)) {
        $nop = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_posts WHERE tid='" . $topic[0] . "'"));
        $iml = "<img src=\"img/img_31.png\">";
        if ($nop[0] > 24) {
            $iml = "<img src=\"img/img_32.png\">";
        } 
        if ($topic[4] == '1') {
            $iml = "<img src=\"img/img_50.png\">";
        } 
        if ($topic[2] == '1') {
            $iml = "<img src=\"img/img_41.png\">";
        } 
        if ($topic[5] > 0) {
            $iml = "<img src=\"img/img_9.png\">";
        } 
        $atxt = "";
        if ($topic[2] == '1') {
            // closed
            $atxt = "<img src=\"img/img_41.png\">";
        } 
        $tnm = htmlspecialchars($topic[1]);
        echo "<div class=\"sett_line\"><a href=\"rskontakt.php?viewtpc&amp;tid=$topic[0]\">$iml$tnm($nop[0])$atxt</a></div>";
    } 

    if ($page < $num_pages) {
        echo "<div class=\"section border_top\">";
        $npage = $page + 1;
        echo "<div class=\"center\"><a href=\"rskontakt.php?viewfrm&amp;page=$npage&amp;fid=$fid&amp;view=$view\"><img src=\"images/down.png\"></a></div>";
        echo "</div>";
    } 

    echo "<div class=\"section border_top\">";
    $cid = mysql_fetch_array(mysql_query("SELECT cid FROM fun_forums WHERE id='" . $fid . "'"));
    if ($cid[0] > 0) {
        $cinfo = mysql_fetch_array(mysql_query("SELECT name FROM fun_fcats WHERE id='" . $cid[0] . "'"));
        $cname = htmlspecialchars($cinfo[0]);
        echo "<div class=\"comment comm_adv\"><a href=\"rskontakt.php?viewcat&amp;cid=$cid[0]\">";
        echo "<img src=\"img/img_42.png\">$cname</a></div>";
    } 
} else if (isset($_GET['viewtpc'])) {
    echo "Teme mogu da citaju samo clanovi.... :) zato pravac registracija...";
} else
if (isset($_GET['download'])) {
    $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_download WHERE type='1'"));
    echo "<div class='comment comm_adv'><a href=\"rskontakt.php?vaultmusic\"><img src=\"download/muzika.png\" alt=\"*\"/> Muzika($noi[0])</a></div>";
    $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_download WHERE type='4'"));
    echo "<div class='comment comm_adv'><a href=\"rskontakt.php?vaultvideos\"><img src=\"download/video.png\" alt=\"*\"/> Video snimci($noi[0])</a></div>";
    $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_download WHERE type='2'"));
    echo "<div class='comment comm_adv'><a href=\"rskontakt.php?vaultpics\"><img src=\"download/slike.png\" alt=\"*\"/> Fotografije($noi[0])</a></div>";
    $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_download WHERE type='3'"));
    echo "<div class='comment comm_adv'><a href=\"rskontakt.php?vaultgames\"><img src=\"download/igre.png\" alt=\"*\"/> Aplikacije i igrice($noi[0])</a></div>";
    $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_download WHERE type='0'"));
    echo "<div class='comment comm_adv'><a href=\"rskontakt.php?vaultother\"><img src=\"download/ostalo.png\" alt=\"*\"/> Ostalo($noi[0])</a></div>";
} else if (isset($_GET['vaultmusic'])) { // /////////////////Muzika
        $who = $_GET["who"];

    if ($page == "" || $page <= 0)$page = 1;
    if ($who != "") {
        $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_download WHERE uid='" . $who . "' AND type='1'"));
    } else {
        $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_download WHERE type='1'"));
    } 
    $num_items = $noi[0]; //changable
    $items_per_page = 5;
    $num_pages = ceil($num_items / $items_per_page);
    if (($page > $num_pages) && $page != 1)$page = $num_pages;
    $limit_start = ($page-1) * $items_per_page;

    if ($who != "") {
        $sql = "SELECT id, title, itemurl, uid, downloads, pudt FROM fun_download WHERE uid='" . $who . "' AND type='1' ORDER BY pudt DESC LIMIT $limit_start, $items_per_page";
    } else {
        $sql = "SELECT id, title, itemurl, uid, downloads, pudt FROM fun_download WHERE type='1' ORDER BY pudt DESC LIMIT $limit_start, $items_per_page";
    } 

    if ($page > 1) {
        $ppage = $page-1;
        echo "<div class='center border_top'><a href=\"rskontakt.php?$action&amp;page=$ppage&amp;who=$who\"><img src='images/up.png' /></a></div>";
    } 
    $items = mysql_query($sql);
    echo mysql_error();
    if (mysql_num_rows($items) > 0) {
        while ($item = mysql_fetch_array($items)) {
            $ext = getext($item[2]);
            $ime = getextimg($ext);
            $lnk = "<a href=\"rskontakt.php?id=$item[0]\">$ime" . htmlspecialchars($item[1]) . "</a>";
            $downloads = "Preuzeto: <b>$item[4]</b> puta";
            $dateadded = date("d/m/y", $item[5]);
            $dateadded1 = "Dodato: <b>$dateadded</b>";

            if ($who != "") {
                $byusr = "";
            } else {
                $unick = getnick_uid($item[3]);
                $ulnk = "<a href=\"index.php?viewuser&amp;who=$item[3]\">$unick</a>";
                $byusr = "Dodao/la: $ulnk";
            } 
            echo "<div class='comment'>$lnk <br/>$byusr <br/>$dateadded1<br/>$downloads<br/></div>";
        } 
    } 

    if ($page < $num_pages) {
        $npage = $page + 1;
        echo "<div class='center border_top'><a href=\"rskontakt.php?$action&amp;page=$npage&amp;who=$who\"><img src='images/down.png' /></a></div>";
    } 

    echo "<div class='comment comm_adv'><img src='img/img_42.png' /><a href=\"rskontakt.php?download\">Kategorije</a></div>";
} else if (isset($_GET['vaultpics'])) {
    $who = $_GET["who"]; 
    // ////ALL LISTS SCRIPT <<
    if ($page == "" || $page <= 0)$page = 1;
    if ($who != "") {
        $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_download WHERE uid='" . $who . "' AND type='2'"));
    } else {
        $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_download WHERE type='2'"));
    } 
    $num_items = $noi[0]; //changable
    $items_per_page = 5;
    $num_pages = ceil($num_items / $items_per_page);
    if (($page > $num_pages) && $page != 1)$page = $num_pages;
    $limit_start = ($page-1) * $items_per_page;

    if ($who != "") {
        $sql = "SELECT id, title, itemurl, uid, downloads, pudt FROM fun_download WHERE uid='" . $who . "' AND type='2' ORDER BY pudt DESC LIMIT $limit_start, $items_per_page";
    } else {
        $sql = "SELECT id, title, itemurl, uid, downloads, pudt FROM fun_download WHERE type='2' ORDER BY pudt DESC LIMIT $limit_start, $items_per_page";
    } 

    if ($page > 1) {
        $ppage = $page-1;
        echo "<div class='center border_top'><a href=\"rskontakt.php?$action&amp;page=$ppage&amp;who=$who\"><img src='images/up.png' /></a></div>";
    } 
    $items = mysql_query($sql);
    echo mysql_error();
    if (mysql_num_rows($items) > 0) {
        while ($item = mysql_fetch_array($items)) {
            $ext = getext($item[2]);
            $mysock = getimagesize("$item[2]");
            $ime = "<img src=\"$item[2]\" heigh='100' width='100' alt=\"*\"/>";
            $lnk = "<a href=\"rskontakt.php?id=$item[0]\">$ime<br/>$item[1]</a>";
            $downloads = "Preuzeto: <b>$item[4]</b> puta";
            $dateadded = date("d/m/y", $item[5]);
            $dateadded1 = "Dodato: <b>$dateadded</b>";

            if ($who != "") {
                $byusr = "";
            } else {
                $unick = getnick_uid($item[3]);
                $ulnk = "$unick";
                $byusr = "Dodao/la: $ulnk";
            } 
            echo "<div class='sett_line'>$lnk <br/>$byusr <br/>$dateadded1<br/>$downloads<br/></div>";
        } 
    } 
    if ($page < $num_pages) {
        $npage = $page + 1;
        echo "<div class='center border_top'><a href=\"rskontakt.php?$action&amp;page=$npage&amp;who=$who\"><img src='images/down.png' /></a></div>";
    } 

    echo "<div class='comment comm_adv'><img src='img/img_42.png' /><a href=\"rskontakt.php?download\">Kategorije</a></div>";
} else if (isset($_GET['vaultgames'])) { // /////////////////Muzika
        $who = $_GET["who"];

    if ($page == "" || $page <= 0)$page = 1;
    if ($who != "") {
        $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_download WHERE uid='" . $who . "' AND type='1'"));
    } else {
        $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_download WHERE type='1'"));
    } 
    $num_items = $noi[0]; //changable
    $items_per_page = 5;
    $num_pages = ceil($num_items / $items_per_page);
    if (($page > $num_pages) && $page != 1)$page = $num_pages;
    $limit_start = ($page-1) * $items_per_page;

    if ($who != "") {
        $sql = "SELECT id, title, itemurl, uid, downloads, pudt FROM fun_download WHERE uid='" . $who . "' AND type='1' ORDER BY pudt DESC LIMIT $limit_start, $items_per_page";
    } else {
        $sql = "SELECT id, title, itemurl, uid, downloads, pudt FROM fun_download WHERE type='1' ORDER BY pudt DESC LIMIT $limit_start, $items_per_page";
    } 

    if ($page > 1) {
        $ppage = $page-1;
        echo "<div class='center border_top'><a href=\"rskontakt.php?$action&amp;page=$ppage&amp;who=$who\"><img src='images/up.png' /></a></div>";
    } 
    $items = mysql_query($sql);
    echo mysql_error();
    if (mysql_num_rows($items) > 0) {
        while ($item = mysql_fetch_array($items)) {
            $ext = getext($item[2]);
            $ime = getextimg($ext);
            $lnk = "<a href=\"rskontakt.php?id=$item[0]\">$ime" . htmlspecialchars($item[1]) . "</a>";
            $downloads = "Preuzeto: <b>$item[4]</b> puta";
            $dateadded = date("d/m/y", $item[5]);
            $dateadded1 = "Dodato: <b>$dateadded</b>";

            if ($who != "") {
                $byusr = "";
            } else {
                $unick = getnick_uid($item[3]);
                $ulnk = "<a href=\"index.php?viewuser&amp;who=$item[3]\">$unick</a>";
                $byusr = "Dodao/la: $ulnk";
            } 
            echo "<div class='comment'>$lnk <br/>$byusr <br/>$dateadded1<br/>$downloads<br/></div>";
        } 
    } 

    if ($page < $num_pages) {
        $npage = $page + 1;
        echo "<div class='center border_top'><a href=\"rskontakt.php?$action&amp;page=$npage&amp;who=$who\"><img src='images/down.png' /></a></div>";
    } 

    echo "<div class='comment comm_adv'><img src='img/img_42.png' /><a href=\"rskontakt.php?download\">Kategorije</a></div>";
} else if (isset($_GET['vaultvideos'])) { // /////////////////Muzika
        $who = $_GET["who"];

    if ($page == "" || $page <= 0)$page = 1;
    if ($who != "") {
        $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_download WHERE uid='" . $who . "' AND type='1'"));
    } else {
        $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_download WHERE type='1'"));
    } 
    $num_items = $noi[0]; //changable
    $items_per_page = 5;
    $num_pages = ceil($num_items / $items_per_page);
    if (($page > $num_pages) && $page != 1)$page = $num_pages;
    $limit_start = ($page-1) * $items_per_page;

    if ($who != "") {
        $sql = "SELECT id, title, itemurl, uid, downloads, pudt FROM fun_download WHERE uid='" . $who . "' AND type='1' ORDER BY pudt DESC LIMIT $limit_start, $items_per_page";
    } else {
        $sql = "SELECT id, title, itemurl, uid, downloads, pudt FROM fun_download WHERE type='1' ORDER BY pudt DESC LIMIT $limit_start, $items_per_page";
    } 

    if ($page > 1) {
        $ppage = $page-1;
        echo "<div class='center border_top'><a href=\"rskontakt.php?$action&amp;page=$ppage&amp;who=$who\"><img src='images/up.png' /></a></div>";
    } 
    $items = mysql_query($sql);
    echo mysql_error();
    if (mysql_num_rows($items) > 0) {
        while ($item = mysql_fetch_array($items)) {
            $ext = getext($item[2]);
            $ime = getextimg($ext);
            $lnk = "<a href=\"rskontakt.php?id=$item[0]\">$ime" . htmlspecialchars($item[1]) . "</a>";
            $downloads = "Preuzeto: <b>$item[4]</b> puta";
            $dateadded = date("d/m/y", $item[5]);
            $dateadded1 = "Dodato: <b>$dateadded</b>";

            if ($who != "") {
                $byusr = "";
            } else {
                $unick = getnick_uid($item[3]);
                $ulnk = "<a href=\"index.php?viewuser&amp;who=$item[3]\">$unick</a>";
                $byusr = "Dodao/la: $ulnk";
            } 
            echo "<div class='comment'>$lnk <br/>$byusr <br/>$dateadded1<br/>$downloads<br/></div>";
        } 
    } 

    if ($page < $num_pages) {
        $npage = $page + 1;
        echo "<div class='center border_top'><a href=\"rskontakt.php?$action&amp;page=$npage&amp;who=$who\"><img src='images/down.png' /></a></div>";
    } 

    echo "<div class='comment comm_adv'><img src='img/img_42.png' /><a href=\"rskontakt.php?download\">Kategorije</a></div>";
} else if (isset($_GET['vaultother'])) { // /////////////////Muzika
        $who = $_GET["who"];

    if ($page == "" || $page <= 0)$page = 1;
    if ($who != "") {
        $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_download WHERE uid='" . $who . "' AND type='1'"));
    } else {
        $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_download WHERE type='1'"));
    } 
    $num_items = $noi[0]; //changable
    $items_per_page = 5;
    $num_pages = ceil($num_items / $items_per_page);
    if (($page > $num_pages) && $page != 1)$page = $num_pages;
    $limit_start = ($page-1) * $items_per_page;

    if ($who != "") {
        $sql = "SELECT id, title, itemurl, uid, downloads, pudt FROM fun_download WHERE uid='" . $who . "' AND type='1' ORDER BY pudt DESC LIMIT $limit_start, $items_per_page";
    } else {
        $sql = "SELECT id, title, itemurl, uid, downloads, pudt FROM fun_download WHERE type='1' ORDER BY pudt DESC LIMIT $limit_start, $items_per_page";
    } 

    if ($page > 1) {
        $ppage = $page-1;
        echo "<div class='center border_top'><a href=\"rskontakt.php?$action&amp;page=$ppage&amp;who=$who\"><img src='images/up.png' /></a></div>";
    } 
    $items = mysql_query($sql);
    echo mysql_error();
    if (mysql_num_rows($items) > 0) {
        while ($item = mysql_fetch_array($items)) {
            $ext = getext($item[2]);
            $ime = getextimg($ext);
            $lnk = "<a href=\"rskontakt.php?id=$item[0]\">$ime" . htmlspecialchars($item[1]) . "</a>";
            $downloads = "Preuzeto: <b>$item[4]</b> puta";
            $dateadded = date("d/m/y", $item[5]);
            $dateadded1 = "Dodato: <b>$dateadded</b>";

            if ($who != "") {
                $byusr = "";
            } else {
                $unick = getnick_uid($item[3]);
                $ulnk = "<a href=\"index.php?viewuser&amp;who=$item[3]\">$unick</a>";
                $byusr = "Dodao/la: $ulnk";
            } 
            echo "<div class='comment'>$lnk <br/>$byusr <br/>$dateadded1<br/>$downloads<br/></div>";
        } 
    } else {
        echo "NEMA DOWNLOADA U ODBRANOJ KATEGORIJI...";
    } 

    if ($page < $num_pages) {
        $npage = $page + 1;
        echo "<div class='center border_top'><a href=\"rskontakt.php?$action&amp;page=$npage&amp;who=$who\"><img src='images/down.png' /></a></div>";
    } 

    echo "<div class='comment comm_adv'><img src='img/img_42.png' /><a href=\"rskontakt.php?download\">Kategorije</a></div>";
} else if ($id) {
    $id = $_GET["id"];

    $fajl = mysql_fetch_array(mysql_query("SELECT id, title, itemurl, uid, downloads, pudt FROM fun_download WHERE id='" . $id . "'"));
    if ($fajl) {
        $preuzeto = $fajl[4] + 1;
        mysql_query("UPDATE fun_download SET downloads='" . $preuzeto . "' WHERE id='" . $id . "'");
        echo "<div class='titlz'><a href='$fajl[2]'>Preuzmi fajl: $fajl[1]</a></div>";
    } else {
        echo "Fajl sa takvim id-om ne postoji...";
    } 
    echo "<div class='comment comm_adv'><a href=\"rskontakt.php?main\">Kategorije</a></div>";
} else if (isset($_GET['gb'])) {
    echo "<form action='rskontakt.php?gbwrite' method='post'>
<div class='sett_line'>Nadimak (nick): <br /> <input name='name'></div>
<div class='sett_line'>E-mail: <br /> <input name='mail'></div>
<div class='sett_line'>Poruka (Message): <br /><textarea name='text' rows='4'></textarea></div>
<div class='sett_line'>Posalji (Submit)</div>
</form>
"; 
// /// <input type='submit' class='button' value='Posalji (Submit)'>
    function bbkod($text)
    {
        $text = ereg_replace("http://[A-Za-z0-9./=?-_]+", "<a href=\"\\0\">\\0</a>", $text);
        $text = str_replace("\r\n", "<br> ", $text);
        $text = str_replace("\n", '<br/> ', $text);
        $text = preg_replace("/\[b\](.*?)\[\/b\]/i", "<b>\\1</b>", $text);
        $text = preg_replace("/\[i\](.*?)\[\/i\]/i", "<i>\\1</i>", $text);
        $text = preg_replace("/\[u\](.*?)\[\/u\]/i", "<u>\\1</u>", $text);
        $text = preg_replace("/\[big\](.*?)\[\/big\]/i", "<big>\\1</big>", $text);
        $text = preg_replace("/\[small\](.*?)\[\/small\]/i", "<small>\\1</small>", $text);
        return $text;
    } 
    // //////////////////////////////////////////////////
    // //////////////////////////////////////////////////
    // //////////////////////////////////////////////////
    if ($page == "" || $page <= 0)$page = 1;

    $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM knjiga WHERE id"));

    $num_items = $noi[0]; //changable
    $items_per_page = 10;
    $num_pages = ceil($num_items / $items_per_page);
    if (($page > $num_pages) && $page != 1)$page = $num_pages;
    $limit_start = ($page-1) * $items_per_page;

    $sql = "SELECT id, name, mail, text, time FROM knjiga WHERE id ORDER BY time DESC LIMIT $limit_start, $items_per_page";

    if ($page > 1) {
        $ppage = $page-1;
        echo "<div class='center border_top'><a href=\"rskontakt.php?$action&amp;page=$ppage\"><img src='images/up.png' /></a></div>";
    } 
    $items = mysql_query($sql);
    echo mysql_error();
    if (mysql_num_rows($items) > 0) {
        while ($item = mysql_fetch_array($items)) {
            $tekst = bbkod($item[3]);
            $var1 = date("his", $item[4]);
            $var2 = time ();
            $var21 = date("his", $var2);
            $var3 = $var21 - $var1;
            $var4 = date("s", $var3);
            $remain = time() - $item[4];
            $vreme = gettimemsg($remain);
            echo"<div class='comment border_bottom'><b>$item[0]</b>. $item[1] : $tekst <br /> <a href='mailto:$item[2]'>$item[2]</a> <u>$vreme</u> </div> ";
        } 
    } else {
        echo"NEMA ISPISANIH PORUKA...";
    } 

    if ($page < $num_pages) {
        $npage = $page + 1;
        echo "<div class='center border_top'><a href=\"rskontakt.php?$action&amp;page=$npage\"><img src='images/down.png' /></a></div>";
    } 
    // //////////////////////////////////////////
    // //////////////////////////////////////////
    // //////////////////////////////////////////
} else if (isset($_GET['gbwrite'])) {
    $name = $_POST['name'];
    $mail = $_POST['mail'];
    $text = $_POST['text'];
    if ($text && $name == "") {
        echo "MOLIM VAS DA UNESETE NADIMAK I PORUKU!!!
<br />
PLEASE INSERT YOUR NAME AND MESSAGE!!!<br />";
    } else {
        $name = $_POST['name'];
        $ltm = time();
        $res = mysql_query("INSERT INTO knjiga SET name='" . $name . "', mail='" . $mail . "', text='" . $text . "', time='" . $ltm . "'");
        if ($res) {
            echo "<div class=\"pad\"><div class=\"notif border_bottom\">Vasa poruka je uspesno poslata! Hvala.<br />Your message has ben sucessfully sent! Thanks.</div>";
        } else {
            echo "<div class=\"pad\"><div class=\"error border_bottom\">Vasa poruka nije poslata! GRESKA!!!<br />Your message has not be sent! ERROR!!!</div>";
        } 
    } 
    echo "<a href='rskontakt.php?gb'>Vrati se u knjigu...Go back to book...</a>";
} else {
    echo "ZALUTALI STE....";
} 
dnooffline();

?>
</body>
</html>
