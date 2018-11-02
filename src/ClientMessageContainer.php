<?php
declare(strict_types=1);

namespace app;

use app\exceptions\EmptyPayLoadException;
use app\exceptions\EmptyValueException;
use app\exceptions\InvalidDateTimeFormatException;
use app\exceptions\JsonDecodingException;
use app\tools\DateTimeHelper;
use app\validators\UnixTimeStampFloatValidator;

class ClientMessageContainer
{
    /**
     * @var string
     */
    private $id;
    /**
     * @var \DateTime
     */
    private $time;
    /**
     * @var string
     */
    private $payLoad;

    public function __construct(array $decodedMessage)
    {
        $this->setPayLoad($decodedMessage);
        $payLoadObject = $this->jsonDecodePayLoad($this->getPayLoad());
        $this->setId($payLoadObject);
        $this->setTime($payLoadObject);
    }
    
    public function getId(): string
    {
        return $this->id;
    }
    
    protected function setId(\stdClass $payLoadObject): void
    {
        if (empty($payLoadObject->id)) {
            throw new EmptyValueException('id');
        }
        $this->id = (string)$payLoadObject->id;
    }
    
    public function getTime(): \DateTime
    {
        return $this->time;
    }

    /**
     * maybe fail when milliseconds is null
     */
    protected function setTime(\stdClass $payLoadObject): void
    {
        if (!isset($payLoadObject->time) || !UnixTimeStampFloatValidator::validate($payLoadObject->time)) {
            throw new InvalidDateTimeFormatException();
        }
        $this->time = \DateTime::createFromFormat(DateTimeHelper::UNIX_TIMESTAMP_MICROSECONDS, (string)$payLoadObject->time); // todo check this
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
