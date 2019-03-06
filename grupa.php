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
$clid = $_GET["clid"];
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
    if ((islogged($sid) == true) || ($uid == 0)) {
    echo vrhonline($sid, $uid);
    $myid = getuid_sid($sid);
	
    echo "<div class='comment border_top'><a href=\"grupa.php?clubs\">Sve grupe</a></div>";
    echo "<div class='comment border_top'><a href=\"grupa.php?myclub\">Moje grupe</a></div>";
    //echo "<div class='comment border_top'><a href=\"lists.php?clm&amp;who=$myid&amp;who=$uid\">Grupe u kojima sam ja clan</a></div>";
    //echo "<div class='comment border_top'><a href=\"lists.php?pclb&amp;who=$uid\">Najpopularnije grupe</a></div>";
    //echo "<div class='comment border_top'><a href=\"lists.php?aclb&amp;who=$uid\">Najaktivnije grupe</a></div>";
    //echo "<div class='comment border_top'><a href=\"lists.php?rclb&amp;who=$uid\">Slucajna grupa</a></div>";
    $ncl = mysql_fetch_array(mysql_query("SELECT id, name FROM fun_clubs ORDER BY created DESC LIMIT 1"));
    echo "<div class='sett_line border_top'>Najnovija grupa: <a href=\"grupa.php?clid=$ncl[0]\">" . htmlspecialchars($ncl[1]) . "</a></div>";



    echo dnoonline($sid, $uid);
    exit();
   }
} else if (isset($_GET['clmop'])) {
if ((islogged($sid) == true) || ($uid == 0)) {
    $clid = $_GET["clid"];
    $who = $_GET["who"];
    addonline(getuid_sid($sid), "Vrsi izmene u grupi", "");

    echo vrhonline($sid, $uid);
    $whnick = getnick_uid($who);
    echo "<div class='sett_line'>$whnick</div>";
    $exs = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_clubmembers WHERE uid='" . $who . "' AND clid=" . $clid . ""));
    $cow = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_clubs WHERE owner='" . $uid . "' AND id=" . $clid . ""));
    if ($exs[0] > 0 && $cow[0] > 0) {
        echo "<div class='comment'><a href=\"genproc.php?dcm&amp;who=$who&amp;clid=$clid\">Izbaci $whnick iz grupe</a></div>";
        //echo "<a href=\"index.php?gcp&amp;who=$who&amp;clid=$clid\">$whnick's Club Points</a><br/>";
        //echo "<a href=\"index.php?gpl&amp;who=$who&amp;clid=$clid\">&#187;Give $whnick Plusses</a><br/>";
    } else {
        echo "<div class='sett_line'>GRESKA!!!</div>";
    } 

    echo dnoonline($sid, $uid);
    exit();
	}
}
else if (isset($_GET['myclub'])) {
if ((islogged($sid) == true) || ($uid == 0)) {
    addonline(getuid_sid($sid), "My Clubs", "");

    echo vrhonline($sid, $uid);
echo "<div class=\"section border_top\"></div>";
echo "<div class=\"section_title\"><div class=\"marker\">Vase grupe</div></div>";
	$uclubs = mysql_query("SELECT id, name, logo FROM fun_clubs WHERE owner='" . $uid . "'");
        while ($club = mysql_fetch_array($uclubs)) {
		if ($club[2] != "") {
        $avatar = "<img src=\"$club[2]\" height=\"25\" width=\"25\" alt=\"avatar\"/>";
    } else {
        $avatar = "<img src=\"images/logo.png\" height=\"25\" width=\"25\" alt=\"avatar\"/>";
    } 
            echo "<div class='titlz'><p align='center'>$avatar<br /><a href=\"grupa.php?clid=$club[0]\">$club[1]</a><br />";
            echo "<a href=\"genproc.php?dlcl&amp;clid=$club[0]\">obrisi grupu</a></p></div> ";
        } 
        
        $tema = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_topics WHERE authorid='" . $uid . "'"));
    //if ($tema[0] >= 100) {
      //  echo "<br /><div class='titl'>Da bi ste kreirali sopstvenu grupu... Potrebno je da imate 100 otvorenih tema u forumu... :) zato se bacite na posao... ;)</div>";
    //} else {
        

            echo "<div class='sett_line'><a href=\"grupa.php?addcl\">Otvori grupu</a></div>";

    //}

    echo dnoonline($sid, $uid);
    exit();
	}
}  
else if (isset($_GET['clubs'])) {
if ((islogged($sid) == true) || ($uid == 0)) {
    addonline(getuid_sid($sid), "My Clubs List", "");

    echo vrhonline($sid, $uid);
echo "<div class=\"section border_top\"></div>";
echo "<div class=\"section_title\"><div class=\"marker\">Lista grupa</div></div>";
    // ////ALL LISTS SCRIPT <<
    if ($page == "" || $page <= 0)$page = 1;
    $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_clubs"));
    $num_items = $noi[0]; //changable
    $items_per_page = 5;
    $num_pages = ceil($num_items / $items_per_page);
    if (($page > $num_pages) && $page != 1)$page = $num_pages;
    $limit_start = ($page-1) * $items_per_page; 
    // changable sql
    $sql = "SELECT id, name, owner, description, created, logo FROM fun_clubs ORDER BY created DESC LIMIT $limit_start, $items_per_page";

    if ($page > 1) {
        $ppage = $page-1;
        echo "<div class='center border_top'><a href=\"index.php?$action&amp;page=$ppage&amp;view=$view\"><img src='images/up.png' /></a></div>";
    } 
    $items = mysql_query($sql);
    if (mysql_num_rows($items) > 0) {
        while ($item = mysql_fetch_array($items)) {
            $item[1] = htmlspecialchars($item[1]);
            $mems = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_clubmembers WHERE clid='" . $item[0] . "' AND accepted='1'"));
            $lnk = "<a href=\"grupa.php?clid=$item[0]\">$item[1]</a>";
			if ($item[5] != "") {
        $avatar = "<img src=\"$item[5]\" height=\"25\" width=\"25\" alt=\"avatar\"/>";
    } else {
        $avatar = "<img src=\"images/logo.png\" height=\"25\" width=\"25\" alt=\"avatar\"/>";
    } 
            echo "<div class='comment'><table border='0' width='100%' id='download'><tr><td align='center' width='1' height='1'>$avatar</td><td>$lnk<br/><small>Grupa: $mems[0] obozavalac/a</small></td></tr></table></div>";
        } 
    } 
    if ($page < $num_pages) {
        $npage = $page + 1;
        echo "<div class='center border_top'><a href=\"index.php?$action&amp;page=$npage&amp;view=$view\"><img src='images/up.png' /></a></div>";
    } 
    echo dnoonline($sid, $uid);
    exit();
	}
} else if ($clid) {
    $clid = $_GET["clid"];
	$uclanjenje = $_GET["uclanjenje"];
	$clid = $_GET["clid"];
  $who = $_GET["who"];
      $shtxt = $_POST["shtxt"];
  $shid = $_GET["shid"];
    $clinfo = mysql_fetch_array(mysql_query("SELECT name, owner, description, rules, logo, plusses, created FROM fun_clubs WHERE id='" . $clid . "'"));
    addonline(getuid_sid($sid), "Viewing A Club", "");
    $clnm = htmlspecialchars($clinfo[0]);
if (isuser($uid)) { echo vrhonline($sid, $uid); }
	  if ($uclanjenje=="1"){
        $res = mysql_query("INSERT INTO fun_clubmembers SET uid='".$uid."', clid='".$clid."', accepted='1', points='0', joined='".time()."'");
        if($res)
        {
            echo "<div class='notif border_bottom'>Uspesno ste postali clan grupe... <br /> Postuj te pravila grupe koje nalaze vlasnik...</div>";
        }else{
            echo "<div class='error border_bottom'>GRESKA U BAZI...</div>";
        }
		}
		/////// INSERT IN SH BOX
   if ($shtxt=="")
   {
   	   echo "";
   } else {
    $shtxt = $shtxt;
    //$uid = getuid_sid($sid);
    $shtm = time();
    $clinfo = mysql_fetch_array(mysql_query("SELECT name, owner, description, rules, logo, plusses, created FROM fun_clubs WHERE id='" . $clid . "'"));
	if ($clinfo[1] == $uid) {
    $res = mysql_query("INSERT INTO fun_shouts_grupa SET shout='".$shtxt."', shouter='".$uid."', shtime='".$shtm."', grupa='".$clid."', club='1'");
	} else {
    $res = mysql_query("INSERT INTO fun_shouts_grupa SET shout='".$shtxt."', shouter='".$uid."', shtime='".$shtm."', grupa='".$clid."'");
	}
    if($res)
    {
    $shts = mysql_fetch_array(mysql_query("SELECT shouts from fun_users WHERE id='".$uid."'"));
    $shts = $shts[0]+1;
    mysql_query("UPDATE fun_users SET shouts='".$shts."' WHERE id='".$uid."'");
    echo "<div class=\"pad\"><div class=\"notif border_bottom\">Vasa poruka je uspesno postavljena</div></div>";
    }else{
        echo "<div class=\"pad\"><div class=\"error border_bottom\">Vasa poruka nije postavljena! Mogucnost da je greska u bazi, pa vas molimo da pokusate kasnije!</div></div>";
    }
   }
   ///////
   
   /////// DELETE FROM SH BOX
   if(ismod(getuid_sid($sid)))
      {
	  
   if ($shid=="")
   {
   	   echo "";
   } else {
   
    $shid = $shid;
    
   $sht = mysql_fetch_array(mysql_query("SELECT shouter, shout FROM fun_shouts_grupa WHERE id='".$shid."'"));
  $msg = getnick_uid($sht[0]);
  $msg .= ": ".htmlspecialchars((strlen($sht[1])<20?$sht[1]:substr($sht[1], 0, 20)));
  $res = mysql_query("DELETE FROM fun_shouts_grupa WHERE id ='".$shid."'");
  if($res)
          {		 
    
          mysql_query("DELETE FROM fun_komentari_grupa WHERE komowner ='".$shid."'");
		  mysql_query("INSERT INTO fun_mlog SET 'shouts', details='<b>".getnick_uid(getuid_sid($sid))."</b> Deleted the shout on group <b>".$shid."</b> - $msg', actdt='".time()."'");
            echo "<div class=\"pad\"><div class=\"notif border_bottom\">Poruka je uspesno obrisana.....</div></div>";
    }else{
        echo "<div class=\"pad\"><div class=\"error border_bottom\">Poruka nije obrisana.....</div></div>";
    }
   } 
   }
echo "<div class=\"section border_top\"></div>";
echo "<div class=\"section_title\"><div class=\"marker\">$clnm</div></div>";
$ismem = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_clubmembers WHERE clid='".$clid."' AND uid='".getuid_sid($sid)."'"));
if ($ismem[0] > 0) {
            // unjoin
            if ($clinfo[1] != $uid) {
                $bambolejo = "";
            } 
        } else {
		$bambolejo = "<form action='grupa.php?clid=$clid&amp;uclanjenje=1' method='post'>
		<input type=\"hidden\" name=\"who\" value=\"$uid\"/>
		<input class=\"button\" type=\"submit\" value=\"Pridruzi se\"/>
		</form>";
        } 
    if (trim($clinfo[4]) == "") {
        echo "<table border='0' width='100%' id='download'><tr><td align='center' width='1' height='1'><img src=\"images/logo.png\" alt=\"logo\"/></td><td>$bambolejo</td></tr></table>";
		
    $mems = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_clubmembers WHERE clid='" . $clid . "' AND accepted='1'"));
    echo "$mems[0] ljudi voli ovu grupu.";
    } else {
        echo "<table border='0' width='100%' id='download'><tr><td align='center' width='1' height='1'><img src=\"$clinfo[4]\" alt=\"logo\"/></td><td>$bambolejo</td></tr></table>";
		
    $mems = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_clubmembers WHERE clid='" . $clid . "' AND accepted='1'"));
    echo "$mems[0] ljudi voli ovu grupu.";
    } 
   
    if (($ismem[0] > 0) || ismod($uid)) {
        $noa = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_announcements WHERE clid='" . $clid . "'"));
        //echo "<div class='sett_line border_top border_bottom'/><a href=\"lists.php?annc&amp;clid=$clid\">Novosti iz grupe($noa[0])</a> &#149; ";
		$noa = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_chat WHERE rid='" . $rid[0] . "'"));
        //echo "<a href=\"chat.php?sid=$sid&amp;rid=$rid[0]\">Caskaonica($noa[0])</a> &#149; ";
		
    $fid = mysql_fetch_array(mysql_query("SELECT id FROM fun_forums WHERE clubid='" . $clid . "'"));
    $rid = mysql_fetch_array(mysql_query("SELECT id FROM fun_rooms WHERE clubid='" . $clid . "'"));
    $tps = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_topics WHERE fid='" . $fid[0] . "'"));
    $pss = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_posts a INNER JOIN fun_topics b ON a.tid = b.id WHERE b.fid='" . $fid[0] . "'"));
        echo "<div class='comment border_top_light border_bottom_light'><b>Zid</b> 
 &#149; <a href=\"index.php?viewfrm&amp;fid=$fid[0]\">Forum($tps[0]/$pss[0])</a> 
 &#149; <a href=\"grupa.php?grupa=$clid&amp;info\">Informacije</a>";
        echo "</div>";
        $ismem = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_clubmembers WHERE clid='" . $clid . "' AND uid='" . getuid_sid($sid) . "'"));
///////////////////////////////////////////////////////////////////
///////
if (isuser($uid)) {
   echo "<div class=\"section\">";
	echo "<form action=\"grupa.php?clid=$clid\" method=\"post\">";
	echo "<textarea name=\"shtxt\" rows=\"2\" value=\"\"
                		onmouseover=\"focusInputRazglas(this);focus(this);\" onmouseout=\"blur(this);blurInputRazglas(this);\" 
                		xsemc:shortcut=\"shtxt\" ></textarea>";
	echo "<input class=\"button\" type=\"submit\" value=\"Posalji\"/>";    
	echo "</form>";
   echo "</div></div>";}
   else
   {
   echo "<div class='notif border_bottom'>Vi niste clan $stitle da bi mogli da pisete u grupama....<br />Molim vas da se <a href='register.php'>Registrujete</a> ili se <a href='login.php'>Prijavite</a> ako ste vec nas korisnik...</div>";
   }
   
    $who = $_GET["who"];
    //////ALL LISTS SCRIPT <<

    if($page=="" || $page<=0)$page=1;
    if($who=="")
    {
    $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_shouts_grupa WHERE grupa='".$clid."'"));
    }else{
    $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_shouts_grupa WHERE shouter='".$who."', grupa='".$clid."'"));
    }
    $num_items = $noi[0]; //changable
    $items_per_page= 10;
    $num_pages = ceil($num_items/$items_per_page);
    if(($page>$num_pages)&&$page!=1)$page= $num_pages;
    $limit_start = ($page-1)*$items_per_page;

    //changable sql
    if($who =="")
    {
        $sql = "SELECT id, shout, shouter, shtime, club  FROM fun_shouts_grupa WHERE grupa='".$clid."' ORDER BY shtime DESC LIMIT $limit_start, $items_per_page";
}else{
    $sql = "SELECT id, shout, shouter, shtime, club  FROM fun_shouts_grupa  WHERE shouter='".$who."' AND grupa='".$clid."' ORDER BY shtime DESC LIMIT $limit_start, $items_per_page";
}
    $items = mysql_query($sql);
    echo mysql_error();
    if(mysql_num_rows($items)>0)
    {
    while ($item = mysql_fetch_array($items))
    {
	if ($item[4] == "0") {
        $shnick = getnick_uid($item[2]);
        $sht = parsepm($item[1]);
        $shdt = date("d m y-H:i", $item[3]);
      $avlink = getavatar($item[2]);
        if ($avlink!=""){
      $avatar = "<a href=\"index.php?viewuser&amp;who=$item[2]\" title='$shnick'><img src=\"$avlink\" alt='$shnick' height='35' width='35' /></a>";
      }else{
      $avatar = "<a href=\"index.php?viewuser&amp;who=$item[2]\" title='$shnick'><img src=\"images/nopic.jpg\" alt='$shnick' height='35' width='35' /></a>";
      }
      $lnk = "<div class='feed feed_first'>
			<div class='feed_image'>$avatar</div>
			<div class='feed_content'>
			<div> <a href=\"index.php?viewuser&amp;who=$item[2]\">$shnick</a> $sht</div>
			<div class='feed_content_info'>
			<span class='feed_time_stamp'><small>$shdt</small></span>";
	  }else {
        $shnick = getnick_uid($item[2]);
        $sht = parsepm($item[1]);
        $shdt = date("d m y-H:i", $item[3]);
        if ($clinfo[4]!=""){
      $avatar = "<a href=\"grupa.php?clid=$clid\" title='$clinfo[0]'><img src='$clinfo[4]' alt='$clinfo[0]' height='35' width='35' /></a>";
      }else{
      $avatar = "<a href=\"grupa.php?clid=$clid\" title='$clinfo[0]'><img src='images/logo.png' alt='$clinfo[0]' height='35' width='35' /></a>";
      }
      $lnk = "<div class='feed feed_first'>
			<div class='feed_image'>$avatar</div>
			<div class='feed_content'>
			<div> <a href=\"grupa.php?clid=$clid\">$clnm</a> $sht</div>
			<div class='feed_content_info'>
			<span class='feed_time_stamp'><small>$shdt</small></span>";
	  
	  }
if(ismod(getuid_sid($sid)))
      {
	  
      $dlsh = "<small><a href=\"grupa.php?clid=$clid&amp;shid=$item[0]\"><img src='ico/emblem-unreadable.png' /></a></small>";
      }
if($clinfo[1] == $uid)
      {
      $dlsh = "<small><a href=\"grupa.php?clid=$clid&amp;shid=$item[0]\"><img src='ico/emblem-unreadable.png' /></a></small>";
      }
	  
	  if (isuser($uid)) { 
	  $who = $item[0];
	  $brojkomentara = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_komentari_grupa WHERE komowner='".$who."' AND grupa='".$clid."'"));
            if ($brojkomentara[0] == 1) {
                $komentari = "<small><a href=\"komentari.php?grupa&amp;clid=$clid&amp;who=$who\"><i>Jedan komentar</i></a></small>";
            } else if ($brojkomentara[0] > 0) {
                $komentara = $brojkomentara[0];
                $komentari = "<small><a href=\"komentari.php?grupa&amp;clid=$clid&amp;who=$who\"><i><b>$komentara</b> Komentar/a</i></a></small>";
			} else {
                $komentari = "<small><a href=\"komentari.php?grupa&amp;clid=$clid&amp;who=$who\"><i>Prokomentarisi</i></a></small>";
            } 
	  }else{
	   $who = $item[0];
	  $brojkomentara = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_komentari_grupa WHERE komowner='".$who."' AND grupa='".$clid."'"));
	    if ($brojkomentara[0] == 1) {
                $komentari = "<small><a href=\"#\"><i>Jedan komentar</i></a></small>";
            } else if ($brojkomentara[0] > 0) {
                $komentara = $brojkomentara[0];
                $komentari = "<small><a href=\"#\"><i><b>$komentara</b> Komentar/a</i></a></small>";
			} else {
                $komentari = "<small><a href=\"#\"><i>Prokomentarisi</i></a></small>";
            } 
	  }
	  ///////// 

	  if (isuser($uid)) { 
$brl = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_shout_like_grupa WHERE shid='".$who."' AND liked='1'"));
	 $lajk = mysql_fetch_array(mysql_query("SELECT id FROM fun_shout_like_grupa WHERE shid= '".$who."' AND uid='".$uid."'"));
	  if ($lajk[0]=="")
        {
	  $lajkova = "<small><a href=\"komentari.php?grupa&amp;like=$item[0]&amp;liked=1&amp;clid=$clid&amp;who=$who\">Svidja mi se</a></small>";
	  } else {
		
	  $lajkova = "<small><a href=\"komentari.php?grupa&amp;dislike=$item[0]&amp;liked=0&amp;clid=$clid&amp;who=$who\">Ne svidja mi se</a></small>";
	  
	  } }else{
	  
$brl = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_shout_like_grupa WHERE shid='".$who."' AND liked='1'"));
	 $lajk = mysql_fetch_array(mysql_query("SELECT id FROM fun_shout_like_grupa WHERE shid= '".$who."' AND uid='".$uid."'"));
	  if ($lajk[0]=="")
        {
	  $lajkova = "<small><a href=\"#\">Svidja mi se</a></small>";
	  } else {
		
	  $lajkova = "<small><a href=\"#\">Ne svidja mi se</a><small>";
	  
	  }
	  }
	  /////////
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
<span class='user_profile_link_span'><a href='index.php?viewuser&amp;who=$k_likez[0]'>$lajkovaoje</a><span> i jos <a href='lajkovi.php?grupa&amp;who=$who'>$zaz  prijatelj$koliko</a> $voli ovaj status.</div>";
}else { $likeballon =""; }

    //$brojlajkova = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_shout_like ON a.id = b.uid WHERE b.shid='" . $who . "'"));
      echo "$lnk <small><span> &#183; $komentari</span><span> &#183; $lajkova</span>    $dlsh";
                echo "$likeballon
			</small>
			</div>
			</div>
			</div>
			<div class='clear border_bottom'></div>
			</div>";
    }
    }

    if($page<$num_pages)
    {
      $npage = $page+1;
      echo "<a href=\"grupa.php?clid=$clid&amp;page=$npage\" class='view_more'>Citaj dalje</a>";
  }
///////////////////////////////////////////////////////////////////
        if ($ismem[0] > 0) {
            // unjoin
            if ($clinfo[1] != $uid) {
                if (isuser($uid)) { echo "<div class='comment border_top_light'><a href=\"grupa.php?sid=$sid&amp;clid=$clid&amp;izadji=1\">Izadji iz grupe</a></div>"; }
            } 
        }
        if (isadmin(getuid_sid($sid))) {
            echo "<div class='comment border_top_light'><a href=\"admincp.php?club&amp;clid=$clid\">Admin Tools</a></div>";
        }
    } else {
echo "<div class=\"section border_top\"></div>";
echo "<div class=\"section_title\"><div class=\"marker\">Statistika grupe</div></div>";
    $mems = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_clubmembers WHERE clid='" . $clid . "' AND accepted='1'"));
    echo "Clanova: $mems[0]<br/>";
    echo "Grupa nastala: " . date("d/m/y", $clinfo[6]) . "<br/>";
    $fid = mysql_fetch_array(mysql_query("SELECT id FROM fun_forums WHERE clubid='" . $clid . "'"));
    $rid = mysql_fetch_array(mysql_query("SELECT id FROM fun_rooms WHERE clubid='" . $clid . "'"));
    $tps = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_topics WHERE fid='" . $fid[0] . "'"));
    $pss = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_posts a INNER JOIN fun_topics b ON a.tid = b.id WHERE b.fid='" . $fid[0] . "'"));
        echo "Otvorenih tema: <b>$tps[0]</b>, Ispisanih poruka: <b>$pss[0]</b><br/>";
echo "<div class=\"section border_top\"></div>";
echo "<div class=\"section_title\"><div class=\"marker\">O grupi</div></div>";
        echo htmlspecialchars($clinfo[2]);
echo "<div class=\"section border_top\"></div>";
echo "<div class=\"section_title\"><div class=\"marker\">Pravila grupe</div></div>";
        echo htmlspecialchars($clinfo[3]);
        echo "<br/><br/>";
		if($ismem[0]>0)
		{
			//unjoin 
			if($clinfo[1]!=$uid)
			{
				if (isuser($uid)) { echo "<div class='comment border_top_light'><a href=\"grupa.php?unjc&amp;clid=$clid\">Izclani se iz grupe</a></div>"; }
			}
    } 
	}
	if (isuser($uid)) { dnoonline($sid, $uid); } else { dnooffline(); }
    exit();
}
else if (isset($_GET['fotografije'])) {
if ((islogged($sid) == true) || ($uid == 0)) {
    $clid = $_GET["grupa"];
    addonline(getuid_sid($sid), "fotografije", "");
	if (isuser($uid)) { echo vrhonline($sid, $uid); }
$clinfo = mysql_fetch_array(mysql_query("SELECT name, owner, description, rules, logo, plusses, created FROM fun_clubs WHERE id='" . $clid . "'"));
$clnm = htmlspecialchars($clinfo[0]);

    if (trim($clinfo[4]) == "") {
        echo "<table border='0' width='100%' id='download'><tr><td align='center' width='1' height='1'><img src=\"images/logo.png\" alt=\"logo\"/></td><td>$bambolejo</td></tr></table>";
		
    $mems = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_clubmembers WHERE clid='" . $clid . "' AND accepted='1'"));
    echo "$mems[0] ljudi voli ovu grupu.";
    } else {
        echo "<table border='0' width='100%' id='download'><tr><td align='center' width='1' height='1'><img src=\"$clinfo[4]\" alt=\"logo\"/></td><td>$bambolejo</td></tr></table>";
		
    $mems = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_clubmembers WHERE clid='" . $clid . "' AND accepted='1'"));
    echo "$mems[0] ljudi voli ovu grupu.";
    } 
    $fid = mysql_fetch_array(mysql_query("SELECT id FROM fun_forums WHERE clubid='" . $clid . "'"));
    $rid = mysql_fetch_array(mysql_query("SELECT id FROM fun_rooms WHERE clubid='" . $clid . "'"));
    $tps = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_topics WHERE fid='" . $fid[0] . "'"));
    $pss = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_posts a INNER JOIN fun_topics b ON a.tid = b.id WHERE b.fid='" . $fid[0] . "'"));
        echo "<div class='border_top_light border_bottom_light'><a href=\"grupa.php?clid=$clid\">Zid</a> 
 &#149; <a href=\"index.php?viewfrm&amp;fid=$fid[0]\">Forum($tps[0]/$pss[0])</a> 
 &#149; <a href=\"grupa.php?grupa=$clid&amp;info\">Informacije</a> 
 ";
        echo "</div>";
     

    $fotografije = "SELECT id, imageurl, uid FROM fun_fotografije_grupa WHERE uid='" . $clid . "'";

    $foto = mysql_query($fotografije);
    if (mysql_num_rows($foto) > 0) {
        while ($item = mysql_fetch_array($foto)) {
            if ($item[2] == "") {
                echo "<br /><table border='0' width='100%' id='download'><tr><td align='left' width='1' height='1'><b>Ova grupa nema fotografija.</b></td></tr></table><br /><br />";
            } else {
                echo "<a href='grupa.php?fotopogled&amp;fotka=$clid'><img src='$item[1]' class='fotografija' height='50' width='50'></a>";
            } 
        } 
    } 
    $pregledac = getuid_sid($sid);
    if ($clinfo[1] == $pregledac) {
        echo "<div class='comment comm_adv'><img src='img/img_50.png' /> <a href='grupa.php?upload'>Dodaj fotografiju</a></div>";
    } 
    echo dnoonline($sid, $uid);
    exit();
	}
} else if (isset($_GET['info'])) {
if ((islogged($sid) == true) || ($uid == 0)) {
    $clid = $_GET["grupa"];

    echo vrhonline($sid, $uid);
	$clinfo = mysql_fetch_array(mysql_query("SELECT name, owner, description, rules, logo, plusses, created FROM fun_clubs WHERE id='" . $clid . "'"));
$clnm = htmlspecialchars($clinfo[0]);

    if (trim($clinfo[4]) == "") {
        echo "<table border='0' width='100%' id='download'><tr><td align='center' width='1' height='1'><img src=\"images/logo.png\" alt=\"logo\"/></td><td>$bambolejo</td></tr></table>";
		
    $mems = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_clubmembers WHERE clid='" . $clid . "' AND accepted='1'"));
    echo "$mems[0] ljudi voli ovu grupu.";
    } else {
        echo "<table border='0' width='100%' id='download'><tr><td align='center' width='1' height='1'><img src=\"$clinfo[4]\" alt=\"logo\"/></td><td>$bambolejo</td></tr></table>";
		
    $mems = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_clubmembers WHERE clid='" . $clid . "' AND accepted='1'"));
    echo "$mems[0] ljudi voli ovu grupu.";
    } 
    $fid = mysql_fetch_array(mysql_query("SELECT id FROM fun_forums WHERE clubid='" . $clid . "'"));
    $rid = mysql_fetch_array(mysql_query("SELECT id FROM fun_rooms WHERE clubid='" . $clid . "'"));
    $tps = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_topics WHERE fid='" . $fid[0] . "'"));
    $pss = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_posts a INNER JOIN fun_topics b ON a.tid = b.id WHERE b.fid='" . $fid[0] . "'"));
        echo "<div class='border_top_light border_bottom_light'><a href=\"grupa.php?clid=$clid\">Zid</a> 
 &#149; <a href=\"index.php?viewfrm&amp;fid=$fid[0]\">Forum($tps[0]/$pss[0])</a> 
 &#149; <b>Informacije</b> 
 ";
        echo "</div>";
echo "<div class=\"section border_top\"></div>";
echo "<div class=\"section_title\"><div class=\"marker\">Statistika grupe</div></div>";
    $mems = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_clubmembers WHERE clid='" . $clid . "' AND accepted='1'"));
    echo "Clanova: <a href=\"lists.php?clmem&amp;clid=$clid\">$mems[0]</a><br/>";
    $clinfo = mysql_fetch_array(mysql_query("SELECT name, owner, description, rules, logo, plusses, created FROM fun_clubs WHERE id='" . $clid . "'"));
    echo "Grupa nastala: " . date("d/m/y", $clinfo[6]) . "<br/>";
    $fid = mysql_fetch_array(mysql_query("SELECT id FROM fun_forums WHERE clubid='" . $clid . "'"));
    $rid = mysql_fetch_array(mysql_query("SELECT id FROM fun_rooms WHERE clubid='" . $clid . "'"));
    $tps = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_topics WHERE fid='" . $fid[0] . "'"));
    $pss = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_posts a INNER JOIN fun_topics b ON a.tid = b.id WHERE b.fid='" . $fid[0] . "'"));
        echo "Otvorenih tema: <b>$tps[0]</b>, Ispisanih poruka: <b>$pss[0]</b><br/>";
echo "<div class=\"section border_top\"></div>";
echo "<div class=\"section_title\"><div class=\"marker\">O grupi</div></div>";
        echo htmlspecialchars($clinfo[2]);
echo "<div class=\"section border_top\"></div>";
echo "<div class=\"section_title\"><div class=\"marker\">Pravila grupe</div></div>";
        echo htmlspecialchars($clinfo[3]);


    echo dnoonline($sid, $uid);
exit();
}
}else if (isset($_GET['addcl'])) {
if ((islogged($sid) == true) || ($uid == 0)) {
    addonline(getuid_sid($sid), "Adding A Club", "");

    echo vrhonline($sid, $uid);
        $tema = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_topics WHERE authorid='" . $uid . "'"));
   // if ($tema[0] >= 100) {

            echo "<form action=\"genproc.php?addcl\" method=\"post\">";
            echo "<div class='sett_line border_top'>Naziv grupe:<input name=\"clnm\" maxlength=\"250\"/></div>";
            echo "<div class='sett_line border_top'>Opis grupe:<input name=\"clds\" maxlength=\"200\"/></div>";
            echo "<div class='sett_line border_top'>Pravila:<input name=\"clrl\" maxlength=\"500\"/></div>";
            echo "<div class='sett_line border_top'>Fotografija:<input name=\"cllg\" maxlength=\"200\"/></div>";
            echo "<input type=\"submit\" class='button' value=\"Otvori\"/>";
            echo "</form>";
    //} else {
      //  echo "Aaaaaaaaaaaaaa pa ne ce mo takooo... :D ";
    //} 

    echo dnoonline($sid, $uid);
    exit();
	}
} else if (isset($_GET['fotopogled'])) {
if ((islogged($sid) == true) || ($uid == 0)) {
    $fotka = $_GET["grupa"];
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
        $res = mysql_query("INSERT INTO fun_komentari_foto_grupa SET komowner='" . $fotka . "', komsigner='" . $uid . "', dtime='" . $crdate . "', kommsg='" . $msgtxt . "'");
        if ($res) {
            echo "<div class=\"pad\"><div class=\"notif border_bottom\">Vasa poruka je uspesno postavljena!!!</div>";
        } else {
            echo "<div class=\"pad\"><div class=\"error border_bottom\">Vasa poruka nije postavljena! Mogucnost da je greska u bazi, pa vas molimo da pokusate kasnije!</div>";
        } 
    } 

    $fotografija = mysql_fetch_array(mysql_query("SELECT uid, imageurl, time, descript FROM fun_fotografije_grupa WHERE id='" . $fotka . "'"));

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
            $obrisi = "<a href='grupa.php?brisifoto&amp;grupa=$fotka'>Obrisi fotografiju</a>";
            $profil = "<a href='grupa.php?zaprofilnu&amp;grupa=$fotka'>Postavi na profilnu</a> |";
        } else {
            $obrisi = "";
            $profil = "";
        } 
        if (isadmin(getuid_sid($sid))) {
            $aobrisi = "<br /> ADMIN: <a href='index.php?brisifoto&amp;grupa=$fotka'>Obrisi fotografiju</a>";
        } else {
            $aobrisi = "";
        } 
        echo "<div class='sett_line'><a href='index.php?viewuser&amp;who=$fotografija[0]'>$snick</a> $bs <br /> $fotografija[3]<br />$profil $obrisi $aobrisi</div>";

        if ($page == "" || $page <= 0)$page = 1;
        $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_komentari_foto_grupa WHERE komowner='" . $fotka . "'"));
        $num_items = $noi[0]; //changable
        $items_per_page = 7;
        $num_pages = ceil($num_items / $items_per_page);
        if (($page > $num_pages) && $page != 1)$page = $num_pages;
        $limit_start = ($page-1) * $items_per_page;

        if ($page > 1) {
            $ppage = $page-1;
            echo "<div class='center section border_bottom'><a href=\"grupa.php?fotopogled&amp;page=$ppage&amp;grupa=$fotka\"><img src='images/up.png' /></a></div>";
        } 

        $sql = "SELECT komowner, komsigner, kommsg, dtime, id FROM fun_komentari_foto_grupa WHERE komowner='" . $fotka . "' ORDER BY dtime DESC LIMIT $limit_start, $items_per_page";

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
            echo "<div class='center section border_bottom'><a href=\"grupa.php?fotopogled&amp;page=$npage&amp;grupa=$fotka\"><img src='images/down.png' /></a></div>";
        } 
        // //// UNTILL HERE >>
        echo "<div id=\"comment_box_468323575248\" class=\"sett_line\"><form action=\"grupa.php?fotopogled&amp;grupa=$fotka\" method=\"post\"><div>Dodaj komentar</div>";
        echo "<textarea name=\"msgtxt\" rows=\"3\"></textarea><br/>";
        echo "<input class=\"button\" type=\"submit\" value=\"Upisi komentar\"/>";
        echo "</form></div>";
    } 
    echo dnoonline($sid, $uid);
    exit();
	}
} else if (isset($_GET['brisifoto'])) {
if ((islogged($sid) == true) || ($uid == 0)) {
    $fotka = $_GET["grupa"];
    addonline(getuid_sid($sid), "Gledam fotku", "");
    echo vrhonline($sid, $uid);

    $fotografija = mysql_fetch_array(mysql_query("SELECT uid, imageurl, time, descript FROM fun_fotografije_grupa WHERE id='" . $fotka . "'"));
    if ($fotografija[0] == $uid) {
        $res = mysql_query("DELETE FROM fun_fotografije_grupa  WHERE id='" . $fotka . "' AND uid='" . $fotografija[0] . "'");
        if ($res) {
            echo "Fotografija uspesno obrisana<br />";
        } else {
            echo "Greska u bazi <br />";
        } 
    } else {
        echo "Ova fotografija nije vasa...<br />";
    } 

    if (isadmin(getuid_sid($sid))) {
        $res = mysql_query("DELETE FROM fun_fotografije_grupa  WHERE id='" . $fotka . "' AND uid='" . $fotografija[0] . "'");
        if ($res) {
            echo "ADMIN: Fotografija uspesno obrisana";
        } else {
            echo "ADMIN: Greska u bazi";
        } 
    } 

    echo dnoonline($sid, $uid);
    exit();
	}
} else if (isset($_GET['zaprofilnu'])) {
if ((islogged($sid) == true) || ($uid == 0)) {
    $fotka = $_GET["grupa"];
    addonline(getuid_sid($sid), "Gledam fotku", "");
    echo vrhonline($sid, $uid);

    $fotografija = mysql_fetch_array(mysql_query("SELECT uid, imageurl, time, descript FROM fun_fotografije_grupa WHERE id='" . $fotka . "'"));
    if ($fotografija[0] == $uid) {
        $res = mysql_query("UPDATE fun_clubs SET avatar='" . $fotografija[1] . "' WHERE id='" . $fotka . "'");
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
} else
if (isset($_GET['upload'])) {
if ((islogged($sid) == true) || ($uid == 0)) {
    addonline(getuid_sid($sid), "Uploading a Photo", "");
    $clid = $_GET["grupa"];
    $comment = $_POST["comment"];

    echo vrhonline($sid, $uid);

    echo "<small>";

    echo "<form name=\"form2\" enctype=\"multipart/form-data\" method=\"post\" action=\"upload.php?clid=$clid&amp;uploadgr\" />";
    echo "<div class='sett_line'><input type=\"file\" size=\"32\" name=\"my_field\" value=\"\" />";
    echo "<input type=\"hidden\" name=\"action\" value=\"grupa\" />
	<input type=\"hidden\" name=\"clid\" value=\"$clid\" /></div>";
    echo "<div class='sett_line'>Opis: <br /><textarea name='descript' rows='4' size='20'></textarea></div>";
    echo "<input type=\"submit\" name=\"Uploaduj\" class=\"button\" value=\"upload\" /><br/>";
    echo "</form>";

    echo "</small>";

    echo dnoonline($sid, $uid);
    exit();
	}
} 
else if (isset($_GET['annc'])) {
if ((islogged($sid) == true) || ($uid == 0)) {
    addonline(getuid_sid($sid), "Adding An Announcement", "");
    $clid = $_GET["clid"];

    echo vrhonline($sid, $uid);
    echo "<p align=\"center\">";
    $cow = mysql_fetch_array(mysql_query("SELECT owner FROM fun_clubs WHERE id='" . $clid . "'"));
    $uid = getuid_sid($sid);
    if ($cow[0] != $uid) {
        echo "This club is not yours!";
    } else {
        echo "<form action=\"genproc.php?annc&amp;clid=$clid\" method=\"post\">";
        echo "Text:<input name=\"antx\" maxlength=\"200\"/><br/>";
        echo "<input type=\"submit\" value=\"GO\"/>";
        echo "</form>";
    } 
    echo "</p>";
    echo dnoonline($sid, $uid);
    exit();
	}
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
