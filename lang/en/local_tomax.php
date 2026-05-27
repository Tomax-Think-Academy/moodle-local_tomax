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

$string['identifier_by_email'] = 'Email Address';
$string['identifier_by_id'] = 'ID Number';
$string['identifier_by_username'] = 'Username';

// Tomax System Configuration section
$string['tomax_settings_heading'] = 'Tomax System Configuration';
$string['tomax_settings_desc']    = 'Define the Tomax system configurations.';
$string['domain']                 = 'Domain';
$string['tomax_teacher_id_field']       = 'Teacher external identifier';
$string['tomax_teacher_id_field_desc']  = 'The user profile field sent to Tomax as the teacher\'s unique external ID (e.g. for SSO and course sync). Must be unique per user.';
$string['tomax_student_id_field']       = 'Student external identifier';
$string['tomax_student_id_field_desc']  = 'The user profile field sent to Tomax as the student\'s unique external ID (e.g. for participant sync and identity verification). Must be unique per user.';

// TomaETest System Configuration section
$string['tet_settings_heading']        = 'TomaETest System Configuration';
$string['tet_settings_desc']           = 'Define the TomaETest system configurations.';
$string['tet_userid']                  = 'TomaETest UserID';
$string['tet_apikey']                  = 'TomaETest APIKey';
$string['tet_connection_check_heading'] = 'TomaETest Connection Check';
$string['tet_connection_check_desc']   = 'Click here to check your TomaETest connection (save the changes before checking): <button type=\'button\' onclick=\'window.open("{$a}", "_self")\'>Check Connection</button>';

// TomaGrade System Configuration section
$string['tg_settings_heading']        = 'TomaGrade System Configuration';
$string['tg_settings_desc']           = 'Define the TomaGrade system configurations.';
$string['tg_userid']                  = 'TomaGrade UserID';
$string['tg_apikey']                  = 'TomaGrade APIKey';
$string['tg_connection_check_heading'] = 'TomaGrade Connection Check';
$string['tg_connection_check_desc']   = 'Click here to check your TomaGrade connection (save the changes before checking): <button type=\'button\' onclick=\'window.open("{$a}", "_self")\'>Check Connection</button>';

// Proxy Settings section
$string['proxy_settings_heading'] = 'Proxy Settings';
$string['use_proxy']              = 'Use Proxy';
$string['proxy_url']              = 'Proxy URL';
$string['proxy_port']             = 'Proxy Port';

$string['well_connected'] = "Your system is well connected!";
$string['connection_auth_error'] = "Please check your APIKey and UserID";
$string['no_TET_open_connection'] = "It seems you do not have an open connection to TomaETest";
$string['no_TG_open_connection'] = "It seems you do not have an open connection to TomaGrade";

// Display Name Fields section
$string['displaynamefields_heading'] = 'Display Name Fields';
$string['displaynamefields_desc'] = 'Choose which Moodle profile and course fields are used when sending names to Tomax systems. Defaults match standard Moodle fields; changing these only affects future API calls.';

$string['user_firstname_field'] = 'User first name field';
$string['user_firstname_field_desc'] = 'The user profile field used as the first name sent to Tomax systems. Falls back to "firstname" if the chosen field is empty for a user.';

$string['user_lastname_field'] = 'User last name field';
$string['user_lastname_field_desc'] = 'The user profile field used as the last name sent to Tomax systems. Falls back to "lastname" if the chosen field is empty for a user.';

$string['course_name_field'] = 'Course name field';
$string['course_name_field_desc'] = 'The course field used as the course name sent to Tomax systems. Falls back to "fullname" if the chosen field is empty for a course.';

// Field option labels
$string['field_firstname']          = 'First name (firstname)';
$string['field_lastname']           = 'Last name (lastname)';
$string['field_alternatename']      = 'Alternate name (alternatename)';
$string['field_middlename']         = 'Middle name (middlename)';
$string['field_firstnamephonetic']  = 'First name - phonetic (firstnamephonetic)';
$string['field_lastnamephonetic']   = 'Last name - phonetic (lastnamephonetic)';
$string['field_fullname']           = 'Full name (fullname)';
$string['field_shortname']          = 'Short name (shortname)';
$string['field_idnumber']           = 'ID number (idnumber)';
