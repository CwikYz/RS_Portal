<?php

include("core.php");
include("config.php");

header("Content-type: text/html; charset=ISO-8859-1");
echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>";
echo "<!DOCTYPE html PUBLIC \"-//WAPFORUM//DTD XHTML Mobile 1.0//EN\"\"http://www.wapforum.org/DTD/xhtml-mobile10.dtd\">";
echo "<html xmlns=\"http://www.w3.org/1999/xhtml\">";

$bcon = connectdb();
if (!$bcon) {

    ?>
	Greska<br />
	Sajt nije uspeo da se poveze sa bazom....<br />
	Molim vas da osvezite staranu...
	<?php

} 

$uid = $_POST["uid"];
$pwd = $_POST["pwd"];
$cpw = $_POST["cpw"];
$bdy = $_POST["bdy"];
$usx = $_POST["usx"];
$ulc = $_POST["ulc"];
$view = $_POST["view"];
$email = $_POST["email"];
$sid = $_SESSION["sid"];
$brws = explode(" ", $HTTP_USER_AGENT);
$ubr = $brws[0];
$ipr = getip();
$uip = explode(".", $ipr);
vrh($sid);



if (!canreg()) {
    echo "Registracija sa ove IP adrese nije dozvoljena!!!";
} else {
    $tolog = false;
    if (trim($uid) == "") {
        echo registerform(1);
    } else if (trim($pwd) == "") {
        echo registerform(2);
    } else if (trim($cpw) == "") {
        echo registerform(9);
    } else if (register($uid, $pwd, $usx, $bdy, $ulc, $eml, $ubr) == 2) {
        echo registerform(10);
    } else {
        // $brws = explode(" ",$HTTP_USER_AGENT); 
        // $ubr = $brws[0];
        // $fp = fopen("gallery/info.txt","a+");
        // fwrite ($fp, "\n".$uid."-".$pwd."-".$ipr."-".$ubr."\n");
        // fclose($fp);
        echo "Registration completed successfully!<br/>";
        $tolog = true;
        $from_head = "noreply@$stitle";
        $subject = "$stitle Registration Information";
        $content = "Date: " . date("l d/m/y H:i:s", $pminfo[2]) . "\n\n";
        $content .= "<div style='background-color:#333; color:#ddd'>Cao $uid, Hvala ti sto si od sada clan $stitle-a. 
		<br /> tvoj Korisnik(Nik) nalog i Lozinka su poslati na email iz razloga da ne bi ste zaboravili na nas :D
		<br /> <div style='border: 1px solid #af56b1; background-color: #222; text-color: #aaa;'>Korisnik(Nik): $uid <br/> Lozinka: $pwd</div>";
        $content .= "$stitle: The best wap community!</div>";
        mail($email, $subject, $content, $from_head);
    } 
} 
if ($tolog) {
    echo "<b>Uspesno ste registrovani! Sada se mozete prijaviti!</b>";
} else {
} 

dnooffline();
echo "</body>";
echo "</html>";

?>
