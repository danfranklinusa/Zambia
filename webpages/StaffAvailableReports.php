<?php
  global $participant,$message_error,$message2,$congoinfo;
  $title="Staff Manage Sessions";
  require_once('db_functions.php');
  require_once('StaffHeader.php');
  require_once('StaffFooter.php');
  require_once('StaffCommonCode.php');
  require_once('RenderSearchSession.php');
  staff_header($title);
?>

<p>
First cut at categorizing reports is underway.   The reports are starting to be grouped based on who is interested in seeing them. The last link shows all the reports. </p>
<hr>
<table>
  <COL><COL>
   <tr><td><A HREF="StaffReportsForProgram.php">Reports for Program</A>
   <tr><td><A HREF="StaffReportsForPublications.php">Reports for Publications</A>
   <tr><td><A HREF="StaffReportsForHotel.php">Reports for Hotel</A>
   <tr><td><A HREF="StaffReportsForTech.php">Reports for Tech</A>
   <tr><td><A HREF="reportindex.php">All Reports</A>
</table>

<p> New spiffy autogenerated reports... 
<p>If the div/area head for each would like any of the reports in 
his/her area tweaked, removed, etc.  email zambia@arisia.org and let us know.
<table>
  <COL><COL>
   <tr><td><A HREF="reportindex.php">All Reports</A>
   <tr><td><A HREF="reportCONFLICT.php">Conflict Reports</A>
   <tr><td><A HREF="reportEVENTS.php">Events Reports</A>
   <tr><td><A HREF="reportFASTTRACK.php">Fast Track Reports</A>
   <tr><td><A HREF="reportGAMING.php">Gaming Reports</A>
   <tr><td><A HREF="reportGOH.php">GoH Reports</A>
   <tr><td><A HREF="reportHOTEL.php">Hotel Reports</A>
   <tr><td><A HREF="reportPROG.php">Programming Reports</A>
   <tr><td><A HREF="reportPUBS.php">Publication Reports</A>
   <tr><td><A HREF="reportREG.php">Registration Reports</A>
   <tr><td><A HREF="reportTECH.php">Tech Reports</A>
</table>
<?php staff_footer(); ?>
