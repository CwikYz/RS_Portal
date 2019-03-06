<?php

include("core.php");
include("config.php");
@session_start();

header("Content-type: text/html; charset=ISO-8859-1");
echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>";
echo "<!DOCTYPE html PUBLIC \"-//WAPFORUM//DTD XHTML Mobile 1.0//EN\"\"http://www.wapforum.org/DTD/xhtml-mobile10.dtd\">";
echo "<html xmlns=\"http://www.w3.org/1999/xhtml\">";


?>
<!--Molim vas bez kradje sourcea i dizajna portala....-->
<!--Molim vas bez kradje sourcea i dizajna portala....-->
<!--Molim vas bez kradje sourcea i dizajna portala....-->
<!--Molim vas bez kradje sourcea i dizajna portala....-->
<!--Molim vas bez kradje sourcea i dizajna portala....-->
<!--Molim vas bez kradje sourcea i dizajna portala....-->
<!--Molim vas bez kradje sourcea i dizajna portala....-->
<!--Molim vas bez kradje sourcea i dizajna portala....-->
<!--Molim vas bez kradje sourcea i dizajna portala....-->
<!--Molim vas bez kradje sourcea i dizajna portala....-->


<?php
$bcon = connectdb();
if (!$bcon) {

    ?>
	Greska<br />
	Sajt nije uspeo da se poveze sa bazom....<br />
	Molim vas da osvezite staranu...
	<?php

} 
$brws = explode(" ", $HTTP_USER_AGENT);
$ubr = $brws[0];
$uip = getip();
$action = $_GET["action"];
$sid = $_SESSION["sid"];
$page = $_GET["page"];
$who = $_GET["who"];
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
        $uid = getuid_sid($sid);
        $whonick = getnick_uid($uid);
        $logoutses = mysql_query("DELETE FROM fun_ses WHERE uid='" . $uid . "'");
        $logoutonline = mysql_query("DELETE FROM fun_online WHERE userid='" . $uid . "'");
        echo "<form action=\"login.php\" method=\"get\">";
        echo "<small>Korisnicko ime (Nadimak):</small> <br /><input name=\"loguid\" maxlength=\"30\"/><br/>";
        echo "<small>Lozinka:</small>  <br /><input type=\"password\" name=\"logpwd\" maxlength=\"30\"/><br/>";
        echo "<input class=\"button\" type=\"submit\" value=\"Prijava\"/>";
        echo "</form>";
        echo "<br /> <small>Imate problema sa logovanjem?</small> <br /> <small><a href=\"login.php\">Pokusajte alternativnu prijavu</a></small> <br />";
        echo "<br /> <small>Potreban ti je $stitle nalog?</small> <br /> <small><a href=\"register.php\">Prijavite se ovde</a></small> <br />";

        exit();
    } 
} 
	//if(!isset($_SESSION["sid"]))
//{
//header('location:http://secafe.freehostia.com/index.php?main');
//}
    $uid = getuid_sid($sid);
    if ((islogged($sid) == false) || ($uid == 0)) {


$fotkica = mysql_fetch_array(mysql_query("SELECT adr FROM fotkice ORDER BY RAND()  LIMIT 1"));
    ?>
<div style='background-color:#000;text-align:center;'><img style='width:100%;' src='<?echo $fotkica[0];?>' /></div>
<div class="section border_top"></div>
<div class="section_title"><div class="marker"><small>Potreban ti je RS Profil? <a href="register.php">Napravi ga ovde</a></small></div></div>
<div class="section border_top"></div>
<div class="section_title"><div class="marker">Login na RS</div></div>
<form action="login.php" method="get" onsubmit="document.forms[0].submit.disabled = 'true'; document.forms[0].submit.value = 'Prijavljivanje u toku...';">
<div class='sett_line'><div style='margin: 2px'><small>Korisnicko ime (Nadimak):</small> <br /> <input name="loguid" maxlength="30"/></div></div>
<div class='sett_line'><div style='margin: 2px'><small>Lozinka:</small>  <br /> <input type="password" name="logpwd" maxlength="30"/></div></div>
<div class='sett_line'><div style='margin: 2px'><input style="color:#555;font-weight:bold;opacity:1;background-color:#eee;border:1px solid #aaa;text-shadow:#fff 0 1px 0;" type="submit" value="Prijava"/></div></div></div>
</form>
<div class='notif border_bottom_light'><small>Imate problema sa logovanjem?</small> <br /> <small><a href="login.php">Pokusajte alternativnu prijavu</a></small> </div>



<?php
        include ("portal/index.php");
		
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
// //////////////////////////////////////MAIN PAGE
if (isset($_GET['main'])) {
    $shtxt = $_POST["shtxt"];
    $shid = $_GET["shid"];
    $todo = $_GET["todo"];
    $ko = $_GET["ko"];
    addvisitor(); 
    // saveuinfo($sid);
    echo vrhonline($sid, $uid); 
    // ///// INSERT IN SH BOX
    if ($shtxt == "") {
        echo "";
    } else {
        $shtxt = $shtxt; 
        // $uid = getuid_sid($sid);
        $shtm = time();
        $res = mysql_query("INSERT INTO fun_shouts SET shout='" . $shtxt . "', shouter='" . $uid . "', shtime='" . $shtm . "'");
        if ($res) {
            $shts = mysql_fetch_array(mysql_query("SELECT shouts from fun_users WHERE id='" . $uid . "'"));
            $shts = $shts[0] + 1;
            mysql_query("UPDATE fun_users SET shouts='" . $shts . "' WHERE id='" . $uid . "'");
            echo "<div class=\"pad\"><div class=\"notif border_bottom\">Vasa poruka je uspesno postavljena</div></div>";
        } else {
            echo "<div class=\"pad\"><div class=\"error border_bottom\">Vasa poruka nije postavljena! Mogucnost da je greska u bazi, pa vas molimo da pokusate kasnije!</div></div>";
        } 
    } 
    // /////
    // ///// DELETE FROM SH BOX
    if (ismod(getuid_sid($sid))) {
        if ($shid == "") {
            echo "";
        } else {
            $shid = $shid;

            $sht = mysql_fetch_array(mysql_query("SELECT shouter, shout FROM fun_shouts WHERE id='" . $shid . "'"));
            $msg = getnick_uid($sht[0]);
            $msg .= ": " . htmlspecialchars((strlen($sht[1]) < 20?$sht[1]:substr($sht[1], 0, 20)));
            $res = mysql_query("DELETE FROM fun_shouts WHERE id ='" . $shid . "'");
            if ($res) {
                mysql_query("DELETE FROM fun_komentari WHERE komowner ='" . $shid . "'");
                mysql_query("INSERT INTO fun_mlog SET action='shouts', details='<b>" . getnick_uid(getuid_sid($sid)) . "</b> Deleted the shout <b>" . $shid . "</b> - $msg', actdt='" . time() . "'");
                echo "<div class=\"pad\"><div class=\"notif border_bottom\">Poruka je uspesno obrisana.....</div></div>";
            } else {
                echo "<div class=\"pad\"><div class=\"error border_bottom\">Poruka nije obrisana.....</div></div>";
            } 
        } 
    } 
    // /////
    /*
  $reqs = getnreqs($uid);
  if($reqs>0)
  {
    echo "<div class=\"pad\"><div class=\"notif border_bottom\">Imate <a href=\"lists.php?reqs\">$reqs zahtev/a</a> za prijatelja!</div></div>";
  }
  */
    $reqs = getnreqs($uid);
    if ($reqs > 0) {
        echo "<div class='pad'><div class='notif border_bottom'>"; 
        // changable sql
        $sql = "SELECT uid  FROM fun_buddies WHERE tid='" . $uid . "' AND agreed='0'";

        $items = mysql_query($sql);
        echo mysql_error();
        if (mysql_num_rows($items) > 0) {
            while ($item = mysql_fetch_array($items)) {
                $rnick = getnick_uid($item[0]);
                $avlink = getavatar($item[0]);
                if ($avlink != "") {
                    $avatar = "<img src=\"$avlink\" class=\"p\" height=\"25\" width=\"25\" alt=\"avatar\"/>";
                } else {
                    $avatar = "<img src=\"images/nopic.jpg\" class=\"p\" height=\"25\" width=\"25\" alt=\"avatar\"/>";
                } 
                $lnk = "<a href=\"index.php?viewuser&amp;who=$item[0]\">$rnick</a>";
                echo "<table border='0' width='100%' id='download'>Imate zahtev za prijateljstvo od: <tr><td align='left' width='1' height='1'>$avatar</td><td>$lnk " . rating($item[0]) . "<br /><a href=\"index.php?main&amp;ko=$item[0]&amp;todo=add\">Prihvati</a>, <a href=\"index.php?main&amp;ko=$item[0]&amp;todo=del\">Ignorisi</a></td></tr></table>";
            } 
        } 
        echo "</div></div>";
    } 
    // /////////////////////////////////////////////////////////////////////////////////////////////////////////
    if ($todo) {
        $ko = $_GET["ko"];
        $ja = getuid_sid($sid);

        $tnick = getnick_uid($ko);

        if ($todo == "add") {
            if (budres($ko, $ja) != 3) {
                if (arebuds($ko, $ja)) {
                    echo "<div class='pad'><div class='notif border_bottom'>$tnick je vec vas prijatelj</div></div>";
                } else if (budres($ko, $ja) == 1) {
                    $res = mysql_query("UPDATE fun_buddies SET agreed='1' WHERE uid='" . $ko . "' AND tid='" . $ja . "'");
                    if ($res) {
                        echo "<div class='pad'><div class='notif border_bottom'>$tnick je ispesno dodat/a u tvoju listu prijatelja</div></div>";
                    } else {
                        echo "<div class='pad'><div class='notif border_bottom'>Ne mozes $tnick da dodas u prijatelje</div></div>";
                    } 
                } else {
                    echo "<div class='pad'><div class='notif border_bottom'>Ne mozes $tnick da dodas u prijatelje</div></div>";
                } 
            } else {
                echo "<div class='pad'><div class='notif border_bottom'>Ne mozes $tnick da dodas u prijatelje</div></div>";
            } 
        } else if ($todo = "del") {
            $res = mysql_query("DELETE FROM fun_buddies WHERE (uid='" . $ko . "' AND tid='" . $ja . "') OR (uid='" . $ja . "' AND tid='" . $ko . "')");
            if ($res) {
                echo "<div class='pad'><div class='notif border_bottom'>$tnick je izbacen/a iz prijatelja</div></div>";
            } else {
                echo "<div class='pad'><div class='notif border_bottom'>Ne mozes $tnick da izbacis iz prijatelja</div></div>";
            } 
        } 
    } 
    // /////////////////////////////////////////////////////////////////////////////////////////////////////////
    // /////////////////////////////////////////////////////////////////////////////////////
    if ($pages == "" || $pages <= 0)$pages = 1;
    $myid = getuid_sid($sid);
    $num_items = getnotifycount($myid); //changable
    $items_per_pages = 5;
    $num_pages = ceil($num_items / $items_per_pages);
    if ($pages > $num_pages)$pages = $num_pages;
    $limit_starts = ($pages-1) * $items_per_pages;
    if ($num_items > 0) {
        $rr = "SELECT
            a.name, b.id, b.byuid, b.unread, b.starred FROM fun_users a
            INNER JOIN fun_notify b ON a.id = b.byuid
            WHERE b.touid='" . $myid . "' AND b.unread='1'
            ORDER BY b.timesent DESC
            LIMIT $limit_starts, $items_per_pages
    ";

        $aa = mysql_query($rr);
        echo mysql_error();
        while ($ss = mysql_fetch_array($aa)) {
            $pminfo = mysql_fetch_array(mysql_query("SELECT text FROM fun_notify WHERE id='" . $ss[1] . "'"));
            $pmtext = htmlspecialchars($pminfo[0]);
            $pmdet = substr($pmtext, 0, 1000);
            $tekst = parsepm($pmdet);
            if ($ss[3] == "1") {
                $pminfo = mysql_fetch_array(mysql_query("SELECT text, byuid, timesent,touid, reported FROM fun_notify WHERE id='" . $ss[1] . "'"));
                if (getuid_sid($sid) == $myid) {
                    mysql_query("UPDATE fun_notify SET unread='0' WHERE id='" . $ss[1] . "'");
                } 
                $iml = "<b><div class=\"sett_line border_top_light\"> <small>$tekst</small>";
                $iml .= " </div></b>";
            } else {
                $iml = "<div class=\"sett_line border_bottom_light\"><small>$tekst</small>";
                $iml .= " </div>";
            } 

            $lnk = "$iml";
            echo "$lnk";
        } 
    } 
	if ($_GET["poke"]) {
	if ($_GET["poke"] == 2){
	echo "<div class='pad'><div class='notif border_bottom'>Bockanje uspesno uklonjeno</div></div>";
	mysql_query("DELETE FROM fun_poke WHERE uid='" . $uid . "' AND pid='" . $_GET["pid"] . "'");
	}
	if ($_GET["poke"] == 1) {
		$poke = getnick_uid($_GET["p"]);
	echo "<div class='pad'><div class='notif border_bottom'>Uspesno ste bocnuli <b>$poke</b></div></div>";
   mysql_query("INSERT INTO fun_poke SET pid='" . $uid . "', uid='" . $_GET["pid"] . "', vreme='".time()."'");
	mysql_query("DELETE FROM fun_poke WHERE uid='" . $uid . "' AND pid='" . $_GET["pid"] . "'");
	}
	}
	$zipo = "SELECT pid FROM fun_poke WHERE uid='" . $uid . "'";
        
    $items = mysql_query($zipo);
    echo mysql_error();
    if (mysql_num_rows($items) > 0) {
        while ($item = mysql_fetch_array($items)) {
		$poker = getnick_uid($item[0]);
echo "<table class='sett_line' width='100%'>
				<tr>
					<td width='2%'>
							<img src='/ico/hand_point.png'>
						</td>
					<td>
						<a href='/index.php?viewuser&amp;who=$item[0]'>$poker</a> te je bocnuo/la 
						<br />
						<a href='index.php?main&amp;poke=1&amp;pid=$item[0]&amp;p=$item[0]'>Uzvrati bockanje</a> ili <a href='index.php?main&amp;poke=2&amp;pid=$item[0]'>Ukloni bockanje</a>
					</td>
				</tr>
			</table>";
	}}
	////////////////////////////////////////////////
	///////
$k_post = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_users where month(`birthday`) = month(curdate()) and dayofmonth(`birthday`) = dayofmonth(curdate());"));
$k_bday = mysql_fetch_array(mysql_query("SELECT name FROM fun_users where month(`birthday`) = month(curdate()) and dayofmonth(`birthday`) = dayofmonth(curdate()) ORDER BY name LIMIT 1"));
$bday = mysql_fetch_array(mysql_query("SELECT id FROM fun_users where month(`birthday`) = month(curdate()) and dayofmonth(`birthday`) = dayofmonth(curdate()) ORDER BY name LIMIT 1"));

$sisa = $k_post[0];
$sisaz = $k_post[0] - 1;
if($sisa==1){
echo "<table class='sett_line' width='100%'><tr><td width='2%'><img src='/ico/cake.png'></td><td> <a href='/index.php?viewuser&amp;who=$bday[0]'>$k_bday[0]</a> slavi rodjendan danas.</td></tr></table>";
}
else if($sisa>=2){
echo "<table class='sett_line' width='100%'><tr><td width='2%'><img src='/ico/cake.png'></td><td> <a href='/index.php?viewuser&amp;who=$bday[0]'>$k_bday[0]</a> i jos <a href='/notify.php?bday'>$sisaz  prijatelj/a</a> slavi rodjendan danas.</td></tr></table>";
}
    // //////////////////////////////////////////////////////////////////////////////////////
    // /////
    echo "<div class=\"sett_line\">";
    echo "<form action=\"index.php?main\" method=\"post\">";
    echo "<small>Reci sta ti je na umu?</small><br /><textarea name=\"shtxt\" class='diz' rows=\"2\" value=\"\"></textarea>";
    echo "<input class=\"button\" type=\"submit\" value=\"Posalji\"/>";
    echo "</form>";
    echo "</div>";
	
    /*
  echo "<br/>";
  echo "<a href=\"index.php?funm\">Fun Menu</a><br/>";
  */
  echo "<div class=\"section border_top\">";
$status = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_shouts"));
echo "<div class=\"section_title\"><div class=\"marker\"><a href=\"portal.php?Statusi\">Statusi</a> ($status[0])</div></div></div>";
    // ////////////////////////////////////////
    include ("portal/all.php"); 
    // ////////////////////////////////////////
    include ("portal/mod.php"); 
    // ////////////////////////////////////////
    include ("portal/admin.php"); 
    // ////////////////////////////////////////
    echo "<div class=\"section border_top\">";
    echo "<div class=\"section_title\"><div class=\"marker\">Da li se poznajete?</div></div>";

    $sql = mysql_fetch_array(mysql_query("SELECT id, name FROM fun_users ORDER BY RAND()  LIMIT 1"));

    $avlink = getavatar($sql[0]);
    if ($avlink != "") {
        $avatar = "<img src=\"$avlink\" class=\"p\" height=\"25\" width=\"25\" alt=\"avatar\"/>";
    } else {
        $avatar = "<img src=\"images/nopic.jpg\" class=\"p\" height=\"25\" width=\"25\" alt=\"avatar\"/>";
    } 
    echo "<table border='0' width='100%' id='download'><tr><td align='center' width='1' height='1'>$avatar</td><td><a href=\"index.php?viewuser&amp;who=$sql[0]\"><b>$sql[1]</b></a><br />";
    echo rating($sql[0]);
    echo "</td></tr></table></div>";
    echo dnoonline($sid, $uid);
    exit();
} 
// //////////////////////////////////Forumi

else if (isset($_GET['Forumi'])) {
    addonline(getuid_sid($sid), "Forumi!", "");
    echo vrhonline($sid, $uid);
    echo "<div class=\"titl\"><b>Random tema:</b></div>";
    $lpt = mysql_fetch_array(mysql_query("SELECT id, name FROM fun_topics ORDER BY RAND() LIMIT 1"));
    $tlnm = htmlspecialchars($lpt[1]);
    $tpclnk = "<a
href=\"index.php?viewtpc&amp;tid=$lpt[0]&amp;go=last\">$tlnm</a>";
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
    $tpclnk = "<a href=\"index.php?viewtpc&amp;tid=$lpt[0]&amp;go=last\">$tlnm</a>";
    $vulnk = "<br/><a href=\"index.php?viewuser&amp;who=$tluid\">$tlnick</a><br/>";

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
    $tpclnk = "<a href=\"index.php?viewtpc&amp;tid=$lpt[0]&amp;go=last\">$tlnm</a>";
    $vulnk = "<a href=\"index.php?viewuser&amp;who=$tluid\">$tlnick</a>";
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
    $tpclnk = "<a href=\"index.php?viewtpc&amp;tid=$lpt[0]&amp;go=last\">$tlnm</a>";
    $vulnk = "<a href=\"index.php?viewuser&amp;who=$tluid\">$tlnick</a>";
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
    $tpclnk = "<a href=\"index.php?viewtpc&amp;tid=$lpt[0]&amp;go=last\">$tlnm</a>";
    $vulnk = "<a href=\"index.php?viewuser&amp;who=$tluid\">$tlnick</a>";
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
    $tpclnk = "<a href=\"index.php?viewtpc&amp;tid=$lpt[0]&amp;go=last\">$tlnm</a>";
    $vulnk = "<a href=\"index.php?viewuser&amp;who=$tluid\">$tlnick</a>";
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
    $tpclnk = "<a href=\"index.php?viewtpc&amp;tid=$lpt[0]&amp;go=last\">$tlnm</a>";
    $vulnk = "<a href=\"index.php?viewuser&amp;who=$tluid\">$tlnick</a>";
    echo "<div class=\"comment\"><div class=\"comm_adv\"><small>5: $tpclnk</small></div></div>";
    echo "<small><div class=\"titlz\"><a href=\"index.php?teme\">Najnovije teme</a></div>";
    echo "<div class=\"titlz\"><a href=\"index.php?postovi\">Najnoviji postovi</a></div></small>"; 
    // /////////////////////////////////////////////////////////////////////////////
    $fcats = mysql_query("SELECT id, name FROM fun_fcats ORDER BY position, id");
    while ($fcat = mysql_fetch_array($fcats)) {
        $topics = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_topics a INNER JOIN fun_forums b ON a.fid = b.id WHERE b.cid='" . $fcat[0] . "'"));
        $posts = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_posts a INNER JOIN fun_topics b ON a.tid = b.id INNER JOIN fun_forums c ON b.fid = c.id WHERE c.cid='" . $fcat[0] . "'"));
        $catlink = "<div class=\"sett_line\"><a href=\"index.php?viewcat&amp;cid=$fcat[0]\">$fcat[1]</a><br /> Otvorenih tema: <div class=\"titl\">$topics[0]</div> <br /> Napisanih poruka: <div class=\"titl\">$posts[0]</div></div>";
        $notp = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_topics WHERE fid='" . $forum[0] . "'")); 
        // echo "<div class=\"border_top_light\"><div class=\"qn section\" id=\"anchor_fbid_310948112165\"><div class=\"is\">";
        echo "$catlink"; 
        // echo "</div></div></div>";
        $forums = mysql_query("SELECT id, name FROM fun_forums WHERE cid='" . $fcat[0] . "' AND clubid='0' ORDER BY position, id, name");
        if (getfview() == 0) {
            echo "<br/><small>";
            while ($forum = mysql_fetch_array($forums)) {
                if (canaccess(getuid_sid($sid), $forum[0])) {
                    echo "$iml <a href=\"index.php?viewfrm&amp;fid=$forum[0]\">$forum[1]</a> ";
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

    $notc = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_topics"));
    $nops = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_posts"));
    echo "<div class='titlz'>Otvoreno tema: <b>$notc[0]</b> - Ispisano poruka: <b>$nops[0]</b></div>";
    if (isadmin(getuid_sid($sid))) {
        echo "<div class=\"border_top_light\"><div class=\"qn section\" id=\"anchor_fbid_310948112165\"><div class=\"is\">";
        echo "<form action=\"admproc.php?addcat\" method=\"post\">";
        echo "<input name=\"fcname\" maxlength=\"30\"/>";
        echo "<input name=\"fcpos\" format=\"*N\" size=\"3\"  maxlength=\"3\"/>";
        echo "<input type=\"submit\" class ='button' value=\"Dodaj kategoriju\"/>";
        echo "</form>";
        echo "</div></div></div>";
    } 
    echo dnoonline($sid, $uid);
    exit();
} else if (isset($_GET['caskanje'])) {
    echo vrhonline($sid, $uid);
echo "<div class=\"section border_top\">";
$onbuds = getonbuds($uid);
echo "<div class=\"section_title\"><div class=\"marker\"><a href=\"lists.php?buds\">Caskanje ($onbuds)</a></div></div>";
echo "<div class=\"adv\"><small>"; 
// Chat
$rooms = mysql_query("SELECT id, name, perms, mage, chposts FROM fun_rooms WHERE static='1' AND clubid='0'");
while ($room = mysql_fetch_array($rooms)) {
    if (canenter($room[0], $sid)) {
        $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_chonline WHERE rid='" . $room[0] . "'"));
        echo "<a href=\"chat.php?sid=$sid&amp;rid=$room[0]\">$room[1]($noi[0])</a><br/>";
    } 
} 
echo "<a href=\"index.php?uchat\">Privatno caskanje</a>";

echo "</small></div></div>";
    echo dnoonline($sid, $uid);
	exit();
}else if (isset($_GET['teme'])) {
    echo vrhonline($sid, $uid);
    $sql = "SELECT id, name, crdate FROM fun_topics ORDER BY id DESC LIMIT 0 , 20";

    $items = mysql_query($sql);
    echo mysql_error();
    if (mysql_num_rows($items) > 0) {
        while ($item = mysql_fetch_array($items)) {
            if (canaccess(getuid_sid($sid), getfid_tid($item[0]))) {
                echo "<div class=\"sett_line\"><a href=\"index.php?viewtpc&amp;tid=$item[0]&amp;go=last\">" . htmlspecialchars($item[1]) . "</a> <small>" . date("d m y-H:i:s", $item[2]) . "</small></div>";
            } 
        } 
    } 
    echo dnoonline($sid, $uid);
    exit();
} else if (isset($_GET['postovi'])) {
    echo vrhonline($sid, $uid); 
    
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
        $c = "<a href=\"index.php?viewtpc&amp;tid=$item[2]&amp;go=last\">$a</a>";
        $d = "<a href=\"index.php?viewuser&amp;who=$item[1]\">$b</a>";
        echo "<div class=\"sett_line\">$c Od $d</div>";
    } 
    
    echo dnoonline($sid, $uid);
    exit();
} 
// /////////////////////////

// /////////////////////////////////Control Panel
else if (isset($_GET['cpanel'])) {
    addonline(getuid_sid($sid), "User Control Panel", "");

    echo vrhonline($sid, $uid);
    echo "<p align=\"center\">";
    echo "<img src=\"images/cpanel.gif\" alt=\"CPanel\"/><br/>";
    echo "<b>Control Panel</b>";
    echo "</p>";
    echo "<p>";
    $tmsg = getpmcount(getuid_sid($sid));
    $umsg = getunreadpm(getuid_sid($sid));
    echo "<a href=\"inbox.php?main\">&#187;inbox($umsg/$tmsg)</a><br/>";
    $uid = getuid_sid($sid); 
    // $new_gm = getnewgml($uid);
    echo "<a href=\"index.php?rwidc\">&#187;foggys world! ID card.</a><br/>";
    echo "<a href=\"index.php?myclub\">&#187;My Clubs</a><br/>";
    echo "<a href=\"index.php?viewuser&amp;who=$uid\">&#187;Profile</a><br/>";
    echo "<a href=\"index.php?uset\">&#187;Settings</a><br/>";
    echo "<a href=\"index.php?uxset\">&#187;Extended Settings</a><br/>";

    $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_vault WHERE uid='" . $uid . "'"));
    echo "<a href=\"lists.php?vault&amp;who=$uid\">&#187;My Vault($noi[0])</a><br/>";
    $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_ignore WHERE name='" . $uid . "'"));
    echo "<a href=\"lists.php?ignl\">&#187;Ignore List($noi[0])</a><br/>";
    $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_gbook WHERE gbowner='" . $uid . "'"));
    echo "<a href=\"lists.php?gbook&amp;who=$uid\">&#187;Guestbook($noi[0])</a><br/>";
    echo "<a href=\"index.php?poll\">&#187;My Poll</a><br/>";
    $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_blogs WHERE bowner='" . $uid . "'"));
    echo "<a href=\"lists.php?blogs&amp;who=$uid\">&#187;Blogs($noi[0])</a><br/>";
    echo "<a href=\"lists.php?chmood\">&#187;Chatmood</a><br/>"; 
    // echo "<a href=\"status.php?status\">&#187;Status</a><br/>";
    echo "<a href=\"lists.php?avatars\">&#187;Avatars</a><br/>";

    echo "<a href=\"lists.php?ecards\">&#187;E-Cards</a><br/>";
    echo "<a href=\"lists.php?bbcode\">&#187;BBCode</a><br/>";
    echo "<a href=\"lists.php?faqs\">&#187;F.A.Qs&#171;</a><br/>";

    echo "</p>";

    echo dnoonline($sid, $uid);
    exit();
} 
// /////////////////////////////////Control Panel
else if (isset($_GET['rwidc'])) {
    addonline(getuid_sid($sid), "My  ID", "");

    echo vrhonline($sid, $uid);
    echo "<p align=\"center\">";
    echo "<b>foggysworld! ID card</b><br/>";
    $uid = getuid_sid($sid);
    echo "<img src=\"rwidc.php?id=$uid\" alt=\"ll id\"/><br/><br/>";
    echo "This ID card is updated automatically everytime someone request it, the source to your card is http://foggysworld.wapcodes.co.uk/rwidc.php?id=$uid<br/><br/>";
    echo "you can use it as an avatar in other sites<br/><br/>";
    echo "To look at others cards view the user profile then go to more information&gt;ID card.";
    echo "</p>";
    echo dnoonline($sid, $uid);
    exit();
} 

// /////////////////////////////////Search
else if (isset($_GET['search'])) {
    addonline(getuid_sid($sid), "Searching For Something", "");

    echo vrhonline($sid, $uid);
    echo "<p align=\"center\">";
    echo "<img src=\"images/search.gif\" alt=\"*\"/><br/>";
    echo "<b>Search Menu</b>";
    echo "</p>";
    echo "<p>";
    echo "<a href=\"search.php?tpc\">&#0187;In Topics</a><br/>";
    echo "<a href=\"search.php?blg\">&#0187;In Blogs</a><br/>";
    echo "<a href=\"search.php?nbx\">&#0187;In My Inbox</a><br/>";
    echo "<a href=\"search.php?clb\">&#0187;In Clubs</a><br/><br/>";
    echo "Find Members:<br/>";
    echo "<a href=\"search.php?mbrn\">&#0187;In Nicknames</a><br/>"; 
    // echo "<a href=\"search.php?mbrl\">&#0187;In Location</a><br/>";
    // echo "<a href=\"search.php?mbrs\">&#0187;By sex orientation</a><br/>";
    echo "More search options for members are to come<br/>";
    echo "<br/>or you can just type the nickname of the member and view its profile<br/>";

    echo "<form action=\"index.php?viewuser\" method=\"post\">";
    echo "<br/>Nickname <input name=\"mnick\" maxlength=\"15\"/><br/>";
    echo "<input type=\"submit\" value=\"Profile\"/>";
    echo "</form>";

    echo "</p>";

    echo dnoonline($sid, $uid);
    exit();
} 
// /////////////////////////////////Settings
else if (isset($_GET['igraonica'])) {
    echo vrhonline($sid, $uid);
	echo '<iframe src="http://www.come2play.com/appGame/AllGames.aspx?channel_id=176667" width="762" height="1230" frameborder="0" scrolling="no" allowtransparency="true" ></iframe>';
    echo dnoonline($sid, $uid);
    exit();
}
else if (isset($_GET['uset'])) {
    addonline(getuid_sid($sid), "User Settings", "");

    echo vrhonline($sid, $uid);
    echo "<onevent type=\"onenterforward\">";
    $uid = getuid_sid($sid);
    $avat = getavatar($uid);
    $email = mysql_fetch_array(mysql_query("SELECT email FROM fun_users WHERE id='" . $uid . "'"));
    $site = mysql_fetch_array(mysql_query("SELECT site FROM fun_users WHERE id='" . $uid . "'"));
    $bdy = mysql_fetch_array(mysql_query("SELECT birthday FROM fun_users WHERE id='" . $uid . "'"));
    $uloc = mysql_fetch_array(mysql_query("SELECT location FROM fun_users WHERE id='" . $uid . "'"));
    $usig = mysql_fetch_array(mysql_query("SELECT signature FROM fun_users WHERE id='" . $uid . "'"));
    $sx = mysql_fetch_array(mysql_query("SELECT sex FROM fun_users WHERE id='" . $uid . "'"));
    $uloc[0] = htmlspecialchars($uloc[0]);

    echo "<p align=\"center\">";
    echo "<b>Settings</b>";
    echo "</p>";
    echo "<p>";
    echo "<form action=\"genproc.php?uprof\" method=\"post\">";
    echo "Avatar: <input name=\"savat\" maxlength=\"100\" value=\"$avat\"/><br/>";
    echo "E-Mail: <input name=\"semail\" maxlength=\"100\" value=\"$email[0]\"/><br/>";
    echo "Site: <input name=\"usite\" maxlength=\"100\" value=\"$site[0]\"/><br/>";
    echo "Birthday(YYYY-MM-DD): <input name=\"ubday\" maxlength=\"50\" value=\"$bdy[0]\"/><br/>";
    echo "Location: <input name=\"uloc\" maxlength=\"50\" value=\"$uloc[0]\"/><br/>";
    echo "Signature: <input name=\"usig\" maxlength=\"100\" value=\"$usig[0]\"/><br/>";
    echo "Sex: <select name=\"usex\" value=\"$sx[0]\">";
    echo "<option value=\"M\">Male</option>";
    echo "<option value=\"F\">Female</option>";
    echo "</select><br/>";
    echo "<input type=\"submit\" value=\"Update\"/>";
    echo "</form>";
    echo "<br/><br/>";
    $sml = mysql_fetch_array(mysql_query("SELECT hvia FROM fun_users WHERE id='" . getuid_sid($sid) . "'"));
    if ($sml[0] == "1") {
        echo "<a href=\"genproc.php?shsml&amp;act=dis\">Disable Smilies</a>";
    } else {
        echo "<a href=\"genproc.php?shsml&amp;act=enb\">Enable Smilies</a>";
    } 
    echo "<br/><br/>";
    echo "<form action=\"genproc.php?upwd\" method=\"post\">";
    echo "Password: <input type=\"password\" name=\"npwd\" format=\"*x\" maxlength=\"15\"/><br/>";
    echo "Password again: <input type=\"password\" name=\"cpwd\" format=\"*x\" maxlength=\"15\"/><br/>";
    echo "<input type=\"submit\" value=\"Change\"/>";
    echo "</form>";

    echo "</p>";

    echo dnoonline($sid, $uid);
    exit();
} 
// /////////////////////////////////Poll Topic
else if (isset($_GET['poll'])) {
    addonline(getuid_sid($sid), "Administrating Poll", "");

    echo vrhonline($sid, $uid);
    echo "<p>";
    $uid = getuid_sid($sid);
    if (getplusses($uid) < 50) {
        echo "Minimum plusses required to administrate your poll is 50 plusses";
    } else {
        $pid = mysql_fetch_array(mysql_query("SELECT pollid FROM fun_users WHERE id='" . $uid . "'"));
        if ($pid[0] == 0) {
            echo "<a href=\"index.php?crpoll\">Create Poll</a>";
        } else {
            echo "<a href=\"index.php?viewpl&amp;who=$uid\">View Your Poll</a><br/>";
            echo "<a href=\"genproc.php?dlpoll\">Delete Your Poll</a><br/>";
        } 
    } 
    echo "</p>";

    echo "<p align=\"center\">";

    echo dnoonline($sid, $uid);
    exit();
    echo "</p>";
} else if (isset($_GET['crpoll'])) {
    addonline(getuid_sid($sid), "Creating A New Poll", "");

    echo vrhonline($sid, $uid);
    echo "<p>";
    if (getplusses(getuid_sid($sid)) >= 50) {
        $pid = mysql_fetch_array(mysql_query("SELECT pollid FROM fun_users WHERE id='" . $uid . "'"));
        if ($pid[0] == 0) {
            echo "<form action=\"genproc.php?crpoll\" method=\"post\">";

            echo "Question:<input name=\"pques\" maxlength=\"250\"/><br/>";
            echo "Opcija 1:<input name=\"opt1\" maxlength=\"100\"/><br/>";
            echo "Opcija 2:<input name=\"opt2\" maxlength=\"100\"/><br/>";
            echo "Opcija 3:<input name=\"opt3\" maxlength=\"100\"/><br/>";
            echo "Opcija 4:<input name=\"opt4\" maxlength=\"100\"/><br/>";
            echo "Opcija 5:<input name=\"opt5\" maxlength=\"100\"/><br/>";
            echo "<input type=\"submit\" value=\"Create\"/>";
            echo "</form>";
        } else {
            echo "<img src=\"images/notok.gif\" alt=\"x\"/>You already have a poll, delete your current one before adding a new one";
        } 
    } else {
        echo "<img src=\"images/notok.gif\" alt=\"x\"/>You should have at least 50 plusses to create a poll";
    } 
    echo "</p>";

    echo dnoonline($sid, $uid);
    exit();
} else if (isset($_GET['stats'])) {
    addonline(getuid_sid($sid), "Site stats", "");

    echo vrhonline($sid, $uid);
    echo "<p align=\"center\">";
    $norm = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_users"));
    echo "";
    echo "Registered Members: <b>$norm[0]</b> ";
    $memid = mysql_fetch_array(mysql_query("SELECT id, name  FROM fun_users ORDER BY regdate DESC LIMIT 0,1"));
    echo "The Newsest Member is: <b><a href=\"index.php?viewuser&amp;who=$memid[0]\">$memid[1]</a></b><br/>";
    $mols = mysql_fetch_array(mysql_query("SELECT name, value FROM fun_settings WHERE id='2'"));
    echo "Most Users Online: <b>$mols[1]</b> Members on $mols[0]<br/>";
    $mols = mysql_fetch_array(mysql_query("SELECT ppl, dtm FROM fun_mpot WHERE ddt='" . date("d m y") . "'"));
    echo "Most Users Online(<a href=\"lists.php?moto\"> For today only</a>): <b>$mols[0]</b> Members at $mols[1]<br/>";
    $tm24 = time() - (24 * 60 * 60);
    $aut = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_users WHERE lastact>'" . $tm24 . "'"));
    echo mysql_error();
    echo "Active users today <b>$aut[0]</b><br/>";
    $notc = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_topics"));
    $nops = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_posts"));
    echo "Number of Topics: <b>$notc[0]</b> - Number of Posts: <b>$nops[0]</b><br/>";
    $nopm = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_private"));
    echo "Number of PMs: <b>$nopm[0]</b><br/>";
    $nopm = mysql_fetch_array(mysql_query("SELECT value FROM fun_settings WHERE name='Counter'"));
    echo "Counter: <b>$nopm[0]</b>";
    echo "";
    echo "</p>";
    echo "<p>";
    echo ""; 
    // ///
    echo "<a href=\"index.php?l24\">&#187;Whats Happened Here In Last 24 Hours</a><br/>";
    echo "<a href=\"lists.php?members\">&#187;Members($norm[0])</a><br/>";
    $norm = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_users WHERE sex='M'"));

    echo "<a href=\"lists.php?males\">-&#187;Males($norm[0])</a><br/>";
    $norm = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_users WHERE sex='F'"));
    echo "<a href=\"lists.php?fems\">-&#187;Females($norm[0])</a><br/>";

    $tbday = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_users where month(`birthday`) = month(curdate()) and dayofmonth(`birthday`) = dayofmonth(curdate());"));
    echo "<a href=\"lists.php?bdy\">&#187;Today's Birthday($tbday[0])</a><br/>";

    $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_blogs"));
    echo "<a href=\"lists.php?allbl\">&#187;Blogs($noi[0])</a><br/>";
    $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_users WHERE pollid>'0'"));
    echo "<a href=\"lists.php?polls\">&#187;Polls($noi[0])</a><br/>";
    echo "<a href=\"lists.php?topp\">&#187;Top Posters</a><br/>";
    echo "<a href=\"lists.php?tchat\">&#187;Top Chatters</a><br/>";
    echo "<a href=\"lists.php?tgame\">&#187;Top Gamers</a><br/>";
    echo "<a href=\"lists.php?topb\">&#187;Top Battlers</a><br/>";
    echo "<a href=\"lists.php?tshout\">&#187;Top Shouters</a><br/>";
    $nobr = mysql_fetch_array(mysql_query("SELECT COUNT(DISTINCT browserm) FROM fun_users WHERE browserm IS NOT NULL "));
    echo "<a href=\"lists.php?brows\">&#187;Browsers($nobr[0])</a><br/>";
    $noi = mysql_fetch_array(mysql_query("SELECT count(*) FROM fun_users WHERE perm>'0'"));
    echo "<a href=\"lists.php?staff\">&#187;Staff Members($noi[0])</a><br/>";
    $noi = mysql_fetch_array(mysql_query("SELECT count(*) FROM fun_judges"));
    echo "<a href=\"lists.php?judg\">&#187;Battles Judges($noi[0])</a><br/>";
    $noi = mysql_fetch_array(mysql_query("SELECT count(*) FROM fun_penalties WHERE penalty='1' OR penalty='2'"));
    echo "<a href=\"lists.php?banned\">&#187;Banned($noi[0])</a><br/>";
    if (ismod(getuid_sid($sid))) {
        $noi = mysql_fetch_array(mysql_query("SELECT count(*) FROM fun_penalties WHERE penalty='0'"));
        echo "<a href=\"lists.php?trashed\">&#187;Trashed($noi[0])</a><br/>";
        $noi = mysql_fetch_array(mysql_query("SELECT count(*) FROM fun_penalties WHERE penalty='2'")); 
        // echo "<a href=\"lists.php?ipban\">&#187;Banned IPs($noi[0])</a><br/>";
    } 

    echo "</p>";

    echo dnoonline($sid, $uid);
    exit();
} else if (isset($_GET['l24'])) {
    addonline(getuid_sid($sid), "Site stats", "");

    echo vrhonline($sid, $uid);

    echo "<p>";
    echo ""; 
    // ///
    echo "Things that have happened in foggysworld during last 24 hours<br/><br/>";
    $tm24 = time() - (24 * 60 * 60);
    $aut = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_users WHERE lastact>'" . $tm24 . "'"));
    echo "Active Members: <b>$aut[0]</b><br/>";
    $aut = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_users WHERE regdate>'" . $tm24 . "'"));
    echo "Registered Members: <b>$aut[0]</b><br/>";
    $aut = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_blogs WHERE bgdate>'" . $tm24 . "'"));
    echo "Blogs Created: <b>$aut[0]</b><br/>";
    $aut = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_clubmembers WHERE joined>'" . $tm24 . "' AND accepted='1'"));
    echo "Members Joined Clubs: <b>$aut[0]</b><br/>";
    $aut = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_clubs WHERE created>'" . $tm24 . "'"));
    echo "Clubs Created: <b>$aut[0]</b><br/>";
    $aut = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_buddies WHERE reqdt>'" . $tm24 . "' AND agreed='1'"));
    echo "Buddies Added: <b>$aut[0]</b><br/>";
    $aut = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_gbook WHERE dtime>'" . $tm24 . "'"));
    echo "Guestbooks Signed: <b>$aut[0]</b><br/>";
    if (ismod(getuid_sid($sid))) {
        $aut = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_mlog WHERE actdt>'" . $tm24 . "'"));
        echo "ModLog Actions: <b>$aut[0]</b><br/>";
    } 
    $aut = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_polls WHERE pdt>'" . $tm24 . "'"));
    echo "Polls Added: <b>$aut[0]</b><br/>";
    $aut = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_posts WHERE dtpost>'" . $tm24 . "'"));
    echo "Posts: <b>$aut[0]</b><br/>";
    $aut = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_private WHERE timesent>'" . $tm24 . "'"));
    echo "PMs Sent: <b>$aut[0]</b><br/>";
    $aut = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_shouts WHERE shtime>'" . $tm24 . "'"));
    echo "Shouts: <b>$aut[0]</b><br/>";
    $aut = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_topics WHERE crdate>'" . $tm24 . "'"));
    echo "Topics Created: <b>$aut[0]</b><br/>";
    $aut = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_vault WHERE pudt>'" . $tm24 . "'"));
    echo "Vault Items Added: <b>$aut[0]</b><br/>";
    echo "</p>";
    echo "<p align=\"center\">";
    echo "<a href=\"index.php?stats\"><img src=\"images/stat.gif\" alt=\"*\"/>";
    echo "Statistics</a><br/>";
    echo "</p>";
    echo dnoonline($sid, $uid);
    exit();
} else if (isset($_GET['shout'])) {
    addonline(getuid_sid($sid), "Close Your Ears Im Shouting", "");

    echo vrhonline($sid, $uid);

    echo "<p align=\"center\">";
    if (getplusses(getuid_sid($sid)) < 0) {
        echo "You need at least 0 plusses to shout!";
    } else {
        echo "please take note: $nick all your plusses will be removed if you spam or flood the shoutbox<br/>";
        echo "also smilies and BBcode do not work in shoutbox<br/><br/>";
        echo "<form action=\"genproc.php?shout\" method=\"post\">";
        echo "Text:<input name=\"shtxt\" maxlength=\"100\"/><br/>";
        echo "<input class=\"button\" type=\"submit\" valie=\"Posalji\"/>";
        echo "</form>";
    } 
    echo "</p>";
    echo dnoonline($sid, $uid);
    exit();
} 
// ////////////////////////////////////////shout

// ////////////////////////////////////////Guestbook
else if (isset($_GET['addblg'])) {
    if (!getplusses(getuid_sid($sid)) > 0) {
        echo vrhonline($sid, $uid);
        echo "<p align=\"center\">";
        echo "you should have 0 plusses to add a blog<br/><br/>";
        echo "</p>";
        echo dnoonline($sid, $uid);
        exit();
    } 
    addonline(getuid_sid($sid), "Adding a blog", "");

    echo vrhonline($sid, $uid);
    echo "<card id=\"main\" title=\"Add Blog\">";

    echo "<p align=\"center\">";
    echo "<form action=\"genproc.php?addblg\" method=\"post\">";
    echo "Title:<input name=\"btitle\" maxlength=\"30\"/><br/>";
    echo "Text:<input name=\"msgtxt\" maxlength=\"500\"/><br/>";
    echo "<input type=\"submit\" value=\"Add Blog\"/>";
    echo "</form>";

    echo "</p>";
    echo dnoonline($sid, $uid);
    exit();
} 
// ////////////////////////////////////////add vault
else if (isset($_GET['addvlt'])) {
    if (!getplusses(getuid_sid($sid)) > 5) {
        echo vrhonline($sid, $uid);
        echo "<p align=\"center\">";
        echo " you should have 5 plusses to add an item to your vault<br/><br/>";
        echo "</p>";
        echo dnoonline($sid, $uid);
        exit();
    } 
    addonline(getuid_sid($sid), "Adding a vault item", "");

    echo vrhonline($sid, $uid);

    echo "<p align=\"center\">";
    echo "<form action=\"genproc.php?addvlt\" method=\"post\">";
    echo "The vault is used to store your downloadable links,like images, mp3's, games  etc...<br/>WARNING: if you used it to wapsites links they will got deleted and you'll lose some plusses, if you used it for ranking sites links like 2wap, mradar, you'll be banned<br/><br/>";
    echo "Item Name:<input name=\"viname\" maxlength=\"50\"/><br/>";
    echo "Item Url:<input name=\"vilink\" maxlength=\"255\"/><br/>";
    echo "<input type=\"submit\" value=\"Add Item\"/>";
    echo "</form>";
    echo "</p>";
    echo dnoonline($sid, $uid);
    exit();
} 
// ////////////////////////////////////////Guestbook
else if (isset($_GET['signgb'])) {
    $who = $_GET["who"];
    addonline(getuid_sid($sid), "Signing Users guestbook", "");
    echo vrhonline($sid, $uid);
    if (!cansigngb(getuid_sid($sid), $who)) {
        echo vrhonline($sid, $uid);
        echo "<p align=\"center\">";
        echo "You cant Sign this user guestbook<br/><br/>";
        echo "</p>";
        echo dnoonline($sid, $uid);
        exit();
    } 

    echo "<p align=\"center\">";
    echo "<form action=\"genproc.php?signgb\" method=\"post\">";
    echo "Text:<input name=\"msgtxt\" maxlength=\"500\"/><br/>";
    echo "<input type=\"submit\" value=\"GO\"/>";
    echo "</form>";
    echo "</p>";

    echo dnoonline($sid, $uid);
    exit();
} else if (isset($_GET['online'])) {
    addonline(getuid_sid($sid), "Online List", "");

    echo vrhonline($sid, $uid); 
    // ////ALL LISTS SCRIPT <<
    if ($page == "" || $page <= 0)$page = 1;
    $num_items = getnumonline(); //changable
    $items_per_page = 10;
    $num_pages = ceil($num_items / $items_per_page);
    if ($page > $num_pages)$page = $num_pages;
    $limit_start = ($page-1) * $items_per_page; 
    // changable sql
    $sql = "SELECT
            a.name, b.place, b.userid FROM fun_users a
            INNER JOIN fun_online b ON a.id = b.userid
            GROUP BY 1,2
            LIMIT $limit_start, $items_per_page
    ";
    if ($page > 1) {
        $ppage = $page-1;
        echo "<div class='center section border_top'><a href=\"index.php?online&amp;page=$ppage\"><img src='images/up.png' /></a></div>";
    } 
    $items = mysql_query($sql);
    echo mysql_error();
    while ($item = mysql_fetch_array($items)) {
        $avlink = getavatar($item[2]);
        if ($avlink != "") {
            $avatar = "<img src=\"$avlink\" class=\"p\" height=\"25\" width=\"25\" alt=\"avatar\"/>";
        } else {
            $avatar = "<img src=\"images/nopic.jpg\" class=\"p\" height=\"25\" width=\"25\" alt=\"avatar\"/>";
        } 
        $lnk = "<a href=\"index.php?viewuser&amp;who=$item[2]\">$item[0]</a>";
        echo "<div class='border_bottom_light'><table border='0' width='100%' id='download'><tr><td align='left' width='1' height='1'>$avatar</td><td>$lnk<br />";
        echo rating($item[2]);
        echo "</td></tr></table></div>";
    } 
    if ($page < $num_pages) {
        $npage = $page + 1;
        echo "<div class='center section border_bottom'><a href=\"index.php?online&amp;page=$npage\"><img src='images/down.png'></a></div>";
    } 
    // //// UNTILL HERE >>
    echo dnoonline($sid, $uid);
    exit();
} else if (isset($_GET['viewpl'])) {
    $who = $_GET["who"];
    addonline(getuid_sid($sid), "Viewing A Users Poll", "");

    echo vrhonline($sid, $uid);
    echo "<p>";
    $uid = getuid_sid($sid);
    $pollid = mysql_fetch_array(mysql_query("SELECT pollid FROM fun_users WHERE id='" . $who . "'"));
    if ($pollid[0] > 0) {
        $polli = mysql_fetch_array(mysql_query("SELECT id, pqst, opt1, opt2, opt3, opt4, opt5, pdt FROM fun_polls WHERE id='" . $pollid[0] . "'"));
        if (trim($polli[1]) != "") {
            $qst = parsepm($polli[1], $sid);
            echo $qst . "<br/><br/>";
            $vdone = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_presults WHERE uid='" . $uid . "' AND pid='" . $pollid[0] . "'"));
            $nov = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_presults WHERE pid='" . $pollid[0] . "'"));
            $nov = $nov[0];
            if ($vdone[0] > 0) {
                $voted = true;
            } else {
                $voted = false;
            } 
            $opt1 = $polli[2];
            if (trim($opt1) != "") {
                $opt1 = htmlspecialchars($opt1);
                $nov1 = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_presults WHERE pid='" . $pollid[0] . "' AND ans='1'"));
                $nov1 = $nov1[0];
                if ($nov > 0) {
                    $per = floor(($nov1 / $nov) * 100);
                    $rests = "Glasova: $nov1($per%)";
                } else {
                    $rests = "Glasova: 0(0%)";
                } 
                if ($voted) {
                    $lnk = "1.$opt1 $rests<br/>";
                } else {
                    $lnk = "1.<a href=\"genproc.php?votepl&amp;plid=$pollid[0]&amp;ans=1\">$opt1</a> $rests<br/>";
                } 
                echo "$lnk";
            } 
            $opt2 = $polli[3];
            if (trim($opt2) != "") {
                $opt2 = htmlspecialchars($opt2);
                $nov2 = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_presults WHERE pid='" . $pollid[0] . "' AND ans='2'"));
                $nov2 = $nov2[0];
                if ($nov > 0) {
                    $per = floor(($nov2 / $nov) * 100);
                    $rests = "Glasova: $nov2($per%)";
                } else {
                    $rests = "Glasova: 0(0%)";
                } 
                if ($voted) {
                    $lnk = "2.$opt2 $rests<br/>";
                } else {
                    $lnk = "2.<a href=\"genproc.php?votepl&amp;plid=$pollid[0]&amp;ans=2\">$opt2</a> $rests<br/>";
                } 
                echo "$lnk";
            } 
            $opt3 = $polli[4];
            if (trim($opt3) != "") {
                $opt3 = htmlspecialchars($opt3);
                $nov3 = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_presults WHERE pid='" . $pollid[0] . "' AND ans='3'"));
                $nov3 = $nov3[0];
                if ($nov > 0) {
                    $per = floor(($nov3 / $nov) * 100);
                    $rests = "Glasova: $nov3($per%)";
                } else {
                    $rests = "Glasova: 0(0%)";
                } 
                if ($voted) {
                    $lnk = "3.$opt3 $rests<br/>";
                } else {
                    $lnk = "3.<a href=\"genproc.php?votepl&amp;plid=$pollid[0]&amp;ans=3\">$opt3</a> $rests<br/>";
                } 
                echo "$lnk";
            } 
            $opt4 = $polli[5];
            if (trim($opt4) != "") {
                $opt4 = htmlspecialchars($opt4);
                $nov4 = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_presults WHERE pid='" . $pollid[0] . "' AND ans='4'"));
                $nov4 = $nov4[0];
                if ($nov > 0) {
                    $per = floor(($nov4 / $nov) * 100);
                    $rests = "Glasova: $nov4($per%)";
                } else {
                    $rests = "Glasova: 0(0%)";
                } 
                if ($voted) {
                    $lnk = "4.$opt4 $rests<br/>";
                } else {
                    $lnk = "4.<a href=\"genproc.php?votepl&amp;plid=$pollid[0]&amp;ans=4\">$opt4</a> $rests<br/>";
                } 
                echo "$lnk";
            } 
            $opt5 = $polli[6];
            if (trim($opt5) != "") {
                $opt5 = htmlspecialchars($opt5);
                $nov5 = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_presults WHERE pid='" . $pollid[0] . "' AND ans='5'"));
                $nov5 = $nov5[0];
                if ($nov > 0) {
                    $per = floor(($nov5 / $nov) * 100);
                    $rests = "Glasova: $nov5($per%)";
                } else {
                    $rests = "Glasova: 0(0%)";
                } 
                if ($voted) {
                    $lnk = "5.$opt5 $rests<br/>";
                } else {
                    $lnk = "5.<a href=\"genproc.php?votepl&amp;plid=$pollid[0]&amp;ans=5\">$opt5</a> $rests<br/>";
                } 
                echo "$lnk";
            } 
            echo "" . date("d m y - H:i", $polli[7]) . "";
        } else {
            echo "<img src=\"images/notok.gif\" alt=\"x\"/>This poll doesn't exist";
        } 
    } else {
        echo "<img src=\"images/notok.gif\" alt=\"x\"/>This user have no poll";
    } 
    echo "</p>";
    echo dnoonline($sid, $uid);
    exit();
} else if (isset($_GET['stfol'])) {
    addonline(getuid_sid($sid), "Wheres The Staff", "");

    echo vrhonline($sid, $uid); 
    // ////ALL LISTS SCRIPT <<
    if ($page == "" || $page <= 0)$page = 1;
    $timeout = 180;
    $timeon = time() - $timeout;
    $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_users WHERE perm>'0' AND lastact>'" . $timeon . "'"));
    $num_items = $noi[0]; //changable
    $items_per_page = 10;
    $num_pages = ceil($num_items / $items_per_page);
    if ($page > $num_pages)$page = $num_pages;
    $limit_start = ($page-1) * $items_per_page;
    if ($limit_start < 0)$limit_start = 0; 
    // changable sql
    $sql = "
    SELECT name, perm, id FROM fun_users WHERE perm>'0' AND lastact>'" . $timeon . "'
            LIMIT $limit_start, $items_per_page
    ";
    echo "<p>";
    $items = mysql_query($sql);
    echo mysql_error();
    while ($item = mysql_fetch_array($items)) {
        $lnk = "<a href=\"index.php?viewuser&amp;who=$item[2]\">$item[0]</a>";
        if ($item[1] == 1) {
            $item[1] = "Ass Admin";
        } else if ($item[1] == 2) {
            $item[1] = "owner";
        } 
        echo "$lnk - $item[1] <br/>";
    } 
    echo "</p>";
    echo "<p align=\"center\">";
    if ($page > 1) {
        $ppage = $page-1;
        echo "<a href=\"index.php?$action&amp;page=$ppage\">&#171;PREV</a> ";
    } 
    if ($page < $num_pages) {
        $npage = $page + 1;
        echo "<a href=\"index.php?$action&amp;page=$npage\">Next&#187;</a>";
    } 
    echo "<br/>$page/$num_pages<br/>";
    if ($num_pages > 2) {
        echo getjumper($action, $sid, "index");
    } 
    echo "</p>"; 
    // //// UNTILL HERE >>
    echo dnoonline($sid, $uid);
    exit();
} else if (isset($_GET['chbmsg'])) {
    addonline(getuid_sid($sid), "Buddy Message", "");

    echo vrhonline($sid, $uid);

    $cmsg = htmlspecialchars(getbudmsg(getuid_sid($sid)));

    echo "<form action=\"genproc.php?upbmsg\" method=\"post\">";

    echo "Text:<input name=\"bmsg\" maxlength=\"100\" value=\"$cmsg\"/><br/>";
    echo "<input type=\"submit\" value=\"GO\"/>";
    echo "</form><br/>";
    echo "<a href=\"lists.php?buds\">";
    echo "Lista prijatelja</a><br/>";
    echo "</p>";
    echo dnoonline($sid, $uid);
    exit();
} 
// ///////////////////////////////viewuser profile
else if (isset($_GET['viewuser'])) {
    $ko = $_POST["who"];
    $msgtxt = $_POST["msgtxt"];
    $todo = $_GET["todo"];
    addonline(getuid_sid($sid), "Korisnicki profil", "index.php?viewuser&amp;who=$who");

    echo vrhonline($sid, $uid);
    if ($who == "" || $who == 0) {
        $mnick = $_POST["mnick"];
        $who = getuid_nick($mnick);
    } 
    $whonick = getnick_uid($who);

    if ($whonick != "") {
        // //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        if ($msgtxt == "") {
            echo "";
        } else {
            $msgtxt = $msgtxt; 
            // $uid = getuid_sid($sid);
            $shtm = time();
            $res = mysql_query("INSERT INTO fun_gbook SET gbowner='" . $who . "', gbsigner='" . $uid . "', dtime='" . $shtm . "', gbmsg='" . $msgtxt . "'");
            if ($res) {
                $shts = mysql_fetch_array(mysql_query("SELECT shouts from fun_users WHERE id='" . $uid . "'"));
                $shts = $shts[0] + 1;
                mysql_query("UPDATE fun_users SET shouts='" . $shts . "' WHERE id='" . $uid . "'");
                echo "<div class=\"notif border_bottom\">Vasa poruka je uspesno postavljena</div>"; 
                // //
                $zzz = getnick_uid($uid);
                $ppp = mysql_query("SELECT id FROM fun_users WHERE name='" . $who . "'");
                $zzz = getnick_uid($uid);

                $msg = "[user=" . $uid . "]" . $zzz . "[/user] je pisao na vasem [user=" . $who . "]zid/u[/user].";
                mysql_query("INSERT INTO fun_notify SET text='" . $msg . "', byuid='" . $uid . "', touid='" . $who . "', unread='1', timesent='" . time() . "'"); 
                // //
            } else {
                echo "<div class=\"notif border_bottom\">Vasa poruka nije postavljena! Mogucnost da je greska u bazi, pa vas molimo da pokusate kasnije!</div>";
            } 
        } 
		if ($_GET["poke"]) {
		$pk = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_poke WHERE pid='".$uid."' AND uid='".$who."'"));
		if ($pk[0] == 0) {
		if ($_GET["poke"] == 1) {
		$poke = getnick_uid($_GET["p"]);
	echo "<div class='pad'><div class='notif border_bottom'>Uspesno ste bocnuli <b>$poke</b></div></div>";
   mysql_query("INSERT INTO fun_poke SET pid='" . $uid . "', uid='" . $who . "', vreme='".time()."'");
	mysql_query("DELETE FROM fun_poke WHERE uid='" . $uid . "' AND pid='" . $_GET["pid"] . "'");
	}
	} else {
		$poke = getnick_uid($_GET["p"]);
	echo "<div class='pad'><div class='error border_bottom'>$poke jos uvek nije primetio/la vase bockanje</div></div>";
	}
	}
        echo "<div id='holder'>
		<div class='profile_image'>";
        $avlink = getavatar($who);
        if ($avlink) {
            echo "<img src=\"$avlink\" alt='$whonick' width='100' height='100' no_link='1' />";
        } else {
            $nopl = mysql_fetch_array(mysql_query("SELECT sex FROM fun_users WHERE id='" . $who . "'"));
            if ($nopl[0] == 'M') {
                echo "<img src=\"images/nopicboy.gif\" alt='$whonick' width='100' height='100' no_link='1' />";
            } else if ($nopl[0] == 'F') {
                echo "<img src=\"images/nopicgirl.gif\" alt='$whonick' width='100' height='100' no_link='1' />";
            } else {
                echo "<img src=\"images/nopic.jpg\" alt='$whonick' width='100' height='100' no_link='1' />";
            } 
        } 
		 $status = mysql_fetch_array(mysql_query("SELECT shout FROM fun_shouts WHERE shouter='" . $who . "' ORDER BY shtime DESC LIMIT 1"));
		 $status = parsemsg($status[0], $sid);
        echo "</div>
		<div class='profile_full_name'>$whonick</div>
		<div class='profile_status'>$status";
        $nopl = mysql_fetch_array(mysql_query("SELECT sex, birthday, location FROM fun_users WHERE id='" . $who . "'"));
        $uage = getage($nopl[1]);
        if ($nopl[0] == 'M') {
            $usex = "Musko";
        } else if ($nopl[0] == 'F') {
            $usex = "Zensko";
        } else {
            $usex = "Pol nije odredjen.";
        } 
        $nopl[2] = htmlspecialchars($nopl[2]);
        echo "<br />
		<div class='border_top_light'></div><small>Godina: <b>$uage</b><br/>";
        echo "Pol: <b>$usex</b><br/>";
        echo "Prebivaliste: <b>$nopl[2]</b></small><br/>";
        echo rating($who);
		echo "</div>
		<div class='clear border_bottom_light'></div>
		</div>
		<div class'comment'>";

        $uid = getuid_sid($sid);
        if (budres($uid, $who) == 0) {
            echo "<a href=\"genproc.php?bud&amp;who=$who&amp;todo=add\">Dodaj kao prijatelja</a><br />";
        } else if (budres($uid, $who) == 1) {
            echo "<a href=\"genproc.php?bud&amp;who=$who&amp;todo=del\">Izbaci iz prijatelja</a><br />";
        } 

        $ires = ignoreres($uid, $who);
        if ($ires == 2) {
            echo "<a href=\"genproc.php?ign&amp;who=$who&amp;todo=del\">Skini blokadu</a><br />";
        } else if ($ires == 1) {
            echo "<a href=\"genproc.php?ign&amp;who=$who&amp;todo=add\">Blokiraj</a><br />";
        } 
		if ($who == $uid) {
		} else {
		  echo "<a href='index.php?viewuser&amp;poke=1&amp;pid=$who&amp;p=$who&amp;who=$who'>Bocni</a> <br />";
        }
        echo "<a href=\"inbox.php?sendpm&amp;who=$who\">Posalji poruku</a> </div>";
        echo "<div class=\"section sett_line\"><b>Zid</b> 
 | <a href=\"index.php?viewusrmore&amp;who=$who\">Informacije</a> 
 | <a href=\"index.php?fotografije&amp;who=$who\">Fotografije</a> ";
        if (ismod(getuid_sid($sid))) {
            echo "
 | <a href=\"index.php?moduser&amp;who=$who\">Mod panel clana</a>";
        } 
        echo "<form action=\"index.php?viewuser&amp;who=$who\" method=\"post\">";
        echo "<textarea name=\"msgtxt\" rows=\"3\"/></textarea>";
        echo "<input class=\"button\" type=\"submit\" value=\"Posalji\"/>";
        echo "</form>";
        echo "</div>"; 
        // ///////////////////////
        if ($page == "" || $page <= 0)$page = 1;
        $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_gbook WHERE gbowner='" . $who . "'"));
        $num_items = $noi[0]; //changable
        $items_per_page = 10;
        $num_pages = ceil($num_items / $items_per_page);
        if (($page > $num_pages) && $page != 1)$page = $num_pages;
        $limit_start = ($page-1) * $items_per_page;

        $sql = "SELECT gbowner, gbsigner, gbmsg, dtime, id FROM fun_gbook WHERE gbowner='" . $who . "' ORDER BY dtime DESC LIMIT $limit_start, $items_per_page";

        $items = mysql_query($sql);
        echo mysql_error();
        if (mysql_num_rows($items) > 0) {
            while ($item = mysql_fetch_array($items)) {
                $snick = getnick_uid($item[1]);
                $lnk = "<a href=\"index.php?viewuser&amp;who=$item[1]\">$snick</a>";
                $var1 = date("his", $item[3]);
                $var2 = time ();
                $var21 = date("his", $var2);
                $var3 = $var21 - $var1;
                $var4 = date("s", $var3);
                $remain = time() - $item[3];
                $bs = gettimemsg($remain);
                $avlink = getavatar($item[1]);
                if ($avlink != "") {
                    $avatar = "<a href=\"index.php?viewuser&amp;who=$item[1]\" title='$shnick'><img src=\"$avlink\" alt='$shnick' height='35' width='35' /></a>";
                } else {
                    $avatar = "<a href=\"index.php?viewuser&amp;who=$item[1]\" title='$shnick'><img src=\"images/nopic.jpg\" alt='$shnick' height='35' width='35' /></a>";
                } 
                $text = parsepm($item[2], $sid);
                echo "<div class='feed feed_first'>
			<div class='feed_image'>$avatar</div>
			<div class='feed_content'>
			<div> $lnk $text</div>
			<div class='feed_content_info'>
			<span class='feed_time_stamp'><small>$bs</small></span>";
                if (candelgb($uid, $item[4])) {
                    $delnk = "<a href=\"genproc.php?delfgb&amp;mid=$item[4]\"><img src='ico/emblem-unreadable.png' /></a>";
                } 
				if (ismod(getuid_sid($sid))) {
                    $delnk = "<a href=\"genproc.php?delfgb&amp;mid=$item[4]\"><img src='ico/emblem-unreadable.png' /></a>";
                } 
            $brojkomentara = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_komentari_zid WHERE komowner='" . $item[4] . "'"));
            if ($brojkomentara[0] == 1) {
                $komentari = "<small><a href=\"komentari.php?zid&amp;who=$item[4]\"><i>Jedan komentar</i></a></small>";
            } else if ($brojkomentara[0] > 0) {
                $komentara = $brojkomentara[0];
                $komentari = "<small><a href=\"komentari.php?zid&amp;who=$item[4]\"><i><b>$komentara</b> Komentar/a</i></a></small>";
			} else {
                $komentari = "<small><a href=\"komentari.php?zid&amp;who=$item[4]\"><i>Prokomentarisi</i></a></small>";
            } 

                $brl = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_shout_like_zid WHERE shid='" . $item[4] . "' AND liked='1'"));
            $lajk = mysql_fetch_array(mysql_query("SELECT id FROM fun_shout_like_zid WHERE shid= '" . $item[4] . "' AND uid='".$uid."'"));
		
            if ($lajk[0] == "") {
                    $lajkova = "<a href=\"komentari.php?zid&amp;like=$item[4]&amp;liked=1&amp;who=$item[0]\">Svidja mi se</a>";
                } else {
                        $lajkova = "<a href=\"komentari.php?zid&amp;dislike=$item[4]&amp;liked=0&amp;who=$item[4]\">Ne svidja mi se</a>";
                } 
				$k_like = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_shout_like_zid WHERE shid='" . $item[4] . "' AND liked='1'"));
			$k_likez = mysql_fetch_array(mysql_query("SELECT uid FROM fun_shout_like_zid WHERE shid='" . $item[4] . "' AND liked='1' ORDER BY reqdt DESC LIMIT 1"));
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
<span class='user_profile_link_span'><a href='index.php?viewuser&amp;who=$k_likez[0]'>$lajkovaoje</a></span> $lajkovanjes</div>";
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
<span class='user_profile_link_span'><a href='index.php?viewuser&amp;who=$k_likez[0]'>$lajkovaoje</a><span> i jos <a href='lajkovi.php?profilzid&amp;who=$item[4]'>$zaz  prijatelj$koliko</a> $voli ovaj status.</div>";
}else { $likeballon =""; }
                // ///////
                echo "<small><span> &#183; $komentari</span><span> &#183; $lajkova</span>    $delnk";
                echo "$likeballon
			</small>
			</div>
			</div>
			</div>
			<div class='clear border_bottom'></div>
			</div>";
            } 
        } 
		
        if ($page < $num_pages) {
            $npage = $page + 1;
            echo " <a href='index.php?viewuser&amp;page=$npage&amp;who=$who'  class='view_more'>Citaj dalje</a>";
        } 

        $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_blogs WHERE bowner='" . $who . "'"));

        echo "<div class='comment comm_adv'><img src='img/img_44.png' /><a href=\"zapis.php?main&amp;who=$who\">Zapisi ($noi[0])</a></div>";
        // ////////////////////////////////////////////////////////////////
    } else {
        echo "<div class='titl'>Clan nepostoji! Mozda ste pogresno ukucali ime...</div>";
    } 
    echo dnoonline($sid, $uid);
    exit();
} else if (isset($_GET['viewusrmore'])) {
    addonline(getuid_sid($sid), "Viewing Users Profile", "index.php?viewusrmore&amp;who=$who");

    echo vrhonline($sid, $uid);
    if ($who == "" || $who == 0) {
        $mnick = $_POST["mnick"];
        $who = getuid_nick($mnick);
    } 
    $whonick = getnick_uid($who);
    if ($whonick != "") {
         if ($_GET["poke"]) {
		if ($_GET["poke"] == 0){
	echo "<div class='pad'><div class='notif border_bottom'>Bockanje uspesno uklonjeno</div></div>";
	mysql_query("DELETE FROM fun_poke WHERE uid='" . $uid . "' AND pid='" . $_GET["pid"] . "'");
	} else if ($_GET["poke"] == 1) {
		$poke = getnick_uid($_GET["p"]);
	echo "<div class='pad'><div class='notif border_bottom'>Uspesno ste bocnuli <b>$poke</b></div></div>";
   mysql_query("INSERT INTO fun_poke SET pid='" . $uid . "' WHERE uid='" . $_GET["pid"] . "'");
	mysql_query("DELETE FROM fun_poke WHERE uid='" . $uid . "' AND pid='" . $_GET["pid"] . "'");
	} else {
		$poke = getnick_uid($_GET["p"]);
	echo "<div class='pad'><div class='notif border_bottom'><b>$poke</b> jos nije primetio/la vase bockanje</div></div>";
	}
	}
        echo "<div id='holder'>
		<div class='profile_image'>";
        $avlink = getavatar($who);
        if ($avlink) {
            echo "<img src=\"$avlink\" alt='$whonick' width='100' height='100' no_link='1' />";
        } else {
            $nopl = mysql_fetch_array(mysql_query("SELECT sex FROM fun_users WHERE id='" . $who . "'"));
            if ($nopl[0] == 'M') {
                echo "<img src=\"images/nopicboy.gif\" alt='$whonick' width='100' height='100' no_link='1' />";
            } else if ($nopl[0] == 'F') {
                echo "<img src=\"images/nopicgirl.gif\" alt='$whonick' width='100' height='100' no_link='1' />";
            } else {
                echo "<img src=\"images/nopic.jpg\" alt='$whonick' width='100' height='100' no_link='1' />";
            } 
        } 
		 $status = mysql_fetch_array(mysql_query("SELECT shout FROM fun_shouts WHERE shouter='" . $who . "' ORDER BY shtime DESC LIMIT 1"));
		 $status = parsemsg($status[0], $sid);
        echo "</div>
		<div class='profile_full_name'>$whonick</div>
		<div class='profile_status'>$status";
        $nopl = mysql_fetch_array(mysql_query("SELECT sex, birthday, location FROM fun_users WHERE id='" . $who . "'"));
        $uage = getage($nopl[1]);
        if ($nopl[0] == 'M') {
            $usex = "Musko";
        } else if ($nopl[0] == 'F') {
            $usex = "Zensko";
        } else {
            $usex = "Pol nije odredjen.";
        } 
        $nopl[2] = htmlspecialchars($nopl[2]);
        echo "<br />
		<div class='border_top_light'></div><small>Godina: <b>$uage</b><br/>";
        echo "Pol: <b>$usex</b><br/>";
        echo "Prebivaliste: <b>$nopl[2]</b></small><br/>";
        echo rating($who);
		echo "</div>
		<div class='clear border_bottom_light'></div>
		</div>
		<div class'comment'>";

        $uid = getuid_sid($sid);
        if (budres($uid, $who) == 0) {
            echo "<a href=\"genproc.php?bud&amp;who=$who&amp;todo=add\">Dodaj kao prijatelja</a><br />";
        } else if (budres($uid, $who) == 1) {
            echo "<a href=\"genproc.php?bud&amp;who=$who&amp;todo=del\">Izbaci iz prijatelja</a><br />";
        } 

        $ires = ignoreres($uid, $who);
        if ($ires == 2) {
            echo "<a href=\"genproc.php?ign&amp;who=$who&amp;todo=del\">Skini blokadu</a><br />";
        } else if ($ires == 1) {
            echo "<a href=\"genproc.php?ign&amp;who=$who&amp;todo=add\">Blokiraj</a><br />";
        } 
		if ($who == $uid) {
		} else {
		  echo "<a href='index.php?viewuser&amp;poke=1&amp;pid=$who&amp;p=$who&amp;who=$who'>Bocni</a> <br />";
        }
        echo "<a href=\"inbox.php?sendpm&amp;who=$who\">Posalji poruku</a> </div>";
        echo "<div class='sett_line'><a href=\"index.php?viewuser&amp;who=$who\">Zid</a> 
 | <b>Informacije</b> 
 | <a href=\"index.php?fotografije&amp;who=$who\">Fotografije</a> ";
        if (ismod(getuid_sid($sid))) {
            echo "
 | <a href=\"index.php?moduser&amp;who=$who\">Mod panel clana</a>";
        } 
        echo "</div>";
		
            echo "<div class='border_top'><div class=\"section_title\"><div class=\"marker\">Info clana</div></div></div>";

        $nopl = mysql_fetch_array(mysql_query("SELECT sex, birthday, location FROM fun_users WHERE id='" . $who . "'"));
        $uage = getage($nopl[1]);
        if ($nopl[0] == 'M') {
            $usex = "Musko";
        } else if ($nopl[0] == 'F') {
            $usex = "Zensko";
        } else {
            $usex = "Pol nije odredjen.";
        } 
        $nopl[2] = htmlspecialchars($nopl[2]);
        echo "Godina: <b>$uage</b><br/>";
        echo "Pol: <b>$usex</b><br/>";
        echo "Prebivaliste: <b>$nopl[2]</b><br/>";
		
            echo "<div class='border_top'><div class=\"section_title\"><div class=\"marker\">Aktivnost</div></div></div>";
        $unol = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_topics WHERE authorid='" . $who . "'"));
        $tlink = "<a href=\"lists.php?tbuid&amp;who=$who\">$unol[0]</a>";
        echo "Napravljenih tema: <b>$tlink</b><br/>";
        $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_favtopic WHERE uid='" . $who . "'"));
        echo "Oznacenih tema:<a href=\"favtopic.php?lfavtpc&amp;who=$who\"> $noi[0]</a><br/>";
        $unop = mysql_fetch_array(mysql_query("SELECT posts FROM fun_users WHERE id='" . $who . "'"));
        $unol = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_posts WHERE uid='" . $who . "'"));
        $plink = "<a href=\"lists.php?uposts&amp;who=$who\">$unol[0]</a>";
        echo "Ispisanih poruka: <b>$plink/$unop[0]</b><br/>";
        $noin = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_private WHERE touid='" . $who . "'"));
        $nout = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_private WHERE byuid='" . $who . "'"));
        echo "Poruke primljene: <b>$noin[0]</b> - poslate: <b>$nout[0]</b><br/>";
        $nopl = mysql_fetch_array(mysql_query("SELECT plusses FROM fun_users WHERE id='" . $who . "'"));
        echo "Plusici: <b>$nopl[0]</b><br/>";

        $nopl = mysql_fetch_array(mysql_query("SELECT chmsgs FROM fun_users WHERE id='" . $who . "'"));
        echo "Poruka u caskaonici: <b>$nopl[0]</b><br/>";
        $nopl = mysql_fetch_array(mysql_query("SELECT battlep FROM fun_users WHERE id='" . $who . "'")); 
        // echo "Battle Points: <b>$nopl[0]</b><br/>";
        $judg = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_judges WHERE uid='" . $who . "'"));
        if ($judg[0] > 0) {
            // echo "<b>Battle Board Judge</b><br/>";
        } 
        $nout = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_shouts WHERE shouter='" . $who . "'"));
        $nopl = mysql_fetch_array(mysql_query("SELECT shouts FROM fun_users WHERE id='" . $who . "'"));
        echo "Statusi: <b><a href=\"lists.php?shouts&amp;who=$who\">$nout[0]</a>/$nopl[0]</b><br/>";
        if (ismod(getuid_sid($sid))) {
            $nopl = mysql_fetch_array(mysql_query("SELECT regdate FROM fun_users WHERE id='" . $who . "'"));
            $jdt = date("d m y-H:i:s", $nopl[0]);
            echo "Clan od: <b>$jdt</b><br/>";
            $nopl = mysql_fetch_array(mysql_query("SELECT lastact FROM fun_users WHERE id='" . $who . "'"));
            $jdt = date("d m y-H:i:s", $nopl[0]);
            echo "Poslednja aktivnost: <b>$jdt</b><br/>";
            $nopl = mysql_fetch_array(mysql_query("SELECT lastvst FROM fun_users WHERE id='" . $who . "'"));
            $jdt = date("d m y-H:i:s", $nopl[0]);
            echo "Poslednja poseta: <b>$jdt</b><br/>";
            $nopl = mysql_fetch_array(mysql_query("SELECT browserm FROM fun_users WHERE id='" . $who . "'"));
            echo "Browser: <b>$nopl[0]</b><br/>";
        } 
        $nopl = mysql_fetch_array(mysql_query("SELECT email FROM fun_users WHERE id='" . $who . "'"));
        echo "E-mail: <b>$nopl[0]</b><br/>";
        $nopl = mysql_fetch_array(mysql_query("SELECT site FROM fun_users WHERE id='" . $who . "'"));
        $nopl[0] = getbbcode($nopl[0]); 
        // $nopl[0] = str_replace("2wap","2crapwap",$nopl[0]);
        echo "Sajt: $nopl[0]<br/>";
        $nopl = mysql_fetch_array(mysql_query("SELECT signature FROM fun_users WHERE id='" . $who . "'"));
        $sign = parsepm($nopl[0], $sid);
        echo "Potpis: $sign<br/>";
        if (ismod(getuid_sid($sid))) {
            $uipadd = mysql_fetch_array(mysql_query("SELECT ipadd FROM fun_users WHERE id='" . $who . "'"));
            echo "IP:<a href=\"lists.php?byip&amp;who=$who\">$uipadd[0]</a><br/>";
            $nob = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_buddies WHERE (uid='" . $who . "' OR tid='" . $who . "') AND agreed='1'"));
        } 
        echo "Prijatelja: $nob[0]";
        echo "";
        $noi = mysql_fetch_array(mysql_query("SELECT pollid FROM fun_users WHERE id='" . $who . "'"));
        if ($noi[0] > 0) {
            // echo "<a href=\"index.php?viewpl&amp;who=$who\">Anketa</a><br/>";
        } 
        $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_clubs WHERE owner='" . $who . "'"));
        if ($noi[0] > 0) {
            // echo "<a href=\"lists.php?ucl&amp;who=$who\">Grupe($noi[0])</a><br/>";
        } 
        $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_clubmembers WHERE uid='" . $who . "'"));
        if ($noi[0] > 0) {
            // echo "<a href=\"lists.php?clm&amp;who=$who\">Grupe ($noi[0])</a><br/>";
        } 
        $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_blogs WHERE bowner='" . $who . "'"));
        if ($noi[0] > 0) {
            // echo "<a href=\"lists.php?blogs&amp;who=$who\">Blogovi ($noi[0])</a><br/>";
        } 
        $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_vault WHERE uid='" . $who . "'"));
        if ($noi[0] > 0) {
            // echo "<a href=\"lists.php?vault&amp;who=$who\">Veze ($noi[0])</a><br/>";
        } 
        $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_gbook WHERE gbowner='" . $who . "'")); 
        // echo "<a href=\"lists.php?gbook&amp;who=$who\">Guestbook($noi[0])</a><br/>";
        $judg = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_judges WHERE uid='" . getuid_sid($sid) . "'"));
        if (ismod(getuid_sid($sid)) || $judg[0] > 0) {
            // echo "<a href=\"index.php?batp&amp;who=$who\">Battle Points</a><br/>";
        } 
    } else {
        echo "<img src=\"images/notok.gif\" alt=\"X\"/> Korisnik nepostoji";
    } 
    echo dnoonline($sid, $uid);
    exit();
} 
/*
\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
*/

else if (isset($_GET['fotografije'])) {
    $ko = $_POST["who"];
    $msgtxt = $_POST["msgtxt"];
    $todo = $_GET["todo"];
    addonline(getuid_sid($sid), "fotografije", "");

    echo vrhonline($sid, $uid);
    if ($who == "" || $who == 0) {
        $mnick = $_POST["mnick"];
        $who = getuid_nick($mnick);
    } 
    $whonick = getnick_uid($who);

    if ($whonick != "") {
         if ($_GET["poke"]) {
		if ($_GET["poke"] == 0){
	echo "<div class='pad'><div class='notif border_bottom'>Bockanje uspesno uklonjeno</div></div>";
	mysql_query("DELETE FROM fun_poke WHERE uid='" . $uid . "' AND pid='" . $_GET["pid"] . "'");
	} else if ($_GET["poke"] == 1) {
		$poke = getnick_uid($_GET["p"]);
	echo "<div class='pad'><div class='notif border_bottom'>Uspesno ste bocnuli <b>$poke</b></div></div>";
   mysql_query("INSERT INTO fun_poke SET pid='" . $uid . "' WHERE uid='" . $_GET["pid"] . "'");
	mysql_query("DELETE FROM fun_poke WHERE uid='" . $uid . "' AND pid='" . $_GET["pid"] . "'");
	} else {
		$poke = getnick_uid($_GET["p"]);
	echo "<div class='pad'><div class='notif border_bottom'><b>$poke</b> jos nije primetio/la vase bockanje</div></div>";
	}
	}
        echo "<div id='holder'>
		<div class='profile_image'>";
        $avlink = getavatar($who);
        if ($avlink) {
            echo "<img src=\"$avlink\" alt='$whonick' width='100' height='100' no_link='1' />";
        } else {
            $nopl = mysql_fetch_array(mysql_query("SELECT sex FROM fun_users WHERE id='" . $who . "'"));
            if ($nopl[0] == 'M') {
                echo "<img src=\"images/nopicboy.gif\" alt='$whonick' width='100' height='100' no_link='1' />";
            } else if ($nopl[0] == 'F') {
                echo "<img src=\"images/nopicgirl.gif\" alt='$whonick' width='100' height='100' no_link='1' />";
            } else {
                echo "<img src=\"images/nopic.jpg\" alt='$whonick' width='100' height='100' no_link='1' />";
            } 
        } 
		 $status = mysql_fetch_array(mysql_query("SELECT shout FROM fun_shouts WHERE shouter='" . $who . "' ORDER BY shtime DESC LIMIT 1"));
		 $status = parsemsg($status[0], $sid);
        echo "</div>
		<div class='profile_full_name'>$whonick</div>
		<div class='profile_status'>$status";
        $nopl = mysql_fetch_array(mysql_query("SELECT sex, birthday, location FROM fun_users WHERE id='" . $who . "'"));
        $uage = getage($nopl[1]);
        if ($nopl[0] == 'M') {
            $usex = "Musko";
        } else if ($nopl[0] == 'F') {
            $usex = "Zensko";
        } else {
            $usex = "Pol nije odredjen.";
        } 
        $nopl[2] = htmlspecialchars($nopl[2]);
        echo "<br />
		<div class='border_top_light'></div><small>Godina: <b>$uage</b><br/>";
        echo "Pol: <b>$usex</b><br/>";
        echo "Prebivaliste: <b>$nopl[2]</b></small><br/>";
        echo rating($who);
		echo "</div>
		<div class='clear border_bottom_light'></div>
		</div>
		<div class'comment'>";

        $uid = getuid_sid($sid);
        if (budres($uid, $who) == 0) {
            echo "<a href=\"genproc.php?bud&amp;who=$who&amp;todo=add\">Dodaj kao prijatelja</a><br />";
        } else if (budres($uid, $who) == 1) {
            echo "<a href=\"genproc.php?bud&amp;who=$who&amp;todo=del\">Izbaci iz prijatelja</a><br />";
        } 

        $ires = ignoreres($uid, $who);
        if ($ires == 2) {
            echo "<a href=\"genproc.php?ign&amp;who=$who&amp;todo=del\">Skini blokadu</a><br />";
        } else if ($ires == 1) {
            echo "<a href=\"genproc.php?ign&amp;who=$who&amp;todo=add\">Blokiraj</a><br />";
        }
		if ($who == $uid) {
		} else {
		  echo "<a href='index.php?viewuser&amp;poke=1&amp;pid=$who&amp;p=$who&amp;who=$who'>Bocni</a> <br />";
        }
        echo "<a href=\"inbox.php?sendpm&amp;who=$who\">Posalji poruku</a> </div>";
        echo "<div class='sett_line'><a href=\"index.php?viewuser&amp;who=$who\">Zid</a> 
 | <a href=\"index.php?viewusrmore&amp;who=$who\">Informacije</a> 
 | <b>Fotografije</b> ";
        if (ismod(getuid_sid($sid))) {
            echo "
 | <a href=\"index.php?moduser&amp;who=$who\">Mod panel clana</a>";
        } 
        echo "</div>";
    } 

    $fotografije = "SELECT id, imageurl, uid FROM fun_fotografije WHERE uid='" . $who . "'";

    $foto = mysql_query($fotografije);
    if (mysql_num_rows($foto) > 0) {
        while ($item = mysql_fetch_array($foto)) {
            if ($item[2] == "") {
                echo "<br /><table border='0' width='100%' id='download'><tr><td align='left' width='1' height='1'><b>Ovaj clan nema fotografija.</b></td></tr></table><br /><br />";
            } else {
                echo "<a href='index.php?fotopogled&amp;fotka=$item[0]'><img src='$item[1]' class='fotografija' height='50' width='50'></a>";
            } 
        } 
    } 
    $pregledac = getuid_sid($sid);
    if ($who == $pregledac) {
        echo "<div class='comment comm_adv'><img src='img/img_50.png' /> <a href='index.php?upload'>Dodaj fotografiju</a></div>";
    } 
    echo dnoonline($sid, $uid);
    exit();
} else if (isset($_GET['moduser'])) {
    echo vrhonline($sid, $uid);
    if ($who == "" || $who == 0) {
        $mnick = $_POST["mnick"];
        $who = getuid_nick($mnick);
    } 
    $whonick = getnick_uid($who);

    if ($whonick != "") {
         if ($_GET["poke"]) {
		if ($_GET["poke"] == 0){
	echo "<div class='pad'><div class='notif border_bottom'>Bockanje uspesno uklonjeno</div></div>";
	mysql_query("DELETE FROM fun_poke WHERE uid='" . $uid . "' AND pid='" . $_GET["pid"] . "'");
	} else if ($_GET["poke"] == 1) {
		$poke = getnick_uid($_GET["p"]);
	echo "<div class='pad'><div class='notif border_bottom'>Uspesno ste bocnuli <b>$poke</b></div></div>";
   mysql_query("INSERT INTO fun_poke SET pid='" . $uid . "' WHERE uid='" . $_GET["pid"] . "'");
	mysql_query("DELETE FROM fun_poke WHERE uid='" . $uid . "' AND pid='" . $_GET["pid"] . "'");
	} else {
		$poke = getnick_uid($_GET["p"]);
	echo "<div class='pad'><div class='notif border_bottom'><b>$poke</b> jos nije primetio/la vase bockanje</div></div>";
	}
	}
        echo "<div id='holder'>
		<div class='profile_image'>";
        $avlink = getavatar($who);
        if ($avlink) {
            echo "<img src=\"$avlink\" alt='$whonick' width='100' height='100' no_link='1' />";
        } else {
            $nopl = mysql_fetch_array(mysql_query("SELECT sex FROM fun_users WHERE id='" . $who . "'"));
            if ($nopl[0] == 'M') {
                echo "<img src=\"images/nopicboy.gif\" alt='$whonick' width='100' height='100' no_link='1' />";
            } else if ($nopl[0] == 'F') {
                echo "<img src=\"images/nopicgirl.gif\" alt='$whonick' width='100' height='100' no_link='1' />";
            } else {
                echo "<img src=\"images/nopic.jpg\" alt='$whonick' width='100' height='100' no_link='1' />";
            } 
        } 
		 $status = mysql_fetch_array(mysql_query("SELECT shout FROM fun_shouts WHERE shouter='" . $who . "' ORDER BY shtime DESC LIMIT 1"));
		 $status = parsemsg($status[0], $sid);
        echo "</div>
		<div class='profile_full_name'>$whonick</div>
		<div class='profile_status'>$status";
        $nopl = mysql_fetch_array(mysql_query("SELECT sex, birthday, location FROM fun_users WHERE id='" . $who . "'"));
        $uage = getage($nopl[1]);
        if ($nopl[0] == 'M') {
            $usex = "Musko";
        } else if ($nopl[0] == 'F') {
            $usex = "Zensko";
        } else {
            $usex = "Pol nije odredjen.";
        } 
        $nopl[2] = htmlspecialchars($nopl[2]);
        echo "<br />
		<div class='border_top_light'></div><small>Godina: <b>$uage</b><br/>";
        echo "Pol: <b>$usex</b><br/>";
        echo "Prebivaliste: <b>$nopl[2]</b></small><br/>";
        echo rating($who);
		echo "</div>
		<div class='clear border_bottom_light'></div>
		</div>
		<div class'comment'>";

        $uid = getuid_sid($sid);
        if (budres($uid, $who) == 0) {
            echo "<a href=\"genproc.php?bud&amp;who=$who&amp;todo=add\">Dodaj kao prijatelja</a><br />";
        } else if (budres($uid, $who) == 1) {
            echo "<a href=\"genproc.php?bud&amp;who=$who&amp;todo=del\">Izbaci iz prijatelja</a><br />";
        } 

        $ires = ignoreres($uid, $who);
        if ($ires == 2) {
            echo "<a href=\"genproc.php?ign&amp;who=$who&amp;todo=del\">Skini blokadu</a><br />";
        } else if ($ires == 1) {
            echo "<a href=\"genproc.php?ign&amp;who=$who&amp;todo=add\">Blokiraj</a><br />";
        } 
		if ($who == $uid) {
		} else {
		  echo "<a href='index.php?viewuser&amp;poke=1&amp;pid=$who&amp;p=$who&amp;who=$who'>Bocni</a> <br />";
        }echo "<a href=\"inbox.php?sendpm&amp;who=$who\">Posalji poruku</a> </div>";
        echo "<div class='sett_line'><a href=\"index.php?viewuser&amp;who=$who\">Zid</a> 
 | <a href=\"index.php?viewusrmore&amp;who=$who\">Informacije</a> 
 | <a href=\"index.php?fotografije&amp;who=$who\">Fotografije</a> ";
        if (ismod(getuid_sid($sid))) {
            echo "| <b>Mod panel clana</b>";
        } 
        echo "</div>";

        if (ismod(getuid_sid($sid))) {
            $pen[0] = "Obrisi";
            $pen[1] = "Banuj";
            $pen[2] = "Banuj + Ip";

            echo "<div class=\"section border_top\">";
            echo "<div class=\"section_title\"><div class=\"marker\">Brisanje/Ban/Ban + Ip clana</div></div>";

            echo "<form action=\"modproc.php?pun\" method=\"post\">";
            echo "<div class='comment border_bottom'>Funkcija: <select name=\"pid\">";
            for($i = 0;$i < count($pen);$i++) {
                echo "<option value=\"$i\">$pen[$i]</option>";
            } 
            echo "</select></div>";
            echo "<div class='comment border_bottom'>Razlog: <input name=\"pres\" maxlength=\"250\"/></div>";
            echo "<div class='comment border_bottom'>Dana: <input name=\"pds\" format=\"*N\" maxlength=\"4\"/></div>";
            echo "<div class='comment border_bottom'>Sati: <input name=\"phr\" format=\"*N\" maxlength=\"4\"/></div>";
            echo "<div class='comment border_bottom'><input type=\"hidden\" name=\"pmn\" format=\"*N\" maxlength=\"2\" value='0'/></div>";
            echo "<div class='comment'><input type=\"hidden\" name=\"psc\" format=\"*N\" maxlength=\"2\" value='0'/></div>";
            echo "<input type=\"submit\" class='button' value=\"Izvrsi\"/>";

            echo "<input type=\"hidden\" name=\"who\" value=\"$who\"/>";

            echo "</form> </div>";

            $resi[0] = "Oduzmi";
            $resi[1] = "Dodaj";

            echo "<div class=\"section border_top\">";
            echo "<div class=\"section_title\"><div class=\"marker\">Dodaj/Oduzmi plusice</div></div>";

            echo "<form action=\"modproc.php?pls\" method=\"post\">";
            echo "<div class='comment border_bottom'>Akcija: <select name=\"pid\">";
            for($i = 0;$i < count($resi);$i++) {
                echo "<option value=\"$i\">$resi[$i]</option>";
            } 
            echo "</select></div>";
            echo "<div class='comment border_bottom'>Razlog: <input name=\"pres\" maxlength=\"250\"/></div>";
            echo "<div class='comment'>Plusica: <input name=\"pval\" format=\"*N\" maxlength=\"3\"/></div>";
            echo "<input type=\"submit\" class='button' value=\"Izvrsi\"/>";

            echo "<input type=\"hidden\" name=\"who\" value=\"$who\"/>";

            echo "</form> </div>";

            echo "<div class=\"section border_top\">";
            echo "<div class=\"section_title\"><div class=\"marker\">Ostale Funkcije</div></div>";

            if (istrashed($who)) {
                echo "<a href=\"modproc.php?untr&amp;who=$who\">Vrati clana iz korpe</a>";
            } 
            if (isbanned($who)) {
                echo "<a href=\"modproc.php?unbn&amp;who=$who\">Skini ban</a><br/>";
            } 
            if (!isshield($who)) {
                echo "<a href=\"modproc.php?shld&amp;who=$who\">Zastiti clana</a><br/>";
            } else {
                echo "<a href=\"modproc.php?ushld&amp;who=$who\">Skini zastitu clana</a><br/>";
            } 
        } 
        // //////////
        // ////////////////
    } 
    // //////////////////
    // //////////////////
    echo dnoonline($sid, $uid);
    exit();
} else if (isset($_GET['fotopogled'])) {
    $fotka = $_GET["fotka"];
    addonline(getuid_sid($sid), "Gledam fotku", "");
    echo vrhonline($sid, $uid);

    $who = $_GET["who"];
    $msgtxt = $_POST["msgtxt"]; 
    // $like = $_GET["like"];
    // $liked = $_GET["liked"];
    // $dislike = $_GET["dislike"];
    // ////////////////////////////////////////
    if ($msgtxt == "") {
        echo "";
    } else {
        $uid = getuid_sid($sid);
        $crdate = time();
        $res = mysql_query("INSERT INTO fun_komentari_foto SET komowner='" . $fotka . "', komsigner='" . $uid . "', dtime='" . $crdate . "', kommsg='" . $msgtxt . "'");
        if ($res) {
            echo "<div class=\"pad\"><div class=\"notif border_bottom\">Vasa poruka je uspesno postavljena!!!</div>";

            $vlasnik = mysql_fetch_array(mysql_query("SELECT uid FROM fun_fotografije WHERE id='" . $fotka . "'"));
            $zzz = getnick_uid($uid);

            $msg = "[user=" . $uid . "]" . $zzz . "[/user] je prokomentarisao/la vasu [foto=" . $fotka . "]fotografiju[/foto].";
            mysql_query("INSERT INTO fun_notify SET text='" . $msg . "', byuid='" . $uid . "', touid='" . $vlasnik[0] . "', unread='1', timesent='" . time() . "'");
        } else {
            echo "<div class=\"pad\"><div class=\"error border_bottom\">Vasa poruka nije postavljena! Mogucnost da je greska u bazi, pa vas molimo da pokusate kasnije!</div>";
        } 
    } 

    $fotografija = mysql_fetch_array(mysql_query("SELECT uid, imageurl, time, descript FROM fun_fotografije WHERE id='" . $fotka . "'"));

    if ($fotografija[0] == "") {
        echo "<br /><table border='0' width='100%' id='download'><tr><td align='left' width='1' height='1'><b>Ovaj clan nema fotografija.</b></td></tr></table><br /><br />";
    } else {
        echo "<div class='pozadina'><img src='$fotografija[1]' /></div>";
        $snick = getnick_uid($fotografija[0]);
        $var1 = date("his", $fotografija[2]);
        $var2 = time ();
        $var21 = date("his", $var2);
        $var3 = $var21 - $var1;
        $var4 = date("s", $var3);
        $remain = time() - $fotografija[2];
        $bs = gettimemsg($remain);
        if ($fotografija[0] == $uid) {
            $obrisi = "<a href='index.php?brisifoto&amp;fotka=$fotka'>Obrisi fotografiju</a>";
            $profil = "<a href='index.php?zaprofilnu&amp;fotka=$fotka'>Postavi na profilnu</a> |";
        } else {
            $obrisi = "";
            $profil = "";
        } 
        if (isadmin(getuid_sid($sid))) {
            $aobrisi = "<br /> ADMIN: <a href='index.php?brisifoto&amp;fotka=$fotka'>Obrisi fotografiju</a>";
        } else {
            $aobrisi = "";
        } 
        echo "<div class='sett_line'><a href='index.php?viewuser&amp;who=$fotografija[0]'>$snick</a> $bs <br /> $fotografija[3]<br />$profil $obrisi $aobrisi</div>";

        if ($page == "" || $page <= 0)$page = 1;
        $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_komentari_foto WHERE komowner='" . $fotka . "'"));
        $num_items = $noi[0]; //changable
        $items_per_page = 7;
        $num_pages = ceil($num_items / $items_per_page);
        if (($page > $num_pages) && $page != 1)$page = $num_pages;
        $limit_start = ($page-1) * $items_per_page;

        if ($page > 1) {
            $ppage = $page-1;
            echo "<div class='center section border_bottom'><a href=\"index.php?fotopogled&amp;page=$ppage&amp;fotka=$fotka\"><img src='images/up.png' /></a></div>";
        } 

        $sql = "SELECT komowner, komsigner, kommsg, dtime, id FROM fun_komentari_foto WHERE komowner='" . $fotka . "' ORDER BY dtime DESC LIMIT $limit_start, $items_per_page";

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
                $var1 = date("his", $item[3]);
                $var2 = time ();
                $var21 = date("his", $var2);
                $var3 = $var21 - $var1;
                $var4 = date("s", $var3);
                $remain = time() - $item[3];
                $bs = gettimemsg($remain);
                $lnk = "<div id=\"comments_468323575248\" class=\"section border_bottom\"><div class=\"comment\"><div class=\"comm_adv\"><table border='0' width='100%' id='download'><tr><td align='left' width='1' height='1'>$avatar</td><td><a href=\"index.php?viewuser&amp;who=$item[1]\">$snick</a> <br /> <small>$bs</small></td></tr></table>";
                echo "$lnk<small>";

                $delnk = "";

                $text = parsepm($item[2], $sid);
                echo "<div>$text</div>";
                echo "</div></div></div></small>";
            } 
        } 
        if ($page < $num_pages) {
            $npage = $page + 1;
            echo "<div class='center section border_bottom'><a href=\"index.php?fotopogled&amp;page=$npage&amp;fotka=$fotka\"><img src='images/down.png' /></a></div>";
        } 
        // //// UNTILL HERE >>
        echo "<div id=\"comment_box_468323575248\" class=\"sett_line\"><form action=\"index.php?fotopogled&amp;fotka=$fotka\" method=\"post\"><div>Dodaj komentar</div>";
        echo "<textarea name=\"msgtxt\" rows=\"3\"></textarea><br/>";
        echo "<input class=\"button\" type=\"submit\" value=\"Upisi komentar\"/>";
        echo "</form></div>";
    } 
    echo dnoonline($sid, $uid);
    exit();
} else if (isset($_GET['brisifoto'])) {
    $fotka = $_GET["fotka"];
    addonline(getuid_sid($sid), "Gledam fotku", "");
    echo vrhonline($sid, $uid);

    $fotografija = mysql_fetch_array(mysql_query("SELECT uid, imageurl, time, descript FROM fun_fotografije WHERE id='" . $fotka . "'"));
    if ($fotografija[0] == $uid) {
        $res = mysql_query("DELETE FROM fun_fotografije  WHERE id='" . $fotka . "' AND uid='" . $fotografija[0] . "'");
        if ($res) {
            echo "Fotografija uspesno obrisana<br />";
        } else {
            echo "Greska u bazi <br />";
        } 
    } else {
        echo "Ova fotografija nije vasa...<br />";
    } 

    if (isadmin(getuid_sid($sid))) {
        $res = mysql_query("DELETE FROM fun_fotografije  WHERE id='" . $fotka . "' AND uid='" . $fotografija[0] . "'");
        if ($res) {
            echo "ADMIN: Fotografija uspesno obrisana";
        } else {
            echo "ADMIN: Greska u bazi";
        } 
    } 

    echo dnoonline($sid, $uid);
    exit();
} else if (isset($_GET['zaprofilnu'])) {
    $fotka = $_GET["fotka"];
    addonline(getuid_sid($sid), "Gledam fotku", "");
    echo vrhonline($sid, $uid);

    $fotografija = mysql_fetch_array(mysql_query("SELECT uid, imageurl, time, descript FROM fun_fotografije WHERE id='" . $fotka . "'"));
    if ($fotografija[0] == $uid) {
        $res = mysql_query("UPDATE fun_users SET avatar='" . $fotografija[1] . "' WHERE id='" . $uid . "'");
        if ($res) {
            echo "Fotografija uspesno postavljena kao profilna<br />";
        } else {
            echo "Greska fotografija nije postavljena na profil<br />";
        } 
    } else {
        echo "Ova fotografija nije vasa...<br />";
    } 

    echo dnoonline($sid, $uid);
    exit();
} 
if (isset($_GET['upload'])) {
    addonline(getuid_sid($sid), "Uploading a Photo", "");
    $rate = $_POST["rate"];
    $comment = $_POST["comment"];

    echo vrhonline($sid, $uid);

    echo "<small>";

    echo "<form name=\"form2\" enctype=\"multipart/form-data\" method=\"post\" action=\"upload.php?upload\" />";
    echo "<div class='sett_line'><input type=\"file\" size=\"32\" name=\"my_field\" value=\"\" />";
    echo "<input type=\"hidden\" name=\"action\" value=\"image\" /></div>";
    echo "<div class='sett_line'>Opis: <br /><textarea name='descript' rows='4' size='20'></textarea></div>";
    echo "<input type=\"submit\" name=\"Uploaduj\" class=\"button\" value=\"upload\" /><br/>";
    echo "</form>";

    echo "</small>";

    echo dnoonline($sid, $uid);
    exit();
} 
// /////////////////////////////////////////
// ////////////////////////////////////////
// ///////////////////////////////////////
// //////////////////////////////////////
// /////////////////////////////////////
else if (isset($_GET['viewbud'])) {
    addonline(getuid_sid($sid), "Viewing buddy", "index.php?viewbud&amp;who=$who");

    echo vrhonline($sid, $uid);
    echo "<p align=\"center\">";
    if ($uid == "" || $uid == 0) {
        $mnick = $_POST["mnick"];
        $who = getuid_nick($mnick);
    } 
    $whonick = getnick_uid($who);
    if ($whonick != "") {
        echo "</p>";
        echo "<p>";

        echo "$whonick<br/>";

        echo "<br/>pop up message: ";
        echo "<form action=\"popup.php?send\" method=\"post\">";
        echo "<input type=\"text\" name=\"text\" maxlength=\"150\"/>";
        echo "<input type=\"submit\" value=\"send\"/>";
        echo "<input type=\"hidden\" name=\"who\" value=\"$who\"/>";
        echo "</form>";
        echo "<br/>";

        echo "<a href=\"inbox.php?sendpm&amp;who=$who\">+ send message</a><br/>";
    } else {
        echo "<img src=\"images/notok.gif\" alt=\"X\"/> Member dos not exist<br/>";
    } 

    echo "<br/>0 <a a href=\"lists.php?bud\">buddylist</a>";
    echo "</p>";
    echo dnoonline($sid, $uid);
    exit();
} 
// //////////////////////////////////////// uxset
else if (isset($_GET['uxset'])) {
    addonline(getuid_sid($sid), "Extended Settings", "");

    echo vrhonline($sid, $uid);
    echo "<p>";
    echo "<a href=\"index.php?uadd\">&#187;My Address</a><br/>";
    echo "<a href=\"index.php?uper\">&#187;Personality</a><br/>"; 
    // echo "<a href=\"index.php?gmset\">&#187;Gmail Settings</a><br/>";
    echo "<a href=\"index.php?umin\">&#187;More about me</a><br/>";
    echo "<a href=\"index.php?upre\">&#187;Preferences</a><br/>";

    echo "</p>";
    echo "<p align=\"center\">";

    echo "</p>";
    echo dnoonline($sid, $uid);
    exit();
} 
// ////////////////////////////////////////User Address
else if (isset($_GET['uadd'])) {
    addonline(getuid_sid($sid), "My Address", "");

    echo vrhonline($sid, $uid);
    $ainfo = mysql_fetch_array(mysql_query("SELECT country, city, street, phoneno, timezone FROM fun_xinfo WHERE uid='" . getuid_sid($sid) . "'"));

    echo "<p>";
    echo "<img src=\"images/point.gif\" alt=\"!\"/>Go to Preferences and choose buddies only if you want only your buddies to see your street and phone number<br/>";
    echo "<img src=\"images/point.gif\" alt=\"!\"/>If you don't anyone to see these information just don't type them<br/>";
    echo "<img src=\"images/point.gif\" alt=\"!\"/>Timezone is required to get your e-mails from G-Mail account in your local time.<br/>";
    echo "<img src=\"images/point.gif\" alt=\"!\"/>Example on timezone is 2 for +2 hours on GMT, or -2.5 for -2:30 on GMT<br/>";
    echo "<img src=\"images/point.gif\" alt=\"!\"/>These info. will help you to meet friends and dates from where do you live<br/><br/>";

    echo "<form action=\"genproc.php?uadd\" method=\"post\">";
    echo '
    Contry: <input name="ucon" maxlength="50" value=\"$ainfo[0]\"/><br/>
    City: <input name="ucit" maxlength="50" value=\"$ainfo[1]\"/><br/>
    Street: <input name="ustr" maxlength="50" value=\"$ainfo[2]\"/><br/>
    Timezone(e.g +2 or -2.5): <input name="utzn" size="5" value="0" maxlength="5" value=\"$ainfo[4]\"/><br/>
    Phone No.: <input name="uphn" maxlength="20" value=\"$ainfo[3]\"/><br/>
    ';
    echo "<input type=\"submit\" value=\"Save\"/>";
    echo "</form>";
    echo "</p>";
    echo "<p align=\"center\">";
    echo "<a href=\"index.php?uxset\">";
    echo "Extended Settings</a><br/>";
    echo "</p>";
    echo dnoonline($sid, $uid);
    exit();
} 
// ////////////////////////////////////////User Preferences
else if (isset($_GET['upre'])) {
    addonline(getuid_sid($sid), "Preferences", "");

    echo vrhonline($sid, $uid);
    $ainfo = mysql_fetch_array(mysql_query("SELECT sitedscr, budsonly, sexpre FROM fun_xinfo WHERE uid='" . getuid_sid($sid) . "'"));

    echo "<p>";
    echo "<img src=\"images/point.gif\" alt=\"!\"/>Your site already set in your normal settings<br/>";
    echo "<img src=\"images/point.gif\" alt=\"!\"/>Set buddies only to yes, so only your buddies can see your phone number, street, and real name<br/>";
    echo "<img src=\"images/point.gif\" alt=\"!\"/>Sex preference will help the correct people to find you<br/><br/>";

    echo "<form action=\"genproc.php?upre\" method=\"post\">";
    echo '
    Site description: <input name="usds" maxlength="200" value=\"$ainfo[0]\"/><br/>
    Buddies Only:
    <select name="ubon" value="$ainfo[1]">
    <option value="1">Yes</option>
    <option value="0">No</option>
    </select>
    <br/>Sex Preference:
    <select name="usxp" value="$ainfo[2]">
    <option value="F">Females</option>
    <option value="M">Males</option>
    <option value="B">Both</option>
    </select>
    ';
    echo "<input type=\"submit\" value=\"Save\"/>";
    echo "</form>";
    echo "</p>";
    echo "<p align=\"center\">";
    echo "<a href=\"index.php?uxset\">";
    echo "Extended Settings</a><br/>";
    echo "</p>";
    echo dnoonline($sid, $uid);
    exit();
} 
// ////////////////////////////////////////User Personaliy
else if (isset($_GET['uper'])) {
    addonline(getuid_sid($sid), "Personality", "");

    echo vrhonline($sid, $uid);
    $ainfo = mysql_fetch_array(mysql_query("SELECT height, weight, realname, racerel, eyescolor, profession, hairtype FROM fun_xinfo WHERE uid='" . getuid_sid($sid) . "'"));

    echo "<p>";
    echo "<form action=\"genproc.php?uper\" method=\"post\">";
    echo '
    Height: <input name="uhig" maxlength="10" value="$ainfo[0]"/><br/>
    Weight: <input name="uwgt" maxlength="10" value="$ainfo[1]"/><br/>
    Real Name: <input name="urln" maxlength="100" value="$ainfo[2]"/><br/>
    Ethnic Origin: <input name="ueor" maxlength="100" value="$ainfo[3]"/><br/>
    Eyes: <input name="ueys" maxlength="10" value="$ainfo[4]"/><br/>
    Hair: <input name="uher" maxlength="50" value="$ainfo[5]"/><br/>
    Profession: <input name="upro" maxlength="100" value="$ainfo[6]"/><br/>
    ';
    echo "<input type=\"submit\" value=\"Save\"/>";
    echo "</form>";
    echo "</p>";
    echo "<p align=\"center\">";
    echo "<a href=\"index.php?uxset\">";
    echo "Extended Settings</a><br/>";
    echo "</p>";
    echo dnoonline($sid, $uid);
    exit();
} 
// ////////////////////////////////////////User Personaliy
else if (isset($_GET['umin'])) {
    addonline(getuid_sid($sid), "More About Me", "");

    echo vrhonline($sid, $uid);
    $ainfo = mysql_fetch_array(mysql_query("SELECT likes, deslikes, habitsb, habitsg, favsport, favmusic, moretext FROM fun_xinfo WHERE uid='" . getuid_sid($sid) . "'"));

    echo "<p>";
    echo "<form action=\"genproc.php?umin\" method=\"post\">";
    echo '
    Likes: <input name="ulik" maxlength="250" value="$ainfo[0]"/><br/>
    Dislikes: <input name="udlk" maxlength="250" value="$ainfo[1]"/><br/>
    Bad Habbits: <input name="ubht" maxlength="250" value="$ainfo[2]"/><br/>
    Good Habbits: <input name="ught" maxlength="250" value="$ainfo[3]"/><br/>
    Favorite Sports: <input name="ufsp" maxlength="100" value="$ainfo[4]"/><br/>
    Favorite Music: <input name="ufmc" maxlength="100" value="$ainfo[5]"/><br/>
    More Text: <input name="umtx" maxlength="500" value="$ainfo[6]"/><br/>
    ';
    echo "<input type=\"submit\" value=\"Save\"/>";
    echo "</form>";
    echo "</p>";
    echo "<p align=\"center\">";
    echo "<a href=\"index.php?uxset\">";
    echo "Extended Settings</a><br/>";
    echo "</p>";
    echo dnoonline($sid, $uid);
    exit();
} 
// ////////////////////////////////////////Give Game Plusses
else if (isset($_GET['givegp'])) {
    $who = $_GET["who"];
    addonline(getuid_sid($sid), "Giving Game Plusses", "");

    echo vrhonline($sid, $uid);

    echo "<p align=\"center\">";
    echo "<b>Give GPs To " . getnick_uid($who) . "</b><br/><br/>";
    $gps = mysql_fetch_array(mysql_query("SELECT gplus FROM fun_users WHERE id='" . getuid_sid($sid) . "'"));
    echo "You have $gps[0] GP's<br/><br/>";
    echo "GP's to give<br/>";
    echo "<form action=\"genproc.php?givegp&amp;who=$who\" method=\"post\">";
    echo "<input name=\"tfgp\" format=\"*N\" maxlength=\"2\"/>";
    echo "<input type=\"submit\" value=\"GO\"/>";
    echo "</form>";
    echo "</p>";
    echo dnoonline($sid, $uid);
    exit();
} 
// ////////////////////////////////////////Give Battle points
else if (isset($_GET['batp'])) {
    $who = $_GET["who"];
    addonline(getuid_sid($sid), "Giving Battle Points", "");

    echo vrhonline($sid, $uid);

    echo "<p align=\"center\">";
    $judg = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_judges WHERE uid='" . getuid_sid($sid) . "'"));
    if (ismod(getuid_sid($sid)) || $judg[0] > 0) {
        echo "<form action=\"genproc.php?batp&amp;who=$who\" method=\"post\">";
        echo "<b>Give/Take BPs To " . getnick_uid($who) . "</b><br/><br/>";
        echo "<input name=\"ptbp\" format=\"*N\" maxlength=\"2\"/>";
        echo "<input type=\"submit\" Value=\"Give\"/>";
        echo "<input type=\"hidden\" name=\"giv\" value=\"1\"/>";
        echo "</form>";

        echo "<form action=\"genproc.php?batp&amp;who=$who\" method=\"post\">";
        echo "<b>Give/Take BPs To " . getnick_uid($who) . "</b><br/><br/>";
        echo "<input name=\"ptbp\" format=\"*N\" maxlength=\"2\"/>";
        echo "<input type=\"submit\" Value=\"Take\"/>";

        echo "<input type=\"hidden\" name=\"giv\" value=\"0\"/>";
        echo "</form>";

        echo "<br/><br/>";
    } else {
        echo "You Can't Do This";
    } 
    echo "</p>";
    echo dnoonline($sid, $uid);
    exit();
} else if (isset($_GET['chat'])) {
    addonline(getuid_sid($sid), "Chat Menu", "");

    echo vrhonline($sid, $uid);
    echo "<p align=\"center\">";
    echo "<img src=\"images/chat.gif\" alt=\"*\"/><br/>";

    echo "<br/>";

    $unreadinbox = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_private WHERE unread='1' AND touid='" . getuid_sid($sid) . "'"));
    $pmtotl = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_private WHERE touid='" . getuid_sid($sid) . "'"));
    $unrd = "(" . $unreadinbox[0] . "/" . $pmtotl[0] . ")";
    echo "<a href=\"inbox.php?main&amp;page=1\">Inbox$unrd</a><br/><br/> ";
    echo "<a href=\"index.php?uchat\">Users Rooms</a><br/><br/>";
    $rooms = mysql_query("SELECT id, name, perms, mage, chposts FROM fun_rooms WHERE static='1' AND clubid='0'");
    while ($room = mysql_fetch_array($rooms)) {
        if (canenter($room[0], $sid)) {
            $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_chonline WHERE rid='" . $room[0] . "'"));
            echo "<a href=\"chat.php?sid=$sid&amp;rid=$room[0]\">$room[1]($noi[0])</a><br/>";
        } 
    } 

    echo "</p>";

    echo dnoonline($sid, $uid);
    exit();
} else if (isset($_GET['uchat'])) {
    $rname = mysql_escape_string($_POST["rname"]);
    $rpass = trim($_POST["rpass"]);
    addonline(getuid_sid($sid), "Chat Menu", "");

    echo vrhonline($sid, $uid);
    if ($rname) {
        if ($rpass == "") {
            $cns = 1;
        } else {
            $cns = 0;
        } 
        $res = mysql_query("INSERT INTO fun_rooms SET name='" . $rname . "', pass='" . $rpass . "', censord='" . $cns . "', static='0', lastmsg='" . time() . "'");
        if ($res) {
            echo "<div class=\"pad\"><div class=\"notif border_bottom\">Uspesno ste kreirali sobu</div>";
        } else {
            echo "<div class=\"pad\"><div class=\"error border_bottom\">Trenutno nije moguce kreirati sobu... Pokusajte kasnije</div>";
        } 
    } 

    echo "<div class=\"center border_bottom_light\"><a href=\"index.php?mkroom\">Napravi sobu</a></div>";
    $rooms = mysql_query("SELECT id, name, pass FROM fun_rooms WHERE static='0'");
    $co = 0;
    while ($room = mysql_fetch_array($rooms)) {
        $co++;
        if (canenter($room[0], $sid)) {
            $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_chonline WHERE rid='" . $room[0] . "'"));
            if ($room[2] == "") {
                echo "<div class=\"comment comm_adv\"><a href=\"chat.php?sid=$sid&amp;rid=$room[0]\">" . htmlspecialchars($room[1]) . "($noi[0])</a></div>";
            } else {
                echo htmlspecialchars($room[1]);
            } 
        } 
    } 

    echo "</p>";

    echo dnoonline($sid, $uid);
    exit();
} else if (isset($_GET['mkroom'])) {
    addonline(getuid_sid($sid), "Creating Chatroom", "");

    echo vrhonline($sid, $uid);
    echo "<div class=\"titlz comm_adv\"/>Posaljite lozinku postom prijateljima sa kojim zelite da cjaskate!<br />Ako zelite mozete kreirati i kao javnu sobu tako sto ce te polje za pass ostaviti prazno.</div>";

    echo "<form action=\"index.php?uchat\" method=\"post\">";
    echo "<div class=\"titl\">Naziv sobe:</div> <br /> <input name=\"rname\" maxlength=\"30\"/><br/>";
    echo "<div class=\"titl\">Lozinka:</div> <br /> <input name=\"rpass\" format=\"*x\" maxlength=\"10\"/><br/>";
    echo "<input type=\"submit\" class=\"button\" value=\"Napravi\"/>";
    echo "</form>";
    echo dnoonline($sid, $uid);
    exit();
} else if (isset($_GET['funm'])) {
    addonline(getuid_sid($sid), "Fun Menu", "");

    echo vrhonline($sid, $uid);
    echo "<p align=\"center\">";
    echo "<img src=\"images/roll.gif\" alt=\"*\"/><br/>";
    echo "Hello, so you want to have some creative fun? well you came into the right place";
    echo "</p>";
    echo "<p>";
    echo "<a href=\"chatbot.php?sid=$sid\">&#187;auto foggy</a><br/>";
    echo "<a href=\"games.php?guessgm\">&#187;Guess The Number</a><br/>"; 
    // echo "&#187;Hangman<br/>";
    // echo "&#187;Dares Box<br/>";
    echo "</p>";
    echo dnoonline($sid, $uid);
    exit();
} 
// /////////////////////////////view blog
else if (isset($_GET['viewblog'])) {
    $bid = $_GET["bid"];
    addonline(getuid_sid($sid), "Viewing Users Blog", "");

    echo vrhonline($sid, $uid);

    $pminfo = mysql_fetch_array(mysql_query("SELECT btext, bname, bgdate,bowner, id FROM fun_blogs WHERE id='" . $bid . "'"));
    $bttl = htmlspecialchars($pminfo[1]);
    $btxt = parsemsg($pminfo[0], $sid);
    $bnick = getnick_uid($pminfo[3]);
    $vbbl = "<a href=\"index.php?viewuser&amp;who=$pminfo[3]\">$bnick</a><br/>";
    echo "Grupa br: <b>$bid</b><br/>";
    echo "<b>$bttl</b> od: $vbbl<br/>";
    echo "$btxt<br/>";
    $tmstamp = $pminfo[2];
    $tmdt = date("d m y - h:i:s", $tmstamp);
    echo "$tmdt<br/><br/>";
    $vb = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_brate WHERE uid='" . $uid . "' AND blogid='" . $bid . "'"));
    if ($vb[0] == 0) {
        echo "<form action=\"genproc.php?rateb&amp;bid=$pminfo[4]\" method=\"post\">";
        echo "<select name=\"brate\">";
        echo "<option value=\"1\">1</option>";
        echo "<option value=\"2\">2</option>";
        echo "<option value=\"3\">3</option>";
        echo "<option value=\"4\">4</option>";
        echo "<option value=\"5\">5</option>";
        echo "</select><br/>";
        echo "<input type=\"submit\" value=\"Rate\"/>";
        echo "</form>";
    } else {
        $rinfo = mysql_fetch_array(mysql_query("SELECT COUNT(*) as nofr, SUM(brate) as nofp FROM fun_brate WHERE blogid='" . $bid . "'"));
        $ther = $rinfo[1] / $rinfo[0];
        echo "Glasova: $ther - Poena: $rinfo[1]";
    } 
    echo "<a href=\"lists.php?allbl\">Vrati se ublog</a><br/>";
    $bnick = getnick_uid($pminfo[3]);
    echo "<a href=\"lists.php?blogs&amp;who=$pminfo[3]\">Vrati se u $bnick blog</a><br/>";

    echo dnoonline($sid, $uid);
    exit();
} 
// ///////////////////////////////ADMIN CP
else if (isset($_GET['admincp'])) {
    addonline(getuid_sid($sid), "Admin CP", "");

    echo vrhonline($sid, $uid);
    echo "<p align=\"center\">";
    echo "<b>Admin CP</b>";
    echo "</p>";
    echo "<p>";
    if (isadmin(getuid_sid($sid))) {
        echo "<a href=\"admincp.php?general\">&#187;General Settings</a><br/>";
        echo "<a href=\"admincp.php?fcats\">&#187;Forum Categories</a><br/>";
        echo "<a href=\"admincp.php?forums\">&#187;Forums</a><br/>";
        echo "<a href=\"admincp.php?ugroups\">&#187;User groups</a><br/>";
        echo "<a href=\"admincp.php?addperm\">&#187;Add permissions</a><br/>";
        echo "<a href=\"admincp.php?chuinfo\">&#187;Change user info</a><br/>";
        echo "<a href=\"admincp.php?manrss\">&#187;Manage RSS Sources</a><br/>";
        echo "<a href=\"index.php?givegp\">&#187;Game points</a><br/>";
        echo "<a href=\"admincp.php?addsml\">&#187;Add Smilies</a><br/>";
        echo "<a href=\"admincp.php?addavt\">&#187;Add Avatar</a><br/>";
        echo "<a href=\"admincp.php?chrooms\">&#187;Chatrooms</a><br/>";
        echo "<a href=\"admincp.php?clrdta\">&#187;Clear Data</a><br/>";
    } else {
        echo "You are not an Admin";
    } 
    echo "</p>";
    echo dnoonline($sid, $uid);
    exit();
} 
// ///////////////////////////////Terms of use
else if (isset($_GET['terms'])) {
    $uid = getuid_sid($sid);
    if ($uid > 0) {
        addonline(getuid_sid($sid), "Terms of use", "");
    } 

    echo vrhonline($sid, $uid);
    echo "<p>";
    echo "<img src=\"images/point.gif\" alt=\"!\"/>posts containing racism, spamming, flooding, adult content, hacking will be deleted immediately, and the posters will get warned or banned<br/>";
    echo "<img src=\"images/point.gif\" alt=\"!\"/>chatting, discriminating, posting off topics, posting useless posts (smilies only or one word posts), and free posting results to staff to delete the posts, then substracting plusses and a ban in some cases<br/>";
    echo "<img src=\"images/point.gif\" alt=\"!\"/>Registering more than one nickname, could result in  all your accounts being deleted<br/>";
    echo "<img src=\"images/point.gif\" alt=\"!\"/>Never give your username and password to anyone<br/>";
    echo "<img src=\"images/point.gif\" alt=\"!\"/>Faking your personal information (like age, sex, location etc..) just to gain access to hidden forums and other content in here, could result in you being banned, or getting a warning at least<br/>";
    echo "<img src=\"images/point.gif\" alt=\"!\"/>harassment and racism will result in a ban for 7 days minimum without a warning, and an permanent IP-Ban if this behavior continues<br/>";
    echo "<img src=\"images/point.gif\" alt=\"!\"/>Bumping topics (keep posting in them by the author of the topic just to keep it in first page) will cause these topics to be deleted and the penalty could vary between warning, substracting plusses, or even a ban<br/>";
    echo "<img src=\"images/point.gif\" alt=\"!\"/>You can only speak in english on this site, this includes all the chatrooms except (International and the languages board) but you are free to create your own chatroom and club in any language<br/>";
    echo "<img src=\"images/point.gif\" alt=\"!\"/>No smoking in public, next to the pregnant ladies, or next to babies hehe<br/>";
    echo "<img src=\"images/point.gif\" alt=\"!\"/>The most important rule is to have fun here and enjoy your stay ;)<br/>";
    echo "<img src=\"images/point.gif\" alt=\"!\"/>just one more final thing, stay away from amylee she bites ;o) lol<br/>";
    echo "<br/>Remeber, these rules were made for protecting you before protecting us, if you think they are a little restrictive then read <a href=\"lists.php?faqs\">our F.A.Qs</a> or ask any online staff member(only if you logged in), Thank you so much<br/>";
    echo "</p>";
    if ($uid > 0) {
        echo dnoonline($sid, $uid);
        exit();
    } else {
        echo dnoonline($sid, $uid);
        exit();
    } 
} else if (isset($_GET['pltpc'])) {
    $tid = $_GET["tid"];
    addonline(getuid_sid($sid), "Creating A Poll", "");

    echo vrhonline($sid, $uid);
    echo "<p>";
    if ((getplusses(getuid_sid($sid)) >= 0) || ismod($uid)) {
        $pid = mysql_fetch_array(mysql_query("SELECT pollid FROM fun_topics WHERE id='" . $tid . "'"));
        if ($pid[0] == 0) {
            echo "<form action=\"genproc.php?pltpc&amp;tid=$tid\" method=\"post\">";
            echo "Pitanje:<input name=\"pques\" maxlength=\"250\"/><br/>";
            echo "Opcija 1:<input name=\"opt1\" maxlength=\"100\"/><br/>";
            echo "Opcija 2:<input name=\"opt2\" maxlength=\"100\"/><br/>";
            echo "Opcija 3:<input name=\"opt3\" maxlength=\"100\"/><br/>";
            echo "Opcija 4:<input name=\"opt4\" maxlength=\"100\"/><br/>";
            echo "Opcija 5:<input name=\"opt5\" maxlength=\"100\"/><br/>";
            echo "<input type=\"submit\" value=\"Napravi\"/>";
            echo "</form>";
        } else {
            echo "<img src=\"images/notok.gif\" alt=\"x\"/>Ova tema vec ima anketu";
        } 
    } else {
        echo "<img src=\"images/notok.gif\" alt=\"x\"/>Treba vam 500 poena";
    } 
    echo dnoonline($sid, $uid);
    exit();
} else if (isset($_GET['viewcat'])) {
    $cid = $_GET["cid"];
    addonline(getuid_sid($sid), "Viewing Forum Category", "");
    $cinfo = mysql_fetch_array(mysql_query("SELECT name from fun_fcats WHERE id='" . $cid . "'"));

    echo vrhonline($sid, $uid);
    $forums = mysql_query("SELECT id, name FROM fun_forums WHERE cid='" . $cid . "' AND clubid='0' ORDER BY position, id, name");
    echo "";
    while ($forum = mysql_fetch_array($forums)) {
        if (canaccess(getuid_sid($sid), $forum[0])) {
            $notp = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_topics WHERE fid='" . $forum[0] . "'"));
            $nops = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_posts a INNER JOIN fun_topics b ON a.tid = b.id WHERE b.fid='" . $forum[0] . "'"));
            echo "<div class=\"sett_line\"><a href=\"index.php?viewfrm&amp;fid=$forum[0]\">$forum[1]($notp[0]/$nops[0])</a><br />";
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
            $tpclnk = "<a href=\"index.php?viewtpc&amp;tid=$lpt[0]&amp;go=last\">$tlnm</a>";
            $vulnk = "<a href=\"index.php?viewuser&amp;who=$tluid\">$tlnick</a>";
            echo "Poslednja poruka u: $tpclnk <br /> Koju je napisao/la: $vulnk</div>";
        } 
    } 
    echo "";
    echo dnoonline($sid, $uid);
    exit();
} 
// ////////////////////////////////View Topic
else if (isset($_GET['viewtpc'])) {
    addonline(getuid_sid($sid), "Viewing Forum Topic", "");
    $tid = $_GET["tid"];
    $go = $_GET["go"];
    $tfid = mysql_fetch_array(mysql_query("SELECT fid FROM fun_topics WHERE id='" . $tid . "'"));
    echo vrhonline($sid, $uid);
    if (!canaccess(getuid_sid($sid), $tfid[0])) {
        echo vrhonline($sid, $uid);
        echo "<div class=\"sett_line\"><div class=\"center\">Vi nemate dozvolu da pristupite ovom delu foruma...</div></div>";

        echo dnoonline($sid, $uid);
        exit();
    } 

    $tinfo = mysql_fetch_array(mysql_query("SELECT name, text, authorid, crdate, views, fid, pollid from fun_topics WHERE id='" . $tid . "'"));
    $tnm = htmlspecialchars($tinfo[0]);

    $num_pages = getnumpages($tid);
    if ($page == "" || $page < 1)$page = 1;
    if ($go != "")$page = getpage_go($go, $tid);
    $posts_per_page = 5;
    if ($page > $num_pages)$page = $num_pages;
    $limit_start = $posts_per_page * ($page-1);
    $lastlink = "<div class=\"titlz\"><a href=\"index.php?$action&amp;tid=$tid&amp;go=last\">Poslednja strana</a></div>";
    $firstlink = "<div class=\"titlz\"><a href=\"index.php?$action&amp;tid=$tid&amp;page=1\">Prva strana</a></div>";
    $golink = "";
    if ($page > 1) {
        $golink = $firstlink;
    } 
    if ($page < $num_pages) {
        $golink .= $lastlink;
    } 
    if ($golink != "") {
        echo "$golink<br />";
    } 
    $vws = $tinfo[4] + 1;
    $rpls = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_posts WHERE tid='" . $tid . "'"));
    echo "Poruka: <div class=\"titl\">$rpls[0]</div> <br />Pregleda: <div class=\"titl\">$vws</div><br/>"; 
    // /fm here
    $ttext = mysql_fetch_array(mysql_query("SELECT authorid, text, crdate, pollid FROM fun_topics WHERE id='" . $tid . "'"));
    $unick = getnick_uid($ttext[0]);

    $avlink = getavatar($ttext[0]);
    if ($avlink != "") {
        $avatar = "<img src=\"$avlink\" height=\"25\" width=\"25\" alt=\"avatar\"/>";
    } else {
        $avatar = "<img src=\"images/nopic.jpg\" height=\"25\" width=\"25\" alt=\"avatar\"/>";
    } 
    $usl = "<a href=\"index.php?viewuser&amp;who=$ttext[0]\">$unick</a>";
    $topt = "<a href=\"index.php?tpcopt&amp;tid=$tid\">*</a>";
    if ($go == $tid) {
        $fli = "<img src=\"img/img_44.png\">";
    } else {
        $fli = "";
    } 
    $pst = parsemsg($ttext[1], $sid);
    $dtot = date("d-m-y - H:i:s", $ttext[2]);
    echo "<div class=\"sett_line\"><table border='0' width='100%' id='download'><tr><td align='left' width='1' height='1'>$avatar</td><td>$usl<br /> $dtot</td></tr></table>  $fli$pst $topt";
    if ($ttext[3] > 0) {
        echo "<br/><a href=\"index.php?viewtpl&amp;who=$tid\">*ANKETA*</a>";
    } 
    echo "<br/><a href=\"favtopic.php?addfav&amp;tid=$tid\">Oznaci temu</a>";
    echo"</div>";
    if ($page == 1) {
        $posts_per_page = 4;
        mysql_query("UPDATE fun_topics SET views='" . $vws . "' WHERE  id='" . $tid . "'");
    } 
    if ($page > 1) {
        $limit_start--;
    } 

    if ($page > 1) {
        $ppage = $page-1;
        echo "<div class=\"center border_bottom border_top\"><a href=\"index.php?viewtpc&amp;page=$ppage&amp;tid=$tid\"><img src=\"images/up.png\"/></a></div>";
    } 
    $sql = "SELECT id, text, uid, dtpost, quote FROM fun_posts WHERE tid='" . $tid . "' ORDER BY dtpost LIMIT $limit_start, $posts_per_page";
    $posts = mysql_query($sql);
    while ($post = mysql_fetch_array($posts)) {
        $unick = getnick_uid($post[2]);

        $avlink = getavatar($post[2]);
        if ($avlink != "") {
            $avatar = "<img src=\"$avlink\" height=\"25\" width=\"25\" alt=\"avatar\"/>";
        } else {
            $avatar = "<img src=\"images/nopic.jpg\" height=\"25\" width=\"25\" alt=\"avatar\"/>";
        } 
        $usl = "<a href=\"index.php?viewuser&amp;who=$post[2]\">$unick</a>";
        $pst = parsemsg($post[1], $sid);
        $topt = "<a href=\"index.php?pstopt&amp;pid=$post[0]&amp;page=$page&amp;fid=$tinfo[5]\">*</a>";
        if ($post[4] > 0) {
            $qtl = "<div class=\"titl\"><i><a href=\"index.php?viewtpc&amp;tid=$tid&amp;pst=\">(quote:p=blaze,d=16-04-2006)</a></i></div>";
        } 
        if ($go == $post[0]) {
            $fli = "<img src=\"img/img_44.png\"/>";
        } else {
            $fli = "";
        } 
        $dtot = date("d-m-y - H:i:s", $post[3]);
        echo "<div class=\"comment comm_adv\"><table border='0' width='100%' id='download'><tr><td align='left' width='1' height='1'>$avatar</td><td> $usl <br /> $dtot </td></tr></table> $fli$pst $topt</div>";
        echo "";
    } 
    // /to here
    if ($page < $num_pages) {
        $npage = $page + 1;
        echo "<div class=\"center border_top border_bottom\"><a href=\"index.php?viewtpc&amp;page=$npage&amp;tid=$tid\"><img src=\"images/down.png\"/></a></div>";
    } 
    echo "<form action=\"genproc.php?post\" method=\"post\">";
    echo "<textarea name=\"reptxt\" rows=\"2\" maxlength=\"500\"/></textarea>";
    echo "<input type=\"hidden\" name=\"tid\" value=\"$tid\"/>";
    echo "<input type=\"hidden\" name=\"qut\" value=\"$qut\"/>";
    echo "<input type=\"submit\" class=\"button\" value=\"Upisi\"/>";
    echo "</form>";
    $fid = $tinfo[5];
    $fname = getfname($fid);
    $cid = mysql_fetch_array(mysql_query("SELECT cid FROM fun_forums WHERE id='" . $fid . "'"));
    $cinfo = mysql_fetch_array(mysql_query("SELECT name FROM fun_fcats WHERE id='" . $cid[0] . "'"));
    $cname = $cinfo[0];

    $cid = mysql_fetch_array(mysql_query("SELECT cid FROM fun_forums WHERE id='" . $fid . "'"));
    if ($cid[0] > 0) {
        $cinfo = mysql_fetch_array(mysql_query("SELECT name FROM fun_fcats WHERE id='" . $cid[0] . "'"));
        $cname = htmlspecialchars($cinfo[0]);
        echo "<div class=\"comment comm_adv\"><a href=\"index.php?viewcat&amp;cid=$cid[0]\">";
        echo "<img src=\"img/img_42.png\">$cname</a>";
    } else {
        $cid = mysql_fetch_array(mysql_query("SELECT clubid FROM fun_forums WHERE id='" . $fid . "'"));
        $cinfo = mysql_fetch_array(mysql_query("SELECT name FROM fun_clubs WHERE id='" . $cid[0] . "'"));
        $cname = htmlspecialchars($cinfo[0]);
        echo "<div class=\"comment comm_adv\"><a href=\"index.php?gocl&amp;clid=$cid[0]\">";
        echo "<img src=\"img/img_42.png\">$cname Grupu</a>";
    } 
    $fname = htmlspecialchars($fname);
    echo " &gt;<a href=\"index.php?viewfrm&amp;fid=$fid\">$fname</a> &gt; $tnm";
    echo "</div>";
    echo dnoonline($sid, $uid);
    exit();
} 
// ////////////////////////////////View Forum
else if (isset($_GET['viewfrm'])) {
    $fid = $_GET["fid"];
    $view = $_GET["view"];
    if (!canaccess(getuid_sid($sid), $fid)) {
        addonline(getuid_sid($sid), "im viewing admin forum naughty me", "");

        echo vrhonline($sid, $uid);
        echo "<div class=\"sett_line\"><div class=\"center\">Vi nemate dozvolu da pristupite ovom delu foruma...</div></div>";
        echo dnoonline($sid, $uid);
        exit();
    } 
    addonline(getuid_sid($sid), "Viewing Forum", "");
    $finfo = mysql_fetch_array(mysql_query("SELECT name from fun_forums WHERE id='" . $fid . "'"));
    $fnm = htmlspecialchars($finfo[0]);

    echo vrhonline($sid, $uid);
    $norf = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_rss WHERE fid='" . $fid . "'"));
    if ($norf[0] > 0) {
        echo "<a href=\"rwrss.php?showfrss&amp;fid=$fid\"><img src=\"img/img_30.png\" alt=\"rss\"/>$finfo[0] RSS</a><br/>";
    } 
    echo "<div class=\"titlz\"><a href=\"index.php?newtopic&amp;fid=$fid\"><img src=\"img/img_44.png\">Nova tema</a></div>";

    if ($page > 1) {
        $ppage = $page-1;
        echo "<div class=\"section border_top\">";
        echo "<div class=\"center\"><a href=\"index.php?viewfrm&amp;page=$ppage&amp;fid=$fid&amp;view=$view\"><img src=\"images/up.png\"></a></div>";
        echo "</div>";
    } 
    echo "<div class=\"section border_top\">";

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
            echo "<div class=\"comment comm_adv\"><a href=\"index.php?viewtpc&amp;tid=$topic[0]\">$iml$pltx$tnm($nop[0])$atxt</a></div>";
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
            $iml = "<img src=\"img/img_33.png\">";
        } 
        if ($topic[4] == '1') {
            $iml = "<img src=\"img/img_50.png\">";
        } 
        if ($topic[2] == '1') {
            $iml = "<img src=\"img/img_41.png\">";
        } 
        if ($topic[5] > 0) {
            $iml = "<img src=\"img/img_32.png\">";
        } 
        $atxt = "";
        if ($topic[2] == '1') {
            // closed
            $atxt = "<img src=\"img/img_41.png\">";
        } 
        $tnm = htmlspecialchars($topic[1]);
        echo "<div class=\"sett_line\"><a href=\"index.php?viewtpc&amp;tid=$topic[0]\">$iml$tnm($nop[0])$atxt</a></div>";
    } 

    if ($page < $num_pages) {
        echo "<div class=\"section border_top\">";
        $npage = $page + 1;
        echo "<div class=\"center\"><a href=\"index.php?viewfrm&amp;page=$npage&amp;fid=$fid&amp;view=$view\"><img src=\"images/down.png\"></a></div>";
        echo "</div>";
    } 
    /* if($page>1)
    {
      $ppage = $page-1;
      echo "<a href=\"index.php?viewfrm&amp;page=$ppage&amp;fid=$fid&amp;view=$view\">Starije</a> ";
    }
    if($page<$num_pages)
    {
      $npage = $page+1;
      echo "<a href=\"index.php?viewfrm&amp;page=$npage&amp;fid=$fid&amp;view=$view\">Pogledaj jos</a>";
    }*/
    echo "<div class=\"section border_top\">";
    /*echo "<br/>$page/$num_pages<br/>";
    if($num_pages>2)
    {

        $rets = "<form action=\"index.php\" method=\"get\">";
      $rets .= "Strana: <input name=\"page\" format=\"*N\" size=\"3\"/>";
        $rets .= "<input type=\"submit\" value=\"Idi\"/>";
        $rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
        $rets .= "<input type=\"hidden\" name=\"fid\" value=\"$fid\"/>";
        $rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
		$rets .= "<input type=\"hidden\" name=\"view\" value=\"$view\"/>";
        $rets .= "</form>";

        echo $rets;
    }*/

    echo "<div class=\"titlz\"><a href=\"index.php?newtopic&amp;fid=$fid\"><img src=\"img/img_44.png\">Nova tema</a></div>";
    echo "<div class=\"section border_top\">";
    $cid = mysql_fetch_array(mysql_query("SELECT cid FROM fun_forums WHERE id='" . $fid . "'"));
    if ($cid[0] > 0) {
        $cinfo = mysql_fetch_array(mysql_query("SELECT name FROM fun_fcats WHERE id='" . $cid[0] . "'"));
        $cname = htmlspecialchars($cinfo[0]);
        echo "<div class=\"comment comm_adv\"><a href=\"index.php?viewcat&amp;cid=$cid[0]\">";
        echo "<img src=\"img/img_42.png\">$cname</a></div>";
    } else {
        $cid = mysql_fetch_array(mysql_query("SELECT clubid FROM fun_forums WHERE id='" . $fid . "'"));
        $cinfo = mysql_fetch_array(mysql_query("SELECT name FROM fun_clubs WHERE id='" . $cid[0] . "'"));
        $cname = htmlspecialchars($cinfo[0]);
        echo "<div class=\"comment comm_adv\"><a href=\"grupa.php?gocl&amp;clid=$cid[0]\">";
        echo "<img src=\"img/img_42.png\">$cname Grupa</a></div>";
    } 
    echo dnoonline($sid, $uid);
    exit();
} else if (isset($_GET['newtopic'])) {
    $fid = $_GET["fid"];
    if (!canaccess(getuid_sid($sid), $fid)) {
        echo vrhonline($sid, $uid);
        echo "<div class=\"sett_line\"><div class=\"center\">Vi nemate dozvolu da pristupite ovom delu foruma...</div></div>";
        echo dnoonline($sid, $uid);
        exit();
    } 
    addonline(getuid_sid($sid), "Creating new topic", "index.php?online");

    echo vrhonline($sid, $uid);
    echo "<form action=\"genproc.php?newtopic\" method=\"post\">";
    echo "<div class=\"sett_line\">Tema:<br /><input name=\"ntitle\" maxlength=\"30\"/></div>";
    echo "<div class=\"sett_line\">Opis teme:<br /><textarea name=\"tpctxt\" rows='6' /></textarea></div>";
    echo "<input type=\"hidden\" name=\"fid\" value=\"$fid\"/>";
    echo "<input type=\"submit\" class=\"button\" value=\"Napravi\"/>";
    echo "<form>";

    echo "<div class=\"section border_top\"><div class=\"comment comm_adv\"><a href=\"index.php?viewfrm&amp;fid=$fid\">";
    $fname = getfname($fid);
    echo "$fname</a></div>";
    echo dnoonline($sid, $uid);
    exit();
} 
// ////////////////////////////////////////Post reply
else if (isset($_GET['post'])) {
    $tid = $_GET["tid"];

    $tfid = mysql_fetch_array(mysql_query("SELECT fid FROM fun_topics WHERE id='" . $tid . "'"));
    $fid = $tfid[0];
    if (!canaccess(getuid_sid($sid), $fid)) {
        echo vrhonline($sid, $uid);
        echo "<div class=\"sett_line\"><div class=\"center\">Vi nemate dozvolu da pristupite ovom delu foruma...</div></div>";
        echo dnoonline($sid, $uid);
        exit();
    } 
    addonline(getuid_sid($sid), "Posting reply", "");

    echo vrhonline($sid, $uid);

    $qut = $_GET["qut"];
    echo "<form action=\"genproc.php?post\" method=\"post\">";
    echo "Poruka:<input name=\"reptxt\" maxlength=\"500\"/><br/>";
    echo "<input type=\"hidden\" name=\"tid\" value=\"$tid\"/>";
    echo "<input type=\"hidden\" name=\"qut\" value=\"$qut\"/>";
    echo "<input type=\"submit\" value=\"Upisi\"/>";
    echo "</form>";

    $fid = getfid($tid);
    $fname = getfname($fid);
    echo "<br/><br/><a href=\"index.php?viewtpc&amp;tid=$tid\">";
    echo "Vrati se u temu</a>";
    echo "<br/><a href=\"index.php?viewfrm&amp;fid=$fid\">";
    echo "$fname</a><br/>";
    echo dnoonline($sid, $uid);
    exit();
} else if (isset($_GET['viewtpl'])) {
    $who = $_GET["who"];
    addonline(getuid_sid($sid), "Viewing a poll", "");

    echo vrhonline($sid, $uid);
    echo "<p>";
    $uid = getuid_sid($sid);
    $pollid = mysql_fetch_array(mysql_query("SELECT pollid FROM fun_topics WHERE id='" . $who . "'"));
    if ($pollid[0] > 0) {
        $polli = mysql_fetch_array(mysql_query("SELECT id, pqst, opt1, opt2, opt3, opt4, opt5, pdt FROM fun_polls WHERE id='" . $pollid[0] . "'"));
        if (trim($polli[1]) != "") {
            $qst = parsepm($polli[1], $sid);
            echo $qst . "<br/><br/>";
            $vdone = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_presults WHERE uid='" . $uid . "' AND pid='" . $pollid[0] . "'"));
            $nov = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_presults WHERE pid='" . $pollid[0] . "'"));
            $nov = $nov[0];
            if ($vdone[0] > 0) {
                $voted = true;
            } else {
                $voted = false;
            } 
            $opt1 = $polli[2];
            if (trim($opt1) != "") {
                $opt1 = htmlspecialchars($opt1);
                $nov1 = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_presults WHERE pid='" . $pollid[0] . "' AND ans='1'"));
                $nov1 = $nov1[0];
                if ($nov > 0) {
                    $per = floor(($nov1 / $nov) * 100);
                    $rests = "Glasova: $nov1($per%)";
                } else {
                    $rests = "Glasova: 0(0%)";
                } 
                if ($voted) {
                    $lnk = "1.$opt1 $rests<br/>";
                } else {
                    $lnk = "1.<a href=\"genproc.php?votepl&amp;plid=$pollid[0]&amp;ans=1\">$opt1</a> $rests<br/>";
                } 
                echo "$lnk";
            } 
            $opt2 = $polli[3];
            if (trim($opt2) != "") {
                $opt2 = htmlspecialchars($opt2);
                $nov2 = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_presults WHERE pid='" . $pollid[0] . "' AND ans='2'"));
                $nov2 = $nov2[0];
                if ($nov > 0) {
                    $per = floor(($nov2 / $nov) * 100);
                    $rests = "Glasova: $nov2($per%)";
                } else {
                    $rests = "Glasova: 0(0%)";
                } 
                if ($voted) {
                    $lnk = "2.$opt2 $rests<br/>";
                } else {
                    $lnk = "2.<a href=\"genproc.php?votepl&amp;plid=$pollid[0]&amp;ans=2\">$opt2</a> $rests<br/>";
                } 
                echo "$lnk";
            } 
            $opt3 = $polli[4];
            if (trim($opt3) != "") {
                $opt3 = htmlspecialchars($opt3);
                $nov3 = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_presults WHERE pid='" . $pollid[0] . "' AND ans='3'"));
                $nov3 = $nov3[0];
                if ($nov > 0) {
                    $per = floor(($nov3 / $nov) * 100);
                    $rests = "Glasova: $nov3($per%)";
                } else {
                    $rests = "Glasova: 0(0%)";
                } 
                if ($voted) {
                    $lnk = "3.$opt3 $rests<br/>";
                } else {
                    $lnk = "3.<a href=\"genproc.php?votepl&amp;plid=$pollid[0]&amp;ans=3\">$opt3</a> $rests<br/>";
                } 
                echo "$lnk";
            } 
            $opt4 = $polli[5];
            if (trim($opt4) != "") {
                $opt4 = htmlspecialchars($opt4);
                $nov4 = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_presults WHERE pid='" . $pollid[0] . "' AND ans='4'"));
                $nov4 = $nov4[0];
                if ($nov > 0) {
                    $per = floor(($nov4 / $nov) * 100);
                    $rests = "Glasova: $nov4($per%)";
                } else {
                    $rests = "Glasova: 0(0%)";
                } 
                if ($voted) {
                    $lnk = "4.$opt4 $rests<br/>";
                } else {
                    $lnk = "4.<a href=\"genproc.php?votepl&amp;plid=$pollid[0]&amp;ans=4\">$opt4</a> $rests<br/>";
                } 
                echo "$lnk";
            } 
            $opt5 = $polli[6];
            if (trim($opt5) != "") {
                $opt5 = htmlspecialchars($opt5);
                $nov5 = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_presults WHERE pid='" . $pollid[0] . "' AND ans='5'"));
                $nov5 = $nov5[0];
                if ($nov > 0) {
                    $per = floor(($nov5 / $nov) * 100);
                    $rests = "Glasova: $nov5($per%)";
                } else {
                    $rests = "Glasova: 0(0%)";
                } 
                if ($voted) {
                    $lnk = "5.$opt5 $rests<br/>";
                } else {
                    $lnk = "5.<a href=\"genproc.php?votepl&amp;plid=$pollid[0]&amp;ans=5\">$opt5</a> $rests<br/>";
                } 
                echo "$lnk";
            } 
            echo "" . date("d m y - H:i", $polli[7]) . "";
        } else {
            echo "<img src=\"images/notok.gif\" alt=\"x\"/>Anketa nepostoji ili je obrisana!";
        } 
    } else {
        echo "<img src=\"images/notok.gif\" alt=\"x\"/>Korisnik nema anketu";
    } 
    echo "</p>";
    echo dnoonline($sid, $uid);
    exit();
} 
// ////////////////////////////////////////Post Opcijas
else if (isset($_GET['pstopt'])) {
    $pid = $_GET["pid"];
    $page = $_GET["page"];
    $fid = $_GET["fid"];
    addonline(getuid_sid($sid), "Post Opcijas", "");
    $pinfo = mysql_fetch_array(mysql_query("SELECT uid,tid, text  FROM fun_posts WHERE id='" . $pid . "'"));
    $trid = $pinfo[0];
    $tid = $pinfo[1];
    $ptext = htmlspecialchars($pinfo[2]);

    echo vrhonline($sid, $uid);

    echo "<p align=\"center\">";
    echo "<b>Opcije poruke</b>";

    echo "</p>";
    echo "<p>";
    $trnick = getnick_uid($trid);
    echo "<a href=\"inbox.php?sendpm&amp;who=$trid\">&#187;Posalji poruku $trnick</a><br/>";
    echo "<a href=\"index.php?viewuser&amp;who=$trid\">&#187;Pogledaj $trnick Profil</a><br/>"; 
    // echo "<a href=\"index.php?post&amp;tid=$tid&amp;qut=$pid\">&#187;Quote</a><br/>";
    echo "<a href=\"genproc.php?rpost&amp;pid=$pid\">&#187;Obavesti administraciju</a><br/>";
    echo "<a href=\"index.php?viewtpc&amp;tid=$tid&amp;page=$page\">&#171;Vrati se na temu</a><br/>";
    if (ismod(getuid_sid($sid))) {
        echo "<br/>Poruka: ";
        echo "<form action=\"modproc.php?edtpst&amp;pid=$pid\" method=\"post\">";
        echo "<textarea name=\"ptext\" rows=\"5\"/>$pinfo[2]</textarea>";
        echo "<input type=\"submit\" value=\"Izmeni\"/>";
        echo "</form>";
        echo "<br/>";
        echo "<br/><a href=\"modproc.php?delp&amp;pid=$pid\">&#187;Obrisi</a><br/>";
    } 
    echo "</p>";
    echo dnoonline($sid, $uid);
    exit();
} else if (isset($_GET['tpcopt'])) {
    $tid = $_GET["tid"];
    addonline(getuid_sid($sid), "Topic Opcijas", "");
    $tinfo = mysql_fetch_array(mysql_query("SELECT name,fid, authorid, text, pinned, closed  FROM fun_topics WHERE id='" . $tid . "'"));
    $trid = $tinfo[2];
    $ttext = htmlspecialchars($tinfo[3]);
    $tname = htmlspecialchars($tinfo[0]);

    echo vrhonline($sid, $uid);

    echo "<p align=\"center\">";
    echo "<b>Opcije teme</b>";

    echo "</p>";
    echo "<p>";
    echo "Tema br: <b>$tid</b><br/>";
    $trnick = getnick_uid($trid);
    echo "<a href=\"inbox.php?sendpm&amp;who=$trid\">&#187;Posalji poruku $trnick</a><br/>";
    echo "<a href=\"index.php?viewuser&amp;who=$trid\">&#187;Pogledaj $trnick Profil</a><br/>"; 
    // echo "<a href=\"index.php?post&amp;tid=$tid&amp;qut=$pid\">&#187;Quote</a><br/>";
    $plid = mysql_fetch_array(mysql_query("SELECT pollid FROM fun_topics WHERE id='" . $tid . "'"));
    if ($plid[0] == 0) {
        if (ismod($uid)) {
            echo "<a href=\"index.php?pltpc&amp;tid=$tid\">&#187;Dodaj Anketu</a><br/>";
        } 
    } else {
        if (ismod($uid)) {
            echo "<a href=\"genproc.php?dltpl&amp;tid=$tid\">&#187;Ukloni anketu</a><br/>";
        } 
    } 
    echo "<a href=\"genproc.php?rtpc&amp;tid=$tid\">&#187;Obavesti administraciju</a><br/>";
    echo "<a href=\"index.php?viewtpc&amp;tid=$tid&amp;page=1\">&#171;Vrati se na temu</a><br/>";
    if (ismod(getuid_sid($sid))) {
        echo "<br/>Naziv: ";
        echo "<form action=\"modproc.php?rentpc&amp;tid=$tid\" method=\"post\">";
        echo "<input name=\"tname\" value=\"$tname\" maxlength=\"25\" value=\"$tname\"/> ";

        echo "<input type=\"submit\" value=\"Rename\"/>";
        echo "</form>";

        echo "<br/>Poruka: ";
        echo "<form action=\"modproc.php?edttpc&amp;tid=$tid\" method=\"post\">";
        echo "<input name=\"ttext\" value=\"$ttext\" maxlength=\"500\" value=\"$pmtext\"/> ";

        echo "<input type=\"submit\" value=\"Izmeni\"/>";
        echo "</form>";

        echo "<br/><a href=\"modproc.php?delt&amp;tid=$tid\">&#187;Obrisi</a><br/>";
        echo "<br/>";
        if ($tinfo[5] == '1') {
            $ctxt = "Otvori";
            $cact = "0";
        } else {
            $ctxt = "Zatvori";
            $cact = "1";
        } 
        echo "<a href=\"modproc.php?clot&amp;tid=$tid&amp;tdo=$cact\">&#187;$ctxt</a><br/>";
        if ($tinfo[4] == '1') {
            $ptxt = "Otkaci";
            $pact = "0";
        } else {
            $ptxt = "Zakaci";
            $pact = "1";
        } 
        echo "<a href=\"modproc.php?pint&amp;tid=$tid&amp;tdo=$pact\">&#187;$ptxt</a><br/>"; 
        // echo "<a href=\"index.php?post&amp;tid=$tid&amp;qut=$pid\">&#187;Quote</a><br/>";
        echo "<br/>Move to:<br/>";
        $forums = mysql_query("SELECT id, name FROM fun_forums WHERE clubid='0'");

        echo "<form action=\"modproc.php?mvt&amp;tid=$tid\" method=\"post\">";
        echo "<select name=\"mtf\">";
        while ($forum = mysql_fetch_array($forums)) {
            echo "<option value=\"$forum[0]\">$forum[1]</option>";
        } 
        echo "</select><br/>";
        echo "<input type=\"submit\" value=\"Premesti\"/>";
        echo "</form>";
    } 
    echo "</p>";
    echo dnoonline($sid, $uid);
    exit();
} else {
    // ///////////////////////Main Page Here
    $log = $_GET['log'];
    if ($log == "da") {
        $uid = getuid_sid($sid);
        $whonick = getnick_uid($uid);
        $logoutses = mysql_query("DELETE FROM fun_ses WHERE uid='" . $uid . "'");
        $logoutonline = mysql_query("DELETE FROM fun_online WHERE userid='" . $uid . "'");
    }

$fotkica = mysql_fetch_array(mysql_query("SELECT adr FROM fotkice ORDER BY RAND()  LIMIT 1"));
    ?>
<div style='background-color:#000;text-align:center;'><img style='width:100%;' src='<?echo $fotkica[0];?>' /></div>
<div class="section border_top"></div>
<div class="section_title"><div class="marker"><small>Potreban ti je RS Profil? <a href="register.php">Napravi ga ovde</a></small></div></div>
<div class="section border_top"></div>
<div class="section_title"><div class="marker">Login na RS</div></div>
<form action="login.php" method="get" onsubmit="document.forms[0].submit.disabled = 'true'; document.forms[0].submit.value = 'Prijavljivanje u toku...';">
<div class='sett_line'><div style='margin: 2px'><small>Korisnicko ime (Nadimak):</small> <br /> <input name="loguid" maxlength="30"/></div></div>
<div class='sett_line'><div style='margin: 2px'><small>Lozinka:</small>  <br /> <input type="password" name="logpwd" maxlength="30"/></div></div>
<div class='sett_line'><div style='margin: 2px'><input style="color:#555;font-weight:bold;opacity:1;background-color:#eee;border:1px solid #aaa;text-shadow:#fff 0 1px 0;" type="submit" value="Prijava"/></div></div></div>
</form>
<div class='notif border_bottom_light'><small>Imate problema sa logovanjem?</small> <br /> <small><a href="login.php">Pokusajte alternativnu prijavu</a></small> </div>



<?php
    include ("portal/index.php");
    echo dnooffline();
    exit();
} 

echo "</body>";
echo "</html>";

?>
