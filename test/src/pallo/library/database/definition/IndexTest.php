<?php

namespace pallo\library\database\definition;

use \PHPUnit_Framework_TestCase;

class IndexTest extends PHPUnit_Framework_TestCase {

    public function testConstruct() {
        $name = 'index';
        $fields = array(
            'id' => new Field('id', 'integer'),
            'name' => new Field('name', 'string'),
        );
        $index = new Index($name, $fields);

        $this->assertEquals($name, $index->getName());
        $this->assertEquals($fields, $index->getFields());
    }

    /**
     * @expectedException pallo\library\database\exception\DatabaseException
     */
    public function testConstructThrowsExceptionWhenEmptyNamePassed() {
        new Index('', array(new Field('id', 'integer')));
    }

    /**
     * @expectedException pallo\library\database\exception\DatabaseException
     */
    public function testConstructThrowsExceptionWhenInvalidNamePassed() {
        new Index($this, array(new Field('id', 'integer')));
    }

    /**
     * @expectedException pallo\library\database\exception\DatabaseException
     */
    public function testConstructThrowsExceptionWhenEmptyFieldsPassed() {
        new Index('index', array());
    }

    /**
     * @expectedException pallo\library\database\exception\DatabaseException
     */
    public function testConstructThrowsExceptionWhenInvalidFieldPassed() {
        new Index('index', array($this));
    }

}