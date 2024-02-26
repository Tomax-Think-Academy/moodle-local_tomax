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

        global $DB;
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
    
    public static function moodle_participants_to_tet_participants($moodlearray) {
        return
            array_map(function ($student) {
                $newstudent = new stdClass();
                $newstudent->TETParticipantFirstName = $student->firstname;
                $newstudent->TETParticipantLastName = $student->lastname;
                $newstudent->TETParticipantPhone = $student->phone1;
                $newstudent->TETParticipantIdentity = self::get_external_id_for_participant($student);
                return $newstudent;
            }, $moodlearray);
    }

    public static function moodle_users_to_tet_users($moodlearray) {
        return array_map(function ($user) {
            $newuser = new stdClass();

            $newuser->EtestRole = "ROLE_MOODLE";
            // TODORON: change to role based on role in moodle
            $newuser->TETExternalID = static::get_external_id_for_teacher($user);
            $newuser->UserName = static::get_external_id_for_teacher($user);
            $newuser->TETUserLastName = $user->lastname;
            $newuser->TETUserEmail = $user->email;
            $newuser->TETUserFirstName = $user->firstname;
            $newuser->TETUserPhone = $user->phone1;

            return $newuser;
        }, $moodlearray);
    }

    public static function create_tet_user($id) {
        global $DB;

        $user = $DB->get_record("user", array("id" => $id));
        $user = static::moodle_users_to_tet_users([$user])[0];

        $tetuserresponse = tomaetest_connection::tet_post_request("user/getByExternalID/view", ["ExternalID" => $user->TETExternalID]);

        if (!$tetuserresponse["success"]) {
            $sendingobject = [
                "UserName" => $user->UserName,
                "Attributes" => $user
            ];
            unset($sendingobject["Attributes"]->UserName);
            unset($sendingobject["Attributes"]->EtestRole);
            $tetuserresponse = tomaetest_connection::tet_post_request("user/insert", $sendingobject);
            if (!$tetuserresponse['success']) {
                return "Duplicate ExternalID/UserName - " . $sendingobject["UserName"] . " Please check for duplicate data.";
            }
            $tetuserid = $tetuserresponse["data"];
        } else {
            $tetuserid = $tetuserresponse["data"]["Entity"];
        }
        $rolename = $user->EtestRole;
        $tetroleresponse = tomaetest_connection::tet_post_request("role/getByName/view", ["Name" => $rolename]);

        if (!$tetroleresponse["success"]) {
            return "Could not find role in TET.";
        }
        $roleid = $tetroleresponse["data"]["Entity"]["ID"];
        $responseconnect = tomaetest_connection::tet_post_request("user/edit?ID=" . $tetuserid, [
            "ID" => $tetuserid,
            "Attributes" => new stdClass(),
            "Roles" => ["Delete" => [], "Insert" => [$roleid]]
        ]);
        return true;
    }







    // TODORON: move all these to the new plugin
    public static function get_coursemodule($cmid) {
        global $DB;
        $record = $DB->get_record('course_modules', array('id' => $cmid));
        return $record;
    }
    public static function get_course($cmid) {
        global $DB;
        $record = $DB->get_record_sql(
            "select * from {course_modules}
            join {course} on {course_modules}.course = {course}.id
            where {course_modules}.id = ?",
            [$cmid]
        );
        return $record;
    }
    public static function get_course_information($courseid) {
        global $DB;
        return $DB->get_record('course', array('id' => $courseid));
    }
    public static function get_activity_by_exam_code($code) {
        global $DB;
        $record = $DB->get_record_sql(
            // TODORON: update table name
            "select * from {CHANGE_THIS_NAME} where extradata like ?",
            ["%\"TETExamLink\":\"$code\"%"]
        );
        return $record;
    }
    public static function update_record($record) {
        global $DB;
        $record->extradata = json_encode($record->extradata);
        // TODORON: update table name
        return $DB->update_record('CHANGE_THIS_NAME', $record);
    }
    public static function get_etest_activity($activityid) {
        global $DB;
        try {
            // TODORON: update table name
            $record = $DB->get_record('CHANGE_THIS_NAME', array('quizid' => $activityid));
        } catch (Exception $e) {
            return false;
        }
        if ($record != false) {
            if (isset($record->extradata)) {
                $record->extradata = json_decode($record->extradata, true);
            } else {
                $record->extradata = [];
            }
        }
        return $record;
    }
    public static function get_cmid($activityid) {
        global $DB;
        $record = $DB->get_record_sql("SELECT {course_modules}.ID from {course_modules}
        join {modules} on module = {modules}.id
        where {modules}.name = 'CHANGE' and {course_modules}.instance = ?", [$activityid]);

        return ($record != false) ? $record->id : null;
    }
    public static function get_quiz_students($quizid) {
        global $DB;

        $cmid = static::get_cmid($quizid);
        $context = context_module::instance($cmid);

        $students = get_users_by_capability($context, "mod/quiz:attempt");
        $students = static::moodle_participants_to_tet_participants($students);

        return $students;
    }
    public static function get_moodle_teachers($quizid = null, $userid = null) {
        global $DB;
        if ($quizid === null) {
            $systemcontext = context_system::instance();
            $teachers = [];
            if (has_capability("mod/quizaccess_tomaetest:viewtomaetestmonitor", $systemcontext, $userid)) {
                array_push($teachers, $DB->get_record('user', array("id" => $userid)));
            }
            return $teachers;
        }
        $cmid = static::get_cmid($quizid);
        $context = context_module::instance($cmid);
        $teachers = get_users_by_capability($context, "mod/quizaccess_tomaetest:viewtomaetestmonitor");
        if ($userid !== null) {
            $teachers = array_filter($teachers, function ($user) use ($userid) {
                return $user->id === $userid;
            });
        }

        return $teachers;
    }
    public static function get_moodle_teachers_by_course($courseid) {
        $context = context_course::instance($courseid);
        return get_users_by_capability($context, "mod/quizaccess_tomaetest:viewtomaetestmonitor");
    }
    public static function get_moodle_allowed_integrity_management($userid = null) {
        global $DB;
        $systemcontext = context_system::instance();
        $teachers = [];
        if (has_capability("mod/quizaccess_tomaetest:viewtomaetestair", $systemcontext, $userid)) {
            array_push($teachers, $DB->get_record('user', array("id" => $userid)));
        }
        return $teachers;
    }

    
}
tomax_utils::$config = get_config('local_tomax');
