<?php
/**
 * Scary - A simple session organizer for PHP
 *
 * @category   Scary Session
 * @package    Rammy Labs
 *
 * @author     Moviet
 * @license    MIT Public License
 *
 * @version    Build @@version@@
 */
namespace Moviet\Session;

/**
* start new scary thriller
*/
class Scary
{
    /**
     * @param string $key
     */
    protected static $key;

    /**
     * @param string $value
     */
    protected static $value;
    
    /**
     * @param string $entry
     */
    protected static $entry;
    
    /**
     * @param string $value
     */
    protected static $story;
    
    /**
     * @param string $exist
     */
    protected static $exist;
    
    /**
     * @param int $inc
     */
    protected static $inc;
    
    /**
     * set variable $limit
     */
    protected static $limit;
    
    /**
     * @param int $ttl
     */
    protected static $ttl;
    
    /**
     * @param int $live
     */
    protected static $live;
    
    /**
     * @param string $mset
     */
    protected static $mset;
    
    /**
     * @param string $mkey
     */
    protected static $mkey;
    
    /**
     * @param string $mvalue
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
     * Check if session was already started
     */
    public function __construct() 
    {
        if (!session_id() ? session_start() :  @session_start());
    }

    /**
     * Create single session key
     *
     * @param string $key
     */
    public function set($key)
    {
        self::$key = $key;

        return new self;
    }

    /**
     * Create value for single seesion key
     *
     * @var $value
     */
    public function value($value)
    {
        self::$value = $value;

        return $this;
    }

    /**
     * Create session expired
     *
     * @param int $ttl
     */
    public function ttl($ttl)
    {
        self::$ttl[self::$key] = ($ttl * self::EXPIRED_IN_SECOND);

        return $this;
    }

    /**
     * Create session increment
     *
     * @param int $increment
     */
    public function inc($increment = null)
    {
        self::$inc[self::$key] = (int) $increment;

        return $this;
    }

    /**
     * Generate session increment
     *
     * @return int
     */
    protected function getInc()
    {
        return self::$inc;
    }

    /**
     * Verify session increment if exists
     * calculate value and destroy
     * 
     * @param string $key
     * @return bool
     */
    public function flinc($key)
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
     *
     * @param string $timer
     * @return array
     */
    protected function entry($timer = null)
    {
        self::$entry = $timer;

        return $this;
    }

    /**
     * Generate expire id 
     *
     * @param string $timer
     * @return array
     */
    protected function create($timer = null)
    {
        self::$story = $timer;

        return $this;
    }

    /**
     * Save session with single method
     * Create session identity
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
         * Check if session increment has negotiated
         */
        if (!is_null(self::$inc[self::$key])) {   
            self::sinc(self::INCREMENT_KEY);
        }

        /**
         * Check if session has negotiated to expired
         */
        if (self::$entry === time()) {
            if ((time() - self::make(self::$key)[self::$story]) > (self::$ttl[self::$key])) {
                self::refresh(self::$key);
                self::remove(self::$key);
            }     
        }
    }

    /**
     * Getting session from single or multiple method
     * 
     * @param string $key
     * @param string $id
     * @return array
     */
    public function read($key, $id = '')
    {
        return empty($id) ? self::make($key)[$key] : self::make($key)[$id];
    }

    /**
     * Change or revision any session value
     * Generate timer when it was set
     * 
     * @param string $key
     * @param string $value
     * @return bool
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
     * Create session key for multiple value
     * 
     * @param string $keys
     * @return array
     */
    public function mset($keys)
    {
        foreach ([$keys] as $key) {
            self::$mset[$key] = $key;
        }

        return new self;
    }

    /**
     * Create session with multiple subkey
     * 
     * @param string $id
     * @return array
     */
    public function mkey($id)
    {
        $ids = !is_array($id) ? array_filter(explode(',',$id)) : $id;

        self::$mkey = $ids;

        return $this;
    }

    /**
     * Create session with multiple value
     * 
     * @param string $value
     * @return array
     */
    public function mval($value)
    {
        $values = !is_array($value) ? array_filter(explode(',',$value)) : $value;

        self::$mvalue = $values;

        return $this;
    }

    /**
     * Create session expires for multiple method
     * and remove if session has expired
     * 
     * @param string $key
     * @param int $ttl
     * @return bool
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
     * Generate session with multiple attributes
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
     * Evaluate and replace session with multiple value
     * 
     * @param string $mset
     * @param string $mkey
     * @param string $mvalue
     * @return bool
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
     * Remove any existing session with key identity
     * 
     * @param string $key
     * @return bool
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
     * Regenerate session id when they exists
     * 
     * @param string $key
     */
    public function refresh($key)
    {
        if (self::exist($key)) {
            session_regenerate_id(true);
        }
    }

    /**
     * Regenerate new session id when they exists
     * 
     * @param string $key
     */
    public function newId($key)
    {
        if (self::exist($key)) {
            session_regenerate_id();
        }
    }

    /**
     * Destroy session id when they exists
     * 
     * @param string $key
     * @return bool
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
     * Make session readable from serialize
     * 
     * @param string $key
     * @param string $id
     * @return array
     */
    protected function make($key, $id = null)
    {
        return is_null($id) ? unserialize($_SESSION[$key]) : $_SESSION[$id];
    } 

    /**
     * Generate session auto increment
     * 
     * @param string $key
     * @return array
     */
    protected function sinc($key)
    {
        return !isset($_SESSION[$key]) ? $_SESSION[$key] = serialize(self::INCREMENT_KEY_LOOP) : $_SESSION[$key] += self::INCREMENT_KEY_START;
    }

    /**
     * Save session early
     * 
     * @param string $key
     * @param string $value
     * @return array
     */
    protected function save($key, $value)
    {
        return $_SESSION[$key] = serialize(array_filter($value));
    }

    /**
     * Check if session has exists
     * 
     * @param string $key
     * @return bool
     */
    public function exist($key)
    {
        return isset($_SESSION[$key]);
    }

    /**
     * unset session has already exists
     */
    protected function remove($key)
    {       
        unset($_SESSION[$key]);
    }
}
