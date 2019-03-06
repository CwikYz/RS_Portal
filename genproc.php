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
$uid = getuid_sid($sid);
	vrh($sid);

    $uid = getuid_sid($sid);
if((islogged($sid)==false)||($uid==0))
    {
        
      echo "<p align=\"center\">";
      echo "You are not logged in<br/>";
      echo "Or Your session has been expired<br/><br/>";
      echo "</p>";
  echo dnooffline();
      exit();
    }
	
if(isset($_GET['newtopic']))
{
  $fid = $_POST["fid"];
  $ntitle = $_POST["ntitle"];
  $tpctxt = $_POST["tpctxt"];
  if(!canaccess(getuid_sid($sid), $fid))
    {
        
   echo vrhonline($sid,$uid);
      echo "<p align=\"center\">";
      echo "You Don't Have A Permission To View The Contents Of This Forum<br/><br/>";
      echo "</p>";
  echo dnoonline($sid,$uid);
      exit();
    }
  addonline(getuid_sid($sid),"Created New Topic","");
    
   echo vrhonline($sid,$uid);
      echo "<p align=\"center\">";
      $crdate = time();
      //$uid = getuid_sid($sid);
      
        $res = false;
      
        $ltopic = mysql_fetch_array(mysql_query("SELECT crdate FROM fun_topics WHERE authorid='".$uid."' ORDER BY crdate DESC LIMIT 1"));
        global $topic_af;
        $antiflood = time()-$ltopic[0];
        if($antiflood>$topic_af)
{
  if((trim($ntitle)!="")||(trim($tpctxt)!=""))
      {
      $res = mysql_query("INSERT INTO fun_topics SET name='".$ntitle."', fid='".$fid."', authorid='".$uid."', text='".$tpctxt."', crdate='".$crdate."', lastpost='".$crdate."'");
     }
       if($res)
      {
        $usts = mysql_fetch_array(mysql_query("SELECT posts, plusses FROM fun_users WHERE id='".$uid."'"));
        $ups = $usts[0]+1;
        $upl = $usts[1]+1;
        mysql_query("UPDATE fun_users SET posts='".$ups."', plusses='".$upl."' WHERE id='".$uid."'");
        $tnm = htmlspecialchars($ntitle);
        echo "<div class='titl'>Tema <b>$tnm</b> Uspesno kreirana</div>";
        $tid = mysql_fetch_array(mysql_query("SELECT id FROM fun_topics WHERE name='".$ntitle."' AND fid='".$fid."'"));
        echo "<div class='comment comm_adv'><a href=\"index.php?viewtpc&amp;tid=$tid[0]\">";
echo "Pogledaj temu</a></div>";
      }else{
        echo "<div class='titl'>Greska tema nije kreirana</div>";
      }
      }else{
        $af = $topic_af -$antiflood;
        echo "<div class='titl'>Antispam sistem: $af</div>";
      }

      



      $fname = getfname($fid);
      echo "<div class='sett_line'><a href=\"index.php?viewfrm&amp;fid=$fid\">";
echo "$fname</a></div>";
  echo dnoonline($sid,$uid);
      exit();
}
else if(isset($_GET['post']))
{
    $tid = $_POST["tid"];
    $tfid = mysql_fetch_array(mysql_query("SELECT fid FROM fun_topics WHERE id='".$tid."'"));
if(!canaccess(getuid_sid($sid), $tfid[0]))
    {
   echo vrhonline($sid,$uid);
        
      echo "GRESKA!!!<br/>Vratite se nazad";
  echo dnoonline($sid,$uid);
      exit();
    }
  $reptxt = $_POST["reptxt"];
  $qut = $_POST["qut"];
  addonline(getuid_sid($sid),"Posted A reply","");
  
   echo vrhonline($sid,$uid);
      $crdate = time();
      $fid = getfid($tid);
      //$uid = getuid_sid($sid);
      $res = false;
      $closed = mysql_fetch_array(mysql_query("SELECT closed FROM fun_topics WHERE id='".$tid."'"));
      
      if(($closed[0]!='1')||(ismod($uid)))
      {
      
        $lpost = mysql_fetch_array(mysql_query("SELECT dtpost FROM fun_posts WHERE uid='".$uid."' ORDER BY dtpost DESC LIMIT 1"));
        global $post_af;
        $antiflood = time()-$lpost[0];
        if($antiflood>$post_af)
{
  if(trim($reptxt)!="")
      {
      $res = mysql_query("INSERT INTO fun_posts SET text='".$reptxt."', tid='".$tid."', uid='".$uid."', dtpost='".$crdate."', quote='".$qut."'");
}
      if($res)
      {
        $usts = mysql_fetch_array(mysql_query("SELECT posts, plusses FROM fun_users WHERE id='".$uid."'"));
        $ups = $usts[0]+1;
        $upl = $usts[1]+1;
        mysql_query("UPDATE fun_users SET posts='".$ups."', plusses='".$upl."' WHERE id='".$uid."'");
        mysql_query("UPDATE fun_topics SET lastpost='".$crdate."' WHERE id='".$tid."'");
        echo "<div class=\"titl\">Poruka uspesno upisana</div>";
        echo "<div class=\"sett_line\"><a href=\"index.php?viewtpc&amp;tid=$tid&amp;go=last\">";
echo "Vrati se na temu</a></div>";
      }else{
        echo "Greska u bazi...";
      }
      }else{
$af = $post_af -$antiflood;
        echo "Anti spam kontrola: $af";
      }
      }else{
        echo "Tema je zatvorena";
      }
      
      $fname = getfname($fid);
      echo "<div class=\"comment comm_adv\"><a href=\"index.php?viewfrm&amp;fid=$fid\">";
echo "$fname</a></div>";
  echo dnoonline($sid,$uid);
      
  
}

else if (isset($_GET['uadd']))
{
    $ucon = $_POST["ucon"];
    $ucit = $_POST["ucit"];
    $ustr = $_POST["ustr"];
    $utzn = $_POST["utzn"];
    $uphn = $_POST["uphn"];
    addonline(getuid_sid($sid),"My Address","");
    
   echo vrhonline($sid,$uid);
    echo "<p align=\"center\">";
    $uid = getuid_sid($sid);
    $exs = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_xinfo WHERE uid='".$uid."'"));
    if($exs[0]>0)
    {
        $res = mysql_query("UPDATE fun_xinfo SET country='".$ucon."', city='".$ucit."', street='".$ustr."', timezone='".$utzn."', phoneno='".$uphn."' WHERE uid='".$uid."'");
        if($res)
        {
          echo "<img src=\"images/ok.gif\" alt=\"O\"/>Address Updated Successfully<br/><br/>";
        }else{
            echo "<img src=\"images/notok.gif\" alt=\"O\"/>Database Error!<br/><br/>";
        }
    }else{
        $res = mysql_query("INSERT INTO fun_xinfo SET uid='".$uid."', country='".$ucon."', city='".$ucit."', street='".$ustr."', timezone='".$utzn."', phoneno='".$uphn."'");
        if($res)
        {
          echo "<img src=\"images/ok.gif\" alt=\"O\"/>Address Updated Successfully<br/><br/>";
        }else{
            echo "<img src=\"images/notok.gif\" alt=\"O\"/>Database Error!<br/><br/>";
        }
    }
    echo "<a href=\"index.php?uxset\">";
echo "Extended Settings</a><br/>";
  echo "</p>";
  echo dnoonline($sid,$uid);
    
}

else if(isset($_GET['gcp']))
{
    $clid = $_GET["clid"];
    $who = $_GET["who"];
    $giv = $_POST["giv"];
    $pnt = $_POST["pnt"];
    addonline(getuid_sid($sid),"Moderating Club Member","");
    
   echo vrhonline($sid,$uid);
    echo "<p align=\"center\">";
    $whnick = getnick_uid($who);
    echo "<b>$whnick</b>";
    echo "</p>";
    echo "<p>";
    $exs = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_clubmembers WHERE uid='".$who."' AND clid=".$clid.""));
$cow = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_clubs WHERE owner='".$uid."' AND id=".$clid.""));
if($exs[0]>0 && $cow[0]>0)
{
    $mpt = mysql_fetch_array(mysql_query("SELECT points FROM fun_clubmembers WHERE uid='".$who."' AND clid='".$clid."'"));
    if($giv=="1")
    {
      $pnt = $mpt[0]+$pnt;
    }else{
        $pnt = $mpt[0]-$pnt;
        if($pnt<0)$pnt=0;
    }
    $res = mysql_query("UPDATE fun_clubmembers SET points='".$pnt."' WHERE uid='".$who."' AND clid='".$clid."'");
    if($res)
    {
        echo "<img src=\"images/ok.gif\" alt=\"O\"/>Club points updated successfully!";
    }else{
      echo "<img src=\"images/notok.gif\" alt=\"X\"/>Database Error!";
    }
    }else{
      echo "<img src=\"images/notok.gif\" alt=\"X\"/>Missing Info!";
    }
    echo "</p>";
  echo dnoonline($sid,$uid);
    
}

else if(isset($_GET['gpl']))
{
    $clid = $_GET["clid"];
    $who = $_GET["who"];
    $pnt = $_POST["pnt"];
    addonline(getuid_sid($sid),"Moderating Club Member","");
    
   echo vrhonline($sid,$uid);
    echo "<p align=\"center\">";
    $whnick = getnick_uid($who);
    echo "<b>$whnick</b>";
    echo "</p>";
    echo "<p>";
      echo "<img src=\"images/notok.gif\" alt=\"X\"/>Because people misused the plusses thing, clubs owners cant give plusses anymore";
    
    echo "</p>";
  echo dnoonline($sid,$uid);
    
}

else if (isset($_GET['upre']))
{
    $usds = $_POST["usds"];
    $usds = str_replace('"', "", $usds);
    $usds = str_replace("'", "", $usds);
    $ubon = $_POST["ubon"];
    $usxp = $_POST["usxp"];
    addonline(getuid_sid($sid),"Preferences","");
    
   echo vrhonline($sid,$uid);
    echo "<p align=\"center\">";
    $uid = getuid_sid($sid);
    $exs = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_xinfo WHERE uid='".$uid."'"));
    if($exs[0]>0)
    {
        $res = mysql_query("UPDATE fun_xinfo SET sitedscr='".$usds."', budsonly='".$ubon."', sexpre='".$usxp."' WHERE uid='".$uid."'");
        if($res)
        {
          echo "<img src=\"images/ok.gif\" alt=\"O\"/>Preferences Updated Successfully<br/><br/>";
        }else{
            echo "<img src=\"images/notok.gif\" alt=\"O\"/>Database Error!<br/><br/>";
        }
    }else{
        $res = mysql_query("INSERT INTO fun_xinfo SET uid='".$uid."', sitedscr='".$usds."', budsonly='".$ubon."', sexpre='".$usxp."'");
        if($res)
        {
          echo "<img src=\"images/ok.gif\" alt=\"O\"/>Preferences Updated Successfully<br/><br/>";
        }else{
            echo "<img src=\"images/notok.gif\" alt=\"O\"/>Database Error!<br/><br/>";
        }
    }
    echo "<a href=\"index.php?uxset\">";
echo "Extended Settings</a><br/>";
  echo "</p>";
  echo dnoonline($sid,$uid);
    
}

else if (isset($_GET['gmset']))
{
    $ugun = $_POST["ugun"];
    $ugpw = $_POST["ugpw"];
    $ugch = $_POST["ugch"];
    addonline(getuid_sid($sid),"G-Mail Settings","");
    
   echo vrhonline($sid,$uid);
    echo "<p align=\"center\">";
    $uid = getuid_sid($sid);
    $exs = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_xinfo WHERE uid='".$uid."'"));
    if($exs[0]>0)
    {
        $res = mysql_query("UPDATE fun_xinfo SET gmailun='".$ugun."', gmailpw='".$ugpw."', gmailchk='".$ugch."', gmaillch='".time()."' WHERE uid='".$uid."'");
        if($res)
        {
          echo "<img src=\"images/ok.gif\" alt=\"O\"/>Gmail Settings Updated Successfully<br/><br/>";
        }else{
            echo "<img src=\"images/notok.gif\" alt=\"O\"/>Database Error!<br/><br/>";
        }
    }else{
        $res = mysql_query("INSERT INTO fun_xinfo SET uid='".$uid."', gmailun='".$ugun."', gmailpw='".$ugpw."', gmailchk='".$ugch."', gmaillch='".time()."'");
        if($res)
        {
          echo "<img src=\"images/ok.gif\" alt=\"O\"/>G-Mail Settings Updated Successfully<br/><br/>";
        }else{
            echo "<img src=\"images/notok.gif\" alt=\"O\"/>Database Error!<br/><br/>";
        }
    }
    echo "<a href=\"index.php?uxset\">";
echo "Extended Settings</a><br/>";
  echo "</p>";
  echo dnoonline($sid,$uid);

}

else if (isset($_GET['uper']))
{
    $uhig = $_POST["uhig"];
    $uwgt = $_POST["uwgt"];
    $urln = $_POST["urln"];
    $ueor = $_POST["ueor"];
    $ueys = $_POST["ueys"];
    $uher = $_POST["uher"];
    $upro = $_POST["upro"];
    
    addonline(getuid_sid($sid),"Personality","");
    
   echo vrhonline($sid,$uid);
    echo "<p align=\"center\">";
    $uid = getuid_sid($sid);
    $exs = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_xinfo WHERE uid='".$uid."'"));
    if($exs[0]>0)
    {
        $res = mysql_query("UPDATE fun_xinfo SET height='".$uhig."', weight='".$uwgt."', realname='".$urln."', eyescolor='".$ueys."', profession='".$upro."', racerel='".$ueor."',hairtype='".$uher."'  WHERE uid='".$uid."'");
        if($res)
        {
          echo "<img src=\"images/ok.gif\" alt=\"O\"/>Personal Info Updated Successfully<br/><br/>";
        }else{
            echo "<img src=\"images/notok.gif\" alt=\"O\"/>Database Error!<br/><br/>";
        }
    }else{
        $res = mysql_query("INSERT INTO fun_xinfo SET uid='".$uid."', height='".$uhig."', weight='".$uwgt."', realname='".$urln."', eyescolor='".$ueys."', profession='".$upro."', racerel='".$ueor."',hairtype='".$uher."'");
        if($res)
        {
          echo "<img src=\"images/ok.gif\" alt=\"O\"/>Personal Info Updated Successfully<br/><br/>";
        }else{
            echo "<img src=\"images/notok.gif\" alt=\"O\"/>Database Error!<br/><br/>";
        }
    }
    echo "<a href=\"index.php?uxset\">";
echo "Extended Settings</a><br/>";
  echo "</p>";
  echo dnoonline($sid,$uid);
    
}

else if (isset($_GET['umin']))
{
    $ulik = $_POST["ulik"];
    $ulik = str_replace('"', "", $ulik);
    $ulik = str_replace("'", "", $ulik);
    $udlk = $_POST["udlk"];
    $udlk = str_replace('"', "", $udlk);
    $udlk = str_replace("'", "", $udlk);
    $ubht = $_POST["ubht"];
    $ubht = str_replace('"', "", $ubht);
    $ubht = str_replace("'", "", $ubht);
    $ught = $_POST["ught"];
    $ught = str_replace('"', "", $ught);
    $ught = str_replace("'", "", $ught);
    $ufsp = $_POST["ufsp"];
    $ufsp = str_replace('"', "", $ufsp);
    $ufsp = str_replace("'", "", $ufsp);
    $ufmc = $_POST["ufmc"];
    $ufmc = str_replace('"', "", $ufmc);
    $ufmc = str_replace("'", "", $ufmc);
    $umtx = $_POST["umtx"];
    $umtx = str_replace('"', "", $umtx);
    $umtx = str_replace("'", "", $umtx);
    addonline(getuid_sid($sid),"More about me","");
    
   echo vrhonline($sid,$uid);
    echo "<p align=\"center\">";
    $uid = getuid_sid($sid);
    $exs = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_xinfo WHERE uid='".$uid."'"));
    if($exs[0]>0)
    {
        $res = mysql_query("UPDATE fun_xinfo SET likes='".$ulik."', deslikes='".$udlk."', habitsb='".$ubht."', habitsg='".$ught."', favsport='".$ufsp."', favmusic='".$ufmc."',moretext='".$umtx."'  WHERE uid='".$uid."'");
        if($res)
        {
          echo "<img src=\"images/ok.gif\" alt=\"O\"/>Info Updated Successfully<br/><br/>";
        }else{
            echo "<img src=\"images/notok.gif\" alt=\"O\"/>Database Error!<br/><br/>";
        }
    }else{
        $res = mysql_query("INSERT INTO fun_xinfo SET uid='".$uid."', likes='".$ulik."', deslikes='".$udlk."', habitsb='".$ubht."', habitsg='".$ught."', favsport='".$ufsp."', favmusic='".$ufmc."',moretext='".$umtx."'");
        if($res)
        {
          echo "<img src=\"images/ok.gif\" alt=\"O\"/>Info Updated Successfully<br/><br/>";
        }else{
            echo "<img src=\"images/notok.gif\" alt=\"O\"/>Database Error!<br/><br/>";
        }
    }
    echo "<a href=\"index.php?uxset\">";
echo "Extended Settings</a><br/>";
  echo "</p>";
  echo dnoonline($sid,$uid);
    
}

else if(isset($_GET['mkroom']))
{
        $rname = mysql_escape_string($_POST["rname"]);
        $rpass = trim($_POST["rpass"]);
        addonline(getuid_sid($sid),"Creating Chatroom","");
        
   echo vrhonline($sid,$uid);
        echo "<p align=\"center\">";
        if ($rpass=="")
        {
          $cns = 1;
        }else{
            $cns = 0;
        }
        $prooms = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_rooms WHERE static='0'"));
        if($prooms[0]<10)
        {
        $res = mysql_query("INSERT INTO fun_rooms SET name='".$rname."', pass='".$rpass."', censord='".$cns."', static='0', lastmsg='".time()."'");
        if($res)
        {
          echo "<img src=\"images/ok.gif\" alt=\"O\"/>Room created successfully<br/><br/>";
        }else{
            echo "<img src=\"images/notok.gif\" alt=\"X\"/>Database Error!<br/><br/>";
        }
        }else{
            echo "<img src=\"images/notok.gif\" alt=\"X\"/>There's already 10 users rooms<br/><br/>";
        }
        echo "<a href=\"index.php?uchat\"><img src=\"images/chat.gif\" alt=\"*\"/>Chatrooms</a><br/>";
        echo "</p>";
  echo dnoonline($sid,$uid);
        
        
}

else if(isset($_GET['signgb']))
{
  $who = $_POST["who"];
  $msgtxt = $_POST["msgtxt"];
  //$qut = $_POST["qut"];
  addonline(getuid_sid($sid),"Signing a guestbook","");
  
   echo vrhonline($sid,$uid);
      echo "<p align=\"center\">";
      $crdate = time();
      //$uid = getuid_sid($sid);
      $res = false;

      if(trim($msgtxt)!="")
      {
        
      $res = mysql_query("INSERT INTO fun_gbook SET gbowner='".$who."', gbsigner='".$uid."', dtime='".$crdate."', gbmsg='".$msgtxt."'");
      }
      if($res)
      {
        echo "<img src=\"images/ok.gif\" alt=\"O\"/>Message Posted Successfully";
      }else{
        echo "<img src=\"images/notok.gif\" alt=\"X\"/>Error Posting Message";
      }
      
      echo "<br/><br/>";
      echo "</p>";
  echo dnoonline($sid,$uid);
      

}
else if(isset($_GET['votepl']))
{
  //$uid = getuid_sid($sid);
  $plid = $_GET["plid"];
  $ans = $_GET["ans"];
  addonline(getuid_sid($sid),"Poll Voting ;)","");
  
   echo vrhonline($sid,$uid);
    echo "<p align=\"center\">";
    $voted = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_presults WHERE uid='".$uid."' AND pid='".$plid."'"));
    if($voted[0]==0)
    {
        $res = mysql_query("INSERT INTO fun_presults SET uid='".$uid."', pid='".$plid."', ans='".$ans."'");
        if($res)
        {
            echo "<img src=\"images/ok.gif\" alt=\"o\"/>Thanx for your voting";
        }else{
            echo "<img src=\"images/notok.gif\" alt=\"x\"/>Database Error!";
        }
    }else{
        echo "<img src=\"images/notok.gif\" alt=\"x\"/>You already voted for this poll";
    }
  echo "</p>";
  echo dnoonline($sid,$uid);
  
}
else if(isset($_GET['dlpoll']))
{
  //$uid = getuid_sid($sid);
  addonline(getuid_sid($sid),"Deleting Poll","");
  
   echo vrhonline($sid,$uid);
    echo "<p align=\"center\">";
    $pid = mysql_fetch_array(mysql_query("SELECT pollid FROM fun_users WHERE id='".$uid."'"));
        $res = mysql_query("UPDATE fun_users SET pollid='0' WHERE id='".$uid."'");
        if($res)
        {
          $res = mysql_query("DELETE FROM fun_presults WHERE pid='".$pid[0]."'");
		  $res = mysql_query("DELETE FROM fun_pp_pres WHERE pid='".$pid[0]."'");
          $res = mysql_query("DELETE FROM fun_polls WHERE id='".$pid[0]."'");
            echo "<img src=\"images/ok.gif\" alt=\"o\"/>Poll Deleted";
        }else{
            echo "<img src=\"images/notok.gif\" alt=\"x\"/>Database Error!";
        }
  echo "</p>";
  echo dnoonline($sid,$uid);
  
}

else if(isset($_GET['delan']))
{
  //$uid = getuid_sid($sid);
  addonline(getuid_sid($sid),"Deleting Announcement","");
  
   echo vrhonline($sid,$uid);
  $clid = $_GET["clid"];
  $anid = $_GET["anid"];
  $uid = getuid_sid($sid);
    echo "<p align=\"center\">";
    $pid = mysql_fetch_array(mysql_query("SELECT owner FROM fun_clubs WHERE id='".$clid."'"));
    $exs = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_announcements WHERE id='".$anid."' AND clid='".$clid."'"));
    if(($uid==$pid[0])&&($exs[0]>0))
    {
        $res = mysql_query("DELETE FROM fun_announcements WHERE id='".$anid."'");
        if($res)
        {

            echo "<img src=\"images/ok.gif\" alt=\"o\"/>Announcement Deleted";
        }else{
            echo "<img src=\"images/notok.gif\" alt=\"x\"/>Database Error!";
        }
    }else{
        echo "<img src=\"images/notok.gif\" alt=\"x\"/>Yo can't delete this announcement!";
    }
  echo "</p>";
  echo dnoonline($sid,$uid);
  
}

else if(isset($_GET['dlcl']))
{
  //$uid = getuid_sid($sid);
  addonline(getuid_sid($sid),"Deleting Club","");
  
   echo vrhonline($sid,$uid);
  $clid = $_GET["clid"];
  $uid = getuid_sid($sid);
  
    $pid = mysql_fetch_array(mysql_query("SELECT owner FROM fun_clubs WHERE id='".$clid."'"));
    if($uid==$pid[0])
    {
        $res = deleteClub($clid);
        if($res)
        {
          
            echo "Grupa obrisana";
        }else{
            echo "Greska u bazi";
        }
    }else{
        echo "Ti ne mo zes da obrises ovu grupu";
    }
	
  echo dnoonline($sid,$uid);
  
}

else if(isset($_GET['dltpl']))
{
  //$uid = getuid_sid($sid);
  $tid = $_GET["tid"];
  addonline(getuid_sid($sid),"Deleting Poll","");
  
   echo vrhonline($sid,$uid);
    echo "<p align=\"center\">";
    $pid = mysql_fetch_array(mysql_query("SELECT pollid FROM fun_topics WHERE id='".$tid."'"));
        $res = mysql_query("UPDATE fun_topics SET pollid='0' WHERE id='".$tid."'");
        if($res)
        {
          $res = mysql_query("DELETE FROM fun_presults WHERE pid='".$pid[0]."'");
          $res = mysql_query("DELETE FROM fun_polls WHERE id='".$pid[0]."'");
            echo "<img src=\"images/ok.gif\" alt=\"o\"/>Poll Deleted";
        }else{
            echo "<img src=\"images/notok.gif\" alt=\"x\"/>Database Error!";
        }
  echo "</p>";
  echo dnoonline($sid,$uid);
  
}

else if(isset($_GET['reqjc']))
{
  //$uid = getuid_sid($sid);
  $clid = $_GET["clid"];
  addonline(getuid_sid($sid),"Joining A Club","");
  
   echo vrhonline($sid,$uid);
    echo "<p align=\"center\">";
    $uid = getuid_sid($sid);
    $isin = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_clubmembers WHERE uid='".$uid."' AND clid='".$clid."'"));
    if($isin[0]==0){
        $res = mysql_query("INSERT INTO fun_clubmembers SET uid='".$uid."', clid='".$clid."', accepted='0', points='0', joined='".time()."'");
        if($res)
        {
            echo "<img src=\"images/ok.gif\" alt=\"o\"/>Request sent! the club owner should accept your request";
        }else{
            echo "<img src=\"images/notok.gif\" alt=\"x\"/>Database Error!";
        }
        }else{
            echo "<img src=\"images/notok.gif\" alt=\"x\"/>You already in this club or request sent and waiting for acception";
        }
  echo "</p>";
  echo dnoonline($sid,$uid);
  
}

else if(isset($_GET['unjc']))
{
  //$uid = getuid_sid($sid);
  $clid = $_GET["clid"];
  addonline(getuid_sid($sid),"Unjoining club","");
  
   echo vrhonline($sid,$uid);
    echo "<p align=\"center\">";
    $uid = getuid_sid($sid);
    $isin = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_clubmembers WHERE uid='".$uid."' AND clid='".$clid."'"));
    if($isin[0]>0){
        $res = mysql_query("DELETE FROM fun_clubmembers WHERE uid='".$uid."' AND clid='".$clid."'");
        if($res)
        {
            echo "<img src=\"images/ok.gif\" alt=\"o\"/>Unjoined club successfully";
        }else{
            echo "<img src=\"images/notok.gif\" alt=\"x\"/>Database Error!";
        }
        }else{
            echo "<img src=\"images/notok.gif\" alt=\"x\"/>You're not a member of this club!";
        }
  echo "</p>";
  echo dnoonline($sid,$uid);
  
}

else if(isset($_GET['acm']))
{
  //$uid = getuid_sid($sid);
  $clid = $_GET["clid"];
  $who = $_GET["who"];
  addonline(getuid_sid($sid),"Adding a member to club","");
  
   echo vrhonline($sid,$uid);
    echo "<p align=\"center\">";
    $uid = getuid_sid($sid);
    $cowner = mysql_fetch_array(mysql_query("SELECT owner FROM fun_clubs WHERE id='".$clid."'"));
    if($cowner[0]==$uid){
        $res = mysql_query("UPDATE fun_clubmembers SET accepted='1' WHERE clid='".$clid."' AND uid='".$who."'");
        if($res)
        {
            echo "<img src=\"images/ok.gif\" alt=\"o\"/>Member added to your club";
        }else{
            echo "<img src=\"images/notok.gif\" alt=\"x\"/>Database Error!";
        }
        }else{
            echo "<img src=\"images/notok.gif\" alt=\"x\"/>This club ain't yours";
        }
  echo "</p>";
  echo dnoonline($sid,$uid);
  
}
else if(isset($_GET['accall']))
{
  //$uid = getuid_sid($sid);
  $clid = $_GET["clid"];
  
  addonline(getuid_sid($sid),"Adding a member to club","");
  
   echo vrhonline($sid,$uid);
    echo "<p align=\"center\">";
    $uid = getuid_sid($sid);
    $cowner = mysql_fetch_array(mysql_query("SELECT owner FROM fun_clubs WHERE id='".$clid."'"));
    if($cowner[0]==$uid){
        $res = mysql_query("UPDATE fun_clubmembers SET accepted='1' WHERE clid='".$clid."'");
        if($res)
        {
            echo "<img src=\"images/ok.gif\" alt=\"o\"/>All Members Accepted";
        }else{
            echo "<img src=\"images/notok.gif\" alt=\"x\"/>Database Error!";
        }
        }else{
            echo "<img src=\"images/notok.gif\" alt=\"x\"/>This club ain't yours";
        }
  echo "</p>";
  echo dnoonline($sid,$uid);
  
}
else if(isset($_GET['denall']))
{
  //$uid = getuid_sid($sid);
  $clid = $_GET["clid"];
  
  addonline(getuid_sid($sid),"Adding a member to club","");
  
   echo vrhonline($sid,$uid);
    echo "<p align=\"center\">";
    $uid = getuid_sid($sid);
    $cowner = mysql_fetch_array(mysql_query("SELECT owner FROM fun_clubs WHERE id='".$clid."'"));
    if($cowner[0]==$uid){
        $res = mysql_query("DELETE FROM fun_clubmembers WHERE accepted='0' AND clid='".$clid."'");
        if($res)
        {
            echo "<img src=\"images/ok.gif\" alt=\"o\"/>All Members Denied";
        }else{
            echo "<img src=\"images/notok.gif\" alt=\"x\"/>Database Error!";
        }
        }else{
            echo "<img src=\"images/notok.gif\" alt=\"x\"/>This club ain't yours";
        }
  echo "</p>";
  echo dnoonline($sid,$uid);
  
}
else if(isset($_GET['dcm']))
{
  //$uid = getuid_sid($sid);
  $clid = $_GET["clid"];
  $who = $_GET["who"];
  addonline(getuid_sid($sid),"Deleting a member from club","");
  
   echo vrhonline($sid,$uid);
    echo "<p align=\"center\">";
    $uid = getuid_sid($sid);
    $cowner = mysql_fetch_array(mysql_query("SELECT owner FROM fun_clubs WHERE id='".$clid."'"));
    if($cowner[0]==$uid){
        $res = mysql_query("DELETE FROM fun_clubmembers  WHERE clid='".$clid."' AND uid='".$who."'");
        if($res)
        {
            echo "<img src=\"images/ok.gif\" alt=\"o\"/>Member deleted from your club";
        }else{
            echo "<img src=\"images/notok.gif\" alt=\"x\"/>Database Error!";
        }
        }else{
            echo "<img src=\"images/notok.gif\" alt=\"x\"/>This club ain't yours";
        }
  echo "</p>";
  echo dnoonline($sid,$uid);
  
}

else if(isset($_GET['crpoll']))
{
  addonline(getuid_sid($sid),"Creating Poll","");
    
   echo vrhonline($sid,$uid);
    echo "<p align=\"center\">";
    //$uid = getuid_sid($sid);
    if(getplusses(getuid_sid($sid))>=50)
    {
    $pid = mysql_fetch_array(mysql_query("SELECT pollid FROM fun_users WHERE id='".$uid."'"));
        if($pid[0] == 0)
        {
          $pques = $_POST["pques"];
          $opt1 = $_POST["opt1"];
          $opt2 = $_POST["opt2"];
          $opt3 = $_POST["opt3"];
          $opt4 = $_POST["opt4"];
          $opt5 = $_POST["opt5"];
          if((trim($pques)!="")&&(trim($opt1)!="")&&(trim($opt2)!=""))
          {
            $pex = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_polls WHERE pqst LIKE '".$pques."'"));
            if($pex[0]==0)
            {
              $res = mysql_query("INSERT INTO fun_polls SET pqst='".$pques."', opt1='".$opt1."', opt2='".$opt2."', opt3='".$opt3."', opt4='".$opt4."', opt5='".$opt5."', pdt='".time()."'");
              if($res)
              {
                $pollid = mysql_fetch_array(mysql_query("SELECT id FROM fun_polls WHERE pqst='".$pques."' "));
                mysql_query("UPDATE fun_users SET pollid='".$pollid[0]."' WHERE id='".$uid."'");
                echo "<img src=\"images/ok.gif\" alt=\"O\"/>Your poll created successfully";
              }else{
                echo "<img src=\"images/notok.gif\" alt=\"x\"/>Database Eroor!";
              }
                }else{
            echo "<img src=\"images/notok.gif\" alt=\"x\"/>There's already a poll with the same question";
          }

          }else{
             echo "<img src=\"images/notok.gif\" alt=\"x\"/>The poll must have a question, and at least 2 options";
          }
          }else{
            echo "<img src=\"images/notok.gif\" alt=\"x\"/>You already have a poll";
          }
          }else{
            echo "<img src=\"images/notok.gif\" alt=\"x\"/>You should have at least 50 plusses to create a poll";

          }
  echo "</p>";
  echo dnoonline($sid,$uid);
    
}
else if(isset($_GET['pltpc']))
{
  $tid = $_GET["tid"];
  addonline(getuid_sid($sid),"Creating Poll","");
    
   echo vrhonline($sid,$uid);
    echo "<p align=\"center\">";
    //$uid = getuid_sid($sid);
    if((getplusses(getuid_sid($sid))>=500)||ismod($uid))
    {
    $pid = mysql_fetch_array(mysql_query("SELECT pollid FROM fun_topics WHERE id='".$tid."'"));
        if($pid[0] == 0)
        {
          $pques = $_POST["pques"];
          $opt1 = $_POST["opt1"];
          $opt2 = $_POST["opt2"];
          $opt3 = $_POST["opt3"];
          $opt4 = $_POST["opt4"];
          $opt5 = $_POST["opt5"];
          if((trim($pques)!="")&&(trim($opt1)!="")&&(trim($opt2)!=""))
          {
            $pex = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_polls WHERE pqst LIKE '".$pques."'"));
            if($pex[0]==0)
            {
              $res = mysql_query("INSERT INTO fun_polls SET pqst='".$pques."', opt1='".$opt1."', opt2='".$opt2."', opt3='".$opt3."', opt4='".$opt4."', opt5='".$opt5."', pdt='".time()."'");
              if($res)
              {
                $pollid = mysql_fetch_array(mysql_query("SELECT id FROM fun_polls WHERE pqst='".$pques."' "));
                mysql_query("UPDATE fun_topics SET pollid='".$pollid[0]."' WHERE id='".$tid."'");
                echo "<img src=\"images/ok.gif\" alt=\"O\"/>Your poll created successfully";
              }else{
                echo "<img src=\"images/notok.gif\" alt=\"x\"/>Database Eroor!";
              }
                }else{
            echo "<img src=\"images/notok.gif\" alt=\"x\"/>There's already a poll with the same question";
          }

          }else{
             echo "<img src=\"images/notok.gif\" alt=\"x\"/>The poll must have a question, and at least 2 options";
          }
          }else{
            echo "<img src=\"images/notok.gif\" alt=\"x\"/>This Topic Already Have A poll";
          }
          }else{
            echo "<img src=\"images/notok.gif\" alt=\"x\"/>You should have at least 500 plusses to create a poll";

          }
  echo "</p>";
  echo dnoonline($sid,$uid);
    
}
else if(isset($_GET['addblg']))
{

if(!getplusses(getuid_sid($sid))>50)
    {
        
   echo vrhonline($sid,$uid);
      echo "<p align=\"center\">";
      echo "Only 50+ plusses can add blogs<br/>";
      echo "</p>";
  echo dnoonline($sid,$uid);
      exit();
    }
  $msgtxt = $_POST["btitle"];
  $msgtxt = $_POST["msgtxt"];
  //$qut = $_POST["qut"];
  addonline(getuid_sid($sid),"Adding a blog","");
  
   echo vrhonline($sid,$uid);
      echo "<p align=\"center\">";
      $crdate = time();
      //$uid = getuid_sid($sid);
      $res = false;

      if((trim($msgtxt)!="")&&(trim($btitle)!=""))
      {
      $res = mysql_query("INSERT INTO fun_blogs SET bowner='".$uid."', bname='".$btitle."', bgdate='".$crdate."', btext='".$msgtxt."'");
      }
      if($res)
      {
        echo "<img src=\"images/ok.gif\" alt=\"O\"/>Message Posted Successfully";
      }else{
        echo "<img src=\"images/notok.gif\" alt=\"X\"/>Error Posting Message";
      }

      echo "<br/><br/>";
      echo "</p>";
  echo dnoonline($sid,$uid);
      

}

else if(isset($_GET['addvlt']))
{

if(!getplusses(getuid_sid($sid))>24)
    {
        
   echo vrhonline($sid,$uid);
      echo "<p align=\"center\">";
      echo "Only 25+ plusses can add a vault item<br/>";
      echo "</p>";
  echo dnoonline($sid,$uid);
      exit();
    }
  $viname = $_POST["viname"];
  $vilink = $_POST["vilink"];
  //$qut = $_POST["qut"];
  addonline(getuid_sid($sid),"Adding a vault item","");
  
   echo vrhonline($sid,$uid);
      echo "<p align=\"center\">";
      $crdate = time();
      //$uid = getuid_sid($sid);
      $res = false;

      if((trim($vilink)!="")&&(trim($viname)!=""))
      {
      $res = mysql_query("INSERT INTO fun_vault SET uid='".$uid."', title='".mysql_escape_string($viname)."', pudt='".$crdate."', itemurl='".$vilink."'");
      }
      if($res)
      {
        echo "<img src=\"images/ok.gif\" alt=\"O\"/>Item added Successfully";
      }else{
        echo "<img src=\"images/notok.gif\" alt=\"X\"/>Error adding an item";
      }

      echo "<br/><br/>";
      echo "</p>";
  echo dnoonline($sid,$uid);
      

}
//////////////////////////////////////////shout

else if(isset($_GET['shout']))
{
  $shtxt = $_POST["shtxt"];
    addonline(getuid_sid($sid),"Shouting","");

   echo vrhonline($sid,$uid);
    
    echo "<p align=\"center\">";
    if(getplusses(getuid_sid($sid))<0)
    {
        echo "<img src=\"images/notok.gif\" alt=\"X\"/>You should have at least 75 plusses to shout!";
    }else{
      $shtxt = $shtxt;
    //$uid = getuid_sid($sid);
    $shtm = time();
    $res = mysql_query("INSERT INTO fun_shouts SET shout='".$shtxt."', shouter='".$uid."', shtime='".$shtm."'");
    if($res)
    {
    $shts = mysql_fetch_array(mysql_query("SELECT shouts from fun_users WHERE id='".$uid."'"));
    $shts = $shts[0]+1;
    mysql_query("UPDATE fun_users SET shouts='".$shts."' WHERE id='".$uid."'");
    echo "<img src=\"images/ok.gif\" alt=\"O\"/>Shout added successfully";
    }else{
        echo "<img src=\"images/notok.gif\" alt=\"X\"/>Database Error";
    }
            }
    echo "</p>";
  echo dnoonline($sid,$uid);
    
}

//////////////////////////////////////////Announce

else if(isset($_GET['annc']))
{
  $antx = $_POST["antx"];
  $clid = $_GET["clid"];
    addonline(getuid_sid($sid),"Announcing","");
   echo vrhonline($sid,$uid);
$cow = mysql_fetch_array(mysql_query("SELECT owner FROM fun_clubs WHERE id='".$clid."'"));
    $uid = getuid_sid($sid);
    
    echo "<p align=\"center\">";
    if($cow[0]!=$uid)
    {
        echo "<img src=\"images/notok.gif\" alt=\"X\"/>This is not your club!";
    }else{
      $shtxt = $shtxt;
    //$uid = getuid_sid($sid);
    $shtm = time();
    $res = mysql_query("INSERT INTO fun_announcements SET antext='".$antx."', clid='".$clid."', antime='".$shtm."'");
    if($res)
    {
    echo "<img src=\"images/ok.gif\" alt=\"O\"/>Announcement Added!";
    }else{
        echo "<img src=\"images/notok.gif\" alt=\"X\"/>Database Error";
    }
            }
    echo "</p>";
  echo dnoonline($sid,$uid);
}

else if(isset($_GET['rateb']))
{
  $brate = $_POST["brate"];
  $bid = $_GET["bid"];
  addonline(getuid_sid($sid),"Rating a blog","");
  //$uid = getuid_sid($sid);
  
   echo vrhonline($sid,$uid);
  
  echo "<p align=\"center\">";
  $vb = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_brate WHERE uid='".$uid."' AND blogid='".$bid."'"));
  if($vb[0]==0)
  {
    $res = mysql_query("INSERT INTO fun_brate SET uid='".$uid."', blogid='".$bid."', brate='".$brate."'");
    if($res)
    {
        echo "<img src=\"images/ok.gif\" alt=\"o\"/>Blog rated successfully<br/>";
    }else{
        echo "<img src=\"images/notok.gif\" alt=\"x\"/>Database Error!<br/>";
    }
  }else{
    echo "<img src=\"images/notok.gif\" alt=\"x\"/>You have rated this blog before<br/>";
  }
  echo "<br/><br/>";
  echo "</p>";
  echo dnoonline($sid,$uid);
  
}

else if(isset($_GET['delfgb']))
{
    $mid = $_GET["mid"];
  addonline(getuid_sid($sid),"Deleting GB Message","");
  
   echo vrhonline($sid,$uid);
  echo "<p align=\"center\">";
  if(candelgb(getuid_sid($sid), $mid))
  {
    $res = mysql_query("DELETE FROM fun_gbook WHERE id='".$mid."'");
    if($res)
        {
            echo "<img src=\"images/ok.gif\" alt=\"o\"/>Message Deleted From Guestbook<br/>";
        }else{
          echo "<img src=\"images/notok.gif\" alt=\"x\"/>Database Error!<br/>";
        }
  }else{
    echo "<img src=\"images/notok.gif\" alt=\"X\"/>You can't delete this message";
  }
  echo "<br/><br/>";
  echo "</p>";
  echo dnoonline($sid,$uid);
}

else if(isset($_GET['delvlt']))
{
    $vid = $_GET["vid"];
  addonline(getuid_sid($sid),"Deleting Vault Item","");
  
   echo vrhonline($sid,$uid);
  echo "<p align=\"center\">";
  $itemowner = mysql_fetch_array(mysql_query("SELECT uid FROM fun_vault WHERE id='".$vid."'"));
  if(ismod(getuid_sid($sid))||getuid_sid($sid)==$itemowner[0])
  {
    $res = mysql_query("DELETE FROM fun_vault WHERE id='".$vid."'");
    if($res)
        {
            echo "<img src=\"images/ok.gif\" alt=\"o\"/>Item Deleted From Vault<br/>";
        }else{
          echo "<img src=\"images/notok.gif\" alt=\"x\"/>Database Error!<br/>";
        }
  }else{
    echo "<img src=\"images/notok.gif\" alt=\"X\"/>You can't delete this item";
  }
  echo "<br/><br/>";
  echo "</p>";
  echo dnoonline($sid,$uid);
}

else if(isset($_GET['delbl']))
{
    $bid = $_GET["bid"];
  addonline(getuid_sid($sid),"Deleting A Blog","");
  
   echo vrhonline($sid,$uid);
  if(candelbl(getuid_sid($sid), $bid))
  {
    $res = mysql_query("DELETE FROM fun_blogs WHERE id='".$bid."'");
    if($res)
        {
   mysql_query("DELETE FROM fun_blogscomment WHERE blogowner='".$bid."'");
            echo "Zapis uspesno obrisan...";
        }else{
          echo "Zapis nije obrisan...";
        }
  }else{
    echo "Vi nemate dozvolu a obrisete ovaj zapis...";
  }
  echo dnoonline($sid,$uid);
}
else if(isset($_GET['rpost']))
{
  $pid = $_GET["pid"];
  addonline(getuid_sid($sid),"Reporting Post","");
  
   echo vrhonline($sid,$uid);
  echo "<p align=\"center\">";
  $pinfo = mysql_fetch_array(mysql_query("SELECT reported FROM fun_posts WHERE id='".$pid."'"));
          if($pinfo[0]=="0")
          {
          $str = mysql_query("UPDATE fun_posts SET reported='1' WHERE id='".$pid."' ");
          if($str)
          {
            echo "<img src=\"images/ok.gif\" alt=\"O\"/>Post reported to mods successfully";
          }else{
            echo "<img src=\"images/notok.gif\" alt=\"X\"/>Can't report post at the moment";
          }
          }else{
            echo "<img src=\"images/notok.gif\" alt=\"X\"/>This Post is already reported";
          }
          echo "<br/><br/>";
  echo "</p>";
  echo dnoonline($sid,$uid);
        
        
}


else if(isset($_GET['rtpc']))
{
  $tid = $_GET["tid"];
  addonline(getuid_sid($sid),"Reporting Topic","");
  
   echo vrhonline($sid,$uid);
  echo "<p align=\"center\">";
  $pinfo = mysql_fetch_array(mysql_query("SELECT reported FROM fun_topics WHERE id='".$tid."'"));
          if($pinfo[0]=="0")
          {
          $str = mysql_query("UPDATE fun_topics SET reported='1' WHERE id='".$tid."' ");
          if($str)
          {
            echo "<img src=\"images/ok.gif\" alt=\"O\"/>Topic reported to mods successfully";
          }else{
            echo "<img src=\"images/notok.gif\" alt=\"X\"/>Can't report topic at the moment";
          }
          }else{
            echo "<img src=\"images/notok.gif\" alt=\"X\"/>This Topic is already reported";
          }
          echo "<br/><br/>";
  echo "</p>";
  echo dnoonline($sid,$uid);


}

else if(isset($_GET['bud']))
{
  $todo = $_GET["todo"];
  $who = $_GET["who"];
  addonline(getuid_sid($sid),"Adding/Removing Buddy","");
  
   echo vrhonline($sid,$uid);
  //$uid = getuid_sid($sid);
    $unick = getnick_uid($uid);
    $tnick = getnick_uid($who);
  if($todo=="add")
  {
    if(budres($uid,$who)!=3){
    if(arebuds($uid,$who))
    {
      echo "<small>$tnick je vec vas prijatelj</small>";
    }else if(budres($uid, $who)==0)
    {
        $res = mysql_query("INSERT INTO fun_buddies SET uid='".$uid."', tid='".$who."', reqdt='".time()."'");
        if($res)
        {
            echo "<small>Zahtev za prijateljstvo je poslat korisniku $tnick</small>";
        }else{
          echo "<small>Ne mozes $tnick da dodas u prijatelje</small>";
        }
    }
else if(budres($uid, $who)==1)
    {
        $res = mysql_query("UPDATE fun_buddies SET agreed='1' WHERE uid='".$who."' AND tid='".$uid."'");
        if($res)
        {
            echo "<small>$tnick je ispesno dodat/a u tvoju listu prijatelja</small>";
        }else{
          echo "<small>Ne mozes $tnick da dodas u prijatelje</small>";
        }
    }
    else{
        echo "<small>Ne mozes $tnick da dodas u prijatelje</small>";
    }
    }else{
        echo "<small>Ne mozes $tnick da dodas u prijatelje</small>";
    }
  }else if($todo="del")
  {
    
        
      
      $res= mysql_query("DELETE FROM fun_buddies WHERE (uid='".$uid."' AND tid='".$who."') OR (uid='".$who."' AND tid='".$uid."')");
      if($res)
        {
            echo "<small>$tnick je izbacen/a iz prijatelja</small>";
        }else{
          echo "<small>Ne mozes $tnick da izbacis iz prijatelja</small>";
        }

  }
  echo dnoonline($sid,$uid);
}

//////////////////////////////////////////Update buddy message
else if(isset($_GET['upbmsg']))
{
    addonline(getuid_sid($sid),"Updating Buddy message","");
    $bmsg = $_POST["bmsg"];
    
   echo vrhonline($sid,$uid);
  echo "<p align=\"center\">";
  //$uid = getuid_sid($sid);
  $res = mysql_query("UPDATE fun_users SET budmsg='".$bmsg."' WHERE id='".$uid."'");
  if($res)
        {
            echo "<img src=\"images/ok.gif\" alt=\"o\"/>Buddy message updated successfully<br/>";
        }else{
          echo "<img src=\"images/notok.gif\" alt=\"x\"/>can't update your buddy message<br/>";
        }
echo "</p>";
  echo dnoonline($sid,$uid);
  
}

//////////////////////////////////////////Select Avatar
else if(isset($_GET['upav']))
{
    addonline(getuid_sid($sid),"Updating Avatar","");
    $avid = $_GET["avid"];
    
   echo vrhonline($sid,$uid);
  echo "<p align=\"center\">";
  //$uid = getuid_sid($sid);
  $avlnk = mysql_fetch_array(mysql_query("SELECT avlink FROM fun_avatars WHERE id='".$avid."'"));
  $res = mysql_query("UPDATE fun_users SET avatar='".$avlnk[0]."' WHERE id='".$uid."'");
  if($res)
        {
            echo "<img src=\"images/ok.gif\" alt=\"o\"/>Avatar Selected<br/>";
        }else{
          echo "<img src=\"images/notok.gif\" alt=\"x\"/>Database Error!<br/>";
        }
        echo "<br/>";
  
echo "</p>";
  echo dnoonline($sid,$uid);
  
}

//////////////////////////////////////////Select Avatar
else if(isset($_GET['upcm']))
{
    addonline(getuid_sid($sid),"Updating Chatmood","");
    $cmid = $_GET["cmid"];
    
   echo vrhonline($sid,$uid);
  echo "<p align=\"center\">";
  //$uid = getuid_sid($sid);
  $res = mysql_query("UPDATE fun_users SET chmood='".$cmid."' WHERE id='".$uid."'");
  if($res)
        {
            echo "<img src=\"images/ok.gif\" alt=\"o\"/>Mood Selected<br/>";
        }else{
          echo "<img src=\"images/notok.gif\" alt=\"x\"/>Database Error!<br/>";
        }
        echo "<br/>";
echo "<a href=\"index.php?chat\">";
echo "Chatrooms</a><br/>";
echo "</p>";
  echo dnoonline($sid,$uid);
  
}

//////////////////////////////////////////Give GPs
else if(isset($_GET['givegp']))
{
    addonline(getuid_sid($sid),"Giving Game Plusses","");
    $who = $_GET["who"];
    $ptg = $_POST["ptg"];
    
   echo vrhonline($sid,$uid);
  echo "<p align=\"center\">";
  //$uid = getuid_sid($sid);
  $gpsf = mysql_fetch_array(mysql_query("SELECT gplus FROM fun_users WHERE id='".$uid."'"));
  $gpst = mysql_fetch_array(mysql_query("SELECT gplus FROM fun_users WHERE id='".$who."'"));
  if($gpsf[0]>=$ptg){
    $gpsf = $gpsf[0]-$ptg;
    $gpst = $gpst[0]+$ptg;
    $res = mysql_query("UPDATE fun_users SET gplus='".$gpst."' WHERE id='".$who."'");
  if($res)
        {
          $res = mysql_query("UPDATE fun_users SET gplus='".$gpsf."' WHERE id='".$uid."'");
            echo "<img src=\"images/ok.gif\" alt=\"o\"/>Game Plusses Updated Successfully<br/>";
        }else{
          echo "<img src=\"images/notok.gif\" alt=\"x\"/>Database Error!<br/>";
        }
      }else{
          echo "<img src=\"images/notok.gif\" alt=\"x\"/>You don't have enough GPs to give<br/>";
        }

        echo "<br/>";
  
echo "</p>";
  echo dnoonline($sid,$uid);
  
}

//////////////////// add club

else if(isset($_GET['addcl']))
{
    addonline(getuid_sid($sid),"Adding Club","");
    $clnm = trim($_POST["clnm"]);
    $clnm = str_replace("$", "", $clnm);
    $clds = trim($_POST["clds"]);
    $clds = str_replace("$", "", $clds);
    $clrl = trim($_POST["clrl"]);
    $clrl = str_replace("$", "", $clrl);
    $cllg = trim($_POST["cllg"]);
    $cllg = str_replace("$", "", $cllg);
    
   echo vrhonline($sid,$uid);
    $uid = getuid_sid($sid);

        if(($clnm=="")||($clds=="")||($clrl==""))
        {
          echo "Dali ste sigurni da ste popunili naziv, opis i pravila grupe?";
        }else{
          $nmex = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_clubs WHERE name LIKE '".$clnm."'"));
          if($nmex[0]>0)
          {
            echo "Grupa sa takvim imenom vec postoji...";
          }else{
            $res = mysql_query("INSERT INTO fun_clubs SET name='".$clnm."', owner='".$uid."', description='".$clds."', rules='".$clrl."', logo='".$cllg."', plusses='0', created='".time()."'");
            if($res)
            {
              $clid = mysql_fetch_array(mysql_query("SELECT id FROM fun_clubs WHERE owner='".$uid."' AND name='".$clnm."'"));
                echo "Cestitam... uspesno ste otvorili grupu...";
                mysql_query("INSERT INTO fun_clubmembers SET uid='".$uid."', clid='".$clid[0]."', accepted='1', points='50', joined='".time()."'");
                //$ups = getplusses($uid);
                //$ups += 5;
                //mysql_query("UPDATE fun_users SET plusses='".$ups."' WHERE id='".$uid."'");
                $fnm = $clnm;
                $cnm = $clnm;
                mysql_query("INSERT INTO fun_forums SET name='".$fnm."', position='0', cid='0', clubid='".$clid[0]."'");
                mysql_query("INSERT INTO fun_rooms SET name='".$cnm."', pass='', static='1', mage='0', chposts='0', perms='0', censord='0', freaky='0', lastmsg='".time()."', clubid='".$clid[0]."'");
            }else{
                echo "Greska u bazi...";
            }
          }
        }

    
  echo dnoonline($sid,$uid);
    
}
//////////////////////////////////////////Give GPs
else if(isset($_GET['batp']))
{
    addonline(getuid_sid($sid),"Giving Game Plusses","");
    $who = $_GET["who"];
    $ptg = $_POST["ptbp"];
    $giv = $_POST["giv"];
    
   echo vrhonline($sid,$uid);
  echo "<p align=\"center\">";
  //$uid = getuid_sid($sid);
  $judg = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_judges WHERE uid='".getuid_sid($sid)."'"));
  $gpst = mysql_fetch_array(mysql_query("SELECT battlep FROM fun_users WHERE id='".$who."'"));
  if(ismod(getuid_sid($sid))||$judg[0]>0)
  {
    if ($giv=="1")
    {
        $gpst = $gpst[0]+$ptg;
    }else{
        $gpst = $gpst[0]-$ptg;
        if($gpst<0)$gpst=0;
    }
    $res = mysql_query("UPDATE fun_users SET battlep='".$gpst."' WHERE id='".$who."'");
  if($res)
        {
          $vnick = getnick_uid($who);
          if ($giv=="1")
          {
            $ms1 = " Added $ptg points to ";
          }else{
            $ms1 = " removed $ptg points from ";
          }

          mysql_query("INSERT INTO fun_mlog SET action='bpoints', details='<b>".getnick_uid(getuid_sid($sid))."</b> $ms1  $vnick', actdt='".time()."'");
            echo "<img src=\"images/ok.gif\" alt=\"o\"/>Battle Points Updated Successfully<br/>";
        }else{
          echo "<img src=\"images/notok.gif\" alt=\"x\"/>Database Error!<br/>";
        }
      }else{
          echo "<img src=\"images/notok.gif\" alt=\"x\"/>You can't do this<br/>";
        }

        echo "<br/>";

echo "</p>";
  echo dnoonline($sid,$uid);
  
}

/////////////////////////////Add remove from ignoire list

else if(isset($_GET['ign']))
{
    addonline(getuid_sid($sid),"Updating ignore list","");
    $todo = $_GET["todo"];
    $who = $_GET["who"];
    
   echo vrhonline($sid,$uid);
  echo "<p align=\"center\">";
  //$uid = getuid_sid($sid);
  $tnick = getnick_uid($who);
  if($todo=="add")
  {
    if(ignoreres($uid, $who)==1)
    {
      $res= mysql_query("INSERT INTO fun_ignore SET name='".$uid."', target='".$who."'");
    if($res)
        {
            echo "<img src=\"images/ok.gif\" alt=\"o\"/>$tnick was added successfully to your ignore list<br/>";
        }else{
          echo "<img src=\"images/notok.gif\" alt=\"x\"/>Error Updating Database<br/>";
        }
    }else{
        echo "<img src=\"images/notok.gif\" alt=\"x\"/>You can't Add $tnick to your ignore list<br/>";
    }
  }else if($todo="del")
  {
    if(ignoreres($uid, $who)==2)
    {
      $res= mysql_query("DELETE FROM fun_ignore WHERE name='".$uid."' AND target='".$who."'");
      if($res)
        {
            echo "<img src=\"images/ok.gif\" alt=\"o\"/>$tnick was deleted successfully from your ignore list<br/>";
        }else{
          echo "<img src=\"images/notok.gif\" alt=\"x\"/>Error Updating Database<br/>";
        }
      }else{
        echo "<img src=\"images/notok.gif\" alt=\"x\"/>$tnick is not ignored by you<br/>";
      }
  }
  echo "<br/><a href=\"lists.php?ignl\">";
echo "Ignore List</a><br/>";
echo "</p>";
  echo dnoonline($sid,$uid);
  
}

//////////////////////////////////////////Update profile
else if(isset($_GET['uprof']))
{
    addonline(getuid_sid($sid),"Updating Settings","");
    $savat = $_POST["savat"];
    $semail = $_POST["semail"];
    $usite = $_POST["usite"];
    $ubday = $_POST["ubday"];
    $uloc = $_POST["uloc"];
    $usig = $_POST["usig"];
    $usex = $_POST["usex"];
    
   echo vrhonline($sid,$uid);
  echo "<p align=\"center\">";
  //$uid = getuid_sid($sid);
  $res = mysql_query("UPDATE fun_users SET avatar='".$savat."', email='".$semail."', site='".$usite."', birthday='".$ubday."', location='".$uloc."', signature='".$usig."', sex='".$usex."' WHERE id='".$uid."'");
  if($res)
  {
    echo "<img src=\"images/ok.gif\" alt=\"o\"/>Your profile was updated successfully<br/>";
  }else{
    echo "<img src=\"images/notok.gif\" alt=\"x\"/>Error updating your profile<br/>";
  }
  echo "</p>";
  echo dnoonline($sid,$uid);
}

//////////////////////////////////////////Update profile
else if(isset($_GET['shsml']))
{
    addonline(getuid_sid($sid),"Updating Smilies","");
    $act = $_GET["act"];
    $acts = ($act=="dis" ? 0 : 1);
    
   echo vrhonline($sid,$uid);
  echo "<p align=\"center\">";
  //$uid = getuid_sid($sid);
  $res = mysql_query("UPDATE fun_users SET hvia='".$acts."' WHERE id='".$uid."'");
  if($res)
  {
    echo "<img src=\"images/ok.gif\" alt=\"o\"/>Smilies Visibility updated successfully<br/>";
  }else{
    echo "<img src=\"images/notok.gif\" alt=\"x\"/>Error updating your profile<br/>";
  }
  echo "</p>";
  echo dnoonline($sid,$uid);
}

//////////////////////////////////////////Change Password

else if(isset($_GET['upwd']))
{
    addonline(getuid_sid($sid),"Updating Settings","");
    $npwd = $_POST["npwd"];
    $cpwd = $_POST["cpwd"];
    
   echo vrhonline($sid,$uid);
  echo "<p align=\"center\">";
  //$uid = getuid_sid($sid);
  if($npwd!=$cpwd)
  {
    echo "<img src=\"images/notok.gif\" alt=\"x\"/>Your Password and Confirm Password Doesn't match<br/>";
    
  }else if((strlen($npwd)<4) || (strlen($npwd)>15)){
    echo "<img src=\"images/notok.gif\" alt=\"x\"/>Your password should be between 4 and 15 letters only<br/>";

  }else{
    $pwd = md5($npwd);
    $res = mysql_query("UPDATE fun_users SET pass='".$pwd."' WHERE id='".$uid."'");
    if($res)
  {
    echo "<img src=\"images/ok.gif\" alt=\"o\"/>Your password was updated successfully<br/>";
  }else{
    echo "<img src=\"images/notok.gif\" alt=\"x\"/>Error updating your password<br/>";
  }
  }
  echo "</p>";
  echo dnoonline($sid,$uid);
}
else{
   echo vrhonline($sid,$uid);
   
  echo "<p align=\"center\">";
  echo "I don't know how did you get into here, but there's nothing to show<br/><br/>";
  echo "</p>";
  echo dnoonline($sid,$uid);
}

	echo "</body>";
	echo "</html>";
?>
