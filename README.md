Scary - A simple session serializable for php
======================================================

[![Build Status](https://img.shields.io/travis/vlexfid/session-scary.svg?style=flat-square)](https://travis-ci.com/vlexfid/session-scary)
[![License](http://img.shields.io/:license-mit-blue.svg?style=flat-square)](http://doge.mit-license.org)
[![Usage](https://img.shields.io/badge/usage-easy-ff69b4.svg)](https://github.com/vlexfid/php-encryption)

A very simple session manager that may help you for handling scary styles on php

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
  that doesn't want to lost when the page's refresh, like common you can use it as easy away

### Generate Multiple Session
* To generate session repository you can scratch like this
   ```php
   Scary::mset('My Session Manager')
      ->mkey(['Specialist','Senior','Junior'])
      ->mval(['value_1','value_2','value_3'])
      ->swap();
   ```   
* or you can make other style like
   ```php
   Scary::mset('My Session Manager')
      ->mkey('Specialist, Senior, Junior') // without array
      ->mval([$variable_1, $variable_2, $variable_3])
      ->swap();
    ```
* Then to get the value using multiple method on above
  ```php
  Scary::read('My Session Manager','Junior'); // output => $variable_3
  ```

  **Notes** : 
  > When you refresh the browser the values will be **save** in session at first, it may useful for (eg. token, random, url)  
  like common when you close the browser, everything will gone.

### Evaluated A Session
* For _single session_, you can replace with something
   ```php
   Scary::change('My Boss','Run'); // replace other value eg. Run
   ```
* And for _multiple method_, you can replace like this
   ```php
   Scary::mchange('My Bread','My Chocolato','Eat Me');
   ```
   
  Now it was going through changes and you can dump with yayy...

### Make Auto Increment
* For example you may want to _make_ eg. session login attempt using single method
   ```php
   Scary::set('my_key')->value('11001101')->inc(5)->get();
   ```

* Or using multiple set like
  ```php
  Scary::mset('My Desire Key')
     ->mkey(['Eat','Drink','Lick','Whatever..'])
     ->mval(['Apple','Orange','Lollipop','Yayy..'])
     ->inc(5)
     ->swap();
  ```

  Example on above will be set auto-increment within **5 times** start from 0-5 after _browser refresh_

* If you want to verify eg. login session using on above, you can write like
   ```php
   if (Scary::flinc('My Desire Key') !== true)
   
   // Do something
   ```
   **Notes** : 
   > It will return **false** when session doesn't exists, then you can do something 

### Custom Session Expiration
* You can set simply custom time to live using single method
   ```php
   Scary::set('my_key')->value('11001101')->ttl(5)->get();
   ```
   
* Or using multiple set
   ```php
   Scary::mset('My Desire Key')
      ->mkey(['Smile','Happy','Affraid'])
      ->mval(['Lost','Donate','Any Expression Here'])
      ->live(5) // ttl
      ->swap();
   ```
   **Notes** : 
   > use _**ttl or live**_ like sample above, it's meant the session will expired within **5 minutes**

### Regenerate Session Id
* You must check session was already exists or not
   ```php
   if (Scary::exist('my_session_key'))
   
   // Do something
   ```
   
* To regenerate session with create **new id** you can grab like
   ```php
   Scary::newId('my_session_key');
   ```
   
* Or using this to only **refresh** session_id
   ```php
   Scary::refresh('my_session_key');
   ```

### Remove Session
* To **remove** a single session you must provide the key
   ```php
   Scary::trash('my_session_key');
   ```
   
* Or **remove** multiple session at once
   ```php
   Scary::trash('my_session_key, my_session_key_two, my_session_key_three, and...');
   ```
   
* Then to **destroy** all sessions and make them gone
   ```php
   Scary::clean('my_session_key');
   ```

## Example

#### Create A Single Session
```php
require '__DIR__' . 'vendor/autoload.php';

use Vlexfid\Session\Scary;

$randomToken = base64_encode(random_bytes(16));

Scary::set('Something key')->value($randomToken)->get();

$check = Scary::read('Something key');

// dump check : ec4a7498b9f44fefa1ba309799d88ab722b486369d
```

#### Create Multiple Session
```php
require '__DIR__' . 'vendor/autoload.php';

use Vlexfid\Session\Scary;

$verify = 'true';

$loginId = '2928929-988787-8877-78688868';

$randomMult = bin2hex(random_bytes(16));

$downloadUrl = 'github.com/vlexfid/session/scary';

Scary::mset('Something key')
  ->mkey(['verify','token_key','mis-loggedin','download-url'])
  ->mval([$verify, $randomMult, $loginId, $downloadUrl])
  ->swap();

$yaycheck = Scary::read('Something key','download-url');

// dump yaaaayyy : github.com/vlexfid/session/scary
```

## License

`Vlexfid/session-scary` is released under the MIT public license. See LICENSE for details.
