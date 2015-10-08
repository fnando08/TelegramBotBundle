<?php

namespace Fpradas\TelegramBotBundle\Objects;

use Fpradas\TelegramBotBundle\Objects\Abstracts\AbstractObject;

/**
 * Class PhotoSize
 *
 * @package Telegram\Bot\Objects
 *
 * @method string   getFileId()     Unique identifier for this file.
 * @method int      getWidth()      Photo width.
 * @method int      getHeight()     Photo height.
 * @method int      getFileSize()   (Optional). File size.
 */
class PhotoSize extends AbstractObject
{
    /**
     * @inheritdoc
     */
    public function getRelations()
    {
        return [];
    }
}
