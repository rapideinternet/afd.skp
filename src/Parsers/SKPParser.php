<?php


namespace SIVI\AFDSKP\Parsers;

use SIVI\AFDSKP\Models\DoeFunctieVraag;
use SIVI\AFDSKP\Models\Message;
use SIVI\AFDSKP\Parsers\Contracts\SKPParser as SKPParserContract;

class SKPParser implements SKPParserContract
{
    /**
     * XMLParser constructor.
     * @param
     */
    public function __construct()
    {
    }

    /**
     * @param $xmlString
     * @return
     */
    public function parse($xmlString): Message
    {
        $xml = simplexml_load_string($xmlString);

        return $this->processMessage($xml);
    }

    /**
     * @param $name
     * @param \SimpleXMLElement $nodes
     * @return Message
     */
    public function processMessage(\SimpleXMLElement $nodes) : Message
    {
        $name = $nodes->getName();

        $messageNode = $nodes->children()[0];

        $message = $this->processFunction($messageNode);

        return $message;
    }

    private function processFunction(\SimpleXMLElement $nodes)
    {
        $message;
        switch ($nodes->getName()){
            case "doeFunctieVraag":
                $message = $this->setProcessInfo(new DoeFunctieVraag(),$nodes);
        }
        return $message;
    }

    private function setProcessInfo(Message $message, \SimpleXMLElement $nodes)
    {
        $procesInfo = $nodes->procesInfo;

        $message->productId = (string)$procesInfo->functie->productId;
        $message->contextId = (string)$procesInfo->functie->contextId;
        $message->functieId = (string)$procesInfo->functie->functieId;

        return $message;
    }

}
