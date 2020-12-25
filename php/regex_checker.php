<?php

function check_email($email){
    if(preg_match('/^([\w\-\+\.]+)\@([\w\-\+\.]+)\.([\w\-\+\.]+)$/',$email)==1){
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

function check_nome($nome){
    if(preg_match('/^([\p{L}\s]*)$/u',$nome)==1){
        return true;
    }
    return false;
}

function check_tel($tel){
    if(preg_match('/^(\d{10})$/',$tel)==1){
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

function check_cap($cap){
    if(preg_match('/^(\d{5})$/',$cap)==1){
        return true;
    }
    return false;
}

function check_piva($piva){
    if(preg_match('/^[0-9]{11}$/',$piva)==1){
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