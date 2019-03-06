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
    $msgtxt = $_POST["msgtxt"];
    $like = $_GET["like"];
    $liked = $_GET["liked"];
    $dislike = $_GET["dislike"];
    addonline(getuid_sid($sid), "komentari", "");

    echo vrhonline($sid, $uid); 
    // ////////////////////////////////////////
    if ($msgtxt == "") {
        echo "";
    } else {
        $uid = getuid_sid($sid);
        $crdate = time();
        $res = mysql_query("INSERT INTO fun_komentari SET komowner='" . $who . "', komsigner='" . $uid . "', dtime='" . $crdate . "', kommsg='" . $msgtxt . "'");
        if ($res) {
            echo "<div class=\"notif border_bottom\">Vasa poruka je uspesno postavljena!!!</div>";

            $vlasnik = mysql_fetch_array(mysql_query("SELECT shout, shouter, shtime FROM fun_shouts WHERE id='" . $who . "'"));
            if ($vlasnik[1] == $uid) {
                    $sql = "SELECT DISTINCT komsigner FROM fun_komentari WHERE komowner='" . $who . "'";

                    $items = mysql_query($sql);
                    echo mysql_error();
                    if (mysql_num_rows($items) > 0) {
                        while ($item = mysql_fetch_array($items)) {
                            $datas = getnick_uid($vlasnik[1]);
                            $zzz = getnick_uid($uid);

                            $msg = "[img]ico/comment2.png[/img] [user=" . $uid . "]" . $zzz . "[/user] je prokomentarisao/la svoj [shout=" . $who . "]status[/shout].";
                            mysql_query("INSERT INTO fun_notify SET text='" . $msg . "', byuid='" . $uid . "', touid='" . $item[0] . "', unread='1', timesent='" . time() . "'");
                        } 
                    } 
                
            } else {
                // ////////////////////////////////////
				$sql = "SELECT DISTINCT komsigner FROM fun_komentari WHERE komowner='" . $who . "'";

                    $items = mysql_query($sql);
                    echo mysql_error();
                    if (mysql_num_rows($items) > 0) {
                        while ($item = mysql_fetch_array($items)) {
                            $datas = getnick_uid($vlasnik[1]);
                            $zzz = getnick_uid($uid);

                            $msg = "[img]ico/comment2.png[/img] [user=" . $uid . "]" . $zzz . "[/user] je prokomentarisao [shout=" . $who . "]status[/shout] clana [user=" . $vlasnik[1] . "]" . $datas . "[/user].";
                            mysql_query("INSERT INTO fun_notify SET text='" . $msg . "', byuid='" . $uid . "', touid='" . $item[0] . "', unread='1', timesent='" . time() . "'");
							
                        } 
                    } 
            $msg = "[img]ico/comment2.png[/img] [user=" . $uid . "]" . $zzz . "[/user] je prokomentarisao/la vas [shout=" . $who . "]status[/shout].";
            mysql_query("INSERT INTO fun_notify SET text='" . $msg . "', byuid='" . $uid . "', touid='" . $vlasnik[1] . "', unread='1', timesent='" . time() . "'");
            } 
        } else {
            echo "<div class=\"error border_bottom\">Vasa poruka nije postavljena! Mogucnost da je greska u bazi, pa vas molimo da pokusate kasnije!</div>";
        } 
    } 
    // ////ALL LISTS SCRIPT <<
    // ////////////>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
    $my = "SELECT shout, shouter, shtime FROM fun_shouts WHERE id='" . $who . "'";
    $rssss = mysql_query($my);
    echo mysql_error();
    if (mysql_num_rows($rssss) > 0) {
        while ($rs = mysql_fetch_array($rssss)) {
            $avlink = getavatar($rs[1]);
            if ($avlink != "") {
                $avatar = "<a href=\"index.php?viewuser&amp;who=$rs[1]\" title='$snick'><img src=\"$avlink\" alt='$snick' height='35' width='35' /></a>";
            } else {
                $avatar = "<a href=\"index.php?viewuser&amp;who=$rs[1]\" title='$snick'><img src=\"images/nopic.jpg\" alt='$snick' height='35' width='35' /></a>";
            } 
            $snick = getnick_uid($rs[1]);
            $var1 = date("his", $rs[2]);
            $var2 = time ();
            $var21 = date("his", $var2);
            $var3 = $var21 - $var1;
            $var4 = date("s", $var3);
            $remain = time() - $rs[2];
            $bs = gettimemsg($remain);
            $lnk = "<div class='feed feed_first'>
			<div class='feed_image'>
			$avatar </div>
			<div class='feed_content'>
			<div>
			<a href=\"index.php?viewuser&amp;who=$rs[1]\" title='$snick'>$snick</a>";
            echo "$lnk";
            $text = parsepm($rs[0], $sid);
            echo "<p>$text</p> </div>
			<div class='feed_content_info'>
			<span class='feed_time_stamp'><small>$bs</small></span>";
            if (ismod(getuid_sid($sid))) {
                $dlsh = " <a href=\"index.php?main&amp;shid=$item[0]\"><img src='ico/emblem-unreadable.png' /></a>";
            } 

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
                $lajkova = "<small><a href=\"komentari.php?main&amp;like=$who&amp;liked=1&amp;who=$who\">Svidja mi se </a></small>";
            } else {
                    $lajkova = "<small><a href=\"komentari.php?main&amp;dislike=$who&amp;liked=0&amp;who=$who\">Ne svidja mi se </a></small>";
                
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
<span class='user_profile_link_span'><a href='/index.php?viewuser&amp;who=$k_likez[0]'>$lajkovaoje</a></span> i jos <a href='/lajkovi.php?main&amp;who=$who'>$zaz  prijatelj$koliko</a> $voli ovaj status.</div>";
}else { $likeballon =""; }

            echo "<span> &#183; $komentari</span><span> &#183; $lajkova</span> $likeballon
			</div>
			</div>
			</div>
			</div>
			<div class='clear border_bottom'></div>
			";
        } 
    } 
    // ///// LIKE & DISLIKE
    if ($like) {
        $ltm = time();
        mysql_query("INSERT INTO fun_shout_like SET shid='" . $like . "', uid='" . $uid . "', reqdt='" . $ltm . "', liked='" . $liked . "'");

        $vlasnik = mysql_fetch_array(mysql_query("SELECT shout, shouter, shtime FROM fun_shouts WHERE id='" . $who . "'"));
        $zzz = getnick_uid($uid);

        $msg = "[img]ico/vote_yes.png[/img] [user=" . $uid . "]" . $zzz . "[/user] kaze da mu/joj se svidja tvoj [shout=" . $who . "]status[/shout].";
        mysql_query("INSERT INTO fun_notify SET text='" . $msg . "', byuid='" . $uid . "', touid='" . $vlasnik[1] . "', unread='1', timesent='" . time() . "'");
    } 
    if ($dislike) {
        mysql_query("DELETE FROM fun_shout_like WHERE (uid='" . $uid . "' AND shid='" . $dislike . "') OR (uid='" . $dislike . "' AND shid='" . $uid . "')");
    } 
    // ////////////>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
    if ($page == "" || $page <= 0)$page = 1;
    $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_komentari WHERE komowner='" . $who . "'"));
    $num_items = $noi[0]; //changable
    $items_per_page = 7;
    $num_pages = ceil($num_items / $items_per_page);
    if (($page > $num_pages) && $page != 1)$page = $num_pages;
    $limit_start = ($page-1) * $items_per_page;

    if ($page > 1) {
        $ppage = $page-1;
        echo "<a href=\"komentari.php?main&amp;page=$ppage&amp;who=$who\" class='view_more'>Citaj prethodno</a>";
    } 

    $sql = "SELECT komowner, komsigner, kommsg, dtime, id FROM fun_komentari WHERE komowner='" . $who . "' ORDER BY dtime DESC LIMIT $limit_start, $items_per_page";
echo "<h2>Komentari</h2>";
    $items = mysql_query($sql);
    echo mysql_error();
    if (mysql_num_rows($items) > 0) {
        while ($item = mysql_fetch_array($items)) {
            $avlink = getavatar($item[1]);
            if ($avlink != "") {
                $avatar = "<a href=\"index.php?viewuser&amp;who=$item[1]\" title='$snick'><img src=\"$avlink\" alt='$snick' height='35' width='35' /></a>";
            } else {
                $avatar = "<a href=\"index.php?viewuser&amp;who=$item[1]\" title='$snick'><img src=\"images/nopic.jpg\" alt='$snick' height='35' width='35' /></a>";
            } 
            $snick = getnick_uid($item[1]);
            $var1 = date("his", $item[3]);
            $var2 = time ();
            $var21 = date("his", $var2);
            $var3 = $var21 - $var1;
            $var4 = date("s", $var3);
            $remain = time() - $item[3];
            $bs = gettimemsg($remain);
            $text = parsepm($item[2], $sid);
            $lnk = "<div id=\"js_comment_162\" class=\"js_mini_feed_comment comment_mini js_mini_comment_item_1785\">
			<div style=\"position:absolute; right:0; margin-right:2px;\">
			</div>
			<div style=\"position:absolute; left:0; margin-left:4px;\">
			$avatar </div>
			<div style=\"margin-left:40px;\">
			<span class='user_profile_link_span'><a href=\"index.php?viewuser&amp;who=$item[1]\">$snick</a></span>
			<div id='js_comment_text_162'><p>$text</p></div>
			</div>
			<div class='extra_info'>
			$bs &#183; Ukloni &#183; Prijavi </div>
			</div>
			</div>";
            echo "$lnk";
        } 
    } 
    if ($page < $num_pages) {
        $npage = $page + 1;
        echo "<a href=\"komentari.php?main&amp;page=$npage&amp;who=$who\" class='view_more'>Citaj dalje</a>";
    } 
    // //// UNTILL HERE >>
    echo "<div class=\"section\"><br /><form action=\"komentari.php?main&amp;who=$who\" method=\"post\"><div>Dodaj komentar</div>";
    echo "<textarea name=\"msgtxt\" rows=\"3\"></textarea><br/>";
    echo "<input class=\"button\" type=\"submit\" value=\"Upisi komentar\"/>";
    echo "</form></div>";
    echo dnoonline($sid, $uid);
	exit();
} else
if (isset($_GET['zid'])) {
    $who = $_GET["who"];
    $msgtxt = $_POST["msgtxt"];
    $like = $_GET["like"];
    $liked = $_GET["liked"];
    $dislike = $_GET["dislike"];
    addonline(getuid_sid($sid), "komentari", "");

    echo vrhonline($sid, $uid); 
    // ////////////////////////////////////////
    if ($msgtxt == "") {
        echo "";
    } else {
        $uid = getuid_sid($sid);
        $crdate = time();
        $res = mysql_query("INSERT INTO fun_komentari_zid SET komowner='" . $who . "', komsigner='" . $uid . "', dtime='" . $crdate . "', kommsg='" . $msgtxt . "'");
        if ($res) {
            echo "<div class=\"notif border_bottom\">Vasa poruka je uspesno postavljena!!!</div>";

            $vlasnik = mysql_fetch_array(mysql_query("SELECT gbowner FROM fun_gbook WHERE id='" . $who . "'"));
            $zzz = getnick_uid($uid);

            $msg = "[img]ico/comment2.png[/img] [user=" . $uid . "]" . $zzz . "[/user] je prokomentarisao/la vasu poruku na [zid=" . $who . "]zid/u[/zid].";
            mysql_query("INSERT INTO fun_notify SET text='" . $msg . "', byuid='" . $uid . "', touid='" . $vlasnik[0] . "', unread='1', timesent='" . time() . "'");

            $msg = "[img]ico/comment2.png[/img] [user=" . $uid . "]" . $zzz . "[/user] je prokomentarisao/la vasu poruku na [zid=" . $who . "]zid/u[/zid].";
            mysql_query("INSERT INTO fun_notify SET text='" . $msg . "', byuid='" . $uid . "', touid='" . $uid . "', unread='1', timesent='" . time() . "'");
        } else {
            echo "<div class=\"error border_bottom\">Vasa poruka nije postavljena! Mogucnost da je greska u bazi, pa vas molimo da pokusate kasnije!</div>";
        } 
    } 
    // ////ALL LISTS SCRIPT <<
    // ////////////>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
    $my = "SELECT gbmsg, gbsigner, dtime FROM fun_gbook WHERE id='" . $who . "'";
    $rssss = mysql_query($my);
    echo mysql_error();
    if (mysql_num_rows($rssss) > 0) {
        while ($rs = mysql_fetch_array($rssss)) {
            $avlink = getavatar($rs[1]);
            if ($avlink != "") {
                $avatar = "<a href=\"index.php?viewuser&amp;who=$rs[1]\" title='$snick'><img src=\"$avlink\" alt='$snick' height='35' width='35' /></a>";
            } else {
                $avatar = "<a href=\"index.php?viewuser&amp;who=$rs[1]\" title='$snick'><img src=\"images/nopic.jpg\" alt='$snick' height='35' width='35' /></a>";
            } 
            $snick = getnick_uid($rs[1]);
            $var1 = date("his", $rs[2]);
            $var2 = time ();
            $var21 = date("his", $var2);
            $var3 = $var21 - $var1;
            $var4 = date("s", $var3);
            $remain = time() - $rs[2];
            $bs = gettimemsg($remain);
            $text = parsepm($rs[0], $sid);
            $lnk = "<div class='feed feed_first'>
			<div class='feed_image'>
			$avatar </div>
			<div class='feed_content'>
			<div>
			<a href=\"index.php?viewuser&amp;who=$rs[1]\" title='$snick'>$snick</a>";
            echo "$lnk";
            $text = parsepm($rs[0], $sid);
            echo "<p>$text</p> </div>
			<div class='feed_content_info'>
			<span class='feed_time_stamp'><small>$bs</small></span>";
            if (ismod(getuid_sid($sid))) {
                $dlsh = " <a href=\"index.php?zid&amp;shid=$item[0]\"><img src='ico/emblem-unreadable.png' /></a>";
            } 

            $brojkomentara = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_komentari_zid WHERE komowner='" . $who . "'"));
            if ($brojkomentara[0] == 1) {
                $komentari = "<small><a href=\"komentari.php?zid&amp;who=$who\"><i>Jedan komentar</i></a></small>";
            } else if ($brojkomentara[0] > 0) {
                $komentara = $brojkomentara[0];
                $komentari = "<small><a href=\"komentari.php?zid&amp;who=$who\"><i><b>$komentara</b> Komentar/a</i></a></small>";
			} else {
                $komentari = "<small><a href=\"komentari.php?zid&amp;who=$who\"><i>Prokomentarisi</i></a></small>";
            } 
            // ///////
            $brl = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_shout_like_zid WHERE shid='" . $who . "' AND liked='1'"));
            $lajk = mysql_fetch_array(mysql_query("SELECT id FROM fun_shout_like_zid WHERE shid= '" . $who . "' AND uid='".$uid."'"));
		
            if ($lajk[0] == "") {
                $lajkova = "<small><a href=\"komentari.php?zid&amp;like=$who&amp;liked=1&amp;who=$who\">Svidja mi se </a></small>";
            } else {
                    $lajkova = "<small><a href=\"komentari.php?zid&amp;dislike=$who&amp;liked=0&amp;who=$who\">Ne svidja mi se </a></small>";
                
            } 
			
          $k_like = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_shout_like_zid WHERE shid='" . $who . "' AND liked='1'"));
			$k_likez = mysql_fetch_array(mysql_query("SELECT uid FROM fun_shout_like_zid WHERE shid='" . $who . "' AND liked='1' ORDER BY reqdt DESC LIMIT 1"));
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
<span class='user_profile_link_span'><a href='/index.php?viewuser&amp;who=$k_likez[0]'>$lajkovaoje</a></span> i jos <a href='/lajkovi.php?zid&amp;who=$who'>$zaz  prijatelj$koliko</a> $voli ovaj status.</div>";
}else { $likeballon =""; }

            echo "<span> &#183; $komentari</span><span> &#183; $lajkova</span> $likeballon
			</div>
			</div>
			</div>
			</div>
			<div class='clear border_bottom'></div>
			";
        } 
    } 
    // ///// LIKE & DISLIKE
    if ($like) {
        $ltm = time();
        mysql_query("INSERT INTO fun_shout_like_zid SET shid='" . $like . "', uid='" . $uid . "', reqdt='" . $ltm . "', liked='" . $liked . "'");

        $vlasnik = mysql_fetch_array(mysql_query("SELECT gbowner FROM fun_gbook WHERE id='" . $who . "'"));
        $zzz = getnick_uid($uid);

        $msg = "[img]ico/vote_yes.png[/img] [user=" . $uid . "]" . $zzz . "[/user] kaze da mu/joj se svidja tvoja poruka na [zid=" . $who . "]zid/u[/zid].";
        mysql_query("INSERT INTO fun_notify SET text='" . $msg . "', byuid='" . $uid . "', touid='" . $vlasnik[0] . "', unread='1', timesent='" . time() . "'");
    } 
    if ($dislike) {
        mysql_query("DELETE FROM fun_shout_like_zid WHERE (uid='" . $uid . "' AND shid='" . $dislike . "') OR (uid='" . $dislike . "' AND shid='" . $uid . "')");
    } 
    // ////////////>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
    if ($page == "" || $page <= 0)$page = 1;
    $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_komentari_zid WHERE komowner='" . $who . "'"));
    $num_items = $noi[0]; //changable
    $items_per_page = 7;
    $num_pages = ceil($num_items / $items_per_page);
    if (($page > $num_pages) && $page != 1)$page = $num_pages;
    $limit_start = ($page-1) * $items_per_page;
echo "<h2>Komentari</h2>";
    if ($page > 1) {
        $ppage = $page-1;
        echo "<a href=\"komentari.php?zid&amp;page=$ppage&amp;who=$who\"  class='view_more'>Citaj prethodno</a>";
    } 

    $sql = "SELECT komowner, komsigner, kommsg, dtime, id FROM fun_komentari_zid WHERE komowner='" . $who . "' ORDER BY dtime DESC LIMIT $limit_start, $items_per_page";

    $items = mysql_query($sql);
    echo mysql_error();
    if (mysql_num_rows($items) > 0) {
        while ($item = mysql_fetch_array($items)) {
            $avlink = getavatar($item[1]);
            if ($avlink != "") {
                $avatar = "<a href=\"index.php?viewuser&amp;who=$item[1]\" title='$snick'><img src=\"$avlink\" alt='$snick' height='35' width='35' /></a>";
            } else {
                $avatar = "<a href=\"index.php?viewuser&amp;who=$item[1]\" title='$snick'><img src=\"images/nopic.jpg\" alt='$snick' height='35' width='35' /></a>";
            } 
            $snick = getnick_uid($item[1]);
            $var1 = date("his", $item[3]);
            $var2 = time ();
            $var21 = date("his", $var2);
            $var3 = $var21 - $var1;
            $var4 = date("s", $var3);
            $remain = time() - $item[3];
            $bs = gettimemsg($remain);
            $text = parsepm($item[2], $sid);
            $lnk = "<div id=\"js_comment_162\" class=\"js_mini_feed_comment comment_mini js_mini_comment_item_1785\">
			<div style=\"position:absolute; right:0; margin-right:2px;\">
			</div>
			<div style=\"position:absolute; left:0; margin-left:4px;\">
			$avatar </div>
			<div style=\"margin-left:40px;\">
			<span class='user_profile_link_span'><a href=\"index.php?viewuser&amp;who=$item[1]\">$snick</a></span>
			<div id='js_comment_text_162'><p>$text</p></div>
			</div>
			<div class='extra_info'>
			$bs &#183; Ukloni &#183; Prijavi </div>
			</div>
			</div>";
            echo "$lnk";
        } 
    } 
    if ($page < $num_pages) {
        $npage = $page + 1;
        echo "<a href=\"komentari.php?zid&amp;page=$npage&amp;who=$who\" class='view_more'>Citaj dalje</a>";
    } 
    // //// UNTILL HERE >>
    echo "<div class=\"section\"><br /><form action=\"komentari.php?zid&amp;who=$who\" method=\"post\"><div>Dodaj komentar</div>";
    echo "<textarea name=\"msgtxt\" rows=\"3\"></textarea><br/>";
    echo "<input class=\"button\" type=\"submit\" value=\"Upisi komentar\"/>";
    echo "</form></div>";
    echo dnoonline($sid, $uid);
	exit();
} else if (isset($_GET['grupa'])) {
    $who = $_GET["who"];
	 $clid = $_GET["clid"];
    $msgtxt = $_POST["msgtxt"];
    $like = $_GET["like"];
    $liked = $_GET["liked"];
    $dislike = $_GET["dislike"];

    echo vrhonline($sid, $uid); 
    // ////////////////////////////////////////
    if ($msgtxt == "") {
        echo "";
    } else {
        $uid = getuid_sid($sid);
        $crdate = time();
 $clinfo = mysql_fetch_array(mysql_query("SELECT name, owner, description, rules, logo, plusses, created FROM fun_clubs WHERE id='" . $clid . "'"));
	if ($clinfo[1] == $uid) {
        $res = mysql_query("INSERT INTO fun_komentari_grupa SET komowner='" . $who . "', komsigner='" . $uid . "', dtime='" . $crdate . "', kommsg='" . $msgtxt . "', grupa='".$clid."', club='1'");
		}else{
        $res = mysql_query("INSERT INTO fun_komentari_grupa SET komowner='" . $who . "', komsigner='" . $uid . "', dtime='" . $crdate . "', kommsg='" . $msgtxt . "', grupa='".$clid."'");
		}
        if ($res) {
            echo "<div class=\"notif border_bottom\">Vasa poruka je uspesno postavljena!!!</div>"; 
        } else {
            echo "<div class=\"error border_bottom\">Vasa poruka nije postavljena! Mogucnost da je greska u bazi, pa vas molimo da pokusate kasnije!</div>";
        } 
    } 
    // ////ALL LISTS SCRIPT <<
    // ////////////>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
    $my = "SELECT shout, shouter, shtime, club, grupa FROM fun_shouts_grupa WHERE id='" . $who . "'";
    $rssss = mysql_query($my);
    echo mysql_error();
    if (mysql_num_rows($rssss) > 0) {
        while ($rs = mysql_fetch_array($rssss)) {
 $clinfo = mysql_fetch_array(mysql_query("SELECT name, owner, description, rules, logo, plusses, created FROM fun_clubs WHERE id='" . $clid . "'"));
		if ($rs[3] == "0") {
            $avlink = getavatar($rs[1]);
            if ($avlink != "") {
                $avatars = "<a href=\"index.php?viewuser&amp;who=$rs[1]\" title='$snick'><img src=\"$avlink\" alt='$snick' height='35' width='35' /></a>";
            } else {
                $avatars = "<a href=\"index.php?viewuser&amp;who=$rs[1]\" title='$snick'><img src=\"images/nopic.jpg\" alt='$snick' height='35' width='35' /></a>";
            } 
            $snick = getnick_uid($rs[1]);
			   $var1 = date("his", $rs[2]);
            $var2 = time ();
            $var21 = date("his", $var2);
            $var3 = $var21 - $var1;
            $var4 = date("s", $var3);
            $remain = time() - $rs[2];
            $bs = gettimemsg($remain);
            
            $text = parsepm($rs[0], $sid);
            $lnk = "<div class='feed feed_first'>
			<div class='feed_image'>
			$avatars </div>
			<div class='feed_content'>
			<div>
			<a href=\"index.php?viewuser&amp;who=$rs[1]\" title='$snick'>$snick</a>";
            echo "$lnk";
            $text = parsepm($rs[0], $sid);
            echo "<p>$text</p> </div>
			<div class='feed_content_info'>
			<span class='feed_time_stamp'><small>$bs</small></span>";
            if (ismod(getuid_sid($sid))) {
                $dlsh = " <a href=\"index.php?grupa&amp;shid=$item[0]\"><img src='ico/emblem-unreadable.png' /></a>";
            } 

            $brojkomentara = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_komentari_grupa WHERE komowner='" . $who . "'"));
            if ($brojkomentara[0] == 1) {
                $komentari = "<small><a href=\"komentari.php?grupa&amp;who=$who\"><i>Jedan komentar</i></a></small>";
            } else if ($brojkomentara[0] > 0) {
                $komentara = $brojkomentara[0];
                $komentari = "<small><a href=\"komentari.php?grupa&amp;who=$who\"><i><b>$komentara</b> Komentar/a</i></a></small>";
			} else {
                $komentari = "<small><a href=\"komentari.php?grupa&amp;who=$who\"><i>Prokomentarisi</i></a></small>";
            } 
            // ///////
            $brl = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_shout_like_grupa WHERE shid='" . $who . "' AND liked='1'"));
            $lajk = mysql_fetch_array(mysql_query("SELECT id FROM fun_shout_like_grupa WHERE shid= '" . $who . "' AND uid='".$uid."'"));
		
            if ($lajk[0] == "") {
                $lajkova = "<small><a href=\"komentari.php?grupa&amp;like=$who&amp;liked=1&amp;who=$who\">Svidja mi se </a></small>";
            } else {
                    $lajkova = "<small><a href=\"komentari.php?grupa&amp;dislike=$who&amp;liked=0&amp;who=$who\">Ne svidja mi se </a></small>";
                
            } 
			
          $k_like = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_shout_like_grupa WHERE shid='" . $who . "' AND liked='1'"));
			$k_likez = mysql_fetch_array(mysql_query("SELECT uid FROM fun_shout_like_grupa WHERE shid='" . $who . "' AND liked='1' ORDER BY reqdt DESC LIMIT 1"));
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
<span class='user_profile_link_span'><a href='/index.php?viewuser&amp;who=$k_likez[0]'>$lajkovaoje</a></span> i jos <a href='/lajkovi.php?grupa&amp;who=$who'>$zaz  prijatelj$koliko</a> $voli ovaj status.</div>";
}else { $likeballon =""; }

            echo "<span> &#183; $komentari</span><span> &#183; $lajkova</span> $likeballon
			</div>
			</div>
			</div>
			</div>
			<div class='clear border_bottom'></div>
			</div>";
			} else {
			 if ($clinfo[4]!=""){
      $avatarz = "<a href=\"grupa.php?clid=$clid\" title='$snick'><img src=\"$avlink\" alt='$snick' height='35' width='35' /></a>";
      }else{
      $avatarz = "<a href=\"grupa.php?clid=$clid\" title='$snick'><img src='images/logo.png' alt='$snick' height='35' width='35' /></a>";
      }
            $snick = $clinfo[0];
			   $var1 = date("his", $rs[2]);
            $var2 = time ();
            $var21 = date("his", $var2);
            $var3 = $var21 - $var1;
            $var4 = date("s", $var3);
            $remain = time() - $rs[2];
            $bs = gettimemsg($remain);
            
            $text = parsepm($rs[0], $sid);
            $lnk = "<div class='feed feed_first'>
			<div class='feed_image'>
			$avatarz </div>
			<div class='feed_content'>
			<div>
			<a href=\"grupa.php?clid=$clid\" title='$snick'>$snick</a>";
            echo "$lnk";
            $text = parsepm($rs[0], $sid);
            echo "<p>$text</p> </div>
			<div class='feed_content_info'>
			<span class='feed_time_stamp'><small>$bs</small></span>";
            if (ismod(getuid_sid($sid))) {
                $dlsh = " <a href=\"index.php?grupa&amp;shid=$item[0]\"><img src='ico/emblem-unreadable.png' /></a>";
            } 

            $brojkomentara = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_komentari_grupa WHERE komowner='" . $who . "'"));
            if ($brojkomentara[0] == 1) {
                $komentari = "<small><a href=\"komentari.php?grupa&amp;who=$who&amp;clid=$clid\"><i>Jedan komentar</i></a></small>";
            } else if ($brojkomentara[0] > 0) {
                $komentara = $brojkomentara[0];
                $komentari = "<small><a href=\"komentari.php?grupa&amp;who=$who&amp;clid=$clid\"><i><b>$komentara</b> Komentar/a</i></a></small>";
			} else {
                $komentari = "<small><a href=\"komentari.php?grupa&amp;who=$who&amp;clid=$clid\"><i>Prokomentarisi</i></a></small>";
            } 
            // ///////
            $brl = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_shout_like_grupa WHERE shid='" . $who . "' AND liked='1'"));
            $lajk = mysql_fetch_array(mysql_query("SELECT id FROM fun_shout_like_grupa WHERE shid= '" . $who . "' AND uid='".$uid."'"));
		
            if ($lajk[0] == "") {
                $lajkova = "<small><a href=\"komentari.php?grupa&amp;like=$who&amp;liked=1&amp;who=$who&amp;clid=$clid\">Svidja mi se </a></small>";
            } else {
                    $lajkova = "<small><a href=\"komentari.php?grupa&amp;dislike=$who&amp;liked=0&amp;who=$who&amp;clid=$clid\">Ne svidja mi se </a></small>";
                
            } 
			
          $k_like = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_shout_like_grupa WHERE shid='" . $who . "' AND liked='1'"));
			$k_likez = mysql_fetch_array(mysql_query("SELECT uid FROM fun_shout_like_grupa WHERE shid='" . $who . "' AND liked='1' ORDER BY reqdt DESC LIMIT 1"));
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
<span class='user_profile_link_span'><a href='/index.php?viewuser&amp;who=$k_likez[0]'>$lajkovaoje</a></span> i jos <a href='/lajkovi.php?grupa&amp;who=$who'>$zaz  prijatelj$koliko</a> $voli ovaj status.</div>";
}else { $likeballon =""; }

            echo "<span> &#183; $komentari</span><span> &#183; $lajkova</span> $likeballon
			</div>
			</div>
			</div>
			</div>
			<div class='clear border_bottom'></div>
			</div>
			";
			}
        } 
    } 
    // ///// LIKE & DISLIKE
    if ($like) {
        $ltm = time();
        mysql_query("INSERT INTO fun_shout_like_grupa SET shid='" . $like . "', uid='" . $uid . "', reqdt='" . $ltm . "', liked='" . $liked . "'");
    } 
    if ($dislike) {
        mysql_query("DELETE FROM fun_shout_like_grupa WHERE (uid='" . $uid . "' AND shid='" . $dislike . "') OR (uid='" . $dislike . "' AND shid='" . $uid . "')");
    } 
    // ////////////>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
    if ($page == "" || $page <= 0)$page = 1;
    $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_komentari_grupa WHERE komowner='" . $who . "'"));
    $num_items = $noi[0]; //changable
    $items_per_page = 7;
    $num_pages = ceil($num_items / $items_per_page);
    if (($page > $num_pages) && $page != 1)$page = $num_pages;
    $limit_start = ($page-1) * $items_per_page;
echo "<h2>Komentari</h2>";
    if ($page > 1) {
        $ppage = $page-1;
        echo "<a href=\"komentari.php?grupa&amp;page=$ppage&amp;clid=$clid&amp;who=$who\" class='view_more'>Citaj prethodno</a>";
    } 

	 $clid = $_GET["clid"];
    $sql = "SELECT komowner, komsigner, kommsg, dtime, id, club, grupa FROM fun_komentari_grupa WHERE komowner='" . $who . "' ORDER BY dtime DESC LIMIT $limit_start, $items_per_page";

    $items = mysql_query($sql);
    echo mysql_error();
    if (mysql_num_rows($items) > 0) {
        while ($item = mysql_fetch_array($items)) {
            $clinfo = mysql_fetch_array(mysql_query("SELECT name, owner, description, rules, logo, plusses, created FROM fun_clubs WHERE id='" . $clid . "'"));
		if ($item[5] == "0") {
            $avlink = getavatar($item[1]);
            if ($avlink != "") {
                $avatara = "<a href=\"index.php?viewuser&amp;who=$item[1]\" title='$snick'><img src=\"$avlink\" alt='$snick' height='35' width='35' /></a>";
            } else {
                $avatara = "<a href=\"index.php?viewuser&amp;who=$item[1]\" title='$snick'><img src=\"images/nopic.jpg\" alt='$snick' height='35' width='35' /></a>";
            } 
            $snick = getnick_uid($item[1]);
			$var1 = date("his", $item[3]);
            $var2 = time ();
            $var21 = date("his", $var2);
            $var3 = $var21 - $var1;
            $var4 = date("s", $var3);
            $remain = time() - $item[3];
            $bs = gettimemsg($remain);
            
            $text = parsepm($item[2], $sid);
            $lnk = "<div id=\"js_comment_162\" class=\"js_mini_feed_comment comment_mini js_mini_comment_item_1785\">
			<div style=\"position:absolute; right:0; margin-right:2px;\">
			</div>
			<div style=\"position:absolute; left:0; margin-left:4px;\">
			$avatara </div>
			<div style=\"margin-left:40px;\">
			<span class='user_profile_link_span'><a href='index.php?viewuser&amp;who=$item[1]'>$snick</a></span>
			<div id='js_comment_text_162'><p>$text</p></div>
			</div>
			<div class='extra_info'>
			$bs &#183; Ukloni &#183; Prijavi </div>
			</div>
			";
			}else {
			 if ($clinfo[4]!=""){
      $avatar = "<a href=\"grupa.php?clid=$clid\" title='$snick'><img src=\"$clinfo[4]\" alt='$snick' height='35' width='35' /></a>";
      }else{
      $avatar = "<a href=\"grupa.php?clid=$clid\" title='$snick'><img src='images/logo.png' alt='$snick' height='35' width='35' //></a>";
      }
            $snick = $clinfo[0];
			   $var1 = date("his", $item[3]);
            $var2 = time ();
            $var21 = date("his", $var2);
            $var3 = $var21 - $var1;
            $var4 = date("s", $var3);
            $remain = time() - $item[3];
            $bs = gettimemsg($remain);
            
            $text = parsepm($item[2], $sid);
            $lnk = "<div id=\"js_comment_162\" class=\"js_mini_feed_comment comment_mini js_mini_comment_item_1785\">
			<div style=\"position:absolute; right:0; margin-right:2px;\">
			</div>
			<div style=\"position:absolute; left:0; margin-left:4px;\">
			$avatar </div>
			<div style=\"margin-left:40px;\">
			<span class='user_profile_link_span'><a href='grupa.php?clid=$clid'>$snick</a></span>
			<div id='js_comment_text_162'><p>$text</p></div>
			</div>
			<div class='extra_info'>
			$bs &#183; Ukloni &#183; Prijavi </div>
			</div>
			";
			}
            echo "$lnk";
        } 
    } 
    if ($page < $num_pages) {
        $npage = $page + 1;
        echo "<a href=\"komentari.php?grupa&amp;page=$npage&amp;clid=$clid&amp;who=$who\" class='view_more'>Citaj dalje</a>";
    } 
    // //// UNTILL HERE >>
    echo "<div class=\"section\"><br /><form action=\"komentari.php?grupa&amp;clid=$clid&amp;who=$who\" method=\"post\"><div>Dodaj komentar</div>";
    echo "<textarea name=\"msgtxt\" rows=\"3\"></textarea><br/>";
    echo "<input class=\"button\" type=\"submit\" value=\"Upisi komentar\"/>";
    echo "</form></div>";
    echo dnoonline($sid, $uid);
	exit();
} else {
    // ///////////////////////Main Page Here
    echo vrhoffline();
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
