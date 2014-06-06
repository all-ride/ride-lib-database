<?php

namespace ride\library\database\manipulation\expression;

use ride\library\database\exception\DatabaseException;

/**
 * Plain SQL expression
 */
class SqlExpression extends Expression {

    /**
     * The SQL of this expression
     * @var string
     */
    private $sql;

    /**
     * Construct a new SQL expression
     * @param string $sql
     * @return null
     * @throws \ride\library\database\exception\DatabaseException when the sql
     * is empty or not a string
     */
    public function __construct($sql) {
    	$this->setSql($sql);
    }

    /**
     * Set the SQL for this expression
     * @param string $sql
     * @return null
     * @throws \ride\library\database\exception\DatabaseException when the sql
     * is empty or not a string
     */
    private function setSql($sql) {
        if ($sql === null) {
            $this->sql = 'NULL';
            return;
        }

        if (is_bool($sql)) {
            $this->sql = $sql ? '1' : '0';
            return;
        }

		if (!is_scalar($sql) || $sql === '') {
			throw new DatabaseException('Provided sql is empty');
		}

        $this->sql = $sql;
    }

    /**
     * Get the SQL of this expression
     * @return string the SQL
     */
    public function getSql() {
        return $this->sql;
    }

}