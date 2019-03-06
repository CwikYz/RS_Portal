<?php


include("core.php");
include("config.php");


header("Content-type: text/html; charset=ISO-8859-1");
echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>";
echo "<!DOCTYPE html PUBLIC \"-//WAPFORUM//DTD XHTML Mobile 1.0//EN\"\"http://www.wapforum.org/DTD/xhtml-mobile10.dtd\">";
echo "<html xmlns=\"http://www.w3.org/1999/xhtml\">";

$bcon = connectdb();
if (!$bcon)
{
    
    echo "<p align=\"center\">";
    echo "<img src=\"images/exit.gif\" alt=\"*\"/><br/>";
    echo "ERROR! cannot connect to database<br/><br/>";
    echo "This error happens usually when backing up the database, please be patient, The site will be up any minute<br/><br/>";
    echo "Soon, we will offer services that doesn't depend on MySQL databse to let you enjoy our site, while the database is not connected<br/>";
    echo "<b>THANK YOU VERY MUCH</b>";
    echo "</p>";
    exit();
}
$brws = explode(" ",$HTTP_USER_AGENT);
$ubr = $brws[0];
$uip = getip();
$action = $_GET["action"];
$sid = $_SESSION["sid"];
$page = $_GET["page"];
$who = $_GET["who"];
	vrh($sid);

$uid = getuid_sid($sid);

    if((islogged($sid)==false)||($uid==0))
    {
        
      echo "<p align=\"center\">";
      echo "You are not logged in<br/>";
      echo "Or Your session has been expired<br/><br/>";
      echo "<a href=\"index.php\">Login</a>";
      echo "</p>";
      exit();
    }
if(isbanned($uid))
    {
        
      echo "<p align=\"center\">";
      echo "<img src=\"images/notok.gif\" alt=\"x\"/><br/>";
      echo "You are <b>Banned</b><br/>";
      $banto = mysql_fetch_array(mysql_query("SELECT timeto FROM fun_penalties WHERE uid='".$uid."' AND penalty='1'"));
      $remain = $banto[0]- time();
      $rmsg = gettimemsg($remain);
      echo "Time to finish your penalty: $rmsg<br/><br/>";
      //echo "<a href=\"index.php\">Login</a>";
      echo "</p>";
      exit();
    }
if(isset($_GET['']))
{
  addonline($uid,"Viewing User Profile","");
  
  echo "<p align=\"center\">";
  $whonick = getnick_uid($who);
  echo "<a href=\"index.php?viewuser&amp;who=$who\">$whonick's Basic Profile</a>";
  echo "</p>";
  echo "<p><small>";
  $regd = mysql_fetch_array(mysql_query("SELECT regdate FROM fun_users WHERE id='".$who."'"));
  $sage = time()-$regd[0];
  $rwage = ceil($sage/(24*60*60));
  echo "&#187;foggysworld age: <b>$rwage days</b><br/>";
  echo "&#187;foggysworld rating(0-5): <b>".geturate($who)."</b><br/>";
  $pstn = mysql_fetch_array(mysql_query("SELECT posts FROM fun_users WHERE id='".$who."'"));
  $ppd = $pstn[0]/$rwage;
  echo "&#187;Posts info: <b>$pstn[0]</b> posts, with average of <b>$ppd</b> posts/day<br/>";
  $chpn = mysql_fetch_array(mysql_query("SELECT chmsgs FROM fun_users WHERE id='".$who."'"));
  $cpd = $chpn[0]/$rwage;
  echo "&#187;Chating info: <b>$chpn[0]</b> chat messages, with average of <b>$cpd</b> chat messages/day<br/>";
  $gbsg = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_gbook WHERE gbsigner='".$who."'"));
  echo "&#187;Have signed: <b>$gbsg[0] Guestbooks</b><br/>";
  $brts = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_brate WHERE uid='".$who."'"));
  echo "&#187;Have rated: <b>$brts[0] Blogs</b><br/>";
  $pvts = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_presults WHERE uid='".$who."'"));
  echo "&#187;Have voted in <b>$pvts[0] Polls</b><br/>";
  $strd = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_private WHERE touid='".$who."' AND starred='1'"));
  echo "&#187;Starred PMs: <b>$strd[0]</b><br/><br/>";
  echo "<a href=\"uinfo.php?fsts&amp;who=$who\">&#187;Posts In forums</a><br/>";
  echo "<a href=\"uinfo.php?cinf&amp;who=$who\">&#187;Contact Info</a><br/>";
  echo "<a href=\"uinfo.php?look&amp;who=$who\">&#187;Looking</a><br/>";
  echo "<a href=\"uinfo.php?pers&amp;who=$who\">&#187;Personality</a><br/>";
  echo "<a href=\"uinfo.php?rwidc&amp;who=$who\">&#187;foggysworld ID Card</a><br/>";
  echo "</small></p>";
  echo "<p align=\"center\">";
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
  
}

else if(isset($_GET['rwidc']))
{
    addonline(getuid_sid($sid),"foggysworld ID","");
    
    echo "<p align=\"center\">";
    echo "<b>foggysworld! ID card</b><br/>";
    echo "<img src=\"rwidc.php?id=$who\" alt=\"ll id\"/><br/><br/>";
    echo "The source for this ID card is http://foggysworld.wapcodes.co.uk/rwidc.php?id=$who<br/><br/>";
    echo "To look at your card Go to CPanel&gt; foggysworld ID Card.";
    
    echo "</p>";
    echo "<p align=\"center\">";
    echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
    
}

else if(isset($_GET['fsts']))
{
    addonline($uid,"Viewing User Profile","");
    $whonick = getnick_uid($who);
    
    echo "<p><small>";
    echo "<a href=\"index.php?main\">Home</a>&gt;";

    echo "<a href=\"index.php?viewuser&amp;who=$who\">$whonick</a><br/>";
    echo "&gt;<a href=\"uinfo.php?sid=$sid&amp;who=$who\">Extended Info</a>&gt;Posts in forums<br/><br/>";
    $pst = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_posts WHERE uid='".$who."'"));
    $frms = mysql_query("SELECT id, name FROM fun_forums WHERE clubid='0' ORDER BY name");
    while ($frm=mysql_fetch_array($frms))
    {
      $nops = mysql_fetch_array(mysql_query("SELECT COUNT(*) as nops, a.uid FROM fun_posts a INNER JOIN fun_topics b ON a.tid = b.id WHERE a.uid='".$who."' AND b.fid='".$frm[0]."' GROUP BY a.uid "));
      $prc = ceil(($nops[0]/$pst[0])*100);
      echo htmlspecialchars($frm[1]).": <b>$nops[0] ($prc%)</b><br/>";
    }
    echo "<br/><a href=\"index.php?main\">Home</a>&gt;";
    
    echo "<a href=\"index.php?viewuser&amp;who=$who\">$whonick</a><br/>";
    echo "&gt;<a href=\"uinfo.php?sid=$sid&amp;who=$who\">Extended Info</a>&gt;Posts in forums";
    echo "</small></p>";
    
}

else if(isset($_GET['cinf']))
{
    addonline($uid,"Viewing User Profile","");
    $whonick = getnick_uid($who);
    
    echo "<p><small>";
    echo "<a href=\"index.php?main\">Home</a>&gt;";

    echo "<a href=\"index.php?viewuser&amp;who=$who\">$whonick</a><br/>";
    echo "&gt;<a href=\"uinfo.php?sid=$sid&amp;who=$who\">Extended Info</a>&gt;Contact Info<br/><br/>";
    //duh
    $inf1 = mysql_fetch_array(mysql_query("SELECT country, city, street, phoneno, realname, budsonly, sitedscr FROM fun_xinfo WHERE uid='".$who."'"));
    
    $inf2 = mysql_fetch_array(mysql_query("SELECT site, email FROM fun_users WHERE id='".$who."'"));
    if($inf1[5]=='1')
    {
    if(($uid==$who)||(arebuds($uid, $who)))
    {
        $rln = $inf1[4];
        $str = $inf1[2];
        $phn = $inf1[3];
    }else{
        $rln = "Can't view";
        $str = "Can't view";
        $phn = "Can't view";
    }
    }else{
      $rln = $inf1[4];
      $str = $inf1[2];
      $phn = $inf1[3];
    }
    echo "Real Name: $rln<br/>";
    echo "Country: $inf1[0]<br/>";
    echo "City: $inf1[1]<br/>";
    echo "Street: $str<br/>";
    echo "Site: <a href=\"$inf2[0]\">$inf2[0]</a><br/>";
    echo "Site description: $inf1[6]<br/>";
    echo "Phone No.: $phn<br/>";
    echo "E-Mail: $inf2[1]<br/>";
    //tuh
    echo "<br/><a href=\"index.php?main\">Home</a>&gt;";

    echo "<a href=\"index.php?viewuser&amp;who=$who\">$whonick</a><br/>";
    echo "&gt;<a href=\"uinfo.php?sid=$sid&amp;who=$who\">Extended Info</a>&gt;Contact Info";
    echo "</small></p>";
    
}

else if(isset($_GET['look']))
{
    addonline($uid,"Viewing User Profile","");
    $whonick = getnick_uid($who);
    
    echo "<p><small>";
    echo "<a href=\"index.php?main\">Home</a>&gt;";

    echo "<a href=\"index.php?viewuser&amp;who=$who\">$whonick</a><br/>";
    echo "&gt;<a href=\"uinfo.php?sid=$sid&amp;who=$who\">Extended Info</a>&gt;Looking<br/><br/>";
    //duh
    $inf1 = mysql_fetch_array(mysql_query("SELECT sexpre, height, weight, racerel, hairtype, eyescolor FROM fun_xinfo WHERE uid='".$who."'"));
    $inf2 = mysql_fetch_array(mysql_query("SELECT sex FROM fun_users WHERE id='".$who."'"));
    if($inf1[0]=="M" && $inf2[0]=="F")
    {
      $sxp = "Straight";
    }else if($inf1[0]=="F" && $inf2[0]=="M")
    {
      $sxp = "Straight";
    }else if($inf1[0]=="M" && $inf2[0]=="M"){
      $sxp = "Gay";
    }else if($inf1[0]=="F" && $inf2[0]=="F"){
      $sxp = "Lesbian";
    }else if($inf1[0]=="B"){
      $sxp = "Bisexual";
    }else{
      $sxp = "inapplicable";
    }
    if($inf2[0]=="M")
    {
      $usx = "Male";
    }else if($inf2[0]=="F")
    {
      $usx = "Female";
    }else{
      $usx = "Shemale";
    }
    echo "Sex: $usx<br/>";
    echo "Orientation: $sxp<br/>";
    echo "Height: $inf1[1]<br/>";
    echo "Weight: $inf1[2]<br/>";
    echo "Ethnic origin: $inf1[3]<br/>";
    echo "Hair: $inf1[4]<br/>";
    echo "Eyes: $inf1[5]<br/>";
    //tuh
    echo "<br/><a href=\"index.php?main\">Home</a>&gt;";

    echo "<a href=\"index.php?viewuser&amp;who=$who\">$whonick</a><br/>";
    echo "&gt;<a href=\"uinfo.php?sid=$sid&amp;who=$who\">Extended Info</a>&gt;Looking";
    echo "</small></p>";
    
}

else if(isset($_GET['pers']))
{
    addonline($uid,"Viewing User Profile","");
    $whonick = getnick_uid($who);
    
    echo "<p><small>";
    echo "<a href=\"index.php?main\">Home</a>&gt;";

    echo "<a href=\"index.php?viewuser&amp;who=$who\">$whonick</a><br/>";
    echo "&gt;<a href=\"uinfo.php?sid=$sid&amp;who=$who\">Extended Info</a>&gt;Personality<br/><br/>";
    //duh
    $inf1 = mysql_fetch_array(mysql_query("SELECT likes, deslikes, habitsb, habitsg, favsport, favmusic, moretext FROM fun_xinfo WHERE uid='".$who."'"));
    echo "<b>Likes:</b> ".parsemsg($inf1[0])."<br/>";
    echo "<b>Dislikes:</b> ".parsemsg($inf1[1])."<br/>";
    echo "<b>Bad Habits:</b> ".parsemsg($inf1[2])."<br/>";
    echo "<b>Good Habits:</b> ".parsemsg($inf1[3])."<br/>";
    echo "<b>Sport:</b> ".parsemsg($inf1[4])."<br/>";
    echo "<b>Music:</b> ".parsemsg($inf1[5])."<br/>";
    echo "<b>More text:</b> ".parsemsg($inf1[6])."<br/>";
    //tuh
    echo "<br/><a href=\"index.php?main\">Home</a>&gt;";

    echo "<a href=\"index.php?viewuser&amp;who=$who\">$whonick</a><br/>";
    echo "&gt;<a href=\"uinfo.php?sid=$sid&amp;who=$who\">Extended Info</a>&gt;Personality";
    echo "</small></p>";
    
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
