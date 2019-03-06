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
if(!isadmin(getuid_sid($sid)))
  {
   echo vrhonline($sid,$uid);
    
      echo "<p align=\"center\">";
      echo "You are not an admin<br/>";
      echo "<br/>";
      echo "<a href=\"index.php\">Home</a>";
      echo "</p>";
  echo dnoonline($sid,$uid);
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
    addonline(getuid_sid($sid),"Admin CP","");
if(isset($_GET['general']))
{
    
   echo vrhonline($sid,$uid);
if(isadmin(getuid_sid($sid)))
  {
  
    $xtm = getsxtm();
    $paf = getpmaf();
    $fvw = getfview();
    $fmsg = htmlspecialchars(getfmsg());
    if(canreg())
    {
      $arv = "e";
    }else{
      $arv= "d";
    }
  
  
  echo "<p align=\"center\">";
  echo "<b>General Settings</b><br/>";
  echo "</p>";
  echo "<p>";
echo "<form action=\"admproc.php?general\" method=\"post\">";
  echo "Session Period: ";
  echo "<input name=\"sesp\" format=\"*N\" maxlength=\"3\" size=\"3\ value=\"$xtm\"/>";
  echo "<br/>PM Antiflood<input name=\"pmaf\" format=\"*N\" maxlength=\"3\" size=\"3\" value=\"$paf\"/>";
  echo "<br/>Forum Message: ";
  echo "<input name=\"fmsg\"  maxlength=\"255\" value=\"$fmsg\"/>";
  echo "<br/>Registration: ";
  echo "<select name=\"areg\" value=\"$arv\">";
  echo "<option value=\"e\">Enabled</option>";
  echo "<option value=\"d\">Disabled</option>";
  echo "</select><br/>";
  echo "View:";
  echo "<select name=\"fvw\" value=\"$fvw\">";
  //$vname[0]="Drop Menu";
  $vname[0]="Horizontal Links";
  $vname[1]="Nothing";
  for($i=0;$i<count($vname);$i++)
  {
    echo "<option value=\"$i\">$vname[$i]</option>";
  }
  
  echo "</select>";

echo "<input type=\"submit\" value=\"submit\"/>";
echo "</form>";
 
  echo "</p>";
  echo "<p align=\"center\">";
  echo "<a href=\"index.php?admincp\"><img src=\"images/admn.gif\" alt=\"*\"/>";
  echo "Admin CP</a>";
  }
  echo dnoonline($sid,$uid);
}
else if(isset($_GET['addperm']))
{
    
   echo vrhonline($sid,$uid);
if(isadmin(getuid_sid($sid)))
  {
    echo "<p align=\"center\">";
    echo "<b>Add permission</b>";
    $forums = mysql_query("SELECT id, name FROM fun_forums ORDER BY position, id, name");
echo "<form action=\"admproc.php?addperm\" method=\"post\">";
    echo "<br/><br/>Forum: <select name=\"fid\">";
    while ($forum=mysql_fetch_array($forums))
    {
        echo "<option value=\"$forum[0]\">$forum[1]</option>";
    }
    echo "</select>";
    $forums = mysql_query("SELECT id, name FROM fun_groups ORDER BY  name, id");
    echo "<br/>UGroups: <select name=\"gid\">";
    while ($forum=mysql_fetch_array($forums))
    {
        echo "<option value=\"$forum[0]\">$forum[1]</option>";
    }
    echo "</select>";
echo "<input type=\"submit\" value=\"Submit\"/>";
echo "</form>";
    
    echo "<br/><br/><a href=\"index.php?admincp\"><img src=\"images/admn.gif\" alt=\"*\"/>";
  echo "Admin CP</a><br/>";
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
  echo "Home</a>";
  echo "</p>";
    }
  echo dnoonline($sid,$uid);
}

else if(isset($_GET['fcats']))
{
    
   echo vrhonline($sid,$uid);
if(isadmin(getuid_sid($sid)))
  {
    echo "<p>";
    echo "<a href=\"admincp.php?addcat\">&#187;Add Category</a><br/>";
    echo "<a href=\"admincp.php?edtcat\">&#187;Edit Category</a><br/>";
    echo "<a href=\"admincp.php?delcat\">&#187;Delete Category</a><br/>";
    echo "</p>";
    echo "<p align=\"center\">";
    echo "<a href=\"index.php?admincp\"><img src=\"images/admn.gif\" alt=\"*\"/>";
  echo "Admin CP</a><br/>";
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
  echo "Home</a>";
  echo "</p>";
    }
  echo dnoonline($sid,$uid);
}

else if(isset($_GET['club']))
{

   echo vrhonline($sid,$uid);
if(isadmin(getuid_sid($sid)))
  {
	$clid = $_GET["clid"];
    
    echo "<p>";
    echo "<a href=\"admincp.php?gccp&amp;clid=$clid\">&#187;Give Credit Plusses</a><br/>";
    echo "<a href=\"admproc.php?delclub&amp;clid=$clid\">&#187;Delete Club</a><br/>";
    echo "</p>";
    echo "<p align=\"center\">";
    echo "<a href=\"index.php?admincp\"><img src=\"images/admn.gif\" alt=\"*\"/>";
  echo "Admin CP</a><br/>";
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
  echo "Home</a>";
  echo "</p>";
    }
  echo dnoonline($sid,$uid);
}

else if(isset($_GET['manrss']))
{
    
   echo vrhonline($sid,$uid);
if(isadmin(getuid_sid($sid)))
  {
    echo "<p>";
    echo "<a href=\"admincp.php?addrss\">&#187;Add Source</a><br/>";
    $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_rss"));
    if($noi[0]>0)
    {
        $rss = mysql_query("SELECT title, id FROM fun_rss");

echo "<form action=\"admincp.php?edtrss\" method=\"post\">";
        echo "<br/><select name=\"rssid\">";
        while($rs=mysql_fetch_array($rss))
        {
            echo "<option value=\"$rs[1]\">$rs[0]</option>";
        }
      echo "</select><br/>";
echo "<input type=\"submit\" value=\"Edit\"/>";
echo "<br/>";
echo "</form>";

}
$noe = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_rss"));
    if($noe[0]>0)
    {
        $rss1 = mysql_query("SELECT title, id FROM fun_rss");

echo "<form action=\"admproc.php?delrss\" method=\"post\">";
        echo "<br/><select name=\"rssid\">";
        while($rs1=mysql_fetch_array($rss1))
        {
            echo "<option value=\"$rs1[1]\">$rs1[0]</option>";
        }
      echo "</select><br/>";
echo "<input type=\"submit\" value=\"Delete\"/>";
echo "<br/>";
echo "</form>";

    
    }
    echo "</p>";
    echo "<p align=\"center\">";
    echo "<a href=\"index.php?admincp\"><img src=\"images/admn.gif\" alt=\"*\"/>";
  echo "Admin CP</a><br/>";
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
  echo "Home</a>";
  echo "</p>";
    }
  echo dnoonline($sid,$uid);
}

else if(isset($_GET['chrooms']))
{
   echo vrhonline($sid,$uid);
    
if(isadmin(getuid_sid($sid)))
  {
    echo "<p>";
    echo "<a href=\"admincp.php?addchr\">&#187;Add Room</a><br/>";
    $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_rooms"));
    if($noi[0]>0)
    {
echo "<form action=\"admproc.php?delchr\" method=\"post\">";
        $rss = mysql_query("SELECT name, id FROM fun_rooms");
        echo "<br/><select name=\"chrid\">";
        while($rs=mysql_fetch_array($rss))
        {
            echo "<option value=\"$rs[1]\">$rs[0]</option>";
        }
      echo "</select><br/>";
echo "<input type=\"submit\" value=\"Delete\"/>";
echo "</form>";
    echo "<br/>";
    }
    echo "</p>";
    echo "<p align=\"center\">";
    echo "<a href=\"index.php?admincp\"><img src=\"images/admn.gif\" alt=\"*\"/>";
  echo "Admin CP</a><br/>";
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
  echo "Home</a>";
  echo "</p>";
    }
  echo dnoonline($sid,$uid);
}

else if(isset($_GET['forums']))
{
   echo vrhonline($sid,$uid);
    
if(isadmin(getuid_sid($sid)))
  {
    echo "<p>";
    echo "<a href=\"admincp.php?addfrm\">&#187;Add Forum</a><br/>";
    echo "<a href=\"admincp.php?edtfrm\">&#187;Edit Forum</a><br/>";
    echo "<a href=\"admincp.php?delfrm\">&#187;Delete Forum</a><br/>";
    echo "</p>";
    echo "<p align=\"center\">";
    echo "<a href=\"index.php?admincp\"><img src=\"images/admn.gif\" alt=\"*\"/>";
  echo "Admin CP</a><br/>";
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
  echo "Home</a>";
  echo "</p>";
    }
  echo dnoonline($sid,$uid);
}

else if(isset($_GET['clrdta']))
{

   echo vrhonline($sid,$uid);
if(isadmin(getuid_sid($sid)))
  {
    echo "<p>";
    echo "<a href=\"admproc.php?delpms\">&#187;Deleted PMs</a><br/>";
    echo "<a href=\"admproc.php?clrmlog\">&#187;Clear ModLog</a><br/>";
    echo "<a href=\"admproc.php?delsht\">&#187;Delete Old Shouts</a><br/>";
    echo "</p>";
    echo "<p align=\"center\">";
    echo "<a href=\"index.php?admincp\"><img src=\"images/admn.gif\" alt=\"*\"/>";
  echo "Admin CP</a><br/>";
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
  echo "Home</a>";
  echo "</p>";
    }
  echo dnoonline($sid,$uid);
}
else if(isset($_GET['ugroups']))
{
    
   echo vrhonline($sid,$uid);
if(isadmin(getuid_sid($sid)))
  {
    echo "<p>";
    echo "<a href=\"admincp.php?addgrp\">&#187;Add User Group</a><br/>";
    //echo "<a href=\"admincp.php?edtgrp\">&#187;Edit User group</a><br/>";
    echo "<a href=\"admincp.php?delgrp\">&#187;Delete User group</a><br/>";
    echo "</p>";
    echo "<p align=\"center\">";
    echo "<a href=\"index.php?admincp\"><img src=\"images/admn.gif\" alt=\"*\"/>";
  echo "Admin CP</a><br/>";
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
  echo "Home</a>";
  echo "</p>";
    }
  echo dnoonline($sid,$uid);
}
else if(isset($_GET['addcat']))
{
    
   echo vrhonline($sid,$uid);
if(isadmin(getuid_sid($sid)))
  {
    echo "<p align=\"center\">";
    echo "<b>Add Category</b><br/><br/>";
	echo "<form action=\"admproc.php?addcat\" method=\"post\">";
    echo "Name:<input name=\"fcname\" maxlength=\"30\"/><br/>";
    echo "Position:<input name=\"fcpos\" format=\"*N\" size=\"3\"  maxlength=\"3\"/><br/>";
echo "<input type=\"submit\" value=\"Add\"/>";
    echo "</form>";
        echo "<br/><br/><a href=\"admincp.php?fcats\">";
  echo "Forum Categories</a><br/>";
    echo "<a href=\"index.php?admincp\"><img src=\"images/admn.gif\" alt=\"*\"/>";
  echo "Admin CP</a><br/>";
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
  echo "Home</a>";
  echo "</p>";
    }
  echo dnoonline($sid,$uid);
}
else if(isset($_GET['addfrm']))
{
   echo vrhonline($sid,$uid);

if(isadmin(getuid_sid($sid)))
  {
    echo "<p align=\"center\">";
    echo "<b>Add Forum</b><br/><br/>";
echo "<form action=\"admproc.php?addfrm\" method=\"post\">";
    echo "Name:<input name=\"frname\" maxlength=\"30\"/><br/>";
    echo "Position:<input name=\"frpos\" format=\"*N\" size=\"3\"  maxlength=\"3\"/><br/>";
    $fcats = mysql_query("SELECT id, name FROM fun_fcats ORDER BY position, id, name");
    echo "Category: <select name=\"fcid\">";
    while ($fcat=mysql_fetch_array($fcats))
    {
        echo "<option value=\"$fcat[0]\">$fcat[1]</option>";
    }
    echo "</select><br/>";
echo "<input type=\"submit\" value=\"Add\"/>";
echo "</form>";
    
    
    echo "<br/><br/><a href=\"admincp.php?forums\">";
  echo "Forums</a><br/>";
    echo "<a href=\"index.php?admincp\"><img src=\"images/admn.gif\" alt=\"*\"/>";
  echo "Admin CP</a><br/>";
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
  echo "Home</a>";
  echo "</p>";
    }
  echo dnoonline($sid,$uid);
}
else if(isset($_GET['gccp']))
{
   echo vrhonline($sid,$uid);

if(isadmin(getuid_sid($sid)))
  {
    echo "<p align=\"center\">";
    echo "<b>Add club plusses</b><br/><br/>";
	$clid = $_GET["clid"];
echo "<form action=\"admproc.php?gccp&amp;clid=$clid\" method=\"post\">";
    echo "Plusses:<input name=\"plss\" maxlength=\"3\" size=\"3\" format=\"*N\"/><br/>";
echo "<input type=\"submit\" value=\"Give\"/>";
echo "</form>";
    
    echo "<br/><br/><a href=\"index.php?admincp\"><img src=\"images/admn.gif\" alt=\"*\"/>";
  echo "Admin CP</a><br/>";
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
  echo "Home</a>";
  echo "</p>";
    }
  echo dnoonline($sid,$uid);
}
else if(isset($_GET['addsml']))
{
    
   echo vrhonline($sid,$uid);
if(isadmin(getuid_sid($sid)))
  {
    echo "<p align=\"center\">";
    echo "<b>Add Smilies</b><br/><br/>";
echo "<form action=\"admproc.php?addsml\" method=\"post\">";
    echo "Code:<input name=\"smlcde\" maxlength=\"30\"/><br/>";
    echo "Image Source:<input name=\"smlsrc\" maxlength=\"200\"/><br/>";
 echo "<input type=\"submit\" value=\"Add\"/>";
echo "</form>";
    echo "<br/><br/><a href=\"index.php?admincp\"><img src=\"images/admn.gif\" alt=\"*\"/>";
  echo "Admin CP</a><br/>";
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
  echo "Home</a>";
  echo "</p>";
    }
  echo dnoonline($sid,$uid);
}

else if(isset($_GET['addavt']))
{
    
   echo vrhonline($sid,$uid);
if(isadmin(getuid_sid($sid)))
  {
    echo "<p align=\"center\">";
    echo "<b>Add Smilies</b><br/><br/>";
echo "<form action=\"admproc.php?addavt\" method=\"post\">";
    echo "Source:<input name=\"avtsrc\" maxlength=\"30\"/><br/>";
echo "<input type=\"submit\" value=\"Add\"/>";
echo "</form>";
    
    echo "<br/><br/><a href=\"index.php?admincp\"><img src=\"images/admn.gif\" alt=\"*\"/>";
  echo "Admin CP</a><br/>";
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
  echo "Home</a>";
  echo "</p>";
    }
  echo dnoonline($sid,$uid);
}

else if(isset($_GET['addrss']))
{
   echo vrhonline($sid,$uid);
    
if(isadmin(getuid_sid($sid)))
  {
    echo "<p align=\"center\">";
    echo "<b>Add RSS</b><br/><br/>";
echo "<form action=\"admproc.php?addrss\" method=\"post\">";
    echo "Name:<input name=\"rssnm\" maxlength=\"50\"/><br/>";
    echo "Source:<input name=\"rsslnk\" maxlength=\"255\"/><br/>";
    echo "Image:<input name=\"rssimg\" maxlength=\"255\"/><br/>";
    echo "Description:<input name=\"rssdsc\"  maxlength=\"255\"/><br/>";
    $forums = mysql_query("SELECT id, name FROM fun_forums ORDER BY position, id, name");
    echo "Forum: <select name=\"fid\">";
    echo "<option value=\"0\">NO FORUM</option>";
    while ($forum=mysql_fetch_array($forums))
    {
        echo "<option value=\"$forum[0]\">$forum[1]</option>";
    }
    echo "</select><br/>";
echo "<input type=\"submit\" value=\"Add\"/>";
echo "</form>";
   
    echo "<br/><br/><a href=\"admincp.php?manrss\">";
  echo "<img src=\"images/rss.gif\" alt=\"rss\"/>Manage RSS</a><br/>";
    echo "<a href=\"index.php?admincp\"><img src=\"images/admn.gif\" alt=\"*\"/>";
  echo "Admin CP</a><br/>";
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
  echo "Home</a>";
  echo "</p>";
    }
  echo dnoonline($sid,$uid);
}

else if(isset($_GET['addchr']))
{
   echo vrhonline($sid,$uid);

if(isadmin(getuid_sid($sid)))
  {
    echo "<p align=\"center\">";
    echo "<b>Add Room</b><br/><br/>";
echo "<form action=\"admproc.php?addchr\" method=\"post\">";
    echo "Name:<input name=\"chrnm\" maxlength=\"30\"/><br/>";
    echo "Minimum Age:<input name=\"chrage\" format=\"*N\" maxlength=\"3\" size=\"3\"/><br/>";
    echo "Minimum Chat Posts:<input name=\"chrpst\" format=\"*N\" maxlength=\"4\" size=\"4\"/><br/>";
    echo "Permission:<select name=\"chrprm\">";
    echo "<option value=\"0\">Normal</option>";
    echo "<option value=\"1\">Moderators</option>";
    echo "<option value=\"2\">Admins</option>";
    echo "</select><br/>";
    echo "Censored:<select name=\"chrcns\">";
    echo "<option value=\"1\">Yes</option>";
    echo "<option value=\"0\">No</option>";
    echo "</select><br/>";
    echo "Fun:<select name=\"chrfun\">";
    echo "<option value=\"0\">No</option>";
    echo "<option value=\"1\">esreveR</option>";
    echo "<option value=\"2\">auto foggy</option>";
    echo "</select><br/>";
echo "<input type=\"submit\" value=\"Add\"/>";
    echo "<form>";
    echo "<br/><br/><a href=\"admincp.php?chrooms\">";
  echo "<img src=\"images/chat.gif\" alt=\"chat\"/>Chatrooms</a><br/>";
    echo "<a href=\"index.php?admincp\"><img src=\"images/admn.gif\" alt=\"*\"/>";
  echo "Admin CP</a><br/>";
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
  echo "Home</a>";
  echo "</p>";
    }
  echo dnoonline($sid,$uid);
}

else if(isset($_GET['edtrss']))
{
   echo vrhonline($sid,$uid);
  
if(isadmin(getuid_sid($sid)))
  {
  $rssid = $_POST["rssid"];
  $rsinfo = mysql_fetch_array(mysql_query("SELECT title, link, imgsrc, fid, dscr FROM fun_rss WHERE id='".$rssid."'"));
    
    
    echo "<p align=\"center\">";
    
    echo "<b>Edit RSS</b><br/><br/>";
echo "<form action=\"admproc.php?edtrss\" method=\"post\">";
    echo "Name:<input name=\"rssnm\" maxlength=\"50\" value=\"$rsinfo[0]\"/><br/>";
    echo "Source:<input name=\"rsslnk\" maxlength=\"255\" value=\"$rsinfo[1]\"/><br/>";
    echo "Image:<input name=\"rssimg\" maxlength=\"255\" value=\"$rsinfo[2]\"/><br/>";
    echo "Description:<input name=\"rssdsc\"  maxlength=\"255\" value=\"$rsinfo[4]\"/><br/>";
    $forums = mysql_query("SELECT id, name FROM fun_forums ORDER BY position, id, name");
    echo "Forum: <select name=\"fid\" value=\"$rsinfo[3]\">";
    echo "<option value=\"0\">NO FORUM</option>";
    while ($forum=mysql_fetch_array($forums))
    {
        echo "<option value=\"$forum[0]\">$forum[1]</option>";
    }
    echo "</select><br/>";
echo "<input type=\"submit\" value=\"Edit\"/>";
echo "<input type=\"hidden\" name=\"fid\" value=\"$fid\"/>";
echo "<input type=\"hidden\" name=\"rssid\" value=\"$rssid\"/>";
echo "</form>";
        echo "<br/><br/><a href=\"admincp.php?manrss\">";
  echo "<img src=\"images/rss.gif\" alt=\"rss\"/>Manage RSS</a><br/>";
    echo "<a href=\"index.php?admincp\"><img src=\"images/admn.gif\" alt=\"*\"/>";
  echo "Admin CP</a><br/>";
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
  echo "Home</a>";
  echo "</p>";
    }
  echo dnoonline($sid,$uid);
}

else if(isset($_GET['addgrp']))
{
    
   echo vrhonline($sid,$uid);
if(isadmin(getuid_sid($sid)))
  {
    echo "<p align=\"center\">";
    echo "<b>Add Group</b><br/><br/>";
echo "<form action=\"admproc.php?addgrp\" method=\"post\">";
    echo "Name:<input name=\"ugname\" maxlength=\"30\"/><br/>";
    echo "Auto Assign:<select name=\"ugaa\">";
    echo "<option value=\"1\">Yes</option>";
    echo "<option value=\"0\">No</option>";
    echo "</select><br/>";
    echo "<br/><small><b>For Auto Assign Only</b></small><br/>";
    echo "Allow:<select name=\"allus\">";
    echo "<option value=\"0\">Normal Users</option>";
    echo "<option value=\"1\">Mods</option>";
    echo "<option value=\"2\">Admins</option>";
    echo "</select><br/>";
    echo "Min. Age:";
    echo "<input name=\"mage\" format=\"*N\" maxlength=\"3\" size=\"3\"/>";
    echo "<br/>Min. Posts:";
    echo "<input name=\"mpst\" format=\"*N\" maxlength=\"3\" size=\"3\"/>";
    echo "<br/>Min. Plusses:";
    echo "<input name=\"mpls\" format=\"*N\" maxlength=\"3\" size=\"3\"/><br/>";
echo "<input type=\"submit\" value=\"Add\"/>";
echo "</form>";

    echo "<br/><br/><a href=\"admincp.php?ugroups\">";
  echo "UGroups</a><br/>";
    echo "<a href=\"index.php?admincp\"><img src=\"images/admn.gif\" alt=\"*\"/>";
  echo "Admin CP</a><br/>";
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
  echo "Home</a>";
  echo "</p>";
    }
  echo dnoonline($sid,$uid);
}



else if(isset($_GET['edtfrm']))
{
   echo vrhonline($sid,$uid);
    
if(isadmin(getuid_sid($sid)))
  {
    echo "<p align=\"center\">";
    echo "<b>Edit Forum</b><br/><br/>";
    $forums = mysql_query("SELECT id,name FROM fun_forums ORDER BY position, id, name");
echo "<form action=\"admproc.php?edtfrm\" method=\"post\">";
    echo "Forum: <select name=\"fid\">";
    while($forum=mysql_fetch_array($forums))
    {
      echo "<option value=\"$forum[0]\">$forum[1]</option>";
    }
    echo "</select>";
    echo "<br/>Name:<input name=\"frname\" maxlength=\"30\"/><br/>";
    echo "Position:<input name=\"frpos\" format=\"*N\" size=\"3\"  maxlength=\"3\"/><br/>";
    $fcats = mysql_query("SELECT id, name FROM fun_fcats ORDER BY position, id, name");
    echo "Category: <select name=\"fcid\">";
    while ($fcat=mysql_fetch_array($fcats))
    {
        echo "<option value=\"$fcat[0]\">$fcat[1]</option>";
    }
    echo "</select><br/>";
echo "<input type=\"submit\" value=\"Edit\"/>";
echo "</form>";
    
    echo "<br/><br/><a href=\"admincp.php?forums\">";
  echo "Forums</a><br/>";
    echo "<a href=\"index.php?admincp\"><img src=\"images/admn.gif\" alt=\"*\"/>";
  echo "Admin CP</a><br/>";
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
  echo "Home</a>";
  echo "</p>";
    }
  echo dnoonline($sid,$uid);
}
else if(isset($_GET['delfrm']))
{

   echo vrhonline($sid,$uid);
if(isadmin(getuid_sid($sid)))
  {
    echo "<p align=\"center\">";
    echo "<b>Delete Forum</b><br/><br/>";
    $forums = mysql_query("SELECT id,name FROM fun_forums ORDER BY position, id, name");
echo "<form action=\"admproc.php?delfrm\" method=\"post\">";
    echo "Forum: <select name=\"fid\">";
    while($forum=mysql_fetch_array($forums))
    {
      echo "<option value=\"$forum[0]\">$forum[1]</option>";
    }
    echo "</select><br/>";
echo "<input type=\"submit\" value=\"Delete\"/>";
    echo "</form>";
    
    echo "<br/><br/><a href=\"admincp.php?forums\">";
  echo "Forums</a><br/>";
    echo "<a href=\"index.php?admincp\"><img src=\"images/admn.gif\" alt=\"*\"/>";
  echo "Admin CP</a><br/>";
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
  echo "Home</a>";
  echo "</p>";
    }
  echo dnoonline($sid,$uid);
}else if(isset($_GET['delgrp']))
{
   echo vrhonline($sid,$uid);
    
if(isadmin(getuid_sid($sid)))
  {
    echo "<p align=\"center\">";
    echo "<b>Delete UGroup</b><br/><br/>";
    $forums = mysql_query("SELECT id,name FROM fun_groups ORDER BY name, id");
echo "<form action=\"admproc.php?delgrp\" method=\"post\">";
    echo "UGroup: <select name=\"ugid\">";
    while($forum=mysql_fetch_array($forums))
    {
      echo "<option value=\"$forum[0]\">$forum[1]</option>";
    }
    echo "</select><br/>";
echo "<input type=\"submit\" value=\"Delete\"/>";
echo "</form>";
       echo "<br/><br/><a href=\"admincp.php?forums\">";
  echo "Forums</a><br/>";
    echo "<a href=\"index.php?admincp\"><img src=\"images/admn.gif\" alt=\"*\"/>";
  echo "Admin CP</a><br/>";
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
  echo "Home</a>";
  echo "</p>";
    }
  echo dnoonline($sid,$uid);
}
else if(isset($_GET['edtcat']))
{
   echo vrhonline($sid,$uid);
    
if(isadmin(getuid_sid($sid)))
  {
    echo "<p align=\"center\">";
    echo "<b>Edit Category</b><br/><br/>";
    $fcats = mysql_query("SELECT id, name FROM fun_fcats ORDER BY position, id, name");
echo "<form action=\"admproc.php?edtcat\" method=\"post\">";
    echo "Edit: <select name=\"fcid\">";
    while ($fcat=mysql_fetch_array($fcats))
    {
        echo "<option value=\"$fcat[0]\">$fcat[1]</option>";
    }
    echo "</select><br/>";
    echo "Name:<input name=\"fcname\" maxlength=\"30\"/><br/>";
    echo "Position:<input name=\"fcpos\" format=\"*N\" size=\"3\"  maxlength=\"3\"/><br/>";
echo "<input type=\"submit\" value=\"Edit\"/>";
echo "</form>";
    
    echo "<br/><br/><a href=\"admincp.php?fcats\">";
  echo "Forum Categories</a><br/>";
    echo "<a href=\"index.php?admincp\"><img src=\"images/admn.gif\" alt=\"*\"/>";
  echo "Admin CP</a><br/>";
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
  echo "Home</a>";
  echo "</p>";
    }
  echo dnoonline($sid,$uid);
}else if(isset($_GET['delcat']))
{
   echo vrhonline($sid,$uid);

if(isadmin(getuid_sid($sid)))
  {
    echo "<p align=\"center\">";
    echo "<b>Delete Category</b><br/><br/>";
    $fcats = mysql_query("SELECT id, name FROM fun_fcats ORDER BY position, id, name");
echo "<form action=\"admproc.php?delcat\" method=\"post\"/>";
    echo "Delete: <select name=\"fcid\">";
    
    while ($fcat=mysql_fetch_array($fcats))
    {
        echo "<option value=\"$fcat[0]\">$fcat[1]</option>";
    }
    echo "</select><br/>";
echo "<input type=\"submit\" value=\"Delete\"/>";
    echo "</form>";
    
    echo "<br/><br/><a href=\"admincp.php?fcats\">";
  echo "Forum Categories</a><br/>";
    echo "<a href=\"index.php?admincp\"><img src=\"images/admn.gif\" alt=\"*\"/>";
  echo "Admin CP</a><br/>";
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
  echo "Home</a>";
  echo "</p>";
    }
  echo dnoonline($sid,$uid);
}
/////////////////////////////////user info

else if(isset($_GET['chuinfo']))
{
   echo vrhonline($sid,$uid);
    
if(isadmin(getuid_sid($sid)))
  {
    echo "<p align=\"center\">";
    echo "Type user nickname<br/><br/>";
echo "<form action=\"admincp.php?acui\" method=\"post\">";
    echo "User: <input name=\"unick\" format=\"*x\" maxlength=\"15\"/><br/>";
echo "<input type=\"submit\" value=\"find\"/>";
echo "</form>";
        echo "<br/><br/><a href=\"index.php?admincp\"><img src=\"images/admn.gif\" alt=\"*\"/>";
  echo "Admin CP</a><br/>";
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
  echo "Home</a>";
  echo "</p>";
    }
  echo dnoonline($sid,$uid);
}

//////////////////////////////////////Change User info

else if(isset($_GET['acui']))
{
   echo vrhonline($sid,$uid);
    
if(isadmin(getuid_sid($sid)))
  {
    echo "<p align=\"center\">";
    $unick = $_POST["unick"];
    $tid = getuid_nick($unick);
    if($tid==0)
    {
      echo "<img src=\"images/notok.gif\" alt=\"x\"/>User Does Not exist<br/>";
    }else{
      echo "</p>";
      echo "<p>";
      echo "<a href=\"admincp.php?chubi&amp;who=$tid\">&#187;$unick's Profile</a><br/>";
      $judg = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_judges WHERE uid='".$tid."'"));
      if($judg[0]>0)
      {
      echo "<a href=\"admproc.php?deljdg&amp;who=$tid\">&#187;Remove $unick From Judges List</a><br/>";
      }else{
        echo "<a href=\"admproc.php?addjdg&amp;who=$tid\">&#187;Make $unick judge</a><br/>";
      }
      //echo "<a href=\"admincp.php?addtog&amp;who=$tid\">&#187;Add  $unick to a group</a><br/>";
      //echo "<a href=\"admincp.php?umset&amp;who=$tid\">&#187;$unick's Mod. Settings</a><br/>";
	  echo "<a href=\"admproc.php?delxp&amp;who=$tid\">&#187;Delete $unick's posts</a><br/>";
      echo "<a href=\"admproc.php?delu&amp;who=$tid\">&#187;Delete $unick</a><br/>";
      echo "</p>";
      echo "<p align=\"center\">";
    }
    echo "<a href=\"admincp.php?chuinfo\">";
  echo "Users Info</a><br/>";
    echo "<a href=\"index.php?admincp\"><img src=\"images/admn.gif\" alt=\"*\"/>";
  echo "Admin CP</a><br/>";
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
  echo "Home</a>";
  echo "</p>";
    }
  echo dnoonline($sid,$uid);
}

////////////////////////////////////////////

else if(isset($_GET['chubi']))
{
   echo vrhonline($sid,$uid);
    
if(isadmin(getuid_sid($sid)))
  {
    $who = $_GET["who"];
    $unick = getnick_uid($who);
    
    $avat = getavatar($who);
    $email = mysql_fetch_array(mysql_query("SELECT email FROM fun_users WHERE id='".$who."'"));
    $site = mysql_fetch_array(mysql_query("SELECT site FROM fun_users WHERE id='".$who."'"));
    $bdy = mysql_fetch_array(mysql_query("SELECT birthday FROM fun_users WHERE id='".$who."'"));
    $uloc = mysql_fetch_array(mysql_query("SELECT location FROM fun_users WHERE id='".$who."'"));
    $usig = mysql_fetch_array(mysql_query("SELECT signature FROM fun_users WHERE id='".$who."'"));
    $sx = mysql_fetch_array(mysql_query("SELECT sex FROM fun_users WHERE id='".$who."'"));
    $perm = mysql_fetch_array(mysql_query("SELECT perm FROM fun_users WHERE id='".$who."'"));
  
    echo "<p>";
echo "<form action=\"admproc.php?uprof&amp;who=$who\" method=\"post\">";
    echo "Nickname: <input name=\"unick\" maxlength=\"15\" value=\"$unick\"/><br/>";
    echo "Avatar: <input name=\"savat\" maxlength=\"100\" value=\"$avat\"/><br/>";
    echo "E-Mail: <input name=\"semail\" maxlength=\"100\" value=\"$email[0]\"/><br/>";
    echo "Site: <input name=\"usite\" maxlength=\"100\" value=\"$site[0]\"/><br/>";
    echo "Birthday<small>(YYYY-MM-DD)</small>: <input name=\"ubday\" maxlength=\"50\" value=\"$bdy[0]\"/><br/>";
    echo "Location: <input name=\"uloc\" maxlength=\"50\" value=\"$uloc[0]\"/><br/>";
    echo "Signature: <input name=\"usig\" maxlength=\"100\" value=\"$usig[0]\"/><br/>";
    echo "Sex: <select name=\"usex\" value=\"$sx[0]\">";
    echo "<option value=\"M\">Male</option>";
    echo "<option value=\"F\">Female</option>";
    echo "</select><br/>";
    echo "Privileges: <select name=\"perm\" value=\"$perm[0]\">";
    echo "<option value=\"0\">Normal</option>";
    echo "<option value=\"1\">Moderator</option>";
    echo "<option value=\"2\">Admin</option>";
    echo "</select><br/>";
echo "<input type=\"submit\" value=\"Update\"/>";
echo "</form>";
   
    echo "<br/><br/>";
echo "<form action=\"admproc.php?upwd&amp;who=$who\" method=\"post\">";
    echo "Password: <input name=\"npwd\" format=\"*x\" maxlength=\"15\"/><br/>";
echo "<input type=\"submit\" value=\"Change\"/>";
echo "</form>";
   
    echo "</p>";
    echo "<p align=\"center\">";
    echo "<a href=\"admincp.php?chuinfo\">";
  echo "Users Info</a><br/>";
    echo "<a href=\"index.php?admincp\"><img src=\"images/admn.gif\" alt=\"*\"/>";
  echo "Admin CP</a><br/>";
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
  echo "Home</a>";
  echo "</p>";
    }
  echo dnoonline($sid,$uid);
    
}
else{
   echo vrhonline($sid,$uid);
   
  echo "<p align=\"center\">";
  echo "I don't know how did you get into here, but there's nothing to show<br/><br/>";
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
  echo dnoonline($sid,$uid);
}

	echo "</body>";
	echo "</html>";
?>
