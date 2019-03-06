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
$page = $_GET["page"];
	vrh($sid);

if($action != "")
{
    if(islogged($sid)==false)
    {
      
      echo "<p align=\"center\">";
      echo "You are not logged in<br/>";
      echo "Or Your session has been expired<br/><br/>";
      echo "<a href=\"index.php\">Login</a>";
      echo "</p>";
      
      exit();
    }
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

//////////////////////////////////Members List

if(isset($_GET['members']))
{
    addonline(getuid_sid($sid),"Viewing Members List","");
    $view = $_GET["view"];
    if($view=="")$view="date";
    
    echo "<p align=\"center\">";
    echo "<img src=\"images/bdy.gif\" alt=\"*\"/><br/>";
    echo "<a href=\"lists.php?members&amp;view=name\">Order By Name</a><br/>";
    echo "<a href=\"lists.php?members&amp;view=date\">Order By Join Date</a><br/>";
    echo "</p>";
    //////ALL LISTS SCRIPT <<

    if($page=="" || $page<=0)$page=1;
    $num_items = regmemcount(); //changable
    $items_per_page= 10;
    $num_pages = ceil($num_items/$items_per_page);
    if(($page>$num_pages)&&$page!=1)$page= $num_pages;
    $limit_start = ($page-1)*$items_per_page;

    //changable sql
    if($view=="name")
    {
        $sql = "SELECT id, name, regdate FROM fun_users ORDER BY name LIMIT $limit_start, $items_per_page";
    }else{
        $sql = "SELECT id, name, regdate FROM fun_users ORDER BY regdate DESC LIMIT $limit_start, $items_per_page";
    }

    echo "<p>";
    $items = mysql_query($sql);
    
    if(mysql_num_rows($items)>0)
    {
    while ($item = mysql_fetch_array($items))
    {
      $jdt = date("d-m-y", $item[2]);
      $lnk = "<a href=\"index.php?viewuser&amp;who=$item[0]\">$item[1]</a> <small>joined: $jdt</small>";
      echo "$lnk<br/>";
    }
    }
    echo "</p>";
    echo "<p align=\"center\">";
    if($page>1)
    {
      $ppage = $page-1;
      echo "<a href=\"lists.php?members&amp;page=$ppage&amp;view=$view\">&#171;PREV</a> ";
    }
    if($page<$num_pages)
    {
      $npage = $page+1;
      echo "<a href=\"lists.php?members&amp;page=$npage&amp;view=$view\">Next&#187;</a>";
    }
    echo "<br/>$page/$num_pages<br/>";
    if($num_pages>2)
    {

        $rets = "<form action=\"lists.php\" method=\"get\">";
      $rets .= "Jump to page<input name=\"page\" format=\"*N\" size=\"3\"/>";
        $rets .= "<input type=\"submit\" value=\"GO\"/>";
        $rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
        $rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
        $rets .= "<input type=\"hidden\" name=\"view\" value=\"$view\"/>";
        $rets .= "</form>";

        echo $rets;
    }
    echo "</p>";
  ////// UNTILL HERE >>
    echo "<p align=\"center\">";
    echo "<a href=\"index.php?stats\"><img src=\"images/stat.gif\" alt=\"*\"/>";
echo "Site Stats</a><br/>";
    echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
    
}

//////////////////////////////////List users by IP

if(isset($_GET['byip']))
{
    addonline(getuid_sid($sid),"Mod CP","");
    
    //////ALL LISTS SCRIPT <<
    $who = $_GET["who"];
    $whoinfo = mysql_fetch_array(mysql_query("SELECT ipadd, browserm FROM fun_users WHERE id='".$who."'"));
    if(ismod(getuid_sid($sid))){
    if($page=="" || $page<=0)$page=1;
    $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_users WHERE ipadd='".$whoinfo[0]."' AND browserm='".$whoinfo[1]."'"));
    $num_items = $noi[0]; //changable
    $items_per_page= 10;
    $num_pages = ceil($num_items/$items_per_page);
    if(($page>$num_pages)&&$page!=1)$page= $num_pages;
    $limit_start = ($page-1)*$items_per_page;

    //changable sql
    
        $sql = "SELECT id, name FROM fun_users WHERE ipadd='".$whoinfo[0]."' AND browserm='".$whoinfo[1]."' ORDER BY name  LIMIT $limit_start, $items_per_page";
    

    echo "<p>";
    $items = mysql_query($sql);

    if(mysql_num_rows($items)>0)
    {
    while ($item = mysql_fetch_array($items))
    {
      
      $lnk = "<a href=\"index.php?viewuser&amp;who=$item[0]\">$item[1]</a>";
      echo "$lnk<br/>";
    }
    }
    echo "</p>";
    echo "<p align=\"center\">";
    if($page>1)
    {
      $ppage = $page-1;
      echo "<a href=\"lists.php?$action&amp;page=$ppage&amp;who=$who\">&#171;PREV</a> ";
    }
    if($page<$num_pages)
    {
      $npage = $page+1;
      echo "<a href=\"lists.php?$action&amp;page=$npage&amp;who=$who\">Next&#187;</a>";
    }
    echo "<br/>$page/$num_pages<br/>";
    if($num_pages>2)
    {

        $rets .= "<form action=\"lists.php\" method=\"get\">";
      $rets .= "Jump to page<input name=\"page\" format=\"*N\" size=\"3\"/>";
        $rets .= "<input type=\"submit\" value=\"GO\"/>";
        $rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
        $rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
        
        $rets .= "<input type=\"hidden\" name=\"who\" value=\"$who\"/>";
        $rets .= "</form>";

        echo $rets;
    }
    echo "</p>";
    }else{
      echo "<p align=\"center\">";
      echo "You can't view this list";
      echo "</p>";
    }
  ////// UNTILL HERE >>
    echo "<p align=\"center\">";
    echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
    
}


//////////////////////////////////Top Posters List

else if(isset($_GET['topp']))
{
    addonline(getuid_sid($sid),"Top Forum Posters","");
    
    echo "<p align=\"center\">";
    echo "<b>Our Top Posters</b><br/><small>Thank you all for keeping this site alive<br/>";
    $weekago = time();
    $weekago -= 7*24*60*60;
    $noi = mysql_fetch_array(mysql_query("SELECT COUNT(DISTINCT uid) FROM fun_posts WHERE dtpost>'".$weekago."';"));
    echo "<a href=\"lists.php?tpweek\">This week($noi[0])</a><br/>";
    $noi = mysql_fetch_array(mysql_query("SELECT COUNT(DISTINCT uid)  FROM fun_posts ;"));
    echo "<a href=\"lists.php?tptime\">All the time($noi[0])</a></small><br/>";
    echo "</p>";
    //////ALL LISTS SCRIPT <<

    if($page=="" || $page<=0)$page=1;
    $num_items = regmemcount(); //changable
    $items_per_page= 10;
    $num_pages = ceil($num_items/$items_per_page);
    if(($page>$num_pages)&&$page!=1)$page= $num_pages;
    $limit_start = ($page-1)*$items_per_page;

    //changable sql

        $sql = "SELECT id, name, posts FROM fun_users ORDER BY posts DESC LIMIT $limit_start, $items_per_page";


    echo "<p>";
    $items = mysql_query($sql);
    echo mysql_error();
    if(mysql_num_rows($items)>0)
    {
    while ($item = mysql_fetch_array($items))
    {

      $lnk = "<a href=\"index.php?viewuser&amp;who=$item[0]\">$item[1]</a> <small>Posts: $item[2]</small>";
      echo "$lnk<br/>";
    }
    }
    echo "</p>";
    echo "<p align=\"center\">";
    if($page>1)
    {
      $ppage = $page-1;
      echo "<a href=\"lists.php?topp&amp;page=$ppage&amp;view=$view\">&#171;PREV</a> ";
    }
    if($page<$num_pages)
    {
      $npage = $page+1;
      echo "<a href=\"lists.php?topp&amp;page=$npage&amp;view=$view\">Next&#187;</a>";
    }
    echo "<br/>$page/$num_pages<br/>";
    if($num_pages>2)
    {

        $rets = "<form action=\"lists.php\" method=\"get\">";
      $rets .= "Jump to page: <input name=\"page\" format=\"*N\" size=\"3\"/>";
        $rets .= "<input type=\"submit\" value=\"GO\"/>";
        $rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
        $rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
        $rets .= "</form>";

        echo $rets;
    }
    echo "</p>";
  ////// UNTILL HERE >>
    echo "<p align=\"center\">";
    echo "<a href=\"index.php?stats\"><img src=\"images/stat.gif\" alt=\"*\"/>";
echo "Site Stats</a><br/>";
    echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
    
}

//////////////////////////////////Most online daily list

else if(isset($_GET['moto']))
{
    addonline(getuid_sid($sid),"Most Online Daily Users","");
    
    echo "<p align=\"center\">";
    echo "<small>Maximum number of users was online in the last 10 Days<br/>";
    
    
    echo "</small>";
    echo "</p>";
    //////ALL LISTS SCRIPT <<

    
    
    
    
    

    //changable sql

        $sql = "SELECT ddt, dtm, ppl FROM fun_mpot ORDER BY id DESC LIMIT 10";


    echo "<p>";
    $items = mysql_query($sql);
    
    if(mysql_num_rows($items)>0)
    {
    while ($item = mysql_fetch_array($items))
    {

      $lnk = "<small>$item[0]($item[1]) Members: $item[2]</small>";
      echo "$lnk<br/>";
    }
    }
    echo "</p>";
    
    
  ////// UNTILL HERE >>
    echo "<p align=\"center\">";
    echo "<a href=\"index.php?stats\"><img src=\"images/stat.gif\" alt=\"*\"/>";
echo "Site Stats</a><br/>";
    echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
    
}


//////////////////////////////////Top Chatters

else if(isset($_GET['tchat']))
{
    addonline(getuid_sid($sid),"Top Chatters","");
    
    echo "<p align=\"center\">";
    echo "<b>Top Chatters</b>";
    echo "</p>";
    //////ALL LISTS SCRIPT <<

    if($page=="" || $page<=0)$page=1;
    $num_items = regmemcount(); //changable
    $items_per_page= 10;
    $num_pages = ceil($num_items/$items_per_page);
    if(($page>$num_pages)&&$page!=1)$page= $num_pages;
    $limit_start = ($page-1)*$items_per_page;

    //changable sql

        $sql = "SELECT id, name, chmsgs FROM fun_users ORDER BY chmsgs DESC LIMIT $limit_start, $items_per_page";


    echo "<p>";
    $items = mysql_query($sql);
    echo mysql_error();
    if(mysql_num_rows($items)>0)
    {
    while ($item = mysql_fetch_array($items))
    {

      $lnk = "<a href=\"index.php?viewuser&amp;who=$item[0]\">$item[1]</a> <small>Chat Posts: $item[2]</small>";
      echo "$lnk<br/>";
    }
    }
    echo "</p>";
    echo "<p align=\"center\">";
    if($page>1)
    {
      $ppage = $page-1;
      echo "<a href=\"lists.php?tchat&amp;page=$ppage&amp;view=$view\">&#171;PREV</a> ";
    }
    if($page<$num_pages)
    {
      $npage = $page+1;
      echo "<a href=\"lists.php?tchat&amp;page=$npage&amp;view=$view\">Next&#187;</a>";
    }
    echo "<br/>$page/$num_pages<br/>";
    if($num_pages>2)
    {

        $rets = "<form action=\"lists.php\" method=\"get\">";
      $rets .= "Jump to page<input name=\"page\" format=\"*N\" size=\"3\"/>";
        $rets .= "<input type=\"submit\" value=\"GO\"/>";
        $rets .= "<input name=\"hidden\" name=\"action\" value=\"$action\"/>";
        $rets .= "<input name=\"hidden\" name=\"sid\" value=\"$sid\"/>";
        $rets .= "</form>";

        echo $rets;
    }
    echo "</p>";
  ////// UNTILL HERE >>
    echo "<p align=\"center\">";
    echo "<a href=\"index.php?stats\"><img src=\"images/stat.gif\" alt=\"*\"/>";
echo "Site Stats</a><br/>";
    echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
    
}

//////////////////////////////////Top Chatters

else if(isset($_GET['smc']))
{
	$who = $_GET["who"];
	$wnick = getnick_uid($who);
    addonline(getuid_sid($sid),"Smoochers List","");
    
	echo "<p align=\"center\">";
	echo "<small>Members smooched by <a href=\"index.php?viewuser&amp;who=$who\">$wnick</a>";
	echo "</small></p>";
    //////ALL LISTS SCRIPT <<
	
    if($page=="" || $page<=0)$page=1;
	$noi = mysql_fetch_array(mysql_query("SELECT COUNT(id) FROM fun_usfun WHERE uid='".$who."' AND action='smooch'")); //changable
    $num_items = $noi[0];
    $items_per_page= 10;
    $num_pages = ceil($num_items/$items_per_page);
    if(($page>$num_pages)&&$page!=1)$page= $num_pages;
    $limit_start = ($page-1)*$items_per_page;

    //changable sql

        $sql = "
		SELECT a.target, b.name
		FROM fun_usfun a INNER JOIN fun_users b ON a.target = b.id
		WHERE a.uid='".$who."' AND a.action='smooch'
		ORDER BY a.actime DESC LIMIT $limit_start, $items_per_page
		;";


    echo "<p><small>";
    $items = mysql_query($sql);
    echo mysql_error();
    if(mysql_num_rows($items)>0)
    {
    while ($item = mysql_fetch_array($items))
    {

      $lnk = "<a href=\"index.php?viewuser&amp;who=$item[0]\">$item[1]</a>";
      echo "$lnk<br/>";
    }
    }
    echo "</small></p>";
    echo "<p align=\"center\">";
    if($page>1)
    {
      $ppage = $page-1;
      echo "<a href=\"lists.php?$action&amp;page=$ppage&amp;who=$who\">&#171;PREV</a> ";
    }
    if($page<$num_pages)
    {
      $npage = $page+1;
      echo "<a href=\"lists.php?$action&amp;page=$npage&amp;who=$who\">Next&#187;</a>";
    }
	if($num_pages>1)
	{
    echo "<br/>$page/$num_pages<br/>";
	}
    if($num_pages>2)
    {

        $rets = "<form action=\"lists.php\" method=\"get\">";
      $rets .= "Jump to page<input name=\"page\" format=\"*N\" size=\"3\"/>";
        $rets .= "<input type=\"submit\" value=\"GO\"/>";
        $rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
        $rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
        $rets .= "<input type=\"hidden\" name=\"who\" value=\"$who\"/>";
        $rets .= "</form>";

        echo $rets;
    }
    echo "</p>";
  ////// UNTILL HERE >>
    echo "<p align=\"center\">";
    echo "<a href=\"index.php?stats\"><img src=\"images/stat.gif\" alt=\"*\"/>";
echo "Site Stats</a><br/>";
    echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
    
}

else if(isset($_GET['smd']))
{
	$who = $_GET["who"];
	$wnick = getnick_uid($who);
    addonline(getuid_sid($sid),"Smoochers List","");
    
	echo "<p align=\"center\">";
	echo "<small>Members smooched <a href=\"index.php?viewuser&amp;who=$who\">$wnick</a>";
	echo "</small></p>";
    //////ALL LISTS SCRIPT <<
	
    if($page=="" || $page<=0)$page=1;
	$noi = mysql_fetch_array(mysql_query("SELECT COUNT(id) FROM fun_usfun WHERE target='".$who."' AND action='smooch'")); //changable
    $num_items = $noi[0];
    $items_per_page= 10;
    $num_pages = ceil($num_items/$items_per_page);
    if(($page>$num_pages)&&$page!=1)$page= $num_pages;
    $limit_start = ($page-1)*$items_per_page;

    //changable sql

        $sql = "
		SELECT a.uid, b.name
		FROM fun_usfun a INNER JOIN fun_users b ON a.uid = b.id
		WHERE a.target='".$who."' AND a.action='smooch'
		ORDER BY a.actime DESC LIMIT $limit_start, $items_per_page
		;";


    echo "<p><small>";
    $items = mysql_query($sql);
    echo mysql_error();
    if(mysql_num_rows($items)>0)
    {
    while ($item = mysql_fetch_array($items))
    {

      $lnk = "<a href=\"index.php?viewuser&amp;who=$item[0]\">$item[1]</a>";
      echo "$lnk<br/>";
    }
    }
    echo "</small></p>";
    echo "<p align=\"center\">";
    if($page>1)
    {
      $ppage = $page-1;
      echo "<a href=\"lists.php?$action&amp;page=$ppage&amp;who=$who\">&#171;PREV</a> ";
    }
    if($page<$num_pages)
    {
      $npage = $page+1;
      echo "<a href=\"lists.php?$action&amp;page=$npage&amp;who=$who\">Next&#187;</a>";
    }
	if($num_pages>1)
	{
    echo "<br/>$page/$num_pages<br/>";
	}
    if($num_pages>2)
    {

        $rets = "<form action=\"lists.php\" method=\"get\">";
      $rets .= "Jump to page<input name=\"page\" format=\"*N\" size=\"3\"/>";
        $rets .= "<input type=\"submit\" value=\"GO\"/>";
        $rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
        $rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
        $rets .= "<input type=\"hidden\" name=\"who\" value=\"$who\"/>";
        $rets .= "</form>";

        echo $rets;
    }
    echo "</p>";
  ////// UNTILL HERE >>
    echo "<p align=\"center\">";
    echo "<a href=\"index.php?stats\"><img src=\"images/stat.gif\" alt=\"*\"/>";
echo "Site Stats</a><br/>";
    echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
    
}

//////////////////////////////////Top Chatters

else if(isset($_GET['kck']))
{
	$who = $_GET["who"];
	$wnick = getnick_uid($who);
    addonline(getuid_sid($sid),"Kickers List","");
    
	echo "<p align=\"center\">";
	echo "<small>Members Kicked by <a href=\"index.php?viewuser&amp;who=$who\">$wnick</a>";
	echo "</small></p>";
    //////ALL LISTS SCRIPT <<
	
    if($page=="" || $page<=0)$page=1;
	$noi = mysql_fetch_array(mysql_query("SELECT COUNT(id) FROM fun_usfun WHERE uid='".$who."' AND action='kick'")); //changable
    $num_items = $noi[0];
    $items_per_page= 10;
    $num_pages = ceil($num_items/$items_per_page);
    if(($page>$num_pages)&&$page!=1)$page= $num_pages;
    $limit_start = ($page-1)*$items_per_page;

    //changable sql

        $sql = "
		SELECT a.target, b.name
		FROM fun_usfun a INNER JOIN fun_users b ON a.target = b.id
		WHERE a.uid='".$who."' AND a.action='kick'
		ORDER BY a.actime DESC LIMIT $limit_start, $items_per_page
		;";


    echo "<p><small>";
    $items = mysql_query($sql);
    echo mysql_error();
    if(mysql_num_rows($items)>0)
    {
    while ($item = mysql_fetch_array($items))
    {

      $lnk = "<a href=\"index.php?viewuser&amp;who=$item[0]\">$item[1]</a>";
      echo "$lnk<br/>";
    }
    }
    echo "</small></p>";
    echo "<p align=\"center\">";
    if($page>1)
    {
      $ppage = $page-1;
      echo "<a href=\"lists.php?$action&amp;page=$ppage&amp;who=$who\">&#171;PREV</a> ";
    }
    if($page<$num_pages)
    {
      $npage = $page+1;
      echo "<a href=\"lists.php?$action&amp;page=$npage&amp;who=$who\">Next&#187;</a>";
    }
	if($num_pages>1)
	{
    echo "<br/>$page/$num_pages<br/>";
	}
    if($num_pages>2)
    {
  $rets = "<form action=\"lists.php\" method=\"get\">";
      $rets .= "Jump to page<input name=\"page\" format=\"*N\" size=\"3\"/>";
        $rets .= "<input type=\"submit\" value=\"GO\"/>";
        $rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
        $rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
        $rets .= "<input type=\"hidden\" name=\"who\" value=\"$who\"/>";
        $rets .= "</form>";

        echo $rets;
    }
    echo "</p>";
  ////// UNTILL HERE >>
    echo "<p align=\"center\">";
    echo "<a href=\"index.php?stats\"><img src=\"images/stat.gif\" alt=\"*\"/>";
echo "Site Stats</a><br/>";
    echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
    
}

else if(isset($_GET['kcd']))
{
	$who = $_GET["who"];
	$wnick = getnick_uid($who);
    addonline(getuid_sid($sid),"Kickers List","");
    
	echo "<p align=\"center\">";
	echo "<small>Members Kicked <a href=\"index.php?viewuser&amp;who=$who\">$wnick</a>";
	echo "</small></p>";
    //////ALL LISTS SCRIPT <<
	
    if($page=="" || $page<=0)$page=1;
	$noi = mysql_fetch_array(mysql_query("SELECT COUNT(id) FROM fun_usfun WHERE target='".$who."' AND action='kick'")); //changable
    $num_items = $noi[0];
    $items_per_page= 10;
    $num_pages = ceil($num_items/$items_per_page);
    if(($page>$num_pages)&&$page!=1)$page= $num_pages;
    $limit_start = ($page-1)*$items_per_page;

    //changable sql

        $sql = "
		SELECT a.uid, b.name
		FROM fun_usfun a INNER JOIN fun_users b ON a.uid = b.id
		WHERE a.target='".$who."' AND a.action='kick'
		ORDER BY a.actime DESC LIMIT $limit_start, $items_per_page
		;";


    echo "<p><small>";
    $items = mysql_query($sql);
    echo mysql_error();
    if(mysql_num_rows($items)>0)
    {
    while ($item = mysql_fetch_array($items))
    {

      $lnk = "<a href=\"index.php?viewuser&amp;who=$item[0]\">$item[1]</a>";
      echo "$lnk<br/>";
    }
    }
    echo "</small></p>";
    echo "<p align=\"center\">";
    if($page>1)
    {
      $ppage = $page-1;
      echo "<a href=\"lists.php?$action&amp;page=$ppage&amp;who=$who\">&#171;PREV</a> ";
    }
    if($page<$num_pages)
    {
      $npage = $page+1;
      echo "<a href=\"lists.php?$action&amp;page=$npage&amp;who=$who\">Next&#187;</a>";
    }
	if($num_pages>1)
	{
    echo "<br/>$page/$num_pages<br/>";
	}
    if($num_pages>2)
    {
        $rets = "<form action=\"lists.php\" method=\"get\">";
      $rets .= "Jump to page<input name=\"page\" format=\"*N\" size=\"3\"/>";
        $rets .= "<input type=\"submit\" value=\"GO\"/>";
        $rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
        $rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
        $rets .= "<input type=\"hidden\" name=\"who\" value=\"$who\"/>";
        $rets .= "</form>";

        echo $rets;
    }
    echo "</p>";
  ////// UNTILL HERE >>
    echo "<p align=\"center\">";
    echo "<a href=\"index.php?stats\"><img src=\"images/stat.gif\" alt=\"*\"/>";
echo "Site Stats</a><br/>";
    echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
    
}

//////////////////////////////////Top Chatters

else if(isset($_GET['pok']))
{
	$who = $_GET["who"];
	$wnick = getnick_uid($who);
    addonline(getuid_sid($sid),"Pokers List","");
    
	echo "<p align=\"center\">";
	echo "<small>Members Poked by <a href=\"index.php?viewuser&amp;who=$who\">$wnick</a>";
	echo "</small></p>";
    //////ALL LISTS SCRIPT <<
	
    if($page=="" || $page<=0)$page=1;
	$noi = mysql_fetch_array(mysql_query("SELECT COUNT(id) FROM fun_usfun WHERE uid='".$who."' AND action='poke'")); //changable
    $num_items = $noi[0];
    $items_per_page= 10;
    $num_pages = ceil($num_items/$items_per_page);
    if(($page>$num_pages)&&$page!=1)$page= $num_pages;
    $limit_start = ($page-1)*$items_per_page;

    //changable sql

        $sql = "
		SELECT a.target, b.name
		FROM fun_usfun a INNER JOIN fun_users b ON a.target = b.id
		WHERE a.uid='".$who."' AND a.action='poke'
		ORDER BY a.actime DESC LIMIT $limit_start, $items_per_page
		;";


    echo "<p><small>";
    $items = mysql_query($sql);
    echo mysql_error();
    if(mysql_num_rows($items)>0)
    {
    while ($item = mysql_fetch_array($items))
    {

      $lnk = "<a href=\"index.php?viewuser&amp;who=$item[0]\">$item[1]</a>";
      echo "$lnk<br/>";
    }
    }
    echo "</small></p>";
    echo "<p align=\"center\">";
    if($page>1)
    {
      $ppage = $page-1;
      echo "<a href=\"lists.php?$action&amp;page=$ppage&amp;who=$who\">&#171;PREV</a> ";
    }
    if($page<$num_pages)
    {
      $npage = $page+1;
      echo "<a href=\"lists.php?$action&amp;page=$npage&amp;who=$who\">Next&#187;</a>";
    }
	if($num_pages>1)
	{
    echo "<br/>$page/$num_pages<br/>";
	}
    if($num_pages>2)
    {
  $rets = "<form action=\"lists.php\" method=\"get\">";
      $rets .= "Jump to page<input name=\"page\" format=\"*N\" size=\"3\"/>";
        $rets .= "<input type=\"submit\" value=\"GO\"/>";
        $rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
        $rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
        
        $rets .= "<input type=\"hidden\" name=\"who\" value=\"$who\"/>";
        $rets .= "</form>";

        echo $rets;
    }
    echo "</p>";
  ////// UNTILL HERE >>
    echo "<p align=\"center\">";
    echo "<a href=\"index.php?stats\"><img src=\"images/stat.gif\" alt=\"*\"/>";
echo "Site Stats</a><br/>";
    echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
    
}

else if(isset($_GET['pkd']))
{
	$who = $_GET["who"];
	$wnick = getnick_uid($who);
    addonline(getuid_sid($sid),"Pokers List","");
    
	echo "<p align=\"center\">";
	echo "<small>Members Poked <a href=\"index.php?viewuser&amp;who=$who\">$wnick</a>";
	echo "</small></p>";
    //////ALL LISTS SCRIPT <<
	
    if($page=="" || $page<=0)$page=1;
	$noi = mysql_fetch_array(mysql_query("SELECT COUNT(id) FROM fun_usfun WHERE target='".$who."' AND action='poke'")); //changable
    $num_items = $noi[0];
    $items_per_page= 10;
    $num_pages = ceil($num_items/$items_per_page);
    if(($page>$num_pages)&&$page!=1)$page= $num_pages;
    $limit_start = ($page-1)*$items_per_page;

    //changable sql

        $sql = "
		SELECT a.uid, b.name
		FROM fun_usfun a INNER JOIN fun_users b ON a.uid = b.id
		WHERE a.target='".$who."' AND a.action='poke'
		ORDER BY a.actime DESC LIMIT $limit_start, $items_per_page
		;";


    echo "<p><small>";
    $items = mysql_query($sql);
    echo mysql_error();
    if(mysql_num_rows($items)>0)
    {
    while ($item = mysql_fetch_array($items))
    {

      $lnk = "<a href=\"index.php?viewuser&amp;who=$item[0]\">$item[1]</a>";
      echo "$lnk<br/>";
    }
    }
    echo "</small></p>";
    echo "<p align=\"center\">";
    if($page>1)
    {
      $ppage = $page-1;
      echo "<a href=\"lists.php?$action&amp;page=$ppage&amp;who=$who\">&#171;PREV</a> ";
    }
    if($page<$num_pages)
    {
      $npage = $page+1;
      echo "<a href=\"lists.php?$action&amp;page=$npage&amp;who=$who\">Next&#187;</a>";
    }
	if($num_pages>1)
	{
    echo "<br/>$page/$num_pages<br/>";
	}
    if($num_pages>2)
    {
        $rets = "<form action=\"lists.php\" method=\"get\">";
      $rets .= "Jump to page<input name=\"page\" format=\"*N\" size=\"3\"/>";
        $rets .= "<input type=\"submit\" value=\"GO\"/>";
        

 $rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
        

 $rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
        

 $rets .= "<input type=\"hidden\" name=\"who\" value=\"$who\"/>";
        $rets .= "</form>";

        echo $rets;
    }
    echo "</p>";
  ////// UNTILL HERE >>
    echo "<p align=\"center\">";
    echo "<a href=\"index.php?stats\"><img src=\"images/stat.gif\" alt=\"*\"/>";
echo "Site Stats</a><br/>";
    echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
    
}

//////////////////////////////////Top Chatters

else if(isset($_GET['hgs']))
{
	$who = $_GET["who"];
	$wnick = getnick_uid($who);
    addonline(getuid_sid($sid),"Huggers List","");
    
	echo "<p align=\"center\">";
	echo "<small>Members Hugged by <a href=\"index.php?viewuser&amp;who=$who\">$wnick</a>";
	echo "</small></p>";
    //////ALL LISTS SCRIPT <<
	
    if($page=="" || $page<=0)$page=1;
	$noi = mysql_fetch_array(mysql_query("SELECT COUNT(id) FROM fun_usfun WHERE uid='".$who."' AND action='hug'")); //changable
    $num_items = $noi[0];
    $items_per_page= 10;
    $num_pages = ceil($num_items/$items_per_page);
    if(($page>$num_pages)&&$page!=1)$page= $num_pages;
    $limit_start = ($page-1)*$items_per_page;

    //changable sql

        $sql = "
		SELECT a.target, b.name
		FROM fun_usfun a INNER JOIN fun_users b ON a.target = b.id
		WHERE a.uid='".$who."' AND a.action='hug'
		ORDER BY a.actime DESC LIMIT $limit_start, $items_per_page
		;";


    echo "<p><small>";
    $items = mysql_query($sql);
    echo mysql_error();
    if(mysql_num_rows($items)>0)
    {
    while ($item = mysql_fetch_array($items))
    {

      $lnk = "<a href=\"index.php?viewuser&amp;who=$item[0]\">$item[1]</a>";
      echo "$lnk<br/>";
    }
    }
    echo "</small></p>";
    echo "<p align=\"center\">";
    if($page>1)
    {
      $ppage = $page-1;
      echo "<a href=\"lists.php?$action&amp;page=$ppage&amp;who=$who\">&#171;PREV</a> ";
    }
    if($page<$num_pages)
    {
      $npage = $page+1;
      echo "<a href=\"lists.php?$action&amp;page=$npage&amp;who=$who\">Next&#187;</a>";
    }
	if($num_pages>1)
	{
    echo "<br/>$page/$num_pages<br/>";
	}
    if($num_pages>2)
    {
       $rets = "<form action=\"lists.php\" method=\"get\">";
      $rets .= "Jump to page<input name=\"page\" format=\"*N\" size=\"3\"/>";
        $rets .= "<input type=\"submit\" value=\"GO\"/>";
        

 $rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
        

 $rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
        

 $rets .= "<input type=\"hidden\" name=\"who\" value=\"$who\"/>";
        $rets .= "</form>";

        echo $rets;
    }
    echo "</p>";
  ////// UNTILL HERE >>
    echo "<p align=\"center\">";
    echo "<a href=\"index.php?stats\"><img src=\"images/stat.gif\" alt=\"*\"/>";
echo "Site Stats</a><br/>";
    echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
    
}

else if(isset($_GET['hgd']))
{
	$who = $_GET["who"];
	$wnick = getnick_uid($who);
    addonline(getuid_sid($sid),"Huggers List","");
    
	echo "<p align=\"center\">";
	echo "<small>Members hugged <a href=\"index.php?viewuser&amp;who=$who\">$wnick</a>";
	echo "</small></p>";
    //////ALL LISTS SCRIPT <<
	
    if($page=="" || $page<=0)$page=1;
	$noi = mysql_fetch_array(mysql_query("SELECT COUNT(id) FROM fun_usfun WHERE target='".$who."' AND action='hug'")); //changable
    $num_items = $noi[0];
    $items_per_page= 10;
    $num_pages = ceil($num_items/$items_per_page);
    if(($page>$num_pages)&&$page!=1)$page= $num_pages;
    $limit_start = ($page-1)*$items_per_page;

    //changable sql

        $sql = "
		SELECT a.uid, b.name
		FROM fun_usfun a INNER JOIN fun_users b ON a.uid = b.id
		WHERE a.target='".$who."' AND a.action='hug'
		ORDER BY a.actime DESC LIMIT $limit_start, $items_per_page
		;";


    echo "<p><small>";
    $items = mysql_query($sql);
    echo mysql_error();
    if(mysql_num_rows($items)>0)
    {
    while ($item = mysql_fetch_array($items))
    {

      $lnk = "<a href=\"index.php?viewuser&amp;who=$item[0]\">$item[1]</a>";
      echo "$lnk<br/>";
    }
    }
    echo "</small></p>";
    echo "<p align=\"center\">";
    if($page>1)
    {
      $ppage = $page-1;
      echo "<a href=\"lists.php?$action&amp;page=$ppage&amp;who=$who\">&#171;PREV</a> ";
    }
    if($page<$num_pages)
    {
      $npage = $page+1;
      echo "<a href=\"lists.php?$action&amp;page=$npage&amp;who=$who\">Next&#187;</a>";
    }
	if($num_pages>1)
	{
    echo "<br/>$page/$num_pages<br/>";
	}
    if($num_pages>2)
    {
 $rets = "<form action=\"lists.php\" method=\"get\">";
      $rets .= "Jump to page<input name=\"page\" format=\"*N\" size=\"3\"/>";
        $rets .= "<input type=\"submit\" value=\"GO\"/>";
         $rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
         $rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>"; 
$rets .= "<input type=\"hidden\" name=\"who\" value=\"$who\"/>";
          $rets .= "</form>";

        echo $rets;
    }
    echo "</p>";
  ////// UNTILL HERE >>
    echo "<p align=\"center\">";
    echo "<a href=\"index.php?stats\"><img src=\"images/stat.gif\" alt=\"*\"/>";
echo "Site Stats</a><br/>";
    echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
    
}


//////////////////////////////////requists

else if(isset($_GET['reqs']))
{
    addonline(getuid_sid($sid),"Buddy Requests List","");
    
    echo "<p align=\"center\">";
    global $max_buds;
    $uid = getuid_sid($sid);
    echo "<small>The following members want you to add them to your buddy list<br/>";
    $remp = $max_buds - getnbuds($uid);
    echo "you have <b>$remp</b> Places left</small>";
    echo "</p>";
    //////ALL LISTS SCRIPT <<

    if($page=="" || $page<=0)$page=1;
    $nor = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_buddies WHERE tid='".$uid."' AND agreed='0'"));
    $num_items = $nor[0]; //changable
    $items_per_page= 10;
    $num_pages = ceil($num_items/$items_per_page);
    if(($page>$num_pages)&&$page!=1)$page= $num_pages;
    $limit_start = ($page-1)*$items_per_page;

    //changable sql

        $sql = "SELECT uid  FROM fun_buddies WHERE tid='".$uid."' AND agreed='0' ORDER BY reqdt DESC LIMIT $limit_start, $items_per_page";


    echo "<p>";
    $items = mysql_query($sql);
    echo mysql_error();
    if(mysql_num_rows($items)>0)
    {
    while ($item = mysql_fetch_array($items))
    {
        $rnick = getnick_uid($item[0]);
      $lnk = "<a href=\"index.php?viewuser&amp;who=$item[0]\">$rnick</a>: <a href=\"genproc.php?bud&amp;who=$item[0]&amp;todo=add\">Accept</a>, <a href=\"genproc.php?bud&amp;who=$item[0]&amp;todo=del\">Decline</a>";
      echo "$lnk<br/>";
    }
    }
    echo "</p>";
    echo "<p align=\"center\">";
    if($page>1)
    {
      $ppage = $page-1;
      echo "<a href=\"lists.php?$action&amp;page=$ppage&amp;view=$view\">&#171;PREV</a> ";
    }
    if($page<$num_pages)
    {
      $npage = $page+1;
      echo "<a href=\"lists.php?$action&amp;page=$npage&amp;view=$view\">Next&#187;</a>";
    }
    echo "<br/>$page/$num_pages<br/>";
    if($num_pages>2)
    {
  $rets = "<form action=\"lists.php\" method=\"get\">";
      $rets .= "Jump to page<input name=\"page\" format=\"*N\" size=\"3\"/>";
        $rets .= "<input type=\"submit\" value=\"GO\"/>";
         $rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
         $rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
         $rets .= "</form>";

        echo $rets;
    }
    echo "</p>";
  ////// UNTILL HERE >>
    echo "<p align=\"center\">";
    echo "<a href=\"index.php?stats\"><img src=\"images/stat.gif\" alt=\"*\"/>";
echo "Site Stats</a><br/>";
    echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
    
}




//////////////////////////////////shouts

else if(isset($_GET['shouts']))
{
    addonline(getuid_sid($sid),"Viewing Shouts","");
    
    $who = $_GET["who"];
    //////ALL LISTS SCRIPT <<

    if($page=="" || $page<=0)$page=1;
    if($who=="")
    {
    $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_shouts"));
    }else{
    $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_shouts WHERE shouter='".$who."'"));
    }
    $num_items = $noi[0]; //changable
    $items_per_page= 10;
    $num_pages = ceil($num_items/$items_per_page);
    if(($page>$num_pages)&&$page!=1)$page= $num_pages;
    $limit_start = ($page-1)*$items_per_page;

    //changable sql
    if($who =="")
    {
        $sql = "SELECT id, shout, shouter, shtime  FROM fun_shouts ORDER BY shtime DESC LIMIT $limit_start, $items_per_page";
}else{
    $sql = "SELECT id, shout, shouter, shtime  FROM fun_shouts  WHERE shouter='".$who."'ORDER BY shtime DESC LIMIT $limit_start, $items_per_page";
}

    echo "<p><small>";
    $items = mysql_query($sql);
    echo mysql_error();
    if(mysql_num_rows($items)>0)
    {
    while ($item = mysql_fetch_array($items))
    {
        $shnick = getnick_uid($item[2]);
        $sht = htmlspecialchars($item[1]);
        $shdt = date("d m y-H:i", $item[3]);
      $lnk = "<a href=\"index.php?viewuser&amp;who=$item[2]\">$shnick</a>: $sht<br/>$shdt";
      if(ismod(getuid_sid($sid)))
      {
      $dlsh = "<a href=\"modproc.php?delsh&amp;shid=$item[0]\">[x]</a>";
      }else{
        $dlsh = "";
      }
      echo "$lnk $dlsh<br/>";
    }
    }
    echo "</small></p>";
    echo "<p align=\"center\">";
    if($page>1)
    {
      $ppage = $page-1;
      echo "<a href=\"lists.php?shouts&amp;page=$ppage&amp;who=$who\">&#171;PREV</a> ";
    }
    if($page<$num_pages)
    {
      $npage = $page+1;
      echo "<a href=\"lists.php?shouts&amp;page=$npage&amp;who=$who\">Next&#187;</a>";
    }
    echo "<br/>$page/$num_pages<br/>";
    if($num_pages>2)
    {
  $rets = "<form action=\"lists.php\" method=\"get\">";
      $rets .= "Jump to page<input name=\"page\" format=\"*N\" size=\"3\"/>";
        $rets .= "<input type=\"submit\" value=\"GO\"/>";
         $rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
         $rets .= "<input type=\"hidden\" name=\"who\" value=\"$who\"/>";
         $rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
         $rets .= "</form>";

        echo $rets;
    }
    echo "</p>";
  ////// UNTILL HERE >>
    echo "<p align=\"center\">";
    echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
    
}


//////////////////////////////////User Clubs

else if(isset($_GET['ucl']))
{
    addonline(getuid_sid($sid),"User Clubs","");
    
    $who = $_GET["who"];
    //////ALL LISTS SCRIPT <<

    if($page=="" || $page<=0)$page=1;
    $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_clubs WHERE owner='".$who."'"));
    
    $num_items = $noi[0]; //changable
    $items_per_page= 10;
    $num_pages = ceil($num_items/$items_per_page);
    if(($page>$num_pages)&&$page!=1)$page= $num_pages;
    $limit_start = ($page-1)*$items_per_page;

    //changable sql
    
    $sql = "SELECT id  FROM fun_clubs  WHERE owner='".$who."' ORDER BY id LIMIT $limit_start, $items_per_page";


    echo "<p><small>";
    $items = mysql_query($sql);
    echo mysql_error();
    if(mysql_num_rows($items)>0)
    {
    while ($item = mysql_fetch_array($items))
    {
        $nom = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_clubmembers WHERE clid='".$item[0]."' AND accepted='1'"));
		$clinfo = mysql_fetch_array(mysql_query("SELECT name, description FROM fun_clubs WHERE id='".$item[0]."'"));
      $lnk = "<a href=\"index.php?gocl&amp;clid=$item[0]\">".htmlspecialchars($clinfo[0])."</a>($nom[0])<br/>".htmlspecialchars($clinfo[1])."<br/>";
      echo $lnk;
    }
    }
    echo "</small></p>";
    echo "<p align=\"center\">";
    if($page>1)
    {
      $ppage = $page-1;
      echo "<a href=\"lists.php?$action&amp;page=$ppage&amp;who=$who\">&#171;PREV</a> ";
    }
    if($page<$num_pages)
    {
      $npage = $page+1;
      echo "<a href=\"lists.php?$action&amp;page=$npage&amp;who=$who\">Next&#187;</a>";
    }
    if($num_pages>1){
    echo "<br/>$page/$num_pages<br/>";
    }
    if($num_pages>2)
    {
 $rets = "<form action=\"lists.php\" method=\"get\">";
      $rets .= "Jump to page<input name=\"page\" format=\"*N\" size=\"3\"/>";
        $rets .= "<input type=\"submit\" value=\"GO\"/>";
        
 $rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
        
 $rets .= "<input type=\"hidden\" name=\"who\" value=\"$who\"/>";
        
 $rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
        
        $rets .= "</form>";

        echo $rets;
    }
    echo "</p>";
  ////// UNTILL HERE >>
    echo "<p align=\"center\">";
    $whonick = getnick_uid($who);
    echo "<a href=\"index.php?viewuser&amp;who=$who\">$whonick's Profile</a><br/>";
    echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
    
}


//////////////////////////////////User Clubs

else if(isset($_GET['clm']))
{
    addonline(getuid_sid($sid),"Viewing A Member's Club","");
    $who = $_GET["who"];
    //////ALL LISTS SCRIPT <<

    if($page=="" || $page<=0)$page=1;
    $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_clubmembers WHERE uid='".$who."' AND accepted='1'"));

    $num_items = $noi[0]; //changable
    $items_per_page= 10;
    $num_pages = ceil($num_items/$items_per_page);
    if(($page>$num_pages)&&$page!=1)$page= $num_pages;
    $limit_start = ($page-1)*$items_per_page;

    //changable sql

    $sql = "SELECT  clid  FROM fun_clubmembers  WHERE uid='".$who."' AND accepted='1' ORDER BY joined DESC  LIMIT $limit_start, $items_per_page";


    echo "<p><small>";
    $items = mysql_query($sql);
    echo mysql_error();
    if(mysql_num_rows($items)>0)
    {
    while ($item = mysql_fetch_array($items))
    {
        $clnm = mysql_fetch_array(mysql_query("SELECT name FROM fun_clubs WHERE id='".$item[0]."'"));
      $lnk = "<a href=\"index.php?gocl&amp;clid=$item[0]\">".htmlspecialchars($clnm[0])."</a><br/>";
      echo $lnk;
    }
    }
    echo "</small></p>";
    echo "<p align=\"center\">";
    if($page>1)
    {
      $ppage = $page-1;
      echo "<a href=\"lists.php?$action&amp;page=$ppage&amp;who=$who\">&#171;PREV</a> ";
    }
    if($page<$num_pages)
    {
      $npage = $page+1;
      echo "<a href=\"lists.php?$action&amp;page=$npage&amp;who=$who\">Next&#187;</a>";
    }
    if($num_pages>1){
    echo "<br/>$page/$num_pages<br/>";
    }
    if($num_pages>2)
    {
      $rets = "<form action=\"lists.php\" method=\"get\">";
        $rets .= "Jump to page<input name=\"page\" format=\"*N\" size=\"3\"/>";
        $rets .= "<input type=\"submit\" value=\"GO\"/>";
        $rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
        $rets .= "<input type=\"hidden\" name=\"who\" value=\"$who\"/>";
        $rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
        
        $rets .= "</form>";

        echo $rets;
    }
    echo "</p>";
  ////// UNTILL HERE >>
    echo "<p align=\"center\">";
    $whonick = getnick_uid($who);
    echo "<a href=\"index.php?viewuser&amp;who=$who\">$whonick's Profile</a><br/>";
    echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
    
}

//////////////////////////////////Popular clubs

else if(isset($_GET['pclb']))
{
    addonline(getuid_sid($sid),"Viewing Most Popular Clubs","");
    
    //////ALL LISTS SCRIPT <<

    if($page=="" || $page<=0)$page=1;
    $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_clubs"));

    $num_items = $noi[0]; //changable
    $items_per_page= 5;
    $num_pages = ceil($num_items/$items_per_page);
    if(($page>$num_pages)&&$page!=1)$page= $num_pages;
    $limit_start = ($page-1)*$items_per_page;

    //changable sql

    $sql = "SELECT clid, COUNT(*) as notl FROM fun_clubmembers WHERE accepted='1' GROUP BY clid ORDER BY notl DESC LIMIT $limit_start, $items_per_page";


    echo "<p><small>";
    $items = mysql_query($sql);
    echo mysql_error();
    if(mysql_num_rows($items)>0)
    {
    while ($item = mysql_fetch_array($items))
    {
        $clnm = mysql_fetch_array(mysql_query("SELECT name, description FROM fun_clubs WHERE id='".$item[0]."'"));

      $lnk = "<a href=\"index.php?gocl&amp;clid=$item[0]\">".htmlspecialchars($clnm[0])."</a>($item[1])<br/>".htmlspecialchars($clnm[1])."<br/>";
      echo $lnk;
    }
    }
    echo "</small></p>";
    echo "<p align=\"center\">";
    if($page>1)
    {
      $ppage = $page-1;
      echo "<a href=\"lists.php?$action&amp;page=$ppage&amp;who=$who\">&#171;PREV</a> ";
    }
    if($page<$num_pages)
    {
      $npage = $page+1;
      echo "<a href=\"lists.php?$action&amp;page=$npage&amp;who=$who\">Next&#187;</a>";
    }
    if($num_pages>1){
    echo "<br/>$page/$num_pages<br/>";
    }
    if($num_pages>2)
    {
      $rets = "<form action=\"lists.php\" method=\"get\">";
        $rets .= "Jump to page<input name=\"page\" format=\"*N\" size=\"3\"/>";
        $rets .= "<input type=\"submit\" value=\"GO\"/>";
        $rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
        $rets .= "<input type=\"hidden\" name=\"who\" value=\"$who\"/>";
        $rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
        
        $rets .= "</form>";

        echo $rets;
    }
    echo "</p>";
  ////// UNTILL HERE >>
    echo "<p align=\"center\">";
    echo "<a href=\"index.php?clmenu\">Clubs Menu</a><br/>";
    echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
    
}

//////////////////////////////////Active clubs

else if(isset($_GET['aclb']))
{
    addonline(getuid_sid($sid),"Viewing Most Active Clubs","");
    
    //////ALL LISTS SCRIPT <<

    if($page=="" || $page<=0)$page=1;
    $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_clubs"));

    $num_items = $noi[0]; //changable
    $items_per_page= 5;
    $num_pages = ceil($num_items/$items_per_page);
    if(($page>$num_pages)&&$page!=1)$page= $num_pages;
    $limit_start = ($page-1)*$items_per_page;

    //changable sql

    $sql = "SELECT COUNT(*) as notp, b.clubid FROM fun_topics a INNER JOIN fun_forums b ON a.fid = b.id WHERE b.clubid >'0'  GROUP BY b.clubid ORDER BY notp DESC LIMIT $limit_start, $items_per_page";


    echo "<p><small>";
    $items = mysql_query($sql);
    echo mysql_error();
    if(mysql_num_rows($items)>0)
    {
    while ($item = mysql_fetch_array($items))
    {
        $clnm = mysql_fetch_array(mysql_query("SELECT name, description FROM fun_clubs WHERE id='".$item[1]."'"));

      $lnk = "<a href=\"index.php?gocl&amp;clid=$item[1]\">".htmlspecialchars($clnm[0])."</a>($item[0] Topics)<br/>".htmlspecialchars($clnm[1])."<br/>";
      echo $lnk;
    }
    }
    echo "</small></p>";
    echo "<p align=\"center\">";
    if($page>1)
    {
      $ppage = $page-1;
      echo "<a href=\"lists.php?$action&amp;page=$ppage&amp;who=$who\">&#171;PREV</a> ";
    }
    if($page<$num_pages)
    {
      $npage = $page+1;
      echo "<a href=\"lists.php?$action&amp;page=$npage&amp;who=$who\">Next&#187;</a>";
    }
    if($num_pages>1){
    echo "<br/>$page/$num_pages<br/>";
    }
    if($num_pages>2)
    {
      $rets = "<form action=\"lists.php\" method=\"get\">";
        $rets .= "Jump to page<input name=\"page\" format=\"*N\" size=\"3\"/>";
        $rets .= "<input type=\"submit\" value=\"GO\"/>";
        $rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
        $rets .= "<input type=\"hidden\" name=\"who\" value=\"$who\"/>";
        $rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
        
        $rets .= "</form>";

        echo $rets;
    }
    echo "</p>";
  ////// UNTILL HERE >>
    echo "<p align=\"center\">";
    echo "<a href=\"index.php?clmenu\">Clubs Menu</a><br/>";
    echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
    
}

//////////////////////////////////Random clubs

else if(isset($_GET['rclb']))
{
    addonline(getuid_sid($sid),"Viewing A Random Club","");
    
    //////ALL LISTS SCRIPT <<

    $sql = "SELECT id, name, description FROM fun_clubs ORDER BY RAND()  LIMIT 5";


    echo "<p><small>";
    $items = mysql_query($sql);
    echo mysql_error();
    if(mysql_num_rows($items)>0)
    {
    while ($item = mysql_fetch_array($items))
    {

      $lnk = "<a href=\"index.php?gocl&amp;clid=$item[0]\">".htmlspecialchars($item[1])."</a><br/>".htmlspecialchars($item[2])."<br/>";
      echo $lnk;
    }
    }
    echo "</small></p>";
    
    
  ////// UNTILL HERE >>
    echo "<p align=\"center\">";
    echo "<a href=\"index.php?clmenu\">Clubs Menu</a><br/>";
    echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
    
}
//////////////////////////////////shouts

else if(isset($_GET['annc']))
{
    addonline(getuid_sid($sid),"looking At An Announcement","");
    
    $clid = $_GET["clid"];
    //////ALL LISTS SCRIPT <<
    $uid = getuid_sid($sid);
    if($page=="" || $page<=0)$page=1;
    $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_announcements WHERE clid='".$clid."'"));
    
    $num_items = $noi[0]; //changable
    $items_per_page= 5;
    $num_pages = ceil($num_items/$items_per_page);
    if(($page>$num_pages)&&$page!=1)$page= $num_pages;
    $limit_start = ($page-1)*$items_per_page;

    //changable sql
        $sql = "SELECT id, antext, antime  FROM fun_announcements WHERE clid='".$clid."' ORDER BY antime DESC LIMIT $limit_start, $items_per_page";

    $cow = mysql_fetch_array(mysql_query("SELECT owner FROM fun_clubs WHERE id='".$clid."'"));
    echo "<p><small>";
    $items = mysql_query($sql);
    if(mysql_num_rows($items)>0)
    {
    while ($item = mysql_fetch_array($items))
    {
      if($cow[0]==$uid)
      {
      $dlan = "<a href=\"genproc.php?delan&amp;anid=$item[0]&amp;clid=$clid\">[x]</a>";
      }else{
        $dlan = "";
      }
      $annc = htmlspecialchars($item[1])."<br/>".date("d/m/y (H:i)", $item[2]);
      echo "$annc $dlan<br/>";
    }
    }
    echo "</small></p>";
    echo "<p align=\"center\">";
    if($page>1)
    {
      $ppage = $page-1;
      echo "<a href=\"lists.php?$action&amp;page=$ppage&amp;clid=$clid\">&#171;PREV</a> ";
    }
    if($page<$num_pages)
    {
      $npage = $page+1;
      echo "<a href=\"lists.php?$action&amp;page=$npage&amp;clid=$clid\">Next&#187;</a>";
    }
    echo "<br/>$page/$num_pages<br/>";
    if($num_pages>2)
    {
      $rets = "<form action=\"lists.php\" method=\"get\">";
        $rets .= "Jump to page<input name=\"page\" format=\"*N\" size=\"3\"/>";
        $rets .= "<input type=\"submit\" value=\"GO\"/>";
        $rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
        $rets .= "<input type=\"hidden\" name=\"clid\" value=\"$clid\"/>";
        $rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
        
        $rets .= "</form>";

        echo $rets;
    }
    echo "</p>";
  ////// UNTILL HERE >>
    echo "<p align=\"center\">";
    if($cow[0]==$uid)
      {
      $dlan = "<a href=\"index.php?annc&amp;clid=$clid\">Announce!</a><br/><br/>";
      echo $dlan;
      }
    echo "<a href=\"index.php?gocl&amp;clid=$clid\">";
echo "Back to club</a><br/>";
    echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
    
}
//////////////////////////////////clubs requests

else if(isset($_GET['clreq']))
{
    addonline(getuid_sid($sid),"Viewing Club Requests","");
    
    $clid = $_GET["clid"];
    $uid = getuid_sid($sid);
    $cowner = mysql_fetch_array(mysql_query("SELECT owner FROM fun_clubs WHERE id='".$clid."'"));
    //////ALL LISTS SCRIPT <<
    if($cowner[0]==$uid)
    {
    if($page=="" || $page<=0)$page=1;
    $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_clubmembers WHERE clid='".$clid."' AND accepted='0'"));
    $num_items = $noi[0]; //changable
    $items_per_page= 10;
    $num_pages = ceil($num_items/$items_per_page);
    if(($page>$num_pages)&&$page!=1)$page= $num_pages;
    $limit_start = ($page-1)*$items_per_page;

    //changable sql
        $sql = "SELECT uid  FROM fun_clubmembers WHERE clid='".$clid."' AND accepted='0' ORDER BY joined DESC LIMIT $limit_start, $items_per_page";
    echo "<p><small>";
    $items = mysql_query($sql);
    echo mysql_error();
    if(mysql_num_rows($items)>0)
    {
    while ($item = mysql_fetch_array($items))
    {
        $shnick = getnick_uid($item[0]);
      $lnk = "<a href=\"index.php?viewuser&amp;who=$item[0]\">$shnick</a>: <a href=\"genproc.php?acm&amp;who=$item[0]&amp;clid=$clid\">accept</a>, <a href=\"genproc.php?dcm&amp;who=$item[0]&amp;clid=$clid\">deny</a><br/>";
      echo "$lnk";
    }
    }
    echo "</small></p>";
    echo "<p align=\"center\">";
    if($page>1)
    {
      $ppage = $page-1;
      echo "<a href=\"lists.php?$action&amp;page=$ppage&amp;clid=$clid\">&#171;PREV</a> ";
    }
    if($page<$num_pages)
    {
      $npage = $page+1;
      echo "<a href=\"lists.php?$action&amp;page=$npage&amp;clid=$clid\">Next&#187;</a>";
    }
    echo "<br/>$page/$num_pages<br/>";
    if($num_pages>2)
    {
      $rets = "<form action=\"lists.php\" method=\"get\">";
        $rets .= "Jump to page<input name=\"page\" format=\"*N\" size=\"3\"/>";
        $rets .= "<input type=\"submit\" value=\"GO\"/>";
        $rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
        $rets .= "<input type=\"hidden\" name=\"clid\" value=\"$clid\"/>";
        $rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
        
        $rets .= "</form>";

        echo $rets;
    }
	echo "<br/><br/><a href=\"genproc.php?accall&amp;clid=$clid\">Accept All</a>, ";
	echo "<a href=\"genproc.php?denall&amp;clid=$clid\">Deny All</a>";
    echo "</p>";
    }else{
      echo "<p align=\"center\">This club isnt yours</p>";
    }
  ////// UNTILL HERE >>
    echo "<p align=\"center\">";
    echo "<a href=\"index.php?gocl&amp;clid=$clid\">";
echo "Back to club</a><br/>";
    echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
    
}

//////////////////////////////////clubs members

else if(isset($_GET['clmem']))
{
    addonline(getuid_sid($sid),"Viewing Club Members","");
        $clid = $_GET["clid"];
    $uid = getuid_sid($sid);
    $cowner = mysql_fetch_array(mysql_query("SELECT owner FROM fun_clubs WHERE id='".$clid."'"));
    //////ALL LISTS SCRIPT <<
    if($page=="" || $page<=0)$page=1;
    $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_clubmembers WHERE clid='".$clid."' AND accepted='1'"));
    $num_items = $noi[0]; //changable
    $items_per_page= 10;
    $num_pages = ceil($num_items/$items_per_page);
    if(($page>$num_pages)&&$page!=1)$page= $num_pages;
    $limit_start = ($page-1)*$items_per_page;

    //changable sql
        $sql = "SELECT uid, joined, points  FROM fun_clubmembers WHERE clid='".$clid."' AND accepted='1' ORDER BY joined DESC LIMIT $limit_start, $items_per_page";
    echo "<p><small>";
    $items = mysql_query($sql);
    echo mysql_error();
    if(mysql_num_rows($items)>0)
    {
    while ($item = mysql_fetch_array($items))
    {
      if($cowner[0]==$uid)
      {
        $oop = ": <a href=\"index.php?clmop&amp;who=$item[0]&amp;clid=$clid\">Options</a>";
      }else{
        $oop = "";
      }
        $shnick = getnick_uid($item[0]);
      $lnk = "<a href=\"index.php?viewuser&amp;who=$item[0]\">$shnick</a>$oop<br/>";
      $lnk .= "Joined: ".date("d/m/y", $item[1])." - Club Points: $item[2]";
      
      echo "$lnk<br/>";
    }
    }
    echo "</small></p>";
    echo "<p align=\"center\">";
    if($page>1)
    {
      $ppage = $page-1;
      echo "<a href=\"lists.php?$action&amp;page=$ppage&amp;clid=$clid\">&#171;PREV</a> ";
    }
    if($page<$num_pages)
    {
      $npage = $page+1;
      echo "<a href=\"lists.php?$action&amp;page=$npage&amp;clid=$clid\">Next&#187;</a>";
    }
    echo "<br/>$page/$num_pages<br/>";
    if($num_pages>2)
    {
      $rets = "<form action=\"lists.php\" method=\"get\">";
        $rets .= "Jump to page<input name=\"page\" format=\"*N\" size=\"3\"/>";
        $rets .= "<input type=\"submit\" value=\"GO\"/>";
        $rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
        $rets .= "<input type=\"hidden\" name=\"clid\" value=\"$clid\"/>";
        $rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
        
        $rets .= "</form>";

        echo $rets;
    }
    echo "</p>";
    
  ////// UNTILL HERE >>
    echo "<p align=\"center\">";
    echo "<a href=\"index.php?gocl&amp;clid=$clid\">";
echo "Back to club</a><br/>";
    echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
    
}


//////////////////////////////////User topics

else if(isset($_GET['tbuid']))
{
  $who = $_GET["who"];
    addonline(getuid_sid($sid),"Viewing A Users Topic List","");
    
    
    //////ALL LISTS SCRIPT <<

    if($page=="" || $page<=0)$page=1;
    $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_topics WHERE authorid='".$who."'"));
    $num_items = $noi[0]; //changable
    $items_per_page= 10;
    $num_pages = ceil($num_items/$items_per_page);
    if(($page>$num_pages)&&$page!=1)$page= $num_pages;
    $limit_start = ($page-1)*$items_per_page;

    //changable sql
    
    $sql = "SELECT id, name, crdate  FROM fun_topics  WHERE authorid='".$who."'ORDER BY crdate DESC LIMIT $limit_start, $items_per_page";


    echo "<p><small>";
    $items = mysql_query($sql);
    echo mysql_error();
    if(mysql_num_rows($items)>0)
    {
    while ($item = mysql_fetch_array($items))
    {
      if(canaccess(getuid_sid($sid),getfid_tid($item[0])))
      {
        echo "<a href=\"index.php?viewtpc&amp;tid=$item[0]\">".htmlspecialchars($item[1])."</a> <small>".date("d m y-H:i:s",$item[2])."</small><br/>";
        }else{
          echo "Private Topic<br/>";
        }
    }
    }
    echo "</small></p>";
    echo "<p align=\"center\">";
    if($page>1)
    {
      $ppage = $page-1;
      echo "<a href=\"lists.php?$action&amp;page=$ppage&amp;who=$who\">&#171;PREV</a> ";
    }
    if($page<$num_pages)
    {
      $npage = $page+1;
      echo "<a href=\"lists.php?$action&amp;page=$npage&amp;who=$who\">Next&#187;</a>";
    }
    echo "<br/>$page/$num_pages<br/>";
    if($num_pages>2)
    {
      $rets = "<form action=\"lists.php\" method=\"get\">";
        $rets .= "Jump to page<input name=\"page\" format=\"*N\" size=\"3\"/>";
        $rets .= "<input type=\"submit\" value=\"GO\"/>";
        $rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
        $rets .= "<input type=\"hidden\" name=\"who\" value=\"$who\"/>";
        $rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
        
        $rets .= "</form>";

        echo $rets;
    }
    echo "</p>";
  ////// UNTILL HERE >>
    echo "<p align=\"center\">";
    $unick = getnick_uid($who);
    echo "<a href=\"index.php?viewuser&amp;who=$who\">";
echo "$unick's Profile</a><br/>";
    echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
    
}

//////////////////////////////////User topics

else if(isset($_GET['uposts']))
{
  $who = $_GET["who"];
    addonline(getuid_sid($sid),"Viewing Users Posts","");
    

    //////ALL LISTS SCRIPT <<

    if($page=="" || $page<=0)$page=1;
    $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_posts WHERE uid='".$who."'"));
    $num_items = $noi[0]; //changable
    $items_per_page= 10;
    $num_pages = ceil($num_items/$items_per_page);
    if(($page>$num_pages)&&$page!=1)$page= $num_pages;
    $limit_start = ($page-1)*$items_per_page;

    //changable sql

    $sql = "SELECT id, dtpost  FROM fun_posts  WHERE uid='".$who."'ORDER BY dtpost DESC LIMIT $limit_start, $items_per_page";


    echo "<p><small>";
    $items = mysql_query($sql);
    echo mysql_error();
    if(mysql_num_rows($items)>0)
    {
    while ($item = mysql_fetch_array($items))
    {
      $tid = gettid_pid($item[0]);
      $tname = gettname($tid);
      if(canaccess(getuid_sid($sid),getfid_tid($tid)))
      {
        echo "<a href=\"index.php?viewtpc&amp;tid=$tid&amp;go=$item[0]\">".htmlspecialchars($tname)."</a> <small>".date("d m y-H:i:s",$item[1])."</small><br/>";
        }else{
          echo "Private Post<br/>";
        }
    }
    }
    echo "</small></p>";
    echo "<p align=\"center\">";
    if($page>1)
    {
      $ppage = $page-1;
      echo "<a href=\"lists.php?$action&amp;page=$ppage&amp;who=$who\">&#171;PREV</a> ";
    }
    if($page<$num_pages)
    {
      $npage = $page+1;
      echo "<a href=\"lists.php?$action&amp;page=$npage&amp;who=$who\">Next&#187;</a>";
    }
    echo "<br/>$page/$num_pages<br/>";
    if($num_pages>2)
    {
      $rets = "<form action=\"lists.php\" method=\"get\">";
        $rets .= "Jump to page<input name=\"page\" format=\"*N\" size=\"3\"/>";
        $rets .= "<input type=\"submit\" value=\"GO\"/>";
        $rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
        $rets .= "<input type=\"hidden\" name=\"who\" value=\"$who\"/>";
        $rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
        
        $rets .= "</form>";

        echo $rets;
    }
    echo "</p>";
  ////// UNTILL HERE >>
  
    echo "<p align=\"center\">";
    $unick = getnick_uid($who);
    echo "<a href=\"index.php?viewuser&amp;who=$who\">";
echo "$unick's Profile</a><br/>";
    echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
    
}

//////////////////////////////////Top Gamers

else if(isset($_GET['tgame']))
{
    addonline(getuid_sid($sid),"Viewing Top Gamers List","");
    
    echo "<p align=\"center\">";
    echo "<b>Top Gamers</b>";
    echo "</p>";
    //////ALL LISTS SCRIPT <<

    if($page=="" || $page<=0)$page=1;
    $num_items = regmemcount(); //changable
    $items_per_page= 10;
    $num_pages = ceil($num_items/$items_per_page);
    if(($page>$num_pages)&&$page!=1)$page= $num_pages;
    $limit_start = ($page-1)*$items_per_page;

    //changable sql

        $sql = "SELECT id, name, gplus FROM fun_users ORDER BY gplus DESC LIMIT $limit_start, $items_per_page";


    echo "<p>";
    $items = mysql_query($sql);
    echo mysql_error();
    if(mysql_num_rows($items)>0)
    {
    while ($item = mysql_fetch_array($items))
    {

      $lnk = "<a href=\"index.php?viewuser&amp;who=$item[0]\">$item[1]</a> <small>Game Plusses: $item[2]</small>";
      echo "$lnk<br/>";
    }
    }
    echo "</p>";
    echo "<p align=\"center\">";
    if($page>1)
    {
      $ppage = $page-1;
      echo "<a href=\"lists.php?tgame&amp;page=$ppage&amp;view=$view\">&#171;PREV</a> ";
    }
    if($page<$num_pages)
    {
      $npage = $page+1;
      echo "<a href=\"lists.php?tgame&amp;page=$npage&amp;view=$view\">Next&#187;</a>";
    }
    echo "<br/>$page/$num_pages<br/>";
    if($num_pages>2)
    {
      $rets = "<form action=\"lists.php\" method=\"get\">";
        $rets .= "Jump to page<input name=\"page\" format=\"*N\" size=\"3\"/>";
        $rets .= "<input type=\"submit\" value=\"GO\"/>";
        $rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
        $rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
        
        $rets .= "</form>";

        echo $rets;
    }
    echo "</p>";
  ////// UNTILL HERE >>
    echo "<p align=\"center\">";
    echo "<a href=\"index.php?stats\"><img src=\"images/stat.gif\" alt=\"*\"/>";
echo "Site Stats</a><br/>";
    echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
    
}

//////////////////////////////////Top Gammers

else if(isset($_GET['topb']))
{
    addonline(getuid_sid($sid),"Viewing Top Rap Battlers","");
    
    echo "<p align=\"center\">";
    echo "<b>Top Battlers</b>";
    echo "</p>";
    //////ALL LISTS SCRIPT <<

    if($page=="" || $page<=0)$page=1;
    $num_items = regmemcount(); //changable
    $items_per_page= 10;
    $num_pages = ceil($num_items/$items_per_page);
    if(($page>$num_pages)&&$page!=1)$page= $num_pages;
    $limit_start = ($page-1)*$items_per_page;

    //changable sql

        $sql = "SELECT id, name, battlep FROM fun_users ORDER BY battlep DESC LIMIT $limit_start, $items_per_page";


    echo "<p>";
    $items = mysql_query($sql);
    if(mysql_num_rows($items)>0)
    {
    while ($item = mysql_fetch_array($items))
    {

      $lnk = "<a href=\"index.php?viewuser&amp;who=$item[0]\">$item[1]</a> <small>Battle Points: $item[2]</small>";
      echo "$lnk<br/>";
    }
    }
    echo "</p>";
    echo "<p align=\"center\">";
    if($page>1)
    {
      $ppage = $page-1;
      echo "<a href=\"lists.php?topb&amp;page=$ppage\">&#171;PREV</a> ";
    }
    if($page<$num_pages)
    {
      $npage = $page+1;
      echo "<a href=\"lists.php?topb&amp;page=$npage\">Next&#187;</a>";
    }
    echo "<br/>$page/$num_pages<br/>";
    if($num_pages>2)
    {
      $rets = "<form action=\"lists.php\" method=\"get\">";
        $rets .= "Jump to page<input name=\"page\" format=\"*N\" size=\"3\"/>";
        $rets .= "<input type=\"submit\" value=\"GO\"/>";
        $rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
        $rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
        
        $rets .= "</form>";

        echo $rets;
    }
    echo "</p>";
  ////// UNTILL HERE >>
    echo "<p align=\"center\">";
    echo "<a href=\"index.php?stats\"><img src=\"images/stat.gif\" alt=\"*\"/>";
echo "Site Stats</a><br/>";
    echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
    
}


//////////////////////////////////Banned

else if(isset($_GET['banned']))
{
    addonline(getuid_sid($sid),"Viewing The Naughty Users List","");
    
    echo "<p align=\"center\">";
    echo "<b>Banned List</b>";
    echo "</p>";
    //////ALL LISTS SCRIPT <<

    if($page=="" || $page<=0)$page=1;
    $noi = mysql_fetch_array(mysql_query("SELECT count(*) FROM fun_penalties WHERE penalty='1' OR penalty='2'"));
    $num_items = $noi[0]; //changable
    $items_per_page= 5;
    $num_pages = ceil($num_items/$items_per_page);
    if(($page>$num_pages)&&$page!=1)$page= $num_pages;
    $limit_start = ($page-1)*$items_per_page;

    //changable sql

        $sql = "SELECT uid, penalty, pnreas, exid FROM fun_penalties WHERE penalty='1' OR penalty='2' ORDER BY timeto LIMIT $limit_start, $items_per_page";


    echo "<p>";
    $items = mysql_query($sql);
    echo mysql_error();
    if(mysql_num_rows($items)>0)
    {
    while ($item = mysql_fetch_array($items))
    {

      $lnk = "<a href=\"index.php?viewuser&amp;who=$item[0]\">".getnick_uid($item[0])."</a> (".htmlspecialchars($item[2]).")";
      if($item[1]=="1")
      {
        $bt = "Normal Ban";
      }else{
        $bt = "IP Ban";
      }
      if(ismod(getuid_sid($sid)))
      {
        $bym = "By ".getnick_uid($item[3]);
      }else{
        $bym = "";
      }
      echo "<small>$lnk $bt $bym</small><br/>";
    }
    }
    echo "</p>";
    echo "<p align=\"center\">";
    if($page>1)
    {
      $ppage = $page-1;
      echo "<a href=\"lists.php?banned&amp;page=$ppage&amp;view=$view\">&#171;PREV</a> ";
    }
    if($page<$num_pages)
    {
      $npage = $page+1;
      echo "<a href=\"lists.php?banned&amp;page=$npage&amp;view=$view\">Next&#187;</a>";
    }
    echo "<br/>$page/$num_pages<br/>";
    if($num_pages>2)
    {
      $rets = "<form action=\"lists.php\" method=\"get\">";
        $rets .= "Jump to page<input name=\"page\" format=\"*N\" size=\"3\"/>";
        $rets .= "<input type=\"submit\" value=\"GO\"/>";
        $rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
        $rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
        
        $rets .= "</form>";

        echo $rets;
    }
    echo "</p>";
  ////// UNTILL HERE >>
    echo "<p align=\"center\">";
    echo "<a href=\"index.php?stats\"><img src=\"images/stat.gif\" alt=\"*\"/>";
echo "Site Stats</a><br/>";
    echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
    
}

//////////////////////////////////Trashed

else if(isset($_GET['trashed']))
{
    addonline(getuid_sid($sid),"Viewing The Trashed Users List","");
    
    echo "<p align=\"center\">";
    echo "<b>Trashed List</b>";
    echo "</p>";
    //////ALL LISTS SCRIPT <<
    if(ismod(getuid_sid($sid)))
    {
    if($page=="" || $page<=0)$page=1;
    $noi = mysql_fetch_array(mysql_query("SELECT count(*) FROM fun_penalties WHERE penalty='0'"));
    $num_items = $noi[0]; //changable
    $items_per_page= 5;
    $num_pages = ceil($num_items/$items_per_page);
    if(($page>$num_pages)&&$page!=1)$page= $num_pages;
    $limit_start = ($page-1)*$items_per_page;

    //changable sql

        $sql = "SELECT uid, penalty, pnreas, exid FROM fun_penalties WHERE penalty='0' ORDER BY timeto LIMIT $limit_start, $items_per_page";


    echo "<p>";
    $items = mysql_query($sql);
    echo mysql_error();
    if(mysql_num_rows($items)>0)
    {
    while ($item = mysql_fetch_array($items))
    {

      $lnk = "<a href=\"index.php?viewuser&amp;who=$item[0]\">".getnick_uid($item[0])."</a> (".htmlspecialchars($item[2]).")";
      if(ismod(getuid_sid($sid)))
      {
        $bym = "By ".getnick_uid($item[3]);
      }else{
        $bym = "";
      }
      echo "<small>$lnk $bym</small><br/>";
    }
  }
  }else{
    echo "You can't view this list";
  }
    echo "</p>";
    echo "<p align=\"center\">";
    if($page>1)
    {
      $ppage = $page-1;
      echo "<a href=\"lists.php?trashed&amp;page=$ppage&amp;view=$view\">&#171;PREV</a> ";
    }
    if($page<$num_pages)
    {
      $npage = $page+1;
      echo "<a href=\"lists.php?trashed&amp;page=$npage&amp;view=$view\">Next&#187;</a>";
    }
    echo "<br/>$page/$num_pages<br/>";
    if($num_pages>2)
    {
      $rets = "<form action=\"lists.php\" method=\"get\">";
        $rets .= "Jump to page<input name=\"page\" format=\"*N\" size=\"3\"/>";
        $rets .= "<input type=\"submit\" value=\"GO\"/>";
        $rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
        $rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
        
        $rets .= "</form>";

        echo $rets;
    }
    echo "</p>";
  ////// UNTILL HERE >>
    echo "<p align=\"center\">";
    echo "<a href=\"index.php?stats\"><img src=\"images/stat.gif\" alt=\"*\"/>";
echo "Site Stats</a><br/>";
    echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
    
}

//////////////////////////////////Trashed

else if(isset($_GET['ipban']))
{
    addonline(getuid_sid($sid),"Viewing Banned IPs List","");
    
    echo "<p align=\"center\">";
    echo "<b>Banned IP's List</b>";
    echo "</p>";
    //////ALL LISTS SCRIPT <<
    if(ismod(getuid_sid($sid)))
    {
    if($page=="" || $page<=0)$page=1;
    $noi = mysql_fetch_array(mysql_query("SELECT count(*) FROM fun_penalties WHERE penalty='2'"));
    $num_items = $noi[0]; //changable
    $items_per_page= 5;
    $num_pages = ceil($num_items/$items_per_page);
    if(($page>$num_pages)&&$page!=1)$page= $num_pages;
    $limit_start = ($page-1)*$items_per_page;

    //changable sql

        $sql = "SELECT uid, penalty, pnreas, exid, ipadd FROM fun_penalties WHERE penalty='2' ORDER BY timeto LIMIT $limit_start, $items_per_page";


    echo "<p>";
    $items = mysql_query($sql);
    echo mysql_error();
    if(mysql_num_rows($items)>0)
    {
    while ($item = mysql_fetch_array($items))
    {

      $lnk = "<a href=\"index.php?viewuser&amp;who=$item[0]\">".getnick_uid($item[0])."</a> (".htmlspecialchars($item[2]).")";
      if(ismod(getuid_sid($sid)))
      {
        $bym = "By ".getnick_uid($item[3]);
      }else{
        $bym = "";
      }
      $ipl = "IP:<a href=\"lists.php?byip&amp;who=$item[0]\">$item[4]</a>";
      echo "<small>$lnk $bym ($ipl)</small><br/>";
    }
  }
  }else{
    echo "You can't view this list";
  }
    echo "</p>";
    echo "<p align=\"center\">";
    if($page>1)
    {
      $ppage = $page-1;
      echo "<a href=\"lists.php?$action&amp;page=$ppage&amp;view=$view\">&#171;PREV</a> ";
    }
    if($page<$num_pages)
    {
      $npage = $page+1;
      echo "<a href=\"lists.php?$action&amp;page=$npage&amp;view=$view\">Next&#187;</a>";
    }
    echo "<br/>$page/$num_pages<br/>";
    if($num_pages>2)
    {
      $rets = "<form action=\"lists.php\" method=\"get\">";
        $rets .= "Jump to page<input name=\"page\" format=\"*N\" size=\"3\"/>";
        $rets .= "<input type=\"submit\" value=\"GO\"/>";
        $rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
        $rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
        
        $rets .= "</form>";

        echo $rets;
    }
    echo "</p>";
  ////// UNTILL HERE >>
    echo "<p align=\"center\">";
    echo "<a href=\"index.php?stats\"><img src=\"images/stat.gif\" alt=\"*\"/>";
echo "Site Stats</a><br/>";
    echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
    
}
//////////////////////////////////Smilies :)

else if(isset($_GET['smilies']))
{
    addonline(getuid_sid($sid),"Viewing The Smilies List","");
   echo vrhonline($sid,$uid);
    
  echo "<div class=\"section border_top\"></div>";
  echo "<div class=\"section_title\"><div class=\"marker\">Smajliji</div></div>";
    
    //////ALL LISTS SCRIPT <<

    if($page=="" || $page<=0)$page=1;
    $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_smilies"));
    $num_items = $noi[0]; //changable
    $items_per_page= 15;
    $num_pages = ceil($num_items/$items_per_page);
    if(($page>$num_pages)&&$page!=1)$page= $num_pages;
    $limit_start = ($page-1)*$items_per_page;

    //changable sql

        $sql = "SELECT id, scode, imgsrc FROM fun_smilies ORDER BY id DESC LIMIT $limit_start, $items_per_page";


    if($page>1)
    {
      $ppage = $page-1;
      echo "<div class='center border_bottom'><a href=\"lists.php?smilies&amp;page=$ppage&amp;view=$view\"><img src='images/up.png' /></a></div>";
    }
    $items = mysql_query($sql);
	
    echo mysql_error();
    if(mysql_num_rows($items)>0)
    {
    while ($item = mysql_fetch_array($items))
   {
		if(isadmin(getuid_sid($sid)))
		{
			$delsl = "<a href=\"admproc.php?delsm&amp;smid=$item[0]\">[x]</a>";
		}else{
			$delsl = "";
		}
        echo "<div class='titl sredina'><img src=\"$item[2]\" alt=\"$item[1]\"/><br /> $item[1] </div>";
    }
    }
	
    if($page<$num_pages)
    {
      $npage = $page+1;
      echo "<div class='center border_top'><a href=\"lists.php?smilies&amp;page=$npage&amp;view=$view\"><img src='images/down.png' /></a></div>";
    }
    echo "<div class='center'>$page/$num_pages</div>";
    if($num_pages>2)
    {
      $rets = "<div class='comment'><form action=\"lists.php\" method=\"get\">";
        $rets .= "Preskoci na stranu: <input name=\"page\" format=\"*N\" size=\"3\"/>";
        $rets .= "<input type=\"submit\" class='button' value=\"GO\"/>";
        $rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
        $rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
        
        $rets .= "</form></div>";

        echo $rets;
    }
	
  echo dnoonline($sid,$uid);
    
}

//////////////////////////////////Moods :)

else if(isset($_GET['chmood']))
{
    addonline(getuid_sid($sid),"Viewing The Moods List","");
    

    //////ALL LISTS SCRIPT <<

    if($page=="" || $page<=0)$page=1;
    $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_moods"));
    $num_items = $noi[0]; //changable
    $items_per_page= 5;
    $num_pages = ceil($num_items/$items_per_page);
    if(($page>$num_pages)&&$page!=1)$page= $num_pages;
    $limit_start = ($page-1)*$items_per_page;

    //changable sql

        $sql = "SELECT text, img, dscr, id FROM fun_moods ORDER BY id DESC LIMIT $limit_start, $items_per_page";


    echo "<p>";
    $items = mysql_query($sql);
    echo mysql_error();
    if(mysql_num_rows($items)>0)
    {
    while ($item = mysql_fetch_array($items))
   {
        echo "<small><a href=\"genproc.php?upcm&amp;cmid=$item[3]\">$item[0]</a> &#187; ";
        echo "<img src=\"$item[1]\" alt=\"$item[0]\"/> &#187; $item[2] </small><br/>";
    }
    }
    echo "</p>";
    echo "<p align=\"center\">";
    echo "<a href=\"genproc.php?upcm&amp;cmid=0\">Disable Chatmood</a><br/><br/>";
    if($page>1)
    {
      $ppage = $page-1;
      echo "<a href=\"lists.php?chmood&amp;page=$ppage&amp;view=$view\">&#171;PREV</a> ";
    }
    if($page<$num_pages)
    {
      $npage = $page+1;
      echo "<a href=\"lists.php?chmood&amp;page=$npage&amp;view=$view\">Next&#187;</a>";
    }
    echo "<br/>$page/$num_pages<br/>";
    if($num_pages>2)
    {
      $rets = "<form action=\"lists.php\" method=\"get\">";
        $rets .= "Jump to page<input name=\"page\" format=\"*N\" size=\"3\"/>";
        $rets .= "<input type=\"submit\" value=\"GO\"/>";
        $rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
        $rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
        
        $rets .= "</form>";

        echo $rets;
    }
    echo "</p>";
  ////// UNTILL HERE >>
    echo "<p align=\"center\">";
    echo "<a href=\"index.php?chat\">";
echo "Chatrooms</a><br/>";
    echo "<a href=\"index.php?cpanel\">";
echo "CPanel</a><br/>";
    echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
    
}

//////////////////////////////////Avatars

else if(isset($_GET['avatars']))
{
    addonline(getuid_sid($sid),"Viwing The Avatars List","");
    

    //////ALL LISTS SCRIPT <<

    if($page=="" || $page<=0)$page=1;
    $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_avatars"));
    $num_items = $noi[0]; //changable
    $items_per_page= 2;
    $num_pages = ceil($num_items/$items_per_page);
    if(($page>$num_pages)&&$page!=1)$page= $num_pages;
    $limit_start = ($page-1)*$items_per_page;

    //changable sql

        $sql = "SELECT id, avlink FROM fun_avatars ORDER BY id DESC LIMIT $limit_start, $items_per_page";


    echo "<p>";
    $items = mysql_query($sql);
    echo mysql_error();
    if(mysql_num_rows($items)>0)
    {
    while ($item = mysql_fetch_array($items))
   {
        echo "<img src=\"$item[1]\" alt=\"avatar\"/><br/>";
        echo "<a href=\"genproc.php?upav&amp;avid=$item[0]\">SELECT</a><br/>";
        echo "<br/>";
    }
    }
    echo "</p>";
    echo "<p align=\"center\">";
    if($page>1)
    {
      $ppage = $page-1;
      echo "<a href=\"lists.php?avatars&amp;page=$ppage&amp;view=$view\">&#171;PREV</a> ";
    }
    if($page<$num_pages)
    {
      $npage = $page+1;
      echo "<a href=\"lists.php?avatars&amp;page=$npage&amp;view=$view\">Next&#187;</a>";
    }
    echo "<br/>$page/$num_pages<br/>";
    if($num_pages>2)
    {
      $rets = "<form action=\"lists.php\" method=\"get\">";
        $rets .= "Jump to page<input name=\"page\" format=\"*N\" size=\"3\"/>";
        $rets .= "<input type=\"submit\" value=\"GO\"/>";
        $rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
        $rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
        
        $rets .= "</form>";

        echo $rets;
    }
    echo "</p>";
  ////// UNTILL HERE >>
    echo "<p align=\"center\">";
    echo "<a href=\"index.php?cpanel\">";
echo "CPanel</a><br/>";
    echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
    
}

//////////////////////////////////E-cards

else if(isset($_GET['ecards']))
{
    addonline(getuid_sid($sid),"Viewing E-Cards List","");
    

    //////ALL LISTS SCRIPT <<

    if($page=="" || $page<=0)$page=1;
    $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_cards"));
    $num_items = $noi[0]; //changable
    $items_per_page= 2;
    $num_pages = ceil($num_items/$items_per_page);
    if(($page>$num_pages)&&$page!=1)$page= $num_pages;
    $limit_start = ($page-1)*$items_per_page;

    //changable sql

        $sql = "SELECT id, category FROM fun_cards ORDER BY id DESC LIMIT $limit_start, $items_per_page";


    echo "<p>";
    $items = mysql_query($sql);
    echo mysql_error();
    if(mysql_num_rows($items)>0)
    {
    while ($item = mysql_fetch_array($items))
   {
		$sl = strlen($item[0]);
		$cid="";
		if($sl<3)
		{
			for($i=$sl;$i<3;$i++)
			{
				$cid .= "0";
			}
		}
		$cid .= $item[0];
		$msg = "Sample Text";
        echo "<img src=\"pmcard.php?cid=$cid&amp;msg=$msg\" alt=\"$cid\"/><br/>";
        echo "<small>[card=$cid]$msg"."[/card]</small>";
        echo "<br/>";
    }
    }
    echo "</p>";
    echo "<p align=\"center\">";
    if($page>1)
    {
      $ppage = $page-1;
      echo "<a href=\"lists.php?$action&amp;page=$ppage&amp;view=$view\">&#171;PREV</a> ";
    }
    if($page<$num_pages)
    {
      $npage = $page+1;
      echo "<a href=\"lists.php?$action&amp;page=$npage&amp;view=$view\">Next&#187;</a>";
    }
    echo "<br/>$page/$num_pages<br/>";
    if($num_pages>2)
    {
      $rets = "<form action=\"lists.php\" method=\"get\">";
        $rets .= "Jump to page<input name=\"page\" format=\"*N\" size=\"3\"/>";
        $rets .= "<input type=\"submit\" value=\"GO\"/>";
        $rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
        $rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
        
        $rets .= "</form>";

        echo $rets;
    }
    echo "</p>";
  ////// UNTILL HERE >>
    echo "<p align=\"center\">";
    echo "<a href=\"index.php?cpanel\">";
echo "CPanel</a><br/>";
    echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
    
}

//////////////////////////////////Buddies

else if(isset($_GET['buds']))
{
    addonline(getuid_sid($sid),"Viewing My Buddies List","");
    
    $uid = getuid_sid($sid);
    echo parsemsg(getbudmsg($uid), $sid);
    //////ALL LISTS SCRIPT <<
vrhonline($sid,$uid);
    if($page=="" || $page<=0)$page=1;
    $num_items = getnbuds($uid); //changable
    $items_per_page= 5;
    $num_pages = ceil($num_items/$items_per_page);
    if(($page>$num_pages)&&$page!=1)$page= $num_pages;
    $limit_start = ($page-1)*$items_per_page;

    if($page>1)
    {
      $ppage = $page-1;
      echo "<div class='section center border_top_light'><a href=\"lists.php?buds&amp;page=$ppage&amp;view=$view\"><img src=\"images/up.png\"></a></div>";
    }
    //changable sql
/*
$sql = "SELECT
            a.name, b.place, b.userid FROM fun_users a
            INNER JOIN fun_online b ON a.id = b.userid
            GROUP BY 1,2
            LIMIT $limit_start, $items_per_page
    ";
*/
        $sql = "SELECT a.lastact, a.name, a.id, b.uid, b.tid, b.reqdt FROM fun_users a INNER JOIN fun_buddies b ON (a.id = b.uid) OR (a.id=b.tid) WHERE (b.uid='".$uid."' OR b.tid='".$uid."') AND b.agreed='1' AND a.id!='".$uid."' GROUP BY 1,2  ORDER BY a.lastact DESC LIMIT $limit_start, $items_per_page";


    $items = mysql_query($sql);
    echo mysql_error();
    if(mysql_num_rows($items)>0)
    {
    while ($item = mysql_fetch_array($items))
    {
          if(isonline($item[2]))
  {
    $iml = "<img src=\"images/online.png\" alt=\"+\"/>";
    $uact = "Poslednja aktivnost: ";
    $ladt = date("d m y-H:i:s", $item[0]);
    $uact .= $ladt;
  }else{
    $iml = "";
    $uact = "Poslednja aktivnost: ";
    $ladt = date("d m y-H:i:s", $item[0]);
    $uact .= $ladt;
  }
  $avlink = getavatar($item[2]);
        if ($avlink!=""){
      $avatar = "<img src=\"$avlink\" height=\"25\" width=\"25\" alt=\"avatar\"/>";
      }else{
      $avatar = "<img src=\"images/nopic.jpg\" height=\"25\" width=\"25\" alt=\"avatar\"/>";
      }
      $lnk = "$avatar<a href=\"index.php?viewuser&amp;who=$item[2]\">$item[1]</a>$iml";
      echo "<div class=\"border_top_light\"><div class=\"qn section\" id=\"anchor_fbid_310948112165\"><div class=\"is\">$lnk<br/>";
      echo "<small>";
      $bs = date("d m y-H:i:s",$item[5]);
      echo "Prijatelji od:$bs<br/>";
      echo "$uact<br/>";
      /*echo "Trenutni status: ";
      $bmsg = parsemsg(getbudmsg($item[2]), $sid);
      echo "$bmsg<br/>";*/
      echo "</small>";
	  echo "</div></div></div>";
      
    }
    }else{
	echo "<small>Vi nemate prijatelja na listi...</small><br /><small>Vreme je da pocnete da se druzite... :)</small><br /><br />";
	}
	
    if($page<$num_pages)
    {
      $npage = $page+1;
      echo "<div class='section center border_top_light'><a href=\"lists.php?buds&amp;page=$npage&amp;view=$view\"><img src=\"images/down.png\"></a></div>";
    }
dnoonline($sid,$uid);
    
}

//////////////////////////////////Buddies

else if(isset($_GET['gbook']))
{
    $who = $_GET["who"];
    addonline(getuid_sid($sid),"Viewing Guestbook","");
    
    $uid = getuid_sid($sid);
    
    //////ALL LISTS SCRIPT <<

    if($page=="" || $page<=0)$page=1;
    $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_gbook WHERE gbowner='".$who."'"));
    $num_items = $noi[0]; //changable
    $items_per_page= 5;
    $num_pages = ceil($num_items/$items_per_page);
    if(($page>$num_pages)&&$page!=1)$page= $num_pages;
    $limit_start = ($page-1)*$items_per_page;

    
        $sql = "SELECT gbowner, gbsigner, gbmsg, dtime, id FROM fun_gbook WHERE gbowner='".$who."' ORDER BY dtime DESC LIMIT $limit_start, $items_per_page";


    echo "<p>";
    $items = mysql_query($sql);
    echo mysql_error();
    if(mysql_num_rows($items)>0)
    {
    while ($item = mysql_fetch_array($items))
    {
        
          if(isonline($item[1]))
  {
    $iml = "<img src=\"images/onl.gif\" alt=\"+\"/>";
    
  }else{
    $iml = "<img src=\"images/ofl.gif\" alt=\"-\"/>";
  }
    $snick = getnick_uid($item[1]);
      $lnk = "<a href=\"index.php?viewuser&amp;who=$item[1]\">$iml$snick</a>";
      $bs = date("d m y-H:i:s",$item[3]);
      echo "$lnk<br/><small>";
      if(candelgb($uid, $item[4]))
      {
        $delnk = "<a href=\"genproc.php?delfgb&amp;mid=$item[4]\">[x]</a>";
      }else{
        $delnk = "";
      }
      $text = parsepm($item[2], $sid);
      echo "$text<br/>$bs $delnk<br/>";
      echo "</small>";

    }
    }
    echo "</p>";
    echo "<p align=\"center\">";
    if($page>1)
    {
      $ppage = $page-1;
      echo "<a href=\"lists.php?$action&amp;page=$ppage&amp;who=$who\">&#171;PREV</a> ";
    }
    if($page<$num_pages)
    {
      $npage = $page+1;
      echo "<a href=\"lists.php?$action&amp;page=$npage&amp;who=$who\">Next&#187;</a>";
    }
    echo "<br/>$page/$num_pages<br/>";
    if($num_pages>2)
    {
      $rets = "<form action=\"lists.php\" method=\"get\">";
        $rets .= "Jump to page<input name=\"page\" format=\"*N\" size=\"3\"/>";
        $rets .= "<input type=\"submit\" value=\"GO\"/>";
        $rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
        $rets .= "<input type=\"hidden\" name=\"who\" value=\"$who\"/>";
        $rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
        
        $rets .= "</form>";

        echo $rets;
    }
    echo "</p>";
  ////// UNTILL HERE >>
    echo "<p align=\"center\">";
    if(cansigngb($uid, $who))
    {
    echo "<a href=\"index.php?signgb&amp;who=$who\">";
echo "Add Your Message</a><br/>";
}
    echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
    
}

//////////////////////////////////Buddies

else if(isset($_GET['vault']))
{
    $who = $_GET["who"];
    addonline(getuid_sid($sid),"Viewing The Vault","");
    
    $uid = getuid_sid($sid);



    //////ALL LISTS SCRIPT <<

    if($page=="" || $page<=0)$page=1;
    if($who!="")
    {
    $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_vault WHERE uid='".$who."'"));
    }else{
    $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_vault"));
    }
    $num_items = $noi[0]; //changable
    $items_per_page= 10;
    $num_pages = ceil($num_items/$items_per_page);
    if(($page>$num_pages)&&$page!=1)$page= $num_pages;
    $limit_start = ($page-1)*$items_per_page;

    if($who!="")
    {
        $sql = "SELECT id, title, itemurl FROM fun_vault WHERE uid='".$who."' ORDER BY pudt DESC LIMIT $limit_start, $items_per_page";
        }else{
$sql = "SELECT id, title, itemurl, uid FROM fun_vault  ORDER BY pudt DESC LIMIT $limit_start, $items_per_page";
        }


    echo "<p><small>";
    $items = mysql_query($sql);
    echo mysql_error();
    if(mysql_num_rows($items)>0)
    {
    while ($item = mysql_fetch_array($items))
    {
      $ext = getext($item[2]);
        $ime = getextimg($ext);
        $lnk = "<a href=\"$item[2]\">$ime".htmlspecialchars($item[1])."</a>";
        
      if(candelvl($uid, $item[0]))
      {
        $delnk = "<a href=\"genproc.php?delvlt&amp;vid=$item[0]\">[x]</a>";
      }else{
        $delnk = "";
      }
      if($who!="")
      {
        $byusr="";
      }else{
        $unick = getnick_uid($item[3]);
        $ulnk = "<a href=\"index.php?viewuser&amp;who=$item[3]\">$unick</a>";
        $byusr = "- By $ulnk";
      }
      echo "$lnk $byusr $delnk<br/>";
      

    }
    }
    echo "</small></p>";
    echo "<p align=\"center\">";
    if($page>1)
    {
      $ppage = $page-1;
      echo "<a href=\"lists.php?$action&amp;page=$ppage&amp;who=$who\">&#171;PREV</a> ";
    }
    if($page<$num_pages)
    {
      $npage = $page+1;
      echo "<a href=\"lists.php?$action&amp;page=$npage&amp;who=$who\">Next&#187;</a>";
    }
    echo "<br/>$page/$num_pages<br/>";
    if($num_pages>2)
    {
      $rets = "<form action=\"lists.php\" method=\"get\">";
        $rets .= "Jump to page<input name=\"page\" format=\"*N\" size=\"3\"/>";
        $rets .= "<input type=\"submit\" value=\"GO\"/>";
        $rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
        $rets .= "<input type=\"hidden\" name=\"who\" value=\"$who\"/>";
        $rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
        
        $rets .= "</form>";

        echo $rets;
    }
    echo "</p>";
  ////// UNTILL HERE >>
    echo "<p align=\"center\">";
    if($uid==$who && getplusses($uid)>25)
    {
    echo "<a href=\"index.php?addvlt\">";
echo "Add Item</a><br/>";
}
if($who!="")
{
echo "<a href=\"index.php?viewuser&amp;who=$who\">";
$whonick = getnick_uid($who);
echo "$whonick's Profile</a><br/>";
}else{
echo "<a href=\"index.php?stats\">";
echo "<img src=\"images/stat.gif\" alt=\"*\"/>Site Stats</a><br/>";
}
    echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
    
}

//////////////////////////////////Ignore list

else if(isset($_GET['ignl']))
{
    addonline(getuid_sid($sid),"Viewing My Ignore List","");
        $uid = getuid_sid($sid);
    echo "<p align=\"center\">";
    echo "<b>Ignore List</b>";

    echo "</p>";
    //////ALL LISTS SCRIPT <<

    if($page=="" || $page<=0)$page=1;
    $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_ignore WHERE name='".$uid."'"));
    $num_items = $noi[0]; //changable
    $items_per_page= 10;
    $num_pages = ceil($num_items/$items_per_page);
    if(($page>$num_pages)&&$page!=1)$page= $num_pages;
    $limit_start = ($page-1)*$items_per_page;

    //changable sql
/*
$sql = "SELECT
            a.name, b.place, b.userid FROM fun_users a
            INNER JOIN fun_online b ON a.id = b.userid
            GROUP BY 1,2
            LIMIT $limit_start, $items_per_page
    ";
*/
        $sql = "SELECT target FROM fun_ignore WHERE name='".$uid."' LIMIT $limit_start, $items_per_page";


    echo "<p>";
    $items = mysql_query($sql);
    echo mysql_error();
    if(mysql_num_rows($items)>0)
    {
    while ($item = mysql_fetch_array($items))
    {
        $tnick = getnick_uid($item[0]);
          if(isonline($item[0]))
  {
    $iml = "<img src=\"images/onl.gif\" alt=\"+\"/>";
    
  }else{
    $iml = "<img src=\"images/ofl.gif\" alt=\"-\"/>";
    
  }
      $lnk = "<a href=\"index.php?viewuser&amp;who=$item[0]\">$iml$tnick</a>";
      echo "$lnk: ";
      echo "<small><a href=\"genproc.php?ign&amp;who=$item[0]&amp;todo=del\">Remove From Ignore list</a></small><br/>";
    }
    }
    echo "</p>";
    echo "<p align=\"center\">";
    if($page>1)
    {
      $ppage = $page-1;
      echo "<a href=\"lists.php?ignl&amp;page=$ppage\">&#171;PREV</a> ";
    }
    if($page<$num_pages)
    {
      $npage = $page+1;
      echo "<a href=\"lists.php?ignl&amp;page=$npage\">Next&#187;</a>";
    }
    echo "<br/>$page/$num_pages<br/>";
    if($num_pages>2)
    {
      $rets = "<form action=\"lists.php\" method=\"get\">";
        $rets .= "Jump to page<input name=\"page\" format=\"*N\" size=\"3\"/>";
        $rets .= "<input type=\"submit\" value=\"GO\"/>";
        $rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
        $rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
        
        $rets .= "</form>";

        echo $rets;
    }
    echo "</p>";
  ////// UNTILL HERE >>
    echo "<p align=\"center\">";
    echo "<a href=\"index.php?cpanel\">";
echo "CPanel</a><br/>";
    echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
    
}

//////////////////////////////////Blogs

else if(isset($_GET['blogs']))
{
    addonline(getuid_sid($sid),"Viewing A Users Blog","");
    
    $uid = getuid_sid($sid);
    $who = $_GET["who"];
    $tnick = getnick_uid($who);
    echo "<p align=\"center\">";
    echo "<b>$tnick's Blogs</b>";

    echo "</p>";
    //////ALL LISTS SCRIPT <<

    if($page=="" || $page<=0)$page=1;
    $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_blogs WHERE bowner='".$who."'"));
    $num_items = $noi[0]; //changable
    $items_per_page= 10;
    $num_pages = ceil($num_items/$items_per_page);
    if(($page>$num_pages)&&$page!=1)$page= $num_pages;
    $limit_start = ($page-1)*$items_per_page;

        $sql = "SELECT id, bname FROM fun_blogs WHERE bowner='".$who."' ORDER BY bgdate DESC LIMIT $limit_start, $items_per_page";


    echo "<p>";
    $items = mysql_query($sql);
    echo mysql_error();
    if(mysql_num_rows($items)>0)
    {
    while ($item = mysql_fetch_array($items))
    {
        $bname = htmlspecialchars($item[1]);
    if(candelbl($uid,$item[0]))
    {
      $dl = "<a href=\"genproc.php?delbl&amp;bid=$item[0]\">[X]</a>";
    }else{
      $dl = "";
    }
      $lnk = "<a href=\"index.php?viewblog&amp;bid=$item[0]\">&#187;$bname</a>";
      echo "$lnk $dl<br/>";
    }
    }
    echo "</p>";
    echo "<p align=\"center\">";
    if($page>1)
    {
      $ppage = $page-1;
      echo "<a href=\"lists.php?$action&amp;page=$ppage&amp;who=$who\">&#171;PREV</a> ";
    }
    if($page<$num_pages)
    {
      $npage = $page+1;
      echo "<a href=\"lists.php?$action&amp;page=$npage&amp;who=$who\">Next&#187;</a>";
    }
    echo "<br/>$page/$num_pages<br/>";
    if($num_pages>2)
    {
      $rets = "<form action=\"lists.php\" method=\"get\">";
        $rets .= "Jump to page<input name=\"page\" format=\"*N\" size=\"3\"/>";
        $rets .= "<input type=\"submit\" value=\"GO\"/>";
        $rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
        $rets .= "<input type=\"hidden\" name=\"who\" value=\"$who\"/>";
        $rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
        
        $rets .= "</form>";

        echo $rets;
    }
    echo "</p>";
  ////// UNTILL HERE >>
    echo "<p align=\"center\">";
    if($who==$uid)
    {
        echo "<a href=\"index.php?addblg\">";
echo "Add a blog</a><br/>";
echo "<a href=\"index.php?cpanel\">";
echo "CPanel</a><br/>";
    }
    echo "<a href=\"lists.php?allbl\">";
echo "All Blogs</a><br/>";
    echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
    
}

//////////////////////////////////Blogs

else if(isset($_GET['allbl']))
{
    addonline(getuid_sid($sid),"Viewqing Blogs list","");
    
    $uid = getuid_sid($sid);
    $view = $_GET["view"];
    if($view =="")$view="time";
    echo "<p align=\"center\"><small>";
    if($view!="time")
    {
      echo "<a href=\"lists.php?allbl&amp;view=time\">View Newest</a><br/>";
    }
    if($view!="points")
    {
      echo "<a href=\"lists.php?allbl&amp;view=points\">View by points</a><br/>";
    }
    if($view!="rate")
    {
      echo "<a href=\"lists.php?allbl&amp;view=rate\">View most rated</a><br/>";
    }
    if($view!="votes")
    {
      echo "<a href=\"lists.php?allbl&amp;view=votes\">View most voted</a>";
    }
    echo "</small></p>";
    //////ALL LISTS SCRIPT <<
    
    if($page=="" || $page<=0)$page=1;
    $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_blogs"));
    $num_items = $noi[0]; //changable
    $items_per_page= 7;
    $num_pages = ceil($num_items/$items_per_page);
    if(($page>$num_pages)&&$page!=1)$page= $num_pages;
    $limit_start = ($page-1)*$items_per_page;
if($view=="time")
{
  $ord = "a.bgdate";
}else if($view=="votes")
{
  $ord = "nofv";
}else if($view=="rate")
{
  $ord = "avv";
}else if($view=="points")
{
  $ord = "nofp";
}
if ($view=="time"){
  $sql = "SELECT id, bname, bowner FROM fun_blogs ORDER by bgdate DESC LIMIT $limit_start, $items_per_page";
}else{
        $sql = "SELECT a.id, a.bname, a.bowner, COUNT(b.id) as nofv, SUM(b.brate) as nofp, AVG(b.brate) as avv FROM fun_blogs a INNER JOIN fun_brate b ON a.id = b.blogid GROUP BY a.id ORDER BY $ord DESC LIMIT $limit_start, $items_per_page";
}
    echo "<p><small>";
    $items = mysql_query($sql);
    echo mysql_error();
    if(mysql_num_rows($items)>0)
    {
    while ($item = mysql_fetch_array($items))
    {
      $bname = htmlspecialchars($item[1]);
      if($view=="time")
      {
      $bonick = getnick_uid($item[2]);
        
        $byview = "by <a href=\"index.php?viewuser&amp;who=$item[2]\">$bonick</a>";
        }else if($view=="votes")
        {
          $byview = "Votes: $item[3]";
        }else if($view=="rate")
        {
          $byview = "Rate: $item[5]";
        }else if($view=="points")
        {
          $byview = "Points: $item[4]";
        }
      $lnk = "<a href=\"index.php?viewblog&amp;bid=$item[0]\">&#187;$bname</a> $byview";
      echo "$lnk<br/>";
    }
    }
    echo "</small></p>";
    echo "<p align=\"center\">";
    if($page>1)
    {
      $ppage = $page-1;
      echo "<a href=\"lists.php?$action&amp;page=$ppage&amp;view=$view\">&#171;PREV</a> ";
    }
    if($page<$num_pages)
    {
      $npage = $page+1;
      echo "<a href=\"lists.php?$action&amp;page=$npage&amp;view=$view\">Next&#187;</a>";
    }
    echo "<br/>$page/$num_pages<br/>";
    if($num_pages>2)
    {
      $rets = "<form action=\"lists.php\" method=\"get\">";
        $rets .= "Jump to page<input name=\"page\" format=\"*N\" size=\"3\"/>";
        $rets .= "<input type=\"submit\" value=\"GO\"/>";
        $rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
        $rets .= "<input type=\"hidden\" name=\"view\" value=\"$view\"/>";
        $rets .= "<input type=\"hidden\" name=\"who\" value=\"$who\"/>";
        $rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
        
        $rets .= "</form>";

        echo $rets;
    }
    echo "</p>";
  ////// UNTILL HERE >>
    echo "<p align=\"center\">";
    
    echo "<a href=\"index.php?stats\">";
echo "<img src=\"images/stat.gif\" alt=\"*\"/>Site Stats</a><br/>";
    echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
    
}//////////////////////////////////Blogs

else if(isset($_GET['polls']))
{
    addonline(getuid_sid($sid),"Viewing Polls list","");
    
    $uid = getuid_sid($sid);
    //////ALL LISTS SCRIPT <<

    if($page=="" || $page<=0)$page=1;
    $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_users WHERE pollid>'0'"));
    $num_items = $noi[0]; //changable
    $items_per_page= 10;
    $num_pages = ceil($num_items/$items_per_page);
    if(($page>$num_pages)&&$page!=1)$page= $num_pages;
    $limit_start = ($page-1)*$items_per_page;

  $sql = "SELECT id, name FROM fun_users WHERE pollid>'0' ORDER by pollid DESC LIMIT $limit_start, $items_per_page";

    echo "<p><small>";
    $items = mysql_query($sql);
    echo mysql_error();
    if(mysql_num_rows($items)>0)
    {
    while ($item = mysql_fetch_array($items))
    {
      echo "By <a href=\"index.php?viewpl&amp;who=$item[0]\">$item[1]</a><br/>";
    }
    }
    echo "</small></p>";
    echo "<p align=\"center\">";
    if($page>1)
    {
      $ppage = $page-1;
      echo "<a href=\"lists.php?$action&amp;page=$ppage&amp;view=$view\">&#171;PREV</a> ";
    }
    if($page<$num_pages)
    {
      $npage = $page+1;
      echo "<a href=\"lists.php?$action&amp;page=$npage&amp;view=$view\">Next&#187;</a>";
    }
    echo "<br/>$page/$num_pages<br/>";
    if($num_pages>2)
    {
      $rets = "<form action=\"lists.php\" method=\"get\">";
        $rets .= "Jump to page<input name=\"page\" format=\"*N\" size=\"3\"/>";
        $rets .= "<input type=\"submit\" value=\"GO\"/>";
        $rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
        $rets .= "<input type=\"hidden\" name=\"view\" value=\"$view\"/>";
        $rets .= "<input type=\"hidden\" name=\"who\" value=\"$who\"/>";
        $rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
        
        $rets .= "</form>";

        echo $rets;
    }
    echo "</p>";
  ////// UNTILL HERE >>
    echo "<p align=\"center\">";

    echo "<a href=\"index.php?stats\">";
echo "<img src=\"images/stat.gif\" alt=\"*\"/>Site Stats</a><br/>";
    echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
    
}

//////////////////////////////////Top Gammers

else if(isset($_GET['tshout']))
{
    addonline(getuid_sid($sid),"Viewing Top Shouters list","");
    
    echo "<p align=\"center\">";
    echo "<b>Top Shouters</b>";
    echo "</p>";
    //////ALL LISTS SCRIPT <<

    if($page=="" || $page<=0)$page=1;
    $num_items = regmemcount(); //changable
    $items_per_page= 10;
    $num_pages = ceil($num_items/$items_per_page);
    if(($page>$num_pages)&&$page!=1)$page= $num_pages;
    $limit_start = ($page-1)*$items_per_page;

    //changable sql

        $sql = "SELECT id, name, shouts FROM fun_users ORDER BY shouts DESC LIMIT $limit_start, $items_per_page";


    echo "<p>";
    $items = mysql_query($sql);
    echo mysql_error();
    if(mysql_num_rows($items)>0)
    {
    while ($item = mysql_fetch_array($items))
    {

      $lnk = "<a href=\"index.php?viewuser&amp;who=$item[0]\">$item[1]</a> <small>Shouts: $item[2]</small>";
      echo "$lnk<br/>";
    }
    }
    echo "</p>";
    echo "<p align=\"center\">";
    if($page>1)
    {
      $ppage = $page-1;
      echo "<a href=\"lists.php?tshout&amp;page=$ppage&amp;view=$view\">&#171;PREV</a> ";
    }
    if($page<$num_pages)
    {
      $npage = $page+1;
      echo "<a href=\"lists.php?tshout&amp;page=$npage&amp;view=$view\">Next&#187;</a>";
    }
    echo "<br/>$page/$num_pages<br/>";
    if($num_pages>2)
    {
      $rets = "<form action=\"lists.php\" method=\"get\">";
        $rets .= "Jump to page<input name=\"page\" format=\"*N\" size=\"3\"/>";
        $rets .= "<input type=\"submit\" value=\"GO\"/>";
        $rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
        $rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
        
        $rets .= "</form>";

        echo $rets;
    }
    echo "</p>";
  ////// UNTILL HERE >>
    echo "<p align=\"center\">";
    echo "<a href=\"index.php?stats\"><img src=\"images/stat.gif\" alt=\"*\"/>";
echo "Site Stats</a><br/>";
    echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
    
}

//////////////////////////////////Top Gammers

else if(isset($_GET['bbcode']))
{
    addonline(getuid_sid($sid),"Viewing BBcode List","");
    
    echo "<p align=\"center\">";
    echo "<b>BBcode</b>";
    echo "</p>";
    echo "<p>";
    echo "<b>WARNING:</b> Misusing the bbcodes may cause display errors<br/><br/>";
    echo "[b]TEXT[/b]: <b>TEXT</b><br/>";
    echo "[i]TEXT[/i]: <i>TEXT</i><br/>";
    echo "[u]TEXT[/u]: <u>TEXT</u><br/>";
    echo "[big]TEXT[/big]: <big>TEXT</big><br/>";
    echo "[small]TEXT[/small]: <small>TEXT</small><br/>";
    echo "[url=<i>http://FunMobile.WS</i>]<i>FunMobile.WS</i>[/url]: <a href=\"http://FunMobile.WS\">FunMobile.WS</a><br/>";
    echo "<small>replace http://FunMobile.WS with any other link, and replace FunMobile.WS with any word you want</small><br/><br/>";
    echo "[topic=<i>1501</i>]<i>Topic Name</i>[/topic]: <a href=\"index.php?viewtpc&amp;tid=1501\">Topic Name</a><br/>";
    echo "<small>replace 1501 with the topic id, and replace Topic Name with any word you want</small><br/><br/>";
    echo "[blog=<i>1</i>]<i>Blog Name</i>[/blog]: <a href=\"index.php?viewblog&amp;bid=1\">Blog Name</a><br/>";
    echo "<small>replace 1 with the blog id, and replace Blog Name with any word you want</small><br/><br/>";
    echo "[club=<i>1</i>]<i>Club Name</i>[/club]: <a href=\"index.php?gocl&amp;clid=1501\">Club Name</a><br/>";
    echo "<small>replace 1 with the club id, and replace Club Name with any word you want</small><br/><br/>";
    echo "[br/]: to insert new line, like:";
    echo "hello[br/]world!:<br/>amylee<br/>love you<br/><br/>";
    echo "/rwfaqs: <a href=\"lists.php?faqs\">F.A.Qs</a> <small>in PMs only</small>";
    
    echo "</p>";
    echo "<p align=\"center\">";
    echo "<a href=\"index.php?cpanel\">";
echo "CPanel</a><br/>";
    echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
    
}


//////////////////////////////////Top Gammers

else if(isset($_GET['faqs']))
{
    addonline(getuid_sid($sid),"F.A.Qs","");
    
    echo "<p align=\"center\">";
    echo "<b>F.A.Qs</b>";
    echo "</p>";
    //////ALL LISTS SCRIPT <<

    if($page=="" || $page<=0)$page=1;
    $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_faqs"));
    $num_items = $noi[0]; //changable
    $items_per_page= 5;
    $num_pages = ceil($num_items/$items_per_page);
    if(($page>$num_pages)&&$page!=1)$page= $num_pages;
    $limit_start = ($page-1)*$items_per_page;

    //changable sql

        $sql = "SELECT question, answer FROM fun_faqs ORDER BY id LIMIT $limit_start, $items_per_page";


    echo "<p><small>";
    $items = mysql_query($sql);
    echo mysql_error();
    if(mysql_num_rows($items)>0)
    {
    while ($item = mysql_fetch_array($items))
    {
        $item[0] = parsepm($item[0], $sid);
        $item[1] = parsepm($item[1], $sid);
      echo "<b>Q. $item[0]</b><br/>";
      echo "A. $item[1]<br/>";
    }
    }
    echo "</small></p>";
    echo "<p align=\"center\">";
    if($page>1)
    {
      $ppage = $page-1;
      echo "<a href=\"lists.php?$action&amp;page=$ppage&amp;view=$view\">&#171;PREV</a> ";
    }
    if($page<$num_pages)
    {
      $npage = $page+1;
      echo "<a href=\"lists.php?$action&amp;page=$npage&amp;view=$view\">Next&#187;</a>";
    }
    echo "<br/>$page/$num_pages<br/>";
    if($num_pages>2)
    {
      $rets = "<form action=\"lists.php\" method=\"get\">";
        $rets .= "Jump to page<input name=\"page\" format=\"*N\" size=\"3\"/>";
        $rets .= "<input type=\"submit\" value=\"GO\"/>";
        $rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
        $rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
        
        $rets .= "</form>";

        echo $rets;
    }
    echo "</p>";
  ////// UNTILL HERE >>
    echo "<p align=\"center\">";
    echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
    
}


//////////////////////////////////Staff

else if(isset($_GET['staff']))
{
    addonline(getuid_sid($sid),"Viewing Staff list","");
    echo "<p align=\"center\">";
    echo "<img src=\"smilies/order.gif\" alt=\"*\"/><br/>";
    echo "<b>Staff List</b><br/><small>";
    $noi = mysql_fetch_array(mysql_query("SELECT count(*) FROM fun_users WHERE perm='2'"));
    echo "<a href=\"lists.php?admns\">Admins($noi[0])</a><br/>";
    $noi = mysql_fetch_array(mysql_query("SELECT count(*) FROM fun_users WHERE perm='1'"));
    echo "<a href=\"lists.php?modr\">Moderators($noi[0])</a></small>";
    echo "</p>";
    //////ALL LISTS SCRIPT <<
    $noi = mysql_fetch_array(mysql_query("SELECT count(*) FROM fun_users WHERE perm>'0'"));
    
    if($page=="" || $page<=0)$page=1;
    $num_items = $noi[0]; //changable
    $items_per_page= 10;
    $num_pages = ceil($num_items/$items_per_page);
    if(($page>$num_pages)&&$page!=1)$page= $num_pages;
    $limit_start = ($page-1)*$items_per_page;

    //changable sql

        $sql = "SELECT id, name, perm FROM fun_users WHERE perm>'0' ORDER BY name LIMIT $limit_start, $items_per_page";


    echo "<p>";
    $items = mysql_query($sql);
    echo mysql_error();
    if(mysql_num_rows($items)>0)
    {
    while ($item = mysql_fetch_array($items))
    {
        if($item[2]=='1')
        {
          $tit = "Moderator";
        }else{
          $tit = "Admin";
        }
      $lnk = "<a href=\"index.php?viewuser&amp;who=$item[0]\">$item[1]</a> <small>$tit</small>";
      echo "$lnk<br/>";
    }
    }
    echo "</p>";
    echo "<p align=\"center\">";
    if($page>1)
    {
      $ppage = $page-1;
      echo "<a href=\"lists.php?staff&amp;page=$ppage&amp;view=$view\">&#171;PREV</a> ";
    }
    if($page<$num_pages)
    {
      $npage = $page+1;
      echo "<a href=\"lists.php?staff&amp;page=$npage&amp;view=$view\">Next&#187;</a>";
    }
    echo "<br/>$page/$num_pages<br/>";
    if($num_pages>2)
    {
      $rets = "<form action=\"lists.php\" method=\"get\">";
        $rets .= "Jump to page<input name=\"page\" format=\"*N\" size=\"3\"/>";
        $rets .= "<input type=\"submit\" value=\"GO\"/>";
        $rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
        $rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
        
        $rets .= "</form>";

        echo $rets;
    }
    echo "</p>";
  ////// UNTILL HERE >>
    echo "<p align=\"center\">";
    echo "<a href=\"index.php?stats\"><img src=\"images/stat.gif\" alt=\"*\"/>";
echo "Site Stats</a><br/>";
    echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
    
}

//////////////////////////////////Staff

else if(isset($_GET['admns']))
{
    addonline(getuid_sid($sid),"Viewing Admins list","");
    
    echo "<p align=\"center\">";
    echo "<img src=\"smilies/order.gif\" alt=\"*\"/><br/>";
    echo "<b>Admins List</b><br/>";
    echo "</p>";
    //////ALL LISTS SCRIPT <<
    $noi = mysql_fetch_array(mysql_query("SELECT count(*) FROM fun_users WHERE perm='2'"));

    if($page=="" || $page<=0)$page=1;
    $num_items = $noi[0]; //changable
    $items_per_page= 10;
    $num_pages = ceil($num_items/$items_per_page);
    if(($page>$num_pages)&&$page!=1)$page= $num_pages;
    $limit_start = ($page-1)*$items_per_page;

    //changable sql

        $sql = "SELECT id, name FROM fun_users WHERE perm='2' ORDER BY name LIMIT $limit_start, $items_per_page";


    echo "<p>";
    $items = mysql_query($sql);
    echo mysql_error();
    if(mysql_num_rows($items)>0)
    {
    while ($item = mysql_fetch_array($items))
    {
        
      $lnk = "<a href=\"index.php?viewuser&amp;who=$item[0]\">$item[1]</a>";
      echo "$lnk<br/>";
    }
    }
    echo "</p>";
    echo "<p align=\"center\">";
    if($page>1)
    {
      $ppage = $page-1;
      echo "<a href=\"lists.php?admns&amp;page=$ppage&amp;view=$view\">&#171;PREV</a> ";
    }
    if($page<$num_pages)
    {
      $npage = $page+1;
      echo "<a href=\"lists.php?admns&amp;page=$npage&amp;view=$view\">Next&#187;</a>";
    }
    echo "<br/>$page/$num_pages<br/>";
    if($num_pages>2)
    {
      $rets = "<form action=\"lists.php\" method=\"get\">";
        $rets .= "Jump to page<input name=\"page\" format=\"*N\" size=\"3\"/>";
        $rets .= "<input type=\"submit\" value=\"GO\"/>";
        $rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
        $rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
        
        $rets .= "</form>";

        echo $rets;
    }
    echo "</p>";
  ////// UNTILL HERE >>
    echo "<p align=\"center\">";
    echo "<a href=\"index.php?stats\"><img src=\"images/stat.gif\" alt=\"*\"/>";
echo "Site Stats</a><br/>";
    echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
    
}

//////////////////////////////////judges

else if(isset($_GET['judg']))
{
    addonline(getuid_sid($sid),"Viewing Judges list","");
    
    echo "<p align=\"center\">";
    echo "<b>Battle board judges</b><br/>";
    echo "</p>";
    //////ALL LISTS SCRIPT <<
    $noi = mysql_fetch_array(mysql_query("SELECT count(*) FROM fun_judges"));

    if($page=="" || $page<=0)$page=1;
    $num_items = $noi[0]; //changable
    $items_per_page= 10;
    $num_pages = ceil($num_items/$items_per_page);
    if(($page>$num_pages)&&$page!=1)$page= $num_pages;
    $limit_start = ($page-1)*$items_per_page;

    //changable sql

        $sql = "SELECT uid FROM fun_judges LIMIT $limit_start, $items_per_page";


    echo "<p>";
    $items = mysql_query($sql);
    echo mysql_error();
    if(mysql_num_rows($items)>0)
    {
    while ($item = mysql_fetch_array($items))
    {

      $lnk = "<a href=\"index.php?viewuser&amp;who=$item[0]\">".getnick_uid($item[0])."</a>";
      echo "$lnk<br/>";
    }
    }
    echo "</p>";
    echo "<p align=\"center\">";
    if($page>1)
    {
      $ppage = $page-1;
      echo "<a href=\"lists.php?judg&amp;page=$ppage&amp;view=$view\">&#171;PREV</a> ";
    }
    if($page<$num_pages)
    {
      $npage = $page+1;
      echo "<a href=\"lists.php?judg&amp;page=$npage&amp;view=$view\">Next&#187;</a>";
    }
    echo "<br/>$page/$num_pages<br/>";
    if($num_pages>2)
    {
      $rets = "<form action=\"lists.php\" method=\"get\">";
        $rets .= "Jump to page<input name=\"page\" format=\"*N\" size=\"3\"/>";
        $rets .= "<input type=\"submit\" value=\"GO\"/>";
        $rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
        $rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
        
        $rets .= "</form>";

        echo $rets;
    }
    echo "</p>";
  ////// UNTILL HERE >>
    echo "<p align=\"center\">";
    echo "<a href=\"index.php?stats\"><img src=\"images/stat.gif\" alt=\"*\"/>";
echo "Site Stats</a><br/>";
    echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
    
}

//////////////////////////////////Staff

else if(isset($_GET['modr']))
{
    addonline(getuid_sid($sid),"Viewing Mods list","");
    
    echo "<p align=\"center\">";
    echo "<img src=\"smilies/order.gif\" alt=\"*\"/><br/>";
    echo "<b>Moderators List</b><br/>";
    echo "</p>";
    //////ALL LISTS SCRIPT <<
    $noi = mysql_fetch_array(mysql_query("SELECT count(*) FROM fun_users WHERE perm='1'"));

    if($page=="" || $page<=0)$page=1;
    $num_items = $noi[0]; //changable
    $items_per_page= 10;
    $num_pages = ceil($num_items/$items_per_page);
    if(($page>$num_pages)&&$page!=1)$page= $num_pages;
    $limit_start = ($page-1)*$items_per_page;

    //changable sql

        $sql = "SELECT id, name FROM fun_users WHERE perm='1' ORDER BY name LIMIT $limit_start, $items_per_page";


    echo "<p>";
    $items = mysql_query($sql);
    echo mysql_error();
    if(mysql_num_rows($items)>0)
    {
    while ($item = mysql_fetch_array($items))
    {

      $lnk = "<a href=\"index.php?viewuser&amp;who=$item[0]\">$item[1]</a>";
      echo "$lnk<br/>";
    }
    }
    echo "</p>";
    echo "<p align=\"center\">";
    if($page>1)
    {
      $ppage = $page-1;
      echo "<a href=\"lists.php?modr&amp;page=$ppage&amp;view=$view\">&#171;PREV</a> ";
    }
    if($page<$num_pages)
    {
      $npage = $page+1;
      echo "<a href=\"lists.php?modr&amp;page=$npage&amp;view=$view\">Next&#187;</a>";
    }
    echo "<br/>$page/$num_pages<br/>";
    if($num_pages>2)
    {
      $rets = "<form action=\"lists.php\" method=\"get\">";
        $rets .= "Jump to page<input name=\"page\" format=\"*N\" size=\"3\"/>";
        $rets .= "<input type=\"submit\" value=\"GO\"/>";
        $rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
        $rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
        
        $rets .= "</form>";

        echo $rets;
    }
    echo "</p>";
  ////// UNTILL HERE >>
    echo "<p align=\"center\">";
    echo "<a href=\"index.php?stats\"><img src=\"images/stat.gif\" alt=\"*\"/>";
echo "Site Stats</a><br/>";
    echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
    
}


//////////////////////////////////Top Posters List

else if(isset($_GET['tpweek']))
{
    addonline(getuid_sid($sid),"Viewing Top Posters of the week","");
    
    echo "<p align=\"center\">";
    echo "Top Posters of The week<br/><small>Thank you, you brought the life to this site in the last 7 days</small>";
    echo "</p>";
    //////ALL LISTS SCRIPT <<

    if($page=="" || $page<=0)$page=1;
    $weekago = time();
    $weekago -= 7*24*60*60;
    $noi = mysql_fetch_array(mysql_query("SELECT COUNT(DISTINCT uid)  FROM fun_posts WHERE dtpost>'".$weekago."';"));
    $num_items = $noi[0]; //changable
    $items_per_page= 10;
    $num_pages = ceil($num_items/$items_per_page);
    if(($page>$num_pages)&&$page!=1)$page= $num_pages;
    $limit_start = ($page-1)*$items_per_page;

    //changable sql


        $sql = "SELECT uid, COUNT(*) as nops FROM fun_posts  WHERE dtpost>'".$weekago."'  GROUP BY uid ORDER BY nops DESC LIMIT $limit_start, $items_per_page";


    echo "<p>";
    $items = mysql_query($sql);
    echo mysql_error();
    if(mysql_num_rows($items)>0)
    {
    while ($item = mysql_fetch_array($items))
    {
        $unick = getnick_uid($item[0]);
      $lnk = "<a href=\"index.php?viewuser&amp;who=$item[0]\">$unick</a> <small>Posts: $item[1]</small>";
      echo "$lnk<br/>";
    }
    }
    echo "</p>";
    echo "<p align=\"center\">";
    if($page>1)
    {
      $ppage = $page-1;
      echo "<a href=\"lists.php?tpweek&amp;page=$ppage&amp;view=$view\">&#171;PREV</a> ";
    }
    if($page<$num_pages)
    {
      $npage = $page+1;
      echo "<a href=\"lists.php?tpweek&amp;page=$npage&amp;view=$view\">Next&#187;</a>";
    }
    echo "<br/>$page/$num_pages<br/>";
    if($num_pages>2)
    {
  $rets = "<form action=\"lists.php\" method=\"get\">";
        $rets .= "Jump to page<input name=\"page\" format=\"*N\" size=\"3\"/>";
        $rets .= "<input type=\"submit\" value=\"GO\"/>";
        $rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
        $rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
        
        $rets .= "</form>";

        echo $rets;
    }
    echo "</p>";
  ////// UNTILL HERE >>
    echo "<p align=\"center\">";
    echo "<a href=\"index.php?stats\"><img src=\"images/stat.gif\" alt=\"*\"/>";
echo "Site Stats</a><br/>";
    echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
    
}

//////////////////////////////////Top Posters List

else if(isset($_GET['tptime']))
{
    addonline(getuid_sid($sid),"Viewing Overall Top Posters ","");
    
    echo "<p align=\"center\">";
    echo "Top Posters of all the time";
    echo "</p>";
    //////ALL LISTS SCRIPT <<

    if($page=="" || $page<=0)$page=1;
    
    $noi = mysql_fetch_array(mysql_query("SELECT COUNT(DISTINCT uid)  FROM fun_posts ;"));
    $num_items = $noi[0]; //changable
    $items_per_page= 10;
    $num_pages = ceil($num_items/$items_per_page);
    if(($page>$num_pages)&&$page!=1)$page= $num_pages;
    $limit_start = ($page-1)*$items_per_page;

    //changable sql


        $sql = "SELECT uid, COUNT(*) as nops FROM fun_posts   GROUP BY uid ORDER BY nops DESC LIMIT $limit_start, $items_per_page";


    echo "<p>";
    $items = mysql_query($sql);
    echo mysql_error();
    if(mysql_num_rows($items)>0)
    {
    while ($item = mysql_fetch_array($items))
    {
        $unick = getnick_uid($item[0]);
      $lnk = "<a href=\"index.php?viewuser&amp;who=$item[0]\">$unick</a> <small>Posts: $item[1]</small>";
      echo "$lnk<br/>";
    }
    }
    echo "</p>";
    echo "<p align=\"center\">";
    if($page>1)
    {
      $ppage = $page-1;
      echo "<a href=\"lists.php?tptime&amp;page=$ppage&amp;view=$view\">&#171;PREV</a> ";
    }
    if($page<$num_pages)
    {
      $npage = $page+1;
      echo "<a href=\"lists.php?tptime&amp;page=$npage&amp;view=$view\">Next&#187;</a>";
    }
    echo "<br/>$page/$num_pages<br/>";
    if($num_pages>2)
    {
      $rets = "<form action=\"lists.php\" method=\"get\">";
        $rets .= "Jump to page<input name=\"page\" format=\"*N\" size=\"3\"/>";
        $rets .= "<input type=\"submit\" value=\"GO\"/>";
        $rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
        $rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
        
        $rets .= "</form>";

        echo $rets;
    }
    echo "</p>";
  ////// UNTILL HERE >>
    echo "<p align=\"center\">";
    echo "<a href=\"index.php?stats\"><img src=\"images/stat.gif\" alt=\"*\"/>";
echo "Site Stats</a><br/>";
    echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
    
}


//////////////////////////////////Males List

else if(isset($_GET['males']))
{
    addonline(getuid_sid($sid),"I Need A Man","");

    
    echo "<p align=\"center\">";
    echo "<img src=\"images/male.gif\" alt=\"*\"/><br/>";
    echo "<b>Males</b>";
    echo "</p>";
    //////ALL LISTS SCRIPT <<
    $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_users WHERE sex='M'"));
    if($page=="" || $page<=0)$page=1;
    $num_items = $noi[0]; //changable
    $items_per_page= 10;
    $num_pages = ceil($num_items/$items_per_page);
    if(($page>$num_pages)&&$page!=1)$page= $num_pages;
    $limit_start = ($page-1)*$items_per_page;

    //changable sql
        $sql = "SELECT id, name, birthday FROM fun_users WHERE sex='M' ORDER BY name LIMIT $limit_start, $items_per_page";


    echo "<p>";
    $items = mysql_query($sql);
    echo mysql_error();
    if(mysql_num_rows($items)>0)
    {
    while ($item = mysql_fetch_array($items))
    {
      $uage = getage($item[2]);
      $lnk = "<a href=\"index.php?viewuser&amp;who=$item[0]\">$item[1]</a> <small>Age: $uage</small>";
      echo "$lnk<br/>";
    }
    }
    echo "</p>";
    echo "<p align=\"center\">";
    if($page>1)
    {
      $ppage = $page-1;
      echo "<a href=\"lists.php?males&amp;page=$ppage\">&#171;PREV</a> ";
    }
    if($page<$num_pages)
    {
      $npage = $page+1;
      echo "<a href=\"lists.php?males&amp;page=$npage\">Next&#187;</a>";
    }
    echo "<br/>$page/$num_pages<br/>";
    if($num_pages>2)
    {
        $rets = "<form action=\"lists.php\" method=\"get\">";
      $rets .= "Jump to page<input name=\"page\" format=\"*N\" size=\"3\"/>";
        $rets .= "<input type=\"submit\" value=\"GO\"/>";
        $rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
        $rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
       

 $rets .= "</form>";

        echo $rets;
    }
    echo "</p>";
  ////// UNTILL HERE >>
    echo "<p align=\"center\">";
    echo "<a href=\"index.php?stats\"><img src=\"images/stat.gif\" alt=\"*\"/>";
echo "Site Stats</a><br/>";
    echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
    
}

//////////////////////////////////Males List

else if(isset($_GET['fems']))
{
    addonline(getuid_sid($sid),"I Need A Woman","");

    
    echo "<p align=\"center\">";
    echo "<img src=\"images/female.gif\" alt=\"*\"/><br/>";
    echo "<b>Females</b>";
    echo "</p>";
    //////ALL LISTS SCRIPT <<
    $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_users WHERE sex='F'"));
    if($page=="" || $page<=0)$page=1;
    $num_items = $noi[0]; //changable
    $items_per_page= 10;
    $num_pages = ceil($num_items/$items_per_page);
    if(($page>$num_pages)&&$page!=1)$page= $num_pages;
    $limit_start = ($page-1)*$items_per_page;

    //changable sql
        $sql = "SELECT id, name, birthday FROM fun_users WHERE sex='F' ORDER BY name LIMIT $limit_start, $items_per_page";


    echo "<p>";
    $items = mysql_query($sql);
    echo mysql_error();
    if(mysql_num_rows($items)>0)
    {
    while ($item = mysql_fetch_array($items))
    {
      $uage = getage($item[2]);
      $lnk = "<a href=\"index.php?viewuser&amp;who=$item[0]\">$item[1]</a> <small>Age: $uage</small>";
      echo "$lnk<br/>";
    }
    }
    echo "</p>";
    echo "<p align=\"center\">";
    if($page>1)
    {
      $ppage = $page-1;
      echo "<a href=\"lists.php?fems&amp;page=$ppage\">&#171;PREV</a> ";
    }
    if($page<$num_pages)
    {
      $npage = $page+1;
      echo "<a href=\"lists.php?fems&amp;page=$npage\">Next&#187;</a>";
    }
    echo "<br/>$page/$num_pages<br/>";
    if($num_pages>2)
    {
  $rets = "<form action=\"lists.php\" method=\"get\">";
      $rets .= "Jump to page<input name=\"page\" format=\"*N\" size=\"3\"/>";
        $rets .= "<input type=\"submit\" value=\"GO\"/>";
        $rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
        $rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
        
        $rets .= "</form>";

        echo $rets;
    }
    echo "</p>";
  ////// UNTILL HERE >>
    echo "<p align=\"center\">";
    echo "<a href=\"index.php?stats\"><img src=\"images/stat.gif\" alt=\"*\"/>";
echo "Site Stats</a><br/>";
    echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
    
}

//////////////////////////////////Today's Birthday'

else if(isset($_GET['bdy']))
{
    addonline(getuid_sid($sid),"Viewing Birthday List ","");

    
    echo "<p align=\"center\">";
    echo "<img src=\"images/cake.gif\" alt=\"*\"/><br/>";
    echo "Happy Birthday to:";
    echo "</p>";
    //////ALL LISTS SCRIPT <<
    $noi =mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_users where month(`birthday`) = month(curdate()) and dayofmonth(`birthday`) = dayofmonth(curdate());"));
    if($page=="" || $page<=0)$page=1;
    $num_items = $noi[0]; //changable
    $items_per_page= 10;
    $num_pages = ceil($num_items/$items_per_page);
    if(($page>$num_pages)&&$page!=1)$page= $num_pages;
    $limit_start = ($page-1)*$items_per_page;

    //changable sql
        $sql = "SELECT id, name, birthday  FROM fun_users where month(`birthday`) = month(curdate()) and dayofmonth(`birthday`) = dayofmonth(curdate()) ORDER BY name LIMIT $limit_start, $items_per_page";


    echo "<p>";
    $items = mysql_query($sql);
    echo mysql_error();
    if(mysql_num_rows($items)>0)
    {
    while ($item = mysql_fetch_array($items))
    {
      $uage = getage($item[2]);
      $lnk = "<a href=\"index.php?viewuser&amp;who=$item[0]\">$item[1]</a> <small>Age: $uage</small>";
      echo "$lnk<br/>";
    }
    }
    echo "</p>";
    echo "<p align=\"center\">";
    if($page>1)
    {
      $ppage = $page-1;
      echo "<a href=\"lists.php?bdy&amp;page=$ppage\">&#171;PREV</a> ";
    }
    if($page<$num_pages)
    {
      $npage = $page+1;
      echo "<a href=\"lists.php?bdy&amp;page=$npage\">Next&#187;</a>";
    }
    echo "<br/>$page/$num_pages<br/>";
    if($num_pages>2)
    {
 $rets = "<form action=\"lists.php\" method=\"get\">";
      $rets .= "Jump to page<input name=\"page\" format=\"*N\" size=\"3\"/>";
        $rets .= "<input type=\"submit\" value=\"GO\"/>";
         $rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
         $rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";

 $rets .= "</form>";

        echo $rets;
    }
    echo "</p>";
  ////// UNTILL HERE >>
    echo "<p align=\"center\">";
    echo "<a href=\"index.php?stats\"><img src=\"images/stat.gif\" alt=\"*\"/>";
echo "Site Stats</a><br/>";
    echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
    
}


//////////////////////////////////Browsers

else if(isset($_GET['brows']))
{
    addonline(getuid_sid($sid),"Viewing Browsers List","");

    
    echo "<p align=\"center\">";
    echo "<b>browsers List</b>";
    echo "</p>";
    //////ALL LISTS SCRIPT <<
    $noi=mysql_fetch_array(mysql_query("SELECT COUNT(DISTINCT browserm) FROM fun_users WHERE browserm IS NOT NULL "));
    if($page=="" || $page<=0)$page=1;
    $num_items = $noi[0]; //changable
    $items_per_page= 10;
    $num_pages = ceil($num_items/$items_per_page);
    if(($page>$num_pages)&&$page!=1)$page= $num_pages;
    $limit_start = ($page-1)*$items_per_page;

    //changable sql
        $sql = "SELECT browserm, COUNT(*) as notl FROM fun_users    WHERE browserm!='' GROUP BY browserm ORDER BY notl DESC LIMIT $limit_start, $items_per_page";
//$moderatorz=mysql_query("SELECT tlphone, COUNT(*) as notl FROM users GROUP BY tlphone ORDER BY notl DESC LIMIT  ".$pagest.",5");
    $cou = $limit_start;
    echo "<p>";
    $items = mysql_query($sql);
    echo mysql_error();
    if(mysql_num_rows($items)>0)
    {

    while ($item = mysql_fetch_array($items))
    {
      $cou++;
      $lnk = "$cou-$item[0] <b>$item[1]</b>";
      echo "$lnk<br/>";
    }
    }
    echo "</p>";
    echo "<p align=\"center\">";
    if($page>1)
    {
      $ppage = $page-1;
      echo "<a href=\"lists.php?brows&amp;page=$ppage\">&#171;PREV</a> ";
    }
    if($page<$num_pages)
    {
      $npage = $page+1;
      echo "<a href=\"lists.php?brows&amp;page=$npage\">Next&#187;</a>";
    }
    echo "<br/>$page/$num_pages<br/>";
    if($num_pages>2)
    {
       $rets = "<form action=\"lists.php\" method=\"get\">";
      $rets .= "Jump to page<input name=\"page\" format=\"*N\" size=\"3\"/>";
        $rets .= "<input type=\"submit\" value=\"GO\"/>";
         $rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
         $rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
        

$rets .= "</form>";

        echo $rets;
    }
    echo "</p>";
  ////// UNTILL HERE >>
    echo "<p align=\"center\">";
    echo "<a href=\"index.php?stats\"><img src=\"images/stat.gif\" alt=\"*\"/>";
echo "Site Stats</a><br/>";
    echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
    
}
	echo "</body>";
	echo "</html>";
?>
