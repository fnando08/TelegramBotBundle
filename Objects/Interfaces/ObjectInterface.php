<?php

namespace Fpradas\TelegramBotBundle\Objects\Interfaces;

/**
 * Interface ObjectInterface
 * @package Fpradas\TelegramBotBundle\Objects\Interfaces
 */
interface ObjectInterface
{
    /**
     * Property relations.
     *
     * @return array
     */
    public function getRelations();
}