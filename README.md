Scary - A simple session serializable for php
======================================================

[![Build Status](https://img.shields.io/travis/vlexfid/encryption.svg?style=flat-square)](https://travis-ci.com/vlexfid/encryption)
[![License](http://img.shields.io/:license-mit-blue.svg?style=flat-square)](http://doge.mit-license.org)  

A very simple session *_scary_* manager that may help you for handling scary styles on php

## Requirements
* Composer for installation

## Jump Start

#### Installation
```
composer require "vlexfid/session-scary"
```

## Features
* Create single session
* Generate multiple session
* Evaluated session
* Make auto increment
* Custom session expiration
* Regenerate Session Id
* Remove session

#### Release
The current version is only support for handling _global procedural_ session    
Any contributions are welcome and please follow the basic rules

## Basic Usage

#### Create Single Session
* You can follow the humble style like below
  ```php
  Scary::set('my_token')->value('11001101')->get();
  ```
  the above will equivalent

  `$_SESSION['my_token'] = '11001101'`

  * Then to get your single session
  ```php
  Scary::read('my_token');
  
  // output => 11001101
  ```
  
  **Notes** : 
	 > if you refresh the browser the session will be **save** and _never change_ (eg. token, random value)  
    like common if you close the browser, everything will gone.

#### Generate Multiple Session
   * To generate multiple session you can scratch like this
   ```php
   Scary::mset('My Manager');
        ->mkey('Supervisor_1','Supervisor_2','Supervisor_3');
        ->mval('value_1','value_2','value_3');
        ->swap();
   ```
   * or you can make other style very simply on below
   ```php
   Scary::mset('My Manager');
        ->mkey('Specialist, Senior, Junior');
        ->mval('value_1, value_2, value_3');
        ->swap();
    ```

  * Then to render using multiple session on above
  ```php
  Scary::read('My Manager','Junior'); // any key you use; 
  ```

  **Notes** : 
  > if you refresh the browser the session will be **save** and like common if you close the browser,  
  everything will gone.

#### Evaluated Session
   * For _single session_, you can change with different
   ```php
   Scary::change('My Sesi','Run'); // Whatever the key you use; 
   ```
   
   To ensure it, you can dump and get a new scary thriller

   * Then for _multiple session_, you can change like this
   ```php
   Scary::mchange('My Bread','My Chocolato','Eat Me');
   ```
   Now you are going through changes, you can dump with yaayy.. delicious

#### Make Auto Increment
   * For example you may want to _block_ eg. session login attempt for single method
   ```php
   Scary::set('my_key')->value('11001101')->inc(5)->get();
   ```

   * For multiple set to _block_ eg. session login attempts
      ```php
      Scary::mset('My Desire Key');
         ->mkey('Eat','Drink','Lick','Whatever..');
         ->mval('Apple','Orange','Lollipop','Yayy..');
         ->inc(5);
         ->swap();
      ```

   On above will set auto-increment within **5 times** start from 0-5 when _browser refresh_

   * If you want to remove the limit attempt on above then you must verify
   ```php
   if (Scary::flinc('My Desire Key') !== true) 
   // Do something
   ```
   **Notes** : 
   > It will return **false** when session doesn't exists, then do something 

#### Custom Session Expired

* You can set simply custom expiration for single method like so
   ```php
   Scary::set('my_key')->value('11001101')->ttl(5)->get();
   ```
* And for multiple set with custom expiration please use *_live_* method
   ```php
   Scary::mset('My Desire Key');
        ->mkey('Smile','Happy','Affraid');
        ->mval('Lost','Donate','Any Expression Here');
        ->live(5);
        ->swap();
   ```
   **Notes** : 
   > ttl or live on sample above will expires on **5 seconds**, then you can do something

#### Regenerate Session Id

* You can check session does exists or not
   ```php
   if (Scary::exist('my_session_key'))
   // Do something
   ```
* To regenerate session with create **New Id** you can grab this
   ```php
   Scary::newId('my_session_key');
   ```
* Or using this method to only **Refresh Id** like
   ```php
   Scary::refresh('my_session_key');
   ```

#### Remove Session

* To **remove** a single key your own session use this
   ```php
   Scary::trash('my_session_key');
   // Do something
   ```
* Then to **destroy** all sessions using _safety_ method
   ```php
   Scary::clean('my_session_key');
   ```
* Or using this method to only **Refresh Id** like
   ```php
   Scary::refresh('my_session_key');
   ```

## Example

#### Create A Session

```php
require '__DIR__' . 'vendor/autoload.php';

use Vlexfid\Session\Scary;

$randomToken = base64_encode(random_bytes(16));

Scary::set('Something key')->value($randomToken)->get();

$check = Scary::read('Something key');

// dump check : ec4a7498b9f44fefa1ba309799d88ab722b486369d

$verify = 'true';
$loginId = '2928929-988787-8877-78688868';
$randomMult = bin2hex(random_bytes(16));
$downloadUrl = 'github.com/vlexfid/session/scary';

Scary::mset('Something key')
   ->mkey('verify','token_key','mis-loggedin','download-url')
   ->mval($verify,$randomMult,$loginId,$downloadUrl)
   ->swap();

$yaycheck = Scary::read('Something key','download-url');

// dump yaaaayyy : github.com/vlexfid/session/scary
```
## License

`Vlexfid/session-scary` is released under the MIT public license. See LICENSE for details.