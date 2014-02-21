<?php

namespace ride\library\database\manipulation\statement;

use ride\library\database\manipulation\expression\TableExpression;

use \PHPUnit_Framework_TestCase;

class DeleteStatementTest extends PHPUnit_Framework_TestCase {

	private $statement;

	public function setUp() {
		$this->statement = new DeleteStatement();
	}

	public function testAddTable() {
		$table = new TableExpression('table');

		$this->statement->addTable($table);

		$tables = $this->statement->getTables();

		$expected = array('table' => $table);

		$this->assertEquals($expected, $tables);
	}

	/**
	 * @expectedException ride\library\database\exception\DatabaseException
	 */
	public function testAddTableThrowsExceptionWhenAlreadyAddedTable() {
		$table = new TableExpression('table');

		$this->statement->addTable($table);
		$this->statement->addTable($table);
	}

}