<?php

namespace pallo\library\database\driver;

use pallo\library\database\exception\DatabaseException;
use pallo\library\database\manipulation\statement\Statement;
use pallo\library\database\manipulation\GenericStatementParser;
use pallo\library\database\Dsn;
use pallo\library\log\Log;

/**
 * Abstract driver for a database connection
 */
abstract class AbstractDriver implements Driver {

    /**
     * Name for the log source
     * @var string
     */
    const LOG_SOURCE = 'database';

    /**
     * DSN of the connection
     * @var pallo\library\database\Dsn
     */
    protected $dsn;

    /**
     * Flag to see if a transaction is started
     * @var boolean
     */
    protected $isTransactionStarted;

    /**
     * Parser for statement objects
     * @var pallo\library\database\manipulation\StatementParser
     */
    protected $statementParser;

    /**
     * Instance of the log
     * @var pallo\library\log\Log
     */
    protected $log;

    /**
     * Constructs a new connection with a DSN
     * @param pallo\library\database\Dsn $dsn DSN with the connection parameters
     * @return null
     */
    public function __construct(Dsn $dsn) {
        $this->dsn = $dsn;
        $this->isTransactionStarted = false;
        $this->statementParser = null;
        $this->log = null;
    }

    /**
     * Gets the DSN of this connection
     * @return pallo\library\database\Dsn DSN of this connection
     */
    public function getDsn() {
        return $this->dsn;
    }

    /**
     * Sets a Log to the connection
     * @param pallo\library\log\Log $log
     * @return null
     */
    public function setLog(Log $log = null) {
        $this->log = $log;
    }

    /**
     * Gets the log of this connection
     * @return pallo\library\log\Log
     */
    public function getLog() {
        return $this->log;
    }

    /**
     * Executes a statement on this connection
     * @param pallo\library\database\manipulation\statement\Statement $statement
     * Definition of the statement
     * @return pallo\library\database\DatabaseResult Result of the statement
     */
    public function executeStatement(Statement $statement) {
        $parser = $this->getStatementParser();

        $sql = $parser->parseStatement($statement);

        return $this->execute($sql);
    }

    /**
     * Quotes a identifier to use in SQL statements
     * @param string $identifier Identifier to quote
     * @return string Quoted identifier
     * @throws pallo\library\database\exception\DatabaseException when the
     * provided identifier is empty or not a scalar value
     */
    public function quoteIdentifier($identifier) {
        if (!is_string($identifier) || !$identifier) {
            throw new DatabaseException('Provided identifier is empty');
        }

        return $identifier;
    }

    /**
     * Quotes a value to use in SQL statements
     * @param string $value Value to quote
     * @return string Quoted value
     * @throws pallo\library\database\exception\DatabaseException when the
     * provided value is not a scalar value
     */
    public function quoteValue($value) {
        if ($value !== null && !is_scalar($value)) {
            throw new DatabaseException('Provided value should be scalar');
        }

        return $value;
    }

    /**
     * Gets the statement parser
     * @return pallo\library\database\manipulation\StatementParser
     */
    public function getStatementParser() {
        if ($this->statementParser === null) {
            $this->statementParser = new GenericStatementParser($this);
        }

        return $this->statementParser;
    }

    /**
     * Begins a new transaction
     * @return boolean True is a new transaction is started, false if a
     * transaction is already in progress
     */
    public function beginTransaction() {
        if ($this->isTransactionStarted()) {
            return false;
        }

        $this->execute('BEGIN');

        $this->isTransactionStarted = true;

        return $this->isTransactionStarted;
    }

    /**
     * Commits the transaction in progress
     * @return null
     */
    public function commitTransaction() {
        if (!$this->isTransactionStarted()) {
            throw new DatabaseException('No transaction to commit, use beginTransaction first');
        }

        $this->execute('COMMIT');

        $this->isTransactionStarted = false;
    }

    /**
     * Performs a rollback on the transaction in progress
     * @return null
     */
    public function rollbackTransaction() {
        if (!$this->isTransactionStarted()) {
            throw new DatabaseException('No transaction to rollback, use beginTransaction first');
        }

        $this->execute('ROLLBACK');

        $this->isTransactionStarted = false;
    }

    /**
     * Checks whether a transaction is in progress
     * @return boolean true if a transaction is in progress, false otherwise
     */
    public function isTransactionStarted() {
        return $this->isTransactionStarted;
    }

}