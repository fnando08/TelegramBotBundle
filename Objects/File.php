<?php

namespace Fpradas\TelegramBotBundle\Objects;

use Fpradas\TelegramBotBundle\Objects\Abstracts\AbstractObject;

/**
 * Class File
 *
 * @package Telegram\Bot\Objects
 *
 * @method string   getFileId()     Unique identifier for this file.
 * @method int      getFileSize()   (Optional). File size, if known.
 * @method string   getFilePath()   (Optional). File path. Use https://api.telegram.org/file/bot<token>/<file_path> to get the file.
 */
class File extends AbstractObject
{
    /**
     * @inheritdoc
     */
    public function getRelations()
    {
        return [];
    }
}
