<?php

namespace Fpradas\TelegramBotBundle\Objects;

use Fpradas\TelegramBotBundle\Objects\Abstracts\AbstractObject;

/**
 * Class Contact
 *
 * @package Telegram\Bot\Objects
 *
 * @method string   getPhoneNumber()    Contact's phone number.
 * @method string   getFirstName()      Contact's first name.
 * @method string   getLastName()       (Optional). Contact's last name.
 * @method int      getUserId()         (Optional). Contact's user identifier in Telegram.
 */
class Contact extends AbstractObject
{
    /**
     * @inheritdoc
     */
    public function getRelations()
    {
        return [];
    }
}
