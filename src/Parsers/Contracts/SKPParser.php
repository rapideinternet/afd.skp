<?php

namespace SIVI\AFDSKP\Parsers\Contracts;

use SIVI\AFDSKP\Models\SKPAction;

interface SKPParser
{
    public function parse($xmlString): SKPAction;
}
