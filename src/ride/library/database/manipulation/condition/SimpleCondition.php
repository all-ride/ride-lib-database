<?php

namespace ride\library\database\manipulation\condition;

use ride\library\database\exception\DatabaseException;
use ride\library\database\manipulation\expression\Expression;

/**
 * Simple condition in the form of
 * [expression 1] [comparison operator] [expression 2].
 * eg. city LIKE 'A%'
 */
class SimpleCondition extends Condition {

    /**
     * Comparison operator of this condition
     * @var string
     */
    private $operator;

    /**
     * Expression left of the operator
     * @var \ride\library\database\manipulation\expression\Expression
     */
    private $expressionLeft;

    /**
     * Expression right of the operator
     * @var \ride\library\database\manipulation\expression\Expression
     */
    private $expressionRight;

    /**
     * Constructs a new condition
     * @param \ride\library\database\manipulation\expression\Expression  $expressionLeft Expression
     * left of the comparison operator
     * @param \ride\library\database\manipulation\expression\Expression $expressionRight Expression
     * right of the comparison operator
     * @param string $operator comparison operator between the fields (=, <, >,
     * ...) (default =)
     * @return null
     */
    public function __construct(Expression $expressionLeft, Expression $expressionRight = null, $operator = null) {
        $this->expressionLeft = $expressionLeft;
        $this->expressionRight = $expressionRight;
        $this->setOperator($operator);
    }

    /**
     * Sets the comparison operator of this condition
     * @param string $operator Operator to compare the fields
     * @return null
     * @throws \ride\library\database\exception\DatabaseException when the
     * operator is empty or not a valid string
     */
    protected function setOperator($operator = null) {
    	if ($operator === null) {
    		$this->operator = self::OPERATOR_EQUALS;

    		return;
    	}

    	if (!is_string($operator) || !$operator) {
    		throw new DatabaseException('Provided operator is not a valid string');
    	}

    	$this->operator = strtoupper($operator);
    }

    /**
     * Gets the comparison operator of this condition
     * @return string Operator to compare the expressions
     */
    public function getOperator() {
        return $this->operator;
    }

    /**
     * Gets expression left of the comparison operator
     * @return \ride\library\database\manipulation\expression\Expression
     */
    public function getLeftExpression() {
        return $this->expressionLeft;
    }

    /**
     * Gets expression right of the comparison operator
     * @return \ride\library\database\manipulation\expression\Expression
     */
    public function getRightExpression() {
        return $this->expressionRight;
    }

}
