<?php

namespace Fpradas\TelegramBotBundle\Objects;

use Fpradas\TelegramBotBundle\Objects\Abstracts\AbstractObject;

/**
 * Class Location
 *
 * @package Telegram\Bot\Objects
 *
 * @method float    getLongitude()  Longitude as defined by sender.
 * @method float    getLatitude()   Latitude as defined by sender.
 */
class Location extends AbstractObject
{
    /**
     * @inheritdoc
     */
    public function getRelations()
    {
        return [];
    }
}
