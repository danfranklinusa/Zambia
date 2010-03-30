<?php
    require_once ('db_functions.php');
    require_once('BrainstormCommonCode.php');
    require_once('BrainstormHeader.php');
    $title="Likely to Occur Suggestions";
    $showlinks=$_GET["showlinks"];
    $_SESSION['return_to_page']="ViewPrecis.php?showlinks=$showlinks";
    if ($showlinks=="1") {
            $showlinks=true;
            }
    elseif ($showlinks="0") {
            $showlinks=false;
            }
    if (prepare_db()===false) {
        $message="Error connecting to database.";
        RenderError($title,$message);
        exit ();
        }
   $query = <<<EOD
SELECT sessionid, trackname, null typename, title, 
       CASE
         WHEN HOUR(duration) < 1 THEN concat(date_format(duration,'%i'),'min')
         WHEN MINUTE(duration)=0 THEN concat(date_format(duration,'%k'),'hr')
         ELSE concat(date_format(duration,'%k'),'hr ',date_format(duration,'%i'),'min')
         END
         AS Duration,
       estatten, progguiddesc, persppartinfo
  from Sessions, Tracks, SessionStatuses 
 where Sessions.trackid=Tracks.trackid  
   and SessionStatuses.statusid=Sessions.statusid  
   and SessionStatuses.statusname in ('Vetted','Assigned','Scheduled')
   and Sessions.invitedguest=0
 order by trackname, title
EOD;
    if (($result=mysql_query($query,$link))===false) {
        $message="Error retrieving data from database.";
        RenderError($title,$message);
        exit ();
        }
    brainstorm_header($title);
    echo "<p> These ideas have made the first cut.  We like them and would like to see them happen.   Now to just find all the right people... ";
    echo "<p> If you want to help, email us at "; 
    echo "<a href=\"mailto:".PROGRAM_EMAIL."\">".PROGRAM_EMAIL."</a> </p>\n";
    echo "This list is sorted by Track and then Title." ;
    RenderPrecis($result,$showlinks);
    brainstorm_footer();
    exit();
?> 

