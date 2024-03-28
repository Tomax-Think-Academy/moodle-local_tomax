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
// require_login();
require_once(__DIR__.'/Utils.php');

class tomagrade_connection
{
    public static $config;

    private static function check_config() {
        $config = static::$config;
        if (empty($config->domain) || empty($config->tgapikey) || empty($config->tguserid)) {
            static::$config = get_config('local_tomax');
            $config = static::$config;
            if (empty($config->domain) || empty($config->tgapikey) || empty($config->tguserid)) {
                $missingparams = [];
                foreach (["domain", "tgapikey", "tguserid"] as $key => $value) {
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

    public static function tg_post_request($endpoint, $payload, $parameters = []) {
        return self::tg_request("POST", $endpoint, $parameters, $payload);
    }

    public static function tg_get_request($endpoint, $parameters = []) {
        return self::tg_request("GET", $endpoint, $parameters, []);
    }

    private static function tg_request($method, $endpoint, $parameters, $payload) {
        $configcheck = self::check_config();
        if (isset($configcheck)) {
            return $configcheck;
        }
        $config = static::$config;
        $queryparams = self::convert_query_params($parameters);
        tg_log("================== $method $endpoint to :$config->domain ====================");
        $url = "https://$config->domain.tomagrade.com/TomaGrade/Server/php/WS.php/$endpoint/TOKEN/USER$queryparams";

        tg_log("url: " . $url);
        
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
                "x-apikey: " . $config->tgapikey,
                "x-userid: " . $config->tguserid
            ]
        );

        if ($method == "POST") {
            tg_log("payload: " . json_encode($payload));
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

        tg_log("================== end $method $endpoint to $config->domain ====================");

        if ($response) {
            tg_log("response: " . $response);
            return json_decode($response, true);
        }
        tg_log("err: " . $err);
        return ["success" => false, "message" => $err];
    }

    public function get_courses() {
        $response = $this->tg_get_request("GetCourses");
        return $response;
    }
    
}
tomagrade_connection::$config = get_config('local_tomax');

function tg_log($item) {
}
