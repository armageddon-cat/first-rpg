<?php
declare(strict_types=1);

namespace app;

use app\exceptions\EmptyValueException;
use app\exceptions\InvalidDateTimeFormatException;
use app\exceptions\InvalidGuidException;
use app\exceptions\JsonDecodingException;
use app\tools\DateTimeHelper;

/**
 * Class ServerActions
 * @package Tanks
 */
class ServerActions
{
    /**
     * When new tank come add it to registry and send tank to everyone
     *
     * @param resource $connect
     *
     * @throws \Exception
     */
    public static function onOpen($connect): void
    {
        var_dump('connection opened');
        $now  = DateTimeHelper::createDateTimeMicro();
//        $tank = new Tank($now);
//        TankRegistry::add($tank);
//        $tankString = $tank->prepareToClientJson();
//        fwrite($connect, WebSocket::encode($tankString));
    }
    
    public static function onClose(): void
    {
        var_dump('connection lost, sorry');
        // todo in future maybe unset tank here
    }

    /**
     * everyone send only its data
     *
     * @param resource  $connect
     * @throws \Exception
     */
    public static function onMessage($connect, string $data, \DateTime $serverTime): void
    {
        $decMessage = WebSocket::decode($data);
        if (!$decMessage) {
            var_dump('cannot decode data');
            
            return;
        }
        if (isset($decMessage['type']) && $decMessage['type'] === 'close') {
            var_dump('connection closed');
    
            return;
        }
        
        try {
            $message = new ClientMessageContainer($decMessage);
        } catch (EmptyValueException | JsonDecodingException | InvalidGuidException | InvalidDateTimeFormatException $e) {
            var_dump($e);
            
            return;
        }
//        // create bullet if exists
//        Bullet::create($message);
//        // move tank if tank has new direction
//        TankRegistry::moveEach($serverTime, $message);
//        // make shooting for each present bullet, checking hit
//        BulletRegistry::fireEach();
//        // move one step forward each existing bullet
//        BulletRegistry::moveBullets();
//        // now we can send result back
//        $storage = TankRegistry::getStorageJSON();
//        $encMessage = WebSocket::encode($storage);
//        fwrite($connect, $encMessage);
    }
}
