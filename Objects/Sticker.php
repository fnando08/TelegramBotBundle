<?php

namespace Fpradas\TelegramBotBundle\Objects;

use Fpradas\TelegramBotBundle\Objects\Abstracts\AbstractObject;

/**
 * Class Sticker
 *
 * @package Telegram\Bot\Objects
 *
 * @method string       getFileId()     Unique identifier for this file.
 * @method int          getWidth()      Sticker width.
 * @method int          getHeight()     Sticker height.
 * @method PhotoSize    getThumb()      (Optional). Sticker thumbnail in .webp or .jpg format.
 * @method int          getFileSize()   (Optional). File size.
 */
class Sticker extends AbstractObject
{
    /**
     * @inheritdoc
     */
    public function getRelations()
    {
        return [
            'thumb' => PhotoSize::class,
        ];
    }
}
