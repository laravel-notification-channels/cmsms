# CMSMS notifications channel for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laravel-notification-channels/cmsms.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/cmsms)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/laravel-notification-channels/cmsms/master.svg?style=flat-square)](https://travis-ci.org/laravel-notification-channels/cmsms)
[![StyleCI](https://styleci.io/repos/67046405/shield)](https://styleci.io/repos/67046405)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/90fe40f4-4957-4e65-8630-a62cdb242b86.svg?style=flat-square)](https://insight.sensiolabs.com/projects/90fe40f4-4957-4e65-8630-a62cdb242b86)
[![Quality Score](https://img.shields.io/scrutinizer/g/laravel-notification-channels/cmsms.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/cmsms)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/laravel-notification-channels/cmsms/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/cmsms/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel-notification-channels/cmsms.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/cmsms)

This package makes it easy to send [CMSMS messages](https://dashboard.onlinesmsgateway.com/docs) with Laravel.

## Contents

- [Requirements](#requirements)
- [Installation](#installation)
- [Setting up your CMSMS account](#setting-up-your-cmsms-account)
- [Usage](#usage)
	- [Available message methods](#available-message-methods)
- [Changelog](#changelog)
- [Testing](#testing)
- [Security](#security)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)

## Requirements

- [Sign up](https://dashboard.onlinesmsgateway.com) for a online sms gateway account
- Find your API key in account settings

## Installation

You can install the package via composer:

``` bash
composer require laravel-notification-channels/cmsms
```

This package will register itself automatically with Laravel 5.5 and up trough Package auto-discovery.

### Manual installation

You can install the service provider for Laravel 5.4 and below:

```php
// config/app.php
'providers' => [
    ...
    NotificationChannels\Cmsms\CmsmsServiceProvider::class,
],
```

## Setting up your CMSMS account

Add your CMSMS Product Token and default originator (name or number of sender) to your `config/services.php`:

```php
// config/services.php
...
'cmsms' => [
    'product_token' => env('CMSMS_PRODUCT_TOKEN'),
    'originator' => env('CMSMS_ORIGINATOR'),
],
...
```

Notice: The originator can contain a maximum of 11 alphanumeric characters.

## Usage

Now you can use the channel in your `via()` method inside the notification:

``` php
use NotificationChannels\Cmsms\CmsmsChannel;
use NotificationChannels\Cmsms\CmsmsMessage;
use Illuminate\Notifications\Notification;

class VpsServerOrdered extends Notification
{
    public function via($notifiable)
    {
        return [CmsmsChannel::class];
    }

    public function toCmsms($notifiable)
    {
        return CmsmsMessage::create("Your {$notifiable->service} was ordered!");
    }
}
```


In order to let your Notification know which phone numer you are targeting, add the `routeNotificationForCmsms` method to your Notifiable model.

**Important note**: CMCMS requires the recipients phone number to be in international format. For instance: 0031612345678

```php
public function routeNotificationForCmsms()
{
    return '0031612345678';
}
```

### Available message methods

- `body('')`: Accepts a string value for the message body.
- `originator('')`: Accepts a string value between 1 and 11 characters, used as the message sender name.
- `reference('')`: Accepts a string value for your message reference. This information will be returned in a status report so you can match the message and it's status. Restrictions: 1 - 32 alphanumeric characters. Reference will not work for demo accounts.
- `tariff()`: Accepts a integer value for your message tariff. The unit is eurocent. Requires the `originator` to be set to a specific value. Contact CM for this tariff value. CM also must enable this feature for your contract manually.
- `multipart($minimum, $maximum)`: Accepts a 0 to 8 integer range which allows multipart messages. See the [documentation from CM](https://dashboard.onlinesmsgateway.com/docs#send-a-message-multipart) for more information.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email michel@enflow.nl instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Michel Bardelmeijer](https://github.com/mbardelmeijer)
- [All Contributors](../../contributors)

Special thanks to [Peter Steenbergen](http://petericebear.github.io) for the MessageBird template from where this is mostly based on.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
