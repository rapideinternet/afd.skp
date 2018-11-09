<?php

namespace SIVI\AFDSKP\Models;

class ProcessMessage
{

    public function __construct()
    {
    }

    /**
     * @var string
     */
    public $type;

    /**
     * @var int
     */
    public $code;

    /**
     * @var string
     */
    public $message;

    /**
     * @var string
     */
    public $details;

}