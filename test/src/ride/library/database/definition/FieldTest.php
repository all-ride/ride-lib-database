<?php

namespace ride\library\database\definition;

use \PHPUnit_Framework_TestCase;

class FieldTest extends PHPUnit_Framework_TestCase {

    /**
     * @expectedException ride\library\database\exception\DatabaseException
     */
    public function testConstructThrowsExceptionWhenEmptyNamePassed() {
        new Field('', 'type');
    }

    /**
     * @expectedException ride\library\database\exception\DatabaseException
     */
    public function testConstructThrowsExceptionWhenEmptyTypePassed() {
        new Field('name', '');
    }

    /**
     * @expectedException ride\library\database\exception\DatabaseException
     */
    public function testSetIsAutoNumberingThrowsExceptionWhenNoBooleanPassed() {
        $field = new Field('name', 'type');
        $field->setIsAutoNumbering('test');
    }

    /**
     * @expectedException ride\library\database\exception\DatabaseException
     */
    public function testSetIsPrimaryKeyThrowsExceptionWhenNoBooleanPassed() {
        $field = new Field('name', 'type');
        $field->setIsPrimaryKey('test');
    }

    /**
     * @expectedException ride\library\database\exception\DatabaseException
     */
    public function testSetIsIndexedThrowsExceptionWhenNoBooleanPassed() {
        $field = new Field('name', 'type');
        $field->setIsIndexed('test');
    }

}