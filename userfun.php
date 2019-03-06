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

cleardata();
if(isipbanned($uip,$ubr))
    {
      if(!isshield(getuid_sid($sid)))
      {
        
      echo "<p align=\"center\">";
      echo "<img src=\"images/notok.gif\" alt=\"x\"/><br/>";
      echo "This IP address is blocked<br/>";
      echo "<br/>";
      echo "How ever we grant a shield against IP-Ban for our great users, you can try to see if you are shielded by trying to log-in, if you kept coming to this page that means you are not shielded, so come back when the ip-ban period is over<br/><br/>";
      $banto = mysql_fetch_array(mysql_query("SELECT  timeto FROM fun_penalties WHERE  penalty='2' AND ipadd='".$uip."' AND browserm='".$ubr."' LIMIT 1 "));
      //echo mysql_error();
      $remain =  $banto[0] - time();
      $rmsg = gettimemsg($remain);
      echo " IP: $rmsg<br/><br/>";
      
      echo "</p>";
      echo "<p>";
  echo "UserID: <input name=\"loguid\" format=\"*x\" maxlength=\"30\"/><br/>";
  echo "Password: <input type=\"password\" name=\"logpwd\"  maxlength=\"30\"/><br/>";
  echo "<anchor>LOGIN<go href=\"login.php\" method=\"get\">";
  echo "<postfield name=\"loguid\" value=\"$(loguid)\"/>";
  echo "<postfield name=\"logpwd\" value=\"$(logpwd)\"/>";
  echo "</go></anchor>";
  echo "</p>";
      exit();
      }
    }

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
    

//echo isbanned($uid);
if(isbanned($uid))
    {
        
      echo "<p align=\"center\">";
      echo "<img src=\"images/notok.gif\" alt=\"x\"/><br/>";
      echo "You are <b>Banned</b><br/>";
      $banto = mysql_fetch_array(mysql_query("SELECT timeto FROM fun_penalties WHERE uid='".$uid."' AND penalty='1'"));
	  $banres = mysql_fetch_array(mysql_query("SELECT lastpnreas FROM fun_users WHERE id='".$uid."'"));
	  
      $remain = $banto[0]- time();
      $rmsg = gettimemsg($remain);
      echo "Time to finish your penalty: $rmsg<br/><br/>";
	  echo "Ban Reason: $banres[0]";
      //echo "<a href=\"index.php\">Login</a>";
      echo "</p>";
      exit();
    }
	$res = mysql_query("UPDATE fun_users SET browserm='".$ubr."', ipadd='".$uip."' WHERE id='".getuid_sid($sid)."'");
	$wnick = getnick_uid($who);
	$sex = mysql_fetch_array(mysql_query("SELECT sex FROM fun_users WHERE id='".$who."'"));
	if($sex[0]=="M")
	{
		$pron = "he";
		$pron2 = "him";
		$pron3 = "his";
	}else{
		$pron = "she";
		$pron2 = "her";
		$pron3 = "her";
	}
	addonline($uid,"having fun with another member :P","");
if(isset($_GET['profile']))
{
	
    
    
    echo "<p><small>";
    $nopl = mysql_fetch_array(mysql_query("SELECT gplus FROM fun_users WHERE id='".$who."'"));
  echo "Game Plusses: <b>$nopl[0]</b><br/><br/>";
  
  ///////////////////////////////////////////////////////
	echo "<img src=\"smilies/smooch.gif\" alt=\"smooch\"/><b>Smooch's:</b><br/>";
  $nopl = mysql_fetch_array(mysql_query("SELECT COUNT(id) FROM fun_usfun WHERE uid='".$who."' AND action='smooch'"));
  echo "Have smooched: <b><a href=\"lists.php?smc&amp;who=$who\">$nopl[0]</a></b> Times<br/>";
  $nopl = mysql_fetch_array(mysql_query("SELECT COUNT(id) FROM fun_usfun WHERE target='".$who."' AND action='smooch'"));
  echo "Have been Smooched: <b><a href=\"lists.php?smd&amp;who=$who\">$nopl[0]</a></b> Times<br/>";
  echo "Poor $wnick, a fat old lady have smooched $pron2 untill $pron almost choked! yes you can smooch $wnick but don't kill $pron2<br/>";
	echo "<a href=\"userfun.php?smooch&amp;who=$who\">Smooch!</a><br/><br/>";
  
  //////////////////////////////////////////////////////
  echo "<img src=\"smilies/kick.gif\" alt=\"kick\"/><b>Kicks:</b><br/>";
  $nopl = mysql_fetch_array(mysql_query("SELECT COUNT(id) FROM fun_usfun WHERE uid='".$who."' AND action='kick'"));
  echo "Have Kicked: <b><a href=\"lists.php?kck&amp;who=$who\">$nopl[0]</a></b> Times<br/>";
  $nopl = mysql_fetch_array(mysql_query("SELECT COUNT(id) FROM fun_usfun WHERE target='".$who."' AND action='kick'"));
  echo "Have been Kicked: <b><a href=\"lists.php?kcd&amp;who=$who\">$nopl[0]</a></b> Times<br/>";
  echo "And yes $wnick have been kicked on the shin untill it's smashed, I think it'll be funny to kick $wnick on the chin hehe<br/>";
	echo "<a href=\"userfun.php?kick&amp;who=$who\">Kick!</a><br/><br/>";
	
	///////////////////////////////////////////////////////
	echo "<img src=\"smilies/poke.gif\" alt=\"poke\"/><b>Pokes:</b><br/>";
  $nopl = mysql_fetch_array(mysql_query("SELECT COUNT(id) FROM fun_usfun WHERE uid='".$who."' AND action='poke'"));
  echo "Have Poked: <b><a href=\"lists.php?pok&amp;who=$who\">$nopl[0]</a></b> Times<br/>";
  $nopl = mysql_fetch_array(mysql_query("SELECT COUNT(id) FROM fun_usfun WHERE target='".$who."' AND action='poke'"));
  echo "Have been Poked: <b><a href=\"lists.php?pkd&amp;who=$who\">$nopl[0]</a></b> Times<br/>";
  echo "the last thing that $wnick needs now is another poke, $pron have a hole in $pron3 tummy because of the last poke, and no the other side of the hole is not in $pron3 back, some of us are obsessed with butts you know<br/>";
	echo "<a href=\"userfun.php?poke&amp;who=$who\">Poke!</a><br/><br/>";
	
	///////////////////////////////////////////////////////
	echo "<img src=\"smilies/cuddle.gif\" alt=\"hug\"/><b>Hugs:</b><br/>";
  $nopl = mysql_fetch_array(mysql_query("SELECT COUNT(id) FROM fun_usfun WHERE uid='".$who."' AND action='hug'"));
  echo "Have Hugged: <b><a href=\"lists.php?hgs&amp;who=$who\">$nopl[0]</a></b> Times<br/>";
  $nopl = mysql_fetch_array(mysql_query("SELECT COUNT(id) FROM fun_usfun WHERE target='".$who."' AND action='hug'"));
  echo "Have been Hugged: <b><a href=\"lists.php?hgd&amp;who=$who\">$nopl[0]</a></b> Times<br/>";
  echo "Poor $wnick, remember that fat lady who choked $pron2? well.. she hugged $pron2 untill she broke $pron3 ribs, $pron surely needs a hug from you now<br/>";
	echo "<a href=\"userfun.php?hug&amp;who=$who\">Hug!</a>";
	
    echo "</small></p>";

    echo "<p align=\"center\">";
	echo "<a href=\"index.php?givegp&amp;who=$who\">Donate Game Plusses</a><br/>";
	echo "<a href=\"index.php?viewuser&amp;who=$who\">";
echo "$wnick's profile</a><br/>";
    echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
    
}

else if (isset($_GET['hug']) || isset($_GET['smooch']) || isset($_GET['kick']) || isset($_GET['poke']))
{
	
    echo "<p align=\"center\">";
	$nopl = mysql_fetch_array(mysql_query("SELECT gplus FROM fun_users WHERE id='".$uid."'"));
	if($nopl[0]<5)
	{
		echo "<img src=\"images/notok.gif\" alt=\"X\"/>You should have at least 5 game plusses to perform an action on other members<br/><br/>";
	}else{
		$actime = mysql_fetch_array(mysql_query("SELECT actime FROM fun_usfun WHERE uid='".$uid."' AND target='".$who."' ORDER BY actime DESC LIMIT 1"));
		$timeout = $actime[0] + (10*24*60*60);
		if(time()<$timeout)
		{
			echo "<img src=\"images/notok.gif\" alt=\"X\"/>You can only perform one action on the same user every 10 days<br/><br/>";
		}else{
			if($uid==$who)
			{
				echo "<img src=\"images/notok.gif\" alt=\"X\"/>Why on earth you wanna $action your self?<br/><br/>";
			}else{
				$res = mysql_query("INSERT INTO fun_usfun SET uid='".$uid."', action='".$action."', target='".$who."', actime='".time()."'");
				if(!$res)
				{
					echo mysql_error()."<br/>";
					echo "<img src=\"images/notok.gif\" alt=\"X\"/>DATABASE ERROR!<br/><br/>";
				}else{
					mysql_query("UPDATE fun_users SET gplus=gplus-5 WHERE id='".$uid."'");
					echo "<img src=\"images/ok.gif\" alt=\"+\"/>You just have ".$action."ed $wnick, where did you do that, I'm not gonna tell <img src=\"smilies/spiteful.gif\" alt=\"haba\"/><br/><br/>";
					echo "5 game plusses were subtracted from you, and you can't perform any other action on $wnick for the next 10 days<br/><br/>";
				}
			}
		}
		
	}
	echo "<a href=\"index.php?givegp&amp;who=$who\">Donate Game Plusses</a><br/>";
	echo "<a href=\"index.php?viewuser&amp;who=$who\">";
echo "$wnick's profile</a><br/>";
    echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
    
}
	echo "</body>";
	echo "</html>";
?>
