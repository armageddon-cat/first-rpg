<?php
declare(strict_types=1);

namespace app;

use app\exceptions\EmptyPayLoadException;
use app\exceptions\JsonDecodingException;

class ClientMessageContainer
{
    private $data;
    /**
     * @var string
     */
    private $payLoad;

    public function __construct(array $decodedMessage)
    {
        $this->setPayLoad($decodedMessage);
        $payLoadObject = $this->jsonDecodePayLoad($this->getPayLoad());
        $this->setData($payLoadObject);
    }
    
    public function getData(): \stdClass
    {
        return $this->data;
    }
    
    protected function setData(\stdClass $payLoadObject): void
    {
        $this->data = $payLoadObject;
    }

    protected function getPayLoad(): string
    {
        return $this->payLoad;
    }
    
    protected function setPayLoad(array $decodedMessage): void
    {
        if (empty($decodedMessage['payload'])) {
            throw new EmptyPayLoadException();
        }
        $this->payLoad = $decodedMessage['payload'];
    }
    
    protected function jsonDecodePayLoad(string $getPayLoad): \stdClass
    {
        $decodedPayLoad = json_decode($getPayLoad);
        if ($decodedPayLoad === null) {
            throw new JsonDecodingException(json_last_error());
        }
        return $decodedPayLoad;
    }
}
