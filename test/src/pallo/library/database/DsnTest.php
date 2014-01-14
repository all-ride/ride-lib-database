<?php

namespace pallo\library\database;

use \PHPUnit_Framework_TestCase;

class DsnTest extends PHPUnit_Framework_TestCase {

    public function testConstructWithMysqlDatabase() {
        $string = 'mysql://username:password@localhost:3306/database';

        $dsn = new Dsn($string);
        $this->assertEquals('mysql', $dsn->getProtocol());
        $this->assertEquals('localhost', $dsn->getHost());
        $this->assertEquals('3306', $dsn->getPort());
        $this->assertEquals('username', $dsn->getUsername());
        $this->assertEquals('password', $dsn->getPassword());
        $this->assertEquals('database', $dsn->getDatabase());

        $this->assertEquals($string, (string) $dsn);
        $this->assertEquals('mysql://username:*****@localhost:3306/database', $dsn->getProtectedDsn());

        $string = 'mysql://username@localhost:3306/database';

        $dsn = new Dsn($string);
        $this->assertEquals('username', $dsn->getUsername());
        $this->assertNull($dsn->getPassword());
    }

    public function testConstructWithSqliteDatabase() {
        $dsn = new Dsn('sqlite:///var/lib/sqlite/file.db');
        $this->assertEquals('sqlite', $dsn->getProtocol());
        $this->assertEquals('', $dsn->getHost());
        $this->assertEquals('', $dsn->getPort());
        $this->assertEquals('', $dsn->getUsername());
        $this->assertEquals('', $dsn->getPassword());
        $this->assertEquals('file.db', $dsn->getDatabase());
        $this->assertEquals('/var/lib/sqlite/file.db', $dsn->getPath());
    }

    /**
     * @dataProvider providerConstructThrowsExceptionWhenInvalidDsnPassed
     * @expectedException pallo\library\database\exception\DatabaseException
     */
    public function testConstructThrowsExceptionWhenInvalidDsnPassed($dsn) {
        new Dsn($dsn);
    }

    public function providerConstructThrowsExceptionWhenInvalidDsnPassed() {
        return array(
            array(''),
            array('test'),
        );
    }

}