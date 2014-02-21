<?php

namespace ride\library\database\manipulation\condition;

use ride\library\database\exception\DatabaseException;

/**
 * Plain SQL condition
 */
class SqlCondition extends Condition {

    /**
     * The SQL of this condition
     * @var string
     */
    private $sql;

    /**
     * Constructs a new SQL condition
     * @param string $sql The SQL
     * @return null
     * @throws ride\library\database\exception\DatabaseException when the
     * provided SQL is empty or not a string
     */
    public function __construct($sql) {
    	$this->setSql($sql);
    }

    /**
     * Sets the SQL for this condition
     * @param string $sql The SQL
     * @return null
     * @throws ride\library\database\exception\DatabaseException when the
     * provided SQL is empty or not a string
     */
    private function setSql($sql) {
		if (!is_string($sql) || !$sql) {
			throw new DatabaseException('Provided sql is empty or not a valid string');
		}

        $this->sql = $sql;
    }

    /**
     * Gets the SQL of this condition
     * @return string The SQL
     */
    public function getSql() {
        return $this->sql;
    }

}