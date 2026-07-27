<?php


if (!function_exists('isSuperUser')) {
    function isSuperUser()
    {
        return session()->get('user_auth.login_as') == "superuser";
    
    }
}

if (!function_exists('say')) {
    function say($text)
    {
        return $text;    
    }
}

if (!function_exists('getkDarkMode')) {
    function getkDarkMode()
    {
        return  false;    
    }
}

if (!function_exists('renderFields')) {
    function renderFields()
    {
        return  [];    
    }
}