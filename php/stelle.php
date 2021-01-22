<?php

function printStars($num) 
{
    $rounded = round($num);
    $stelle = '';
    $i = 0;
    while($i<$rounded) {
        // stelle piene
        $stelle = $stelle . '&#9733;'; 
        $i++;
    }
    while($i<5) {
        // stelle vuote
        $stelle = $stelle . '&#9734;';
        $i++;
    }
    return $stelle;
}

?>