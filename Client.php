<?php

namespace Fpradas\TelegramBotBundle;

use Camel\Format\StudlyCaps;
use Fpradas\TelegramBotBundle\Objects\Update;
use Fpradas\TelegramBotBundle\Objects\Message;
use Fpradas\TelegramBotBundle\FileUpload\InputFile;
use Fpradas\TelegramBotBundle\Exceptions\TelegramSDKException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;


/**
 * Class Client
 * @package Fpradas\TelegramBotBundle
 */
class Client
{
    const BASE_URL = 'https://api.telegram.org/bot';

    /**
     * @var string Version number of the Telegram Bot PHP SDK.
     */
    const VERSION = '1.0.0';

    /**
     * @var \GuzzleHttp\Client
     */
    protected $httpClient;

    /**
     * @var
     */
    protected $accessToken;

    /**
     * @var
     */
    protected $lastResponse;

    /**
     * @var bool
     */
    protected $isAsyncRequest = false;

    /**
     * @param $token
     * @param bool|false $async
     */
    public function __construct(
        $token,
        $async = false)
    {
        $this->accessToken = $token;

        if (isset($async)) {
            $this->setAsyncRequest($async);
        }

        $this->httpClient = new \GuzzleHttp\Client();
    }


    /**
     * @return string
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * @return TelegramResponse|null
     */
    public function getLastResponse()
    {
        return $this->lastResponse;
    }

    /**
     * @param $accessToken
     *
     * @return $this
     */
    public function setAccessToken($accessToken)
    {
        if (is_string($accessToken)) {
            $this->accessToken = $accessToken;

            return $this;
        }

        throw new \InvalidArgumentException('The Telegram bot access token must be of type "string"');
    }

    /**
     * @param $isAsyncRequest
     *
     * @return $this
     */
    public function setAsyncRequest($isAsyncRequest)
    {
        $this->isAsyncRequest = $isAsyncRequest;

        return $this;
    }

    /**
     * Check if this is an asynchronous request (non-blocking).
     *
     * @return bool
     */
    public function isAsyncRequest()
    {
        return $this->isAsyncRequest;
    }


    public function sendRequest(
        $method,
        $endpoint,
        array $params = []
    )
    {
        $request = $this->createRequest($method, $endpoint, $params);

        list(
            $url,
            $method,
            $headers,
            $isAsyncRequest
            ) = $this->prepareRequest($request);

        $timeOut = 10;

        if ($method === 'POST') {
            $options = $request->getPostParams();
//            ld($url, $method, $headers, $options, $timeOut, $isAsyncRequest);
        } else {
            $options = ['query' => $request->getParams()];
        }


        $rawResponse = $this->send($url, $method, $headers, $options, $timeOut, $isAsyncRequest);

        $returnResponse = $this->getResponse($request, $rawResponse);

        if ($returnResponse->isError()) {
            throw $returnResponse->getThrownException();
        }

        return $this->lastResponse = $returnResponse;
    }

    /**
     * Creates response object.
     *
     * @param TelegramRequest                    $request
     * @param ResponseInterface|PromiseInterface $response
     *
     * @return TelegramResponse
     */
    protected function getResponse(TelegramRequest $request, $response)
    {
        return new TelegramResponse($request, $response);
    }


    /**
     * Determine if a given type is the message.
     *
     * @param string         $type
     * @param Update|Message $object
     *
     * @return bool
     */
    public function isMessageType($type, $object)
    {
        if ($object instanceof Update) {
            $object = $object->getMessage();
        }

        if ($object->has(strtolower($type))) {
            return true;
        }

        return $this->detectMessageType($object) === $type;
    }

    /**
     * Detect Message Type Based on Update or Message Object.
     *
     * @param Update|Message $object
     *
     * @return string|null
     */
    public function detectMessageType($object)
    {
        if ($object instanceof Update) {
            $object = $object->getMessage();
        }

        $types = ['audio', 'document', 'photo', 'sticker', 'video', 'voice', 'contact', 'location', 'text'];

        return $object
            ->keys()
            ->intersect($types)
            ->pop();
    }

    /**
     * Sends a GET request to Telegram Bot API and returns the result.
     *
     * @param string $endpoint
     * @param array  $params
     *
     * @return TelegramResponse
     *
     * @throws TelegramSDKException
     */
    protected function get($endpoint, $params = [])
    {
        return $this->sendRequest(
            'GET',
            $endpoint,
            $params
        );
    }

    /**
     * Sends a POST request to Telegram Bot API and returns the result.
     *
     * @param string $endpoint
     * @param array  $params
     * @param bool   $fileUpload Set true if a file is being uploaded.
     *
     * @return TelegramResponse
     */
    public function post($endpoint, array $params = [], $fileUpload = false)
    {
        if ($fileUpload) {
            $params = ['multipart' => $params];
        } else {
            $params = ['form_params' => $params];
        }

        return $this->sendRequest(
            'POST',
            $endpoint,
            $params
        );
    }

    /**
     * Prepares the API request for sending to the client handler.
     *
     * @param TelegramRequest $request
     *
     * @return array
     */
    public function prepareRequest(TelegramRequest $request)
    {
        $url = 'https://api.telegram.org/bot'.$request->getAccessToken().'/'.$request->getEndpoint();

        return [
            $url,
            $request->getMethod(),
            $request->getHeaders(),
            $request->isAsyncRequest(),
        ];
    }

    /**
     * Sends a multipart/form-data request to Telegram Bot API and returns the result.
     * Used primarily for file uploads.
     *
     * @param string $endpoint
     * @param array  $params
     *
     * @return Message
     *
     * @throws TelegramSDKException
     */
    public function uploadFile($endpoint, array $params = [])
    {
        $i = 0;
        $multipart_params = [];
        foreach ($params as $name => $contents) {

            if (is_null($contents)) {
                continue;
            }

            if (!is_resource($contents)) {
                if (is_string($contents) && strlen($contents) <= 1024 && is_file($contents)) {
                    $contents =(new InputFile($contents))->open();
                } else {
                    $contents = (string)$contents;
                }
            }

            $multipart_params[$i]['name'] = $name;
            $multipart_params[$i]['contents'] = $contents;
            ++$i;
        }


        $response = $this->post($endpoint, $multipart_params, true);

        return $response->getDecodedBody();
    }




    /**
     * Instantiates a new TelegramRequest entity.
     *
     * @param string $method
     * @param string $endpoint
     * @param array  $params
     *
     * @return TelegramRequest
     */
    protected function createRequest(
        $method,
        $endpoint,
        array $params = []
    ) {
        return new TelegramRequest(
            $this->getAccessToken(),
            $method,
            $endpoint,
            $params,
            $this->isAsyncRequest()
        );
    }

    /**
     * Magic method to process any "get" requests.
     *
     * @param $method
     * @param $arguments
     *
     * @return bool|TelegramResponse
     */
    public function __call($method, $arguments)
    {
        $action = substr($method, 0, 3);

        if ($action === 'get') {

            /** @noinspection PhpUndefinedFunctionInspection */
            $transformer = new StudlyCaps();
            $class_name = $transformer->join([substr($method, 3)]);
            $class = 'Telegram\Bot\Objects\\'.$class_name;
            $response = $this->post($method, $arguments[0] ?: []);

            if (class_exists($class)) {
                return new $class($response->getDecodedBody());
            }

            return $response;
        }

        return false;
    }

    /**
     * @var PromiseInterface[]
     */
    private static $promises = [];

    /**
     * @inheritdoc
     */
    public function send(
        $url,
        $method,
        array $headers = [],
        array $options = [],
        $timeOut = 30,
        $isAsyncRequest = false
    ) {
        $body = isset($options['body']) ? $options['body'] : null;
        $options = $this->getOptions($headers, $body, $options, $timeOut, $isAsyncRequest);

        try {
            $response = $this->httpClient->requestAsync($method, $url, $options);

            if ($isAsyncRequest) {
                self::$promises[] = $response;
            } else {
                $response = $response->wait();
            }
        } catch (RequestException $e) {
            $response = $e->getResponse();
            if (!$response instanceof ResponseInterface) {
                throw new TelegramSDKException($e->getMessage(), $e->getCode());
            }
        }

        return $response;
    }

    /**
     * Prepares and returns request options.
     *
     * @param array $headers
     * @param       $body
     * @param       $options
     * @param       $timeOut
     * @param       $isAsyncRequest
     *
     * @return array
     */
    private function getOptions(array $headers, $body, $options = [], $timeOut, $isAsyncRequest = false)
    {
        $default_options = [
            RequestOptions::HEADERS => $headers,
            RequestOptions::BODY => $body,
            RequestOptions::TIMEOUT => $timeOut,
            RequestOptions::CONNECT_TIMEOUT => 10,
            RequestOptions::SYNCHRONOUS => !$isAsyncRequest,
        ];

        return array_merge($default_options, $options);
    }
}
