<?php

namespace SIVI\AFDSKP\Parsers\Contracts;

use SIVI\AFDSKP\Models\Message;

interface SKPParser
{
    public function parse($xmlString): Message;
}
