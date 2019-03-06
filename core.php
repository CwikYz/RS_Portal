<?php

include("config.php");

// include("gmprc.php");



@session_name('sid');

@session_start();



if (function_exists('ini_set'))

{

ini_set('display_errors',false); 

ini_set('register_globals', false); 

ini_set('session.use_cookies', true); 

ini_set('session.use_trans_sid', true); 

ini_set('arg_separator.output', "&amp;"); 

}



if (!get_magic_quotes_gpc()) {

    $_GET = array_map('trim', $_GET);

    $_POST = array_map('trim', $_POST);

    $_COOKIE = array_map('trim', $_COOKIE);



    $_GET = array_map('addslashes', $_GET);

    $_POST = array_map('addslashes', $_POST);

    $_COOKIE = array_map('addslashes', $_COOKIE);

} 



function connectdb()

{

    global $dbname, $dbuser, $dbhost, $dbpass;

    $conms = @mysql_connect($dbhost, $dbuser, $dbpass); //connect mysql

    if (!$conms) return false;

    $condb = @mysql_select_db($dbname);

    if (!$condb) return false;

    return true;

} 



////////// antihacker

function anti_hacker($txt){

$txt=htmlspecialchars($txt);                       

$txt=stripslashes(trim($txt));

return $txt;}



if(isset($_GET)){foreach($_GET as $key=>$value){$_GET[$key]=anti_hacker($value);}}

if(isset($_POST)){foreach($_POST as $key=>$value){$_POST[$key]=anti_hacker($value);}}

if(isset($_SESSION)){foreach($_SESSION as $key=>$value){$_SESSION[$key]=anti_hacker($value);}}

if(isset($_COOKIE)){foreach($_COOKIE as $key=>$value){$_COOKIE[$key]=anti_hacker($value);}} 



function findcard($tcode)

{

    $st = strpos($tcode, "[card=");

    if ($st === false) {

        return $tcode;

    } else {

        $ed = strpos($tcode, "[/card]");

        if ($ed === false) {

            return $tcode;

        } 

    } 

    $texth = substr($tcode, 0, $st);

    $textf = substr($tcode, $ed + 7);

    $msg = substr($tcode, $st + 10, $ed - $st-10);

    $cid = substr($tcode, $st + 6, 3);

    $words = explode(' ', $msg);

    $msg = implode('+', $words);

    return "$texth<br/><img src=\"pmcard.php?cid=$cid&amp;msg=$msg\" alt=\"$cid\"/><br/>$textf";

} 

function saveuinfo($sid)

{

    $headers = apache_request_headers();

    $alli = "";

    foreach ($headers as $header => $value) {

        $alli .= "$header: $value <br />\n";

    } 

    $alli .= "IP: " . $_SERVER['REMOTE_ADDR'] . "<br/>";

    $alli .= "REFERRER: " . $_SERVER['HTTP_REFERER'] . "<br/>";

    $alli .= "REMOTE HOST: " . getenv('REMOTE_HOST') . "<br/>";

    $alli .= "PROX: " . $_SERVER['HTTP_X_FORWARDED_FOR'] . "<br/>";

    $alli .= "HOST: " . getenv('HTTP_X_FORWARDED_HOST') . "<br/>";

    $alli .= "SERV: " . getenv('HTTP_X_FORWARDED_SERVER') . "<br/>";

    if (trim($sid) != "") {

        $uid = getuid_sid($sid);

        $fname = "tmp/" . getnick_uid($uid) . ".rwi";

        $out = fopen($fname, "w");

        fwrite($out, $alli);

        fclose($out);

    } 

    // return 0;

} 

function rating($uid)

{

    $info = mysql_fetch_array(mysql_query("SELECT * FROM fun_users WHERE id='" . $uid . "'"));

    $posts = $info["posts"];

    $plusses = $info["plusses"];

    $gplus = $gplus["gplus"];

    $shouts = $shouts["shouts"];

    $tot = $posts + $plusses + $gplus + $shouts;

    if ($tot < 100) {

        return "<img src=\"stars/0.0.gif\" alt=\"\"/>";

    } 

    if ($tot < 250) {

        return "<img src=\"stars/0.5.gif\" alt=\"\"/>";

    } 

    if ($tot < 500) {

        return "<img src=\"stars/1.0.gif\" alt=\"\"/>";

    } 

    if ($tot < 750) {

        return "<img src=\"stars/1.5.gif\" alt=\"\"/>";

    } 

    if ($tot < 2500) {

        return "<img src=\"stars/2.0.gif\" alt=\"\"/>";

    } 

    if ($tot < 50000) {

        return "<img src=\"stars/2.5.gif\" alt=\"\"/>";

    } 

    if ($tot < 75000) {

        return "<img src=\"stars/3.0.gif\" alt=\"\"/>";

    } 

    if ($tot < 100000) {

        return "<img src=\"stars/3.5.gif\" alt=\"\"/>";

    } 

    if ($tot < 150000) {

        return "<img src=\"stars/4.0.gif\" alt=\"\"/>";

    } 

    if ($tot < 500000) {

        return "<img src=\"stars/4.5.gif\" alt=\"\"/>";

    } 

    if ($tot < 1000000) {

        return "<img src=\"stars/5.0.gif\" alt=\"\"/>";

    } 

    if ($tot >= 1000000) {

        return "<img src=\"stars/5.0.gif\" alt=\"\"/>";

    } 

} 

function registerform()

{

    $ue = $errl = $pe = $ce = $ee = "";

    switch ($ef) {

        case 1:

            $errl = "<b><font color=\"orange\">!</font></b> Molimo vas da upisete korisnicko ime (Nadimak) ";

            $ue = "<b><font color=\"orange\">!</font></b>";

            break;

        case 2:

            $errl = "<b><font color=\"orange\">!</font></b> Unesite lozinku";

            $pe = "<b><font color=\"orange\">!</font></b>";

            break;

        case 3:

            $errl = "<img src=\"images/point.gif\" alt=\"!\"/> Unesite lozinku ponovo";

            $ce = "<img src=\"images/point.gif\" alt=\"!\"/>";

            break;

        case 4:

            $errl = "<img src=\"images/point.gif\" alt=\"!\"/> Korisnicko ime (Nadimak) nije dozvoljeno";

            $ue = "<img src=\"images/point.gif\" alt=\"!\"/>";

            break;

        case 5:

            $errl = "<img src=\"images/point.gif\" alt=\"!\"/> Pogresna lozinka";

            $pe = "<img src=\"images/point.gif\" alt=\"!\"/>";

            break;

        case 6:

            $errl = "<img src=\"images/point.gif\" alt=\"!\"/> Lozinke se ne poklapaju";

            $ce = "<img src=\"images/point.gif\" alt=\"!\"/>";

            break;

        case 7:

            $errl = "<img src=\"images/point.gif\" alt=\"!\"/> Korisnicko ime (Nadimak) mora da ima najmanje 4 karaktera";

            $ue = "<img src=\"images/point.gif\" alt=\"!\"/>";

            break;

        case 8:

            $errl = "<img src=\"images/point.gif\" alt=\"!\"/> Lozinka mora da ima najmanje 4 karaktera";

            $pe = "<img src=\"images/point.gif\" alt=\"!\"/>";

            break;

        case 9:

            $errl = "<img src=\"images/point.gif\" alt=\"!\"/> Korisnicko ime (Nadimak) je vec u upotrebi";

            $ue = "<img src=\"images/point.gif\" alt=\"!\"/>";

            break;

        case 10:

            $errl = "<img src=\"images/point.gif\" alt=\"!\"/> Greska na bazi... Pokusajte kasnije";



            break;

        case 11:

            $errl = "<img src=\"images/point.gif\" alt=\"!\"/> Korisnicko ime (Nadimak) mora poceti azbucnim slovom";

            $ue = "<img src=\"images/point.gif\" alt=\"!\"/>";

            break;

        case 12:

            $errl = "<img src=\"images/point.gif\" alt=\"!\"/> Korisnicko ime (Nadimak) je zabrannjeno od strane administracije";

            $ue = "<img src=\"images/point.gif\" alt=\"!\"/>";

            break;

        case 13:

            $errl = "<img src=\"images/point.gif\" alt=\"!\"/> Molimo vas pokusajte drugo korisnicko ime (Nadimak)";

            $ue = "<img src=\"images/point.gif\" alt=\"!\"/>";

            break;

        case 14:

            $errl = "<img src=\"images/point.gif\" alt=\"!\"/> Molimo vas upisite e-mail adresu";

            $ee = "<img src=\"images/point.gif\" alt=\"!\"/>";

            break;

        case 15:

            $errl = "<img src=\"images/point.gif\" alt=\"!\"/> Ne postojeca email adresa";

            $ee = "<img src=\"images/point.gif\" alt=\"!\"/>";

            break;

        case 16:

            $errl = "<img src=\"images/point.gif\" alt=\"!\"/> Email adresa je vec u upotrebi";

            $ee = "<img src=\"images/point.gif\" alt=\"!\"/>";

            break;

    } 



    $rform = "<form action=\"register.php?v=$v\" method=\"post\">";

    $rform .= "<div class='titl'>$errl</div>";

    $rform .= "<div class=\"sett_line\">$ue Korisnicko ime (Nadimak) <input name=\"uid\" maxlength=\"30\"/></div>";

    $rform .= "<div class=\"sett_line\">$pe Lozinka <input type=\"password\" name=\"pwd\" format=\"*x\" maxlength=\"50\"/></div>";

    $rform .= "<div class=\"sett_line\">$ce Lozinka (ponovi) <input type=\"password\" name=\"cpw\" format=\"*x\" maxlength=\"50\"/></div>";

    $rform .= "<div class=\"sett_line\">Datum rodjenja <input name=\"bdy\" format=\"*x\" maxlength=\"30\"/></div>";

    $rform .= "<div class=\"sett_line\">Pol ";

    $rform .= "<select name=\"usx\">";

    $rform .= "<option value=\"M\">Musko</option>";

    $rform .= "<option value=\"F\">Zensko</option>";

    $rform .= "</select></div>";

    $rform .= "<div class=\"sett_line\">Prebivaliste <input name=\"ulc\"  maxlength=\"100\"/></div>";

    $rform .= "<div class=\"sett_line\">$ee Email <input name=\"email\" type=\"text\" maxlength=\"55\"/></div>";

    $rform .= "<input class=\"button\" type=\"submit\" value=\"Registruj se\"/>";

    $rform .= "</form>";

    return $rform;

} 

// //////////////////////////////////Vrh Sajta OnLine

function vrhonline($sid, $uid)

{

$uid = $_GET['uid'];



    $uid = getuid_sid($sid);

    echo "<div id=\"fb_header\" class=\"marquee\"><div class=\"h\"><table width=\"100%\"><tr><td align=\"left\" style=\"width:50%\">";

    echo "<span class=\"marquee_tab\"><small><a href=\"index.php?main\" accesskey=\"0\">Kuca</a></small></span>";

    echo " <span class=\"marquee_tab\"><small><a href=\"index.php?viewuser&amp;who=$uid\" accesskey=\"1\">Profil</a></small></span>";

    echo " <span class=\"marquee_tab\"><small><a href=\"lists.php?buds\" accesskey=\"2\">Prijatelji</a></small></span>";

    $tmsg = getunreadpm($uid);

    if ($tmsg > 0) {

        echo " <span class=\"marquee_tab\"><small><a href=\"inbox.php?main\" accesskey=\"3\">Poruke <div class=\"titl\">$tmsg</div></a></small></span>";

    } else {

        echo " <span class=\"marquee_tab\"><small><a href=\"inbox.php?main\" accesskey=\"3\">Poruke</a></small></span>";

    } 

    echo "</td></tr>";

    echo "</table></div></div><div class=\"main\"></div>";

} 

// /////////Kraj///////////

// //////////////////////////////////Vrh Sajta OnLine

function vrhonlinedva($sid, $uid)

{



    echo "<div id=\"fb_header\" class=\"marquee\"><div class=\"h\"><table width=\"100%\"><tr><td align=\"left\" style=\"width:50%\">";

    echo "<span class=\"marquee_tab\"><small><a href=\"index.php?main\" accesskey=\"0\">Kuca</a></small></span>";

    echo " <span class=\"marquee_tab\"><small><a href=\"lists.php?buds\" accesskey=\"2\">Prijatelji</a></small></span>";

    $tmsg = getunreadpm($uid);

    if ($tmsg > 0) {

        echo " <span class=\"marquee_tab\"><small><a href=\"inbox.php?main\" accesskey=\"3\">Poruke <div class=\"titl\">$tmsg</div></a></small></span>";

    } else {

        echo " <span class=\"marquee_tab\"><small><a href=\"inbox.php?main\" accesskey=\"3\">Poruke</a></small></span>";

    } 

    echo "</td></tr>";

    echo "</table></div></div><div class=\"main\"></div>";

} 

// /////////Kraj///////////

// //////////////////////////////////Vrh Online

function vrh($sid)

{



echo "<head>";



echo "<title>Raj Sveta</title>

<META name='viewport' content='width=160; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;'>";

$clan = getuid_sid($sid);

if ($clan) {

$brteme = mysql_fetch_array(mysql_query("SELECT tema FROM fun_users WHERE id='".$clan."'"));

$tema = mysql_fetch_array(mysql_query("SELECT tema FROM fun_tema WHERE id='".$brteme[0]."'"));

echo "<link rel=\"StyleSheet\" type=\"text/css\" href=\"style/$tema[0].css\" />";

echo "<link rel=\"StyleSheet\" type=\"text/css\" href=\"style/style.css\" />";



} else {

echo "<link rel=\"StyleSheet\" type=\"text/css\" href=\"style/default.css\" />";

echo "<link rel=\"StyleSheet\" type=\"text/css\" href=\"style/style.css\" />";

}

echo "</head>";



echo "<body>";



    $brojanje = mysql_fetch_array(mysql_query("SELECT value FROM fun_settings WHERE id='4'"));

    $sabiranje = $brojanje[0] + 1;

    mysql_query("UPDATE fun_settings SET value='" . $sabiranje . "' WHERE id='4'");



    if ($sid) {

        addonline(getuid_sid($sid), "PRISUTAN/A", "");

        echo "<div class=\"head\"><a href='index.php?main'><div class=\"titl\">Raj Sveta</div> Portal</a></div>";

    } else {

        echo "<div class=\"head\"><a href='index.php'><div class=\"titl\">Raj Sveta</div> Portal</a></div>";

    } 

} 

// /////////Kraj///////////

// //////////////////////////////////Dno Sajta OnLine

function dnoonline($sid, $uid)

{

    $uid = getuid_sid($sid);

    echo "<div id=\"footer_nav\" class=\"pad border_top\">";

    echo "<small><a href=\"index.php?Forumi\">Forum</a> &#149; <a href=\"index.php?viewuser&amp;who=$uid\">Profil</a> &#149; <a href=\"lists.php?buds\">Prijatelji</a> &#149; <a href=\"inbox.php?main\">Poruke</a>";

    echo "</small>";

    echo "</div>";

    echo "<div id=\"search\" class=\"summary border_top border_bottom\">";

    echo "<form action=\"search.php?smbr\" method=\"post\">";

    echo "<input name=\"stext\" maxlength=\"15\"/>";

    echo "<input class=\"button\" type=\"submit\" value=\"Pretraga\"/>";

    echo "</form>";

    echo "</div>";

    echo "<div id=\"footer\" class=\"pad\">";



    $korisnik = getnick_uid($uid);

    echo "<small><a href=\"index.php?search\">Pronadji prijatelje</a> &#149; <a href=\"index.php?cpanel\">Podesavanja</a> &#149; <a href='lists.php?smilies'>Smajliji</a> &#149; <a href=\"tema.php?main\">Teme</a><br /><a href=\"index.php?sid=$sid&amp;log=da\">Izloguj se</a> (<a href=\"index.php?viewuser&amp;who=$uid\">$korisnik</a>)<br />RS Portal &#169; 2010-2019<br />Design by: CwikYz<br />";

    $nopm = mysql_fetch_array(mysql_query("SELECT value FROM fun_settings WHERE name='Counter'"));

    echo "Counter: <b>$nopm[0]</b></small></div><div class=\"border_top_light\">";

    echo "</div>";

} 

// /////////Kraj///////////

// //////////////////////////////////Dno Sajta OnLine2

function dnoonlinedva($sid, $uid)

{

    echo "<div id=\"footer_nav\" class=\"pad border_top\">";

    echo "<small><a href=\"index.php?Forumi\">Forum</a> &#149; <a href=\"lists.php?buds\">Prijatelji</a> &#149; <a href=\"inbox.php?main\">Poruke</a>";

    echo "</small>";

    echo "</div>";

    echo "<div id=\"search\" class=\"summary border_top border_bottom\">";

    echo "<form action=\"search.php?smbr\" method=\"post\">";

    echo "<input name=\"stext\" maxlength=\"15\"/>";

    echo "<input class=\"button\" type=\"submit\" value=\"Pretraga\"/>";

    echo "</form>";

    echo "</div>";

    echo "<div id=\"footer\" class=\"pad\">";

    $korisnik = getnick_uid($uid);

    echo "<small><a href=\"index.php?search\">Pronadji prijatelje</a> &#149; <a href=\"index.php?cpanel\">Podesavanja</a> &#149; <a href='lists.php?smilies'>Smajliji</a> &#149; <a href=\"tema.php?main\">Teme</a><br /><a href=\"index.php?sid=$sid&amp;log=da\">Izloguj se</a><br />RS Portal &#169; 2010-2019<br />Design by: CwikYz<br />";



    $nopm = mysql_fetch_array(mysql_query("SELECT value FROM fun_settings WHERE name='Counter'"));

    echo "Counter: <b>$nopm[0]</b></small></div><div class=\"border_top_light\">";

    echo "</div>";

} 

// ////////////Kraj

// //////////////////////////////////Dno Sajta OffLine

function dnooffline()

{

    echo "<div id=\"footer_nav\" class=\"pad border_top\">";

    echo "<a href='index.php'>Pocetna</a>";

    echo "</div>";

    echo "<div id=\"footer\" class=\"pad\">";

    $korisnik = getnick_uid($uid);

    echo "<small>Kreirao: <b>Cvijovic Darko CwikYz</b> <br /> Kontakt: <b>cwikyz@live.com</b> <br /> RS Portal &#169; 2010-2019<br />Design by: CwikYz <br />";



    $nopm = mysql_fetch_array(mysql_query("SELECT value FROM fun_settings WHERE name='Counter'"));

    echo "Counter: <b>$nopm[0]</b></small> </div> <div class=\"border_top_light\">";

    echo "</div>";

} 

// /////////Kraj///////////

// ////////////////////////can sign blog comment?

function cansignblogcomment($uid, $who)

{

    if (arebuds($bid, $who)) {

        return true;

    } 

    if ($bid == $who) {

        return false; //imagine if someone signed his own gbook o.O

    } 

    if (getplusses($uid) >= 50) {

        return true;

    } 

    return false;

} 

// //////////////////////////////////////////is del blog comment

function candelblogcomment($bid, $mid)

{

    $minfo = mysql_fetch_array(mysql_query("SELECT blogowner, blogsigner FROM fun_blogcomment WHERE id='" . $mid . "'"));

    if ($minfo[0] == $bid) {

        return true;

    } 

    if ($minfo[1] == $bid) {

        return true;

    } 

    return false;

} 

// ////////////////////////////MMS LOAD

function boxstart($title)

{

    echo "



	<div class=\"boxed\">



      <div class=\"boxedTitle\">



        <h1 align=\"center\" class=\"boxedTitleText\"><b>$title</b>



	</h1>



      </div>



      <div class=\"boxedContent\">



";

} 



function boxend()

{

    echo "</div></div>";

} 



function getmmscount($uid, $view = "all")

{

    if ($view == "all") {

        $nopm = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM mms WHERE touid='" . $uid . "'"));

    } else if ($view == "snt") {

        $nopm = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM mms WHERE byuid='" . $uid . "'"));

    } else if ($view == "str") {

        $nopm = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM mms WHERE touid='" . $uid . "' AND starred='1'"));

    } else if ($view == "urd") {

        $nopm = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM mms WHERE touid='" . $uid . "' AND unread='1'"));

    } 



    return $nopm[0];

} 

function getunreadmms($uid)

{

    $nopm = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM mms WHERE touid='" . $uid . "' AND unread='1'"));



    return $nopm[0];

} 

// ////////////////////////////////////////// Search Id

function generate_srid($svar1, $svar2 = "", $svar3 = "", $svar4 = "", $svar5 = "")

{

    $res = mysql_fetch_array(mysql_query("SELECT id FROM fun_search WHERE svar1 like '" . $svar1 . "' AND svar2 like '" . $svar2 . "' AND svar3 like '" . $svar3 . "' AND svar4 like '" . $svar4 . "' AND svar5 like '" . $svar5 . "'"));

    if ($res[0] > 0) {

        return $res[0];

    } 

    mysql_query("INSERT INTO fun_search SET svar1='" . $svar1 . "', svar2='" . $svar2 . "', svar3='" . $svar3 . "', svar4='" . $svar4 . "', svar5='" . $svar5 . "', stime='" . time() . "'");

    $res = mysql_fetch_array(mysql_query("SELECT id FROM fun_search WHERE svar1 like '" . $svar1 . "' AND svar2 like '" . $svar2 . "' AND svar3 like '" . $svar3 . "' AND svar4 like '" . $svar4 . "' AND svar5 like '" . $svar5 . "'"));

    return $res[0];

} 



function candelvl($uid, $item)

{

    $candoit = mysql_fetch_array(mysql_query("SELECT  uid FROM fun_vault WHERE id='" . $item . "'"));

    if ($uid == $candoit[0] || ismod($uid)) {

        return true;

    } 

    return false;

} 

// ///////////////////////////////// GET RATE

function geturate($uid)

{

    $pnts = 0; 

    // by blogs, posts per day, chats per day, gb signatures

    if (ismod($uid)) {

        return 5;

    } 

    $noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_blogs WHERE bowner='" . $uid . "'"));

    if ($noi[0] >= 5) {

        $pnts = 5;

    } else {

        $pnts = $noi[0];

    } 

    $noi = mysql_fetch_array(mysql_query("SELECT regdate, plusses, chmsgs FROM fun_users WHERE id='" . $uid . "'"));

    $rwage = ceil((time() - $noi[0]) / (24 * 60 * 60));

    $ppd = ceil($noi[1] / $rwage);

    if ($ppd >= 20) {

        $pnts += 5;

    } else {

        $pnts += floor($ppd / 4);

    } 

    $cpd = ceil($noi[2] / $rwage);

    if ($cpd >= 100) {

        $pnts += 5;

    } else {

        $pnts += floor($cpd / 20);

    } 

    return floor($pnts / 3);

} 

// /////////////////////////////////function isuser

function isuser($uid)

{

    $cus = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_users WHERE id='" . $uid . "'"));

    if ($cus[0] > 0) {

        return true;

    } 

    return false;

} 

// //////////////////////////////////////////Can access forum

function canaccess($uid, $fid)

{

    $fex = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_forums WHERE id='" . $fid . "'"));

    if ($fex[0] == 0) {

        return false;

    } 

    $persc = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_acc WHERE fid='" . $fid . "'"));

    if ($persc[0] == 0) {

        $clid = mysql_fetch_array(mysql_query("SELECT clubid FROM fun_forums WHERE id='" . $fid . "'"));

        if ($clid[0] == 0) {

            return true;

        } else {

            if (ismod($uid)) {

                return true;

            } else {

                $ismm = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_clubmembers WHERE uid='" . $uid . "' AND clid='" . $clid[0] . "'"));

                if ($ismm[0] > 0) {

                    return true;

                } else {

                    return false;

                } 

            } 

        } 

    } else {

        $gid = mysql_fetch_array(mysql_query("SELECT gid FROM fun_acc WHERE fid='" . $fid . "'"));

        $gid = $gid[0];

        $ginfo = mysql_fetch_array(mysql_query("SELECT autoass, mage, userst, posts, plusses FROM fun_groups WHERE id='" . $gid . "'"));

        if ($ginfo[0] == "1") {

            $uperms = mysql_fetch_array(mysql_query("SELECT birthday, perm, posts, plusses FROM fun_users WHERE id='" . $uid . "'"));



            if ($ginfo[2] == 2) {

                if (isadmin($uid)) {

                    return true;

                } else {

                    return false;

                } 

            } 



            if ($ginfo[2] == 1) {

                if (ismod($uid)) {

                    return true;

                } else {

                    return false;

                } 

            } 

            if ($uperms[1] > $ginfo[2]) {

                return true;

            } 

            $acc = true;

            if (getage($uperms[0]) < $ginfo[1]) {

                $acc = false;

            } 

            if ($uperms[2] < $ginfo[3]) {

                $acc = false;

            } 

            if ($uperms[3] < $ginfo[4]) {

                $acc = false;

            } 

        } 

    } 

    return $acc;

} 



function unhtmlspecialchars2($string)

{

    $string = str_replace ('&amp;', '&', $string);

    $string = str_replace ('&#039;', '\'', $string);

    $string = str_replace ('&quot;', '"', $string);

    $string = str_replace ('&lt;', '<', $string);

    $string = str_replace ('&gt;', '>', $string);

    $string = str_replace ('&uuml;', '?', $string);

    $string = str_replace ('&Uuml;', '?', $string);

    $string = str_replace ('&auml;', '?', $string);

    $string = str_replace ('&Auml;', '?', $string);

    $string = str_replace ('&ouml;', '?', $string);

    $string = str_replace ('&Ouml;', '?', $string);

    return $string;

} 



function getuage_sid($sid)

{

    $uid = getuid_sid($sid);

    $uage = mysql_fetch_array(mysql_query("SELECT birthday FROM fun_users WHERE id='" . $uid . "'"));

    return getage($uage[0]);

} 



function canenter($rid, $sid)

{

    $rperm = mysql_fetch_array(mysql_query("SELECT mage, perms, chposts, clubid FROM fun_rooms WHERE id='" . $rid . "'"));

    $uperm = mysql_fetch_array(mysql_query("SELECT birthday, chmsgs FROM fun_users WHERE id='" . getuid_sid($sid) . "'"));

    if ($rperm[3] != 0) {

        if (ismod(getuid_sid($sid))) {

            return true;

        } else {

            $ismm = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_clubmembers WHERE uid='" . getuid_sid($sid) . "' AND clid='" . $rperm[3] . "'"));

            if ($ismm[0] > 0) {

                return true;

            } else {

                return false;

            } 

        } 

    } 

    if ($rperm[1] == 1) {

        return ismod(getuid_sid($sid));

    } 

    if ($rperm[1] == 2) {

        return isadmin(getuid_sid($sid));

    } 



    if (getuage_sid($sid) < $rperm[0]) {

        return false;

    } 

    if ($uperm[1] < $rperm[2]) {

        return false;

    } 

    return true;

} 

// /////////////////clear data

function cleardata()

{

    $timeto = 120;

    $timenw = time();

    $timeout = $timenw - $timeto;

    $exec = mysql_query("DELETE FROM fun_chonline WHERE lton<'" . $timeout . "'");

    $timeto = 300;

    $timenw = time();

    $timeout = $timenw - $timeto;

    $exec = mysql_query("DELETE FROM fun_chat WHERE timesent<'" . $timeout . "'");

    $timeto = 60 * 60;

    $timenw = time();

    $timeout = $timenw - $timeto;

    $exec = mysql_query("DELETE FROM fun_search WHERE stime<'" . $timeout . "'"); 

    // /delete expired rooms

    $timeto = 5 * 60;

    $timenw = time();

    $timeout = $timenw - $timeto;

    $rooms = mysql_query("SELECT id FROM fun_rooms WHERE static='0' AND lastmsg<'" . $timeout . "'");

    while ($room = mysql_fetch_array($rooms)) {

        $ppl = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_chonline WHERE rid='" . $room[0] . "'"));

        if ($ppl[0] == 0) {

            $exec = mysql_query("DELETE FROM fun_rooms WHERE id='" . $room[0] . "'");

        } 

    } 

    $lbpm = mysql_fetch_array(mysql_query("SELECT value FROM fun_settings WHERE name='lastbpm'"));

    $td = date("Y-m-d"); 

    // echo $lbpm[0];

    if ($td != $lbpm[0]) {

        // echo "boo";

        $sql = "SELECT id, name, birthday  FROM fun_users where month(`birthday`) = month(curdate()) and dayofmonth(`birthday`) = dayofmonth(curdate())";

        $ppl = mysql_query($sql);

        while ($mem = mysql_fetch_array($ppl)) {

            $msg = "Sretjanrodjendan!!! Zeli ti $stitle tim!!!";

            autopm($msg, $mem[0]);

        } 

        mysql_query("UPDATE fun_settings SET value='" . $td . "' WHERE name='lastbpm'");

    } 

} 

// /////////////////////////////////////get file ext.

function getext($strfnm)

{

    $str = trim($strfnm);

    if (strlen($str) < 4) {

        return $str;

    } 

    for($i = strlen($str);$i > 0;$i--) {

        $ext .= substr($str, $i, 1);

        if (strlen($ext) == 3) {

            $ext = strrev($ext);

            return $ext;

        } 

    } 

} 

// /////////////////////////////////////get extension icon

function getextimg($ext)

{

    $ext = strtolower($ext);

    switch ($ext) {

        case "jpg":

        case "jpeg":

        case "gif":

        case "png":

        case "bmp":

            return "<img src=\"images/image.gif\" alt=\"image\"/>";

            break;

        case "zip":

        case "rar":

            return "<img src=\"images/pack.gif\" alt=\"package\"/>";

            break;

        case "amr":

        case "wav":

        case "mp3":

        case "m4a":

            return "<img src=\"images/music.gif\" alt=\"music\"/>";

            break;

        case "mpg":

        case "3gp":

        case "mp4":

            return "<img src=\"images/video.gif\" alt=\"video\"/>";

            break;

        default:

            return "<img src=\"images/other.gif\" alt=\"!\"/>";

            break;

    } 

} 

// /////////////////////////////////////Add to chat

function addtochat($uid, $rid)

{

    $timeto = 2760;

    $timenw = time();

    $timeout = $timenw - $timeto;

    $exec = mysql_query("DELETE FROM fun_chonline WHERE lton<'" . $timeout . "'");

    $res = mysql_query("INSERT INTO fun_chonline SET lton='" . time() . "', uid='" . $uid . "', rid='" . $rid . "'");

    if (!$res) {

        mysql_query("UPDATE fun_chonline SET lton='" . time() . "', rid='" . $rid . "' WHERE uid='" . $uid . "'");

    } 

} 

// //////////////////////////////////////////is mod

function ismod($uid)

{

    $perm = mysql_fetch_array(mysql_query("SELECT perm FROM fun_users WHERE id='" . $uid . "'"));



    if ($perm[0] > 0) {

        return true;

    } 

} 

// //////////////////////////////////////////is mod

function candelgb($uid, $mid)

{

    $minfo = mysql_fetch_array(mysql_query("SELECT gbowner, gbsigner FROM fun_gbook WHERE id='" . $mid . "'"));

    if ($minfo[0] == $uid) {

        return true;

    } 

    if ($minfo[1] == $uid) {

        return true;

    } 

    return false;

} 

// //////////////////////////////////////////Spam filter

function isspam($text)

{

    $sfil[0] = "www.";

    $sfil[1] = "http:";

    $text = str_replace(" ", "", $text);

    $text = strtolower($text);

    for($i = 0;$i < count($sfil);$i++) {

        $nosf = substr_count($text, $sfil[$i]);

        if ($nosf > 0) {

            return true;

        } 

    } 



    return false;

} 

// /////////////////////////////////get page from go

function getpage_go($go, $tid)

{

    if (trim($go) == "")return 1;

    if ($go == "last")return getnumpages($tid);

    $counter = 1;



    $posts = mysql_query("SELECT id FROM fun_posts WHERE tid='" . $tid . "'");

    while ($post = mysql_fetch_array($posts)) {

        $counter++;

        $postid = $post[0];

        if ($postid == $go) {

            $tore = ceil($counter / 5);

            return $tore;

        } 

    } 

    return 1;

} 

// //////////////////////////get number of topic pages

function getnumpages($tid)

{

    $nops = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_posts WHERE tid='" . $tid . "'"));

    $nops = $nops[0] + 1; //where did the 1 come from? the topic text, duh!

    $nopg = ceil($nops / 5); //5 is the posts to show in each page

    return $nopg;

} 

// //////////////////////////////////////////can delete a blog?

function candelbl($uid, $bid)

{

    $minfo = mysql_fetch_array(mysql_query("SELECT bowner FROM fun_blogs WHERE id='" . $bid . "'"));

    if (ismod($uid)) {

        return true;

    } 

    if ($minfo[0] == $uid) {

        return true;

    } 



    return false;

} 

// ////////////////////////////////////////////////RAVEBABE

function PostToHost($host, $path, $data_to_send)

{

    $result = "";

    $fp = fsockopen($host, 80, $errno, $errstr, 30);

    if ($fp) {

        fputs($fp, "POST $path HTTP/1.0\n");

        fputs($fp, "Host: $host\n");

        fputs($fp, "Content-type: application/x-www-form-urlencoded\n");

        fputs($fp, "Content-length: " . strlen($data_to_send) . "\n");

        fputs($fp, "Connection: close\n\n");

        fputs($fp, $data_to_send);



        while (!feof($fp)) {

            $result .= fgets($fp, 128);

        } 

        fclose($fp);



        return $result;

    } 

} 

// ///////////////////////Get user plusses

function getplusses($uid)

{

    $plus = mysql_fetch_array(mysql_query("SELECT plusses FROM fun_users WHERE id='" . $uid . "'"));

    return $plus[0];

} 

// ///////////////////////Can uid sign who's guestbook?

function cansigngb($uid, $who)

{

    if (arebuds($uid, $who)) {

        return true;

    } 

    if ($uid == $who) {

        return false; //imagine if someone signed his own gbook o.O

    } 

    if (getplusses($uid) >= 0) {

        return true;

    } 

    return false;

} 

// ///////////////////////////////////////////Are buds?

function arebuds($uid, $tid)

{

    $res = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_buddies WHERE ((uid='" . $uid . "' AND tid='" . $tid . "') OR (uid='" . $tid . "' AND tid='" . $uid . "')) AND agreed='1'"));

    if ($res[0] > 0) {

        return true;

    } 

    return false;

} 

// ////////////////////////////////function get n. of buds

function getnbuds($uid)

{

    $notb = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_buddies WHERE (uid='" . $uid . "' OR tid='" . $uid . "') AND agreed='1'"));

    return $notb[0];

} 

// ///////////////////////////get no. of requists

function getnreqs($uid)

{

    $notb = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_buddies WHERE  tid='" . $uid . "' AND agreed='0'"));

    return $notb[0];

} 

// ///////////////////////////get no. of online buds

function getshlikes($who, $uid)

{

    $likes = mysql_query("SELECT COUNT(id) FROM fun_shout_like WHERE (uid='" . $uid . "' OR shid='" . $who . "') AND liked='1'");

    while ($lik = mysql_fetch_array($likes)) {

        if ($lik[0] == $uid) {

            $liks = $lik[1];

        } else {

            $liks = $lik[0];

        } 

        echo $liks;

    } 

} 

// ///////////////////////////get no. of online buds

function getonbuds($uid)

{

    $counter = 0;

    $buds = mysql_query("SELECT uid, tid FROM fun_buddies WHERE (uid='" . $uid . "' OR tid='" . $uid . "') AND agreed='1'");

    while ($bud = mysql_fetch_array($buds)) {

        if ($bud[0] == $uid) {

            $tid = $bud[1];

        } else {

            $tid = $bud[0];

        } 

        if (isonline($tid)) {

            $counter++;

        } 

    } 

    return $counter;

} 

// ///////////////////////////////////////////get tid frm post id

function gettid_pid($pid)

{

    $tid = mysql_fetch_array(mysql_query("SELECT tid FROM fun_posts WHERE id='" . $pid . "'"));

    return $tid[0];

} 

// /////////////////////////////////////////is trashed?

function istrashed($uid)

{

    $del = mysql_query("DELETE FROM fun_penalties WHERE timeto<'" . time() . "'");

    $not = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_penalties WHERE uid='" . $uid . "' AND penalty='0'"));

    if ($not[0] > 0) {

        return true;

    } else {

        return false;

    } 

} 

// /////////////////////////////////////////is shielded?

function isshield($uid)

{

    $not = mysql_fetch_array(mysql_query("SELECT shield FROM fun_users WHERE id='" . $uid . "'"));

    if ($not[0] == '1') {

        return true;

    } else {

        return false;

    } 

} 

// /////////////////////////////////////////Get IP

function getip_uid($uid)

{

    $not = mysql_fetch_array(mysql_query("SELECT ipadd FROM fun_users WHERE id='" . $uid . "'"));

    return $not[0];

} 

// /////////////////////////////////////////Get Browser

function getbr_uid($uid)

{

    $not = mysql_fetch_array(mysql_query("SELECT browserm FROM fun_users WHERE id='" . $uid . "'"));

    return $not[0];

} 

// /////////////////////////////////////////is trashed?

function isbanned($uid)

{

    $del = mysql_query("DELETE FROM fun_penalties WHERE timeto<'" . time() . "'");

    $not = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_penalties WHERE uid='" . $uid . "' AND (penalty='1' OR penalty='2')"));



    if ($not[0] > 0) {

        return true;

    } else {

        return false;

    } 

} 

// ///////////////////////////////////////////get tid frm post id

function gettname($tid)

{

    $tid = mysql_fetch_array(mysql_query("SELECT name FROM fun_topics WHERE id='" . $tid . "'"));

    return $tid[0];

} 

// ///////////////////////////////////////////get tid frm post id

function getfid_tid($tid)

{

    $fid = mysql_fetch_array(mysql_query("SELECT fid FROM fun_topics WHERE id='" . $tid . "'"));

    return $fid[0];

} 

// ///////////////////////////////////////////is ip banned

function isipbanned($ipa, $brm)

{

    $pinf = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_penalties WHERE penalty='2' AND ipadd='" . $ipa . "' AND browserm='" . $brm . "'"));

    if ($pinf[0] > 0) {

        return true;

    } 

    return false;

} 

// //////////////get number of pinned topics in forum

function getpinned($fid)

{

    $nop = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_topics WHERE fid='" . $fid . "' AND pinned ='1'"));

    return $nop[0];

} 

// ///////////////////////////////////////////can bud?

function budres($uid, $tid)

{ 

    // 3 = can't bud

    // 2 = already buds

    // 1 = request pended

    // 0 = can bud

    if ($uid == $tid) {

        return 3;

    } 



    if (arebuds($uid, $tid)) {

        return 2;

    } 

    $req = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_buddies WHERE ((uid='" . $uid . "' AND tid='" . $tid . "') OR (uid='" . $tid . "' AND tid='" . $uid . "')) AND agreed='0'"));

    if ($req[0] > 0) {

        return 1;

    } 

    $notb = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_buddies WHERE (uid='" . $tid . "' OR tid='" . $tid . "') AND agreed='1'"));

    global $max_buds;

    if ($notb[0] >= $max_buds) {

        return 3;

    } 

    $notb = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_buddies WHERE (uid='" . $uid . "' OR tid='" . $uid . "') AND agreed='1'"));

    global $max_buds;

    if ($notb[0] >= $max_buds) {

        return 3;

    } 

    return 0;

} 

// //////////////////////////////////////////Session expiry time

function getsxtm()

{

    $getdata = mysql_fetch_array(mysql_query("SELECT value FROM fun_settings WHERE name='sesexp'"));

    return $getdata[0];

} 

// //////////////////////////////////////////Get bud msg

function getbudmsg($uid)

{

    $getdata = mysql_fetch_array(mysql_query("SELECT budmsg FROM fun_users WHERE id='" . $uid . "'"));

    return $getdata[0];

} 

// //////////////////////////////////////////Get forum name

function getfname($fid)

{

    $fname = mysql_fetch_array(mysql_query("SELECT name FROM fun_forums WHERE id='" . $fid . "'"));

    return $fname[0];

} 

// //////////////////////////////////////////PM antiflood time

function getpmaf()

{

    $getdata = mysql_fetch_array(mysql_query("SELECT value FROM fun_settings WHERE name='pmaf'"));

    return $getdata[0];

} 

// //////////////////////////////////////////PM antiflood time

function getfview()

{

    $getdata = mysql_fetch_array(mysql_query("SELECT value FROM fun_settings WHERE name='fview'"));

    return $getdata[0];

} 

// //////////////////////////////////////////get forum message

function getfmsg()

{

    $getdata = mysql_fetch_array(mysql_query("SELECT value FROM fun_settings WHERE name='4ummsg'"));

    return $getdata[0];

} 

// ////////////////////////////////////////////is online

function isonline($uid)

{

    $uon = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_online WHERE userid='" . $uid . "'"));

    if ($uon[0] > 0) {

        return true;

    } else {

        return false;

    } 

} 

// /////////////////////////if registration is allowed

function canreg()

{

    $getreg = mysql_fetch_array(mysql_query("SELECT value FROM fun_settings WHERE name='reg'"));

    if ($getreg[0] == '1') {

        return true;

    } else {

        return false;

    } 

} 

// /////////////////////////////////////////Get Forum ID

function getfid($topicid)

{

    $fid = mysql_fetch_array(mysql_query("SELECT fid FROM fun_topics WHERE id='" . $topicid . "'"));

    return $fid[0];

} 

// //////////////////////////////////////////Parse PM

// //anti spam

function parsepm($text, $sid = "")

{

    $text = $text;

    $sml = mysql_fetch_array(mysql_query("SELECT hvia FROM fun_users WHERE id='" . getuid_sid($sid) . "'"));

    if ($sml[0] == "1") {

        $text = getsmilies($text);

    } 

    $text = getbbcode($text, $sid);

    $text = findcard($text);

    return $text;

} 

// //////////////////////////////////////////Parse other msgs

function parsemsg($text, $sid = "")

{

    $text = htmlspecialchars($text);

    $sml = mysql_fetch_array(mysql_query("SELECT hvia FROM fun_users WHERE id='" . getuid_sid($sid) . "'"));

    if ($sml[0] == "1") {

        $text = getsmilies($text);

    } 

    $text = getbbcode($text, $sid);

    $text = findcard($text);

    return $text;

} 

// /////////////////////////////////////////Is site blocked

function isblocked($str, $sender)

{

    if (ismod($sender)) {

        return false;

    } 

    $str = str_replace(" ", "", $str);

    $sites[0] = "1.1.1.1.1.1.1.1";

    for($i = 0;$i < count($sites);$i++) {

        $nosf = substr_count($str, $sites[$i]);

        if ($nosf > 0) {

            return true;

        } 

    } 

    return false;

} 

// /////////////////////////////////////////Is pm starred

function isstarred($pmid)

{

    $strd = mysql_fetch_array(mysql_query("SELECT starred FROM fun_private WHERE id='" . $pmid . "'"));

    if ($strd[0] == "1") {

        return true;

    } else {

        return false;

    } 

} 

// //////////////////////////////////////////IS LOGGED?

function islogged($sid)

{ 

    // delete old sessions first

    $deloldses = mysql_query("DELETE FROM fun_ses WHERE expiretm<'" . time() . "'"); 

    // does sessions exist?

    $sesx = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_ses WHERE id='" . $sid . "'"));



    if ($sesx[0] > 0) {

        if (!isuser(getuid_sid($sid))) {

            return false;

        } 

        // yip it's logged in

        // first extend its session expirement time

        $xtm = time() + (60 * getsxtm());

        $extxtm = mysql_query("UPDATE fun_ses SET expiretm='" . $xtm . "' WHERE id='" . $sid . "'");

        return true;

    } else {

        // nope its session must be expired or something

        return false;

    } 

} 

// //////////////////////Get user nick from session id

function getnick_sid($sid)

{

    $uid = mysql_fetch_array(mysql_query("SELECT uid FROM fun_ses WHERE id='" . $sid . "'"));

    $uid = $uid[0];

    return getnick_uid($uid);

} 

// //////////////////////Get user id from session id

function getuid_sid($sid)

{

    $uid = mysql_fetch_array(mysql_query("SELECT uid FROM fun_ses WHERE id='" . $sid . "'"));

    $uid = $uid[0];

    return $uid;

} 

// ///////////////////Get total number of pms

function getnotifycount($uid, $view = "all")

{

    if ($view == "all") {

        $nopm = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_notify WHERE touid='" . $uid . "'"));

    } else if ($view == "snt") {

        $nopm = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_notify WHERE byuid='" . $uid . "'"));

    } else if ($view == "str") {

        $nopm = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_notify WHERE touid='" . $uid . "' AND starred='1'"));

    } else if ($view == "urd") {

        $nopm = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_notify WHERE touid='" . $uid . "' AND unread='1'"));

    } 

    return $nopm[0];

} 

// ///////////////////Get total number of pms

function getpmcount($uid, $view = "all")

{

    if ($view == "all") {

        $nopm = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_private WHERE touid='" . $uid . "'"));

    } else if ($view == "snt") {

        $nopm = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_private WHERE byuid='" . $uid . "'"));

    } else if ($view == "str") {

        $nopm = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_private WHERE touid='" . $uid . "' AND starred='1'"));

    } else if ($view == "urd") {

        $nopm = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_private WHERE touid='" . $uid . "' AND unread='1'"));

    } 

    return $nopm[0];

} 



function deleteClub($clid)

{

    $fid = mysql_fetch_array(mysql_query("SELECT id FROM fun_forums WHERE clubid='" . $clid . "'"));

    $fid = $fid[0];

    $topics = mysql_query("SELECT id FROM fun_topics WHERE fid=" . $fid . "");

    while ($topic = mysql_fetch_array($topics)) {

        mysql_query("DELETE FROM fun_posts WHERE tid='" . $topic[0] . "'");

    } 

    mysql_query("DELETE FROM fun_topics WHERE fid='" . $fid . "'");

    mysql_query("DELETE FROM fun_forums WHERE id='" . $fid . "'");

    mysql_query("DELETE FROM fun_rooms WHERE clubid='" . $clid . "'");

    mysql_query("DELETE FROM fun_clubmembers WHERE clid='" . $clid . "'");

    mysql_query("DELETE FROM fun_announcements WHERE clid='" . $clid . "'");

    mysql_query("DELETE FROM fun_clubs WHERE id=" . $clid . "");

    return true;

} 



function deleteMClubs($uid)

{

    $uclubs = mysql_query("SELECT id FROM fun_clubs WHERE owner='" . $uid . "'");

    while ($uclub = mysql_fetch_array($uclubs)) {

        deleteClub($uclub[0]);

    } 

} 

// ////////////////////Function add user to online list :P

function addonline($uid, $place, $plclink)

{ 

    // ///delete inactive users

    $tm = time();

    $timeout = $tm - 3780; //time out = 45 minutes

    $deloff = mysql_query("DELETE FROM fun_online WHERE actvtime <'" . $timeout . "'"); 

    // /now try to add user to online list

    $res = mysql_query("UPDATE fun_users SET lastact='" . time() . "' WHERE id='" . $uid . "'");

    $res = mysql_query("INSERT INTO fun_online SET userid='" . $uid . "', actvtime='" . $tm . "', place='" . $place . "', placedet='" . $plclink . "'");

    if (!$res) {

        // most probably userid already in the online list

        // so just update the place and time

        $res = mysql_query("UPDATE fun_online SET actvtime='" . $tm . "', place='" . $place . "', placedet='" . $plclink . "' WHERE userid='" . $uid . "'");

    } 

    $maxmem = mysql_fetch_array(mysql_query("SELECT value FROM fun_settings WHERE id='2'"));



    $result = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_online"));



    if ($result[0] >= $maxmem[0]) {

        $tnow = date("D d M Y - H:i");

        mysql_query("UPDATE fun_settings set name='" . $tnow . "', value='" . $result[0] . "' WHERE id='2'");

    } 

    $maxtoday = mysql_fetch_array(mysql_query("SELECT ppl FROM fun_mpot WHERE ddt='" . date("d m y") . "'"));

    if ($maxtoday[0] == 0 || $maxtoday == "") {

        mysql_query("INSERT INTO fun_mpot SET ddt='" . date("d m y") . "', ppl='1', dtm='" . date("H:i:s") . "'");

        $maxtoday[0] = 1;

    } 

    if ($result[0] >= $maxtoday[0]) {

        mysql_query("UPDATE fun_mpot SET ppl='" . $result[0] . "', dtm='" . date("H:i:s") . "' WHERE ddt='" . date("d m y") . "'");

    } 

} 

// ///////////////////Get members online

function getnumonline()

{

    $nouo = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_online "));

    return $nouo[0];

} 

// ////////////////////////////////////is ignored

function isignored($tid, $uid)

{

    $ign = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_ignore WHERE target='" . $tid . "' AND name='" . $uid . "'"));

    if ($ign[0] > 0) {

        return true;

    } 

    return false;

} 

// /////////////////////////////////////////GET IP

function getip()

{

    if (getenv('HTTP_X_FORWARDED_FOR')) {

        $ip = getenv('HTTP_X_FORWARDED_FOR');

    } else {

        $ip = getenv('REMOTE_ADDR');

    } 

    return $ip;

} 

// ////////////////////////////////////////ignore result

function ignoreres($uid, $tid)

{ 

    // 0 user can't ignore the target

    // 1 yes can ignore

    // 2 already ignored

    if ($uid == $tid) {

        return 0;

    } 

    if (ismod($tid)) {

        // you cant ignore staff members

        return 0;

    } 

    if (arebuds($tid, $uid)) {

        // why the hell would anyone ignore his bud? o.O

        return 0;

    } 

    if (isignored($tid, $uid)) {

        return 2; // the target is already ignored by the user

    } 

    return 1;

} 

// /////////////////////////////////////////Function getage

function getage($strdate)

{

    $dob = explode("-", $strdate);

    if (count($dob) != 3) {

        return 0;

    } 

    $y = $dob[0];

    $m = $dob[1];

    $d = $dob[2];

    if (strlen($y) != 4) {

        return 0;

    } 

    if (strlen($m) != 2) {

        return 0;

    } 

    if (strlen($d) != 2) {

        return 0;

    } 

    $y += 0;

    $m += 0;

    $d += 0;

    if ($y == 0) return 0;

    $rage = date("Y") - $y;

    if (date("m") < $m) {

        $rage -= 1;

    } else {

        if ((date("m") == $m) && (date("d") < $d)) {

            $rage -= 1;

        } 

    } 

    return $rage;

} 

// ///////////////////////////////////////getavatar

function getavatar($uid)

{

    $av = mysql_fetch_array(mysql_query("SELECT avatar FROM fun_users WHERE id='" . $uid . "'"));

    return $av[0];

} 

// ///////////////////////////////////////Can see details?

function cansee($uid, $tid)

{

    if ($uid == $tid) {

        return true;

    } 

    if (ismod($uid)) {

        return true;

    } 

    return false;

} 

// ////////////////////////gettimemsg

function gettimemsg($sec)

{

global $lng;

        if ($sec < 0) $sec = 0;

        $day = floor($sec / 60 / 60 / 24);		  

        if ($sec > 345600) { return 'Pre ' . $day . ' dana'; }

        if ($sec >= 172800) { return 'Pre ' . $day . ' dana'; }

        if ($sec >= 86400) { return 'Juce u ' . date("H:i", mktime(0, 0, $sec)); }

        $hs = floor($sec / 60 / 60);

        if ($hs > 0) { return "Pre $hs h"; } 

        $ms = floor($sec / 60);

        if ($ms > 0) { return "Pre $ms minuta"; } 

        return "Bas sada.";

		

   // $ds = floor($sec / 60 / 60 / 24);

   // if ($ds > 0) { return "Pre $ds dana"; } 

   // $hs = floor($sec / 60 / 60);

  //  if ($hs > 0) {return "Pre $hs sata"; } 

  //  $ms = floor($sec / 60);

  //  if ($ms > 0) { return "Pre $ms minuta"; } 

  //  return "Bas sada.";

} 

// ///////////////////////////////////////get status

function getstatus($uid)

{

    $info = mysql_fetch_array(mysql_query("SELECT perm, plusses FROM fun_users WHERE id='" . $uid . "'"));

    if (isbanned($uid)) {

        return "BANNED!";

    } 

    if ($info[0] == '2') {

        return "Administrator!";

    } else if ($info[0] == '1') {

        return "Moderator!";

    } else {

        if ($info[1] < 10) {

            return "Newbie";

        } else if ($info[1] < 25) {

            return "Rookie";

        } else if ($info[1] < 50) {

            return "Funster";

        } else if ($info[1] < 75) {

            return "Fun Meister";

        } else if ($info[1] < 250) {

            return "Fuantic";

        } else if ($info[1] < 500) {

            return "ViTa1";

        } else if ($info[1] < 750) {

            return "Lava unplugged";

        } else if ($info[1] < 1000) {

            return "GuRu";

        } else if ($info[1] < 1500) {

            return "V.I.P";

        } else if ($info[1] < 2000) {

            return "FaNatic";

        } else if ($info[1] < 2500) {

            return "Ultimate";

        } else if ($info[1] < 3000) {

            return "VeteRaN";

        } else if ($info[1] < 4000) {

            return "Superb";

        } else if ($info[1] < 5000) {

            return "FunMaster";

        } else if ($info[1] < 10000) {

            return "ic0N";

        } else {

            return "Fun newbie";

        } 

    } 

} 

// ///////////////////Get Page Jumber

function getjumper($action, $sid, $pgurl)

{

    $rets = "Idi na stranu<input name=\"pg\" format=\"*N\" size=\"3\"/>";

    $rets .= "<anchor>[OK]";

    $rets .= "<go href=\"$pgurl.php\" method=\"get\">";

    $rets .= "<postfield name=\"action\" value=\"$action\"/>";

    $rets .= "<postfield name=\"sid\" value=\"$sid\"/>";

    $rets .= "<postfield name=\"page\" value=\"$(pg)\"/>";

    $rets .= "</go></anchor>";



    return $rets;

} 

// ///////////////////Get number liked shouts

function likedshouts($who, $uid)

{

    $liked = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_shout_like WHERE shid='" . $who . "' AND uid='" . $uid . "'"));

    return $liked[0];

} 

// ///////////////////Get unread number of pms

function getunreadpm($uid)

{

    $nopm = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_private WHERE touid='" . $uid . "' AND unread='1'"));

    return $nopm[0];

} 

// ////////////////////GET USER NICK FROM USERID

function getnick_uid($uid)

{

    $unick = mysql_fetch_array(mysql_query("SELECT name FROM fun_users WHERE id='" . $uid . "'"));

    return $unick[0];

} 

// /////////////////////////////////////////////Get the smilies

function getsmilies($text)

{

    $sql = "SELECT * FROM fun_smilies";

    $smilies = mysql_query($sql);

    while ($smilie = mysql_fetch_array($smilies)) {

        $scode = $smilie[1];

        $spath = $smilie[2];

        $text = str_replace($scode, "<img src='$spath' alt='$scode' />", $text);

    } 

    return $text;

} 

function nosmilies($text)

{

        $text = str_replace("<img src='(.*?)' alt='(.*?)' />", "$2", $text);

    return $text;

} 

// //////////////////////////////////////////check nicks

function checknick($aim)

{

    $chk = 0;

    $aim = strtolower($aim);

    $nicks = mysql_query("SELECT id, name, nicklvl FROM fun_nicks");



    while ($nick = mysql_fetch_array($nicks)) {

        if ($aim == $nick[1]) {

            $chk = $nick[2];

        } else if (substr($aim, 0, strlen($nick[1])) == $nick[1]) {

            $chk = $nick[2];

        } else {

            $found = strpos($aim, $nick[1]);

            if ($found != 0) {

                $chk = $nick[2];

            } 

        } 

    } 

    return $chk;

} 



function autopm($msg, $who)

{

    mysql_query("INSERT INTO fun_private SET text='" . $msg . "', byuid='1', touid='" . $who . "', unread='1', timesent='" . time() . "'");

} 

// //////////////////////////////////////////////////Register

function register($name, $pass, $usx, $bdy, $ulc, $ubr, $eml)

{

    $execms = mysql_query("SELECT * FROM fun_users WHERE name='" . $name . "';");



    if (mysql_num_rows($execms) > 0) {

        return 1;

    } else {

        $pass = md5($pass);

        $reg = mysql_query("INSERT INTO fun_users SET name='" . $name . "', pass='" . $pass . "', birthday='" . $bdy . "', sex='" . $usx . "', location='" . $ulc . "', regdate='" . time() . "', ipadd='" . getip() . "', browserm='" . $ubr . "', email='" . $eml . "'");

        $eml = $_POST["eml"];



        if ($reg) {

            $uid = mysql_fetch_array(mysql_query("SELECT id FROM fun_users WHERE name='" . $name . "'"));

            $msg = "Cao /reader =)

		Dobro dosao/la na $stitle, drago nam je sto si sa nama, lepo se zabavi! 

		Nadji nove prijatelje, druzi se, dopisuj, razmenjuj slike, otvaraj teme, komentarisi, lajkuj...

		I jos mnogo toga samo na $stitle ;)";

            $msg = mysql_escape_string($msg);

            autopm($msg, $uid[0]);

            return 0;

        } else {

            return 2;

        } 

    } 

} 

// ///////////////////// GET fun_users user id from nickname

function getuid_nick($nick)

{

    $uid = mysql_fetch_array(mysql_query("SELECT id FROM fun_users WHERE name='" . $nick . "'"));

    return $uid[0];

} 

// ///////////////////////////////////////Is admin?

function isadmin($uid)

{

    $admn = mysql_fetch_array(mysql_query("SELECT perm FROM fun_users WHERE id='" . $uid . "'"));

    if ($admn[0] == '2') {

        return true;

    } else {

        return false;

    } 

} 

// /////////////////////////////////parse bbcode

function highlight_code($text)

{

$text = nosmilies($text);

    $text = strtr($text, array('&lt;' => '<', '&gt;' => '>', '&amp;' => '&', '&quot;' => '"', '&#36;' => '$', '&#37;' => '%', '&#39;' => "'", '&#92;' => '\\', '&#94;' => '^', '&#96;' => '`', '&#124;' => '|', '[code]' => '', '[/code]' => '', '<br />' => "\r\n"));



    $text = highlight_string($text, true);

    $text = strtr($text, array("\r\n" => '<br />', '$' => '&#36;', "'" => '&#39;', '%' => '&#37;', '\\' => '&#92;', '`' => '&#96;', '^' => '&#94;', '|' => '&#124;'));



    $text = '<p><div class="d">' . $text . '</div></p>';

    return $text;

} 

function hidden_text($msg)

{

$sid = $_SESSION["sid"];

$uid = getuid_sid("$sid");

    if (isuser($uid)) {

        $text = '<p><div class="hide">' . $msg . '</div></p>';

    } else {

        $text = '<p><div class="hide"><b>Sakriveno</b> Za pregled ove poruke morate biti prijavljeni!</div></p>';

    } 



    return $text;

} 

function grupaclanova($clid, $name)

{

$sid = $_SESSION["sid"];

$mems = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_clubmembers WHERE clid='" . $clid . "' AND accepted='1'"));

$name = mysql_fetch_array(mysql_query("SELECT name FROM fun_clubs WHERE id='" . $clid . "'"));



    return "<p><div style='border-left:3px solid #777;margine-left:2px;'><a href=\"grupa.php?clid=$clid\">$name[0]</a> <br /> <small>Grupa: " . $mems[0] . " ljudi voli ovu grupu.</small></div></p>";

}

function getbbcode($text, $sid = "")

{

    $text = trim($text);

    $sid = $_GET['sid'];

    //$text = preg_replace('(\[code\](.*?)\[\/code\])is', highlight_code(nosmilies($text)), $text);

    //$text = preg_replace('#\[hide\](.*?)\[/hide\]#ie', 'hidden_text("\1")', $text);

    $text = str_replace("\r\n", "<br> ", $text);

    $text = str_replace("\n", '<br/> ', $text);

    $text = preg_replace("/\[b\](.*?)\[\/b\]/i", "<b>\\1</b>", $text);

    $text = preg_replace("/\[i\](.*?)\[\/i\]/i", "<i>\\1</i>", $text);

    $text = preg_replace("/\[u\](.*?)\[\/u\]/i", "<u>\\1</u>", $text);

    $text = preg_replace("/\[big\](.*?)\[\/big\]/i", "<big>\\1</big>", $text);

    $text = preg_replace("/\[small\](.*?)\[\/small\]/i", "<small>\\1</small>", $text);

    $text = preg_replace("/\[url\=(.*?)\](.*?)\[\/url\]/is", "<a href=\"$1\">$2</a>", $text);

    $text = preg_replace("/\[topic\=(.*?)\](.*?)\[\/topic\]/is", "<a href=\"index.php?viewtpc&amp;tid=$1\">$2</a>", $text);

    $text = preg_replace("/\[shout\=(.*?)\](.*?)\[\/shout\]/is", "<a href=\"komentari.php?main&amp;who=$1\">$2</a>", $text);

    $text = preg_replace("/\[zid\=(.*?)\](.*?)\[\/zid\]/is", "<a href=\"komentari.php?zid&amp;who=$1\">$2</a>", $text);



    $text = preg_replace('#\[club\=(.*?)\](.*?)\[/club\]#ie', 'grupaclanova("\1", "\2")', $text);

    $text = preg_replace("/\[foto\=(.*?)\](.*?)\[\/foto\]/is", "<a href=\"index.php?fotopogled&amp;fotka=$1\">$2</a>", $text);

    $text = preg_replace("/\[blog\=(.*?)\](.*?)\[\/blog\]/is", "<a href=\"zapis.php?viewblog&amp;bid=$1\">$2</a>", $text);

    $text = preg_replace("/\[user\=(.*?)\](.*?)\[\/user\]/is", "<a href=\"index.php?viewuser&amp;who=$1\">$2</a>", $text); 

    // $text = ereg_replace("http://[A-Za-z0-9./=?-_]+","<a href=\"\\0\">\\0</a>", $text);

    $text = preg_replace("/\[list\](.*?)\[\/list\]/is", '<ul>\\1</ul>', $text); // List

    $text = preg_replace("(\[s\](.*?)\[\/s\])is", '<span class="bbcode_strikethrough">$1</span>', $text); // Strike through

    $text = preg_replace("(\[o\](.*?)\[\/o\])is", '<span class="bbcode_overline">$1</span>', $text); // Overline

    $text = preg_replace("(\[font=(.*?)\](.*?)\[\/font\])", '<span style="font-family: $1;">$2</span>', $text); // Font

    $text = preg_replace("(\[color=(.*?)\](.*?)\[\/color\])is", '<span style="color: $1">$2</span>', $text); // Color

    $text = preg_replace("(\[size=(.*?)\](.*?)\[\/size\])is", '<span style="font-size: $1px">$2</span>', $text); // Font-Size

    $text = preg_replace("/\[list\](.*?)\[\/list\]/is", '<ul>$1</ul>', $text); // List

    $text = str_replace("[*]", "<li>", $text); // List-Item 

    // Code and Quote Tags

    // $kod = highlight_code(" $text");

    // $text = preg_replace("(\[code\](.*?)\[\/code\])is", '$1', $kod); // Code

    $text = preg_replace("(\[quote\](.*?)\[\/quote\])is", '<div class="d">$1</div>', $text); // Quote 

    // $text = preg_replace("(\[hide\](.*?)\[\/hide\])is", 'hidden_text($1);', $text); // Quote

    $text = preg_replace("/\[img\](.*?)\[\/img\]/", '<img src="$1" />', $text); // Image

    $text = preg_replace("/\[img\=([0-9]*)x([0-9]*)\](.*?)\[\/img\]/", '<img src="$3" class="img" height="$2" width="$1" />', $text); //Image with width and height

    $text = str_replace("<3", "&#9829;", $text); // List-Item

    if (substr_count($text, "[br/]") <= 3) {

        $text = str_replace("[br/]", "<br/>", $text);

    } 

    // //////////

    $youtube_replacement = '<p><object style="width:425px; height:350px;">

			<param name="movie" value="http://www.youtube.com/v/$1"></param>

			<param name="wmode" value="transparent"></param>

			<embed src="http://www.youtube.com/v/$1" wmode="transparent" type="application/x-shockwave-flash" style="width:425px; height:350px;"></embed>

			</object></p>';

    $text = preg_replace("/\[youtube\](.*?)\[\/youtube\]/", $youtube_replacement, $text); // YouTube

    $youtube_replacement = '<p><object style="width:$1px; height:$2px;">

			<param name="movie" value="http://www.youtube.com/v/$3"></param>

			<param name="wmode" value="transparent"></param>

			<embed src="http://www.youtube.com/v/$3" wmode="transparent" type="application/x-shockwave-flash" style="width:$1px; height:$2px;"></embed>

			</object></p>';

    $text = preg_replace("/\[youtube\=([0-9]*)x([0-9]*)\](.*?)\[\/youtube\]/", $youtube_replacement, $text); // YouTube with width/height

    $googlevid_replacement = '<p><object style="width:400px; height:326px;">

			<param name="movie" value=http://video.google.com/googleplayer.swf?docId=$1"></param>

			<embed src="http://video.google.com/googleplayer.swf?docId=$1" wmode="transparent" style="width:400px; height:326px;" 

			id="VideoPlayback" type="application/x-shockwave-flash" flashvars=""></embed></p>';

    $text = preg_replace("/\[googlevid\](.*?)\[\/googlevid\]/", $googlevid_replacement, $text); // Google Video

    $googlevid_replacement = '<p><object style="width:$1px; height:$2px;">

			<param name="movie" value=http://video.google.com/googleplayer.swf?docId=$3"></param>

			<embed src="http://video.google.com/googleplayer.swf?docId=$3" wmode="transparent" style="width:$1px; height:$2px;" 

			id="VideoPlayback" type="application/x-shockwave-flash" flashvars=""></embed></p>';

    $text = preg_replace("/\[googlevid=([0-9]*)x([0-9]*)\](.*?)\[\/googlevid\]/", $googlevid_replacement, $text); // Google Video with width/height 

    // //////////

    $text = str_replace("<3", "&hearts;", $text);

    return $text;

} 

// ////////////////////////////////////////////////MISC FUNCTIONS

function spacesin($word)

{

    $pos = strpos($word, " ");

    if ($pos === false) {

        return false;

    } else {

        return true;

    } 

} 

// ///////////////////////////////Number of registered members

function regmemcount()

{

    $rmc = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_users"));

    return $rmc[0];

} 

// /////

// /////////////////////////function counter

function addvisitor()

{

    $cc = mysql_fetch_array(mysql_query("SELECT value FROM fun_settings WHERE name='Counter'"));

    $cc = $cc[0] + 1;

    $res = mysql_query("UPDATE fun_settings SET value='" . $cc . "' WHERE name='Counter'");

} 



function scharin($word)

{

    $chars = "abcdefghijklmnopqrstuvwxyz0123456789-_";

    for($i = 0;$i < strlen($word);$i++) {

        $ch = substr($word, $i, 1);

        $nol = substr_count($chars, $ch);

        if ($nol == 0) {

            return true;

        } 

    } 

    return false;

} 



function isdigitf($word)

{

    $chars = "abcdefghijklmnopqrstuvwxyz";

    $ch = substr($word, 0, 1);

    $sres = ereg("[0-9]", $ch);



    $ch = substr($word, 0, 1);

    $nol = substr_count($chars, $ch);

    if ($nol == 0) {

        return true;

    } 



    return false;

} 



?>
