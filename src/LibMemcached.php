<?php 
namespace MyOperator;

define('LIBMEMCACHED_DEFAULT_HOST', '127.0.0.1');
define('LIBMEMCACHED_DEFAULT_PORT', 11211);
/**
 * LibMemcached.php - Memcache loader as data source
 *
 *
 * @category   Utilities
 * @package    Myoperator_LibMemcached
 * @author     Ashutosh Chaudhary <ashutosh.chaudhary@myoperator.co>
 * @copyright  2018 MyOperator
 * @license    http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @version    1.0
 * @link       https://gitlab.com/myoperator-packages/libmemcached.git
 */
class LibMemcached {

  public $config = array(
    'host' => LIBMEMCACHED_DEFAULT_HOST,
    'port' => LIBMEMCACHED_DEFAULT_PORT,
    'weight' => '1',
    'persistent' => true
  );
  public $instance = null;
  protected static $class_instance = null;
  protected static $logger = null;

  private function __construct($config=array())
  {
    if($this->_checkConfig($config))
    {
      $this->config = $config + $this->config;
    }

    $instance_type = class_exists('Memcached') ? "Memcached" : (class_exists('Memcache') ? "Memcache" : FALSE);

    if(!$instance_type) {
      throw new \Exception('Neither Memcached and Memcache are available', 500);
    }

    $this->instance = (new $instance_type);
    //@todo prevent autoconnect
    $this->connect();
  }

  private function _checkConfig($config=array())
  {
    return (!empty($config) && array_key_exists('host', $config) && array_key_exists('port', $config));
  }

  public function setLogger($logger)
  {
    if(!$logger) {
      throw new \Exception('Logger can not be empty or null.', 500);
    }
    self::$logger = $logger;
    return $this;
  }

  public function __call($method, $args)
  {
    if(!method_exists($this->instance, $method)) {
      throw new \Exception('Method not exist');
    }
    return call_user_func_array(array($this->instance, $method), $args);
  }

  public static function getInstance($args=array())
  {
    if(!(self::$class_instance instanceof LibMemcached))
    {
      self::$class_instance = new LibMemcached($args);
    }
    return self::$class_instance;
  }

  public function memcache()
  {
    return self::getInstance()->instance;
  }

  public function connect($config=array(), $port=LIBMEMCACHED_DEFAULT_PORT)
  {
    if(NULL === $this->instance)
      throw new \Exception('Not a valid memcache instance');

  	$config = empty($config) ? $this->config : $config;
    if(is_array($config)) extract($config);
    $timeout = isset($timeout) ? $timeout : 1;
    if(is_string($config)) $host = $config;
    $this->instance->addServer($host, $port, $timeout);
    if(!$this->instance->getStats()) {
      throw new \Exception('Unable to connect to memcache server using provided config');
    }
    return $this->instance;
  }

  public function getConfig()
  {
    return $this->config;
  }

  public function setConfig($config = array())
  {
    if(!$this->_checkConfig($config))
    {
      throw new \Exception('Host and Port are missing!', 500);
    }
    $this->config = $config;
    return $this;
  }

  /**
   * Memcache spec fn
   **/
  public function increment($key, $offset=1, $initial_value=1, $expiry=0)
  { 
    if(!$this->instance->get($key)){
      return $this->instance->set($key, $initial_value, $expiry);
    }
    return $this->instance->increment($key, $offset);
  }

  public function decrement($key, $offset=1, $initial_value=0, $expiry=0)
  { 
    if(!$this->instance->get($key)){
      return $this->instance->set($key, $initial_value, $expiry);
    }
    return $this->instance->decrement($key, $offset);
  }

}
