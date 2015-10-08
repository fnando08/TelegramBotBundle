<?php

namespace Fpradas\TelegramBotBundle\Objects;

use Fpradas\TelegramBotBundle\Objects\Abstracts\AbstractObject;

/**
 * Class UserProfilePhotos
 *
 * @package Telegram\Bot\Objects
 *
 * @method int          getTotalCount()     Total number of profile pictures the target user has.
 * @method PhotoSize[]  getPhotos()         Requested profile pictures (in up to 4 sizes each).
 */
class UserProfilePhotos extends AbstractObject
{
    /**
     * @inheritdoc
     */
    public function getRelations()
    {
        return [
            'photos' => PhotoSize::class,
        ];
    }
}
