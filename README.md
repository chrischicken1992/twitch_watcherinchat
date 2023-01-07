# twitch_watcherinchat
This is a PHP + JavaScript, to show you, how many Twitch Users are in your chat.

The Page will be refeshed after 5 Minutes.

There is also included an autoscroller, if you have over 50 Viewer.

## Language

Attention! There are some words in German!

I will add a language file in the next days!

## Installation
Download all files and upload it to your Webhosting.

## Usage
Open OBS and go to Docks -> Custom Browser Docks...

Add the URL, where do you host your files.

E.g. https://twitch.butchris.de/viewer.php.

## Settings

```php
//Your Twitch Username
$twitch_user = "chrischicken1992";

//Turn Database on or off
$usedatabase = true;

//Date Format (https://www.php.net/manual/en/datetime.format.php)
$date_format = "d.m.Y";
$time_format = date('H:i:s') . " Uhr";

```

## Database (MySQL or MariaDB)
A structure file is included. Just import it to your MySQL or MariaDB Database.

You have to add your credentials in the config.php.

Don't forget to set 

```php
$usedatabase = true; 
```
in the viewer.php.

## Contributing

Pull requests are welcome. For major changes, please open an issue first
to discuss what you would like to change.

Please make sure to update tests as appropriate.
