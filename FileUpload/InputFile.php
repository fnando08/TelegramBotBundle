<?php

namespace Fpradas\TelegramBotBundle\FileUpload;

use GuzzleHttp\Psr7;
use Fpradas\TelegramBotBundle\Exceptions\TelegramSDKException;

/**
 * Class InputFile
 *
 * @package Telegram\Bot\FileUpload
 */
class InputFile
{
    /**
     * @var string The path to the file on the system.
     */
    protected $path;

    /**
     * @var resource The stream pointing to the file.
     */
    protected $stream;

    /**
     * Creates a new InputFile entity.
     *
     * @param string $filePath
     *
     * @throws TelegramSDKException
     */
    public function __construct($filePath)
    {
        $this->path = $filePath;
    }

    /**
     * Return the name of the file.
     *
     * @return string
     */
    public function getFileName()
    {
        return basename($this->path);
    }

    /**
     * Opens file stream.
     *
     * @return resource
     *
     * @throws TelegramSDKException
     */
    public function open()
    {
        if (is_resource($this->path)) {
            return $this->path;
        }

        if (!$this->isRemoteFile() && !is_readable($this->path)) {
            throw new TelegramSDKException('Failed to create InputFile entity. Unable to read resource: '.$this->path.'.');
        }

        return Psr7\try_fopen($this->path, 'r');
    }

    /**
     * Returns true if the path to the file is remote.
     *
     * @return bool
     */
    protected function isRemoteFile()
    {
        return preg_match('/^(https?|ftp):\/\/.*/', $this->path) === 1;
    }
}