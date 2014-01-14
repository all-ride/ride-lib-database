<?php

namespace pallo\library\database\manipulation\condition;

use pallo\library\database\manipulation\expression\SqlExpression;

use \PHPUnit_Framework_TestCase;

class NestedConditionTest extends PHPUnit_Framework_TestCase {

    private $condition;

    public function setUp() {
        $this->condition = new SimpleCondition(new SqlExpression('left'), new SqlExpression('right'), Condition::OPERATOR_EQUALS);
    }

	public function testConstruct() {
		$condition = new NestedCondition();
		$this->assertEquals(array(), $condition->getParts());
	}

	/**
	 * @dataProvider providerAddConditionThrowsExceptionWhenInvalidOperatorPassed
	 * @expectedException pallo\library\database\exception\DatabaseException
	 */
	public function testAddConditionThrowsExceptionWhenInvalidOperatorPassed($operator) {
		$condition = new NestedCondition();
		$condition->addCondition($this->condition, $operator);
	}

	public function providerAddConditionThrowsExceptionWhenInvalidOperatorPassed() {
		return array(
            array(''),
            array('test'),
            array($this),
		);
	}

}