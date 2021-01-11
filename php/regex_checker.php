<?php

//TODO: control everything here: not sure it works properly

function check_email($email){
    if(preg_match('/^([\w\-\+\.]+)\@([\w\-\+\.]+)\.([\w\-\+\.]+)$/',$email)==1){
        return true;
    }
    return false;
}

// USERNAME PROPERTIES:
//  - Only letters and numbers (a-z, A-z, 0-9).
//  - No spaces, linebreaks, tabs or special characters.
//  - At least 5 characters in length.
//  - No more than 20 characters in length.
function check_username($username){
    if(preg_match('/^[a-zA-Z0-9]{5,30}$/',$username)==1){
        return true;
    }
    return false;
}

function check_pwd($password){
    if(preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z]{8,}$/',$password)==1){
        return true;
    }
    return false;
}

//\p{L} means any kind of letter fromany language
//TODO: it seems to accept no characters
function check_nome($nome){
    if(preg_match('/^([\p{L}\s]*)$/u',$nome)==1){
        return true;
    }
    return false;
}


function check_num($num){
    if(preg_match('/^([1-9][0-9]*)$/',$num)==1){
        return true;
    }
    return false;
}

function check_number($num){
    if(preg_match('/^([0-9]*)$/',$num)==1){
        return true;
    }
    return false;
}

function check_sito($sito){
    $temp_sito = (!preg_match('#^(ht|f)tps?://#', $sito)) ? 'http://' . $sito : $sito;

    if (filter_var($temp_sito, FILTER_VALIDATE_URL)){
        return true;
    }
    return false;
}
?>