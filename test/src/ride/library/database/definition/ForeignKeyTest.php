<?php

namespace ride\library\database\definition;

use \PHPUnit_Framework_TestCase;

class ForeignKeyTest extends PHPUnit_Framework_TestCase {

    /**
     * @expectedException ride\library\database\exception\DatabaseException
     */
    public function testConstructThrowsExceptionWhenEmptyFieldNamePassed() {
        new ForeignKey('', 'table', 'id');
    }

    /**
     * @expectedException ride\library\database\exception\DatabaseException
     */
    public function testConstructThrowsExceptionWhenEmptyReferenceTablePassed() {
        new ForeignKey('field', '', 'id');
    }

    /**
     * @expectedException ride\library\database\exception\DatabaseException
     */
    public function testConstructThrowsExceptionWhenEmptyReferenceFieldNamePassed() {
        new ForeignKey('field', 'table', '');
    }

}