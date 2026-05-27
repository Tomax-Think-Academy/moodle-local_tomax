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
