<?php

namespace Fpradas\TelegramBotBundle\Objects;

use Fpradas\TelegramBotBundle\Objects\Abstracts\AbstractObject;

/**
 * Class Document
 *
 * @package Telegram\Bot\Objects
 *
 * @method string       getFileId()     Unique file identifier.
 * @method PhotoSize    getThumb()      (Optional). Document thumbnail as defined by sender.
 * @method string       getFileName()   (Optional). Original filename as defined by sender.
 * @method string       getMimeType()   (Optional). MIME type of the file as defined by sender.
 * @method int          getFileSize()   (Optional). File size.
 */
class Document extends AbstractObject
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
