<?php

include("core.php");
include("config.php");

header("Content-type: text/html; charset=ISO-8859-1");
echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>";
echo "<!DOCTYPE html PUBLIC \"-//WAPFORUM//DTD XHTML Mobile 1.0//EN\"\"http://www.wapforum.org/DTD/xhtml-mobile10.dtd\">";
echo "<html xmlns=\"http://www.w3.org/1999/xhtml\">";

connectdb();
$action = $_GET["action"];
$id = $_GET["id"];
$sid = $_SESSION["sid"];
$rid = $_GET["rid"];
$rpw = $_GET["rpw"];
$uid = getuid_sid($sid);
vrh($sid);

$uexist = isuser($uid);

if ((islogged($sid) == false) || !$uexist) {
    echo "<p align=\"center\">";
    echo "You are not logged in<br/>";
    echo "Or Your session has been expired<br/><br/>";
    echo "<a href=\"index.php\">Login</a>";
    echo "</p>";
    exit();
} 

if (isbanned($uid)) {
    echo "<p align=\"center\">";
    echo "<img src=\"images/notok.gif\" alt=\"x\"/><br/>";
    echo "You are <b>Banned</b><br/>";
    $banto = mysql_fetch_array(mysql_query("SELECT timeto FROM fun_penalties WHERE uid='" . $uid . "' AND penalty='1'"));
    $remain = $banto[0] - time();
    $rmsg = gettimemsg($remain);
    echo "Time to finish your penalty: $rmsg<br/><br/>"; 
    // echo "<a href=\"index.php\">Login</a>";
    echo "</p>";
    exit();
} 
$isroom = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_rooms WHERE id='" . $rid . "'"));
if ($isroom[0] == 0) {
    echo "<p align=\"center\">";
    echo "This room doesn't exist anymore<br/>";
    echo ":P see in another room<br/><br/>";
    echo "<a href=\"index.php?chat\">Chatrooms</a>";
    echo "</p>";

    exit();
} 
$passworded = mysql_fetch_array(mysql_query("SELECT pass FROM fun_rooms WHERE id='" . $rid . "'"));
if ($passworded[0] != "") {
    if ($rpw != $passworded[0]) {
        echo "<p align=\"center\">";
        echo "You can't enter this room<br/>";
        echo ":P stay away<br/><br/>";
        echo "<a href=\"index.php?chat\">Chatrooms</a>";
        echo "</p>";

        exit();
    } 
} 
if (!canenter($rid, $sid)) {
    echo "<p align=\"center\">";
    echo "You can't enter this room<br/>";
    echo ":P stay away<br/><br/>";
    echo "<a href=\"index.php?chat\">Chatrooms</a>";
    echo "</p>";

    exit();
} 
addtochat($uid, $rid); 
// This Chat Script is by Ra'ed Far'oun
// raed_mfs@yahoo.com
// want to see main menu...
if ($action == "") {
    // start of main card
    vrhonline($sid, $uid);
    addonline($uid, "Chatrooms", "");

    $message = $_POST["message"];
    $who = $_POST["who"];
    $rinfo = mysql_fetch_array(mysql_query("SELECT censord, freaky FROM fun_rooms WHERE id='" . $rid . "'"));
    if (trim($message) != "") {
        $nosm = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_chat WHERE msgtext='" . $message . "'"));
        if ($nosm[0] == 0) {
            $chatok = mysql_query("INSERT INTO fun_chat SET  chatter='" . $uid . "', who='" . $who . "', timesent='" . time() . "', msgtext='" . $message . "', rid='" . $rid . "';");
            $lstmsg = mysql_query("UPDATE fun_rooms SET lastmsg='" . time() . "' WHERE id='" . $rid . "'");

            $hehe = mysql_fetch_array(mysql_query("SELECT chmsgs FROM fun_users WHERE id='" . $uid . "'"));
            $totl = $hehe[0] + 1;
            $msgst = mysql_query("UPDATE fun_users SET chmsgs='" . $totl . "' WHERE id='" . $uid . "'");
        } 
        $message = "";
    } 

    $chats = mysql_query("SELECT chatter, who, timesent, msgtext, exposed FROM fun_chat WHERE rid='" . $rid . "' ORDER BY timesent DESC, id DESC");
    $counter = 0;

    while ($chat = mysql_fetch_array($chats)) {
        $canc = true;

        if ($counter < 10) {
            if (istrashed($chat[0])) {
                if ($uid != $chat[0]) {
                    $canc = false;
                } 
            } 
            // ////good
            if (isignored($chat[0], $uid)) {
                $canc = false;
            } 
            // ////////good
            if ($chat[0] != $uid) {
                if ($chat[1] != 0) {
                    if ($chat[1] != $uid) {
                        $canc = false;
                    } 
                } 
            } 
            if ($chat[4] == '1' && ismod($uid)) {
                $canc = true;
            } 
            if ($canc) {
                $cmid = mysql_fetch_array(mysql_query("SELECT  chmood FROM fun_users WHERE id='" . $chat[0] . "'"));

                $iml = "";
                if (($cmid[0] != 0)) {
                    $mlnk = mysql_fetch_array(mysql_query("SELECT img, text FROM fun_moods WHERE id='" . $cmid[0] . "'"));
                    $iml = "<img src=\"$mlnk[0]\" alt=\"$mlnk[1]\"/>";
                } 
                $chnick = getnick_uid($chat[0]);
                $optlink = $iml . $chnick;
                if (($chat[1] != 0) && ($chat[0] == $uid)) {
                    // /out
                    $iml = "<img src=\"moods/out.gif\" alt=\"!\"/>";
                    $chnick = getnick_uid($chat[1]);
                    $optlink = $iml . "PM to " . $chnick;
                } 
                if ($chat[1] == $uid) {
                    // /out
                    $iml = "<img src=\"moods/in.gif\" alt=\"!\"/>";
                    $chnick = getnick_uid($chat[0]);
                    $optlink = $iml . "PM by " . $chnick;
                } 
                if ($chat[4] == '1') {
                    // /out
                    $iml = "<img src=\"moods/point.gif\" alt=\"!\"/>";
                    $chnick = getnick_uid($chat[0]);
                    $tonick = getnick_uid($chat[1]);
                    $optlink = "$iml by " . $chnick . " to " . $tonick;
                } 

                $var1 = date("his", $chat[2]);
                $var2 = time ();
                $var21 = date("his", $var2);
                $var3 = $var21 - $var1;
                $var4 = date("s", $var3);
                $remain = time() - $chat[2];
                $ds = gettimemsg($remain);
                $text = parsepm($chat[3], $sid);
                $nos = substr_count($text, "<img src=");
                if (isspam($text)) {
                    $chnick = getnick_uid($chat[0]);
                    echo "<b>Chat system:&#187;<i>*oi! $chnick, no spamming*</i></b><br/>";
                } else if ($nos > 2) {
                    $chnick = getnick_uid($chat[0]);
                    echo "<b>Chat system:&#187;<i>*hey! $chnick, you can only use 2 smilies per msg*</i></b><br/>";
                } else {
                    $sres = substr($chat[3], 0, 3);

                    if ($sres == "/me") {
                        $chco = strlen($chat[3]);
                        $goto = $chco - 3;
                        $rest = substr($chat[3], 3, $goto);
                        $tosay = parsepm($rest, $sid);

                        echo "<b><i>*$chnick $tosay*</i></b><br/>";
                    } else {
                        $tosay = parsepm($chat[3], $sid);

                        if ($rinfo[0] == 1) {
                            $tosay = str_replace("fuck", "*this word rhymes with duck*", $tosay);
                            $tosay = str_replace("shit", "*dont swear*", $tosay);
                            $tosay = str_replace("dick", "*ooo! you dirty person*", $tosay);
                            $tosay = str_replace("pussy", "*angel flaps*", $tosay);
                            $tosay = str_replace("cock", "*daddy stick*", $tosay);
                            $tosay = str_replace("can i be a mod", "*im sniffing staffs ass*", $tosay);
                            $tosay = str_replace("can i be admin", "*im a big ass kisser*", $tosay);

                            $tosay = str_replace("ginger", "*the cute arsonist*", $tosay);
                            $tosay = str_replace("neon", "*the cute but evil princess*", $tosay);
                            $tosay = str_replace("kaas", "*the cheese boy*", $tosay);
                            $tosay = str_replace("slut", "*s+m freak*", $tosay);
                            $tosay = str_replace("kahla", "*lyrical lizard*", $tosay);
                        } 

                        if ($rinfo[1] == 1) {
                            $tosay = htmlspecialchars($chat[3]);
                            $tosay = strrev($tosay);
                        } 
                        $avlink = getavatar($chat[0]);
                        if ($avlink != "") {
                            echo "<div class=\"comment comm_adv\"><table border='0' width='100%' id='download'><tr><td align='left' width='1' height='1'><img src=\"$avlink\" height=\"25\" width=\"25\" alt=\"avatar\"/>";
                        } else {
                            echo "<div class=\"comment comm_adv\"><table border='0' width='100%' id='download'><tr><td align='left' width='1' height='1'><img src=\"../images/nopic.jpg\" height=\"25\" width=\"25\" alt=\"avatar\"/>";
                        } 
                        echo "</td><td><a href=\"index.php?viewuser&amp;who=$chat[0]\">$optlink</a> <br /> $ds </td></tr></table>";
                        echo $tosay . "</div>";
                    } 
                } 

                $counter++;
            } 
        } 
    } 
    echo "<form action=\"chat.php?sid=$sid&amp;rid=$rid&amp;rpw=$rpw\" method=\"post\">";
    echo "<input name=\"message\" type=\"text\" value=\"\" maxlength=\"255\"/>";
    echo "<input type=\"submit\" class=\"button\" value=\"Odgovori\"/>";
    echo "</form>";

    echo "<div class=\"border_top_light\">";
    echo "<div class=\"titlz\"><a href=\"chat.php?time=";
    echo date('dmHis');
    echo "&amp;rid=$rid&amp;rpw=$rpw";
    echo "\">refresh</a></div>";
    echo "<div class=\"titlz\">
        <a href=\"chat.php?say&amp;rid=$rid&amp;rpw=$rpw\">options</a></div>";
    $chatters = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_chonline where rid='" . $rid . "'"));
    echo "<div class=\"titlz\"><a href=\"chat.php?inside&amp;rid=$rid&amp;rpw=$rpw\">U sobi($chatters[0])</a></div>";
    dnoonline($sid, $uid);
} 
// ///////////////////////////////////////////////////SAY
else if (isset($_GET['say'])) {
    addonline($uid, "Writing Chat Message", "");
    echo "<form action=\"chat.php?sid=$sid&amp;rid=$rid&amp;rpw=$rpw\" method=\"post\">";
    echo "<p>Message:<input name=\"message\" type=\"text\" value=\"\" maxlength=\"255\"/><br/>";
    echo "<input type=\"submit\" value=\"Say\"/>";
    echo "</form><br/>";
    echo "<small><a href=\"lists.php?chmood&amp;page=1\">&#187;Chat mood</a></small><br/>";
    echo "<small><a href=\"chat.php?inside&amp;rid=$rid&amp;rpw=$rpw\">&#187;Who's Inside</a></small><br/>";
    echo "<small><a href=\"chat.php?sid=$sid&amp;rid=$rid&amp;rpw=$rpw\">&#171;Chatroom</a></small></p>"; 
    // end
    echo "<p align=\"center\"><a href=\"index.php?chat\"><img src=\"images/chat.gif\" alt=\"*\"/>Chatrooms</a><br/>";
    echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>Home</a></p>";
} 
// //////////////////////////////////////////
// ///////////////////////////////////////////////////SAY2
else if (isset($_GET['say2'])) {
    echo "<p align=\"center\">";
    $unick = getnick_uid($who);
    echo "<b>Private to $unick</b>";
    echo "</p>";

    addonline($uid, "Writing chat message", "");
    echo "<go href=\"chat.php?sid=$sid&amp;rid=$rid&amp;rpw=$rpw\" method=\"post\">";
    echo "<p>Message:<input name=\"message\" type=\"text\" value=\" \" maxlength=\"255\"/><br/>";
    echo "<input type=\"submit\" value=\"Say\"/>";
    echo "<input type=\"hidden\" name=\"who\" value=\"$who\"/>";
    echo "</form>";
    echo "<br/>";
    echo "<small><a href=\"index.php?viewuser&amp;who=$who\">&#187;View $unick's Profile</a></small><br/>";
    echo "<small><a href=\"chat.php?expose&amp;who=$who&amp;rid=$rid&amp;rpw=$rpw\">&#187;Expose $unick</a></small><br/>";

    echo "<small><a href=\"chat.php?inside&amp;rid=$rid&amp;rpw=$rpw\">&#187;Who's Inside</a></small><br/>";
    echo "<small><a href=\"chat.php?sid=$sid&amp;rid=$rid&amp;rpw=$rpw\">&#171;Chatroom</a></small></p>"; 
    // end
    echo "<p align=\"center\"><a href=\"index.php?chat\"><img src=\"images/chat.gif\" alt=\"*\"/>Chatrooms</a><br/>";
    echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>Home</a></p>";
} 
// //////////////////////////////////////////
// ////////////////////////////inside//////////
else if (isset($_GET['inside'])) {
    addonline($uid, "Chat inside list", "");

    echo "<p align=\"center\"><br/>";
    $inside = mysql_query("SELECT DISTINCT * FROM fun_chonline WHERE rid='" . $rid . "' and uid IS NOT NULL");

    while ($ins = mysql_fetch_array($inside)) {
        $unick = getnick_uid($ins[1]);
        $userl = "<small><a href=\"chat.php?say2&amp;who=$ins[1]&amp;rid=$rid&amp;rpw=$rpw\">$unick</a>, </small>";
        echo "$userl";
    } 
    echo "<br/><br/>";
    echo "<a href=\"chat.php?sid=$sid&amp;rid=$rid&amp;rpw=$rpw\">&#171;Chatroom</a><br/>";
    echo "<br/><a href=\"index.php?chat\"><img src=\"images/chat.gif\" alt=\"*\"/>Chatrooms</a><br/>";
    echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>Home</a></p>";
} else if (isset($_GET['expose'])) {
    addonline($uid, "Chat inside list", "");

    echo "<p align=\"center\"><br/>";
    mysql_query("UPDATE fun_chat SET exposed='1' WHERE chatter='" . $who . "' AND who='" . $uid . "'");
    $unick = getnick_uid($who);
    echo "$unick messages to you have been exposed to staff";
    echo "<br/><br/>";
    echo "<a href=\"chat.php?sid=$sid&amp;rid=$rid&amp;rpw=$rpw\">&#171;Chatroom</a><br/>";
    echo "<br/><a href=\"index.php?chat\"><img src=\"images/chat.gif\" alt=\"*\"/>Chatrooms</a><br/>";
    echo "<a href=\"index.php?main\"><img src=\"images/home.gif\" alt=\"*\"/>Home</a></p>";
} 

echo "</body>";
echo "</html>";

?>
