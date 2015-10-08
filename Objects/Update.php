<?php

namespace Fpradas\TelegramBotBundle\Objects;

use Fpradas\TelegramBotBundle\Objects\Abstracts\AbstractObject;

/**
 * Class Update
 * @package Fpradas\TelegramBotBundle\Objects
 */
class Update extends AbstractObject
{
    /**
     * @inheritdoc
     */
    public function getRelations()
    {
        return [
            'message' => Message::class,
        ];
    }

    /**
     * Get recent message.
     *
     * @return Update
     */
    public function recentMessage()
    {
        return new static($this->last());
    }
}
