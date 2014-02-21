<?php

namespace ride\library\database\manipulation\condition;

use \PHPUnit_Framework_TestCase;

class SqlConditionTest extends PHPUnit_Framework_TestCase {

	/**
	 * @dataProvider providerConstructThrowsExceptionWhenSqlIsInvalid
	 * @expectedException ride\library\database\exception\DatabaseException
	 */
	public function testConstructThrowsExceptionWhenSqlIsInvalid($value) {
		new SqlCondition($value);
	}

	public function providerConstructThrowsExceptionWhenSqlIsInvalid() {
		return array(
            array(''),
            array($this),
		);
	}

}