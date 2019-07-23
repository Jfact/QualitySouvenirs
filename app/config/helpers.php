<?php

class Helpers
{

    public function form_action(string $str = null)
    {
        return str_replace('.php', '', htmlspecialchars($str));
    }


    public function no_empty(string $str = null)
    {
        if(empty($str) || $str == null) 
            $str = "unspecified";
        
        return $str;
    }
    
    public function test_input($input) 
    {
        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlspecialchars($input);
        
        return $input;
    }

    public function core_link($location) 
    {
        $core = "/solutions/QualitySouvenirs.v.1.3/";
        
        return $core.$location;
    }
}