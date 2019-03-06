<?php

include("core.php");
include("config.php");

header("Content-type: text/html; charset=ISO-8859-1");
echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>";
echo "<!DOCTYPE html PUBLIC \"-//WAPFORUM//DTD XHTML Mobile 1.0//EN\"\"http://www.wapforum.org/DTD/xhtml-mobile10.dtd\">";
echo "<html xmlns=\"http://www.w3.org/1999/xhtml\">";

connectdb();
$bcon = connectdb();
if (!$bcon) {
    vrh($sid);
    echo "<img src=\"images/exit.gif\" alt=\"*\"/><br/>";
    echo "ERROR! cannot connect to database<br/><br/>";
    echo "This error happens usually when backing up the database, please be patient, The site will be up any minute<br/><br/>";
    echo "<b>THANK YOU VERY MUCH</b>";
    dnooffline();
    exit();
} 

$uid = $_GET["loguid"];
$pwd = $_GET["logpwd"];

$tolog = false;

    $epwd = md5($pwd);
    $uinf = mysql_fetch_array(mysql_query("SELECT pass FROM fun_users WHERE name='" . $uid . "' AND pass='" . $epwd . "'")); 
if ($uinf[0] == $epwd) {

    $tm = time();
    $xtm = $tm + (getsxtm() * 60);
    $did = $uid . $tm;
    $res = mysql_query("INSERT INTO fun_ses SET id='" . md5($did) . "', uid='" . getuid_nick($uid) . "', expiretm='" . $xtm . "'");

    if ($res) {
        $tolog = true;
        $sid = md5($did);

        vrh($sid);
        echo vrhonlinedva($sid, $tolog);
        echo "Zdravo $uid<br/>";
        $idn = getuid_nick($uid);

        $lact = mysql_fetch_array(mysql_query("SELECT lastact FROM fun_users WHERE id='" . $idn . "'"));
        mysql_query("UPDATE fun_users SET lastvst='" . $lact[0] . "' WHERE id='" . $idn . "'");
    } else {
        // is user already logged in?
        $logedin = mysql_fetch_array(mysql_query("SELECT * FROM fun_ses WHERE uid='" . getuid_nick($uid) . "'"));
        if ($logedin[0] > 0) {
            // yip, so let's just update the expiration time
            $xtm = time() + (getsxtm() * 60);
            $res = mysql_query("UPDATE fun_ses SET expiretm='" . $xtm . "' WHERE uid='" . getuid_nick($uid) . "'");

            if ($res) {
                $tolog = true;
                $sid = md5($did);
                vrh($sid);
                echo vrhonlinedva($sid, $tolog);
                echo "Zdravo $uid<br/>";
            } else {
                vrh($sid);
                echo "<img src=\"images/point.gif\" alt=\"!\"/>Nemozete se ulogovati ovog trenutka!<br/>"; //no chance this could happen unless there's error in mysql connection
            } 
        } 
    } 
    // can add text ere//
} else {    vrh($sid); 
    // check for pwd
    $epwd = md5($pwd);
    $uinf = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_users WHERE name='" . $uid . "' AND pass='" . $epwd . "'")); 
    // echo "<img src=\"images/notok.gif\" alt=\"X\"/>Pogresna lozinka<br/><br/>";
}

if ($tolog) {
    $_SESSION["sid"] = md5($did);
    echo "<a href=\"index.php?main\">[ UdjI ]</a><br/><br/>";
    echo "Markirajte ovu stranu radi lakseg ulaza!!!<br/>(Da nebiste stalno kucali korisnik i lozinka!!!)";
    $s = $tolog;
    echo dnoonlinedva($sid, $s, $uid, $did);
} else {

    ?>
<form action="login.php" method="get">
<small>Korisnicko ime (Nadimak):</small> <br /><input name="loguid" maxlength="30"/><br/>
<small>Lozinka:</small>  <br /><input name="logpwd" maxlength="30"/><br/>
<input class="button" type="submit" value="Prijava"/>
</form>
<?php
    dnooffline();
} 

echo "</body>";
echo "</html>";

?>
