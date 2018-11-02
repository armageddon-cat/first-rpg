<?php
declare(strict_types=1);

namespace app;

use app\exceptions\EmptyValueException;
use app\exceptions\InvalidDateTimeFormatException;
use app\exceptions\InvalidGuidException;
use app\exceptions\JsonDecodingException;
use app\tools\DateTimeHelper;

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
        $map = new Map();
        $string = $map->prepareJsonToClient();
//        var_dump($string);
        fwrite($connect, WebSocket::encode($string));
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

        try {
            $message = new ClientMessageContainer($decMessage);
        } catch (EmptyValueException | JsonDecodingException | InvalidGuidException | InvalidDateTimeFormatException $e) {
            var_dump($e);
            
            return;
        }
        $move = new Move($message);
        $map = Map::getInstance();
        if ($move->isAllowed()) {
            $map->player->makeMove($move);
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
        $string = $map->prepareJsonToClient();
        fwrite($connect, WebSocket::encode($string));
    }
}
