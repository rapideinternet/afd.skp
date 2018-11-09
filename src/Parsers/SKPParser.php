<?php

namespace SIVI\AFDSKP\Parsers;

use SIVI\AFD\Processors\Content\TwoPassProcessor;
use SIVI\AFDSKP\Enums\SKPActionTypes;
use SIVI\AFDSKP\Exceptions\NotImplementedException;
use SIVI\AFDSKP\Models\Message;
use SIVI\AFDSKP\Models\Process;
use SIVI\AFDSKP\Models\ProcessMessage;
use SIVI\AFDSKP\Models\SKPAction;
use SIVI\AFDSKP\Parsers\Contracts\SKPParser as SKPParserContract;
use SIVI\AFDSKP\Services\ParserService;

class SKPParser implements SKPParserContract
{
    /**
     * @var ParserService
     */
    private $parserService;
    /**
     * @var TwoPassProcessor
     */
    private $processor;

    /**
     * XMLParser constructor.
     * @param
     */
    public function __construct(TwoPassProcessor $processor)
    {
        $this->processor = $processor;
    }

    /**
     * @param $xmlString
     * @return Message
     * @throws \SIVI\AFDSKP\Exceptions\NotImplementedException
     */
    public function parse($xmlString): SKPAction
    {
        $xml = simplexml_load_string($xmlString);

        //$parser = $this->parserService->getParserByName($xml->getName());

        return $this->parseAction($xml);
    }

    private function parseAction(\SimpleXMLElement $nodes): SKPAction
    {
        $action = new SKPAction();

        if (SKPActionTypes::isValid($nodes->children()[0]->getName())) {
            $action->type = $nodes->children()[0]->getName();
        } else {
            throwException(new NotImplementedException());
        }

        $action->message = $this->parseMessage($nodes->children()[0]);

        return $action;
    }

    public function parseMessage(\SimpleXMLElement $nodes): Message
    {
        $message = new Message();

        $message = $this->setProcessInfo($message, $nodes);
        $message = $this->setContent($message, $nodes);
        $message = $this->setResponse($message, $nodes);

        return $message;
    }

    private function setProcessInfo(Message $message, \SimpleXMLElement $nodes): Message
    {
        $procesInfo = $nodes->procesInfo;

        $newProcessInfo = new Process();

        echo var_dump($nodes);

        $newProcessInfo->procesId = (string)$procesInfo->procesId;
        $newProcessInfo->procesStatus = (string)$procesInfo->procesStatus;
        $newProcessInfo->procesOmschrijving = (string)$procesInfo->procesOmschrijving;
        $newProcessInfo->productId = (string)$procesInfo->functie->productId;
        $newProcessInfo->contextId = (string)$procesInfo->functie->contextId;
        $newProcessInfo->functieId = (string)$procesInfo->functie->functieId;

        $message->processInfo = $newProcessInfo;

        return $message;
    }

    private function setContent(Message $message, \SimpleXMLElement $nodes): Message
    {
        if(isset($nodes->inhoud)) {
            if (isset($nodes->inhoud->gimData)) {
                $message->content = $this->processor->process('edi', (string)$nodes->inhoud->gimData);
                $message->rawContent = (string)$nodes->inhoud->gimData;
            }
            if ($nodes->inhoud->meldingen) {
                foreach ($nodes->inhoud->meldingen as $item){
                    $item = $item->item;
                    $procesMessage = new ProcessMessage();
                    $procesMessage->type = (string)$item->soort;
                    $procesMessage->code = (int)$item->meldingCode;
                    $procesMessage->message = (string)$item->tekst;
                    $procesMessage->details = (string)$item->toelichting;
                    if ($procesMessage->type == "Error") {
                        $message->errors[] = $procesMessage;
                    }else{
                        $message->messages[] = $procesMessage;
                    }
                }
            }
        }

        return $message;
    }

    private function setResponse(Message $message, \SimpleXMLElement $nodes): Message
    {
        if(isset($nodes->resultatenOverzicht)){
            foreach ($nodes->resultatenOverzicht as $item){
                $message->results[] = $this->parseMessage($item->children()[0]);
            }
        }

        return $message;
    }

}
