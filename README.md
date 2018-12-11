Scary - A simple session serializable for php
======================================================

[![Build Status](https://travis-ci.org/vlexfid/php-encryption.svg?branch=master)](https://travis-ci.org/vlexfid/php-encryption)
[![License](http://img.shields.io/:license-mit-blue.svg?style=flat-square)](http://doge.mit-license.org)
[![Usage](https://img.shields.io/badge/usage-easy-ff69b4.svg)](https://github.com/vlexfid/php-encryption)
[![codecov](https://codecov.io/gh/vlexfid/session-scary/branch/master/graph/badge.svg)](https://codecov.io/gh/vlexfid/session-scary)

A very simple session organizer that may help you for handling scary styles on php

## Requirements
* Composer for installation

## Quick Start

#### Installation
```
composer require "vlexfid/session-scary"
```

## Release
The current released is only support for handling happy _global procedural_ session

## Features
* Create Single Session
* Generate Multiple Session
* Evaluate Session
* Make Auto Increment
* Custom Session Expiration
* Regenerate Session Id
* Remove Session

## Usage

### Create A Single Session
* You can follow the humble style like below
  ```php
  Scary::set('my_token')->value('11001101')->get();
  ```
  the above will equivalent

  ```php
  $_SESSION['my_token'] = '11001101'
  ```

* Then to get a single session
  ```php
  Scary::read('my_token'); // output => 11001101
  ```
  
  **Notes** : 
  > When you refresh the browser the values will be **save** in session at first, you may need (eg. token, random, url)  
  and don't want to lost when the page's refresh, just like common it may useful

### Generate Multiple Session
* You can fashion a session banks on easy away
   ```php
   Scary::mset('My Session Manager')
      ->mkey(['Specialist','Senior','Junior'])
      ->mval(['value_1','value_2','value_3'])
      ->swap();
   ```   
* Or you may relax with a free style
   ```php
   $keys = ['Specialist','Senior','Junior'];
   $values = ['Baz','Biz','Buz'];

   Scary::mset('My Session Manager')
      ->mkey($keys) 
      ->mval($values)
      ->swap();
    ```
* And then to express your tailor
  ```php
  Scary::read('My Session Manager','Junior'); // output => Buz
  ```

  **Notes** : 
  > When you refresh the browser the values will be **save** in session at first, you may need (eg. token, random, url)  
  and don't want to lost when the page's refresh, just like common it may useful

### Evaluate A Session
* For _single session_, you can replace with new something
   ```php
   Scary::change('Angry Boss','Run'); // replace other value eg. Run
   ```
* And for _multiple sets_, you can replace like this
   ```php
   Scary::mchange('My Bread','My Chocolato','Eat Me');
   ```
   
  Now that was going through changes and you can dump with yayy...

### Make Auto Increment
* For example you may want to make eg. _login attempt_ using a single method
   ```php
   Scary::set('my_key')->value('11001101')->inc(5)->get();
   ```

* Or using multiple series
  ```php
  Scary::mset('My Desire Key')
     ->mkey(['Eat','Drink','Lick','Whatever..'])
     ->mval(['Apple','Orange','Lollipop','Hufft..'])
     ->inc(5) // <=
     ->swap();
  ```

  On above example you will get an auto-increment max. **5 times**, start from 0-5

* If you want to verify eg. _session loggedin_, you can write like
   ```php
   if (Scary::flinc('My Desire Key') !== true)
   
   // Do something
   ```
   **Notes** : 
   > It will return **false** when session doesn't exists, then you can do something 

### Custom Session Expiration
* You can recruit a custom flash message, expirated or any creations
   ```php
   Scary::set('my_key')->value('11001101')->ttl(5)->get();
   ```
   
* Using multiple happy set
   ```php
   Scary::mset('My Desire Key')
      ->mkey(['Smile','Happy','Affraid'])
      ->mval(['Lost','Donate','Any Expression Here'])
      ->swap();

   Scary::live('My Desire Key', 5) // it can be place in somewhere pages, if return false, you can do something
   ```
   **Notes** : 
   > use _**ttl or live**_ like examples on above meant the session will expired within **5 minutes**

### Regenerate Session Id
* You may doubt with an existing session, you can ensure with this
   ```php
   if (Scary::exist('my_session_key'))
   
   // Do something
   ```
   
* To regenerate session with create **new id** you can draw this
   ```php
   Scary::newId('my_session_key');
   ```
   
* Or using this to **refresh** session_id
   ```php
   Scary::refresh('my_session_key'); // session_regenerate_id(true)
   ```

### Remove Session
* To **remove** a single session with work like charm
   ```php
   Scary::trash('my_session_key');
   ```
   
* And **remove** multiple session at once
   ```php
   Scary::trash('my_session_key, my_session_key_two, my_session_key_three, next...');
   ```
   
* Also you can **destroy** all session and make them gone
   ```php
   Scary::clean('my_session_key');
   ```

## Example

#### Create A Single Session
```php
require '__DIR__' . '/vendor/autoload.php';

use Vlexfid\Session\Scary;

// Generate Cross Smile Request Poorgery
$randomToken = base64_encode(random_bytes(32));

Scary::set('Imo Cry Attack')->value($randomToken)->get();

$check = Scary::read('Imo Cry Attack');

// dump : JHs/jsakjkja87823hsalwatah989jsajh+sakCacanana83729Mama=
```

#### Create Multiple Session
```php
require '__DIR__' . '/vendor/autoload.php';

use Vlexfid\Session\Scary;

// Type whatever collection
$verify = 'true';

// Type whatever you need
$loginId = '2928929-988787-8877-78688868';

// Type whatever you set
$randomMult = bin2hex(random_bytes(16));

// Type anything you type
$downloadUrl = 'github.com/vlexfid/session-scary';

// Compile them in single bandage
Scary::mset('Something key')
->mkey(['verify','token_key','mis-loggedin','download-url'])
->mval([$verify, $randomMult, $loginId, $downloadUrl])
->swap();

$clone = Scary::read('Something key','download-url');

// dump yaaaayyy : github.com/vlexfid/session-scary
```

## License

`Vlexfid/session-scary` is released under the MIT public license. See LICENSE for details
