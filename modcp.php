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
      echo "You are not a admin<br/>";
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

if(isset($_GET['main']))
{
    addonline(getuid_sid($sid),"Admin CP","");
    echo "<p align=\"center\">";
    echo "<b>Reports</b>";
    echo "</p>";
     echo "<p>";
    $nrpm = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_private WHERE reported='1'"));
    echo "<a href=\"modcp.php?rpm\">&#187;Pr. Messages($nrpm[0])</a><br/>";
    $nrps = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_posts WHERE reported='1'"));
    echo "<a href=\"modcp.php?rps\">&#187;Posts($nrps[0])</a><br/>";
    $nrtp = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_topics WHERE reported='1'"));
    echo "<a href=\"modcp.php?rtp\">&#187;Topics($nrtp[0])</a>";
    echo "</p>";
     echo "<p align=\"center\">";
    echo "<b>Logs</b>";
    echo "</p>";
    
     echo "<p>";
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_mlog"));
    if($noi[0]>0){
    $nola = mysql_query("SELECT DISTINCT (action)  FROM fun_mlog ORDER BY actdt DESC");

      while($act=mysql_fetch_array($nola))
      {
        $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_mlog WHERE action='".$act[0]."'"));
        echo "<a href=\"modcp.php?log&amp;view=$act[0]\">$act[0]($noi[0])</a><br/>";
      }

    }
    echo "</p>";
  echo "<p align=\"center\">";
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
}

/////////////////////////////////Reported PMs

else if(isset($_GET['rpm']))
{
  $page = $_GET["page"];
    
    echo "<p align=\"center\">";
    echo "<b>Reported PMs</b>";
    echo "</p>";
    echo "<p>";
    echo "<small>";
    if($page=="" || $page<=0)$page=1;
    $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_private WHERE reported ='1'"));
    $num_items = $noi[0]; //changable
    $items_per_page= 5;
    $num_pages = ceil($num_items/$items_per_page);
    if($page>$num_pages)$page= $num_pages;
    $limit_start = ($page-1)*$items_per_page;
    $sql = "SELECT id, text, byuid, touid, timesent FROM fun_private WHERE reported='1' ORDER BY timesent DESC LIMIT $limit_start, $items_per_page";
    $items = mysql_query($sql);
    while ($item=mysql_fetch_array($items))
    {
      $fromnk = getnick_uid($item[2]);
      $tonick = getnick_uid($item[3]);
      $dtop = date("d m y - H:i:s", $item[4]);
      $text = parsepm($item[1]);
      $flk = "<a href=\"index.php?viewuser&amp;who=$item[2]\">$fromnk</a>";
      $tlk = "<a href=\"index.php?viewuser&amp;who=$item[3]\">$tonick</a>";
      echo "From: $flk To: $tlk<br/>Time: $dtop<br/>";
       echo $text;
       echo "<br/>";
       echo "<a href=\"modproc.php?hpm&amp;pid=$item[0]\">Handle</a><br/><br/>";
    }
    echo "</small>";
    echo "</p>";
    echo "<p align=\"center\">";
    if($page>1)
    {
      $ppage = $page-1;
      echo "<a href=\"modcp.php?$action&amp;page=$ppage\">&#171;PREV</a> ";
    }
    if($page<$num_pages)
    {
      $npage = $page+1;
      echo "<a href=\"modcp.php?$action&amp;page=$npage\">Next&#187;</a>";
    }
    echo "<br/>$page/$num_pages<br/>";
    if($num_pages>2)
    {
$rets = "<form action=\"modcp.php\" method=\"get\">";
      $rets .= "Jump to page<input name=\"page\" format=\"*N\" size=\"3\"/>";
        $rets .= "<input type=\"submit\" value=\"GO\"/>";
        
        $rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
        $rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
        
        
        $rets .= "</form>";

        echo $rets;
    }
    echo "<br/><br/>";
    echo "<a href=\"modcp.php?main\">";
echo "SE R/L</a><br/>";
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
}

/////////////////////////////////Reported Posts

else if(isset($_GET['rps']))
{
  $page = $_GET["page"];
    
    echo "<p align=\"center\">";
    echo "<b>Reported Posts</b>";
    echo "</p>";
    echo "<p>";
    echo "<small>";
    if($page=="" || $page<=0)$page=1;
    $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_posts WHERE reported ='1'"));
    $num_items = $noi[0]; //changable
    $items_per_page= 5;
    $num_pages = ceil($num_items/$items_per_page);
    if($page>$num_pages)$page= $num_pages;
    $limit_start = ($page-1)*$items_per_page;
    $sql = "SELECT id, text, tid, uid, dtpost FROM fun_posts WHERE reported='1' ORDER BY dtpost DESC LIMIT $limit_start, $items_per_page";
    $items = mysql_query($sql);
    while ($item=mysql_fetch_array($items))
    {
      $poster = getnick_uid($item[3]);
      $tname = htmlspecialchars(gettname($item[3]));
      $dtop = date("d m y - H:i:s", $item[4]);
      $text = parsemsg($item[1]);
      $flk = "<a href=\"index.php?viewuser&amp;who=$item[3]\">$poster</a>";
      $tlk = "<a href=\"index.php?viewtpc&amp;tid=$item[2]\">$tname</a>";
      echo "Poster: $flk<br/>In: $tlk<br/>Time: $dtop<br/>";
       echo $text;
       echo "<br/>";
       echo "<a href=\"modproc.php?hps&amp;pid=$item[0]\">Handle</a><br/><br/>";
    }
    echo "</small>";
    echo "</p>";
    echo "<p align=\"center\">";
    if($page>1)
    {
      $ppage = $page-1;
      echo "<a href=\"modcp.php?$action&amp;page=$ppage\">&#171;PREV</a> ";
    }
    if($page<$num_pages)
    {
      $npage = $page+1;
      echo "<a href=\"modcp.php?$action&amp;page=$npage\">Next&#187;</a>";
    }
    echo "<br/>$page/$num_pages<br/>";
    if($num_pages>2)
    {
      $rets = "<form action=\"modcp.php\" method=\"get\">";
      $rets .= "Jump to page<input name=\"page\" format=\"*N\" size=\"3\"/>";
        $rets .= "<input type=\"submit\" value=\"GO\"/>";
        
        $rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
        $rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
        
        
        $rets .= "</form>";

        echo $rets;
    }
    echo "<br/><br/>";
    echo "<a href=\"modcp.php?main\">";
echo "SE R/L</a><br/>";
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
}

/////////////////////////////////Reported Posts

else if(isset($_GET['log']))
{
  $page = $_GET["page"];
  $view = $_GET["view"];
    
    echo "<p align=\"center\">";
    echo "<b>$view</b>";
    echo "</p>";
    echo "<p>";
    echo "<small>";
    if($page=="" || $page<=0)$page=1;
    $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_mlog WHERE  action='".$view."'"));
    $num_items = $noi[0]; //changable
    $items_per_page= 5;
    $num_pages = ceil($num_items/$items_per_page);
    if($page>$num_pages)$page= $num_pages;
    $limit_start = ($page-1)*$items_per_page;
    $sql = "SELECT  actdt, details FROM fun_mlog WHERE action='".$view."' ORDER BY actdt DESC LIMIT $limit_start, $items_per_page";
    $items = mysql_query($sql);
    while ($item=mysql_fetch_array($items))
    {
      echo "Time: ".date("d m y-H:i:s", $item[0])."<br/>";
      echo $item[1];
      echo "<br/>";
       
    }
    echo "</small>";
    echo "</p>";
    echo "<p align=\"center\">";
    if($page>1)
    {
      $ppage = $page-1;
      echo "<a href=\"modcp.php?$action&amp;page=$ppage&amp;view=$view\">&#171;PREV</a> ";
    }
    if($page<$num_pages)
    {
      $npage = $page+1;
      echo "<a href=\"modcp.php?$action&amp;page=$npage&amp;view=$view\">Next&#187;</a>";
    }
    echo "<br/>$page/$num_pages<br/>";
    if($num_pages>2)
    {
      $rets = "<form action=\"modcp.php\" method=\"get\">";
      $rets .= "Jump to page<input name=\"page\" format=\"*N\" size=\"3\"/>";
        $rets .= "<input type=\"submit\" value=\"GO\"/>";
        
        $rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
        $rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
        $rets .= "<input type=\"hidden\" name=\"view\" value=\"$view\"/>";
        
        $rets .= "</form>";
        

        echo $rets;
    }
    echo "<br/><br/>";
    echo "<a href=\"modcp.php?main\">";
echo "SE R/L</a><br/>";
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
}

/////////////////////////////////Reported Topics

else if(isset($_GET['rtp']))
{
  $page = $_GET["page"];
    
    echo "<p align=\"center\">";
    echo "<b>Reported Topics</b>";
    echo "</p>";
    echo "<p>";
    echo "<small>";
    if($page=="" || $page<=0)$page=1;
    $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_topics WHERE reported ='1'"));
    $num_items = $noi[0]; //changable
    $items_per_page= 5;
    $num_pages = ceil($num_items/$items_per_page);
    if($page>$num_pages)$page= $num_pages;
    $limit_start = ($page-1)*$items_per_page;
    $sql = "SELECT id, name, text, authorid, crdate FROM fun_topics WHERE reported='1' ORDER BY crdate DESC LIMIT $limit_start, $items_per_page";
    $items = mysql_query($sql);
    while ($item=mysql_fetch_array($items))
    {
      $poster = getnick_uid($item[3]);
      $tname = htmlspecialchars($item[1]);
      $dtop = date("d m y - H:i:s", $item[4]);
      $text = parsemsg($item[2]);
      $flk = "<a href=\"index.php?viewuser&amp;who=$item[3]\">$poster</a>";
      $tlk = "<a href=\"index.php?viewtpc&amp;tid=$item[0]\">$tname</a>";
      echo "Poster: $flk<br/>In: $tlk<br/>Time: $dtop<br/>";
       echo $text;
       echo "<br/>";
       echo "<a href=\"modproc.php?htp&amp;tid=$item[0]\">Handle</a><br/><br/>";
    }
    echo "</small>";
    echo "</p>";
    echo "<p align=\"center\">";
    if($page>1)
    {
      $ppage = $page-1;
      echo "<a href=\"modcp.php?$action&amp;page=$ppage\">&#171;PREV</a> ";
    }
    if($page<$num_pages)
    {
      $npage = $page+1;
      echo "<a href=\"modcp.php?$action&amp;page=$npage\">Next&#187;</a>";
    }
    echo "<br/>$page/$num_pages<br/>";
    if($num_pages>2)
    {
$rets = "<form action=\"modcp.php\" method=\"get\">";
      $rets .= "Jump to page<input name=\"page\" format=\"*N\" size=\"3\"/>";
        $rets .= "<input type=\"submit\" value=\"GO\"/>";
        
        $rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
        $rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
        
        $rets .= "</form>";


        echo $rets;
    }
    echo "<br/><br/>";
    echo "<a href=\"modcp.php?main\">";
echo "SE R/L</a><br/>";
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
}

///////////////////////////////////////////////Mod a user

else if(isset($_GET['user']))
{
    $who = $_GET["who"];
    
    echo "<p align=\"center\">";
    $unick = getnick_uid($who);
    echo "<b>Moderating $unick</b>";
    echo "</p>";
    echo "<p>";
    echo "<a href=\"modcp.php?penopt&amp;who=$who\">&#187;Penalties</a><br/>";
    echo "<a href=\"modcp.php?plsopt&amp;who=$who\">&#187;Plusses</a><br/><br/>";
    if(istrashed($who))
    {
      echo "<a href=\"modproc.php?untr&amp;who=$who\">&#187;Untrash</a><br/>";
    }
    if(isbanned($who))
    {
      echo "<a href=\"modproc.php?unbn&amp;who=$who\">&#187;Unban</a><br/>";
    }
    if(!isshield($who))
    {
      echo "<a href=\"modproc.php?shld&amp;who=$who\">&#187;Shield</a><br/>";
    }else{
        echo "<a href=\"modproc.php?ushld&amp;who=$who\">&#187;Unshield</a><br/>";
    }
    echo "</p>";
    echo "<p align=\"center\">";
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
}

//////////////////////////////////////Penalties Options

else if(isset($_GET['penopt']))
{
    $who = $_GET["who"];
    
    echo "<p align=\"center\">";
    $unick = getnick_uid($who);
    echo "What do you want to do with $unick";
    echo "</p>";
    echo "<p>";
    $pen[0]="Trash";
    $pen[1]="Ban";
    $pen[2]="Ban Ip";
echo "<form action=\"modproc.php?pun\" method=\"post\">";
    echo "Penalty: <select name=\"pid\">";
    for($i=0;$i<count($pen);$i++)
    {
      echo "<option value=\"$i\">$pen[$i]</option>";
    }
    echo "</select><br/>";
    echo "Reason: <input name=\"pres\" maxlength=\"100\"/><br/>";
    echo "Days: <input name=\"pds\" format=\"*N\" maxlength=\"4\"/><br/>";
    echo "Hours: <input name=\"phr\" format=\"*N\" maxlength=\"4\"/><br/>";
    echo "Minutes: <input name=\"pmn\" format=\"*N\" maxlength=\"2\"/><br/>";
    echo "Seconds: <input name=\"psc\" format=\"*N\" maxlength=\"2\"/><br/>";
    echo "<input type=\"submit\" value=\"GO\"/>";
    
    echo "<input type=\"hidden\" name=\"who\" value=\"$who\"/>";
    
    echo "</form>";
    echo "</p>";
    
     echo "<p align=\"center\">";
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
}

//////////////////////////////////////Penalties Options

else if(isset($_GET['plsopt']))
{
    $who = $_GET["who"];
    
    echo "<p align=\"center\">";
    $unick = getnick_uid($who);
    echo "Add/Substract $unick's Plusses";
    echo "</p>";
    echo "<p>";
    $pen[0]="Substract";
    $pen[1]="Add";
    
echo "<form action=\"modproc.php?pls\" method=\"post\">";
    echo "Action: <select name=\"pid\">";
    for($i=0;$i<count($pen);$i++)
    {
      echo "<option value=\"$i\">$pen[$i]</option>";
    }
    echo "</select><br/>";
    echo "Reason: <input name=\"pres\" maxlength=\"100\"/><br/>";
    echo "Plusses: <input name=\"pval\" format=\"*N\" maxlength=\"3\"/><br/>";
echo "<input type=\"submit\" value=\"GO\"/>";
    
    echo "<input type=\"hidden\" name=\"who\" value=\"$who\"/>";
    
      echo "</form>";
    echo "</p>";

     echo "<p align=\"center\">";
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
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
