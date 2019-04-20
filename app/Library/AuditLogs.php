<?php

namespace App\Library;
use URL;
use Auth;
use ClassFactory as CF;
use Browser;

class AuditLogs{

	public static function audits($acctype,$user,$ipaddress,$action){
        $getDevice = '';
        if(Browser::isMobile()){
            $getDevice = 'Mobile';
        }elseif(Browser::isTablet()){
            $getDevice = 'Tablet';
        }elseif(Browser::isDesktop()){
            $getDevice = 'Desktop';
        }elseif(Browser::isBot()){
            $getDevice = 'Bot';
        }
        if($acctype == 'admin'){
            $array = array(
                'admin_id' => $user->id,
                'action' => $action,
                'ip_address' => $ipaddress,
                'device' => $getDevice,
                'browser' => Browser::browserName(),
                'operating_system' => Browser::platformName(),
            );
            CF::model('Admin_audit')->saveData($array, true);
        }elseif($acctype == 'student'){
            $array = array(
                'student_id' => $user->id,
                'action' => $action,
                'ip_address' => $ipaddress,
                'device' => $getDevice,
                'browser' => Browser::browserName(),
                'operating_system' => Browser::platformName(),
            );
            CF::model('Student_audit')->saveData($array, true);
        }
    }
}

?>