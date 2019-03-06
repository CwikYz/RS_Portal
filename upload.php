<?php
include("config.php");
include("core.php");
include("class.upload.php");

header("Content-type: text/html; charset=ISO-8859-1");
echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>";
echo "<!DOCTYPE html PUBLIC \"-//WAPFORUM//DTD XHTML Mobile 1.0//EN\"\"http://www.wapforum.org/DTD/xhtml-mobile10.dtd\">";
echo "<html xmlns=\"http://www.w3.org/1999/xhtml\">";


?>
<!--Molim vas bez kradje sourcea i dizajna portala....-->
<!--Molim vas bez kradje sourcea i dizajna portala....-->
<!--Molim vas bez kradje sourcea i dizajna portala....-->
<!--Molim vas bez kradje sourcea i dizajna portala....-->
<!--Molim vas bez kradje sourcea i dizajna portala....-->
<!--Molim vas bez kradje sourcea i dizajna portala....-->
<!--Molim vas bez kradje sourcea i dizajna portala....-->
<!--Molim vas bez kradje sourcea i dizajna portala....-->
<!--Molim vas bez kradje sourcea i dizajna portala....-->
<!--Molim vas bez kradje sourcea i dizajna portala....-->


<?php
$bcon = connectdb();
if (!$bcon) {

    ?>
	Greska<br />
	Sajt nije uspeo da se poveze sa bazom....<br />
	Molim vas da osvezite staranu...
	<?php
    exit();
} 
$brws = explode(" ", $HTTP_USER_AGENT);
$ubr = $brws[0];
$uip = getip();
$action = $_GET["action"];
$sid = $_SESSION["sid"];
$page = $_GET["page"];
$who = $_GET["who"];
$clid = $_GET["clid"];
vrh($sid);

$uid = getuid_sid($sid);
cleardata();
if (isipbanned($uip, $ubr)) {
    if (!isshield(getuid_sid($sid))) {
        echo "<p align=\"center\">";
        echo "<img src=\"images/notok.gif\" alt=\"x\"/><br/>";
        echo "This IP address is blocked<br/>";
        echo "<br/>";
        echo "How ever we grant a shield against IP-Ban for our great users, you can try to see if you are shielded by trying to log-in, if you kept coming to this page that means you are not shielded, so come back when the ip-ban period is over<br/><br/>";
        $banto = mysql_fetch_array(mysql_query("SELECT  timeto FROM fun_penalties WHERE  penalty='2' AND ipadd='" . $uip . "' AND browserm='" . $ubr . "' LIMIT 1 ")); 
        // echo mysql_error();
        $remain = $banto[0] - time();
        $rmsg = gettimemsg($remain);
        echo " IP: $rmsg<br/><br/>";

        echo "</p>";
        $uid = getuid_sid($sid);
        $whonick = getnick_uid($uid);
        $logoutses = mysql_query("DELETE FROM fun_ses WHERE uid='" . $uid . "'");
        $logoutonline = mysql_query("DELETE FROM fun_online WHERE userid='" . $uid . "'");
        echo "<form action=\"login.php\" method=\"get\">";
        echo "<small>Korisnicko ime (Nadimak):</small> <br /><input name=\"loguid\" maxlength=\"30\"/><br/>";
        echo "<small>Lozinka:</small>  <br /><input type=\"password\" name=\"logpwd\" maxlength=\"30\"/><br/>";
        echo "<input class=\"button\" type=\"submit\" value=\"Prijava\"/>";
        echo "</form>";
        echo "<br /> <small>Imate problema sa logovanjem?</small> <br /> <small><a href=\"login.php\">Pokusajte alternativnu prijavu</a></small> <br />";
        echo "<br /> <small>Potreban ti je $stitle nalog?</small> <br /> <small><a href=\"register.php\">Prijavite se ovde</a></small> <br />";

        exit();
    } 
} 

    $uid = getuid_sid($sid);
    if ((islogged($sid) == false) || ($uid == 0)) {
        $uid = getuid_sid($sid);
        $whonick = getnick_uid($uid);
        $logoutses = mysql_query("DELETE FROM fun_ses WHERE uid='" . $uid . "'");
        $logoutonline = mysql_query("DELETE FROM fun_online WHERE userid='" . $uid . "'");
        echo "<form action=\"login.php\" method=\"get\">";
        echo "<small>Korisnicko ime (Nadimak):</small> <br /><input name=\"loguid\" maxlength=\"30\"/><br/>";
        echo "<small>Lozinka:</small>  <br /><input type=\"password\" name=\"logpwd\" maxlength=\"30\"/><br/>";
        echo "<input class=\"button\" type=\"submit\" value=\"Prijava\"/>";
        echo "</form>";
        echo "<br /> <small>Imate problema sa logovanjem?</small> <br /> <small><a href=\"login.php\">Pokusajte alternativnu prijavu</a></small> <br />";
        echo "<br /> <small>Potreban ti je $stitle nalog?</small> <br /> <small><a href=\"register.php\">Prijavite se ovde</a></small> <br />";
        echo dnooffline();
        exit();
    } 

// echo isbanned($uid);
if (isbanned($uid)) {
    echo "<p align=\"center\">";
    echo "<img src=\"images/notok.gif\" alt=\"x\"/><br/>";
    echo "You are <b>Banned</b><br/>";
    $banto = mysql_fetch_array(mysql_query("SELECT timeto FROM fun_penalties WHERE uid='" . $uid . "' AND penalty='1'"));
    $banres = mysql_fetch_array(mysql_query("SELECT lastpnreas FROM fun_users WHERE id='" . $uid . "'"));

    $remain = $banto[0] - time();
    $rmsg = gettimemsg($remain);
    echo "Time to finish your penalty: $rmsg<br/><br/>";
    echo "Ban Reason: $banres[0]"; 
    // echo "<a href=\"index.php\">Login</a>";
    echo "</p>";
    exit();
} 
$uid = getuid_sid($sid);
$descript = $_POST["descript"];

?>
</head>
<body>
<?php 
// ////////////////////////////////Members List
// we first include the upload class, as we will need it here to deal with the uploaded file
$userinfo = mysql_fetch_array(mysql_query("SELECT name, sex FROM fun_users WHERE id='" . $uid . "'"));
$membername = $userinfo[0];
// we have three forms on the test page, so we redirect accordingly
if ($_POST['action'] == 'image') {
    // $pstyle = gettheme($sid);
    // echo xhtmlhead("Arawap",$pstyle);
    echo vrhonline($sid, $uid); 
    // ---------- IMAGE UPLOAD ----------
    // we create an instance of the class, giving as argument the PHP object
    // corresponding to the file field from the form
    // All the uploads are accessible from the PHP object $_FILES
    $handle = new Upload($_FILES['my_field']); 
    // then we check if the file has been uploaded properly
    // in its *temporary* location in the server (often, it is /tmp)
    if ($handle->uploaded) {
        // yes, the file is on the server
        // below are some example settings which can be used if the uploaded file is an image.
        $handle->image_resize = true;
        $handle->image_ratio_y = true;
        $handle->image_x = 240; 
        // now, we start the upload 'process'. That is, to copy the uploaded file
        // from its temporary location to the wanted location
        // It could be something like $handle->Process('/home/www/my_uploads/');
        $handle->Process('gallery/'); 
        // we check if everything went OK
        if ($handle->processed) {
            // everything was fine !
            $userinfo = mysql_fetch_array(mysql_query("SELECT name, sex FROM fun_users WHERE id='" . $uid . "'"));
            $membername = $userinfo[0];
            echo '  <div class="titl"><b>Fotografija uspesno uploadovana...</b></div>';
            echo '  <div class="sett_line"><img src="gallery/' . $handle->file_dst_name . '" /><br/>';
            $info = getimagesize($handle->file_dst_pathname);
            echo '  Link do fotografije je: <a href="gallery/' . $handle->file_dst_name . '">http://adresa sajta/gallery/' . $handle->file_dst_name . '</a></div>';
            $imageurl = "gallery/$handle->file_dst_name";
            $crdate = time();
            $reg = mysql_query("INSERT INTO fun_fotografije SET uid='" . $uid . "', imageurl='" . $imageurl . "', sex='" . $userinfo[1] . "', time='" . $crdate . "', descript='" . $descript . "'");
        } else {
            // one error occured
            echo '  Fotografija nije uploadovana na zeljenu lokaciju<br/>';
            echo '  Greska: ' . $handle->error . '<br/>';
        } 
        // we delete the temporary files
        $handle->Clean();
    } else {
        // if we're here, the upload file failed for some reasons
        // i.e. the server didn't receive the file
        echo '  Fotografija nije uploadovana<br/>';
        echo '  Greska: ' . $handle->error . '';
    } 

    echo dnoonline($sid, $uid);
    exit();
} else if ($_POST['action'] == 'grupa') {
$clid = $_POST['clid'];
    // $pstyle = gettheme($sid);
    // echo xhtmlhead("Arawap",$pstyle);
    echo vrhonline($sid, $uid); 
    // ---------- IMAGE UPLOAD ----------
    // we create an instance of the class, giving as argument the PHP object
    // corresponding to the file field from the form
    // All the uploads are accessible from the PHP object $_FILES
    $handle = new Upload($_FILES['my_field']); 
    // then we check if the file has been uploaded properly
    // in its *temporary* location in the server (often, it is /tmp)
    if ($handle->uploaded) {
        // yes, the file is on the server
        // below are some example settings which can be used if the uploaded file is an image.
        $handle->image_resize = true;
        $handle->image_ratio_y = true;
        $handle->image_x = 240; 
        // now, we start the upload 'process'. That is, to copy the uploaded file
        // from its temporary location to the wanted location
        // It could be something like $handle->Process('/home/www/my_uploads/');
        $handle->Process('grupa/'); 
        // we check if everything went OK
        if ($handle->processed) {
            // everything was fine !
            $userinfo = mysql_fetch_array(mysql_query("SELECT name FROM fun_clubs WHERE id='" . $clid . "'"));
            $membername = $userinfo[0];
            echo '  <div class="titl"><b>Fotografija uspesno uploadovana...</b></div>';
            echo '  <div class="sett_line"><img src="grupa/' . $handle->file_dst_name . '" /><br/>';
            $info = getimagesize($handle->file_dst_pathname);
            echo ' </div>';
            $imageurl = "grupa/$handle->file_dst_name";
            $crdate = time();
            $reg = mysql_query("INSERT INTO fun_fotografije_grupa SET uid='" . $clid . "', imageurl='" . $imageurl . "', time='" . $crdate . "', descript='" . $descript . "'");
			echo '<a href="grupa.php?clid='.$clid.'">Vrati se u grupu</a>';
        } else {
            // one error occured
            echo '  Fotografija nije uploadovana na zeljenu lokaciju<br/>';
            echo '  Greska: ' . $handle->error . '<br/>';
        } 
        // we delete the temporary files
        $handle->Clean();
    } else {
        // if we're here, the upload file failed for some reasons
        // i.e. the server didn't receive the file
        echo '  Fotografija nije uploadovana<br/>';
        echo '  Greska: ' . $handle->error . '';
    } 

    echo dnoonline($sid, $uid);
    exit();
} 

?>
</font></body></html>
