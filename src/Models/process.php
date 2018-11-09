<?php

namespace SIVI\AFDSKP\Models;

class Process
{

    public function __construct()
    {
    }

    /**
     * @var string
     */
    public $procesId;

    /**
     * @var string
     */
    public $procesStatus;

    /**
     * @var string
     */
    public $procesOmschrijving;

    /**
     * @var string
     */
    public $productId;

    /**
     * @var string
     */
    public $contextId;

    /**
     * @var string
     */
    public $functieId;

}