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
$pmtext = $_POST["pmtext"];
$who = $_GET["who"];
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

if(isset($_GET['sendpm']))
{

   
  $whonick = getnick_uid($who);
  $byuid = getuid_sid($sid);
  vrhonline($sid,$byuid);
  $tm = time();
  $lastpm = mysql_fetch_array(mysql_query("SELECT MAX(timesent) FROM fun_private WHERE byuid='".$byuid."'"));
  $pmfl = $lastpm[0]+getpmaf();
  if($byuid==1)$pmfl=0;
  if($pmfl<$tm)
  {
    if(!isblocked($pmtext,$byuid))
    {
    if((!isignored($byuid, $who))&&(!istrashed($byuid)))
    {
  $res = mysql_query("INSERT INTO fun_private SET text='".$pmtext."', byuid='".$byuid."', touid='".$who."', timesent='".$tm."'");
  }else{
    $res = true;
  }
  if($res)
  {

    echo "Poruka je uspesno poslata $whonick<br/><br/>";
    echo parsepm($pmtext, $sid);
    
  }else{
    echo "Nemogu da posaljem poruku za $whonick<br/><br/>";
  }
  }else{
    $bantime = time() + (7*24*60*60);
    echo "Nemogu poslati poruku $whonick<br/><br/>";
    mysql_query("UPDATE fun_users SET plusses='0', shield='0' WHERE id='".$byuid."'");
    mysql_query("INSERT INTO fun_private SET text='".$pmtext."', byuid='".$byuid."', touid='2', timesent='".$tm."'");
  }
  }else{
    $rema = $pmfl - $tm;
    echo "Kontrola: $rema Sekundi<br/><br/>";
  }
  dnoonline($sid,$byuid);
    
}

else if($pact=="frd"){
addonline(getuid_sid($sid),"Forwarding PM","");
echo vrhonline($sid,$uid);
if(getuid_sid($sid)==$pminfo[2]||getuid_sid($sid)==$pminfo[1]){
echo "Prosledi na email::<br/><br/>";
echo "<form action=\"inbxproc.php?frdpm\" method=\"post\"><input type=\"text\" name=\"email\" maxlength=\"250\"/><input type=\"hidden\" name=\"pmid\" value=\"$pmid\"/><br/>";
echo "<input type=\"submit\" name=\"submit\" value=\"Posalji\"/>";
echo "</form>";
}else{
echo "Nije tvoje";

}
  echo dnoonline($sid,$uid);

}

else if(isset($_GET['sendto']))
{
  
  $pmtou = $_POST["pmtou"];
  $who = getuid_nick($pmtou);
    if($who==0)
    {
      echo "Korisnik nepostoji<br/>";
    }else{
$whonick = getnick_uid($who);
  $byuid = getuid_sid($sid);
  $tm = time();
  $lastpm = mysql_fetch_array(mysql_query("SELECT MAX(timesent) FROM fun_private WHERE byuid='".$byuid."'"));
  $pmfl = $lastpm[0]+getpmaf();
  if($pmfl<$tm)
  {
    if(!isblocked($pmtext,$byuid))
    {
    if((!isignored($byuid, $who))&&(!istrashed($byuid)))
    {
  $res = mysql_query("INSERT INTO fun_private SET text='".$pmtext."', byuid='".$byuid."', touid='".$who."', timesent='".$tm."'");
  }else{
    $res = true;
  }
  if($res)
  {
    echo "<img src=\"images/ok.gif\" alt=\"O\"/>";
    echo "Poruka uspesno poslata za $whonick<br/><br/>";
    echo parsepm($pmtext, $sid);

  }else{
    echo "<img src=\"images/notok.gif\" alt=\"X\"/>";
    echo "Poruka nije poslata za $whonick<br/><br/>";
  }
  }else{
   $bantime = time() + (7*24*60*60);
    echo "<img src=\"images/notok.gif\" alt=\"X\"/>";
    echo "Poruka nije poslata za $whonick<br/><br/>";
    mysql_query("UPDATE fun_users SET plusses='0', shield='0' WHERE id='".$byuid."'");
    mysql_query("INSERT INTO fun_private SET text='".$pmtext."', byuid='".$byuid."', touid='2', timesent='".$tm."', reported='1'");
  }
  }else{
    $rema = $pmfl - $tm;
    echo "<img src=\"images/notok.gif\" alt=\"X\"/>";
    echo "Kontrola: $rema Sekundi<br/><br/>";
  }

    }
  
  echo dnoonline($sid,$uid);
    
}
else if(isset($_GET['proc']))
{
    $pmact = $_POST["pmact"];
    $pact = explode("-",$pmact);
    $pmid = $pact[1];
    $pact = $pact[0];
    
    $pminfo = mysql_fetch_array(mysql_query("SELECT text, byuid, touid, reported FROM fun_private WHERE id='".$pmid."'"));
    if($pact=="rep")
    {
      addonline(getuid_sid($sid),"Sending PM","");
       
      $whonick = getnick_uid($pminfo[1]);
  echo "Poruka za $whonick<br/><br/>";
  echo "<form action=\"inbxproc.php?sendpm&amp;who=$pminfo[1]\" method=\"post\">";
  echo "<input name=\"pmtext\" maxlength=\"999999\"/><br/>";
  echo "<input type=\"submit\" value=\"Posalji\"/>";
echo "</form>";
  
    }else if($pact=="del")
    {
        addonline(getuid_sid($sid),"Deleting PM","");
        if(getuid_sid($sid)==$pminfo[2])
        {
          if($pminfo[3]=="1")
          {
            
            echo "Poruka je na inspekciji kod administracije i nju nemozete obrisati!!!";
          }else{
          $del = mysql_query("DELETE FROM fun_private WHERE id='".$pmid."' ");
          if($del)
          {
            echo "Poruka je uspesno izbrisana!";
          }else{
            echo "Poruka nije obrisana!";
          }
          }

        }else{
          echo "GRESKA";
        }
    }else if($pact=="str")
    {
        addonline(getuid_sid($sid),"Starring PM","");
        if(getuid_sid($sid)==$pminfo[2])
        {
          $str = mysql_query("UPDATE fun_private SET starred='1' WHERE id='".$pmid."' ");
          if($str)
          {
            echo "Poruka je uspesno oznacena!";
          }else{
            echo "Nemogu da oznacim poruku!";
          }
        }else{
          echo "Greska";
        }
    }else if($pact=="ust")
    {
        addonline(getuid_sid($sid),"Unstarring PM","");
        if(getuid_sid($sid)==$pminfo[2])
        {
          $str = mysql_query("UPDATE fun_private SET starred='0' WHERE id='".$pmid."' ");
          if($str)
          {
            echo "Oznaka skinuta!";
          }else{
            echo "Nemogu da skinem oznaku!";
          }
        }else{
          echo "GRESKA";
        }
    }else if($pact=="rpt")
    {
        addonline(getuid_sid($sid),"Reporting PM","");
        if(getuid_sid($sid)==$pminfo[2])
        {
          if($pminfo[3]=="0")
          {
          $str = mysql_query("UPDATE fun_private SET reported='1' WHERE id='".$pmid."' ");
          if($str)
          {
            echo "Administracija je obavestena!";
          }else{
            echo "GRESKA";
          }
          }else{
            echo "Administracija je vec obavestena!";
          }
        }else{
          echo "Greska";
        }
    }
	else if($pact=="frd")
    {
        addonline(getuid_sid($sid),"Forwarding PM","");
        if(getuid_sid($sid)==$pminfo[2]||getuid_sid($sid)==$pminfo[1])
        {
          
  echo "Prosledina mail:<br/><br/>";
  echo "<input name=\"email\" maxlength=\"250\"/><br/>";
  echo "<anchor>Ok<go href=\"inbxproc.php?frdpm\" method=\"post\">";
  echo "<postfield name=\"email\" value=\"$(email)\"/>";
  echo "<postfield name=\"pmid\" value=\"$pmid\"/>";
  echo "</go></anchor>";
        }else{
          echo "Nije vase";
        }
    }
	else if($pact=="dnl")
    {
        addonline(getuid_sid($sid),"Downloading PM","");
        if(getuid_sid($sid)==$pminfo[2]||getuid_sid($sid)==$pminfo[1])
        {
          echo "Preuzimanje uspesno<br/><br/>";
		  echo "<a href=\"rwdpm.php?dpm&amp;pmid=$pmid\">Preuzmi poruku</a>";
        }else{
          echo "Nije vase";
        }
    }
  echo dnoonline($sid,$uid);
    
  }

else if(isset($_GET['proall']))
{
    $pact = $_POST["pmact"];
    
    addonline(getuid_sid($sid),"Deleting PMs","");
      $uid = getuid_sid($sid);
    if($pact=="ust")
    {
      
      $del = mysql_query("DELETE FROM fun_private WHERE touid='".$uid."' AND reported !='1' AND starred='0' And unread='0'");
      if($del)
          {
            echo "OBRISANO";
          }else{
            echo "GRESKA";
          }
    }else if($pact=="red")
    {
       
        $del = mysql_query("DELETE FROM fun_private WHERE touid='".$uid."' AND reported !='1' and unread='0'");
      if($del)
          {
            echo "OBRISANO!";
          }else{
            echo "<img src=\"images/notok.gif\" alt=\"X\"/>GRESKA";
          }
       
    }else if($pact=="all")
    {
        $del = mysql_query("DELETE FROM fun_private WHERE touid='".$uid."' AND reported !='1'");
      if($del)
          {
            echo "Sve poruke su obrisane!";
          }else{
            echo "GRESKA";
          }
    }
    
  echo dnoonline($sid,$uid);
    
    
  }
else if(isset($_GET['frdpm']))
{
	$email = $_POST["email"];
	$pmid = $_POST["pmid"];
  addonline(getuid_sid($sid),"Forwarding PM","");
  

  $pminfo = mysql_fetch_array(mysql_query("SELECT text, byuid, timesent,touid, reported FROM fun_private WHERE id='".$pmid."'"));
  
  
  if(($pminfo[3]==getuid_sid($sid))||($pminfo[1]==getuid_sid($sid)))
  {
  $from_head = "From: cwikyz@gmail.com";
  $subject = "PM By ".getnick_uid($pminfo[1])." To ".getnick_uid($pminfo[3])." ";
  $content = "Date: ".date("l d/m/y H:i:s", $pminfo[2])."\n\n";
  $content .= $pminfo[0]."\n------------------------\n";
  $content .= "SE CAFE!";
  mail($email, $subject, $content, $from_head);
 echo "<img src=\"images/ok.gif\" alt=\"X\"/>Poruka prosledjena na $email";
  }else{
    echo "<img src=\"images/notok.gif\" alt=\"X\"/>Nije tvoja";
  }
  echo dnoonline($sid,$uid);
    

}

  else{
    addonline(getuid_sid($sid),"Lost in inbox lol","");
    
  echo "IDI kutji";
  echo dnoonline($sid,$uid);
}
echo "</body>";
	echo "</html>";
?>
