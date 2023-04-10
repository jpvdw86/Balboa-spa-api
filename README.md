# Balboa spa / Jacuzzi API wrapper

This is a PHP Library for the Balboa SPA API. Its reverse engineered en converted to PHP library.
It is not an official API and no support on this library !

Example code

```php
$username = 'XXXX';
$password = 'XXXX';

$clientFactory = new \Jpvdw\Balboa\ClientFactory();
$client = $clientFactory->create($username,$password);

// Check capabilities
// Check Model/Device.php for all options
echo $device = $client->getDevice();
echo $device->hasPump0() ? 'Yes' : 'No'.PHP_EOL;
echo $device->hasPump1() ? 'Yes' : 'No'.PHP_EOL;
echo $device->hasLight1() ? 'Yes' : 'No'.PHP_EOL;
echo $device->hasBlower() ? 'Yes' : 'No'.PHP_EOL;


// Get current state data
// Check Model/Panel.php for all options
$panelData = $client->getPanel();
echo $panel->getTemperature().PHP_EOL;
echo $panel->getTargetTemperature().PHP_EOL;

// Control buttons
// Check Service/Buttons.php for al options
$buttons = $client->getButtonActions();
echo $buttons->toggleLights() ? 'done': 'error'.PHP_EOL;
echo $buttons->togglePump1() ? 'done': 'error'.PHP_EOL;
echo $buttons->toggleBlowers() ? 'done': 'error'.PHP_EOL;
echo $buttons->togglePump2() ? 'done': 'error'.PHP_EOL;


// Control Temperature
// Check Service/Temperature.php for al options
$temperature = $client->getTemperatureActions();
echo $temperature->setCelsius(35) ? 'done': 'error'.PHP_EOL;
echo $temperature->setFahrenheit(60) ? 'done': 'error'.PHP_EOL;

``