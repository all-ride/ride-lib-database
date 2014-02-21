<?php

namespace ride\library\database\manipulation\expression;

use \PHPUnit_Framework_TestCase;

class TableExpressionTest extends PHPUnit_Framework_TestCase {

    public function testConstruct() {
        $table = new TableExpression('table');

        $this->assertEquals('table', $table->getName());
        $this->assertNull($table->getAlias());
    }

    public function testConstructWithAlias() {
        $table = new TableExpression('table', 'alias');

        $this->assertEquals('table', $table->getName());
        $this->assertEquals('alias', $table->getAlias());
    }

    /**
     * @expectedException ride\library\database\exception\DatabaseException
     */
    public function testConstructThrowsExceptionWhenNameIsEmpty() {
        new TableExpression('');
    }

    /**
     * @expectedException ride\library\database\exception\DatabaseException
     */
    public function testConstructThrowsExceptionWhenAliasIsEmpty() {
        new TableExpression('table', '');
    }

}