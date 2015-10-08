<?php

namespace Fpradas\TelegramBotBundle;

use Fpradas\TelegramBotBundle\Exceptions\TelegramSDKException;
use Fpradas\TelegramBotBundle\Objects\Message;
use Fpradas\TelegramBotBundle\Objects\Update;
use Fpradas\TelegramBotBundle\Objects\UserProfilePhotos;
use Symfony\Component\Security\Core\User\User;

/**
 * Class Bot
 * @package Fpradas\TelegramBotBundle
 */
class Bot
{

    /**
     * @var Client
     */
    private $client;

    /**
     * Bot constructor.
     *
     * @param $token
     */
    public function __construct($token)
    {
        $this->client = new Client($token);
    }


    /**
     * A simple method for testing your bot's auth token.
     * Returns basic information about the bot in form of a User object.
     *
     * @link https://core.telegram.org/bots/api#getme
     *
     * @return User
     */
    public function getMe()
    {
        $response = $this->client->post('getMe');

        return new User($response->getDecodedBody());
    }

    /**
     * Send text messages.
     *
     * @link https://core.telegram.org/bots/api#sendmessage
     *
     * @param int    $chat_id
     * @param string $text
     * @param bool   $disable_web_page_preview
     * @param int    $reply_to_message_id
     * @param string $reply_markup
     *
     * @return Message
     */
    public function sendMessage(
        $chat_id,
        $text,
        $disable_web_page_preview = false,
        $reply_to_message_id = null,
        $reply_markup = null
    ) {
        $params = compact('chat_id', 'text', 'disable_web_page_preview', 'reply_to_message_id', 'reply_markup');
        $response = $this->client->post('sendMessage', $params);

        return new Message($response->getDecodedBody());
    }

    /**
     * Forward messages of any kind.
     *
     * @link https://core.telegram.org/bots/api#forwardmessage
     *
     * @param int $chat_id
     * @param int $from_chat_id
     * @param int $message_id
     *
     * @return Message
     */
    public function forwardMessage($chat_id, $from_chat_id, $message_id)
    {
        $params = compact('chat_id', 'from_chat_id', 'message_id');
        $response = $this->client->post('forwardMessage', $params);

        return new Message($response->getDecodedBody());
    }

    /**
     * Send Photos.
     *
     * @link https://core.telegram.org/bots/api#sendphoto
     *
     * @param int    $chat_id
     * @param string $photo
     * @param string $caption
     * @param int    $reply_to_message_id
     * @param string $reply_markup
     *
     * @return Message
     */
    public function sendPhoto($chat_id, $photo, $caption = null, $reply_to_message_id = null, $reply_markup = null)
    {
        $params = compact('chat_id', 'photo', 'caption', 'reply_to_message_id', 'reply_markup');

        $response = $this->client->uploadFile('sendPhoto', $params);

        return new Message($response);
    }

    /**
     * Send regular audio files.
     *
     * @link https://core.telegram.org/bots/api#sendaudio
     *
     * @param int    $chat_id
     * @param string $audio
     * @param int    $duration
     * @param string $performer
     * @param string $title
     * @param int    $reply_to_message_id
     * @param string $reply_markup
     *
     * @return Message
     */
    public function sendAudio(
        $chat_id,
        $audio,
        $duration = null,
        $performer = null,
        $title = null,
        $reply_to_message_id = null,
        $reply_markup = null
    ) {
        $params = compact('chat_id', 'audio', 'duration', 'performer', 'title', 'reply_to_message_id', 'reply_markup');

        return $this->client->uploadFile('sendAudio', $params);
    }

    /**
     * Send voice audio files.
     *
     * @link https://core.telegram.org/bots/api#sendaudio
     *
     * @param int    $chat_id
     * @param string $voice
     * @param int    $duration
     * @param int    $reply_to_message_id
     * @param string $reply_markup
     *
     * @return Message
     */
    public function sendVoice($chat_id, $voice, $duration = null, $reply_to_message_id = null, $reply_markup = null)
    {
        $params = compact('chat_id', 'voice', 'duration', 'reply_to_message_id', 'reply_markup');

        return $this->client->uploadFile('sendVoice', $params);
    }

    /**
     * Send general files.
     *
     * @link https://core.telegram.org/bots/api#senddocument
     *
     * @param int    $chat_id
     * @param string $document
     * @param int    $reply_to_message_id
     * @param string $reply_markup
     *
     * @return Message
     */
    public function sendDocument($chat_id, $document, $reply_to_message_id = null, $reply_markup = null)
    {
        $params = compact('chat_id', 'document', 'reply_to_message_id', 'reply_markup');

        return $this->client->uploadFile('sendDocument', $params);
    }

    /**
     * Send .webp stickers.
     *
     * @link https://core.telegram.org/bots/api#sendsticker
     *
     * @param int    $chat_id
     * @param string $sticker
     * @param int    $reply_to_message_id
     * @param string $reply_markup
     *
     * @return Message
     *
     * @throws TelegramSDKException
     */
    public function sendSticker($chat_id, $sticker, $reply_to_message_id = null, $reply_markup = null)
    {
        if (is_file($sticker) && (pathinfo($sticker, PATHINFO_EXTENSION) !== 'webp')) {
            throw new TelegramSDKException('Invalid Sticker Provided. Supported Format: Webp');
        }

        $params = compact('chat_id', 'sticker', 'reply_to_message_id', 'reply_markup');

        return $this->client->uploadFile('sendSticker', $params);
    }

    /**
     * Send Video File, Telegram clients support mp4 videos (other formats may be sent as Document).
     *
     * @see  sendDocument
     * @link https://core.telegram.org/bots/api#sendvideo
     *
     * @param int    $chat_id
     * @param string $video
     * @param int    $duration
     * @param string $caption
     * @param int    $reply_to_message_id
     * @param string $reply_markup
     *
     * @return Message
     */
    public function sendVideo(
        $chat_id,
        $video,
        $duration = null,
        $caption = null,
        $reply_to_message_id = null,
        $reply_markup = null
    ) {
        $params = compact('chat_id', 'video', 'duration', 'caption', 'reply_to_message_id', 'reply_markup');

        return $this->client->uploadFile('sendVideo', $params);
    }

    /**
     * Send point on the map.
     *
     * @link https://core.telegram.org/bots/api#sendlocation
     *
     * @param int    $chat_id
     * @param float  $latitude
     * @param float  $longitude
     * @param int    $reply_to_message_id
     * @param string $reply_markup
     *
     * @return Message
     */
    public function sendLocation($chat_id, $latitude, $longitude, $reply_to_message_id = null, $reply_markup = null)
    {
        $params = compact('chat_id', 'latitude', 'longitude', 'reply_to_message_id', 'reply_markup');
        $response = $this->client->post('sendLocation', $params);

        return new Message($response->getDecodedBody());
    }

    /**
     * Broadcast a Chat Action.
     *
     * @link https://core.telegram.org/bots/api#sendchataction
     *
     * @param int    $chat_id
     * @param string $action
     *
     * @return TelegramResponse
     *
     * @throws TelegramSDKException
     */
    public function sendChatAction($chat_id, $action)
    {
        $validActions = [
            'typing',
            'upload_photo',
            'record_video',
            'upload_video',
            'record_audio',
            'upload_audio',
            'upload_document',
            'find_location',
        ];

        if (isset($action) && in_array($action, $validActions)) {
            return $this->client->post('sendChatAction', compact('chat_id', 'action'));
        }

        throw new TelegramSDKException('Invalid Action! Accepted value: '.implode(', ', $validActions));
    }

    /**
     * Returns a list of profile pictures for a user.
     *
     * @link https://core.telegram.org/bots/api#getuserprofilephotos
     *
     * @param int $user_id
     * @param int $offset
     * @param int $limit
     *
     * @return UserProfilePhotos
     */
    public function getUserProfilePhotos($user_id, $offset = null, $limit = null)
    {
        $response = $this->client->post('getUserProfilePhotos', compact('user_id', 'offset', 'limit'));

        return new UserProfilePhotos($response->getDecodedBody());
    }

    /**
     * Returns basic info about a file and prepare it for downloading.
     *
     * The file can then be downloaded via the link
     * https://api.telegram.org/file/bot<token>/<file_path>,
     * where <file_path> is taken from the response.
     *
     * @link https://core.telegram.org/bots/api#getFile
     *
     * @param string $file_id
     *
     * @return File
     */
    public function getFile($file_id)
    {
        $response = $this->client->post('getFile', compact('file_id'));

        return new File($response->getDecodedBody());
    }

    /**
     * Set a Webhook to receive incoming updates via an outgoing webhook.
     *
     * @link https://core.telegram.org/bots/api#setwebhook
     *
     * @param string $url         HTTPS url to send updates to.
     * @param string $certificate Upload your public key certificate so that the root certificate in use can be checked.
     *
     * @return TelegramResponse
     *
     * @throws TelegramSDKException
     */
    public function setWebhook($url, $certificate = null)
    {
        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            throw new TelegramSDKException('Invalid URL Provided');
        }

//        if (parse_url($url, PHP_URL_SCHEME) !== 'https') {
//            throw new TelegramSDKException('Invalid URL, should be a HTTPS url.');
//        }

        return $this->client->uploadFile('setWebhook', compact('url', 'certificate'));
    }

    /**
     * Returns webhook updates sent by Telegram.
     * Works only if you set a webhook.
     *
     * @see setWebhook
     *
     * @return Update
     */
    public function getWebhookUpdates()
    {
        $body = json_decode(file_get_contents('php://input'), true);

        return new Update($body);
    }

    /**
     * Removes the outgoing webhook (if any).
     *
     * @return TelegramResponse
     */
    public function removeWebhook()
    {
        $url = '';

        return $this->client->post('setWebhook', compact('url'));
    }



    /**
     * Use this method to receive incoming updates using long polling.
     *
     * @link https://core.telegram.org/bots/api#getupdates
     *
     * @param int|null $offset
     * @param int|null $limit
     * @param int|null $timeout
     *
     * @return Update[]
     */
    public function getUpdates($offset = null, $limit = null, $timeout = null)
    {
        $response = $this->client->post('getUpdates', compact('offset', 'limit', 'timeout'));
        $updates = $response->getDecodedBody();

        $data = [];
        foreach ($updates['result'] as $update) {
            $data[] = new Update($update);
        }

        return $data;
    }

    /**
     * Builds a custom keyboard markup.
     *
     * @link https://core.telegram.org/bots/api#replykeyboardmarkup
     *
     * @param array $keyboard
     * @param bool  $resize_keyboard
     * @param bool  $one_time_keyboard
     * @param bool  $selective
     *
     * @return string
     */
    public function replyKeyboardMarkup(
        $keyboard,
        $resize_keyboard = false,
        $one_time_keyboard = false,
        $selective = false
    ) {
        return json_encode(compact('keyboard', 'resize_keyboard', 'one_time_keyboard', 'selective'));
    }

    /**
     * Hide the current custom keyboard and display the default letter-keyboard.
     *
     * @link https://core.telegram.org/bots/api#replykeyboardhide
     *
     * @param bool $selective
     *
     * @return string
     */
    public static function replyKeyboardHide($selective = false)
    {
        $hide_keyboard = true;

        return json_encode(compact('hide_keyboard', 'selective'));
    }

    /**
     * Display a reply interface to the user (act as if the user has selected the bot‘s message and tapped ’Reply').
     *
     * @link https://core.telegram.org/bots/api#forcereply
     *
     * @param bool $selective
     *
     * @return string
     */
    public static function forceReply($selective = false)
    {
        $force_reply = true;

        return json_encode(compact('force_reply', 'selective'));
    }
}