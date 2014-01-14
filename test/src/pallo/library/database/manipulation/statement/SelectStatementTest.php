<?php

namespace pallo\library\database\manipulation\statement;

use pallo\library\database\manipulation\expression\TableExpression;

use \PHPUnit_Framework_TestCase;

class SelectStatementTest extends PHPUnit_Framework_TestCase {

	private $statement;

	public function setUp() {
		$this->statement = new SelectStatement();
	}

    public function testAddTable() {
        $table1 = new TableExpression('tableName');
        $table2 = new TableExpression('tableName2', 'table2Alias');

        $this->statement->addTable($table1);
        $this->statement->addTable($table2);

        $tables = $this->statement->getTables();

        $expected = array(
            'tableName' => $table1,
            'table2Alias' => $table2,
        );

        $this->assertEquals($expected, $tables);
    }

	/**
     * @dataProvider providerSetLimitThrowsExceptionWhenInvalidValueProvided
     * @expectedException pallo\library\database\exception\DatabaseException
	 */
	public function testSetLimitThrowsExceptionWhenInvalidValueProvided($count, $offset) {
        $this->statement->setLimit($count, $offset);
	}

	public function providerSetLimitThrowsExceptionWhenInvalidValueProvided() {
		return array(
            array(-15, 0),
            array('test', 0),
            array(15, 'test'),
            array(15, -15),
		);
	}

}