<?php

namespace ride\library\database\manipulation\expression;

use ride\library\database\exception\DatabaseException;

/**
 * Base expression class
 */
abstract class AliasExpression extends Expression {

    /**
     * Alias for this expression when used as a select expression
     * @var string
     */
    protected $alias;

    /**
     * Set the alias of this expression
     * @param string $alias
     * @return null
     * @throws ride\library\database\exception\DatabaseException
     */
    public function setAlias($alias = null) {
        if ($alias === null) {
            $this->alias = null;

            return;
        }

        if (!is_string($alias) || !$alias) {
            throw new DatabaseException('Provided alias is empty');
        }

        $this->alias = $alias;
    }

    /**
     * Get the alias of this expression
     * @return string
     */
    public function getAlias() {
        return $this->alias;
    }

}