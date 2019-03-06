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
if(!isadmin(getuid_sid($sid)))
  {
    
      echo "<p align=\"center\">";
      echo "You are not an admin<br/>";
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
    addonline(getuid_sid($sid),"Admin CP","");
if(isset($_GET['general']))
{
  $xtm = $_POST["sesp"];
  $fmsg = $_POST["fmsg"];
  $areg = $_POST["areg"];
  $pmaf = $_POST["pmaf"];
  $fvw = $_POST["fvw"];
  if($areg=="d")
  {
    $arv = 0;
  }else{
    $arv = 1;
  }
   
if(isadmin(getuid_sid($sid)))
  {
      echo "<p align=\"center\">";
      
      
      $res = mysql_query("UPDATE fun_settings SET value='".$fmsg."' WHERE name='4ummsg'");
      if($res)
      {
        echo "<img src=\"images/ok.gif\" alt=\"O\"/>Forum Message  updated successfully<br/>";
      }else{
        echo "<img src=\"images/notok.gif\" alt=\"X\"/>Error Updating Forum message<br/>";
      }
      
      
      $res = mysql_query("UPDATE fun_settings SET value='".$xtm."' WHERE name='sesxp'");
      if($res)
      {
        echo "<img src=\"images/ok.gif\" alt=\"O\"/>Session Period updated successfully<br/>";
      }else{
        echo "<img src=\"images/notok.gif\" alt=\"X\"/>Error Updating Session Period<br/>";
      }
      
       $res = mysql_query("UPDATE fun_settings SET value='".$pmaf."' WHERE name='pmaf'");
      if($res)
      {
        echo "<img src=\"images/ok.gif\" alt=\"O\"/>PM antiflood is $pmaf seconds<br/>";
      }else{
        echo "<img src=\"images/notok.gif\" alt=\"X\"/>Error Updating PM antiflood value<br/>";
      }
      
      $res = mysql_query("UPDATE fun_settings SET value='".$arv."' WHERE name='reg'");
      
      if($res)
      {
        echo "<img src=\"images/ok.gif\" alt=\"O\"/>Registration updated successfully<br/>";
      }else{
        echo "<img src=\"images/notok.gif\" alt=\"X\"/>Error Updating Registration<br/>";
      }
      
      $res = mysql_query("UPDATE fun_settings SET value='".$fvw."' WHERE name='fview'");

      if($res)
      {
        echo "<img src=\"images/ok.gif\" alt=\"O\"/>Forums View updated successfully<br/>";
      }else{
        echo "<img src=\"images/notok.gif\" alt=\"X\"/>Error Updating Forums View<br/>";
      }
      echo "<br/>";
      
      echo "<a href=\"admincp.php?general\">";
  echo "Edit general settings</a><br/>";
      echo "<a href=\"index.php?admincp\"><img src=\"images/admn.gif\" alt=\"*\"/>";
  echo "Admin CP</a><br/>";}
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
  echo "Home</a>";
  echo "</p>";
}

//////////////////////////Add moderating

else if(isset($_GET['addfmod']))
{
    $mid = $_POST["mid"];
  $fid = $_POST["fid"];
      
if(isadmin(getuid_sid($sid)))
  {
      echo "<p align=\"center\">";
      $res = mysql_query("INSERT INTO fun_modr SET name='".$mid."', forum='".$fid."'");
      if($res)
      {
        echo "<img src=\"images/ok.gif\" alt=\"O\"/>Moding Privileges Added<br/>";
      }else{
        echo "<img src=\"images/notok.gif\" alt=\"X\"/>Database Error<br/>";
      }
      echo "<br/><br/><a href=\"admincp.php?manmods\">";
  echo "Manage Moderators</a><br/>";
      echo "<a href=\"index.php?admincp\"><img src=\"images/admn.gif\" alt=\"*\"/>";
  echo "Admin CP</a><br/>";}
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
  echo "Home</a>";
  echo "</p>";
}
else if(isset($_GET['delclub']))
{
  $clid = $_GET["clid"];
      
if(isadmin(getuid_sid($sid)))
  {
      echo "<p align=\"center\">";
      $res = deleteClub($clid);
      if($res)
      {
        echo "<img src=\"images/ok.gif\" alt=\"O\"/>Club Deleted<br/>";
      }else{
        echo "<img src=\"images/notok.gif\" alt=\"X\"/>Database Error<br/>";
      }
      
      echo "<br/><br/><a href=\"index.php?admincp\"><img src=\"images/admn.gif\" alt=\"*\"/>";
  echo "Admin CP</a><br/>";}
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
  echo "Home</a>";
  echo "</p>";
}

else if(isset($_GET['gccp']))
{
  $clid = $_GET["clid"];
  $plss = $_POST["plss"];
      
if(isadmin(getuid_sid($sid)))
  {
      echo "<p align=\"center\">";
      $nop = mysql_fetch_array(mysql_query("SELECT plusses FROM fun_clubs WHERE id='".$clid."'"));
	  $newpl = $nop[0] + $plss;
	  $res = mysql_query("UPDATE fun_clubs SET plusses='".$newpl."' WHERE id='".$clid."'");
      if($res)
      {
        echo "<img src=\"images/ok.gif\" alt=\"O\"/>Club plusses updated<br/>";
      }else{
        echo "<img src=\"images/notok.gif\" alt=\"X\"/>Database Error<br/>";
      }
      
      echo "<br/><br/><a href=\"index.php?admincp\"><img src=\"images/admn.gif\" alt=\"*\"/>";
  echo "Admin CP</a><br/>";}
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
  echo "Home</a>";
  echo "</p>";
}

else if(isset($_GET['delfmod']))
{
    $mid = $_POST["mid"];
  $fid = $_POST["fid"];
      
if(isadmin(getuid_sid($sid)))
  {
      echo "<p align=\"center\">";
      $res = mysql_query("DELETE FROM fun_modr WHERE name='".$mid."' AND forum='".$fid."'");
      if($res)
      {
        echo "<img src=\"images/ok.gif\" alt=\"O\"/>Moding Privileges Deleted<br/>";
      }else{
        echo "<img src=\"images/notok.gif\" alt=\"X\"/>Database Error<br/>";
      }
      echo "<br/><br/><a href=\"admincp.php?manmods\">";
  echo "Manage Moderators</a><br/>";
      echo "<a href=\"index.php?admincp\"><img src=\"images/admn.gif\" alt=\"*\"/>";
  echo "Admin CP</a><br/>";}
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
  echo "Home</a>";
  echo "</p>";
}
///////////////////////////////////////

else if(isset($_GET['addcat']))
{
  $fcname = $_POST["fcname"];
  $fcpos = $_POST["fcpos"];
       
if(isadmin(getuid_sid($sid)))
  {
      echo "<p align=\"center\">";
        echo $fcname;
        echo "<br/>";
        $res = mysql_query("INSERT INTO fun_fcats SET name='".$fcname."', position='".$fcpos."'");
        
        if($res)
      {
        echo "<img src=\"images/ok.gif\" alt=\"O\"/>Forum Category added successfully";
      }else{
        echo "<img src=\"images/notok.gif\" alt=\"X\"/>Error adding Forum Category";
      }

      echo "<br/><br/><a href=\"admincp.php?fcats\">";
  echo "Forum Categories</a><br/>";
      echo "<a href=\"index.php?admincp\"><img src=\"images/admn.gif\" alt=\"*\"/>";
  echo "Admin CP</a><br/>";}
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
  echo "Home</a>";

      echo "</p>";
}
else if(isset($_GET['addfrm']))
{
  $frname = $_POST["frname"];
  $frpos = $_POST["frpos"];
  $fcid = $_POST["fcid"];
    
      echo "<p align=\"center\">";   
if(isadmin(getuid_sid($sid)))
  {
        echo $frname;
        echo "<br/>";
        $res = mysql_query("INSERT INTO fun_forums SET name='".$frname."', position='".$frpos."', cid='".$fcid."'");

        if($res)
      {
        echo "<img src=\"images/ok.gif\" alt=\"O\"/>Forum  added successfully";
      }else{
        echo "<img src=\"images/notok.gif\" alt=\"X\"/>Error adding Forum ";
      }

      echo "<br/><br/><a href=\"admincp.php?forums\">";
  echo "Forums</a><br/>";
      echo "<a href=\"index.php?admincp\"><img src=\"images/admn.gif\" alt=\"*\"/>";
  echo "Admin CP</a><br/>";}
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
  echo "Home</a>";

      echo "</p>";
}

else if(isset($_GET['addsml']))
{
  $smlcde = $_POST["smlcde"];
  $smlsrc = $_POST["smlsrc"];
    
      echo "<p align=\"center\">";   
if(isadmin(getuid_sid($sid)))
  {
        echo "<br/>";
        $res = mysql_query("INSERT INTO fun_smilies SET scode='".$smlcde."', imgsrc='".$smlsrc."', hidden='0'");

        if($res)
      {
        echo "<img src=\"images/ok.gif\" alt=\"O\"/>Smilie  added successfully";
      }else{
        echo "<img src=\"images/notok.gif\" alt=\"X\"/>Error adding Smilie ";
      }

      echo "<br/><br/><a href=\"admincp.php?addsml\">";
  echo "Add Another Smilie</a><br/>";
      echo "<a href=\"index.php?admincp\"><img src=\"images/admn.gif\" alt=\"*\"/>";
  echo "Admin CP</a><br/>";}
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
  echo "Home</a>";

      echo "</p>";
}

else if(isset($_GET['addavt']))
{
  $avtsrc = $_POST["avtsrc"];
      
      echo "<p align=\"center\">";   
if(isadmin(getuid_sid($sid)))
  {
	  echo "Source: ".$avtsrc;

        echo "<br/>";
        $res = mysql_query("INSERT INTO fun_avatars SET avlink='".$avtsrc."'");

        if($res)
      {
        echo "<img src=\"images/ok.gif\" alt=\"O\"/>Avatar  added successfully";
      }else{
        echo "<img src=\"images/notok.gif\" alt=\"X\"/>Error adding Avatar ";
      }

      echo "<br/><br/><a href=\"admincp.php?addavt\">";
  echo "Add Another Avatar</a><br/>";
      echo "<a href=\"index.php?admincp\"><img src=\"images/admn.gif\" alt=\"*\"/>";
  echo "Admin CP</a><br/>";}
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
  echo "Home</a>";

      echo "</p>";
}

else if(isset($_GET['addjdg']))
{
  $who = $_GET["who"];
    
      echo "<p align=\"center\">";   
if(isadmin(getuid_sid($sid)))
  {
        echo "<br/>";
        $res = mysql_query("INSERT INTO fun_judges SET uid='".$who."'");

        if($res)
      {
        echo "<img src=\"images/ok.gif\" alt=\"O\"/>Judge  added successfully";
      }else{
        echo "<img src=\"images/notok.gif\" alt=\"X\"/>Error adding Judge ";
      }

      echo "<br/><br/><a href=\"admincp.php?chuinfo\">";
  echo "Users Info</a><br/>";
      echo "<a href=\"index.php?admincp\"><img src=\"images/admn.gif\" alt=\"*\"/>";
  echo "Admin CP</a><br/>";}
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
  echo "Home</a>";

      echo "</p>";
}

else if(isset($_GET['deljdg']))
{
  $who = $_GET["who"];
    
      echo "<p align=\"center\">";   
if(isadmin(getuid_sid($sid)))
  {
        echo "<br/>";
        $res = mysql_query("DELETE FROM fun_judges WHERE uid='".$who."'");

        if($res)
      {
        echo "<img src=\"images/ok.gif\" alt=\"O\"/>Judge  deleted successfully";
      }else{
        echo "<img src=\"images/notok.gif\" alt=\"X\"/>Error deleting Judge ";
      }

      echo "<br/><br/><a href=\"admincp.php?chuinfo\">";
  echo "Users Info</a><br/>";
      echo "<a href=\"index.php?admincp\"><img src=\"images/admn.gif\" alt=\"*\"/>";
  echo "Admin CP</a><br/>";}
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
  echo "Home</a>";

      echo "</p>";
}

else if(isset($_GET['delsm']))
{
  $smid = $_GET["smid"];
    
      echo "<p align=\"center\">";   
if(isadmin(getuid_sid($sid)))
  {
        echo "<br/>";
        $res = mysql_query("DELETE FROM fun_smilies WHERE id='".$smid."'");

        if($res)
      {
        echo "<img src=\"images/ok.gif\" alt=\"O\"/>Smilie  deleted successfully";
      }else{
        echo "<img src=\"images/notok.gif\" alt=\"X\"/>Error deleting smilie ";
      }

      echo "<br/><br/><a href=\"index.php?admincp\"><img src=\"images/admn.gif\" alt=\"*\"/>";
  echo "Admin CP</a><br/>";}
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
  echo "Home</a>";

      echo "</p>";
}

else if(isset($_GET['addrss']))
{
  $rssnm = $_POST["rssnm"];
  $rsslnk = $_POST["rsslnk"];
  $rssimg = $_POST["rssimg"];
  $rssdsc = $_POST["rssdsc"];
  $fid = $_POST["fid"];
  
    
      echo "<p align=\"center\">";   
if(isadmin(getuid_sid($sid)))
  {
        echo $rssnm;
        echo "<br/>";
        $res = mysql_query("INSERT INTO fun_rss SET title='".$rssnm."', link='".$rsslnk."', imgsrc='".$rssimg."', dscr='".$rssdsc."', fid='".$fid."'");

        if($res)
      {
        echo "<img src=\"images/ok.gif\" alt=\"O\"/>Source added successfully";
      }else{
        echo "<img src=\"images/notok.gif\" alt=\"X\"/>Error adding RSS Source";
      }

      echo "<br/><br/><a href=\"admincp.php?manrss\">";
  echo "Manage RSS</a><br/>";
      echo "<a href=\"index.php?admincp\"><img src=\"images/admn.gif\" alt=\"*\"/>";
  echo "Admin CP</a><br/>";}
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
  echo "Home</a>";

      echo "</p>";
}

else if(isset($_GET['addchr']))
{
  $chrnm = $_POST["chrnm"];
  $chrage = $_POST["chrage"];
  $chrpst = $_POST["chrpst"];
  $chrprm = $_POST["chrprm"];
  $chrcns = $_POST["chrcns"];
  $chrfun = $_POST["chrfun"];
  
  

    
      echo "<p align=\"center\">";   
if(isadmin(getuid_sid($sid)))
  {
        echo $chrnm;
        echo "<br/>";
        $res = mysql_query("INSERT INTO fun_rooms SET name='".$chrnm."', static='1', pass='', mage='".$chrage."', chposts='".$chrpst."', perms='".$chrprm."', censord='".$chrcns."' , freaky='".$chrfun."'");
echo mysql_error();
        if($res)
      {
        echo "<img src=\"images/ok.gif\" alt=\"O\"/>Chatroom added successfully";
      }else{
        echo "<img src=\"images/notok.gif\" alt=\"X\"/>Error adding Chat room";
      }

      echo "<br/><br/><a href=\"admincp.php?chrooms\">";
  echo "Chatrooms</a><br/>";
      echo "<a href=\"index.php?admincp\"><img src=\"images/admn.gif\" alt=\"*\"/>";
  echo "Admin CP</a><br/>";}
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
  echo "Home</a>";

      echo "</p>";
}

else if(isset($_GET['edtrss']))
{
  $rssnm = $_POST["rssnm"];
  $rsslnk = $_POST["rsslnk"];
  $rssimg = $_POST["rssimg"];
  $rssdsc = $_POST["rssdsc"];
  $fid = $_POST["fid"];
  $rssid = $_POST["rssid"];
    
      echo "<p align=\"center\">";   
if(isadmin(getuid_sid($sid)))
  {
        echo $rssnm;
        echo "<br/>";
        $res = mysql_query("UPDATE fun_rss SET title='".$rssnm."', link='".$rsslnk."', imgsrc='".$rssimg."', dscr='".$rssdsc."', fid='".$fid."' WHERE id='".$rssid."'");

        if($res)
      {
        echo "<img src=\"images/ok.gif\" alt=\"O\"/>Source updated successfully";
      }else{
        echo "<img src=\"images/notok.gif\" alt=\"X\"/>Error updating RSS Source";
      }

      echo "<br/><br/><a href=\"admincp.php?manrss\">";
  echo "Manage RSS</a><br/>";
      echo "<a href=\"index.php?admincp\"><img src=\"images/admn.gif\" alt=\"*\"/>";
  echo "Admin CP</a><br/>";}
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
  echo "Home</a>";

      echo "</p>";
}

else if(isset($_GET['addperm']))
{
  $fid = $_POST["fid"];
  $gid = $_POST["gid"];
    
      echo "<p align=\"center\">";   
if(isadmin(getuid_sid($sid)))
  {
        echo "<br/>";
        $res = mysql_query("INSERT INTO fun_acc SET fid='".$fid."', gid='".$gid."'");

        if($res)
      {
        echo "<img src=\"images/ok.gif\" alt=\"O\"/>Permission  added successfully";
      }else{
        echo "<img src=\"images/notok.gif\" alt=\"X\"/>Error adding permission ";
      }

      echo "<br/><br/><a href=\"admincp.php?addperm\">";
  echo "Add Permission</a><br/>";
      echo "<a href=\"index.php?admincp\"><img src=\"images/admn.gif\" alt=\"*\"/>";
  echo "Admin CP</a><br/>";}
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
  echo "Home</a>";

      echo "</p>";
}

//////////////////////////////////////////Update profile

else if(isset($_GET['uprof']))
{
    
    $who = $_GET["who"];
    $unick = $_POST["unick"];
    $perm = $_POST["perm"];
    $savat = $_POST["savat"];
    $semail = $_POST["semail"];
    $usite = $_POST["usite"];
    $ubday = $_POST["ubday"];
    $uloc = $_POST["uloc"];
    $usig = $_POST["usig"];
    $usex = $_POST["usex"];
    
  echo "<p align=\"center\">";   
if(isadmin(getuid_sid($sid)))
  {
  $onk = mysql_fetch_array(mysql_query("SELECT name FROM fun_users WHERE id='".$who."'"));
  $exs = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_users WHERE name='".$unick."'"));
  if($onk[0]!=$unick)
  {
	  if($exs[0]>0)
	  {
		echo "<img src=\"images/notok.gif\" alt=\"x\"/>New nickname already exist, choose another one<br/>";
	  }else
  {
  $res = mysql_query("UPDATE fun_users SET avatar='".$savat."', email='".$semail."', site='".$usite."', birthday='".$ubday."', location='".$uloc."', signature='".$usig."', sex='".$usex."', name='".$unick."', perm='".$perm."' WHERE id='".$who."'");
  if($res)
  {
    echo "<img src=\"images/ok.gif\" alt=\"o\"/>$unick's profile was updated successfully<br/>";
  }else{
    echo "<img src=\"images/notok.gif\" alt=\"x\"/>Error updating $unick's profile<br/>";
  }
  }
  }else
  {
  $res = mysql_query("UPDATE fun_users SET avatar='".$savat."', email='".$semail."', site='".$usite."', birthday='".$ubday."', location='".$uloc."', signature='".$usig."', sex='".$usex."', name='".$unick."', perm='".$perm."' WHERE id='".$who."'");
  if($res)
  {
    echo "<img src=\"images/ok.gif\" alt=\"o\"/>$unick's profile was updated successfully<br/>";
  }else{
    echo "<img src=\"images/notok.gif\" alt=\"x\"/>Error updating $unick's profile<br/>";
  }
  }
  echo "<br/><a href=\"admincp.php?chuinfo\">";
  echo "Users Info</a><br/>";
      echo "<a href=\"index.php?admincp\"><img src=\"images/admn.gif\" alt=\"*\"/>";
  echo "Admin CP</a><br/>";}
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
}
/////////////user password
else if(isset($_GET['upwd']))
{
    
    $npwd = $_POST["npwd"];
    $who = $_GET["who"];
    
  echo "<p align=\"center\">";   
if(isadmin(getuid_sid($sid)))
  {
  
   if((strlen($npwd)<4) || (strlen($npwd)>15)){
    echo "<img src=\"images/notok.gif\" alt=\"x\"/>password should be between 4 and 15 letters only<br/>";

  }else{
    $pwd = md5($npwd);
    $res = mysql_query("UPDATE fun_users SET pass='".$pwd."' WHERE id='".$who."'");
    if($res)
  {
    echo "<img src=\"images/ok.gif\" alt=\"o\"/>password was updated successfully<br/>";
  }else{
    echo "<img src=\"images/notok.gif\" alt=\"x\"/>Error updating password<br/>";
  }
}
echo "<br/><a href=\"admincp.php?chuinfo\">";
  echo "Users Info</a><br/>";
      echo "<a href=\"index.php?admincp\"><img src=\"images/admn.gif\" alt=\"*\"/>";
  echo "Admin CP</a><br/>";}
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
}
///////////////add group
else if(isset($_GET['addgrp']))
{
  $frname = $_POST["ugname"];
  $ugaa = $_POST["ugaa"];
  $allus = $_POST["allus"];
  $mage = $_POST["mage"];
  $mpst = $_POST["mpst"];
  $mpls = $_POST["mpls"];
  
    
      echo "<p align=\"center\">";   
if(isadmin(getuid_sid($sid)))
  {
        echo $ugname;
        echo "<br/>";
        $res = mysql_query("INSERT INTO fun_groups SET name='".$ugname."', autoass='".$ugaa."', userst='".$allus."', mage='".$mage."', posts='".$mpst."', plusses='".$mpls."'");

        if($res)
      {
        echo "<img src=\"images/ok.gif\" alt=\"O\"/>User group  added successfully";
      }else{
        echo "<img src=\"images/notok.gif\" alt=\"X\"/>Error adding User group";
      }

      echo "<br/><br/><a href=\"admincp.php?ugroups\">";
  echo "UGroups</a><br/>";
      echo "<a href=\"index.php?admincp\"><img src=\"images/admn.gif\" alt=\"*\"/>";
  echo "Admin CP</a><br/>";}
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
  echo "Home</a>";

      echo "</p>";
}
else if(isset($_GET['edtfrm']))
{
  $fid = $_POST["fid"];
  $frname = $_POST["frname"];
  $frpos = $_POST["frpos"];
  $fcid = $_POST["fcid"];
    
      echo "<p align=\"center\">";   
if(isadmin(getuid_sid($sid)))
  {
        echo $frname;
        echo "<br/>";
        $res = mysql_query("UPDATE fun_forums SET name='".$frname."', position='".$frpos."', cid='".$fcid."' WHERE id='".$fid."'");

        if($res)
      {
        echo "<img src=\"images/ok.gif\" alt=\"O\"/>Forum  updated successfully";
      }else{
        echo "<img src=\"images/notok.gif\" alt=\"X\"/>Error updating Forum ";
      }

      echo "<br/><br/><a href=\"admincp.php?forums\">";
  echo "Forums</a><br/>";
      echo "<a href=\"index.php?admincp\"><img src=\"images/admn.gif\" alt=\"*\"/>";
  echo "Admin CP</a><br/>";}
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
  echo "Home</a>";

      echo "</p>";
}
else if(isset($_GET['edtcat']))
{
  $fcid = $_POST["fcid"];
  $fcname = $_POST["fcname"];
  $fcpos = $_POST["fcpos"];
    
      echo "<p align=\"center\">";   
if(isadmin(getuid_sid($sid)))
  {
        echo $fcname;
        echo "<br/>";
        $res = mysql_query("UPDATE fun_fcats SET name='".$fcname."', position='".$fcpos."' WHERE id='".$fcid."'");

        if($res)
      {
        echo "<img src=\"images/ok.gif\" alt=\"O\"/>Forum Category updated successfully";
      }else{
        echo "<img src=\"images/notok.gif\" alt=\"X\"/>Error updating Forum Category";
      }

      echo "<br/><br/><a href=\"admincp.php?fcats\">";
  echo "Forum Categories</a><br/>";
      echo "<a href=\"index.php?admincp\"><img src=\"images/admn.gif\" alt=\"*\"/>";
  echo "Admin CP</a><br/>";}
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
  echo "Home</a>";

      echo "</p>";
}
else if(isset($_GET['delfrm']))
{
  $fid = $_POST["fid"];
    
      echo "<p align=\"center\">";   
if(isadmin(getuid_sid($sid)))
  {
        
        echo "<br/>";
        $res = mysql_query("DELETE FROM fun_forums WHERE id='".$fid."'");

        if($res)
      {
        echo "<img src=\"images/ok.gif\" alt=\"O\"/>Forum  deleted successfully";
      }else{
        echo "<img src=\"images/notok.gif\" alt=\"X\"/>Error deleting Forum ";
      }

      echo "<br/><br/><a href=\"admincp.php?forums\">";
  echo "Forums</a><br/>";
      echo "<a href=\"index.php?admincp\"><img src=\"images/admn.gif\" alt=\"*\"/>";
  echo "Admin CP</a><br/>";}
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
  echo "Home</a>";

      echo "</p>";
}
else if(isset($_GET['delpms']))
{
  

      echo "<p align=\"center\">";   
if(isadmin(getuid_sid($sid)))
  {

        echo "<br/>";
        $res = mysql_query("DELETE FROM fun_private WHERE reported!='1' AND starred='0' AND unread='0'");

        if($res)
      {
        echo "<img src=\"images/ok.gif\" alt=\"O\"/>All PMS except starred, reported, and unread were deleted";
      }else{
        echo "<img src=\"images/notok.gif\" alt=\"X\"/>Database Error!";
      }

      echo "<br/><br/><a href=\"admincp.php?clrdta\">";
  echo "Clear Data</a><br/>";
      echo "<a href=\"index.php?admincp\"><img src=\"images/admn.gif\" alt=\"*\"/>";
  echo "Admin CP</a><br/>";}
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
  echo "Home</a>";

      echo "</p>";
}
else if(isset($_GET['notify']))
{
  vrhonline($sid, $uid);
if(isadmin(getuid_sid($sid)))
  {

        $res = mysql_query("DELETE FROM fun_notify WHERE unread='0'");

        if($res)
      {
        echo "Sva pogledana obavestenja su obrisana...";
      }else{
        echo "Obavestenja nisu obrisana...";
      }
}

  dnoonline($sid, $uid);
exit();
}

else if(isset($_GET['clrmlog']))
{


      echo "<p align=\"center\">";   
if(isadmin(getuid_sid($sid)))
  {

        echo "<br/>";
        $res = mysql_query("DELETE FROM fun_mlog");
        echo mysql_error();
        if($res)
      {
        echo "<img src=\"images/ok.gif\" alt=\"O\"/>ModLog Cleared Successfully";
      }else{
        echo "<img src=\"images/notok.gif\" alt=\"X\"/>Database Error!";
      }

      echo "<br/><br/><a href=\"admincp.php?clrdta\">";
  echo "Clear Data</a><br/>";
      echo "<a href=\"index.php?admincp\"><img src=\"images/admn.gif\" alt=\"*\"/>";
  echo "Admin CP</a><br/>";}
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
  echo "Home</a>";

      echo "</p>";
}

else if(isset($_GET['delsht']))
{

      echo "<p align=\"center\">";   
if(isadmin(getuid_sid($sid)))
  {
        $altm = time()-(20*24*60*60);
        echo "<br/>";
        $res = mysql_query("DELETE FROM fun_shouts WHERE shtime<'".$altm."'");
        $res = mysql_query("DELETE FROM fun_komentari WHERE dtime<'".$altm."'");

        if($res)
      {
        echo "<img src=\"images/ok.gif\" alt=\"O\"/>Shouts Older Than 5 days were deleted";
      }else{
        echo "<img src=\"images/notok.gif\" alt=\"X\"/>Database Error!";
      }

      echo "<br/><br/><a href=\"admincp.php?clrdta\">";
  echo "Clear Data</a><br/>";
      echo "<a href=\"index.php?admincp\"><img src=\"images/admn.gif\" alt=\"*\"/>";
  echo "Admin CP</a><br/>";}
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
  echo "Home</a>";

      echo "</p>";
}

else if(isset($_GET['delgrp']))
{
  $ugid = $_POST["ugid"];
      echo "<p align=\"center\">";   
if(isadmin(getuid_sid($sid)))
  {

        echo "<br/>";
        $res = mysql_query("DELETE FROM fun_groups WHERE id='".$ugid."'");

        if($res)
      {
        echo "<img src=\"images/ok.gif\" alt=\"O\"/>UGroup  deleted successfully";
      }else{
        echo "<img src=\"images/notok.gif\" alt=\"X\"/>Error deleting UGroup";
      }

      echo "<br/><br/><a href=\"admincp.php?ugroups\">";
  echo "UGroups</a><br/>";
      echo "<a href=\"index.php?admincp\"><img src=\"images/admn.gif\" alt=\"*\"/>";
  echo "Admin CP</a><br/>";}
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
  echo "Home</a>";

      echo "</p>";
}

else if(isset($_GET['delrss']))
{
  $rssid = $_POST["rssid"];
    
      echo "<p align=\"center\">";   
if(isadmin(getuid_sid($sid)))
  {
        echo "<br/>";
        $res = mysql_query("DELETE FROM fun_rss WHERE id='".$rssid."'");

        if($res)
      {
        echo "<img src=\"images/ok.gif\" alt=\"O\"/>Source  deleted successfully";
      }else{
        echo "<img src=\"images/notok.gif\" alt=\"X\"/>Database Error";
      }

      echo "<br/><br/><a href=\"admincp.php?manrss\">";
  echo "Manage RSS</a><br/>";
      echo "<a href=\"index.php?admincp\"><img src=\"images/admn.gif\" alt=\"*\"/>";
  echo "Admin CP</a><br/>";}
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
  echo "Home</a>";

      echo "</p>";
}

else if(isset($_GET['delchr']))
{
  $chrid = $_POST["chrid"];
    
      echo "<p align=\"center\">";   
if(isadmin(getuid_sid($sid)))
  {
        echo "<br/>";
        $res = mysql_query("DELETE FROM fun_rooms WHERE id='".$chrid."'");

        if($res)
      {
        echo "<img src=\"images/ok.gif\" alt=\"O\"/>Room  deleted successfully";
      }else{
        echo "<img src=\"images/notok.gif\" alt=\"X\"/>Database Error";
      }

      echo "<br/><br/><a href=\"admincp.php?chrooms\">";
  echo "Chatrooms</a><br/>";
      echo "<a href=\"index.php?admincp\"><img src=\"images/admn.gif\" alt=\"*\"/>";
  echo "Admin CP</a><br/>";}
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
  echo "Home</a>";

      echo "</p>";
}

else if(isset($_GET['delu']))
{
  $who = $_GET["who"];
    
      echo "<p align=\"center\">";   
if(isadmin(getuid_sid($sid)))
  {

        echo "<br/>";
        $res = mysql_query("DELETE FROM fun_buddies WHERE tid='".$who."' OR uid='".$who."'");
    $res = mysql_query("DELETE FROM fun_gbook WHERE gbowner='".$who."' OR gbsigner='".$who."'");
    $res = mysql_query("DELETE FROM fun_ignore WHERE name='".$who."' OR target='".$who."'");
    $res = mysql_query("DELETE FROM fun_mangr WHERE uid='".$who."'");
    $res = mysql_query("DELETE FROM fun_modr WHERE name='".$who."'");
    $res = mysql_query("DELETE FROM fun_penalties WHERE uid='".$who."' OR exid='".$who."'");
    $res = mysql_query("DELETE FROM fun_posts WHERE uid='".$who."'");
    $res = mysql_query("DELETE FROM fun_private WHERE byuid='".$who."' OR touid='".$who."'");
    $res = mysql_query("DELETE FROM fun_shouts WHERE shouter='".$who."'");
    $res = mysql_query("DELETE FROM fun_topics WHERE authorid='".$who."'");
    $res = mysql_query("DELETE FROM fun_brate WHERE uid='".$who."'");
    $res = mysql_query("DELETE FROM fun_games WHERE uid='".$who."'");
    $res = mysql_query("DELETE FROM fun_presults WHERE uid='".$who."'");
    $res = mysql_query("DELETE FROM fun_vault WHERE uid='".$who."'");
    $res = mysql_query("DELETE FROM fun_blogs WHERE bowner='".$who."'");
    $res = mysql_query("DELETE FROM fun_chat WHERE chatter='".$who."'");
    $res = mysql_query("DELETE FROM fun_chat WHERE who='".$who."'");
    $res = mysql_query("DELETE FROM fun_chonline WHERE uid='".$who."'");
    $res = mysql_query("DELETE FROM fun_online WHERE userid='".$who."'");
    $res = mysql_query("DELETE FROM fun_ses WHERE uid='".$who."'");
    $res = mysql_query("DELETE FROM fun_xinfo WHERE uid='".$who."'");
    deleteMClubs($who);
      $res = mysql_query("DELETE FROM fun_users WHERE id='".$who."'");

        if($res)
      {
        echo "<img src=\"images/ok.gif\" alt=\"O\"/>User  deleted successfully";
      }else{
        echo "<img src=\"images/notok.gif\" alt=\"X\"/>Error deleting UGroup";
      }

      echo "<br/><br/><a href=\"admincp.php?chuinfo\">";
  echo "User info</a><br/>";
      echo "<a href=\"index.php?admincp\"><img src=\"images/admn.gif\" alt=\"*\"/>";
  echo "Admin CP</a><br/>";}
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
  echo "Home</a>";

      echo "</p>";
}


//////////// Delete users posts
else if(isset($_GET['delxp']))
{
  $who = $_GET["who"];
    
      echo "<p align=\"center\">";   
if(isadmin(getuid_sid($sid)))
  {

        echo "<br/>";
    $res = mysql_query("DELETE FROM fun_posts WHERE uid='".$who."'");
    $res = mysql_query("DELETE FROM fun_topics WHERE authorid='".$who."'");
      

        if($res)
      {
	  mysql_query("UPDATE fun_users SET plusses='0' where id='".$who."'");
        echo "<img src=\"images/ok.gif\" alt=\"O\"/>User Posts deleted successfully";
      }else{
        echo "<img src=\"images/notok.gif\" alt=\"X\"/>Error deleting UPosts";
      }

      echo "<br/><br/><a href=\"admincp.php?chuinfo\">";
  echo "User info</a><br/>";
      echo "<a href=\"index.php?admincp\"><img src=\"images/admn.gif\" alt=\"*\"/>";
  echo "Admin CP</a><br/>";}
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
  echo "Home</a>";

      echo "</p>";
}

else if(isset($_GET['delcat']))
{
  $fcid = $_POST["fcid"];
    
      echo "<p align=\"center\">";   
if(isadmin(getuid_sid($sid)))
  {
        echo $fcname;
        echo "<br/>";
        $res = mysql_query("DELETE FROM fun_fcats WHERE id='".$fcid."'");

        if($res)
      {
        echo "<img src=\"images/ok.gif\" alt=\"O\"/>Forum Category deleted successfully";
      }else{
        echo "<img src=\"images/notok.gif\" alt=\"X\"/>Error deleting Forum Category";
      }

      echo "<br/><br/><a href=\"admincp.php?fcats\">";
  echo "Forum Categories</a><br/>";
      echo "<a href=\"index.php?admincp\"><img src=\"images/admn.gif\" alt=\"*\"/>";
  echo "Admin CP</a><br/>";}
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
