<?php
// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Plugin strings are defined here.
 *
 * @package     local_tomax
 * @category    string
 * @copyright   2024 Tomax ltd <roy@tomax.co.il>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'Tomax';

$string['identifier_by_email'] = 'כתובת אימייל';
$string['identifier_by_id'] = 'מספר זהות';
$string['identifier_by_username'] = 'שם משתמש';

$string['well_connected'] = "המערכת שלך מחוברת היטב!";
$string['connection_auth_error'] = "אנא בדוק את APIKey שלך ו-UserID";
$string['no_TET_open_connection'] = "נראה שאין לך חיבור פתוח ל- TomaETest";
$string['no_TG_open_connection'] = "נראה שאין לך חיבור פתוח ל- TomaGrade";

// Display Name Fields section
$string['displaynamefields_heading'] = 'שדות שם תצוגה';
$string['displaynamefields_desc'] = 'בחר אילו שדות פרופיל משתמש ושדות קורס ישמשו לשליחת שמות למערכות Tomax. ברירות המחדל תואמות לשדות Moodle הסטנדרטיים; שינוי ההגדרות ישפיע רק על קריאות API עתידיות.';

$string['user_firstname_field'] = 'שדה שם פרטי של משתמש';
$string['user_firstname_field_desc'] = 'שדה פרופיל המשתמש המשמש כשם פרטי הנשלח למערכות Tomax. אם השדה הנבחר ריק עבור משתמש כלשהו, ​​המערכת תחזור ל-"firstname".';

$string['user_lastname_field'] = 'שדה שם משפחה של משתמש';
$string['user_lastname_field_desc'] = 'שדה פרופיל המשתמש המשמש כשם משפחה הנשלח למערכות Tomax. אם השדה הנבחר ריק עבור משתמש כלשהו, ​​המערכת תחזור ל-"lastname".';

$string['course_name_field'] = 'שדה שם קורס';
$string['course_name_field_desc'] = 'שדה הקורס המשמש כשם הקורס הנשלח למערכות Tomax. אם השדה הנבחר ריק עבור קורס כלשהו, ​​המערכת תחזור ל-"fullname".';

// Field option labels
$string['field_firstname']          = 'שם פרטי (firstname)';
$string['field_lastname']           = 'שם משפחה (lastname)';
$string['field_alternatename']      = 'שם חלופי (alternatename)';
$string['field_middlename']         = 'שם אמצעי (middlename)';
$string['field_firstnamephonetic']  = 'שם פרטי פונטי (firstnamephonetic)';
$string['field_lastnamephonetic']   = 'שם משפחה פונטי (lastnamephonetic)';
$string['field_fullname']           = 'שם מלא (fullname)';
$string['field_shortname']          = 'שם מקוצר (shortname)';
$string['field_idnumber']           = 'מספר מזהה (idnumber)';