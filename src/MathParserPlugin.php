<?php

/**
 * Telebot2
 * https://github.com/Ardakilic/Telebot2.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @link        https://github.com/Ardakilic/Telebot2
 *
 * @copyright   2016 Arda Kilicdagi. (https://arda.pw/)
 * @license     http://opensource.org/licenses/MIT - MIT License
 */

namespace Telebot\Plugins;

use PHPMathParser\Math;

class MathParserPlugin
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
     * @return array|bool The response data
     * @throws \Exception
     */
    public function setResponse()
    {
        if (!$this->isCommand()) {
            throw new \Exception('Bot response should be command');
        }

        $math = new Math();
        $response = $math->evaluate($this->rawInput);


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
