<?php
    require_once('Constants.php');
    require_once('data_functions.php');
    require_once('db_functions.php');
    require_once('render_functions.php');
    require_once('validation_functions.php');
    require_once('php_functions.php');
    require_once('error_functions.php');

    //set_session_timeout();
    session_start();
    if (prepare_db()===false) {
        $message_error="Unable to connect to database.<BR>No further execution possible.";
        RenderError($title,$message_error);
        exit();
        };
    if (isLoggedIn()==false and !isset($logging_in)) {
	    $message="Session expired. Please log in again.";
	    require ('login.php');
	    exit();
    };

    // function to generate a clickable tab.
    // 'text' contains the text that should appear in the tab.
    // 'usable' indicates whether the tab is usable.
    //
    // if the tab is usable, its background and foreground color will
    // be determined by the 'usabletab' class.  when the mouse is over the tab
    // the background and foreground colors of the tab will be determined
    // by the 'mousedovertab' class.
    //
    // if the tab is not usable, the tab will use class 'unusabletab'
    
    Function maketab($text,$usable,$url) {
	if ($usable) {
		echo '<SPAN class="usabletab" onmouseover="mouseovertab(this)" onmouseout="mouseouttab(this)">';
		echo '<IMG class="tabborder" SRC="images/leftCorner.gif" alt="&nbsp;">';
		echo '<A HREF="' . $url . '">' ;// XXX link needs to be quoted
		echo $text;                     // XXX needs to be quoted
		echo '<IMG class="tabborder" SRC="images/rightCorner.gif" alt="&nbsp;">';
		echo '</SPAN>';
	    }
	else {
		echo '<SPAN class="unusabletab">';
		echo '<IMG class="tabborder" SRC="images/leftCorner.gif" alt="&nbsp;">';
		echo $text;                     // XXX needs to be quoted
		echo '<IMG class="tabborder" SRC="images/rightCorner.gif" alt="&nbsp;">';
		echo '</SPAN>';
	    }
    }

/* functions to put the headers in place.  Probably should be generalized more,
 than specifically pre-scripting it, the way we do. */

function posting_header($title) {
  $ConName=CON_NAME; // make it a variable so it can be substituted
  $ConUrl=CON_URL; // make it a variable so it can be substituted
  $HeaderTemplateFile="../Local/HeaderTemplate.html";

  echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/strict.dtd\">\n";
  echo "<html xmlns=\"http://www.w3.org/TR/xhtml1/transitional\">\n";
  echo "<head>\n";
  echo "  <meta http-equiv=\"Content-Type\" content=\"text/html; charset=latin-1\">\n";
  echo "  <title>Zambia -- $ConName -- $title</title>\n";
  if (file_exists($HeaderTemplateFile)) {
    readfile($HeaderTemplateFile);
    } else {
    echo "  <link rel=\"stylesheet\" href=\"Common.css\" type=\"text/css\">\n";
    echo "</head>\n";
    echo "<body>\n";
    echo "<H1 class=\"head\">The information for $ConName</H1>\n";
    echo "<H1 class=\"head\">Return to the <A HREF=\"http://$ConUrl\">$ConName</A> website</H1>\n";
    echo "<hr>\n\n";
    echo "<H2 class=\"head\">$title</H2>\n";
    }
  }

function staff_header($title) {
  require_once ("javascript_functions.php");
  $ConName=CON_NAME; // make it a variable so it can be substituted
  $ConUrl=CON_URL; // make it a variable so it can be substituted

  echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/strict.dtd\">\n";
  echo "<html xmlns=\"http://www.w3.org/TR/xhtml1/transitional\">\n";
  echo "<head>\n";
  echo "  <meta http-equiv=\"Content-Type\" content=\"text/html; charset=latin-1\">\n";
  echo "  <title>Zambia -- $ConName -- $title</title>\n";
  echo "  <link rel=\"stylesheet\" href=\"StaffSection.css\" type=\"text/css\">\n";
  javascript_for_edit_session();
  mousescripts();
  echo "</head>\n";
  echo "<body>\n";
  echo "<H1 class=\"head\">Zambia&ndash;The $ConName Scheduling Tool</H1>\n";
  echo "<H1 class=\"head\">Return to the <A HREF=\"http://$ConUrl\">$ConName</A> website</H1>\n";
  echo "<hr>\n\n";
  if (isset($_SESSION['badgeid'])) {
    echo "  <table class=\"tabhead\">\n    <tr class=\"tabrow\">\n      <td class=\"tabblocks border0020\">\n          ";
    maketab("Staff Overview",1,"StaffPage.php");
    echo "</td>\n      <td class=\"tabblocks border0020\">\n          ";
    maketab("Available Reports",1,"genindex.php");
    echo "</td>\n      <td class=\"tabblocks border0020\">\n          ";
    maketab("Manage Sessions",1,"StaffManageSessions.php");
    echo "</td>\n      <td class=\"tabblocks border0020\">\n          ";
    maketab("Manage Participants &amp; Schedule",1,"StaffManageParticipants.php");
    echo "</td>\n      <td class=\"tabblocks border0020\">\n          ";
    maketab("Printing",1,"PreconPrinting.php");
    echo "</td>\n      <td class=\"tabblocks border0020\">\n          ";
    maketab("TimeCards",1,"VolunteerManage.php");
    echo "</td>\n      <td class=\"tabblocks border0020\">\n          ";
    maketab("Participant View",1,"welcome.php");
    echo "</td>\n      <td class=\"tabblocks border0020\">\n          ";
    maketab("Brainstorm View",may_I('public_login'),"BrainstormWelcome.php");
    echo "</td>\n    </tr>\n  </table>\n";
    echo "<table class=\"header\">\n  <tr>\n    <td style=\"height:5px\">\n      </td>\n    </tr>\n";
    echo "  <tr>\n    <td>\n      <table width=\"100%\">\n";
    echo "        <tr>\n          <td width=\"425\">&nbsp;\n            </td>\n";
    echo "          <td class=\"Welcome\">Welcome ";
    echo $_SESSION['badgename'];
    echo "            </td>\n";
    echo "          <td><A class=\"logout\" HREF=\"logout.php\">&nbsp;Logout&nbsp;</A>\n            </td>\n";
    echo "          <td width=\"25\">&nbsp;\n            </td>\n          </tr>\n        </table>\n";
    echo "      </td>\n    </tr>\n";
    }
  echo "  </table>\n\n<H2 class=\"head\">$title</H2>\n";
  //  echo "Permissions: ".print_r($_SESSION['permission_set'])."\n";
  }

function participant_header($title) {
  require_once ("javascript_functions.php");
  global $badgeid;
  $ConName=CON_NAME; // make it a variable so it can be substituted
  $ConUrl=CON_URL; // make it a variable so it can be substituted

  echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/strict.dtd\">\n";
  echo "<html xmlns=\"http://www.w3.org/TR/xhtml1/transitional\">\n";
  echo "<head>\n";
  echo "  <meta http-equiv=\"Content-Type\" content=\"text/html; charset=latin-1\">\n";
  echo "  <title>Zambia -- $ConName -- $title</title>\n";
  echo "  <link rel=\"stylesheet\" href=\"ParticipantSection.css\" type=\"text/css\">\n";
  mousescripts();
  echo "</head>\n";
  echo "<body>\n";
  echo "<H1 class=\"head\">Zambia&ndash;The $ConName Scheduling Tool</H1>\n";
  echo "<H1 class=\"head\">Return to the <A HREF=\"http://$ConUrl\">$ConName</A> website</H1>\n";
  echo "<hr>\n\n";
  if (isset($_SESSION['badgeid'])) {
    echo "<table class=\"tabhead\">\n";
    echo "  <col width=10%><col width=10%><col width=10%><col width=10%><col width=10%>\n";
    echo "  <col width=10%><col width=10%><col width=10%><col width=10%><col width=10%>\n";
    echo "  <tr class=\"tabrow\">\n    <td class=\"tabblocks border0020\" colspan=2>\n       ";
    maketab("Welcome", 1, "welcome.php");
    echo "</td>\n    <td class=\"tabblocks border0020\" colspan=2>\n       ";
    maketab("My Availability",may_I('my_availability'),"my_sched_constr.php");
    echo "</td>\n    <td class=\"tabblocks border0020\" colspan=2>\n       ";
    maketab("My Panel Interests",may_I('my_panel_interests'),"PartPanelInterests.php");
    echo "</td>\n    <td class=\"tabblocks border0020\" colspan=2>\n       ";
    maketab("My General Interests",may_I('my_gen_int_write'),"my_interests.php");
    echo "</td>\n    <td class=\"tabblocks border0020\" colspan=2>\n       ";
    if (may_I('Staff')) { 
      maketab("Staff View",may_I('Staff'),"StaffPage.php"); 
      }
    echo "</td>\n  </tr>\n  <tr class=\"tabrows\">\n    <td class=\"tabblocks border0020 smallspacer\">&nbsp;";
    echo "</td>\n    <td class=\"tabblocks border0020\" colspan=2>\n       ";
    echo "<!-- XXX this should have a may_I -->\n       ";
    maketab("My Profile",1,"my_contact.php");
    echo "</td>\n    <td class=\"tabblocks border0020\" colspan=2>\n       ";
    maketab("Search Panels",may_I('search_panels'),"my_sessions1.php");
    echo "</td>\n    <td class=\"tabblocks border0020\" colspan=2>\n       ";
    maketab("My Schedule",may_I('my_schedule'),"MySchedule.php");
    echo "</td>\n    <td class=\"tabblocks border0020\" colspan=2>\n       ";
    maketab("Suggest a Session",may_I('BrainstormSubmit'),"BrainstormWelcome.php");
    echo "</td>\n    <td class=\"tabblocks border0020 smallspacer\">&nbsp;";
    echo "</td>\n  </tr>\n</table>\n";
    echo "<table class=\"header\">\n  <tr>\n    <td style=\"height:5px\"></td>\n  </tr>\n";
    echo "  <tr>\n    <td>\n      <table width=\"100%\">\n";
    echo "        <tr>\n          <td width=\"425\">&nbsp;</td>\n";
    echo "          <td class=\"Welcome\">Welcome ";
    echo $_SESSION['badgename'];
    echo "            </td>\n";
    echo "          <td><A class=\"logout\" HREF=\"logout.php\">&nbsp;Logout&nbsp;</A></td>\n";
    echo "          <td width=\"25\">&nbsp;</td>\n        </tr>\n      </table>\n";
    echo "    </td>\n  </tr>\n";
    }
  echo "</table>\n\n<H2 class=\"head\">$title</H2>\n";
  }

function brainstorm_header($title) {
  require_once ("javascript_functions.php");
  $ConName=CON_NAME; // make it a variable so it can be substituted
  $ConUrl=CON_URL; // make it a variable so it can be substituted

  echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/strict.dtd\">\n";
  echo "<html xmlns=\"http://www.w3.org/TR/xhtml1/transitional\">\n";
  echo "<head>\n";
  echo "  <meta http-equiv=\"Content-Type\" content=\"text/html; charset=latin-1\">\n";
  echo "  <title>Zambia -- $ConName -- $title</title>\n";
  echo "  <link rel=\"stylesheet\" href=\"BrainstormSection.css\" type=\"text/css\">\n";
  echo "  <meta name=\"keywords\" content=\"Questionnaire\">\n";
  javascript_for_edit_session();
  javascript_pretty_buttons();
  mousescripts();
  echo "</head>\n";
  echo "<body leftmargin=\"0\" topmargin=\"0\" marginheight=\"0\" marginwidth=\"0\">\n";
  echo "<H1 class=\"head\">Zambia&ndash;The $ConName Scheduling Tool</H1>\n";
  echo "<H1 class=\"head\">Return to the <A HREF=\"http://$ConUrl\">$ConName</A> website</H1>\n";
  echo "<hr>\n\n";
  if (isset($_SESSION['badgeid'])) {
    echo "<table class=\"tabhead\">\n";
    echo "  <col width=8%><col width=8%><col width=8%><col width=8%><col width=8%>\n";
    echo "  <col width=8%><col width=10%><col width=10%><col width=8%><col width=8%>\n";
    echo "  <col width=8%><col width=8%>\n";
    echo "  <tr class=\"tabrow\">\n    <td class=\"tabblocks border0020\" colspan=2>\n       ";
    maketab("Welcome",1,"BrainstormWelcome.php");
    echo "</td>\n    <td class=\"tabblocks border0020\" colspan=2>\n       ";
    maketab("Suggest a Session",may_I('BrainstormSubmit'),"BrainstormCreateSession.php");
    echo "</td>\n    <td class=\"tabblocks border0020\" colspan=2>\n       ";
    maketab("Search Sessions",1,"BrainstormSearchSession.php");
    echo "</td>\n    <td class=\"tabblocks border0020\" colspan=2>\n       ";
    maketab("Suggest a Presenter",may_I('BrainstormSubmit'),"BrainstormSuggestPresenter.php");
    echo "</td>\n    <td class=\"tabblocks border0020\" colspan=2>\n       ";
    if(may_I('Participant')) { 
      maketab("Participants View",may_I('Participant'),"welcome.php"); 
      }
    echo "</td>\n    <td class=\"tabblocks border0020\" colspan=2>\n       ";
    if(may_I('Staff')) { 
      maketab("Staff View",may_I('Staff'),"StaffPage.php");
      }
    echo"  </tr>\n  <tr class=\"tabrows\">\n    <td class=\"tabblocks border0020\" colspan=12>\n         View sessions proposed to date:</td>\n  </tr>";
    echo "</td>\n  </tr>\n  <tr class=\"tabrows\">\n    <td class=\"tabblocks border0020\" colspan=2>\n       ";
    maketab("All Proposals",1,"BrainstormReportAll.php");
    echo "</td>\n    <td class=\"tabblocks border0020\" colspan=2>\n       ";
    maketab("New (Unseen)",1,"BrainstormReportUnseen.php");
    echo "</td>\n    <td class=\"tabblocks border0020\" colspan=2>\n       ";
    maketab("Reviewed",1,"BrainstormReportReviewed.php");
    echo "</td>\n    <td class=\"tabblocks border0020\" colspan=2>\n       ";
    maketab("Likely to Occur",1,"BrainstormReportLikely.php");
    echo "</td>\n    <td class=\"tabblocks border0020\" colspan=4>\n       ";
    maketab("Scheduled",1,"BrainstormReportScheduled.php");
    echo "</td>\n  </tr>\n</table>\n";
    echo "<table class=\"header\">\n  <tr>\n    <td style=\"height:5px\"></td>\n  </tr>\n";
    echo "  <tr>\n    <td>\n      <table width=\"100%\">\n";
    echo "        <tr>\n          <td width=\"425\">&nbsp;</td>\n";
    echo "          <td class=\"Welcome\">Welcome ";
    echo $_SESSION['badgename'];
    echo "            </td>\n";
    echo "          <td><A class=\"logout\" HREF=\"logout.php\">&nbsp;Logout&nbsp;</A></td>\n";
    echo "          <td width=\"25\">&nbsp;</td>\n        </tr>\n      </table>\n";
    echo "    </td>\n  </tr>\n";
    }
  echo "</table>\n\n<H2 class=\"head\">$title</H2>\n";
  }

function posting_footer() {
  $ProgramEmail=PROGRAM_EMAIL; // make it a variable so it can be substituted
  $FooterTemplateFile="../Local/FooterTemplate.html";

  if (file_exists($FooterTemplateFile)) {
    readfile($FooterTemplateFile);
    } else {
    echo "<hr>\n<P>If you have questions or wish to communicate an idea, please contact ";
    echo "<A HREF=\"mailto:$ProgramEmail\">$ProgramEmail</A>.\n</P>";
    }
  include ('google_analytics.php');
  echo "\n\n</body>\n</html>\n";
  }

function staff_footer() {
  $ProgramEmail=PROGRAM_EMAIL; // make it a variable so it can be substituted

  echo "<hr>\n<P>If you would like assistance using this tool or you would like to communicate an";
  echo " idea that you cannot fit into this form, please contact ";
  echo "<A HREF=\"mailto:$ProgramEmail\">$ProgramEmail</A>.\n</P>";
  include ('google_analytics.php');
  echo "\n\n</body>\n</html>\n";
  }

function participant_footer() {
  $ProgramEmail=PROGRAM_EMAIL; // make it a variable so it can be substituted

  echo "<hr>\n<P>If you need help or to tell us something that doesn't fit here, please email ";
  echo "<A HREF=\"mailto:$ProgramEmail\">$ProgramEmail</A>.\n</P>"; 
  include('google_analytics.php');
  echo "\n\n</body>\n</html>\n";
  }

function brainstorm_footer() {
  $ProgramEmail=PROGRAM_EMAIL; // make it a variable so it can be substituted
  $BrainstormEmail=BRAINSTORM_EMAIL;

  echo "<hr>\n<P>If you would like assistance using this tool, please contact ";
  echo "<A HREF=\"mailto:$ProgramEmail\">$ProgramEmail</A>.  ";
  echo "If you would like to communicate an idea that you cannot fit into this form, please contact ";
  echo "<A HREF=\"mailto:$BrainstormEmail\">$BrainstormEmail</A>.</P>";
  include('google_analytics.php');
  echo "\n\n</body>\n</html>\n";
  }

/* Top of page reporting, simplified by the foo_header functions
 for HTML pages.  It takes the title, description and any
 additional information, and puts it all in the right place
 depending on the SESSION variable.*/
function topofpagereport($title,$description,$info) {
  if ($_SESSION['role'] == "Brainstorm") {
    brainstorm_header($title);
  }
  elseif ($_SESSION['role'] == "Participant") {
    participant_header($title);
  }
  elseif ($_SESSION['role'] == "Staff") {
    staff_header($title);
  }
  elseif ($_SESSION['role'] == "Posting") {
    posting_header($title);
  }
  date_default_timezone_set('US/Eastern');
  echo "<P align=center> Generated: ".date("D M j G:i:s T Y")."</P>\n";
  echo $description;
  echo $info;
  
}

/* Top of page reporting, for CSV pages.  It takes only the filename
 as an input, and spits out the CSV headers. */
function topofpagecsv($filename) {
  header("Expires: 0");
  header("Cache-control: private");
  header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
  header("Content-Description: File Transfer");
  header("Content-Type: text/csv");
  header("Content-disposition: attachment; filename=$filename");
}

/* Footer choice, for html pages.  Select the correct footer,
 dependant on role.  This could probably just have the above footer
 functions, rolled into this, for simplicity sake. */
function correct_footer() {
  if ($_SESSION['role'] == "Brainstorm") {
    brainstorm_footer();
  }
  elseif ($_SESSION['role'] == "Participant") {
    participant_footer();
  }
  elseif ($_SESSION['role'] == "Staff") {
    staff_footer();
  }
  elseif ($_SESSION['role'] == "Posting") {
    posting_footer();
  }
}

/* Produce the HTML body version of the information gathered in tables.
 It takes 4 inputs, the number of rows, the header array, the elements
 that go into the table, and if this table is the last thing on a page.
 the switch for the close on how it is called doesn't quite work yet,
 and might want to be simplified out, depending on the calling page to
 do the right thing, dropping it to 3 variables. */
function renderhtmlreport($startrows,$endrows,$header_array,$element_array) {
  $headers="";
  foreach ($header_array as $header_name) {
    $headers.="<TH>";
    $headers.=$header_name;
    $headers.="</TH>\n";
  }
  $htmlstring ="<TABLE BORDER=1>";
  $htmlstring.="<TR>" . $headers . "</TR>";
  for ($i=$startrows; $i<=$endrows; $i++) {
    $htmlstring.="<TR>";
    foreach ($header_array as $header_name) {
      $htmlstring.="<TD>";
      $htmlstring.=$element_array[$i][$header_name];
      $htmlstring.="</TD>\n";
    }
    $htmlstring.="</TR>\n";
  }
  $htmlstring.="</TABLE>";
  return($htmlstring);
}

/* Produce the CSV body version of the information gathered in tables.
 It takes in three variables, the number of rows, the header array,
 and the elements that go in the table.  It then strips out all of the
 unwanted characters (html tags, extraneous returns, and other bits)
 and outputs the comma seperated information.*/
function rendercsvreport($startrows,$endrows,$header_array,$element_array) {
  $headers="";
  $spacestr=array('\\n','\n','\\r','\r','&nbsp;');
  $newstr=array(' ',' ',' ',' ',' ');
  foreach ($header_array as $header_name) {
    $headers.="\"";
    $headers.=strip_tags(trim(str_replace($spacestr,$newstr,$header_name)));
    $headers.="\",";
  }
  $headers = substr($headers, 0, -1);
  $csvstring ="$headers\n";
  for ($i=$startrows; $i<=$endrows; $i++) {
    $rowinfo="";
    foreach ($header_array as $header_name) {
      $rowinfo.="\"";
      $rowinfo.=strip_tags(trim(str_replace($spacestr,$newstr,$element_array[$i][$header_name])));
      $rowinfo.="\",";
    }
    $rowinfo=substr($rowinfo, 0, -1);
    $csvstring.="$rowinfo\n";
  }
  return($csvstring);
}

/* This function presumes multiple calls on the same array informaition.
 It takes in 4 elements, the start and end row for a table, of the series
 of tables, the headers which go in every table, and the full array, from
 which the subset is used.  It then prints them nicely. */
function rendergridreport($startrows,$endrows,$header_array,$element_array) {
  $headers="";
  foreach ($header_array as $header_name) {
    $headers.="<TH class=\"border2222\">";
    $headers.=$header_name;
    $headers.="</TH>\n";
  }
  $gridstring="<P><TABLE cellspacing=0 border=1 class=\"border1111\">";
  $gridstring.="<TR>" . $headers . "</TR>";
  for ($i=$startrows; $i<=$endrows; $i++) {
    $gridstring.="<TR>";
    foreach ($header_array as $header_name) {
      $gridstring.=$element_array[$i][$header_name];
    }
    $gridstring.="</TR>\n";
  }
  $gridstring.="</TABLE></P>";
  return($gridstring);
}


/* Pull the information from the databas for a report.  This should be
 checked with, and possibly unified with other functions in db_functions
 file.  It takes the query and link to do the pull, title and description
 in case there is an error, or just no information, and a reportid, so
 the report can be edited if there is a query error in the report. */
function queryreport($query,$link,$title,$description,$reportid) {
  mysql_query("SET group_concat_max_len = 9216;",$link);
  if (($result=mysql_query($query,$link))===false) {
    $message="<P>Error retrieving data from database.</P>\n<P>";
    if ($reportid !=0) {
      $message.="Edit Report <A HREF=EditReport.php?selreport=$reportid>$reportid</A></P>\n<P>";
    }
    $message.=$query;
    RenderError($title,$message);
    exit ();
  }
  if (0==($rows=mysql_num_rows($result))) {
    $message="$description\n<P>This report retrieved no results matching the criteria.</P>\n";
    RenderError($title,$message);
    exit();
  }
  for ($i=1; $i<=$rows; $i++) {
    $element_array[$i]=mysql_fetch_assoc($result);
  }
  $header_array=array_keys($element_array[1]);
  return array ($rows,$header_array,$element_array);
}

/* Show a list of participants to select from, generated from all participants.
 Each list is ordered by the sorting key, for html-based and visual-based
 searching. */
function select_participant ($selpartid, $returnto) {
  global $link;

  // lastname, firstname (badgename/pubsname) - partid
  $query0="SELECT P.badgeid, concat(CD.lastname,', ',CD.firstname,' (',CD.badgename,'/',P.pubsname,') - ',P.badgeid) AS pname";
  $query0.=" FROM Participants P, CongoDump CD WHERE P.badgeid = CD.badgeid ORDER BY CD.lastname";

  // firstname lastname (badgename/pubsname) - partid
  $query1="SELECT P.badgeid, concat(CD.firstname,' ',CD.lastname,' (',CD.badgename,'/',P.pubsname,') - ',P.badgeid) AS pname";
  $query1.=" FROM Participants P, CongoDump CD WHERE P.badgeid = CD.badgeid ORDER BY CD.lastname";

  // pubsname/badgename (lastname, firstname) - partid
  $query2="SELECT P.badgeid, concat(P.pubsname,'/',CD.badgename,' (',CD.lastname,', ',CD.firstname,') - ',P.badgeid) AS pname";
  $query2.=" FROM Participants P, CongoDump CD WHERE P.badgeid = CD.badgeid ORDER BY CD.lastname";

  // Now give the choices
  echo "<FORM name=\"selpartform\" method=POST action=\"".$returnto."\">\n";
  echo "<DIV><LABEL for=\"partidl\">Select Participant (Lastname)</LABEL>\n";
  echo "<SELECT name=\"partidl\">\n";
  populate_select_from_query($query0, $selpartid, "Select Participant (Lastname)", true);
  echo "</SELECT></DIV>\n";
  echo "<DIV><LABEL for=\"partidf\">Select Participant (Firstname)</LABEL>\n";
  echo "<SELECT name=\"partidf\">\n";
  populate_select_from_query($query1, $selpartid, "Select Participant (Firstname)", true);
  echo "</SELECT></DIV>\n";
  echo "<DIV><LABEL for=\"partidp\">Select Participant (Pubsname) </LABEL>\n";
  echo "<SELECT name=\"partidp\">\n";
  populate_select_from_query($query2, $selpartid, "Select Participant (Pubsname)", true);
  echo "</SELECT></DIV>\n";
  echo "<P>&nbsp;\n";
  echo "<DIV class=\"SubmitDiv\"><BUTTON type=\"submit\" name=\"submit\" class=\"SubmitButton\">Submit</BUTTON></DIV>\n";
  echo "</FORM>\n";
}

/* Generic insert takes five variables: link, title, Table, array of elements, array of values */
function submit_table_element ($link, $title, $table, $element_array, $value_array) {
  foreach ($element_array as $element) {$element_string.=mysql_real_escape_string(stripslashes($element)).",";}
  foreach ($value_array as $value) {$value_string.="'".mysql_real_escape_string(stripslashes($value))."',";}
  $element_string=substr($element_string,0,-1);
  $value_string=substr($value_string,0,-1);
  $query= "INSERT INTO $table ($element_string) VALUES ($value_string)";
  if (!mysql_query($query,$link)) {
    $message_error=$query."<BR>Error updating $table.  Database not updated.";
    RenderError($title,$message_error);
    exit;
  }
  $message="Database updated successfully.<BR>";
  echo "<P class=\"regmsg\">".$message."\n";
}

/* Generic update takes six variables: link, title, Table, paired array of updates,
 which field to match on, and value of the match. */
function update_table_element ($link, $title, $table, $pairedvalue_array, $match_field, $match_value) {
  foreach ($pairedvalue_array as $pairedvalue) {$pairedvalue_string.=$pairedvalue.",";}
  $pairedvalue_string=substr($pairedvalue_string,0,-1);
  $query="UPDATE $table set $pairedvalue_string where $match_field = '$match_value'";
  if (!mysql_query($query,$link)) {
    $message_error=$query."<BR>Error updating $table.  Database not updated.";
    RenderError($title,$message_error);
    exit;
  }
  $message="Database updated successfully.<BR>";
  echo "<P class=\"regmsg\">".$message."\n";
}

/* unfrom/refrom fix so that queries can be set as values in the various database entries */
function unfrom ($transstring) {
   $badfrom = array("FROM", "From", "from");
   $goodfrom = array("UMFRAY", "Umfray", "umfray");
   return str_replace ($badfrom, $goodfrom, $transstring);
   }

function refrom ($transstring) {
   $badfrom = array("FROM", "From", "from");
   $goodfrom = array("UMFRAY", "Umfray", "umfray");
   return str_replace ($goodfrom, $badfrom, $transstring);
   }

/* Used to add a note on a participant as part of flow, and allowing for participant change. */
function submit_participant_note ($note, $partid) {
  global $link;
  $query = "INSERT INTO NotesOnParticipants (badgeid,rbadgeid,note) VALUES ('";
  $query.=$partid."','";
  $query.=$_SESSION['badgeid']."','";
  $query.=mysql_real_escape_string($note)."')";
  if (!mysql_query($query,$link)) {
    $message=$query."<BR>Error updating database with note.  Database not updated.";
    echo "<P class=\"errmsg\">".$message."\n";
    return;
  }
  $message="Database updated successfully with note.<BR>";
  echo "<P class=\"regmsg\">".$message."\n";
}

/* Pull the notes for a participant, in reverse order. */
// I'm no longer sure why the below is here ...
//"SELECT PR.pubsname, PB.pubsname, N.timestamp, N.note FROM NotesOnParticipants N, Participants PR, Participants PB WHERE N.rbadgeid=PR.badgeid AND N.badgeid=PB.badgeid;
function show_participant_notes ($partid) {
  global $link;
  $query = <<<EOD
SELECT
    N.timestamp as'When',
    P.pubsname as 'Who',
    N.note as 'What Was Done'
  FROM
      NotesOnParticipants N,
      Participants P
  WHERE
    N.rbadgeid=P.badgeid AND
    N.badgeid=$partid
  ORDER BY
    timestamp DESC
EOD;
  list($rows,$header_array,$notes_array)=queryreport($query,$link,"Notes on Participant","","");
  echo renderhtmlreport(1,$rows,$header_array,$notes_array);
  correct_footer();
}

/* create_participant and edit_participant functions.  Need more doc. */
function create_participant ($participant_arr,$permrole_arr) {
  global $link;
  $error_status=false;
  // Commented out, becuase some people have short names
  /* if ((strlen($participant_arr['firstname'])+strlen($participant_arr['lastname']) < 5) OR
      (strlen($participant_arr['badgename']) < 5) OR
      (strlen($participant_arr['pubsname']) < 5)) {
    $message_error="All name fields are required and minimum length is 5 characters.  <BR>\n";
    $error_status=true;
  } */
  if (!is_email($participant_arr['email'])) {
    $message_error.="Email address is not valid.  <BR>\n";
    $error_status=true;
  }
  if ($error_status) {
    $message_error.="Database not updated.  <BR>\n";
    exit();
  }
  $query = "SELECT MAX(badgeid) FROM Participants WHERE badgeid>='1' AND badgeid<68";
  $result=mysql_query($query,$link);
  if (!$result) {
    $message_error="Unrecoverable error updating database.  Database not updated.<BR>\n";
    $message_error.=$query;
    RenderError($title,$message_error);
    exit();
  }
  if (mysql_num_rows($result)!=1) {
    $message_error="Database query returned unexpected number of rows(1 expected).  Database not updated.<BR>\n";
    $message_error.=$query;
    RenderError($title,$message_error);
    exit();
  }
  $maxbadgeid=mysql_result($result,0);
  //error_log("Zambia: SubmitEditCreateParticipant.php: maxbadgeid: $maxbadgeid");
  sscanf($maxbadgeid,"%d",$x);
  $newbadgeid=sprintf("%d",$x+1); // convert to num; add 1; convert back to string
  $query = "INSERT INTO Participants (badgeid, password, bestway, interested, bio, progbio, altcontact, prognotes, pubsname) VALUES (";
  $query.= "'".mysql_real_escape_string($newbadgeid)."',";
  $query.= "'".mysql_real_escape_string($participant_arr['password'])."',";
  $query.= "'".mysql_real_escape_string($participant_arr['bestway'])."',";
  $query.= (($participant_arr['interested']=='')?"NULL":$participant_arr['interested']).",";
  $query.= "'".mysql_real_escape_string($participant_arr['bio'])."',";
  $query.= "'".mysql_real_escape_string($participant_arr['progbio'])."',";
  $query.= "'".mysql_real_escape_string($participant_arr['altcontact'])."',";
  $query.= "'".mysql_real_escape_string($participant_arr['prognotes'])."',";
  $query.= "'".mysql_real_escape_string($participant_arr['pubsname'])."');";
  $query2 = "INSERT INTO CongoDump (badgeid, firstname, lastname, badgename, phone, email, postaddress1, postaddress2, postcity, poststate, postzip, regtype) VALUES (";
  $query2.= "'".mysql_real_escape_string($newbadgeid)."',";
  $query2.= "'".mysql_real_escape_string($participant_arr['firstname'])."',";
  $query2.= "'".mysql_real_escape_string($participant_arr['lastname'])."',";
  $query2.= "'".mysql_real_escape_string($participant_arr['badgename'])."',";
  $query2.= "'".mysql_real_escape_string($participant_arr['phone'])."',";
  $query2.= "'".mysql_real_escape_string($participant_arr['email'])."',";
  $query2.= "'".mysql_real_escape_string($participant_arr['postaddress1'])."',";
  $query2.= "'".mysql_real_escape_string($participant_arr['postaddress2'])."',";
  $query2.= "'".mysql_real_escape_string($participant_arr['postcity'])."',";
  $query2.= "'".mysql_real_escape_string($participant_arr['poststate'])."',";
  $query2.= "'".mysql_real_escape_string($participant_arr['postzip'])."',";
  $query2.= "'".mysql_real_escape_string($participant_arr['regtype'])."');";
  $query3 = "INSERT INTO UserHasPermissionRole (badgeid, permroleid) VALUES ";
  for ($i=2; $i<=count($permrole_arr); $i++) {
    $perm="permroleid".$i;
    if ($participant_arr[$perm]=="checked") {
      $query3.="('".$newbadgeid."','".$i."'),";
    }
  }
  $query3=rtrim($query3,',');
  $query4 = "INSERT INTO NotesOnParticipants (badgeid,rbadgeid,note) VALUES ('";
  $query4.=$newbadgeid."','";
  $query4.=$_SESSION['badgeid']."','";
  $query4.=mysql_real_escape_string($participant_arr['note'])."')";
  if (!mysql_query($query,$link)) {
    $message_error=$query."<BR>Error updating CongoDump database.  Database not updated.";
    RenderError($title,$message_error);
    exit();
  }
  if (!mysql_query($query2,$link)) {
    $message_error=$query2."<BR>Error updating Participant database.  Database not updated.";
    RenderError($title,$message_error);
    exit();
  }
  if (!mysql_query($query3,$link)) {
    $message_error=$query3."<BR>Error updating UserHasPermissionRole database.  Database not updated.";
    RenderError($title,$message_error);
    exit();
  }
  if (!mysql_query($query4,$link)) {
    $message_error=$query4."<BR>Error updating NotesOnParticipants database.  Database not updated.";
    RenderError($title,$message_error);
    exit();
  }
  $message="Database updated successfully with ".$participant_arr["badgename"].".<BR>";
  echo "<P class=\"regmsg\">".$message."\n";
}

function edit_participant ($participant_arr,$permrole_arr) {
  global $link;
  $error_status=false;

  // Commented out, becuase some people have short names
  /* if ((strlen($participant_arr['firstname'])+strlen($participant_arr['lastname']) < 5) OR
      (strlen($participant_arr['badgename']) < 5) OR
      (strlen($participant_arr['pubsname']) < 5)) {
    $message_error="All name fields are required and minimum length is 5 characters.  <BR>\n";
    $error_status=true;
  } */
  if (!is_email($participant_arr['email'])) {
    $message="Email address: ".$participant_arr['email']." is not valid.  <BR>\n";
    echo "<P class=\"errmsg\">".$message."\n";
    return;
  }
  $query = "update Participants set ";
  $query.= "bestway=\"".mysql_real_escape_string($participant_arr['bestway'])."\",";
  $query.= "interested=\"".(($participant_arr['interested']=='')?"NULL":$participant_arr['interested'])."\",";
  $query.= "bio=\"".mysql_real_escape_string($participant_arr['bio'])."\",";
  $query.= "progbio=\"".mysql_real_escape_string($participant_arr['progbio'])."\",";
  $query.= "altcontact=\"".mysql_real_escape_string($participant_arr['altcontact'])."\",";
  $query.= "prognotes=\"".mysql_real_escape_string($participant_arr['prognotes'])."\",";
  $query.= "pubsname=\"".mysql_real_escape_string($participant_arr['pubsname'])."\"";
  $query.= " WHERE badgeid=\"".$participant_arr['partid']."\";";
  $query2 = "update CongoDump set ";
  $query2.= "firstname=\"".mysql_real_escape_string($participant_arr['firstname'])."\",";
  $query2.= "lastname=\"".mysql_real_escape_string($participant_arr['lastname'])."\",";
  $query2.= "badgename=\"".mysql_real_escape_string($participant_arr['badgename'])."\",";
  $query2.= "phone=\"".mysql_real_escape_string($participant_arr['phone'])."\",";
  $query2.= "email=\"".mysql_real_escape_string($participant_arr['email'])."\",";
  $query2.= "postaddress1=\"".mysql_real_escape_string($participant_arr['postaddress1'])."\",";
  $query2.= "postaddress2=\"".mysql_real_escape_string($participant_arr['postaddress2'])."\",";
  $query2.= "postcity=\"".mysql_real_escape_string($participant_arr['postcity'])."\",";
  $query2.= "poststate=\"".mysql_real_escape_string($participant_arr['poststate'])."\",";
  $query2.= "postzip=\"".mysql_real_escape_string($participant_arr['postzip'])."\",";
  $query2.= "regtype=\"".mysql_real_escape_string($participant_arr['regtype'])."\"";
  $query2.= " WHERE badgeid=\"".$participant_arr['partid']."\";";
  $query3 = "INSERT INTO NotesOnParticipants (badgeid,rbadgeid,note) VALUES ('";
  $query3.=$participant_arr['partid']."','";
  $query3.=$_SESSION['badgeid']."','";
  $query3.=mysql_real_escape_string($participant_arr['note'])."')";
  for ($i=2; $i<=count($permrole_arr); $i++) {
    $perm="permroleid".$i;
    $wperm="waspermroleid".$i;
    if (isset ($participant_arr[$perm])) {
      if ($participant_arr[$wperm] == "not") {
	$queryl ="INSERT INTO UserHasPermissionRole (badgeid, permroleid) VALUES ";
        $queryl.="('".$participant_arr['partid']."','".$i."');";
        if (!mysql_query($queryl,$link)) {
	  $message=$queryl."<BR>Error updating UserHasPermissionRole database.  Database not updated.";
	  echo "<P class=\"errmsg\">".$message."\n";
	  return;
	}
      }
    } elseif ($participant_arr[$wperm] == "indeed") {
      $queryl ="DELETE FROM UserHasPermissionRole where ";
      $queryl.="badgeid=".$participant_arr['partid']." AND permroleid=".$i.";";
      if (!mysql_query($queryl,$link)) {
	$message=$queryl."<BR>Error updating UserHasPermissionRole database.  Database not updated.";
	echo "<P class=\"errmsg\">".$message."\n";
	return;
      }
    }
  }
  if (!mysql_query($query,$link)) {
    $message=$query."<BR>Error updating Participants database.  Database not updated.";
    echo "<P class=\"errmsg\">".$message."\n";
    return;
  }
  if (!mysql_query($query2,$link)) {
    $message=$query2."<BR>Error updating CongoDump database.  Database not updated.";
    echo "<P class=\"errmsg\">".$message."\n";
    return;
  }
  if (!mysql_query($query3,$link)) {
    $message=$query3."<BR>Error updating NotesOnParticipants database.  Database not updated.";
    echo "<P class=\"errmsg\">".$message."\n";
    return;
  }
  $message="Database updated successfully.<BR>";
  echo "<P class=\"regmsg\">".$message."\n";
}    

function get_emailto_from_permrole($permrolename,$link,$title,$description) {
  /* Takes the permrolename and link, (and title and description, in
     case of failure) and returns a valid email address */

  // Get the email-to from the permrole
  $query=<<<EOD
SELECT
    emailtoquery
  FROM
    EmailTo
  WHERE
    emailtodescription='$permrolename'
EOD;

  // presume there is only one match and return that, with the error report, if necessary
  list($rows,$header_array,$emailtoquery_array)=queryreport($query,$link,$title,$description,0);
  list($rows,$header_array,$emailto_array)=queryreport($emailtoquery_array[1]['emailtoquery'],$link,$title,$description,0);
  return($emailto_array[1]['email']);
}

function send_fixed_email_info($emailto,$subject,$body,$link,$title,$description) {
  /* Takes the emailto (which might be a permrolename), subject, body,
     link (and title, and description in case of failure), resolve the
     emailto, if it is a permrolename, use the default from, and no
     cc, and add an entry to the email queue. */ 

  // Check to see if it is just an email address, or the permrole to be expanded
  if (!strpos($emailto,"@")) {
    $newemailaddress=get_emailto_from_permrole($emailto,$link,$title,$description);
    $newemailto="$emailto <$newemailaddress>";
    $emailto=$newemailto;
  }

  // Insert into queue, the ADMIN_EMAIL is the from address, no cc address, status 1 to send
  $element_array=array('emailto','emailfrom','emailcc','emailsubject','body','status');
  $value_array=array($emailto, ADMIN_EMAIL, '',
		     mysql_real_escape_string(stripslashes(htmlspecialchars_decode($subject))),
		     mysql_real_escape_string(stripslashes(htmlspecialchars_decode($body))),
		     1);
  submit_table_element($link, $title, "EmailQueue", $element_array, $value_array);
}

/* Three flow report functions.  They are remove, add, and delta rank.
 Need more header doc */
function remove_flow_report ($flowid,$table,$title,$description) {
  global $link;

  ## Establish the table name
  $tablename=$table."Flow";

  ## Establish the table element or fail
  if ($table=="Group") {
    $tableelement="gflowid";
  } elseif ($table=="Personal") {
    $tableelement="pflowid";
  } else {
    $message="<P>Error finding table $tablename.  Database not updated.</P>\n<P>";
    RenderError($title,$message);
    exit ();
  }

  ## Set up the query
  $query="DELETE FROM $tablename where $tableelement=$flowid";

  ## Execute the query and test the results
  if (($result=mysql_query($query,$link))===false) {
    $message="<P>Error updating $tablename table.  Database not updated.</P>\n<P>";
    $message.=$query;
    RenderError($title,$message);
    exit ();
  }
}

function add_flow_report ($addreport,$addphase,$table,$group,$title,$description) {
  global $link;
  $mybadgeid=$_SESSION['badgeid'];

  ## Get phaseid list
  $query="SELECT phaseid FROM Phases ORDER BY phaseid";

  ## Retrieve query
  list($phasecount,$unneeded_array_a,$phase_array)=queryreport($query,$link,$title,$description,0);

  ## Build the limits
  $firstphase=$phase_array[1]['phaseid'];
  $lastphase=$phase_array[$phasecount]['phaseid'];

  ## Set the phase, if it is within the phaseid list
  $phasecheck="";
  if (($addphase<=$lastphase) AND ($addphase>=$firstphase)) {
    $phasecheck="phaseid='$addphase'";
  } else {
    $phasecheck="phaseid is NULL";
  }

  ## Establish the table name
  $tablename=$table."Flow";

  ## Establish the table element or fail
  if ($table=="Group") {
    $torder="gfloworder";
    $tname="gflowname";
    $cname=$group;
    $tid="gflowid";
  } elseif ($table=="Personal") {
    $torder="pfloworder";
    $tname="badgeid";
    $cname=$mybadgeid;
    $tid="pflowid";
  } else {
    $message="<P>Error finding table $tablename.  Database not updated.</P>\n<P>";
    RenderError($title,$message);
    exit ();
  }

  ## Get the last element number, to increment
  $query="SELECT $torder AS floworder FROM $tablename where $tname='$cname' AND $phasecheck ORDER BY $torder DESC LIMIT 0,1";

  ## Execute the query, test the results and assign the array values
  if (($result=mysql_query($query,$link))===false) {
    $message="<P>Error retrieving data from database.</P>\n<P>";
    $message.=$query;
    RenderError($title,$message);
    exit ();
  }
  $floworder_array[1]=mysql_fetch_assoc($result);

  ## Increment so we don't have redundant keys
  $nextfloworder=$floworder_array[1]['floworder']+1;

  ## Insert query
  if ($phasecheck!="phaseid is NULL") {
    $query="INSERT INTO $tablename (reportid,$tname,$torder,phaseid) VALUES ($addreport,'$cname',$nextfloworder,$addphase)";
  } else {
    $query="INSERT INTO $tablename (reportid,$tname,$torder) VALUES ($addreport,'$cname',$nextfloworder)";
  }

  ## Execute query
  if (!mysql_query($query,$link)) {
    $message=$query."<BR>Error updating $tablename database.  Database not updated.";
    RenderError($title,$message);
    exit ();
  }
}

function deltarank_flow_report ($flowid,$table,$direction,$title,$description) {
  global $link;
 
  ## Establish the table name;
  $tablename=$table."Flow";

  ## Estabilsh the table elements, or fail;
  if ($table=="Group") {
    $torder="gfloworder";
    $tname="gflowname";
    $tid="gflowid";
  } elseif ($table=="Personal") {
    $torder="pfloworder";
    $tname="badgeid";
    $tid="pflowid";
  } else {
    $message="<P>Error finding table $tablename.  Database not updated.</P>\n<P>";
    RenderError($title,$message);
    exit ();
  }

  ## Get element from table;
  $query="SELECT $torder,$tname,phaseid FROM $tablename WHERE $tid=$flowid";
  list($phaserows,$phaseheader_array,$phasereport_array)=queryreport($query,$link,$title,$description,0);

  ## Set the current flow order number;
  $corder=$phasereport_array[1][$torder];
  $cname=$phasereport_array[1][$tname];

  ## Determine the next flow order number, depending on $direction;
  if ($direction=="Up") {
    $norder=$corder-1;
    if ($norder<1) {
      $message="<P>You cannot have an order number less than 1.</P>\n";
      RenderError($title,$message);
      exit ();
    }
  } elseif ($direction="Down") {
    $norder=$corder+1;
  } else {
    $message="<P>You have chosen an inappropriate direction: $direction.</P>\n";
    RenderError($title,$message);
    exit ();
  }

  ## Determine if there is a phaseid attached to this particular flow element;
  if (isset($phasereport_array[1]['phaseid'])) {
    $phase=$phasereport_array[1]['phaseid'];
    $phasecheck="phaseid='$phase'";
  } else {
    $phasecheck="phaseid is NULL";
  }

  ## Get element to be swapped with from table, based on current element floworder and $norder
  $query="SELECT $tid FROM $tablename WHERE $torder=$norder AND $tname='$cname' AND $phasecheck LIMIT 0,1";
  if (($result=mysql_query($query,$link))===false) {
    $message="<P>Error retrieving data from database.</P>\n<P>";
    $message.=$query;
    RenderError($title,$message);
    exit ();
  }

  ## Swap the elements, checking for errors each time
  $query1="UPDATE $tablename set $torder=$norder where $tid=$flowid";

  ## Execute the query and test the results
  if (($result1=mysql_query($query1,$link))===false) {
    $message="<P>Error updating $tablename table.  Database not updated.</P>\n<P>";
    $message.=$query;
    RenderError($title,$message);
    exit ();
  }

  ## If there is nothing to swap with, simply stop here.
  if (1==($row=mysql_num_rows($result))) {
    $replace_array[1]=mysql_fetch_assoc($result);
    $rtid=$replace_array[1][$tid];
    $query="UPDATE $tablename set $torder=$corder where $tid=$rtid";

    ## Execute the query and test the results;
    if (($result=mysql_query($query,$link))===false) {
      $message="<P>Error updating $tablename table.  Database not updated.</P>\n<P>";
      $message.=$query;
      RenderError($title,$message);
      exit ();
    }
  }
}

?>
