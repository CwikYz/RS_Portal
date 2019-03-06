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
	vrh($sid);
if(!ismod(getuid_sid($sid)))
  {
    
      echo "<p align=\"center\">";
      echo "You are not a mod<br/>";
      echo "<br/>";
      echo "<a href=\"index.php\">Home</a>";
      echo "</p>";
      exit();
    }
if(islogged($sid)==false)
    {
        
      echo "<p align=\"center\">";
      echo "You are not logged in<br/>";
      echo "Or Your session has been expired<br/><br/>";
      echo "<a href=\"index.php\">Login</a>";
      echo "</p>";
      exit();
    }
    addonline(getuid_sid($sid),"admin CP","");
if(isset($_GET['delp']))
{
if (ismod(getuid_sid($sid))) {
  $pid = $_GET["pid"];
  $tid = gettid_pid($pid);
  $fid = getfid_tid($tid);
 
  echo "<p align=\"center\">";
  $res = mysql_query("DELETE FROM fun_posts WHERE id='".$pid."'");
  if($res)
          {
            $tname = mysql_fetch_array(mysql_query("SELECT name FROM fun_topics WHERE id='".$tid."'"));
            mysql_query("INSERT INTO fun_mlog SET action='posts', details='<b>".getnick_uid(getuid_sid($sid))."</b> Deleted Post Number $pid Of the thread ".mysql_escape_string($tname[0])." at the forum ".getfname($fid)."', actdt='".time()."'");
            
            echo "<img src=\"images/ok.gif\" alt=\"O\"/>Post Message Deleted";
          }else{
            echo "<img src=\"images/notok.gif\" alt=\"X\"/>Database Error";
          }
  
  echo "<br/><br/><a href=\"index.php?viewtpc&amp;tid=$tid&amp;page=1000\">";
echo "View Topic</a><br/>";
$fname = getfname($fid);
      echo "<a href=\"index.php?viewfrm&amp;fid=$fid\">";
echo "$fname</a><br/>";
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
  }
}

////////////////////////////////////////////Edit Post

else if(isset($_GET['edtpst']))
{
if (ismod(getuid_sid($sid))) {
  $pid = $_GET["pid"];
  $ptext = $_POST["ptext"];
  $tid = gettid_pid($pid);
  $fid = getfid_tid($tid);
 
  echo "<p align=\"center\">";
  $res = mysql_query("UPDATE fun_posts SET text='"
  .$ptext."' WHERE id='".$pid."'");
  if($res)
          {
            $tname = mysql_fetch_array(mysql_query("SELECT name FROM fun_topics WHERE id='".$tid."'"));
            mysql_query("INSERT INTO fun_mlog SET action='posts', details='<b>".getnick_uid(getuid_sid($sid))."</b> Edited Post Number $pid Of the thread ".mysql_escape_string($tname[0])." at the forum ".getfname($fid)."', actdt='".time()."'");
            echo "<img src=\"images/ok.gif\" alt=\"O\"/>Post Message Edited";
          }else{
            echo "<img src=\"images/notok.gif\" alt=\"X\"/>Database Error";
          }
  echo "<br/><br/>";
  echo "<a href=\"index.php?viewtpc&amp;tid=$tid\">";
echo "View Topic</a><br/>";
$fname = getfname($fid);
      echo "<a href=\"index.php?viewfrm&amp;fid=$fid\">";
echo "$fname</a><br/>";
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
  }
}

////////////////////////////////////////////Edit Post

else if(isset($_GET['edttpc']))
{
if (ismod(getuid_sid($sid))) {
  $tid = $_GET["tid"];
  $ttext = $_POST["ttext"];
  $fid = getfid_tid($tid);
 
  echo "<p align=\"center\">";
  $res = mysql_query("UPDATE fun_topics SET text='"
  .$ttext."' WHERE id='".$tid."'");
  if($res)
          {
            mysql_query("INSERT INTO fun_mlog SET action='topics', details='<b>".getnick_uid(getuid_sid($sid))."</b> Edited the text Of the thread ".mysql_escape_string(gettname($tid))." at the forum ".getfname($fid)."', actdt='".time()."'");
            echo "<img src=\"images/ok.gif\" alt=\"O\"/>Topic Message Edited";
          }else{
            echo "<img src=\"images/notok.gif\" alt=\"X\"/>Database Error";
          }
  echo "<br/><br/>";
  echo "<a href=\"index.php?viewtpc&amp;tid=$tid\">";
echo "View Topic</a><br/>";
$fname = getfname($fid);
      echo "<a href=\"index.php?viewfrm&amp;fid=$fid\">";
echo "$fname</a><br/>";
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
  }
}

///////////////////////////////////////Close/ Open Topic

else if(isset($_GET['clot']))
{
if (ismod(getuid_sid($sid))) {
  $tid = $_GET["tid"];
  $tdo = $_GET["tdo"];
  $fid = getfid_tid($tid);
 
  echo "<p align=\"center\">";
  $res = mysql_query("UPDATE fun_topics SET closed='"
  .$tdo."' WHERE id='".$tid."'");
  if($res)
          {
            if($tdo==1)
            {
              $msg = "Closed";
            }else{
                $msg = "Opened";
            }
            mysql_query("INSERT INTO fun_mlog SET action='topics', details='<b>".getnick_uid(getuid_sid($sid))."</b> Closed The thread ".mysql_escape_string(gettname($tid))." at the forum ".getfname($fid)."', actdt='".time()."'");
            echo "<img src=\"images/ok.gif\" alt=\"O\"/>Topic $msg";
			$tpci = mysql_fetch_array(mysql_query("SELECT name, authorid FROM fun_topics WHERE id='".$tid."'"));
			$tname = htmlspecialchars($tpci[0]);
			$msg = "your thread [topic=$tid]$tname"."[/topic] is $msg"."[br/][small][i]p.s: this is an automatic pm[/i][/small]";
			autopm($msg, $tpci[1]);
          }else{
            echo "<img src=\"images/notok.gif\" alt=\"X\"/>Database Error";
          }
  echo "<br/><br/>";
  
$fname = getfname($fid);
      echo "<a href=\"index.php?viewfrm&amp;fid=$fid\">";
echo "$fname</a><br/>";
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
  }
}

///////////////////////////////////////Untrash user

else if(isset($_GET['untr']))
{
if (ismod(getuid_sid($sid))) {
  $who = $_GET["who"];
 
   echo vrhonline($sid,$uid);
  $res = mysql_query("DELETE FROM fun_penalties WHERE penalty='0' AND uid='".$who."'");
  if($res)
          {
            $unick = getnick_uid($who);
            mysql_query("INSERT INTO fun_mlog SET action='penalties', details='<b>".getnick_uid(getuid_sid($sid))."</b> Untrashed The user <b>".$unick."', actdt='".time()."'");
            echo "Korisnik $unick uspesno vracen sa djubrista... :D";
          }else{
            echo "Greska u bazi...";
          }
		  
  echo dnoonline($sid,$uid); exit();
  }
}

///////////////////////////////////////Unban user

else if(isset($_GET['unbn']))
{
if (ismod(getuid_sid($sid))) {
  $who = $_GET["who"];
   echo vrhonline($sid,$uid);
 
  $res = mysql_query("DELETE FROM fun_penalties WHERE (penalty='1' OR penalty='2') AND uid='".$who."'");
  if($res)
          {
            $unick = getnick_uid($who);
            mysql_query("INSERT INTO fun_mlog SET action='penalties', details='<b>".getnick_uid(getuid_sid($sid))."</b> Unbanned The user <b>".$unick."</b>', actdt='".time()."'");
            echo "Korisniku $unick Je uspesno skinut ban...";
          }else{
            echo "Greska u bazi...";
          }
		  
  echo dnoonline($sid,$uid); exit();
  }
}

///////////////////////////////////////Delete shout

else if(isset($_GET['delsh']))
{
if (ismod(getuid_sid($sid))) {
  $shid = $_GET["shid"];
 
  echo "<p align=\"center\">";
  $sht = mysql_fetch_array(mysql_query("SELECT shouter, shout FROM fun_shouts WHERE id='".$shid."'"));
  $msg = getnick_uid($sht[0]);
  $msg .= ": ".htmlspecialchars((strlen($sht[1])<20?$sht[1]:substr($sht[1], 0, 20)));
  $res = mysql_query("DELETE FROM fun_shouts WHERE id ='".$shid."'");
  if($res)
          {		  
          mysql_query("DELETE FROM fun_komentari WHERE komowner ='".$shid."'");
		  mysql_query("INSERT INTO fun_mlog SET action='shouts', details='<b>".getnick_uid(getuid_sid($sid))."</b> Deleted the shout <b>".$shid."</b> - $msg', actdt='".time()."'");
            echo "<img src=\"images/ok.gif\" alt=\"O\"/>Shout deleted";
          }else{
            echo "<img src=\"images/notok.gif\" alt=\"X\"/>Database Error";
          }
  echo "<br/><br/>";


  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
  }
}


///////////////////////////////////////Unban user

else if(isset($_GET['shld']))
{
if (ismod(getuid_sid($sid))) {
   echo vrhonline($sid,$uid);
  $who = $_GET["who"];
 
  $res = mysql_query("Update fun_users SET shield='1' WHERE id='".$who."'");
  if($res)
          {
            $unick = getnick_uid($who);
            mysql_query("INSERT INTO fun_mlog SET action='penalties', details='<b>".getnick_uid(getuid_sid($sid))."</b> Shielded The user <b>".$unick."</b>', actdt='".time()."'");
            echo "Korisnik $unick je uspesno zasticen";
          }else{
            echo "Greska na bazi...";
          }


  echo dnoonline($sid,$uid); exit();
  }
}

///////////////////////////////////////Unban user

else if(isset($_GET['ushld']))
{
if (ismod(getuid_sid($sid))) {
   echo vrhonline($sid,$uid);
  $who = $_GET["who"];
 
 
  $res = mysql_query("Update fun_users SET shield='0' WHERE id='".$who."'");
  if($res)
          {
            $unick = getnick_uid($who);
            mysql_query("INSERT INTO fun_mlog SET action='penalties', details='<b>".getnick_uid(getuid_sid($sid))."</b> Unshielded The user <b>".$unick."</b>', actdt='".time()."'");
            echo "Korisniku $unick je skinuta zastita";
          }else{
            echo "Greska u bazi...";
          }
  echo dnoonline($sid,$uid); exit();
  }
}

///////////////////////////////////////Pin/ Unpin Topic

else if(isset($_GET['pint']))
{
if (ismod(getuid_sid($sid))) {
  $tid = $_GET["tid"];
  $tdo = $_GET["tdo"];
  $fid = getfid_tid($tid);
 
  echo "<p align=\"center\">";
  $pnd = getpinned($fid);
  if($pnd<=5)
  {
  $res = mysql_query("UPDATE fun_topics SET pinned='"
  .$tdo."' WHERE id='".$tid."'");
  if($res)
          {
            if($tdo==1)
            {
              $msg = "Pinned";
            }else{
                $msg = "Unpinned";
            }
            mysql_query("INSERT INTO fun_mlog SET action='topics', details='<b>".getnick_uid(getuid_sid($sid))."</b> $msg The thread ".mysql_escape_string(gettname($tid))." at the forum ".getfname($fid)."', actdt='".time()."'");
            echo "<img src=\"images/ok.gif\" alt=\"O\"/>Topic $msg";
          }else{
            echo "<img src=\"images/notok.gif\" alt=\"X\"/>Database Error";
          }
          }else{
            echo "<img src=\"images/notok.gif\" alt=\"X\"/>You can only pin 5 topics in every forum";
          }
  echo "<br/><br/>";

$fname = getfname($fid);
      echo "<a href=\"index.php?viewfrm&amp;fid=$fid\">";
echo "$fname</a><br/>";
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
  }
}

///////////////////////////////////Delete the damn thing

else if(isset($_GET['delt']))
{
if (ismod(getuid_sid($sid))) {
  $tid = $_GET["tid"];
  $fid = getfid_tid($tid);
 
  echo "<p align=\"center\">";
  $tname=gettname($tid);
  $res = mysql_query("DELETE FROM fun_topics WHERE id='".$tid."'");
  if($res)
          {
            mysql_query("DELETE FROM fun_posts WHERE tid='".$tid."'");
            mysql_query("INSERT INTO fun_mlog SET action='topics', details='<b>".getnick_uid(getuid_sid($sid))."</b> Deleted The thread ".mysql_escape_string($tname)." at the forum ".getfname($fid)."', actdt='".time()."'");
            echo "<img src=\"images/ok.gif\" alt=\"O\"/>Topic Deleted";
          }else{
            echo "<img src=\"images/notok.gif\" alt=\"X\"/>Database Error";
          }
  echo "<br/><br/>";
  
$fname = getfname($fid);
      echo "<a href=\"index.php?viewfrm&amp;fid=$fid\">";
echo "$fname</a><br/>";
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
  }
}


////////////////////////////////////////////Edit Post

else if(isset($_GET['rentpc']))
{
if (ismod(getuid_sid($sid))) {
  $tid = $_GET["tid"];
  $tname = $_POST["tname"];
  $fid = getfid_tid($tid);
 
  echo "<p align=\"center\">";
  $otname = gettname($tid);
  if(trim($tname!=""))
  {
    $not = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_topics WHERE name LIKE '".$tname."' AND fid='".$fid."'"));
    if($not[0]==0)
    {
  $res = mysql_query("UPDATE fun_topics SET name='"
  .$tname."' WHERE id='".$tid."'");
  if($res)
          {
            mysql_query("INSERT INTO fun_mlog SET action='topics', details='<b>".getnick_uid(getuid_sid($sid))."</b> Renamed The thread ".mysql_escape_string($otname)." to ".mysql_escape_string($tname)." at the forum ".getfname($fid)."', actdt='".time()."'");
            echo "<img src=\"images/ok.gif\" alt=\"O\"/>Topic  Renamed";
          }else{
            echo "<img src=\"images/notok.gif\" alt=\"X\"/>Database Error";
          }
  }else{
    echo "<img src=\"images/notok.gif\" alt=\"X\"/>Topic Name already exist";
  }
    
  }else{
    echo "<img src=\"images/notok.gif\" alt=\"X\"/>You must specify a name for the topic";
  }
  echo "<br/><br/>";
  echo "<a href=\"index.php?viewtpc&amp;tid=$tid\">";
echo "View Topic</a><br/>";
$fname = getfname($fid);
      echo "<a href=\"index.php?viewfrm&amp;fid=$fid\">";
echo "$fname</a><br/>";
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
  }
}

///////////////////////////////////////////////////Move topic



else if(isset($_GET['mvt']))
{
if (ismod(getuid_sid($sid))) {
  $tid = $_GET["tid"];
  $mtf = $_POST["mtf"];
  $fname = htmlspecialchars(getfname($mtf));
  //$fid = getfid_tid($tid);
 
  echo "<p align=\"center\">";
  
    $not = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_topics WHERE name LIKE '".$tname."' AND fid='".$mtf."'"));
    if($not[0]==0)
    {
  $res = mysql_query("UPDATE fun_topics SET fid='"
  .$mtf."', moved='1' WHERE id='".$tid."'");
  if($res)
          {
            mysql_query("INSERT INTO fun_mlog SET action='topics', details='<b>".getnick_uid(getuid_sid($sid))."</b> Moved The thread ".mysql_escape_string($tname)." to forum ".getfname($fid)."', actdt='".time()."'");
			$tpci = mysql_fetch_array(mysql_query("SELECT name, authorid FROM fun_topics WHERE id='".$tid."'"));
			$tname = htmlspecialchars($tpci[0]);
			$msg = "your thread [topic=$tid]$tname"."[/topic] Was moved to $fname forum[br/][small][i]p.s: this is an automatic pm[/i][/small]";
			autopm($msg, $tpci[1]);
            echo "<img src=\"images/ok.gif\" alt=\"O\"/>Topic Moved";
          }else{
            echo "<img src=\"images/notok.gif\" alt=\"X\"/>Database Error";
          }
  }else{
    echo "<img src=\"images/notok.gif\" alt=\"X\"/>Topic Name already exist";
  }


  echo "<br/><br/>";
  

      echo "<a href=\"index.php?viewfrm&amp;fid=$mtf\">";
echo "$fname</a><br/>";
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
  }
}

//////////////////////////////////////////Handle PM

else if(isset($_GET['hpm']))
{
if (ismod(getuid_sid($sid))) {
  $pid = $_GET["pid"];
  
 
  echo "<p align=\"center\">";

    $info = mysql_fetch_array(mysql_query("SELECT byuid, touid FROM fun_private WHERE id='".$pid."'"));
  $res = mysql_query("UPDATE fun_private SET reported='2' WHERE id='".$pid."'");
  if($res)
          {
            mysql_query("INSERT INTO fun_mlog SET action='handling', details='<b>".getnick_uid(getuid_sid($sid))."</b> handled The PM ".$pid."', actdt='".time()."'");
            echo "<img src=\"images/ok.gif\" alt=\"O\"/>PM Handled";
          }else{
            echo "<img src=\"images/notok.gif\" alt=\"X\"/>Database Error";
          }



  echo "<br/><br/>";
    
    echo "<a href=\"index.php?viewuser&amp;who=$info[0]\">PM Sender's Profile</a><br/>";
      echo "<a href=\"index.php?viewuser&amp;who=$info[1]\">PM Reporter's Profile</a><br/><br/>";
      echo "<a href=\"modcp.php?main\">";
echo "SE R/L</a><br/>";
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
  }
}

//////////////////////////////////////////Handle Post

else if(isset($_GET['hps']))
{
if (ismod(getuid_sid($sid))) {
  $pid = $_GET["pid"];

 
  echo "<p align=\"center\">";

    $info = mysql_fetch_array(mysql_query("SELECT uid, tid FROM fun_posts WHERE id='".$pid."'"));
  $res = mysql_query("UPDATE fun_posts SET reported='2' WHERE id='".$pid."'");
  if($res)
          {
            mysql_query("INSERT INTO fun_mlog SET action='handling', details='<b>".getnick_uid(getuid_sid($sid))."</b> handled The Post ".$pid."', actdt='".time()."'");
            echo "<img src=\"images/ok.gif\" alt=\"O\"/>Post Handled";
          }else{
            echo "<img src=\"images/notok.gif\" alt=\"X\"/>Database Error";
          }



  echo "<br/><br/>";
    $poster = getnick_uid($info[0]);
    echo "<a href=\"index.php?viewuser&amp;who=$info[0]\">$poster's Profile</a><br/>";
      echo "<a href=\"index.php?viewtpc&amp;tid=$info[1]\">View Topic</a><br/><br/>";
      echo "<a href=\"modcp.php?main\">";
echo "SE R/L</a><br/>";
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
  }
}

//////////////////////////////////////////Handle Topic

else if(isset($_GET['htp']))
{
if (ismod(getuid_sid($sid))) {
  $pid = $_GET["tid"];

 
  echo "<p align=\"center\">";

    $info = mysql_fetch_array(mysql_query("SELECT authorid FROM fun_topics WHERE id='".$pid."'"));
  $res = mysql_query("UPDATE fun_topics SET reported='2' WHERE id='".$pid."'");
  if($res)
          {
            mysql_query("INSERT INTO fun_mlog SET action='handling', details='<b>".getnick_uid(getuid_sid($sid))."</b> handled The topic ".mysql_escape_string(gettname($pid))."', actdt='".time()."'");
            echo "<img src=\"images/ok.gif\" alt=\"O\"/>Topic Handled";
          }else{
            echo "<img src=\"images/notok.gif\" alt=\"X\"/>Database Error";
          }



  echo "<br/><br/>";
    $poster = getnick_uid($info[0]);
    echo "<a href=\"index.php?viewuser&amp;who=$info[0]\">$poster's Profile</a><br/>";
      echo "<a href=\"index.php?viewtpc&amp;tid=$pid\">View Topic</a><br/><br/>";
      echo "<a href=\"modcp.php?main\">";
echo "SE R/L</a><br/>";
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
}
}

////////////////////////////////////////Punish

else if(isset($_GET['pun']))
{
if (ismod(getuid_sid($sid))) {
   echo vrhonline($sid,$uid);
    $pid = $_POST["pid"];
    $who = $_POST["who"];
    $pres = $_POST["pres"];
    $pds = $_POST["pds"];
    $phr = $_POST["phr"];
    $pmn = $_POST["pmn"];
    $psc = $_POST["psc"];
    
  
  $uip = "";
  $ubr = "";
  $pmsg[0]="Obrisan";
  $pmsg[1]="Banovan";
  $pmsg[2]="Banovan sa ip-om";
  if($pid=='2')
  {
    //ip ban
    $uip = getip_uid($who);
    $ubr = getbr_uid($who);
  }
  if(trim($pres)=="")
  {
    echo "Nemate dozvolu za ovu komandu";
  }else{
    $timeto = $pds*24*60*60;
    $timeto += $phr*60*60;
    $timeto += $pmn*60;
    $timeto += $psc;
    $ptime = $timeto + time();
	if ($who == 1) {
	$who = getuid_sid($sid);
	}
    $unick = getnick_uid($who);
    $res = mysql_query("INSERT INTO fun_penalties SET uid='".$who."', penalty='".$pid."', exid='".getuid_sid($sid)."', timeto='".$ptime."', pnreas='".mysql_escape_string($pres)."', ipadd='".$uip."', browserm='".$ubr."'");
    if($res)
          {
            mysql_query("UPDATE fun_users SET lastpnreas='".$pmsg[$pid].": ".mysql_escape_string($pres)."' WHERE id='".$who."'");
            mysql_query("INSERT INTO fun_mlog SET action='penalties', details='<b>".getnick_uid(getuid_sid($sid))."</b> $pmsg[$pid] The user <b>".$unick."</b> For ".$timeto." Seconds', actdt='".time()."'");
            
            echo "$unick je $pmsg[$pid] na $timeto sekundi";
          }else{
            echo "Greska u bazi...";
          }
  }
  
  echo dnoonline($sid,$uid); exit();
  }

}

////////////////////////////////////////Punish

else if(isset($_GET['pls']))
{
if (ismod(getuid_sid($sid))) {
   echo vrhonline($sid,$uid);
    $pid = $_POST["pid"];
    $who = $_POST["who"];
    $pres = $_POST["pres"];
    $pval = $_POST["pval"];
    

$unick = getnick_uid($who);
$opl = mysql_fetch_array(mysql_query("SELECT plusses FROM fun_users WHERE id='".$who."'"));

if($pid=='0')
{
  $npl = $opl[0] - $pval;
}else{
    $npl = $opl[0] + $pval;
}
if($npl<0)
{
  $npl=0;
}
  if(trim($pres)=="")
  {
    echo "Nemate dozvolu za ovu funkciju...";
  }else{
    
    $res = mysql_query("UPDATE fun_users SET lastplreas='".mysql_escape_string($pres)."', plusses='".$npl."' WHERE id='".$who."'");
    if($res)
          {
            mysql_query("INSERT INTO fun_mlog SET action='penalties', details='<b>".getnick_uid(getuid_sid($sid))."</b> Updated <b>".$unick."</b> plusses from ".$opl[0]." to $npl', actdt='".time()."'");
            echo "$unick je uspesno promenjen status plusica sa $opl[0] na $npl";
          }else{
            echo "Greska u bazi...";
          }
  }
  
  echo dnoonline($sid,$uid); exit();
}
}
else{
    
  echo "<p align=\"center\">";
  echo "I don't know how did you get into here, but there's nothing to show<br/><br/>";
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
}
	echo "</body>";
	echo "</html>";
?>

