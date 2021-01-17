<?php
    
    function printStars($num) {
        $rounded = round($num);
        $stelle = '';
        for($i=0; $i<$rounded; $i++)
        {
            $stelle = $stelle . '&#9733;';
        }
    }

?>