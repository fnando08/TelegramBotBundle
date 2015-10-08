<?php

namespace Fpradas\TelegramBotBundle\Objects;

use Fpradas\TelegramBotBundle\Objects\Abstracts\AbstractObject;

/**
 * Class GroupChat
 *
 * @package Telegram\Bot\Objects
 *
 * @method int      getId()     Unique identifier for this group chat.
 * @method string   getTitle()  Group name.
 */
class GroupChat extends AbstractObject
{
    /**
     * @inheritdoc
     */
    public function getRelations()
    {
        return [];
    }
}
