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
$action = $_GET["action"];
$sid = $_SESSION["sid"];
$uid = getuid_sid($sid);
	vrh($sid);
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
if($action=="guessgm")
{
    addonline(getuid_sid($sid),"PLAYING GTN","");
    
  echo "<p align=\"center\">";
  $gid = $_POST["gid"];
  $un = $_POST["un"];

  if($gid=="")
  {
        mysql_query("DELETE FROM fun_games WHERE uid='".$uid."'");
        mt_srand((double)microtime()*1000000);
        $rn = mt_rand(1,100);
        mysql_query("INSERT INTO fun_games SET uid='".$uid."', gvar1='8', gvar2='".$rn."'");
        $tries = 8;
        $gameid = mysql_fetch_array(mysql_query("SELECT id FROM fun_games WHERE uid='".$uid."'"));
        $gid=$gameid[0];
  }else{
    $ginfo = mysql_fetch_array(mysql_query("SELECT gvar1,gvar2 FROM fun_games WHERE id='".$gid."' AND uid='".$uid."'"));
    $tries = $ginfo[0]-1;
    mysql_query("UPDATE fun_games SET gvar1='".$tries."' WHERE id='".$gid."'");
    $rn = $ginfo[1];
  }
  if ($tries>0)
                {
                $gmsg = "<small>Just try to guess the number before you have no more tries, the number is between 1-100</small><br/><br/>";
                echo $gmsg;
                $tries = $tries-1;
                $gpl = $tries*3;
                echo "Tries:$tries, Plusses:$gpl<br/><br/>";
                      if ($un==$rn){
                        $gpl = $gpl+3;
                        $ugpl = mysql_fetch_array(mysql_query("SELECT gplus FROM fun_users WHERE id='".$uid."'"));
                        $ugpl = $gpl + $ugpl[0];
                        mysql_query("UPDATE fun_users SET gplus='".$ugpl."' WHERE id='".$uid."'");
                        echo "<small>Congrats! the number was $rn, $gpl Plusses has been added to your Game Plusses, <a href=\"games.php?guessgm\">New Game</a></small><br/><br/>";
                      }else{
                        if($un <$rn)
                        {
                          echo "Try bigger number than $un !<br/><br/>";
                        }else{
                            echo "Try smaller number than $un !<br/><br/>";
                        }
echo "<form action=\"games.php?guessgm\" method=\"post\">";
echo "Your Guess: <input type=\"text\" name=\"un\" format=\"*N\" size=\"3\" value=\"$un\"/>";
		
		echo "<input type=\"submit\" value=\"Try\"/>";
		echo "<input type=\"hidden\" name=\"gid\" value=\"$gid\"/>";
		echo "</form";
		echo "<br/>";
                      }


                }else{
                    $gmsg = "<small>GAME OVER, <a href=\"games.php?guessgm\">New Game</a></small><br/><br/>";
                    echo $gmsg;
                }
  echo "<br/><br/><a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p></card>";
}else if($action == "hangman")
{
    addonline(getuid_sid($sid),"PLAYING hangman","");
    echo "<card id=\"main\" title=\"Hangman\">";
  echo "<p align=\"center\">";
  $gid = $_GET["gid"];
  $gchr = $_GET["gchr"];
	$alpha = "abcdefghijklmnopqrstuvwxyz";
  if($gid=="")
  {
		mysql_query("DELETE FROM fun_games WHERE uid='".$uid."'");
		$hmid = mysql_fetch_array(mysql_query("SELECT id FROM fun_hangman ORDER BY RAND() LIMIT 1"));
		mysql_query("INSERT INTO fun_games SET uid='".$uid."', gvar1='abcdefghijklmnopqrstuvwxyz', gvar2='', gvar3='', gvar4='".$hmid[0]."'");
        $gameid = mysql_fetch_array(mysql_query("SELECT id FROM fun_games WHERE uid='".$uid."'"));
        $gid=$gameid[0];
    }else{
		$ginfo = mysql_fetch_array(mysql_query("SELECT gvar1,gvar2, gvar3, gvar4 FROM fun_games WHERE id='".$gid."' AND uid='".$uid."'"));
  }
  if(strlen($ginfo[1])<6)
  {
	$txg = mysql_fetch_array(mysql_query("SELECT text, dscr FROM fun_hangman WHERE id='".$ginfo[3]."'"));
	$tofn = getchars($txg[0]);
	
	
  }
  echo "<br/><br/><a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";

  
}else{
   addonline(getuid_sid($sid),"Lost in Games lol","");
    
  echo "<p align=\"center\">";
  echo "I don't know how did you get into here, but there's nothing to show<br/><br/>";
  echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Home</a>";
  echo "</p>";
}
function getchars($text)
{
	$text = strtolower($text);
	$abc = "abcdefghijklmnopqrstuvwxyz";
	$rts = "";
	for ($i=0; $i<strlen($text); $i++)
	{
		$onc = substr($text,$i, 1);
		$pos = strpos($abc,$onc);
		if($pos===false)
		{
			//meh
		}else{
			$pos = strpos($rts, $onc);
			if($pos===false)
			{
				$rts .= $onc;
			}
		}
	}
	return $rts;
}
	echo "</body>";
	echo "</html>";
?>
