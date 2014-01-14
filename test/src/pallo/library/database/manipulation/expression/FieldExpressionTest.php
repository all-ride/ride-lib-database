<?php

namespace pallo\library\database\manipulation\expression;

use \PHPUnit_Framework_TestCase;

class FieldExpressionTest extends PHPUnit_Framework_TestCase {

    public function testConstruct() {
        $field = new FieldExpression('field');

        $this->assertEquals('field', $field->getName());
        $this->assertNull($field->getTable());
        $this->assertNull($field->getAlias());
    }

    /**
     * @expectedException pallo\library\database\exception\DatabaseException
     */
    public function testConstructThrowsExceptionWhenNameIsEmpty() {
        new FieldExpression('');
    }

    /**
     * @expectedException pallo\library\database\exception\DatabaseException
     */
    public function testConstructThrowsExceptionWhenAliasIsEmpty() {
        new FieldExpression('field', null, '');
    }

}