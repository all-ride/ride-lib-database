<?php

namespace ride\library\database\driver;

use ride\library\database\Dsn;

use \PHPUnit_Framework_TestCase;

class AbstractDriverTest extends PHPUnit_Framework_TestCase {

    private $driver;

    protected function setUp() {
        $dsn = new Dsn('mysql://localhost/database');
        $this->driver = $this->getMock('ride\\library\\database\\driver\\AbstractDriver', array('isConnected', 'connect', 'disconnect', 'ping', 'execute', 'getLastInsertId'), array($dsn));
    }

    /**
     * @dataProvider providerQuoteIdentifierThrowsDatabaseExceptionWhenInvalidValuePassed
     * @expectedException ride\library\database\exception\DatabaseException
     */
    public function testQuoteIdentifierThrowsDatabaseExceptionWhenInvalidValuePassed($value) {
        $this->driver->quoteIdentifier($value);
    }

    public function providerQuoteIdentifierThrowsDatabaseExceptionWhenInvalidValuePassed() {
    	return array(
            array(array()),
            array(''),
            array($this),
    	);
    }

    /**
     * @dataProvider providerQuoteValueThrowsExceptionWhenInvalidValuePassed
     * @expectedException ride\library\database\exception\DatabaseException
     */
    public function testQuoteValueThrowsExceptionWhenInvalidValuePassed($value) {
        $this->driver->quoteValue($value);
    }

    public function providerQuoteValueThrowsExceptionWhenInvalidValuePassed() {
        return array(
            array(array('element1')),
            array($this),
        );
    }

}