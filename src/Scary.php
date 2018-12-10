<?php
/**
 * Scary - A simple session serializable for PHP
 *
 * @category   Scary Session
 * @package    Rammy Labs
 *
 * @author     Vlexfid
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT Public License
 *
 * @version    Build @@version@@
 */
namespace Vlexfid\Session;

/**
* start new scary thriller
*/
class Scary
{
    /**
     * @param string key
     */
    protected static $key;

    /**
     * @param string value
     */
    protected static $value;
    
    /**
     * compile entry
     */
    protected static $entry;
    
    /**
     * generate story
     */
    protected static $story;
    
    /**
     * @param string session exist
     */
    protected static $exist;
    
    /**
     * @param int set increment
     */
    protected static $inc;
    
    /**
     * set variable limit
     */
    protected static $limit;
    
    /**
     * @param int set expiration
     */
    protected static $ttl;
    
    /**
     * @param int set multiexpiration
     */
    protected static $live;
    
    /**
     * @param string set multi session key
     */
    protected static $mset;
    
    /**
     * @param string set multi session subkeys
     */
    protected static $mkey;
    
    /**
     * @param string set multi session values
     */
    protected static $mvalue;

    const CREATE_SERIAL_KEY = 'scary~created:';

    const CREATE_SERIAL_END = 'scary~end:';

    const HASH_SERIAL_KEY = 'crc32b';

    const CREATE_SERIAL_ID = 'serial~id:';

    const EXPIRED_IN_SECOND = 60;

    const INCREMENT_KEY = 'scary~inc';

    const INCREMENT_KEY_LOOP = 0;

    const INCREMENT_KEY_START = 1;

    /**
     * Check if session id was already started
     */
    public function __construct() 
    {
        if (!session_id() ? session_start() :  @session_start());
    }

    /**
     * @param string create session key
     */
    public function set($key)
    {
        self::$key = $key;

        return new self;
    }

    /**
     * @param string create session value
     */
    public function value($value)
    {
        self::$value = $value;

        return $this;
    }

    /**
     * @param int create session expired in seconds
     */
    public function ttl($ttl)
    {
        self::$ttl[self::$key] = ($ttl * self::EXPIRED_IN_SECOND);

        return $this;
    }

    /**
     * @param int create session increment
     */
    public function inc($increment = null)
    {
        self::$inc[self::$key] = (int) $increment;

        return $this;
    }

    /**
     * Getting session increment
     */
    protected function getInc()
    {
        return self::$inc;
    }

    /**
     * Verify session increment
     * 
     * @param string session key
     * 
     * @return boolen;
     */
    public function flinc($key, $default = false)
    {
        self::$limit = self::make(self::INCREMENT_KEY,self::INCREMENT_KEY);

        if (self::exist($key)) {

            if (self::$limit > array_values(self::getInc())[self::INCREMENT_KEY_LOOP]) {

                self::remove(self::INCREMENT_KEY);
                self::remove($key);

                return true;
            }
        }

        return false;
    }

    /**
     * Generate expire time
     */
    protected function entry($timer = null)
    {
        self::$entry = $timer;

        return $this;
    }

    /**
     * Generate expire id 
     */
    protected function create($timer = null)
    {
        self::$story = $timer;

        return $this;
    }

    /**
     * Save session with single method
     * Create session expire and session increment
     * 
     * Remove if session key has expired
     */
    public function get()
    {
        self::$story = !isset(self::$ttl[self::$key]) ? self::CREATE_SERIAL_KEY . time() : self::CREATE_SERIAL_END . hash(self::HASH_SERIAL_KEY, self::$key);

        self::$entry = !isset(self::$ttl[self::$key]) ? self::CREATE_SERIAL_ID . hash(self::HASH_SERIAL_KEY,self::$key) : time();  

        $spare = [self::$story => self::$entry];

        $book = array_combine([self::$key, self::$story], [self::$value, self::$entry]);

        if (!self::exist(self::$key)) {

            self::save(self::$key, $book);
        }
       
        /**
         * Check if session increment was negotiated
         */
        if (!is_null(self::$inc[self::$key])) {   

            self::sinc(self::INCREMENT_KEY);
        }

        /**
         * Check if session expirated was negotiated
         */
        if (self::$entry === time()) {

            if ((time() - self::make(self::$key)[self::$story]) > (self::$ttl[self::$key])) {

                self::refresh(self::$key);
                self::remove(self::$key);
            }     
        }
    }

    /**
     * Getting session value
     * 
     * @param string session value
     */
    public function read($key, $id = '')
    {
        return empty($id) ? self::make($key)[$key] : self::make($key)[$id];
    }

    /**
     * Revision any session value
     * Generate timer when it was set
     * 
     * @param string session_key and new value
     * 
     * @return boolen if doesn't exists
     */
    public function change($key, $value)
    {
        self::$story = !isset(self::$ttl[self::$key]) ? self::CREATE_SERIAL_KEY . time() : self::CREATE_SERIAL_END . hash(self::HASH_SERIAL_KEY, $key);

        self::$entry = !isset(self::$ttl[self::$key]) ? self::CREATE_SERIAL_ID . hash(self::HASH_SERIAL_KEY, $key) : self::$entry = time(); 
        
        $book = array_combine([$key, self::$story], [$value, self::$entry]);

        self::save($key, $book);

        return self::exist($key) ? true : false;
    }

    /**
     * Set session key
     * 
     * @param string session key
     */
    public function mset($keys)
    {
        foreach ([$keys] as $key) {

            self::$mset[$key] = $key;
        }

        return new self;
    }

    /**
     * Set session subkey
     * 
     * @param string session subkey
     */
    public function mkey($id)
    {
        $ids = !is_array($id) ? array_filter(explode(',',$id)) : $id;

        self::$mkey = $ids;

        return $this;
    }

    /**
     * Set session value
     * 
     * @param string session value
     */
    public function mval($value)
    {
        $values = !is_array($value) ? array_filter(explode(',',$value)) : $value;

        self::$mvalue = $values;

        return $this;
    }

    /**
     * Set expire session using multi method
     * 
     * @param string session key
     * 
     * @param int set time to live
     * 
     * @return boolen if emtpy session
     */
    public function live($key, $ttl)
    {
        $live = self::CREATE_SERIAL_END . hash(self::HASH_SERIAL_KEY, $key);

        $value = [$live => time()];

        if (!self::exist($live)) {

            self::save($live, $value);
        } 

        if ((time() - self::make($live)[$live]) > ($ttl * self::EXPIRED_IN_SECOND)) {

            self::refresh($live);
            self::remove($key);
            self::remove($live); 
        }

        return self::exist($live) ? true : false;
    }

    /**
     * Save session with multiple attributes
     * 
     * @return array session set
     */
    public function swap()
    {
        $book = array_combine(self::$mkey, self::$mvalue);
        
        foreach (self::$mset as $key => &$value) {

            if (!self::exist(self::$mset[$value])) {

                self::save(self::$mset[$value], $book);
            }
        }
    }

    /**
     * Evaluate multi session replacement
     * 
     * @param string session key and subkey and new value
     * 
     * @return array session set
     * 
     * @return boolen if emtpy session
     */
    public function mchange($mset, $mkey, $mvalue = null)
    {
        $mkey = !is_array($mkey) ? array_filter(explode(',',$mkey)) : $mkey;

        $mvalue = !is_array($mvalue) ? array_filter(explode(',',$mvalue)) : $mvalue;
        
        $value = array_combine($mkey, $mvalue);
        
        $book = array_merge(self::make($mset), $value);

        if (self::exist($mset)) {

            self::save($mset, $book);
        }

        return self::exist($mset) ? true : false;
    }

    /**
     * Remove any existing keys and keep clean
     * 
     * @param string session key 
     * 
     * @return boolen if emtpy session
     */
    public function trash($key)
    {
        $keys = !is_array($key) ? array_filter(explode(',',$key)) : $key;

        foreach ($keys as $val) {

            self::remove($val);
            self::remove(self::flinc($key));
        }

        return self::exist($key) ? true : false;
    }

    /**
     * Regenerate session id when they were exists
     * 
     * @param string session key 
     */
    public function refresh($key)
    {
        if (self::exist($key)) {

            session_regenerate_id(true);
        }
    }

    /**
     * Regenerate new session id when they were exists
     * 
     * @param string session key 
     */
    public function newId($key)
    {
        if (self::exist($key)) {

            session_regenerate_id();
        }
    }

    /**
     * Destroy session id when they were exists
     * 
     * @param boolen value
     */
    public function clean($key)
    {
        if (self::exist($key)) {

            session_regenerate_id(true);
            session_unset();
            session_destroy();
        }

        return self::exist($key) ? true : false;
    }

    /**
     * Make session readable
     * Read to read
     * 
     * @param array session
     */
    protected function make($key, $id = null)
    {
        return is_null($id) ? unserialize($_SESSION[$key]) : $_SESSION[$id];
    } 

    /**
     * Make session auto increment
     * 
     * @return string serializable session
     */
    protected function sinc($key)
    {
        return !isset($_SESSION[$key]) ? $_SESSION[$key] = serialize(self::INCREMENT_KEY_LOOP) : $_SESSION[$key] += self::INCREMENT_KEY_START;
    }

    /**
     * Save session early
     * 
     * @return string serialize session
     */
    protected function save($key, $value)
    {
        return $_SESSION[$key] = serialize(array_filter($value));
    }

    /**
     * Check if session does exists
     * 
     * @return boolen serialize session
     */
    public function exist($key)
    {
        return isset($_SESSION[$key]);
    }

    /**
     * unset session that's already exists
     */
    protected function remove($key)
    {       
        unset($_SESSION[$key]);
    }
}