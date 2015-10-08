<?php

namespace Fpradas\TelegramBotBundle\Objects;

use Fpradas\TelegramBotBundle\Objects\Abstracts\AbstractObject;

/**
 * Class Audio
 *
 * @package Telegram\Bot\Objects
 *
 * @method string   getFileId()     Unique identifier for this file.
 * @method int      getDuration()   Duration of the audio in seconds as defined by sender.
 * @method string   getPerformer()  (Optional). Performer of the audio as defined by sender or by audio tags.
 * @method string   getTitle()      (Optional). Title of the audio as defined by sender or by audio tags.
 * @method string   getMimeType()   (Optional). MIME type of the file as defined by sender.
 * @method int      getFileSize()   (Optional). File size.
 */
class Audio extends AbstractObject
{
    /**
     * @inheritdoc
     */
    public function getRelations()
    {
        return [];
    }
}
