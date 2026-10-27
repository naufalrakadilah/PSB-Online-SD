<?php

use App\Helpers\StuntingHelper;
use App\Helpers\StuntingResult;

if (! function_exists('naufalfunction')) {
    function naufalfunction(
        mixed $tinggi,
        mixed $param2 = null,
        mixed $param3 = null,
        bool $asArray = false
    ): StuntingResult|array {
        return StuntingHelper::naufalfunction($tinggi, $param2, $param3, $asArray);
    }
}
