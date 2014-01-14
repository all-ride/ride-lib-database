<?php

namespace pallo\library\database;

use pallo\library\database\driver\Driver;

use \PHPUnit_Framework_TestCase;

use pallo\library\database\exception\DatabaseException;

class DatabaseManagerTest extends PHPUnit_Framework_TestCase {

// 	const DRIVER_MOCK = 'pallo\\library\\database\\driver\\DriverMock';

//     private $configIOMock;

//     protected function setUp() {
//         $browser = new GenericFileBrowser(new File(getcwd()));
//         $this->configIOMock = new ConfigIOMock();

//         $this->pallo = new Zibo($browser, $this->configIOMock);
//     }

//     public function testConstructLoadsDrivers() {
//     	$config = array(
//             'driver' => array(
//                 'protocol' => self::DRIVER_MOCK,
//             ),
//         );
//         $this->configIOMock->setValues('database', $config);

//         $manager = new DatabaseManager($this->pallo);

//         $drivers = Reflection::getProperty($manager, 'drivers');

//         $this->assertTrue(is_array($drivers));
//         $this->assertEquals($config['driver'], $drivers);
//     }

//     public function testConstructLoadsConnections() {
//     	$name = 'name';
//     	$config = array(
//             'driver' => array(
//                 'protocol' => self::DRIVER_MOCK,
//             ),
//             'connection' => array(
//                 $name => 'protocol://server/database',
//             ),
//         );
//         $this->configIOMock->setValues('database', $config);

//         $manager = new DatabaseManager($this->pallo);

//         $connections = Reflection::getProperty($manager, 'connections');

//         $this->assertTrue(is_array($connections));
//         $this->assertNotNull($connections[$name]);
//     }

//     public function testConstructHasDrivers() {
//         $manager = new DatabaseManager($this->pallo);

//         $drivers = Reflection::getProperty($manager, 'drivers');

//         $this->assertTrue(is_array($drivers));
//     }

//     public function testConstructHasConnections() {
//         $manager = new DatabaseManager($this->pallo);

//         $connections = Reflection::getProperty($manager, 'connections');

//         $this->assertTrue(is_array($connections));
//     }

//     public function testRegisterDriver() {
//         $manager = new DatabaseManager($this->pallo);
//         $manager->registerDriver('protocol', self::DRIVER_MOCK);

//         $drivers = Reflection::getProperty($manager, 'drivers');

//         $this->assertTrue(in_array(self::DRIVER_MOCK, $drivers));
//     }

//     /**
//      * @expectedException pallo\library\database\exception\DatabaseException
//      */
//     public function testRegisterDriverThrowsExceptionWithEmptyProtocol() {
//         $manager = new DatabaseManager($this->pallo);
//         $manager->registerDriver('', self::DRIVER_MOCK);
//     }

//     /**
//      * @expectedException pallo\library\database\exception\DatabaseException
//      */
//     public function testRegisterDriverThrowsExceptionWithEmptyDriver() {
//         $manager = new DatabaseManager($this->pallo);
//         $manager->registerDriver('protocol', '');
//     }

//     /**
//      * @expectedException pallo\library\database\exception\DatabaseException
//      */
//     public function testRegisterDriverThrowsExceptionWithInvalidDriver() {
//         $driver = 'pallo\\library\\database\\DatabaseManager';
//         $manager = new DatabaseManager($this->pallo);
//         $manager->registerDriver('invalid', $driver);
//     }

//     /**
//      * @expectedException pallo\library\database\exception\DatabaseException
//      */
//     public function testGetConnectionThrowsExceptionWhenConnectionNotFound() {
//     	$manager = new DatabaseManager($this->pallo);
//         $manager->getConnection();
//     }

//     public function testRegisterConnection() {
//     	$manager = new DatabaseManager($this->pallo);

//     	$manager->registerDriver('protocol', self::DRIVER_MOCK);

//         $connectionName = 'test';
//         $manager->registerConnection($connectionName, new Dsn('protocol://server/database'));

//         $connections = Reflection::getProperty($manager, 'connections');
//         $this->assertArrayHasKey($connectionName, $connections);
//     }

//     /**
//      * @expectedException pallo\library\database\exception\DatabaseException
//      */
//     public function testRegisterConnectionThrowsExceptionWhenNameIsEmpty() {
//     	$manager = new DatabaseManager($this->pallo);
//         $manager->registerConnection('', new Dsn('protocol://server/database'));
//     }

//     /**
//      * @expectedException pallo\library\database\exception\DatabaseException
//      */
//     public function testRegisterConnectionThrowsExceptionWhenProtocolHasNoDriver() {
//     	$manager = new DatabaseManager($this->pallo);
//         $dsn = new Dsn('protocol://server/database');
//         $manager->registerConnection('test', $dsn);
//     }

//     public function testGetConnectionWithConnectionName() {
//     	$manager = new DatabaseManager($this->pallo);

//     	$manager->registerDriver('protocol', self::DRIVER_MOCK);

//         $connectionName = 'test';
//         $dsn = new Dsn('protocol://server/database');
//         $manager->registerConnection($connectionName, $dsn);

//         $connection = $manager->getConnection($connectionName);
//         $this->assertTrue($connection instanceof Driver, 'connection is not a Driver');
//         $this->assertTrue($connection->isConnected(), 'connection is not connected');
//     }

//     public function testGetConnectionWithoutConnectionName() {
//     	$manager = new DatabaseManager($this->pallo);

//         $manager->registerDriver('protocol', self::DRIVER_MOCK);

//         $connectionName = 'test';
//         $dsn = new Dsn('protocol://server/database');
//         $manager->registerConnection($connectionName, $dsn);

//         $connection = $manager->getConnection();
//         $this->assertTrue($connection instanceof Driver, 'connection is not a Driver');
//         $this->assertTrue($connection->isConnected(), 'connection is not connected');
//     }

//     /**
//      * @expectedException pallo\library\database\exception\DatabaseException
//      */
//     public function testSetDefaultConnectionThrowsExceptionWhenNameIsEmpty() {
//     	$manager = new DatabaseManager($this->pallo);
//         $manager->setDefaultConnectionName('');
//     }

//     /**
//      * @expectedException pallo\library\database\exception\DatabaseException
//      */
//     public function testSetDefaultConnectionThrowsExceptionWhenNameDoesNotExist() {
//     	$manager = new DatabaseManager($this->pallo);
//         $manager->setDefaultConnectionName('unexistant');
//     }

}