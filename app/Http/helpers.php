<?php


if (!function_exists('isSuperUser')) {
    function isSuperUser()
    {
        return session()->get('user_auth.login_as') == "superuser";
    
    }
}