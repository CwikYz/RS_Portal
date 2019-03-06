<?php
if (isadmin(getuid_sid($sid))) {
    echo "<div class=\"section border_top\">";
    echo "<div class=\"section_title\"><div class=\"marker\">ADMINISTRATOR MENI</div></div>";
    echo "<div class=\"adv\"><small>";
    echo "<a href=\"portal.php?addfotkicu\">Dodaj fotkicu na pocetnu</a><br/>";

    echo "<a href=\"index.php?stats\">Statistika Portala</a><br/>";
    $tm24 = time() - (24 * 60 * 60);
    $aut = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_users WHERE lastact>'" . $tm24 . "'"));
    echo "<a href=\"portal.php?main\">Aktivni clanovi danas ($aut[0])</a><br/>";
    echo "<a href=\"admproc.php?notify\">Isprazni obavestenja</a><br/>";
   // echo "<a href=\"mysql/zinidatikazem/index.php\">MySql Konekcija</a><br/>";

    echo "</small></div></div>";
} 

?>
