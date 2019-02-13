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
 * @copyright   2019 Arda Kilicdagi. (https://arda.pw/)
 * @license     http://opensource.org/licenses/MIT - MIT License
 */

namespace Teleplugins;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;

class CryptoRoiPlugin
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
            $result = $client->request('GET', 'https://tokenstats.io/api/v1/roi?symbol='.trim($this->rawInput));
        } catch (RequestException $e) {
            $output = 'Error';
            if ($e->hasResponse()) {
                $output .= ' : ' . Psr7\str($e->getResponse());
            }
        }

        if($result->getStatusCode() === 200) {
            $roiData = json_decode($result->getBody()->getContents(), true);
            $output = 'Token Name: '.$roiData['name'].'('.$roiData['description'].')'."\n";
            $output .= 'Token Symbol: '.$roiData['symbol']."\n";
            $output .= 'BTC price at launch: '.$roiData['btc_price_at_launch']."\n";
            $output .= 'ETH price at launch: '.$roiData['eth_price_at_launch']."\n";
            $output .= 'USD price at launch: '.$roiData['usd_price_at_launch']."\n";

            $output .= 'BTC price at presale: '.$roiData['btc_price_at_presale']."\n";
            $output .= 'ETH price at presale: '.$roiData['eth_price_at_presale']."\n";
            $output .= 'USD price at presale: '.$roiData['usd_price_at_presale']."\n";

            $output .= 'BTC price current: '.$roiData['current_btc_price']."\n";
            $output .= 'ETH price current: '.$roiData['current_eth_price']."\n";
            $output .= 'USD price current: '.$roiData['current_usd_price']."\n";

            $output .= 'ROI BTC: '.$roiData['roi_btc']."\n";
            $output .= 'ROI ETH: '.$roiData['roi_eth']."\n";
            $output .= 'ROI USD: '.$roiData['roi_usd']."\n";
        } else {
            $output = 'error';
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
