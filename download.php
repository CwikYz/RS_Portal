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
  $id = $_GET["id"];
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
  echo "<form action=\"login.php\" method=\"get\">";
  echo "username:<br/> <input name=\"loguid\" format=\"*x\" size=\"8\" maxlength=\"30\"/><br/>";
  echo "password:<br/> <input type=\"password\" name=\"logpwd\" size=\"8\" maxlength=\"30\"/><br/>";
echo "<input type=\"submit\" value=\"login &#187;\"/>";
echo "</form>"; 
  echo "</p>";
      exit();
      }
    }

    $uid = getuid_sid($sid);
    if((islogged($sid)==false)||($uid==0))
    {
        
echo "<p align=\"center\">";
echo "<form action=\"login.php\" method=\"get\">";
echo "Korisnik: \t<input name=\"loguid\" format=\"*x\" size=\"12\" maxlength=\"30\"/><br/>";
echo "Lozinka:  \t<input type=\"password\" name=\"logpwd\" size=\"12\" maxlength=\"30\"/><br/>";
echo "<input class=\"button\" type=\"submit\" value=\"Loguj &#187;\"/>";
echo "</form>";  
echo "</p>";
echo dnooffline();
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

///////////////////////////////////////////////////// MAIN PAGE

if(isset($_GET['main']))
{
    $who = $_GET["who"];
    addonline(getuid_sid($sid),"download","");
   echo vrhonline($sid,$uid);
    $uid = getuid_sid($sid);
        $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_download WHERE type='1'"));
        echo "<div class='comment comm_adv'><a href=\"download.php?vaultmusic\"><img src=\"download/muzika.png\" alt=\"*\"/> Muzika($noi[0])</a></div>";
        $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_download WHERE type='4'"));
        echo "<div class='comment comm_adv'><a href=\"download.php?vaultvideos\"><img src=\"download/video.png\" alt=\"*\"/> Video snimci($noi[0])</a></div>";
        $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_download WHERE type='2'"));
        echo "<div class='comment comm_adv'><a href=\"download.php?vaultpics\"><img src=\"download/slike.png\" alt=\"*\"/> Fotografije($noi[0])</a></div>";
        $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_download WHERE type='3'"));
        echo "<div class='comment comm_adv'><a href=\"download.php?vaultgames\"><img src=\"download/igre.png\" alt=\"*\"/> Aplikacije i igrice($noi[0])</a></div>";
        $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_download WHERE type='0'"));
        echo "<div class='comment comm_adv'><a href=\"download.php?vaultother\"><img src=\"download/ostalo.png\" alt=\"*\"/> Ostalo($noi[0])</a></div>";
  ////// UNTILL HERE >>
    
  if(ismod(getuid_sid($sid)))
  {
    echo "<div class='center border_top_light'><a href=\"download.php?dodaj\">";
echo "<img src='img/img_2.png' /></a></div>";
}
	
	
  echo dnoonline($sid,$uid);
    
}
else if(isset($_GET['vaultmusic'])) ///////////////////Muzika
{
    $who = $_GET["who"];
    addonline(getuid_sid($sid),"Browsing Downloads","");
   echo vrhonline($sid,$uid);
    $uid = getuid_sid($sid);



    //////ALL LISTS SCRIPT <<

    if($page=="" || $page<=0)$page=1;
    if($who!="")
    {
    $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_download WHERE uid='".$who."' AND type='1'"));
    }else{
    $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_download WHERE type='1'"));
    }
    $num_items = $noi[0]; //changable
    $items_per_page= 5;
    $num_pages = ceil($num_items/$items_per_page);
    if(($page>$num_pages)&&$page!=1)$page= $num_pages;
    $limit_start = ($page-1)*$items_per_page;

    if($who!="")
    {
        $sql = "SELECT id, title, itemurl, uid, downloads, pudt FROM fun_download WHERE uid='".$who."' AND type='1' ORDER BY pudt DESC LIMIT $limit_start, $items_per_page";
        }else{
$sql = "SELECT id, title, itemurl, uid, downloads, pudt FROM fun_download WHERE type='1' ORDER BY pudt DESC LIMIT $limit_start, $items_per_page";
        }


    if($page>1)
    {
      $ppage = $page-1;
      echo "<div class='center border_top'><a href=\"download.php?$action&amp;page=$ppage&amp;who=$who\"><img src='images/up.png' /></a></div>";
    }
    $items = mysql_query($sql);
    echo mysql_error();
    if(mysql_num_rows($items)>0)
    {
    while ($item = mysql_fetch_array($items))
    {
      $ext = getext($item[2]);
        $ime = getextimg($ext);
        $lnk = "<a href=\"download.php?id=$item[0]\">$ime".htmlspecialchars($item[1])."</a>";
        $downloads = "Preuzeto: <b>$item[4]</b> puta";
        $dateadded = date("d/m/y", $item[5]);
        $dateadded1 = "Dodato: <b>$dateadded</b>";
        
        
      if (isadmin(getuid_sid($sid)))
{
        $delnk = "<a href=\"download.php?delvlt&amp;vid=$item[0]\">[x]</a>";
      }else{
        $delnk = "";
      }
      if($who!="")
      {
        $byusr="";
      }else{
        $unick = getnick_uid($item[3]);
        $ulnk = "<a href=\"index.php?viewuser&amp;who=$item[3]\">$unick</a>";
        $byusr = "Dodao/la: $ulnk";
      }
      echo "<div class='comment'>$lnk <br/>$byusr $delnk<br/>$dateadded1<br/>$downloads<br/></div>";
      
    }
    }
	
    if($page<$num_pages)
    {
      $npage = $page+1;
      echo "<div class='center border_top'><a href=\"download.php?$action&amp;page=$npage&amp;who=$who\"><img src='images/down.png' /></a></div>";
    }
  if(ismod(getuid_sid($sid)))
  {
    echo "<div class='center border_top_light'><a href=\"download.php?dodaj\">";
echo "<img src='img/img_2.png' /></a></div>";
}
    echo "<div class='comment comm_adv'><img src='img/img_42.png' /><a href=\"download.php?main\">Kategorije</a></div>";
  echo dnoonline($sid,$uid);
}

////////////////////////////////////////FOTOGRAFIJE
else if(isset($_GET['vaultpics']))
{
    $who = $_GET["who"];
    addonline(getuid_sid($sid),"Browsing Downloads","");
   echo vrhonline($sid,$uid);
    $uid = getuid_sid($sid);



    //////ALL LISTS SCRIPT <<

    if($page=="" || $page<=0)$page=1;
    if($who!="")
    {
    $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_download WHERE uid='".$who."' AND type='2'"));
    }else{
    $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_download WHERE type='2'"));
    }
    $num_items = $noi[0]; //changable
    $items_per_page= 5;
    $num_pages = ceil($num_items/$items_per_page);
    if(($page>$num_pages)&&$page!=1)$page= $num_pages;
    $limit_start = ($page-1)*$items_per_page;

    if($who!="")
    {
        $sql = "SELECT id, title, itemurl, uid, downloads, pudt FROM fun_download WHERE uid='".$who."' AND type='2' ORDER BY pudt DESC LIMIT $limit_start, $items_per_page";
        }else{
        $sql = "SELECT id, title, itemurl, uid, downloads, pudt FROM fun_download WHERE type='2' ORDER BY pudt DESC LIMIT $limit_start, $items_per_page";
        }


    if($page>1)
    {
      $ppage = $page-1;
      echo "<div class='center border_top'><a href=\"download.php?$action&amp;page=$ppage&amp;who=$who\"><img src='images/up.png' /></a></div>";
    }
    $items = mysql_query($sql);
    echo mysql_error();
    if(mysql_num_rows($items)>0)
    {
    while ($item = mysql_fetch_array($items))
    {
      $ext = getext($item[2]);
        $mysock = getimagesize("$item[2]"); 
        $ime = "<img src=\"$item[2]\" heigh='100' width='100' alt=\"*\"/>";
        $lnk = "<a href=\"download.php?id=$item[0]\">$ime<br/>$item[1]</a>";
        $downloads = "Preuzeto: <b>$item[4]</b> puta";
        $dateadded = date("d/m/y", $item[5]);
        $dateadded1 = "Dodato: <b>$dateadded</b>";
        
      if (isadmin(getuid_sid($sid)))
{
        $delnk = "<a href=\"download.php?delvlt&amp;vid=$item[0]\">[x]</a>";
      }else{
        $delnk = "";
      }
      if($who!="")
      {
        $byusr="";
      }else{
        $unick = getnick_uid($item[3]);
        $ulnk = "<a href=\"index.php?viewuser&amp;who=$item[3]\">$unick</a>";
        $byusr = "Dodao/la: $ulnk";
      }
      echo "<div class='sett_line'>$lnk <br/>$byusr $delnk<br/>$dateadded1<br/>$downloads<br/></div>";
      
    }
    }
    if($page<$num_pages)
    {
      $npage = $page+1;
      echo "<div class='center border_top'><a href=\"download.php?$action&amp;page=$npage&amp;who=$who\"><img src='images/down.png' /></a></div>";
    }
    
  if(ismod(getuid_sid($sid)))
  {
    echo "<div class='center border_top_light'><a href=\"download.php?dodaj\">";
echo "<img src='img/img_2.png' /></a></div>";
}
    echo "<div class='comment comm_adv'><img src='img/img_42.png' /><a href=\"download.php?main\">Kategorije</a></div>";
  echo dnoonline($sid,$uid);
}

//////////////////////////VAULT GAMES
else if(isset($_GET['vaultgames']))
{
    $who = $_GET["who"];
    addonline(getuid_sid($sid),"Browsing Downloads","");
   echo vrhonline($sid,$uid);
    $uid = getuid_sid($sid);



    //////ALL LISTS SCRIPT <<

    if($page=="" || $page<=0)$page=1;
    if($who!="")
    {
    $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_download WHERE uid='".$who."' AND type='3'"));
    }else{
    $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_download WHERE type='3'"));
    }
    $num_items = $noi[0]; //changable
    $items_per_page= 5;
    $num_pages = ceil($num_items/$items_per_page);
    if(($page>$num_pages)&&$page!=1)$page= $num_pages;
    $limit_start = ($page-1)*$items_per_page;

    if($who!="")
    {
        $sql = "SELECT id, title, itemurl, uid, downloads, pudt FROM fun_download WHERE uid='".$who."' AND type='3' ORDER BY pudt DESC LIMIT $limit_start, $items_per_page";
        }else{
$sql = "SELECT id, title, itemurl, uid, downloads, pudt FROM fun_download WHERE type='3' ORDER BY pudt DESC LIMIT $limit_start, $items_per_page";
        }

    if($page>1)
    {
      $ppage = $page-1;
      echo "<div class='center border_top'><a href=\"download.php?$action&amp;page=$ppage&amp;who=$who\"><img src='images/up.png' /></a></div>";
    }

    $items = mysql_query($sql);
    echo mysql_error();
    if(mysql_num_rows($items)>0)
    {
    while ($item = mysql_fetch_array($items))
    {
      $ext = getext($item[2]);
        $ime = getextimg($ext);
        $lnk = "<a href=\"download.php?id=$item[0]\">$ime".htmlspecialchars($item[1])."</a>";
        $downloads = "Preuzeto: <b>$item[4]</b> puta";
        $dateadded = date("d/m/y", $item[5]);
        $dateadded1 = "Dodato: <b>$dateadded</b>";
        
      if (isadmin(getuid_sid($sid)))
{
        $delnk = "<a href=\"download.php?delvlt&amp;vid=$item[0]\">[x]</a>";
      }else{
        $delnk = "";
      }
      if($who!="")
      {
        $byusr="";
      }else{
        $unick = getnick_uid($item[3]);
        $ulnk = "<a href=\"index.php?viewuser&amp;who=$item[3]\">$unick</a>";
        $byusr = "Dodao/la: $ulnk";
      }
      echo "<div class='comment'>$lnk <br/>$byusr $delnk<br/>$dateadded1<br/>$downloads<br/></div>";
      
    }
    }
    if($page<$num_pages)
    {
      $npage = $page+1;
      echo "<div class='center border_top'><a href=\"download.php?$action&amp;page=$npage&amp;who=$who\"><img src='images/down.png' /></a></div>";
    }
    
  if(ismod(getuid_sid($sid)))
  {
    echo "<div class='center border_top_light'><a href=\"download.php?dodaj\">";
echo "<img src='img/img_2.png' /></a></div>";
}
    echo "<div class='comment comm_adv'><img src='img/img_42.png' /><a href=\"download.php?main\">Kategorije</a></div>";
  echo dnoonline($sid,$uid);
}

//////////////////////////VAULT Videos
else if(isset($_GET['vaultvideos']))
{
    $who = $_GET["who"];
    addonline(getuid_sid($sid),"Browsing Downloads","");
   echo vrhonline($sid,$uid);
    $uid = getuid_sid($sid);



    //////ALL LISTS SCRIPT <<

    if($page=="" || $page<=0)$page=1;
    if($who!="")
    {
    $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_download WHERE uid='".$who."' AND type='4'"));
    }else{
    $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_download WHERE type='4'"));
    }
    $num_items = $noi[0]; //changable
    $items_per_page= 5;
    $num_pages = ceil($num_items/$items_per_page);
    if(($page>$num_pages)&&$page!=1)$page= $num_pages;
    $limit_start = ($page-1)*$items_per_page;

    if($who!="")
    {
        $sql = "SELECT id, title, itemurl, uid, downloads, pudt FROM fun_download WHERE uid='".$who."' AND type='4' ORDER BY pudt DESC LIMIT $limit_start, $items_per_page";
        }else{
$sql = "SELECT id, title, itemurl, uid, downloads, pudt FROM fun_download WHERE type='4' ORDER BY pudt DESC LIMIT $limit_start, $items_per_page";
        }

    if($page>1)
    {
      $ppage = $page-1;
      echo "<div class='center border_top'><a href=\"download.php?$action&amp;page=$ppage&amp;who=$who\"><img src='images/up.png' /></a></div>";
    }

    $items = mysql_query($sql);
    echo mysql_error();
    if(mysql_num_rows($items)>0)
    {
    while ($item = mysql_fetch_array($items))
    {
      $ext = getext($item[2]);
        $ime = getextimg($ext);
        $lnk = "<a href=\"download.php?id=$item[0]\">$ime".htmlspecialchars($item[1])."</a>";
        $downloads = "Preuzeto: <b>$item[4]</b> puta";
        $dateadded = date("d/m/y", $item[5]);
        $dateadded1 = "Dodato: <b>$dateadded</b>";
        
      if (isadmin(getuid_sid($sid)))
{
        $delnk = "<a href=\"download.php?delvlt&amp;vid=$item[0]\">[x]</a>";
      }else{
        $delnk = "";
      }
      if($who!="")
      {
        $byusr="";
      }else{
        $unick = getnick_uid($item[3]);
        $ulnk = "<a href=\"index.php?viewuser&amp;who=$item[3]\">$unick</a>";
        $byusr = "Dodao/la: $ulnk";
      }
      echo "<div class='comment'>$lnk <br/>$byusr $delnk<br/>$dateadded1<br/>$downloads<br/></div>";
      
    }
    }
    if($page<$num_pages)
    {
      $npage = $page+1;
      echo "<div class='center border_top'><a href=\"download.php?$action&amp;page=$npage&amp;who=$who\"><img src='images/down.png' /></a></div>";
    }
    
  if(ismod(getuid_sid($sid)))
  {
    echo "<div class='center border_top_light'><a href=\"download.php?dodaj\">";
echo "<img src='img/img_2.png' /></a></div>";
}
    echo "<div class='comment comm_adv'><img src='img/img_42.png' /><a href=\"download.php?main\">Kategorije</a></div>";
  echo dnoonline($sid,$uid);
}

//////////////////////////VAULT OTHER
else if(isset($_GET['vaultother']))
{
    $who = $_GET["who"];
    addonline(getuid_sid($sid),"Browsing Downloads","");
   echo vrhonline($sid,$uid);
    $uid = getuid_sid($sid);



    //////ALL LISTS SCRIPT <<

    if($page=="" || $page<=0)$page=1;
    if($who!="")
    {
    $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_download WHERE uid='".$who."' AND type='0'"));
    }else{
    $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_download WHERE type='0'"));
    }
    $num_items = $noi[0]; //changable
    $items_per_page= 5;
    $num_pages = ceil($num_items/$items_per_page);
    if(($page>$num_pages)&&$page!=1)$page= $num_pages;
    $limit_start = ($page-1)*$items_per_page;

    if($who!="")
    {
        $sql = "SELECT id, title, itemurl, uid, downloads, pudt FROM fun_download WHERE uid='".$who."' AND type='0' ORDER BY pudt DESC LIMIT $limit_start, $items_per_page";
        }else{
$sql = "SELECT id, title, itemurl, uid, downloads, pudt FROM fun_download WHERE type='0' ORDER BY pudt DESC LIMIT $limit_start, $items_per_page";
        }

    if($page>1)
    {
      $ppage = $page-1;
      echo "<div class='center border_top'><a href=\"download.php?$action&amp;page=$ppage&amp;who=$who\"><img src='images/up.png' /></a></div>";
    }

    $items = mysql_query($sql);
    echo mysql_error();
    if(mysql_num_rows($items)>0)
    {
    while ($item = mysql_fetch_array($items))
    {
      $ext = getext($item[2]);
        $ime = getextimg($ext);
        $lnk = "<a href=\"download.php?id=$item[0]\">$ime".htmlspecialchars($item[1])."</a>";
        $downloads = "Preuzeto: <b>$item[4]</b> puta";
        $dateadded = date("d/m/y", $item[5]);
        $dateadded1 = "Dodato: <b>$dateadded</b>";
        
      if (isadmin(getuid_sid($sid)))
{
        $delnk = "<a href=\"download.php?delvlt&amp;vid=$item[0]\">[x]</a>";
      }else{
        $delnk = "";
      }
      if($who!="")
      {
        $byusr="";
      }else{
        $unick = getnick_uid($item[3]);
        $ulnk = "<a href=\"index.php?viewuser&amp;who=$item[3]\">$unick</a>";
        $byusr = "Dodao/la: $ulnk";
      }
      echo "<div class='comment'>$lnk <br/>$byusr $delnk<br/>$dateadded1<br/>$downloads<br/></div>";
      
    }
    }
    if($page<$num_pages)
    {
      $npage = $page+1;
      echo "<div class='center border_top'><a href=\"download.php?$action&amp;page=$npage&amp;who=$who\"><img src='images/down.png' /></a></div>";
    }
    
  if(ismod(getuid_sid($sid)))
  {
    echo "<div class='center border_top_light'><a href=\"download.php?dodaj\">";
echo "<img src='img/img_2.png' /></a></div>";
}
    echo "<div class='comment comm_adv'><img src='img/img_42.png' /><a href=\"download.php?main\">Kategorije</a></div>";
  echo dnoonline($sid,$uid);
}

else if(isset($_GET['dodaj']))
{
    addonline(getuid_sid($sid),"Adding a vault item","");
   echo vrhonline($sid,$uid);


    echo "<div class='titl'><small>Dozvoljeni sadrzaji.... mp3, waw, amr, jpg, gif, png, bmp, jad, jar, mpg, 3gp.... ostalo ce biti smesteno u neprilagodjen folder pod nazivom ostalo...<br/>OBAVESTENJE: Svi nasilni, kao i uvredljivi sadrzaji su strogo zabranjeni, kao sto su pedofilija, erotika, vredjanje na nacialnoj i verskoj ispovesti... U slucaju pronalazenja takvog fajla, vlasnik ce biti kaznjen... Kako? to ce administratorsko vece da odluci...</small></div><br/>";
    echo "<form action=\"download.php?addvlt\" method=\"post\">";
	echo "<div class='sett_line'>Naziv fajla: <input name=\"naziv\" size=\"12\" maxlength=\"50\" /> </div>";
	echo "<div class='sett_line'>Adresa fajla: <input name=\"adresa\" size=\"12\" maxlength=\"250\" /> </div>";
    echo "<input type=\"submit\" class='button' value=\"DODAJ\"/>";
	echo "</form>";

    echo "<div class='comment comm_adv'><img src='img/img_42.png' /><a href=\"download.php?main\">Kategorije</a></div>";
  echo dnoonline($sid,$uid);
}
else if(isset($_GET['addvlt']))
{

  //$qut = $_POST["qut"];
  addonline(getuid_sid($sid),"Adding a vault item","");
   echo vrhonline($sid,$uid);
if(ismod(getuid_sid($sid)))
  {
  $naziv = $_POST["naziv"];
  $adresa = $_POST["adresa"];
      $crdate = time();
      //$uid = getuid_sid($sid);
      $res = false;
      
      $ext = getext($adresa);
      if ($ext=="mp3" or $ext=="amr" or $ext=="wav") {
      $type = 1;
      }
      if ($ext=="jpg" or $ext=="gif" or $ext=="png" or $ext=="bmp") {
      $type = 2;
      }
      if ($ext=="jad" or $ext=="jar") {
      $type = 3;
      }
      if ($ext=="mpg" or $ext=="3gp") {
      $type = 4;
      }
      if((trim($adresa)!="")&&(trim($naziv)!=""))
      {
      $res = mysql_query("INSERT INTO fun_download SET uid='".$uid."', title='".mysql_escape_string($naziv)."', pudt='".time()."', itemurl='".$adresa."', type='".$type."'");
      }
      if($res)
      {
        echo "Dodavanje uspesno....";
      }else{
        echo "Greska.....";
      }

      if(ismod(getuid_sid($sid)))
  {
    echo "<div class='center border_top_light'><a href=\"download.php?dodaj\">";
echo "<img src='img/img_2.png' /></a></div>";
}
    echo "<div class='comment comm_adv'><img src='img/img_42.png' /><a href=\"download.php?main\">Kategorije</a></div>";
}else{
	echo "DA NISTE ZALUTALI??? :)";
	}
  echo dnoonline($sid,$uid);

} else if ($id) {
  addonline(getuid_sid($sid),"Adding a vault item","");
   echo vrhonline($sid,$uid);
  $id = $_GET["id"];

$fajl = mysql_fetch_array(mysql_query("SELECT id, title, itemurl, uid, downloads, pudt FROM fun_download WHERE id='".$id."'"));
if ($fajl) {
$preuzeto = $fajl[4]+1;
mysql_query("UPDATE fun_download SET downloads='".$preuzeto."' WHERE id='".$id."'");
echo "<div class='titlz'><a href='$fajl[2]'>Preuzmi fajl: $fajl[1]</a></div>";
} else{
echo "Fajl sa takvim id-om ne postoji...";
}
    echo "<div class='comment comm_adv'><a href=\"download.php?main\">Kategorije</a></div>";

  echo dnoonline($sid,$uid);
}

else if(isset($_GET['delvlt']))
{
    $vid = $_GET["vid"];
  addonline(getuid_sid($sid),"Deleting Vault Item","");
  
   echo vrhonline($sid,$uid);
  
      if (isadmin(getuid_sid($sid)))
{
    $res = mysql_query("DELETE FROM fun_download WHERE id='".$vid."'");
    if($res)
        {
            echo "Fajl uspesno obrisan<br/>";
        }else{
          echo "Greska u bazi";
        }
  }
    echo "<div class='comment comm_adv'><img src='img/img_42.png' /><a href=\"download.php?main\">Kategorije</a></div>";
  echo dnoonline($sid,$uid);
}

else{
  /////////////////////////Main Page Here
$uid =getuid_sid($sid);
$whonick = getnick_uid($uid);
$logoutses = mysql_query("DELETE FROM fun_ses WHERE uid='".$uid."'");
$logoutonline = mysql_query("DELETE FROM fun_online WHERE userid='".$uid."'"); 
echo "<form action=\"login.php\" method=\"get\">";
echo "Korisnik: \t<input name=\"loguid\" format=\"*x\" size=\"12\" maxlength=\"30\"/><br/>";
echo "Lozinka:  \t<input type=\"password\" name=\"logpwd\" size=\"12\" maxlength=\"30\"/><br/>";
echo "<input class=\"button\" type=\"submit\" value=\"Loguj &#187;\"/>";
echo "</form>";  
echo dnooffline();
}


	echo "</body>";
	echo "</html>";
?>
