<?php

namespace EvolutionCMS\Example;


/**
 * Class ExampleApiController
 * @package EvolutionCMS\Custom
 */
class ExampleApiController
{
    /**
     * @var \DocumentParser
     */
    private $modx;

    /**
     * ExampleApiController constructor.
     */
    function __construct()
    {
        $this->modx = EvolutionCMS();
    }

    /**
     * @param int $code
     * @param array $headers
     * @param mixed $body
     */
    private function Response($code = 200, $headers = ['Content-Type' => 'application/json'], $body = '')
    {
        header('HTTP/1.1 '.$code );
        foreach($headers as $k => $v){
            header($k.': '.$v);
            if($k == 'Content-Type' && $v == 'application/json'){
                $body = json_encode($body, JSON_UNESCAPED_UNICODE);
            }
        }
        echo $body;
    }

    /**
     * @param string $id
     * @throws \AgelxNash\Modx\Evo\Database\Exceptions\InvalidFieldException
     * @throws \AgelxNash\Modx\Evo\Database\Exceptions\TableNotDefinedException
     * @throws \AgelxNash\Modx\Evo\Database\Exceptions\UnknownFetchTypeException
     */
    public function getDocument($id = '0')
    {
        $body = $this->modx->getDocument($id);
        $code = ($body == false) ? 404 : 200;
        $this->Response($code, ['Content-Type' => 'application/json'], $body);
    }


}