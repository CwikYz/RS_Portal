<?php


include("core.php");
include("config.php");


header("Content-type: text/html; charset=ISO-8859-1");
echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>";
echo "<!DOCTYPE html PUBLIC \"-//WAPFORUM//DTD XHTML Mobile 1.0//EN\"\"http://www.wapforum.org/DTD/xhtml-mobile10.dtd\">";
echo "<html xmlns=\"http://www.w3.org/1999/xhtml\">";

connectdb();

        
        //clearnc();
$action=$_GET["action"];
$id=$_GET["id"];
$sid = $_SESSION["sid"];
	vrh($sid);
$botid = "b7df63a24e363170";
$input = $_POST["input"];
$custid=$_POST["custid"];
$hostname = "www.pandorabots.com";
$hostpath = "/pandora/talk-xml";
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


                 //start of main card
        echo "<p align=\"center\">";
        echo "<br/>";
        
        
        addonline(getuid_sid($sid),"Chatting to auto foggy","");
    if ($input!="")
    {

        $sendData = "botid=".$botid."&input=".urlencode($input)."&custid=".$custid;
    	// Send the request to Pandorabot
    	$result = PostToHost($hostname, $hostpath, $sendData);
    	//TODO: Process the returned XML as an XML document instead of a big string.
    	// Use string manipulations to pull out the 'custid' and 'that' values.
    	$pos = strpos($result, "custid=\"");

    	// Extract the custid
    	if ($pos === false) {
    		$custid = "";
    	} else {
    		$pos += 8;
    		$endpos = strpos($result, "\"", $pos);
    		$custid = substr($result, $pos, $endpos - $pos);
    	}
    	// Extrat <that> - this is the reply from the Pandorabot
    	$pos = strpos($result, "<that>");
    	if ($pos === false) {
    		$reply = "";
    	} else {
    		$pos += 6;
    		$endpos = strpos($result, "</that>", $pos);
    		$reply = unhtmlspecialchars(substr($result, $pos, $endpos - $pos));
    	}

        //echo htmlspecialchars( $reply);
        $hers = $reply;
        $hers = parsemsg($hers);
             $input=htmlspecialchars($input);
             $nick = getnick_uid($uid);
             echo "<br/><b>$nick: </b>$input<br/>";
             echo "<b>auto foggy: </b>$hers<br/>";
		echo "<form action=\"chatbot.php?sid=$sid\" method=\"post\">";
        echo "<br/><input type=\"text\" name=\"input\" maxlength=\"120\" value=\"$input\"/>";
echo "<input type=\"submit\" value=\"Say\"/>";
        	echo "</form>";
		echo "<br/>";
    }else{
      echo "Hello, now you can chat with our chatbot<br/> her name is auto foggy, have fun<br/>";
echo "<form action=\"chatbot.php?sid=$sid\" method=\"post\">";
      echo "<input type=\"text\" name=\"input\" maxlength=\"120\" value=\"$input\"/>";
		echo "<input type=\"submit\" value=\"Say\"/>";
        	echo "</form>";
		echo "<br/>";
    }
    
    echo "<br/><br/><a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>Home</a><br/>";
echo "</p>";
        

function unhtmlspecialchars( $string )
{
  $string = str_replace ( '&amp;', '&', $string );
  $string = str_replace ( '&#039;', '\'', $string );
  $string = str_replace ( '&quot;', '"', $string );
  $string = str_replace ( '&lt;', '<', $string );
  $string = str_replace ( '&gt;', '>', $string );
  $string = str_replace ( '&uuml;', '?', $string );
  $string = str_replace ( '&Uuml;', '?', $string );
  $string = str_replace ( '&auml;', '?', $string );
  $string = str_replace ( '&Auml;', '?', $string );
  $string = str_replace ( '&ouml;', '?', $string );
  $string = str_replace ( '&Ouml;', '?', $string );
  return $string;
}

	echo "</body>";
	echo "</html>";
?>
