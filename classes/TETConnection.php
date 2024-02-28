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
 * version.php - version information.
 *
 * @package     local_tomax
 * @category    admin
 * @copyright  2024 Tomax ltd <roy@tomax.co.il>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
require_login();
require_once(__DIR__.'/Utils.php');

class tomaetest_connection
{
    public static $config;

    private static function check_config() {
        $config = static::$config;
        if (empty($config->domain) || empty($config->etestapikey) || empty($config->etestuserid)) {
            static::$config = get_config('local_tomax');
            $config = static::$config;
            if (empty($config->domain) || empty($config->etestapikey) || empty($config->etestuserid)) {
                $missingparams = [];
                foreach (["domain", "etestapikey", "etestuserid"] as $key => $value) {
                    if (empty($config->$value)) {
                        array_push($missingparams, $value);
                    }
                }
                return ["success" => false, "missingparams" => $missingparams];
            }
        }
        return null;
    }

    private static function convert_query_params($params) {
        if (empty($params)) {
            return "";
        }
        if (is_array($params) || is_object($params)) {
            return "?" . http_build_query($params);
        }
        // TODORON: handle bad params
    }

    public static function tet_post_request($endpoint, $payload, $parameters = []) {
        return self::tet_request("POST", $endpoint, $parameters, $payload);
    }

    public static function tet_get_request($endpoint, $parameters = []) {
        return self::tet_request("GET", $endpoint, $parameters, []);
    }

    private static function tet_request($method, $endpoint, $parameters, $payload) {
        $configcheck = self::check_config();
        if (isset($configcheck)) {
            return $configcheck;
        }
        $config = static::$config;
        $queryparams = self::convert_query_params($parameters);
        etest_log("================== $method $endpoint to :$config->domain ====================");
        // TODORON: change back to TomaETest (without Ron)
        $url = "https://$config->domain.tomaetest.com/TomaETestRon/api/dashboard/WS/$endpoint$queryparams";

        etest_log("url: " . $url);
        
        $ch = curl_init();
        
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER => [
                "cache-control: no-cache",
                "x-apikey: " . $config->etestapikey,
                "x-userid: " . $config->etestuserid
            ]
        );

        if ($method == "POST") {
            etest_log("payload: " . json_encode($payload));
            $options[CURLOPT_CUSTOMREQUEST] = "POST";
            $options[CURLOPT_POSTFIELDS] = json_encode($payload);
        }

        if (isset($config->useProxy) && $config->useProxy === "1") {
            if (isset($config->proxyURL) && !empty($config->proxyURL)) {
                $proxy = $config->proxyURL;
                if (isset($config->proxyPort) && !empty($config->proxyPort)) {
                    $proxy = $proxy . ':' . $config->proxyPort;
                }
                $options[CURLOPT_PROXY] = $proxy;
            }
            else {
                // TODORON: maybe throw error here?
            }
        }

        curl_setopt_array($ch, $options);

        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        etest_log("================== end $method $endpoint to $config->domain ====================");

        if ($response) {
            etest_log("response: " . $response);
            return json_decode($response, true);
        }
        etest_log("err: " . $err);
        return ["success" => false, "message" => $err];
    }

    public static function get_exams() {
        $result = self::tet_get_request("exam/list");
        return $result;
    }

    public static function sso($userid, $examid=null, $courseid=null, $location=null) {
        $externalid = tomax_utils::get_teacher_id($userid);
        $data["userExternalID"] = $externalid;
        if ($location == "activity-settings" && isset($examid) && isset($courseid)) {
            $externallocation = 'management/courses-lecturer/' . $courseid . "/" . "settings/" . $examid;
            $data["externalLocation"] = $externallocation;
        }
        if ($location == "monitor" && isset($examid)) {
            $externallocation = "exams/" . $examid . "/home";
            $data["externalLocation"] = $externallocation;
        }
        $result = static::tet_post_request("auth/login/SafeGenerateToken", $data);
        if ($result["success"] == true) {
            return $result["data"]["url"];
        }
    }




















    // public static function sso($quizid, $userid, $parid = null) {
    //     $record = quizaccess_tomaetest_utils::get_etest_quiz($quizid);
    //     if (!$record) {
    //         return false;
    //     }
    //     $id = $record->extradata["TETID"];
    //     $externalid = quizaccess_tomaetest_utils::get_teacher_id($userid);
    //     $examid = $record->extradata["TETExternalID"];
    //     $data = ["userExternalID" => $externalid, "examExternalID" => $examid];
    //     if ($parid !== null) {
    //         $data["externalLocation"] = "exams/$id/proctoring/$parid";
    //     }
    //     $result = static::post_request("auth/login/SafeGenerateToken", $data);
    //     if ($result["success"] == true) {
    //         return $result["data"]["url"];
    //     }
    //     return false;
    // }

    public static function sso_integrity_management($userid) {
        $externalid = quizaccess_tomaetest_utils::get_teacher_id($userid);
        $data = ["userExternalID" => $externalid, "externalLocation" => "air"];
        $result = static::post_request("auth/login/SafeGenerateToken", $data);
        if ($result["success"] == true) {
            return $result["data"]["url"];
        }
        return false;
    }

    public static function get_information($id) {
        return static::get_participants_list($id, 1);
    }

    public static function get_exam_specific_information($id) {
        return $result = static::post_request(
            "exam/view?ID=$id",
            []
        );
        return $result;
    }

    public static function get_participants_list($id, $amount = false) {

        $result = static::post_request(
            "exam/participant/view",
            [
                "ID" => $id,
                "filter" => [
                    "firstRow" => 0,
                    "amount" => ($amount === false) ? 200 : 1,
                    "filterItem" => "allPars",
                    "filterString" => ""
                ]
            ]
        );
        return $result;
    }

    public static function sync_to_toma_etest_from_database($quizid, $tetquiz = null) {
        global $DB;
        if ($tetquiz === null) {
            $tetquiz = quizaccess_tomaetest_utils::get_etest_quiz($quizid);
        }
        $quiz = quizaccess_tomaetest_utils::get_quiz($quizid);
        $cmid = quizaccess_tomaetest_utils::get_cmid($quizid);
        $cm = quizaccess_tomaetest_utils::get_coursemodule($cmid);
        $course = quizaccess_tomaetest_utils::get_course_information($quiz->course);

        $externalid = $tetquiz->extradata["TETExternalID"];
        $lockcomputer = $tetquiz->extradata["LockComputer"];
        $verificationtype = $tetquiz->extradata["VerificationType"];
        $verificationtiming = $tetquiz->extradata["VerificationTiming"];
        $proctoringtype = $tetquiz->extradata["ProctoringType"];
        $scanningmodule = (isset($tetquiz->extradata["ScanningModule"])) ? $tetquiz->extradata["ScanningModule"] : false;
        $blockthirdparty = (isset($tetquiz->extradata["BlockThirdParty"])) ? $tetquiz->extradata["BlockThirdParty"] : false;
        $showparticipantonscreen = (isset($tetquiz->extradata["ShowParticipant"])) ? $tetquiz->extradata["ShowParticipant"] : false;
        $relogin = (isset($tetquiz->extradata["ReLogin"])) ? $tetquiz->extradata["ReLogin"] : false;
        $scanningtime = (isset($tetquiz->extradata["ScanningTime"])) ? $tetquiz->extradata["ScanningTime"] : 0;

        date_default_timezone_set('UTC');
        if (isset($quiz->timeopen) && $quiz->timeopen != 0) {
            $date = date("d/m/Y", $quiz->timeopen);
            $time = date("H:i", $quiz->timeopen);
        } else {
            $date = date("d/m/Y", strtotime("+1 month")); // Default = in a month.
            $time = "00:00";
        }

        $quizname = $quiz->name;
        $coursename = $course->fullname;
        $teacherid = null;
        if (isset($tetquiz->extradata["TeacherID"])) {
            $user = $DB->get_record("user", array("id" => $tetquiz->extradata["TeacherID"]));
            $teacherid = quizaccess_tomaetest_utils::get_external_id_for_teacher($user);
        }

        $cmurl = new moodle_url('/mod/quiz/view.php', array('id' => $cmid));
        $thirdparty = [
            "QuizURL" => $cmurl->__toString(),
            "PassToTG" => $scanningmodule
        ];

        $result = self::sync_to_tomaetest($quiz->id, $quizname, $date, $coursename,
         $externalid, $teacherid, $time, $lockcomputer, $verificationtype,
          $verificationtiming, $proctoringtype, $showparticipantonscreen, $thirdparty,
           $scanningmodule, $blockthirdparty, $relogin, $scanningtime);
        return $result;
    }


    public static function sync_to_tomaetest($quizid, $name, $date, $course,
     $externalid, $teacherexternalid, $starttime, $lockcomputer,
      $verificationtype, $verificationtiming, $proctoringtype,
       $showparticipantonscreen, $exam3rdpartyconfig, $scanningmodule,
        $blockthirdparty, $relogin, $scanningtime) {
        $duration = 1000000;
        $data = [
            "bankExamDTO" => null,
            "examParameter" => [
                "TETExamDate" => $date,
                "TETExamName" => $name,
                "TETExamCourse" => $course,
                "TETExamExternalID" => $externalid,
                "TETExamType" => "open",
                "TETTeacherExternalID" => $teacherexternalid,
                "Year" => -1,
                "Moed" => -1,
                "Semester" => -1,
                "TETExamQuestionNumberingType" => "1",
                "TETExamQuestionAnswerNumberingType" => "a",
                "TETExam3rdPartyConfig" => $exam3rdpartyconfig,
                "TETExamRecordingParticipantView" => $showparticipantonscreen,
                "TETExamUseUnlockParticipantPassword" => $relogin,
                "TETExamDuration" => $duration,
                "TETOverallExamOverTime" => 0,
                "TETExamLockComputer" => ["key" => $lockcomputer],
                "TETExamStartTime" => $starttime,
                "TETExamAuthorizationType" => ["key" => "saml"],
                "TETExamPasswordTrustNeeded" => ['key' => $verificationtiming],
                "TETExamEndDelay" => $scanningtime,
                "TETExamProctoringType" => array_map(function($proctoringtype){
                    return ["key" => $proctoringtype];
                }, $proctoringtype),
                "TETExamVerificationType" => array_map(function($verificationtype){
                    return ["key" => $verificationtype];
                }, $verificationtype),
            ]
        ];
        if ($blockthirdparty) {
            $alertedapps = [];
            $deniedapps = [];

            $keys = array_filter(array_keys((array)self::$config), function ($item) {
                if (strpos($item, "tomaetest_appstate_") > -1) {
                    return true;
                }
                return false;
            });

            foreach ($keys as $key) {
                $name = str_replace("tomaetest_appstate_", "", $key);
                // Alert...
                if (self::$config->{$key} === "1") {
                    array_push($alertedapps, $name);
                    // Deny...
                } else if (self::$config->{$key} === "2") {
                    array_push($deniedapps, $name);
                }
            }

            $data['examParameter']["TETExamAlertedApps"] = $alertedapps;
            $data['examParameter']["TETExamDeniedApps"] = $deniedapps;
        }
        $data["examParticipants"] = [];
        $students = quizaccess_tomaetest_utils::get_quiz_students($quizid);
        foreach ($students as $student) {
            array_push(
                $data["examParticipants"],
                [
                    "Active" => true,
                    "UserName" => $student->TETParticipantIdentity,
                    "Attributes" => $student
                ]
            );
        }
        $users = quizaccess_tomaetest_utils::get_quiz_teachers($quizid);
        $data["Users"] = $users;
        if ($scanningmodule === true) {
            $data["bankExamDTO"] = array(
                array(
                    "elements" => [[
                        "TETQuestionType" => "open",
                        "TETElementID" => 1,
                        "TETQuestionScore" => 100,
                        "TETQuestionTitle" => "Scanning question",
                        "TETElementPath" => "1",
                        "TETElementType" => "question",
                        "TETElementPin" => 1,
                        "TETExamQuestionResponseType" => str_replace("\"", "'", json_encode(["scan"])),
                        "TETElementOriginalOrder" => 1,
                        "TETContinuousNumbering" => 0,
                    ]]
                )
            );
        }

        return static::post_request("exam/tsimport/insert", $data);
    }

    
}
tomaetest_connection::$config = get_config('local_tomax');

function etest_log($item) {
}
