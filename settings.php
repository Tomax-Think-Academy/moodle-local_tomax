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

require_once($CFG->dirroot . "/local/tomax/classes/settings/admin_setting_requiredconfigpasswordunmask.php");
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
        "Tomax System Configuration",
        "Define the Tomax system configurations."
    ));

    $settings->add(new admin_setting_requiredtext(
        'local_tomax/domain',
        "Domain",
        "",
        ''
    ));

    $settings->add(new admin_setting_requiredconfigpasswordunmask(
        'local_tomax/etestuserid',
        "TomaETest UserID",
        "",
        ''
    ));

    $settings->add(new admin_setting_requiredconfigpasswordunmask(
        'local_tomax/etestapikey',
        "TomaETest APIKey",
        "",
        ''
    ));

    $settings->add(new admin_setting_configselect(
            'local_tomax/tomax_teacherID',
            'Set the Default Teacher identifier',
            '',
            '',
            $identifierarrayteacher
        ));

    $settings->add(new admin_setting_configselect(
            'local_tomax/tomax_studentID',
            'Set the Default Student identifier',
            '',
            '',
            $identifierarraystudent
        ));
}
