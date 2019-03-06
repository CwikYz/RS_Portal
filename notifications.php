<?php


include("core.php");
include("config.php");


header("Content-type: text/html; charset=ISO-8859-1");
echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>";
echo "<!DOCTYPE html PUBLIC \"-//WAPFORUM//DTD XHTML Mobile 1.0//EN\"\"http://www.wapforum.org/DTD/xhtml-mobile10.dtd\">";
echo "<html xmlns=\"http://www.w3.org/1999/xhtml\">";

	echo "<head>";

	echo "<title>$stitle</title>";
	echo "<link rel=\"StyleSheet\" type=\"text/css\" href=\"style/style.css\" />";
	echo "</head>";

	echo "<body>";
	vrh();
connectdb();
$action = $_GET["action"];
$sid = $_GET["sid"];
$page = $_GET["page"];
$who = $_GET["who"];
$pmid = $_GET["pmid"];
if(islogged($sid)==false)
{
    
      echo "<p align=\"center\">";
      echo "You are not logged in<br/>";
      echo "Or Your session has been expired<br/><br/>";
      echo "<a href=\"index.php\">Login</a>";
      echo "</p>";
      exit();
}
$uid = getuid_sid($sid);
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

if($action=="sendpm")
{
  addonline(getuid_sid($sid),"Sending PM","");
  
  echo "<p align=\"center\">";
  $whonick = getnick_uid($who);
  echo "Send PM to $whonick<br/><br/>";
echo "<form action=\"inbxproc.php?action=sendpm&amp;who=$who&amp;sid=$sid\" method=\"post\">";
  echo "<input name=\"pmtext\" maxlength=\"500\"/><br/>";
echo "<input type=\"submit\" value=\"SEND\"/>";
echo "</form>";
   echo "<br/><br/>";
  echo "<a href=\"index.php?action=main&amp;sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
    

}
else if($action=="sendto")
{
  addonline(getuid_sid($sid),"Sending PM","");
  
  echo "<p align=\"center\">";
  $whonick = getnick_uid($who);
  echo "Send PM to:<br/><br/>";
echo "<form action=\"inbxproc.php?action=sendto&amp;sid=$sid\" method=\"post\">";
  echo "User: <input name=\"who\" format=\"*x\" maxlength=\"15\"/><br/>";
  echo "Text: <input name=\"pmtext\" maxlength=\"500\"/><br/>";

echo "<input type=\"submit\" value=\"SEND\"/>";
echo "</form>";
  
  echo "<br/><br/>";
  echo "<a href=\"index.php?action=main&amp;sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
    

}
else if($action=="main")
{
  addonline(getuid_sid($sid),"User Inbox","");
    
    echo "<p align=\"center\">";
echo "<form action=\"inbox.php\" method=\"get\">";
    echo "View: <select name=\"view\">";
  echo "<option value=\"all\">All</option>";
  echo "<option value=\"snt\">Sent</option>";
  echo "<option value=\"str\">Starred</option>";
  echo "<option value=\"urd\">Unread</option>";
  echo "</select>";
echo "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
echo "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
echo "<input type=\"submit\" value=\"GO\"/>";
echo "</form>";
      echo "</p>";
    $view = $_GET["view"];
    //////ALL LISTS SCRIPT <<
    if($view=="")$view="all";
    if($page=="" || $page<=0)$page=1;
    $myid = getuid_sid($sid);
    $doit=false;
    $num_items = getpmcount($myid,$view); //changable
    $items_per_page= 7;
    $num_pages = ceil($num_items/$items_per_page);
    if($page>$num_pages)$page= $num_pages;
    $limit_start = ($page-1)*$items_per_page;
    if($num_items>0)
    {
      if($doit)
      {
        $exp = "&amp;rwho=$myid";
      }else
      {
        $exp = "";
      }
    //changable sql
    if($view=="all")
  {
    $sql = "SELECT
            a.name, b.id, b.byuid, b.unread, b.starred FROM fun_users a
            INNER JOIN fun_private b ON a.id = b.byuid
            WHERE b.touid='".$myid."'
            ORDER BY b.timesent DESC
            LIMIT $limit_start, $items_per_page
    ";
  }else if($view=="snt")
  {
    $sql = "SELECT
            a.name, b.id, b.touid, b.unread, b.starred FROM fun_users a
            INNER JOIN fun_private b ON a.id = b.touid
            WHERE b.byuid='".$myid."'
            ORDER BY b.timesent DESC
            LIMIT $limit_start, $items_per_page
    ";
  }else if($view=="str")
  {
    $sql = "SELECT
            a.name, b.id, b.byuid, b.unread, b.starred FROM fun_users a
            INNER JOIN fun_private b ON a.id = b.byuid
            WHERE b.touid='".$myid."' AND b.starred='1'
            ORDER BY b.timesent DESC
            LIMIT $limit_start, $items_per_page
    ";
  }else if($view=="urd")
  {
    $sql = "SELECT
            a.name, b.id, b.byuid, b.unread, b.starred FROM fun_users a
            INNER JOIN fun_private b ON a.id = b.byuid
            WHERE b.touid='".$myid."' AND b.unread='1'
            ORDER BY b.timesent DESC
            LIMIT $limit_start, $items_per_page
    ";
  }
    
    echo "<p><small>";
    $items = mysql_query($sql);
    echo mysql_error();
    while ($item = mysql_fetch_array($items))
    {
      if($item[3]=="1")
      {
        $iml = "<img src=\"images/npm.gif\" alt=\"+\"/>";
      }else{
        if($item[4]=="1")
        {
            $iml = "<img src=\"images/spm.gif\" alt=\"*\"/>";
        }else{

        $iml = "<img src=\"images/opm.gif\" alt=\"-\"/>";
        }
      }
      
      $lnk = "<a href=\"inbox.php?action=readpm&amp;pmid=$item[1]&amp;sid=$sid\">$iml $item[0]</a>";
      echo "$lnk<br/>";
    }
    echo "</small></p>";
    echo "<p align=\"center\">";
    
      $npage = $page+1;
      echo "<a href=\"inbox.php?action=sendto&amp;sid=$sid\">Send to</a><br/>";
    if($page>1)
    {
      $ppage = $page-1;
      echo "<a href=\"inbox.php?action=main&amp;page=$ppage&amp;sid=$sid&amp;view=$view$exp\">&#171;PREV</a> ";
    }
    if($page<$num_pages)
    {
      $npage = $page+1;
      echo "<a href=\"inbox.php?action=main&amp;page=$npage&amp;sid=$sid&amp;view=$view$exp\">Next&#187;</a>";
    }
    echo "<br/>$page/$num_pages<br/>";
    if($num_pages>2)
    {
$rets = "<form action=\"inbox.php\" method=\"get\">";
      $rets .= "Jump to page: <input name=\"page\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"GO\"/>";
        $rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
        $rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
         
        $rets .= "<input type=\"hidden\" name=\"view\" value=\"$view\"/>";
$rets .= "</form>";
        echo $rets;
      echo "<br/>";
    }
    echo "<br/>";
echo "<form action=\"inbxproc.php?action=proall&amp;sid=$sid\" method=\"post\">";
      echo "Delete: <select name=\"pmact\">";
  echo "<option value=\"ust\">Unstarred</option>";
  echo "<option value=\"red\">Read</option>";
  echo "<option value=\"all\">All</option>";
  echo "</select>";
echo "<input type=\"submit\" value=\"GO\"/>";
echo "</form>";

    echo "</p>";
    }else{
      echo "<p align=\"center\">";
      echo "You have no Private Messages";
      echo "</p>";
    }
  ////// UNTILL HERE >>

    
    
  echo "<p align=\"center\">";

  echo "<a href=\"index.php?action=main&amp;sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
  
  }
else if($action=="readpm")
{
  addonline(getuid_sid($sid),"Reading PM","");
  
  echo "<p>";

  $pminfo = mysql_fetch_array(mysql_query("SELECT text, byuid, timesent,touid, reported FROM fun_private WHERE id='".$pmid."'"));
  if(getuid_sid($sid)==$pminfo[3])
  {
    $chread = mysql_query("UPDATE fun_private SET unread='0' WHERE id='".$pmid."'");
  }
  
  if(($pminfo[3]==getuid_sid($sid))||($pminfo[1]==getuid_sid($sid)))
  {
  
  if(getuid_sid($sid)==$pminfo[3])
  {
    if(isonline($pminfo[1]))
  {
    $iml = "<img src=\"images/onl.gif\" alt=\"+\"/>";
  }else{
    $iml = "<img src=\"images/ofl.gif\" alt=\"-\"/>";
  }
    $ptxt = "PM By: ";
    
        $bylnk = "<a href=\"index.php?action=viewuser&amp;who=$pminfo[1]&amp;sid=$sid\">$iml".getnick_uid($pminfo[1])."</a>";

  }else{
    if(isonline($pminfo[3]))
  {
    $iml = "<img src=\"images/onl.gif\" alt=\"+\"/>";
  }else{
    $iml = "<img src=\"images/ofl.gif\" alt=\"-\"/>";
  }
    $ptxt = "PM To: ";
    
    $bylnk = "<a href=\"index.php?action=viewuser&amp;who=$pminfo[3]&amp;sid=$sid\">$iml".getnick_uid($pminfo[3])."</a>";
    
  }
  
  echo "$ptxt $bylnk<br/>";
  $tmstamp = $pminfo[2];
  $tmdt = date("d m Y - H:i:s", $tmstamp);
  echo "$tmdt<br/><br/>";
  $pmtext = parsepm($pminfo[0], $sid);
    $pmtext = str_replace("/llfaqs","<a href=\"lists.php?action=faqs&amp;sid=$sid\">FunMobile.WS F.A.Qs</a>", $pmtext);
    $pmtext = str_replace("/reader",getnick_uid($pminfo[3]), $pmtext);
    if(isspam($pmtext))
    {
      if(($pminfo[4]=="0") && ($pminfo[1]!=1))
      {
        mysql_query("UPDATE fun_private SET reported='1' WHERE id='".$pmid."'");
      }
    }
    echo $pmtext;
  echo "</p>";
  echo "<p align=\"center\">";
  echo "<form action=\"inbxproc.php?action=proc&amp;sid=$sid\" method=\"post\">";
  echo "Action: <select name=\"pmact\">";
  echo "<option value=\"rep-$pmid\">Reply</option>";
  echo "<option value=\"del-$pmid\">Delete</option>";
  if(isstarred($pmid))
  {
    echo "<option value=\"ust-$pmid\">Unstar</option>";
  }else{
  echo "<option value=\"str-$pmid\">Star</option>";
  }
  echo "<option value=\"rpt-$pmid\">Report</option>";
   
  echo "</select>";
echo "<input type=\"submit\" value=\"GO\"/>";
echo "</form>";
  
  echo "<br/><br/><a href=\"inbox.php?action=dialog&amp;sid=$sid&amp;who=$pminfo[1]\">Dialog</a>";
 
  }else{
    echo "<img src=\"images/notok.gif\" alt=\"X\"/>This PM ain't yours";
  }
  echo "<br/><br/><a href=\"inbox.php?action=main&amp;sid=$sid\">Back to inbox</a><br/>";
  echo "<a href=\"index.php?action=main&amp;sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
    echo "</card>";

}else if($action=="dialog")
{
    addonline(getuid_sid($sid),"Viewing PM Dialog","");
    echo "<card id=\"main\" title=\"PM Dialog\">";
  $uid = getuid_sid($sid);
  if($page=="" || $page<=0)$page=1;
    $myid = getuid_sid($sid);
    $pms = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_private WHERE (byuid=$uid AND touid=$who) OR (byuid=$who AND touid=$uid) ORDER BY timesent"));
    echo mysql_error();
    $num_items = $pms[0]; //changable
    $items_per_page= 7;
    $num_pages = ceil($num_items/$items_per_page);
    if($page>$num_pages)$page= $num_pages;
    $limit_start = ($page-1)*$items_per_page;
    if($num_items>0)
    {
      echo "<p>";
      $pms = mysql_query("SELECT byuid, text, timesent FROM fun_private WHERE (byuid=$uid AND touid=$who) OR (byuid=$who AND touid=$uid) ORDER BY timesent LIMIT $limit_start, $items_per_page");
      while($pm=mysql_fetch_array($pms))
      {
            if(isonline($pm[0]))
  {
    $iml = "<img src=\"images/onl.gif\" alt=\"+\"/>";
  }else{
    $iml = "<img src=\"images/ofl.gif\" alt=\"-\"/>";
  }
  $bylnk = "<a href=\"index.php?action=viewuser&amp;who=$pm[0]&amp;sid=$sid\">$iml".getnick_uid($pm[0])."</a>";
  echo $bylnk;
  $tmopm = date("d m y - h:i:s",$pm[2]);
  echo " <small>$tmopm<br/>";
  
        echo parsepm($pm[1], $sid);

  
  echo "</small>";
  echo "<br/>--------------<br/>";
      }
      echo "</p><p align=\"center\">";
      if($page>1)
    {
      $ppage = $page-1;
      echo "<a href=\"inbox.php?action=dialog&amp;page=$ppage&amp;sid=$sid&amp;who=$who\">&#171;PREV</a> ";
    }
    if($page<$num_pages)
    {
      $npage = $page+1;
      echo "<a href=\"inbox.php?action=dialog&amp;page=$npage&amp;sid=$sid&amp;who=$who\">Next&#187;</a>";
    }
    echo "<br/>$page/$num_pages<br/>";
    if($num_pages>2)
    {
$rets = "<form action=\"inbox.php\" method=\"get\">";
      $rets .= "Jump to page: <input name=\"page\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"GO\"/>";
        $rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
        $rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
        $rets .= "<input type=\"hidden\" name=\"who\" value=\"$who\"/>";
$rets .= "</form>";
        echo $rets;
      }
      }else{
        echo "<p align=\"center\">";
        echo "NO DATA";
      }
      echo "<br/><br/><a href=\"rwdpm.php?action=dlg&amp;sid=$sid&amp;who=$who\">Download</a><br/><small>only first 50 messages</small><br/>";
       echo "<a href=\"inbox.php?action=main&amp;sid=$sid\">Back to inbox</a><br/>";
  echo "<a href=\"index.php?action=main&amp;sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
}
    else{
      addonline(getuid_sid($sid),"Lost in inbox lol","");
    
  echo "<p align=\"center\">";
  echo "I don't know how did you get into here, but there's nothing to show<br/><br/>";
  echo "<a href=\"index.php?action=main&amp;sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
}

	echo "</body>";
	echo "</html>";
?>
