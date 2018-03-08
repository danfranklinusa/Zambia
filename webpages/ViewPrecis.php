<?php
// Copyright (c) 2011-2018 Peter Olszowka. All rights reserved. See copyright document for more details.
global $title;
require_once('db_functions.php');
require_once('StaffCommonCode.php');
require_once('render_functions.php');
require_once('retrieve.php');
$title = "Precis";
$showlinks = $_GET["showlinks"] == "1" ? "1" : "0";
$_SESSION['return_to_page'] = "ViewPrecis.php?showlinks=$showlinks";
$showlinks = $showlinks == "1";
$statusidlist = get_idlist_from_db("SessionStatuses", "statusid", "statusname",
    "'Brainstorm','Edit Me','Vetted'");
$typeidlist = "";
$trackidlist = "";
$sessionid = "";
$divisionid = "";
$searchtitle = "";
if ($result = retrieve_select_from_db($trackidlist, $statusidlist, $typeidlist, $sessionid, $divisionid, $searchtitle) == 0) {
    staff_header($title);
    echo "<p> If you have any questions please contact ";
    echo "<a href=\"mailto:" . PROGRAM_EMAIL . "\">" . PROGRAM_EMAIL . "</a> </p>\n";
    RenderPrecis($result, $showlinks);
    staff_footer();
    exit();
}
?>
