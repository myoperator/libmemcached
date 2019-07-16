<?php 
include_once __DIR__.'/../vendor/autoload.php';
use PHPUnit\Framework\TestCase;
use Myoperator\LibMemcached;

/**
*  Corresponding Class to test YourClass class
*
*  For each class in your library, there should be a corresponding Unit-Test for it
*  Unit-Tests should be as much as possible independent from other test going on.
*
*  @author yourname
*/
class LibMemcachedTest extends TestCase
{

  /**
     * The Host used for testing.
     *
     * @var string
     * @access protected
     */
    protected $host = 'memcached';

    /**
     * Client instance
     *
     * @var \Clickalicious\Memcached\Client
     * @access protected
     */
    protected $client;

    /**
     * Key for test entries
     *
     * @var string
     * @access protected
     */
    protected $key;

    /**
     * Value for test entries
     *
     * @var string
     * @access protected
     */
    protected $value;

  protected function setUp()
  {
        $this->key   = md5(microtime(true));
        $this->value = sha1($this->key);
        $this->client = Myoperator\LibMemcached::getInstance(array('host' => $this->host, 'port' => 11211));
  }
	
  /**
  * Just check if the YourClass has no syntax error 
  *
  * This is just a simple check to make sure your library has no syntax error. This helps you troubleshoot
  * any typo before you even use this library in a real project.
  *
  */
  public function testIsThereAnySyntaxError()
  {
  	$this->assertTrue(is_object($this->client));
  	unset($var);
  }
  
  /**
     * Test: Set a key value pair.
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     * @return void
     * @access protected
     */
    public function testSetAKeyValuePair()
    {
        $this->assertTrue($this->client->set($this->key, $this->value));
    }

    /**
     * Test: Get a value by key.
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     * @return void
     * @access protected
     */
    public function testGetAValueByKey()
    {
        // Test success (ask for existing key)
        $this->assertTrue($this->client->set($this->key, $this->value));
        $this->assertEquals(
            $this->value,
            $this->client->get($this->key)
        );

        // Test failure (ask for not existing key)
        $this->assertFalse($this->client->get(md5($this->key)));
    }

    /**
     * Test: Add a key value pair.
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     * @return void
     * @access protected
     */
    public function testAddAKeyValuePair()
    {
        // Should here return TRUE cause key does not exist
        $this->assertTrue($this->client->add($this->key, $this->value));
        $this->assertEquals(
            $this->value,
            $this->client->get($this->key)
        );
        // Should now return FALSE cause key already exists
        $this->assertFalse($this->client->add($this->key, $this->value));
    }

    /**
     * Test: Replace an existing value.
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     * @return void
     * @access protected
     */
    public function testReplaceAnExistingValue()
    {
        srand(microtime(true));
        $value = md5(rand(1, 65535));

        $this->assertFalse($this->client->replace($this->key, $value));

        // Should here return TRUE cause key does not exist
        $this->assertTrue($this->client->set($this->key, $this->value));
        $this->assertTrue($this->client->replace($this->key, $value));

        $this->assertEquals(
            $value,
            $this->client->get($this->key)
        );
    }

    /**
     * Test: Append a value to an existing one.
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     * @return void
     * @access protected
     */
    // public function testAppendAValueToAnExistingOne()
    // {
    //     srand(microtime(true));
    //     $value = md5(rand(1, 65535));

    //     $this->assertFalse($this->client->append($this->key, $value));

    //     // Should here return TRUE cause key does not exist
    //     $this->assertTrue($this->client->set($this->key, $this->value));
    //     $this->assertTrue($this->client->append($this->key, $value));

    //     $this->assertEquals(
    //         $this->value . $value,
    //         $this->client->get($this->key)
    //     );
    // }

    /**
     * Test: Prepend a value to an existing one.
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     * @return void
     * @access protected
     */
    // public function testPrependAValueToAnExistingOne()
    // {
    //     srand(microtime(true));
    //     $value = md5(rand(1, 65535));

    //     $this->assertFalse($this->client->prepend($this->key, $value));

    //     $this->assertTrue($this->client->set($this->key, $this->value));
    //     $this->assertTrue($this->client->prepend($this->key, $value));

    //     $this->assertEquals(
    //         $value . $this->value,
    //         $this->client->get($this->key)
    //     );
    // }

    /**
     * Test: Retrieve version.
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     * @return void
     * @access protected
     */
    public function testRetrieveVersion()
    {
        $this->assertRegExp(
            '/\d[\.]\d[\.]\d[\-\w]+/u',
            array_values($this->client->getVersion())[0]
        );
    }

    /**
     * Test: Storing PHP type string.
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     * @return void
     * @access protected
     */
    public function testStoringPhpTypeString()
    {
        $value = 'Hello World!';

        $this->assertTrue($this->client->set($this->key, $value));
        $this->assertTrue(is_string($this->client->get($this->key)));
        $this->assertEquals(
            $value,
            $this->client->get($this->key)
        );
    }

    /**
     * Test: Storing PHP type float.
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     * @return void
     * @access protected
     */
    public function testStoringPhpTypeFloat()
    {
        $value = 5.23;

        $this->assertTrue($this->client->set($this->key, $value));
        $this->assertTrue(is_float($this->client->get($this->key)));
        $this->assertEquals(
            $value,
            $this->client->get($this->key)
        );
    }

    /**
     * Test: Storing PHP type integer.
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     * @return void
     * @access protected
     */
    public function testStoringPhpTypeInteger()
    {
        $value = 523;

        $this->assertTrue($this->client->set($this->key, $value));
        $this->assertTrue(is_int($this->client->get($this->key)));
        $this->assertEquals(
            $value,
            $this->client->get($this->key)
        );
    }

    /**
     * Test: Storing PHP type array.
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     * @return void
     * @access protected
     */
    public function testStoringPhpTypeArray()
    {
        $value = array(
            5,
            23,
        );

        $this->assertTrue($this->client->set($this->key, $value));
        $this->assertTrue(is_array($this->client->get($this->key)));
        $this->assertEquals(
            $value,
            $this->client->get($this->key)
        );
    }

    /**
     * Test: Storing PHP type object.
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     * @return void
     * @access protected
     */
    public function testStoringPhpTypeObject()
    {
        $value = new \stdClass();
        $value->{$this->key} = $this->value;

        $this->assertTrue($this->client->set($this->key, $value));
        $this->assertTrue(is_object($this->client->get($this->key)));
        $this->assertEquals(
            $value,
            $this->client->get($this->key)
        );
    }

    /**
     * Test: Storing PHP type null.
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     * @return void
     * @access protected
     */
    public function testStoringPhpTypeNull()
    {
        $value = null;

        $this->assertTrue($this->client->set($this->key, $value));
        $this->assertTrue(is_null($this->client->get($this->key)));
        $this->assertEquals(
            $value,
            $this->client->get($this->key)
        );
    }

    /**
     * Test: Storing PHP type boolean.
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     * @return void
     * @access protected
     */
    public function testStoringPhpTypeBoolean()
    {
        $value = true;

        $this->assertTrue($this->client->set($this->key, $value));
        $this->assertTrue(is_bool($this->client->get($this->key)));
        $this->assertEquals(
            $value,
            $this->client->get($this->key)
        );
    }

    /**
     * Test: <increment> a stored value.
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     * @return void
     * @access protected
     * @depends testStoringPhpTypeInteger
     */
    public function testIncrementAStoredValue()
    {
        $value = 523;

        $this->assertTrue($this->client->set($this->key, $value));
        $this->assertEquals($value + 2, $this->client->increment($this->key, 2, 2));
        //$this->assertEquals($value + 4, $this->client->incr($this->key, 2));
        $this->assertEquals(
            $value + 2,
            $this->client->get($this->key)
        );
    }

    /**
     * Test: <decrement> a stored value.
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     * @return void
     * @access protected
     * @depends testStoringPhpTypeInteger
     */
    public function testDecrementAStoredValue()
    {
        $value = 525;

        $this->assertTrue($this->client->set($this->key, $value));
        $this->assertEquals($value - 2, $this->client->decrement($this->key, 2, 2));
        //$this->assertEquals($value - 4, $this->client->decr($this->key, 2));
        $this->assertEquals(
            $value - 2,
            $this->client->get($this->key)
        );
    }

    /**
     * Test: Connection - real with success as well as failure.
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     * @return void
     * @access protected
     * @expectedException \Exception
     */
    public function testConnectToAMemcachedDaemon()
    {
        ///var_dump($this->client->connect($this->host, 11211);
        $this->assertTrue(
            ($this->client->connect($this->host, 11211) instanceof Memcached)
        );

        // Now connect to a fake host/port with little timeout - just to get the exception tested
        $this->client->connect(array('host' => '1.2.3.4', 'port' => '11211', 'timeout' => 1));
    }

    /**
     * Test: Retrieve Stats from memcached daemon.
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     * @return void
     * @access protected
     */
    // public function testRetrieveStats()
    // {
    //     $stats = $this->client->stats();

    //     $this->assertTrue($this->client->set($this->key, $this->value));
    //     $this->assertEquals(
    //         $this->value,
    //         $this->client->get($this->key)
    //     );

    //     # Mostly the first key
    //     $this->assertArrayHasKey(
    //         'pid',
    //         $stats
    //     );

    //     # Mostly the last key
    //     $this->assertArrayHasKey(
    //         'evictions',
    //         $stats
    //     );

    //     $stats = $this->client->stats(Client::STATS_TYPE_ITEMS);

    //     $this->assertArrayHasKey(
    //         'items',
    //         $stats
    //     );

    //     $stats = $this->client->stats(Client::STATS_TYPE_SLABS);

    //     $this->assertArrayHasKey(
    //         'active_slabs',
    //         $stats
    //     );

    //     $this->assertGreaterThanOrEqual(
    //         1,
    //         $stats['active_slabs']
    //     );

    //     $slabs = $stats['active_slabs'];

    //     $cachedump = array();

    //     for ($i = 1; $i <= $slabs; ++$i) {
    //         $cachedumpTemp = $this->client->stats(
    //             Client::STATS_TYPE_CACHEDUMP,
    //             $i,
    //             Client::CACHEDUMP_ITEMS_MAX
    //         );

    //         $cachedump = array_merge_recursive(
    //             $cachedump,
    //             $cachedumpTemp
    //         );
    //     }

    //     $this->assertArrayHasKey(
    //         $this->key,
    //         $cachedump
    //     );
    // }

    /**
     * Cleanup after single test. Remove the key created for tests.
     *
     * @author Benjamin Carl <opensource@clickalicious.de>
     * @return void
     * @access protected
     */
    protected function tearDown()
    {
        $this->client->delete($this->key);
    }
}
