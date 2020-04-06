<?php
class Cookie {

    const UNIQUE_ID = "UNIQUE_ID";

    public static function makeName($name) {
        return "waister_" . $name;
    }

    public static function get($name, $default = "") {
        $name = self::makeName($name);

        return isset($_COOKIE[$name]) ? $_COOKIE[$name] : $default;
    }

    public static function set($name, $value) {
        $name = self::makeName($name);

        $date = new DateTime();
        $date->modify("+10 years");
        $time = $date->getTimestamp();

        setcookie($name, $value, $time, false, false, false, true);
    }

    public static function delete($name) {
        $name = self::makeName($name);

        return setcookie($name, "", time() - 3600, false, false, false, true);
    }
}



// Cookie::set(Cookie::UNIQUE_ID, 111);
// echo Cookie::get(Cookie::UNIQUE_ID, 222);
