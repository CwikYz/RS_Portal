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

    if(islogged($sid)==false)
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
if(isset($_GET['tpc']))
{
    addonline(getuid_sid($sid),"Topics search","");
    
    echo "<p>";
echo "<form action=\"search.php?stpc\" method=\"post\">";
    echo "Text: <input name=\"stext\" maxlength=\"30\"/><br/>";
    echo "In: <select name=\"sin\">";
    echo "<option value=\"1\">Topic Posts</option>";
    echo "<option value=\"2\">Topic Text</option>";
    echo "<option value=\"3\">Topic Name</option>";
    echo "</select><br/>";
    echo "Order: <select name=\"sor\">";
    echo "<option value=\"1\">Newest First</option>";
    echo "<option value=\"2\">Oldest First</option>";
    echo "</select><br/>";
echo "<input type=\"submit\" value=\"Search\"/>";
  echo "</form>";
    echo "</p>";
  echo "<p align=\"center\">";
  echo "<a href=\"index.php?search\"><img src=\"images/search.gif\" alt=\"*\"/>";
echo "Search Menu</a><br/>";
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
}

else if(isset($_GET['blg']))
{
    addonline(getuid_sid($sid),"Blogs search","");
    
    echo "<p>";
echo "<form action=\"search.php?sblg\" method=\"post\">";
    echo "Text: <input name=\"stext\" maxlength=\"30\"/><br/>";
    echo "In: <select name=\"sin\">";
    echo "<option value=\"1\">Blog Text</option>";
    echo "<option value=\"2\">Blog Name</option>";
    echo "</select><br/>";
    echo "Order: <select name=\"sor\">";
    echo "<option value=\"1\">Blog Name</option>";
    echo "<option value=\"2\">Time</option>";
    echo "</select><br/>";
echo "<input type=\"submit\" value=\"Search\"/>";
    echo "</form>";
    echo "</p>";
  echo "<p align=\"center\">";
  echo "<a href=\"index.php?search\"><img src=\"images/search.gif\" alt=\"*\"/>";
echo "Search Menu</a><br/>";
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
}

else if(isset($_GET['clb']))
{
    addonline(getuid_sid($sid),"Clubs search","");
    
    echo "<p>";
echo "<form action=\"search.php?sclb\" method=\"post\">";
    echo "Text: <input name=\"stext\" maxlength=\"30\"/><br/>";
    echo "In: <select name=\"sin\">";
    echo "<option value=\"1\">Club Description</option>";
    echo "<option value=\"2\">Club Name</option>";
    echo "</select><br/>";
    echo "Order: <select name=\"sor\">";
    echo "<option value=\"1\">Club Name</option>";
    echo "<option value=\"2\">Oldest</option>";
    echo "<option value=\"3\">Newest</option>";
    echo "</select><br/>";
    
    
echo "<input type=\"submit\" value=\"Search\"/>";
    echo "</form>";

    echo "</p>";
  echo "<p align=\"center\">";
  echo "<a href=\"index.php?search\"><img src=\"images/search.gif\" alt=\"*\"/>";
echo "Search Menu</a><br/>";
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
}

else if(isset($_GET['nbx']))
{
    addonline(getuid_sid($sid),"Inbox search","");
    
    echo "<p>";
echo "<form action=\"search.php?snbx\" method=\"post\">";
    echo "Text: <input name=\"stext\" maxlength=\"30\"/><br/>";
    echo "In: <select name=\"sin\">";
    echo "<option value=\"1\">Recieved Messages</option>";
	echo "<option value=\"2\">Sent Messages</option>";
    echo "<option value=\"3\">Sender Name</option>";
    echo "</select><br/>";
    echo "Order: <select name=\"sor\">";
    echo "<option value=\"1\">Newest PMs</option>";
    echo "<option value=\"2\">Oldest PMs</option>";
    echo "<option value=\"2\">Sender Name</option>";
    echo "</select><br/>";
    
    
echo "<input type=\"submit\" value=\"Search\"/>";
    echo "</form>";
    echo "</p>";
  echo "<p align=\"center\">";
  echo "<a href=\"index.php?search\"><img src=\"images/search.gif\" alt=\"*\"/>";
echo "Search Menu</a><br/>";
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
}

else if(isset($_GET['mbrn']))
{
    addonline(getuid_sid($sid),"Members search","");
    
    echo "<p>";
echo "<form action=\"search.php?smbr\" method=\"post\">";
    echo "Nickname: <input name=\"stext\" maxlength=\"15\"/><br/>";
    echo "Order: <select name=\"sor\">";
    echo "<option value=\"1\">Member Name</option>";
    echo "<option value=\"2\">Last Active</option>";
    echo "<option value=\"3\">Join Date</option>";
    echo "</select><br/>";
    echo "<input type=\"submit\" value=\"Search\"/>";
    echo "</form>";
    echo "</p>";
  echo "<p align=\"center\">";
  echo "<a href=\"index.php?search\"><img src=\"images/search.gif\" alt=\"*\"/>";
echo "Search Menu</a><br/>";
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
}

else if(isset($_GET['stpc']))
{
  $stext = $_POST["stext"];
  $sin = $_POST["sin"];
  $sor = $_POST["sor"];
    addonline(getuid_sid($sid),"Topics search","");
    
    echo "<p>";

        if(trim($stext)=="")
        {
            echo "<br/>Please Specify the text to search for";
        }else{
          //begin search
          if($page=="" || $page<1)$page=1;
          if($sin=="1")
          {
            $where_table = "fun_posts";
            $cond = "text";
            $select_fields = "id, tid";
            if($sor=="1")
            {
              $ord_fields = "dtpost DESC";
            }else{
                $ord_fields = "dtpost";
            }
          }else if($sin=="2")
          {
            $where_table = "fun_topics";
            $cond = "text";
            $select_fields = "name, id";
            if($sor=="1")
            {
              $ord_fields = "crdate DESC";
            }else{
                $ord_fields = "crdate";
            }
          }else if($sin=="3")
          {
            $where_table = "fun_topics";
            $cond = "name";
            $select_fields = "name, id";
            if($sor=="1")
            {
              $ord_fields = "crdate DESC";
            }else{
                $ord_fields = "crdate";
            }
          }
          $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM ".$where_table." WHERE ".$cond." LIKE '%".$stext."%'"));
          $num_items = $noi[0];
          $items_per_page = 10;
          $num_pages = ceil($num_items/$items_per_page);
    if(($page>$num_pages)&&$page!=1)$page= $num_pages;
    $limit_start = ($page-1)*$items_per_page;
    
    $sql = "SELECT ".$select_fields." FROM ".$where_table." WHERE ".$cond." LIKE '%".$stext."%' ORDER BY ".$ord_fields." LIMIT $limit_start, $items_per_page";
          $items = mysql_query($sql);
          while($item=mysql_fetch_array($items))
          {
            if($sin=="1")
            {
              $tname = htmlspecialchars(gettname($item[1]));
			  
              if($tname=="" || !canaccess(getuid_sid($sid),getfid_tid($item[1]))){
                $tlink = "Unreachable<br/>";
              }else{
              $tlink = "<a href=\"index.php?viewtpc&amp;tid=$item[1]&amp;go=$item[0]\">".$tname."</a><br/>";
              }
                echo  $tlink;
            }
            else
            {
              $tname = htmlspecialchars($item[0]);
              if($tname=="" || !canaccess(getuid_sid($sid),getfid_tid($item[1]))){
                $tlink = "Unreachable<br/>";
              }else{
              $tlink = "<a href=\"index.php?viewtpc&amp;tid=$item[1]\">".$tname."</a><br/>";
              }
                echo  $tlink;
            }
          }
          echo "<p align=\"center\">";
    if($page>1)
    {
      $ppage = $page-1;
 $rets = "<form action=\"search.php?$action&amp;page=$ppage\" method=\"post\">";
        $rets .= "<input type=\"submit\" value=\"Prev\"/>";
       
        $rets .= "<input type=\"hidden\" name=\"stext\" value=\"$stext\"/>";
        $rets .= "<input type=\"hidden\" name=\"sin\" value=\"$sin\"/>";
        $rets .= "<input type=\"hidden\" name=\"sor\" value=\"$sor\"/>";
        $rets .= "</form> ";

        echo $rets;
      
    }
    if($page<$num_pages)
    {
      $npage = $page+1;
 $rets = "<form action=\"search.php?$action&amp;page=$ppage\" method=\"post\">";
        $rets .= "<input type=\"submit\" value=\"Next\"/>";
       
        $rets .= "<input type=\"hidden\" name=\"stext\" value=\"$stext\"/>";
        $rets .= "<input type=\"hidden\" name=\"sin\" value=\"$sin\"/>";
        $rets .= "<input type=\"hidden\" name=\"sor\" value=\"$sor\"/>";
        $rets .= "</form> ";

        echo $rets;
      
    }
    echo "<br/>$page/$num_pages<br/>";
    if($num_pages>2)
    {

        $rets = "<form action=\"search.php?$action&amp;page=$page\" method=\"post\">";
      $rets .= "Jump to page<input name=\"page\" format=\"*N\" size=\"3\"/>";
        $rets .= "<input type=\"submit\" value=\"GO\"/>";
        $rets .= "<input type=\"hidden\" name=\"stext\" value=\"$stext\"/>";
        $rets .= "<input type=\"hidden\" name=\"sin\" value=\"$sin\"/>";
        $rets .= "<input type=\"hidden\" name=\"sor\" value=\"$sor\"/>";
        $rets .= "</form>";

        echo $rets;
    }
    echo "</p>";
        }
    
echo "</p>";
  echo "<p align=\"center\">";
  echo "<a href=\"index.php?search\"><img src=\"images/search.gif\" alt=\"*\"/>";
echo "Search Menu</a><br/>";
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
}

else if(isset($_GET['sblg']))
{
  $stext = $_POST["stext"];
  $sin = $_POST["sin"];
  $sor = $_POST["sor"];
    addonline(getuid_sid($sid),"Blogs search","");
    
    echo "<p>";

    
    
        if(trim($stext)=="")
        {
            echo "<br/>Failed to search for blogs";
        }else{
          //begin search
          if($page=="" || $page<1)$page=1;
          if($sin=="1")
          {
            $where_table = "fun_blogs";
            $cond = "btext";
            $select_fields = "id, bname";
            if($sor=="1")
            {
              $ord_fields = "bname";
            }else{
                $ord_fields = "bgdate DESC";
            }
          }else if($sin=="2")
          {
            $where_table = "fun_blogs";
            $cond = "bname";
            $select_fields = "id, bname";
            if($sor=="1")
            {
              $ord_fields = "bname";
            }else{
                $ord_fields = "bgdate DESC";
            }
          }
          $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM ".$where_table." WHERE ".$cond." LIKE '%".$stext."%'"));
          $num_items = $noi[0];
          $items_per_page = 10;
          $num_pages = ceil($num_items/$items_per_page);
    if(($page>$num_pages)&&$page!=1)$page= $num_pages;
    $limit_start = ($page-1)*$items_per_page;

    $sql = "SELECT ".$select_fields." FROM ".$where_table." WHERE ".$cond." LIKE '%".$stext."%' ORDER BY ".$ord_fields." LIMIT $limit_start, $items_per_page";
          $items = mysql_query($sql);
          while($item=mysql_fetch_array($items))
          {
              $tlink = "<a href=\"index.php?viewblog&amp;bid=$item[0]&amp;go=$item[0]\">".htmlspecialchars($item[1])."</a><br/>";

                echo  $tlink;
            
          }
          echo "<p align=\"center\">";
    if($page>1)
    {
      $ppage = $page-1;
 $rets = "<form action=\"search.php?$action&amp;page=$ppage\" method=\"post\">";
        $rets .= "<input type=\"submit\" value=\"Prev\"/>";
       
        $rets .= "<input type=\"hidden\" name=\"stext\" value=\"$stext\"/>";
        $rets .= "<input type=\"hidden\" name=\"sin\" value=\"$sin\"/>";
        $rets .= "<input type=\"hidden\" name=\"sor\" value=\"$sor\"/>";
        $rets .= "</form> ";

        echo $rets;
    }
    if($page<$num_pages)
    {
      $npage = $page+1;
 $rets = "<form action=\"search.php?$action&amp;page=$ppage\" method=\"post\">";
        $rets .= "<input type=\"submit\" value=\"Next\"/>";
       
        $rets .= "<input type=\"hidden\" name=\"stext\" value=\"$stext\"/>";
        $rets .= "<input type=\"hidden\" name=\"sin\" value=\"$sin\"/>";
        $rets .= "<input type=\"hidden\" name=\"sor\" value=\"$sor\"/>";
        $rets .= "</form> ";

        echo $rets;
    }
    echo "<br/>$page/$num_pages<br/>";
    if($num_pages>2)
    {

        $rets = "<form action=\"search.php?$action&amp;page=$page\" method=\"post\">";
      $rets .= "Jump to page<input name=\"page\" format=\"*N\" size=\"3\"/>";
        $rets .= "<input type=\"submit\" value=\"GO\"/>";
        $rets .= "<input type=\"hidden\" name=\"stext\" value=\"$stext\"/>";
        $rets .= "<input type=\"hidden\" name=\"sin\" value=\"$sin\"/>";
        $rets .= "<input type=\"hidden\" name=\"sor\" value=\"$sor\"/>";
        $rets .= "</form>";

        echo $rets;
    }
    echo "</p>";
        }
    
echo "</p>";
  echo "<p align=\"center\">";
  echo "<a href=\"index.php?search\"><img src=\"images/search.gif\" alt=\"*\"/>";
echo "Search Menu</a><br/>";
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
}

else if(isset($_GET['sclb']))
{
  $stext = $_POST["stext"];
  $sin = $_POST["sin"];
  $sor = $_POST["sor"];
    addonline(getuid_sid($sid),"Club search","");
    
    echo "<p>";

    
        if(trim($stext)=="")
        {
            echo "<br/>Failed to search for club";
        }else{
          //begin search
          if($page=="" || $page<1)$page=1;
          if($sin=="1")
          {
            $where_table = "fun_clubs";
            $cond = "description";
            $select_fields = "id, name";
            if($sor=="1")
            {
              $ord_fields = "name";
            }else if($sor=="2"){
                $ord_fields = "created";
            }else if($sor=="3"){
                $ord_fields = "created DESC";
            }
          }else if($sin=="2")
          {
            $where_table = "fun_clubs";
            $cond = "name";
            $select_fields = "id, name";
            if($sor=="1")
            {
              $ord_fields = "name";
            }else if($sor=="2"){
                $ord_fields = "created";
            }else if($sor=="3"){
                $ord_fields = "created DESC";
            }
          }
          $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM ".$where_table." WHERE ".$cond." LIKE '%".$stext."%'"));
          $num_items = $noi[0];
          $items_per_page = 10;
          $num_pages = ceil($num_items/$items_per_page);
    if(($page>$num_pages)&&$page!=1)$page= $num_pages;
    $limit_start = ($page-1)*$items_per_page;

    $sql = "SELECT ".$select_fields." FROM ".$where_table." WHERE ".$cond." LIKE '%".$stext."%' ORDER BY ".$ord_fields." LIMIT $limit_start, $items_per_page";
          $items = mysql_query($sql);
          while($item=mysql_fetch_array($items))
          {
              $tlink = "<a href=\"index.php?gocl&amp;clid=$item[0]&amp;go=$item[0]\">".htmlspecialchars($item[1])."</a><br/>";

                echo  $tlink;

          }
          echo "<p align=\"center\">";
		  if($page>1)
    {
      $ppage = $page-1;
       $rets = "<form action=\"search.php?$action&amp;page=$ppage\" method=\"post\">";
        $rets .= "<input type=\"submit\" value=\"Prev\"/>";
       
        $rets .= "<input type=\"hidden\" name=\"stext\" value=\"$stext\"/>";
        $rets .= "<input type=\"hidden\" name=\"sin\" value=\"$sin\"/>";
        $rets .= "<input type=\"hidden\" name=\"sor\" value=\"$sor\"/>";
        $rets .= "</form> ";

        echo $rets;
    }
    if($page<$num_pages)
    {
      $npage = $page+1;
 $rets = "<form action=\"search.php?$action&amp;page=$ppage\" method=\"post\">";
        $rets .= "<input type=\"submit\" value=\"Next\"/>";
       
        $rets .= "<input type=\"hidden\" name=\"stext\" value=\"$stext\"/>";
        $rets .= "<input type=\"hidden\" name=\"sin\" value=\"$sin\"/>";
        $rets .= "<input type=\"hidden\" name=\"sor\" value=\"$sor\"/>";
        $rets .= "</form> ";

        echo $rets;
    }
    echo "<br/>$page/$num_pages<br/>";
    if($num_pages>2)
    {

        $rets = "<form action=\"search.php?$action&amp;page=$page\" method=\"post\">";
      $rets .= "Jump to page<input name=\"page\" format=\"*N\" size=\"3\"/>";
        $rets .= "<input type=\"submit\" value=\"GO\"/>";
        $rets .= "<input type=\"hidden\" name=\"stext\" value=\"$stext\"/>";
        $rets .= "<input type=\"hidden\" name=\"sin\" value=\"$sin\"/>";
        $rets .= "<input type=\"hidden\" name=\"sor\" value=\"$sor\"/>";
        $rets .= "</form>";

        echo $rets;
    }
    echo "</p>";
        }
    
echo "</p>";
  echo "<p align=\"center\">";
  echo "<a href=\"index.php?search\"><img src=\"images/search.gif\" alt=\"*\"/>";
echo "Search Menu</a><br/>";
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
}

else if(isset($_GET['snbx']))
{
  $stext = $_POST["stext"];
  $sin = $_GET["sin"];
  $sor = $_GET["sor"];
    addonline(getuid_sid($sid),"Inbox search","");
    
    echo "<p>";

        $myid = getuid_sid($sid);
        if(trim($stext)=="")
        {
            echo "<br/>Failed to search for message";
        }else{
          //begin search
          if($page=="" || $page<1)$page=1;
          if($sin==1)
          {
          $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*)  FROM fun_private  WHERE text LIKE '%".$stext."%' AND touid='".$myid."'"));
		  }else if($sin==2)
		  {
			$noi = mysql_fetch_array(mysql_query("SELECT COUNT(*)  FROM fun_private  WHERE text LIKE '%".$stext."%' AND byuid='".$myid."'"));
          }else{
                $stext = getuid_nick($stext);
            $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*)  FROM fun_private  WHERE byuid ='".$stext."' AND touid='".$myid."'"));
          }
          $num_items = $noi[0];
          $items_per_page = 10;
          $num_pages = ceil($num_items/$items_per_page);
    if(($page>$num_pages)&&$page!=1)$page= $num_pages;
    $limit_start = ($page-1)*$items_per_page;
          
          if($sin=="1")
          {
            /*
            $where_table = "fun_blogs";
            $cond = "btext";
            $select_fields = "id, bname";*/
            
            if($sor=="1")
            {
              //$ord_fields = "bname";
              $sql = "SELECT
            a.name, b.id, b.byuid, b.unread, b.starred FROM fun_users a
            INNER JOIN fun_private b ON a.id = b.byuid
            WHERE b.touid='".$myid."' AND b.text like '%".$stext."%'
            ORDER BY b.timesent DESC
            LIMIT $limit_start, $items_per_page";
            //echo $sql;
            }else if($sor=="2"){
                $sql = "SELECT
            a.name, b.id, b.byuid, b.unread, b.starred FROM fun_users a
            INNER JOIN fun_private b ON a.id = b.byuid
            WHERE b.touid='".$myid."' AND b.text like '%".$stext."%'
            ORDER BY b.timesent 
            LIMIT $limit_start, $items_per_page";
            }else{
                $sql = "SELECT
            a.name, b.id, b.byuid, b.unread, b.starred FROM fun_users a
            INNER JOIN fun_private b ON a.id = b.byuid
            WHERE b.touid='".$myid."' AND b.text like '%".$stext."%'
            ORDER BY a.name
            LIMIT $limit_start, $items_per_page";
            }
          }
		  else if($sin=="2")
		  {
			if($sor=="1")
            {
              //$ord_fields = "bname";
              $sql = "SELECT
            a.name, b.id, b.touid, b.unread, b.starred FROM fun_users a
            INNER JOIN fun_private b ON a.id = b.byuid
            WHERE b.byuid='".$myid."' AND b.text like '%".$stext."%'
            ORDER BY b.timesent DESC
            LIMIT $limit_start, $items_per_page";
            //echo $sql;
            }else if($sor=="2"){
                $sql = "SELECT
            a.name, b.id, b.touid, b.unread, b.starred FROM fun_users a
            INNER JOIN fun_private b ON a.id = b.byuid
            WHERE b.byuid='".$myid."' AND b.text like '%".$stext."%'
            ORDER BY b.timesent 
            LIMIT $limit_start, $items_per_page";
            }else{
                $sql = "SELECT
            a.name, b.id, b.touid, b.unread, b.starred FROM fun_users a
            INNER JOIN fun_private b ON a.id = b.touid
            WHERE b.byuid='".$myid."' AND b.text like '%".$stext."%'
            ORDER BY a.name
            LIMIT $limit_start, $items_per_page";
            }
		  }
		  else if($sin=="3")
          {
            
            if($sor=="1")
            {
              //$ord_fields = "bname";
              $sql = "SELECT
            a.name, b.id, b.byuid, b.unread, b.starred FROM fun_users a
            INNER JOIN fun_private b ON a.id = b.byuid
            WHERE b.touid='".$myid."' AND b.byuid ='".$stext."'
            ORDER BY b.timesent DESC
            LIMIT $limit_start, $items_per_page";
            }else if($sor=="2"){
                $sql = "SELECT
            a.name, b.id, b.byuid, b.unread, b.starred FROM fun_users a
            INNER JOIN fun_private b ON a.id = b.byuid
            WHERE b.touid='".$myid."' AND b.byuid ='".$stext."'
            ORDER BY b.timesent
            LIMIT $limit_start, $items_per_page";
            }else{
                $sql = "SELECT
            a.name, b.id, b.byuid, b.unread, b.starred FROM fun_users a
            INNER JOIN fun_private b ON a.id = b.byuid
            WHERE b.touid='".$myid."' AND b.byuid ='".$stext."'
            ORDER BY a.name
            LIMIT $limit_start, $items_per_page";
            }
          }
          

          $items = mysql_query($sql);
          echo mysql_error();
          while($item=mysql_fetch_array($items))
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

      $lnk = "<a href=\"inbox.php?readpm&amp;pmid=$item[1]\">$iml ".getnick_uid($item[2])."</a>";
      echo "$lnk<br/>";

          }
          echo "<p align=\"center\">";
    if($page>1)
    {
      $ppage = $page-1;
      $rets = "<form action=\"search.php?$action&amp;page=$ppage\" method=\"post\">";
        $rets .= "<input type=\"submit\" value=\"Prev\"/>";
       
        $rets .= "<input type=\"hidden\" name=\"stext\" value=\"$stext\"/>";
        $rets .= "<input type=\"hidden\" name=\"sin\" value=\"$sin\"/>";
        $rets .= "<input type=\"hidden\" name=\"sor\" value=\"$sor\"/>";
        $rets .= "</form> ";

        echo $rets;
    }
    if($page<$num_pages)
    {
      $npage = $page+1;
       $rets = "<form action=\"search.php?$action&amp;page=$ppage\" method=\"post\">";
        $rets .= "<input type=\"submit\" value=\"Next\"/>";
       
        $rets .= "<input type=\"hidden\" name=\"stext\" value=\"$stext\"/>";
        $rets .= "<input type=\"hidden\" name=\"sin\" value=\"$sin\"/>";
        $rets .= "<input type=\"hidden\" name=\"sor\" value=\"$sor\"/>";
        $rets .= "</form> ";

        echo $rets;
    }
    echo "<br/>$page/$num_pages<br/>";
    if($num_pages>2)
    {
              $rets = "<form action=\"search.php?$action&amp;page=$page\" method=\"post\">";
      $rets .= "Jump to page<input name=\"page\" format=\"*N\" size=\"3\"/>";
        $rets .= "<input type=\"submit\" value=\"GO\"/>";
        $rets .= "<input type=\"hidden\" name=\"stext\" value=\"$stext\"/>";
        $rets .= "<input type=\"hidden\" name=\"sin\" value=\"$sin\"/>";
        $rets .= "<input type=\"hidden\" name=\"sor\" value=\"$sor\"/>";
        $rets .= "</form>";

        echo $rets;
    }
    echo "</p>";
        }
    
echo "</p>";
  echo "<p align=\"center\">";
  echo "<a href=\"index.php?search\"><img src=\"images/search.gif\" alt=\"*\"/>";
echo "Search Menu</a><br/>";
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
}

else if(isset($_GET['smbr']))
{
	$stext = $_POST["stext"];
  $sin = $_POST["sin"];
  $sor = $_POST["sor"];
    addonline(getuid_sid($sid),"Club search","");
    
    echo "<p>";

    
        if(trim($stext)=="")
        {
            echo "<br/>Failed to search for club";
        }else{
          //begin search
          if($page=="" || $page<1)$page=1;
          
            $where_table = "fun_users";
            $cond = "name";
            $select_fields = "id, name";
            if($sor=="")
            {
              $ord_fields = "name";
            }else if($sor=="2"){
                $ord_fields = "lastact DESC";
            }else if($sor=="3"){
                $ord_fields = "regdate";
            }
          
          $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM ".$where_table." WHERE ".$cond." LIKE '%".$stext."%'"));
          $num_items = $noi[0];
          $items_per_page = 10;
          $num_pages = ceil($num_items/$items_per_page);
    if(($page>$num_pages)&&$page!=1)$page= $num_pages;
    $limit_start = ($page-1)*$items_per_page;

    $sql = "SELECT ".$select_fields." FROM ".$where_table." WHERE ".$cond." LIKE '%".$stext."%' ORDER BY ".$ord_fields." LIMIT $limit_start, $items_per_page";
          $items = mysql_query($sql);
          while($item=mysql_fetch_array($items))
          {
              $tlink = "<a href=\"index.php?viewuser&amp;who=$item[0]\">".htmlspecialchars($item[1])."</a><br/>";

                echo  $tlink;

          }
          echo "<p align=\"center\">";
		  if($page>1)
    {
      $ppage = $page-1;
       $rets = "<form action=\"search.php?$action&amp;page=$ppage\" method=\"post\">";
        $rets .= "<input type=\"submit\" value=\"Prev\"/>";
       
        $rets .= "<input type=\"hidden\" name=\"stext\" value=\"$stext\"/>";
        $rets .= "<input type=\"hidden\" name=\"sin\" value=\"$sin\"/>";
        $rets .= "<input type=\"hidden\" name=\"sor\" value=\"$sor\"/>";
        $rets .= "</form> ";

        echo $rets;
    }
    if($page<$num_pages)
    {
      $npage = $page+1;
      $rets = "<form action=\"search.php?$action&amp;page=$ppage\" method=\"post\">";
        $rets .= "<input type=\"submit\" value=\"Next\"/>";
       
        $rets .= "<input type=\"hidden\" name=\"stext\" value=\"$stext\"/>";
        $rets .= "<input type=\"hidden\" name=\"sin\" value=\"$sin\"/>";
        $rets .= "<input type=\"hidden\" name=\"sor\" value=\"$sor\"/>";
        $rets .= "</form> ";

        echo $rets;
    }
    echo "<br/>$page/$num_pages<br/>";
    if($num_pages>2)
    {
        $rets = "<form action=\"search.php?$action&amp;page=$page\" method=\"post\">";
      $rets .= "Jump to page<input name=\"page\" format=\"*N\" size=\"3\"/>";
        $rets .= "<input type=\"submit\" value=\"GO\"/>";
        $rets .= "<input type=\"hidden\" name=\"stext\" value=\"$stext\"/>";
        $rets .= "<input type=\"hidden\" name=\"sin\" value=\"$sin\"/>";
        $rets .= "<input type=\"hidden\" name=\"sor\" value=\"$sor\"/>";
        $rets .= "</form>";

        echo $rets;
    }
    echo "</p>";
        }
    
echo "</p>";
  echo "<p align=\"center\">";
  echo "<a href=\"index.php?search\"><img src=\"images/search.gif\" alt=\"*\"/>";
echo "Search Menu</a><br/>";
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
}
  

else{
  addonline(getuid_sid($sid),"Lost in search lol","");
    
  echo "<p align=\"center\">";
  echo "I don't know how did you get into here, but there's nothing to show<br/><br/>";
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
}
	echo "</body>";
	echo "</html>";
?>
