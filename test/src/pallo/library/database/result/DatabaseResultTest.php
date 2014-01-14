<?php

namespace pallo\library\database\result;

use \PHPUnit_Framework_TestCase;

class DatabaseResultTest extends PHPUnit_Framework_TestCase {

	protected $result;

	public function setUp() {
		$this->result = new DatabaseResult('sql');
	}

	/**
	 * @expectedException pallo\library\database\exception\DatabaseException
	 */
    public function testGetFirstThrowsExceptionWhenRowCountIsZero() {
        $this->result->getFirst();
    }

	/**
	 * @expectedException pallo\library\database\exception\DatabaseException
	 */
    public function testGetLastThrowsExceptionWhenRowCountIsZero() {
        $this->result->getLast();
    }

}