<?php
// This file is part of Moodle - http://moodle.org/
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
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
/**
 *
 * @package     local_tomax
 * @copyright   2024 Tomax ltd <roy@tomax.co.il>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use local_tomax\Constants;

/**
 * Class tomax_utils
 *
 * @package local_tomax
 */
class tomax_utils
{
    public static $config;

    public static function get_external_id_for_teacher($user) {
        $output = null;
        if (self::$config->tomax_teacherID == Constants::IDENTIFIER_BY_EMAIL) {
            $output = $user->email;
        } else if (self::$config->tomax_teacherID == Constants::IDENTIFIER_BY_ID) {
            $output = $user->idnumber;
        }
        return $output;

    }

    public static function get_external_id_for_participant($user) {
        if (self::$config->tomax_studentID == Constants::IDENTIFIER_BY_EMAIL) {
            $output = $user->email;
        } else if (self::$config->tomax_studentID == Constants::IDENTIFIER_BY_ID) {
            $output = $user->idnumber;
        } else if (self::$config->tomax_studentID == Constants::IDENTIFIER_BY_USERNAME) {
            $output = $user->username;
        }
        return $output;
    }

    public static function get_teacher_id($userid) {
        global $DB;

        $user = $DB->get_record('user', array('id' => $userid));
        return self::get_external_id_for_teacher($user);
    }

    public static function get_participant_by_external_id($externalid) {
        global $DB;

        $column = "";
        if (self::$config->tomax_studentID == Constants::IDENTIFIER_BY_EMAIL) {
            $column = 'email';
        } else if (self::$config->tomax_studentID == Constants::IDENTIFIER_BY_ID) {
            $column = 'idnumber';
        } else if (self::$config->tomax_studentID == Constants::IDENTIFIER_BY_USERNAME) {
            $column = 'username';
        } else {
            return false;
        }
        $user = $DB->get_record('user', array($column => $externalid));
        return $user;
    }

    /**
     * Returns the configured first-name value for a user.
     * Falls back to $user->firstname (with a developer warning) if the configured field is empty.
     *
     * @param stdClass $user A Moodle user record.
     * @return string
     */
    public static function get_user_firstname(stdClass $user): string {
        $field = !empty(static::$config->user_firstname_field) ? static::$config->user_firstname_field : 'firstname';
        $value = isset($user->$field) ? (string)$user->$field : '';
        if ($value === '') {
            debugging(
                "local_tomax: configured first-name field '$field' is empty for user {$user->id}; falling back to firstname.",
                DEBUG_DEVELOPER
            );
            $value = isset($user->firstname) ? (string)$user->firstname : '';
        }
        return $value;
    }

    /**
     * Returns the configured last-name value for a user.
     * Falls back to $user->lastname (with a developer warning) if the configured field is empty.
     *
     * @param stdClass $user A Moodle user record.
     * @return string
     */
    public static function get_user_lastname(stdClass $user): string {
        $field = !empty(static::$config->user_lastname_field) ? static::$config->user_lastname_field : 'lastname';
        $value = isset($user->$field) ? (string)$user->$field : '';
        if ($value === '') {
            debugging(
                "local_tomax: configured last-name field '$field' is empty for user {$user->id}; falling back to lastname.",
                DEBUG_DEVELOPER
            );
            $value = isset($user->lastname) ? (string)$user->lastname : '';
        }
        return $value;
    }

    /**
     * Returns the configured course-name value for a course.
     * Falls back to $course->fullname (with a developer warning) if the configured field is empty.
     *
     * @param stdClass $course A Moodle course record.
     * @return string
     */
    public static function get_course_name(stdClass $course): string {
        $field = !empty(static::$config->course_name_field) ? static::$config->course_name_field : 'fullname';
        $value = isset($course->$field) ? (string)$course->$field : '';
        if ($value === '') {
            debugging(
                "local_tomax: configured course-name field '$field' is empty for course {$course->id}; falling back to fullname.",
                DEBUG_DEVELOPER
            );
            $value = isset($course->fullname) ? (string)$course->fullname : '';
        }
        return $value;
    }

}
tomax_utils::$config = get_config('local_tomax');
