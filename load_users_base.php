<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
set_time_limit(9999999);
define('MY_DS', DIRECTORY_SEPARATOR);
$title = $page_name = 'Получить подписчиков в группу';
require_once('generic' . MY_DS . 'constants.php');
require_once('generic' . MY_DS . 'connection.php');
require_once('generic' . MY_DS . 'actions.php');

require_once('generic/generic_functions.php');
require_once('generic/net_functions.php');
require_once("generic/{$net_code}_functions.php");
include('generic/auth_control.php');









$load_to_file_status= 1;
$limit = MY_REPORT_USERS_COUNTT_LIMIT;
require('generic/prepare_users_list_result.php');

            $file_name = "reports/{$net_code}_self_loaded_" . uniqid() . $user_id . '.csv';
            $fp = fopen($file_name, 'w');

            // CSV
            if ($load_type == 1) {
                $array = array(
                    'Дата сохранения',
                    'ID профиля',
                    'ФИО',
                    get_type_name_by_id($types_fields['is_comment']),
                    get_type_name_by_id($types_fields['is_survey']),
                    get_type_name_by_id($types_fields['is_subscriber']),
                    get_type_name_by_id($types_fields['is_klass']),
                    get_type_name_by_id($types_fields['is_search']),
                    get_type_name_by_id($types_fields['is_repost'])
                );
            } else {
                $array = array(
                    'ID профиля',
                    'ФИО',
                    get_type_name_by_id($types_fields['is_comment']),
                    get_type_name_by_id($types_fields['is_survey']),
                    get_type_name_by_id($types_fields['is_subscriber']),
                    get_type_name_by_id($types_fields['is_klass']),
                    get_type_name_by_id($types_fields['is_repost']),
                );
            }


                fputcsv($fp, $array, ';');

                foreach ($result as $key => $row) {

                    if ($load_type == 1) {
                        $array = array(
                            date("H:i:s d.m.Y", $row['created']),
                            $row['profile_id'],
                            htmlspecialchars_decode($row['user_fio']),
                            $row['is_comment'] ? 'v' : '',
                            $row['is_survey'] ? 'v' : '',
                            $row['is_subscriber'] ? 'v' : '',
                            $row['is_klass'] ? 'v' : '',
                            $row['is_search'] ? 'v' : '',
                            $row['is_repost'] ? 'v' : ''
                        );
                    } else {
                        $array = array(
                            $row['profile_id'],
                            htmlspecialchars_decode($row['user_fio']),
                            $row['is_comment'] ? 'v' : '',
                            $row['is_survey'] ? 'v' : '',
                            $row['is_subscriber'] ? 'v' : '',
                            $row['is_klass'] ? 'v' : '',
                            $row['is_repost'] ? 'v' : ''
                        );
                    }
                    fputcsv($fp, $array, ';'
                    );
                }
            fclose($fp);
            header("Content-type: text/csv");
            header("Content-Disposition: attachment; filename=" . $file_name);
            header("Content-Length: " . filesize($file_name));
            readfile($file_name);
            exit();