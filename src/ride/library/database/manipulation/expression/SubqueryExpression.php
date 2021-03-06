<?php

namespace ride\library\database\manipulation\expression\condition;

use ride\library\database\manipulation\statement\SelectStatement;

/**
 * Subquery expression
 */
class SubqueryExpression extends Expression {

    /**
     * The select statement of this subquery expression
     * @var \ride\library\database\manipulation\statement\SelectStatement
     */
    private $statement;

    /**
     * Construct a new subquery expression
     * @param \ride\library\database\manipulation\statement\SelectStatement $selectStatement
     * @return null
     */
    public function __construct(SelectStatement $selectStatement) {
    	$this->statement = $selectStatement;
    }

    /**
     * Get the select statement of this subquery
     * @return \ride\library\database\manipulation\statement\SelectStatement
     */
    public function getStatement() {
        return $this->statement;
    }

}