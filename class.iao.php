<?php

class InAndOut
{
    private static $initiated = false;

    private static $config;

    public static function init() {
        if (self::$initiated) {
            return;
        }

        add_action('admin_menu', array('InAndOut', 'modifyMenu'));
        add_action('wp_login', array('InAndOut', 'trackLogin'));
        add_action('clear_auth_cookie', array('InAndOut', 'trackLogout'));
        self::$config = json_decode(file_get_contents(__DIR__ . '/' . 'config.json'));
    }

    public static function modifyMenu()
    {
        add_menu_page('In and Out Logs', 'In/Out Logs', 'manage_options', 'iao_logs_list', array('InAndOut', 'InAndOutList'));
        add_submenu_page('iao_logs_list', 'In and Out Settings', 'Settings', 'manage_options', 'iao_logs_settings', array('InAndOut', 'InAndOutSettings'));
    }

    public static function trackLogin($userLogin)
    {
        $user = get_user_by( 'login', $userLogin);
        $logLine = self::prepareLogLine($user, 'login');
        self::saveLogLine($logLine);
    }

    private static function prepareLogLine($user, $event)
    {
        $date = new \DateTime();
        return array(
            'event' => $event,
            'username' => $user->user_login,
            'role' => implode('|',$user->roles),
            'ip' => $_SERVER['REMOTE_ADDR'],
            'date' => $date->format('Y-m-d'),
            'time' => $date->format('H:i:s'),
        );
    }

    public static function trackLogout()
    {
        $user = wp_get_current_user();
        $logLine = self::prepareLogLine($user, 'logout');
        self::saveLogLine($logLine);
    }

    private static function saveLogLine($logLine)
    {
        $filepath = self::getLogFilePath();
        file_put_contents($filepath, implode(';', $logLine).PHP_EOL, FILE_APPEND | LOCK_EX);
    }

    private static function getLogFilePath()
    {
        $logDir = wp_upload_dir( null, false );
        $fullpath = $logDir['basedir'] . '/' . self::$config->path;

        if (is_dir($fullpath) === false) {
            mkdir( $fullpath, 0755, true );
        }

        return ($fullpath . '/' . self::$config->filename . self::$config->extension);
    }

    public static function InAndOutList()
    {
        $filepath = $filepath = self::getLogFilePath();
        $events = array();
        if (file_exists($filepath) === true) {
            $file = fopen($filepath, 'r');
            while(feof($file) == false) {
                $line = fgets($file);
                $events[] = explode(';', $line);
            }
            fclose($file);
        }

        self::view('list', array('events' => $events));
    }

    public static function InAndOutSettings()
    {
        $message = '';
        $error = '';
        if (isset($_POST['insert'])) {
            $path = $_POST['path'];
            $filename = $_POST['filename'];

            if (empty($path) || empty($filename)) {
                self::view('settings', array('config' => self::$config, 'error' => 'Values cannot be empty'));
                return;
            }

            if (preg_match('/^[A-z0-9]+$/', $path, $match) === 0 || preg_match('/^[A-z0-9-]+$/', $filename, $match) === 0) {
                self::view('settings', array('config' => self::$config, 'error' => 'Names are invalid, must use letters or numbers or hyphens only'));
                return;
            }

            $path = $_POST['path'];
            $filename = $_POST['filename'];

            $config = json_encode(array(
                'path' => $path,
                'filename' => $filename,
                'extension' => '.log',
            ));

            file_put_contents(__DIR__ . '/' . 'config.json', $config);

            $message = "Settings saved";
            self::$config = json_decode($config);
        }

        self::view('settings', array('config' => self::$config, 'message' => $message));
    }

    public static function view($name, $args = array())
    {
        foreach ($args AS $key => $val) {
            $$key = $val;
        }

        $file = IAO__DIR . 'views/'. $name . '.php';

        include($file);
    }
}