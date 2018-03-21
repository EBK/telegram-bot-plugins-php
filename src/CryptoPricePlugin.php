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
 * @copyright   2018 Arda Kilicdagi. (https://arda.pw/)
 * @license     http://opensource.org/licenses/MIT - MIT License
 */

namespace Teleplugins;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;

class CryptoPricePlugin
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
     */
    public function setResponse()
    {
        if (!$this->isCommand()) {
            return false;
        }

        $client = new Client();
        // Default output (fallback) message
        $output = 'Coin not found';
        try {
            $result = $client->request('GET', 'https://api.coinmarketcap.com/v1/ticker/?limit=0&convert=ETH');
        } catch (RequestException $e) {
            $output = 'Error';
            if ($e->hasResponse()) {
                $output .= ' : ' . Psr7\str($e->getResponse());
            }
        }

        // If the result variable is set, and the request is without error:
        if (isset($result)) {
            $pricesJson = $result->getBody()->getContents();
            $pricesArray = json_decode($pricesJson, true);

            foreach ($pricesArray as $coin) {
                if ($coin['symbol'] === strtoupper(trim($this->rawInput))) {
                    $output = 'Coin: ' . $coin['name'] . ' (' . $coin['symbol'] . ")\n";
                    $output .= 'Price: ' . $coin['price_btc'] . ' BTC | ' . $coin['price_eth'] . ' ETH | ' . $coin['price_usd'] . " USD\n";
                    $output .= 'Changes: 1H: ' . ($coin['percent_change_1h'] > 0 ? '+' : '') . $coin['percent_change_1h'] . '% | ' .
                        '24H: ' . ($coin['percent_change_24h'] > 0 ? '+' : '') . $coin['percent_change_24h'] . '% | ' .
                        '7D: ' . ($coin['percent_change_7d'] > 0 ? '+' : '') . $coin['percent_change_7d'] . "%\n";

                    break;
                }
            }
        }


        //For what to return, you can refer to Telebot.php or Telegram API
        return [
            'name' => 'text',
            'contents' => $output,
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
