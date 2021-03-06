<?php
/**
 * Scary - A simple testing for scarying
 *
 * @category   Scary Testing
 * @package    Rammy Labs
 *
 * @author     Vlexfid
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT Public License
 *
 * @clone    https://github.com/vlexfid/session-scary 
 *
 * PHPUnit Test v6.5
 */
namespace Moviet\Testing;

use Moviet\Session\Scary;
use PHPUnit\Framework\TestCase;

/**
 * You can follow the example method
 * 
 * It Works like charm
*/
class ScaryTest extends TestCase
{
	public $session;
	
	public function setUp() 
	{
		$this->session = new Scary;
	}
	
	public function test_Session_Key_Cannot_Be_Empty()
	{
		$mock = $this->createMock(Scary::class);

		$mock->method('set')
			->willReturn('session key');

		$this->assertEquals('session key', $mock->set('okay'));

		$null = empty($mock->set('okay'));

		$this->assertFalse($null);
	}
	
	public function test_Session_Value_Cannot_Be_Empty()
	{
		$mock = $this->createMock(Scary::class);

		$mock->method('value')
			->willReturn('my session value');

		$this->assertEquals('my session value', $mock->value('okay'));

		$null = empty($mock->value('okay'));

		$this->assertFalse($null);
	}

	public function test_Return_Stub_setScary()
	{		
		$stub = $this->createMock(Scary::class);

		$stub->method('set')->willReturn('Scary');

		$this->assertEquals('Scary', $stub->set('Scary'));
	}

	public function test_Scary_Has_Been_Set()
	{	
		$this->session->set('Session_Test')->value('Test')->get();

		$scare = $this->session->read('Session_Test');

		$this->assertInternalType('string', $scare, gettype($scare));
	}

	public function test_Return_Stub_Scary_setIncrement()
	{
		$stub = $this->createMock(Scary::class);

		$stub->method('inc')->willReturn(0);

		$this->assertSame(0, $stub->inc(0));
	}

	public function test_Scary_Not_Increment()
	{
		$this->session->set('Session_Test')->value('Test')->inc(0)->get();

		if ($this->session->flinc('Session_Test') !== true) {

			$increment = true;
		}

		$this->assertTrue($increment);
	}
	
	public function test_With_Zero_Time_To_Live()
	{
		$stub = $this->createMock(Scary::class);

		$stub->method('ttl')->willReturn(0);

		$this->assertSame(0, $stub->ttl(0));
	}
	
	public function test_Time_To_Live_Method()
	{
		$mock = $this->createMock(Scary::class);

		$mock->method('ttl')
			->willReturn(5);
		
		$this->assertEquals(5, $mock->ttl(5));

		$null = empty($mock->ttl(5));

		$this->assertFalse($null);
	}

	public function test_Scary_Was_Already_Exists()
	{
		$scary = $this->session->exist('Session_Test');

		$this->assertTrue($scary);
	}

	public function test_Scary_Does_Not_Exists()
	{
		$scary = $this->session->exist('Session_False');

		$this->assertFalse($scary);
	}

	public function test_Scary_Was_Replaced()
	{
		$scare = $this->session->read('Session_Test');

		$scary = $this->session->change('Session_Test','Replace Test');

		$this->assertNotEquals($scare, $scary);
	}

	public function test_Return_Stub_Scary_setExpired()
	{
		$stub = $this->createMock(Scary::class);

		$stub->method('ttl')->willReturn(5);

		$this->assertSame(5, $stub->ttl(5));
	}

	public function test_Scary_Has_Expired()
	{		
		if ($this->session->exist('Session_Expired')) {			
			
			$this->session->set('Session_Expired')->value('Expired')->ttl(0)->get();
		
		} else {
			$scary = false;	
		}
		
		$this->assertFalse($scary);
	}

	public function test_Read_Multiple_Scary()
	{
		$this->session->mset('Session_Multiple')
					->mkey(['Exist','Increment','Expired'])
					->mval(['Exist','Increment','Expired'])
					->swap();

		$scare = $this->session->read('Session_Multiple','Exist');

		$value = 'Exist';

		$this->assertEquals($scare, $value);
	}

	public function test_Return_multiScary_Set()
	{
		$stub = $this->createMock(Scary::class);

		$stub->method('mset')->willReturn('multi session');

		$this->assertEquals('multi session', $stub->mset('multi session'));
	}

	public function test_Return_multiScary_Key()
	{
		$stub = $this->createMock(Scary::class);

		$stub->method('mkey')->willReturn('multi key');

		$this->assertEquals('multi key', $stub->mkey('multi key'));
	}

	public function test_Return_multiScary_Value()
	{
		$stub = $this->createMock(Scary::class);

		$stub->method('mval')->willReturn('multi value');

		$this->assertEquals('multi value', $stub->mval('multi value'));
	}

	public function test_Replace_Multiple_Scary()
	{
		$incres = $this->session->read('Session_Multiple','Increment');

		$this->session->mchange('Session_Multiple','Increment','Decrement');

		$replace = $this->session->read('Session_Multiple','Increment');

		$this->assertNotEquals($incres, $replace);
	}
	
	public function test_Regenerate_New_Id_Scary()
	{
		$newId = $this->session->newId('Session_Multiple');

		$this->assertNull($newId);
	}

	public function test_Regenerate_Refresh_Scary()
	{
		$refreshId = $this->session->refresh('Session_Multiple');

		$this->assertNull($refreshId);
	}

	public function test_Multiple_Scary_setExpired()
	{
		$this->session->mset('Session_Multi_Expired')
					->mkey(['Orange','Banana','Purple'])
					->mval(['Oranye','Pisang','Chocolato'])
					->swap();

		$expired = $this->session->live('Session_Multi_Expired', 0);

		$this->assertTrue($expired);
	}

	public function test_Remove_Single_Scary()
	{
		$remove = $this->session->trash('Session_Multi_Expired');

		$this->assertFalse($remove);
	}

	public function test_Remove_Multi_Scary()
	{
		$remove = $this->session->trash('Session_Multi_Expired','Session_Multiple');

		$this->assertFalse($remove);
	}

	public function test_Destroy_All_Scary()
	{
		$destroy = $this->session->clean('Session_Multiple');
		
		if (!session_id() ? session_start() :  @session_start());

		$this->assertFalse($destroy);
	}

	public function tearDown() {}
}
