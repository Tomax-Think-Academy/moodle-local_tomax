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
 * Plugin administration pages are defined here.
 *
 * @package     local_tomax
 * @category    admin
 * @copyright   2024 Tomax ltd <roy@tomax.co.il>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once($CFG->dirroot . "/local/tomax/classes/settings/admin_settings_requiredconfigpasswordunmask.php");

use local_tomax\Constants;


if ($hassiteconfig) {
    // Create the new settings page
    // - in a local plugin this is not defined as standard, so normal $settings->methods will throw an error as
    // $settings will be null
    $settings = new admin_settingpage('local_tomax', get_string('pluginname', 'local_tomax'));

    // Create
    $ADMIN->add('localplugins', $settings);


    $identifierarraystudent = array(
        Constants::IDENTIFIER_BY_EMAIL => get_string('identifier_by_email', 'local_tomax'),
        Constants::IDENTIFIER_BY_ID => get_string('identifier_by_id', 'local_tomax'),
        Constants::IDENTIFIER_BY_USERNAME => get_string('identifier_by_username', 'local_tomax'),
    );

    $identifierarrayteacher = array(
        Constants::IDENTIFIER_BY_EMAIL => get_string('identifier_by_email', 'local_tomax'),
        Constants::IDENTIFIER_BY_ID => get_string('identifier_by_id', 'local_tomax'),
    );

    $settings->add(new admin_setting_heading(
        "local_tomax_settings",
        get_string('tomax_settings_heading', 'local_tomax'),
        get_string('tomax_settings_desc', 'local_tomax')
    ));

    $settings->add(new admin_setting_requiredtext(
        'local_tomax/domain',
        get_string('domain', 'local_tomax'),
        '',
        ''
    ));

    $settings->add(new admin_setting_configselect(
        'local_tomax/tomax_teacherID',
        get_string('tomax_teacher_id_field', 'local_tomax'),
        get_string('tomax_teacher_id_field_desc', 'local_tomax'),
        '',
        $identifierarrayteacher
    ));

    $settings->add(new admin_setting_configselect(
        'local_tomax/tomax_studentID',
        get_string('tomax_student_id_field', 'local_tomax'),
        get_string('tomax_student_id_field_desc', 'local_tomax'),
        '',
        $identifierarraystudent
    ));

    // --- Display Name Fields ---
    $settings->add(new admin_setting_heading(
        "local_tomax_displayname_settings",
        get_string('displaynamefields_heading', 'local_tomax'),
        get_string('displaynamefields_desc', 'local_tomax')
    ));

    $firstnameoptions = [
        'firstname'          => get_string('field_firstname', 'local_tomax'),
        'alternatename'      => get_string('field_alternatename', 'local_tomax'),
        'middlename'         => get_string('field_middlename', 'local_tomax'),
        'firstnamephonetic'  => get_string('field_firstnamephonetic', 'local_tomax'),
    ];

    $lastnameoptions = [
        'lastname'           => get_string('field_lastname', 'local_tomax'),
        'alternatename'      => get_string('field_alternatename', 'local_tomax'),
        'lastnamephonetic'   => get_string('field_lastnamephonetic', 'local_tomax'),
    ];

    $coursenameoptions = [
        'fullname'  => get_string('field_fullname', 'local_tomax'),
        'shortname' => get_string('field_shortname', 'local_tomax'),
        'idnumber'  => get_string('field_idnumber', 'local_tomax'),
    ];

    $settings->add(new admin_setting_configselect(
        'local_tomax/user_firstname_field',
        get_string('user_firstname_field', 'local_tomax'),
        get_string('user_firstname_field_desc', 'local_tomax'),
        'firstname',
        $firstnameoptions
    ));

    $settings->add(new admin_setting_configselect(
        'local_tomax/user_lastname_field',
        get_string('user_lastname_field', 'local_tomax'),
        get_string('user_lastname_field_desc', 'local_tomax'),
        'lastname',
        $lastnameoptions
    ));

    $settings->add(new admin_setting_configselect(
        'local_tomax/course_name_field',
        get_string('course_name_field', 'local_tomax'),
        get_string('course_name_field_desc', 'local_tomax'),
        'fullname',
        $coursenameoptions
    ));

    $settings->add(new admin_setting_heading(
        "local_tomax_tet_settings",
        get_string('tet_settings_heading', 'local_tomax'),
        get_string('tet_settings_desc', 'local_tomax')
    ));

    $settings->add(new admin_settings_requiredconfigpasswordunmask(
        'local_tomax/etestuserid',
        get_string('tet_userid', 'local_tomax'),
        '',
        ''
    ));

    $settings->add(new admin_settings_requiredconfigpasswordunmask(
        'local_tomax/etestapikey',
        get_string('tet_apikey', 'local_tomax'),
        '',
        ''
    ));

    $checkconnection = new moodle_url('/local/tomax/misc/checkTETConnection.php');
    $settings->add(new admin_setting_heading(
        "tet_connection_check",
        get_string('tet_connection_check_heading', 'local_tomax'),
        get_string('tet_connection_check_desc', 'local_tomax', $checkconnection->out(false))
    ));

    $settings->add(new admin_setting_heading(
        "local_tomax_tg_settings",
        get_string('tg_settings_heading', 'local_tomax'),
        get_string('tg_settings_desc', 'local_tomax')
    ));

    $settings->add(new admin_settings_requiredconfigpasswordunmask(
        'local_tomax/tguserid',
        get_string('tg_userid', 'local_tomax'),
        '',
        ''
    ));

    $settings->add(new admin_settings_requiredconfigpasswordunmask(
        'local_tomax/tgapikey',
        get_string('tg_apikey', 'local_tomax'),
        '',
        ''
    ));

    $checkconnection = new moodle_url('/local/tomax/misc/checkTGConnection.php');
    $settings->add(new admin_setting_heading(
        "tg_connection_check",
        get_string('tg_connection_check_heading', 'local_tomax'),
        get_string('tg_connection_check_desc', 'local_tomax', $checkconnection->out(false))
    ));

    $settings->add(new admin_setting_heading(
        "local_tomax_proxy_settings",
        get_string('proxy_settings_heading', 'local_tomax'),
        ''
    ));
    $settings->add(new admin_setting_configcheckbox(
        'local_tomax/useProxy',
        get_string('use_proxy', 'local_tomax'),
        '',
        '0'
    ));
    $settings->add(new admin_setting_configtext(
        'local_tomax/proxyURL',
        get_string('proxy_url', 'local_tomax'),
        '',
        ''
    ));
    $settings->add(new admin_setting_configtext(
        'local_tomax/proxyPort',
        get_string('proxy_port', 'local_tomax'),
        '',
        ''
    ));
}
