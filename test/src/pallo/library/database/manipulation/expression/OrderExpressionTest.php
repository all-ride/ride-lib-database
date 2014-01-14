<?php

namespace pallo\library\database\manipulation\expression;

use \PHPUnit_Framework_TestCase;

class OrderExpressionTest extends PHPUnit_Framework_TestCase {

    public function testConstruct() {
        $field = new FieldExpression('test');

        $order = new OrderExpression($field);

        $this->assertEquals($field, $order->getExpression());
        $this->assertEquals(OrderExpression::DIRECTION_ASC, $order->getDirection());
    }

    /**
     * @expectedException pallo\library\database\exception\DatabaseException
     */
    public function testConstructThrowsExceptionWhenEmptyDirectionPassed() {
        new OrderExpression(new FieldExpression('field'), '');
    }

    /**
     * @expectedException pallo\library\database\exception\DatabaseException
     */
    public function testConstructThrowsExceptionWhenInvalidDirectionPassed() {
        new OrderExpression(new FieldExpression('field'), 'test');
    }

}