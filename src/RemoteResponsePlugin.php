<?php

/**
 * Telebot2
 * https://github.com/Ardakilic/Telebot2
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @link        https://github.com/Ardakilic/Telebot2
 *
 * @copyright   2016 Arda Kilicdagi. (https://arda.pw/)
 * @license     http://opensource.org/licenses/MIT - MIT License
 */

namespace Teleplugins;

use GuzzleHttp\Client;

class RemoteResponsePlugin
{
    private $responseData;
    private $request;
    private $config;
    private $rawInput; //User's input
    // $config['global_config'] returns the Lumen configuration array
    // $config['global_env'] returns the environment variables

    public function __construct($responseData, $request, $config, $rawInput)
    {
        $this->responseData = $responseData;
        $this->request = $request;
        $this->config = $config;
        $this->rawInput = $rawInput;
    }

    /**
     * The response data for Telegram API
     *
     * @return array
     * @throws \Exception
     */
    public function setResponse()
    {
        if (!$this->isCommand()) {
            throw new \Exception('Bot response should be command');
        }

        $client = new Client(['base_uri' => config('REMOTE_RESPONSE_PLUGIN_BASE_URL', 'https://our-super-secret-url.com')]);
        $response = trim(strip_tags($client->request('GET', sprintf(config('REMOTE_RESPONSE_PLUGIN_ENDPOINT', '/our/super/endpoint/text=%s'), urlencode($this->rawInput)))));

        //Old way:
        //$text = trim(strip_tags(file_get_contents('https://our-super-secret-url.com/our-super-secret-endpoint?text='.urlencode($this->rawInput))));

        //For what to return, you can refer to Telebot.php or Telegram API
        return [
            'name' => 'text',
            'contents' => $response,
        ];
    }


    /**
     * The endpoint of Telegram, this defines how the message will be sent
     *
     * @return string
     */
    public function setEndpoint()
    {
        return 'sendMessage';
    }


    /**
     * This returns whether the request is through a command or not.
     *
     * @return bool
     */
    private function isCommand()
    {
        return isset($this->request['message']['entities']) && $this->request['message']['entities'][0]['type'] == 'bot_command';
    }
}
