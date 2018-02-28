Telegram Bot Plugins
--------
These PHP Classes are the bots we're using in our Telegram group. These bots are created as plugins to [Telebot2](https://github.com/Ardakilic/Telebot2)

You can easily add them to your Telebot2 installation by simply requiring this repo like this in your `composer.json`:

```json
"repositories": [
  { "type": "vcs", "url": "https://github.com/EBK/telegram-bot-plugins-php" }
]
```

After that, simply add these to your `responses` rows in database.

```php
[
  'bot_id' => 1
  'command' => 'quote', //Without the leading slash
  'pattern' => '',
  'response_type' => 'external',
  'response_data' => 'RemoteResponsePlugin',
  'plugin_namespace' => 'Teleplugins', //These two lines will call \Teleplugins\RemoteResponsePlugin Class
  'as_quote' => 'y',
  'preview_links_if_any' => 'n',
  'created_at' => date('Y-m-d H:i:s'),
  'updated_at' => date('Y-m-d H:i:s'),
],

[
  'bot_id' => 2
  'command' => 'chart', //Without the leading slash
  'pattern' => '',
  'response_type' => 'external',
  'response_data' => 'CryptoChartsPlugin',
  'plugin_namespace' => 'Teleplugins', //These two lines will call \Teleplugins\CryptoChartsPlugin Class
  'as_quote' => 'n',
  'preview_links_if_any' => 'n',
  'created_at' => date('Y-m-d H:i:s'),
  'updated_at' => date('Y-m-d H:i:s'),
],
[
  'bot_id' => 3
  'command' => 'calc', //Without the leading slash
  'pattern' => '',
  'response_type' => 'external',
  'response_data' => 'MathParserPlugin',
  'plugin_namespace' => 'Teleplugins', //These two lines will call \Teleplugins\MathParserPlugin Class
  'as_quote' => 'y',
  'preview_links_if_any' => 'n',
  'created_at' => date('Y-m-d H:i:s'),
  'updated_at' => date('Y-m-d H:i:s'),
],

```

And you're set.

Thanks
--------
* [Frank Wikström
 (mossadal)](https://github.com/mossadal) for his awesome [Math Parser Plugin](https://github.com/mossadal/math-parser)
* [Cryptohistory](https://cryptohistory.org/), as we're powering our Remote Responses Plugin from their service.

Donations
--------
Although non mandatory, we'd appreciate if you could buy us some beer, coffee, or whatever you choose. You can find the addresses below:

**BTC:** 1GTN57oRULcfbjc3Y36too4ZEWkpFifDud

**ETH:** TODO

**LTC:** TODO

**NEO** TODO