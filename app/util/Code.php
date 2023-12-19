<?php

namespace app\util;

class Code
{
    public function getCode()
    {
        $verificationCode = rand(100000, 999999);
    }
}
