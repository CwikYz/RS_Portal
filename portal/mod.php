<?php
if (ismod(getuid_sid($sid))) {
    echo "<div class=\"section border_top\">";
    echo "<div class=\"section_title\"><div class=\"marker\">MODERATOR MENI</div></div>";
    echo "<div class=\"adv\"><small>";

    $nrpm = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_private WHERE reported='1'"));
    echo "<a href=\"modcp.php?rpm\">Reglacija poruka($nrpm[0])</a><br/>";
    $nrps = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_posts WHERE reported='1'"));
    echo "<a href=\"modcp.php?rps\">Regulacija postova($nrps[0])</a><br/>";
    $nrtp = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_topics WHERE reported='1'"));
    echo "<a href=\"modcp.php?rtp\">Regulacija tema($nrtp[0])</a>";

    echo "</small></div></div>";
} 

?>
