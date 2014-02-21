<?php

namespace ride\library\database\driver;

use ride\library\database\manipulation\statement\Statement;
use ride\library\database\Dsn;
use ride\library\log\Log;

/**
 * Interface for a driver of a database connection
 */
interface Driver {

    /**
     * Null value
     * @var string
     */
    const VALUE_NULL = 'NULL';

    /**
     * Constructs a new connection with a DSN
     * @param ride\library\database\Dsn $dsn DSN with the connection parameters
     * @return null
     */
    public function __construct(Dsn $dsn);

    /**
     * Gets the DSN of this connection
     * @return ride\library\database\Dsn DSN of this connection
     */
    public function getDsn();

    /**
     * Sets a Log to the connection
     * @param ride\library\log\Log $log
     * @return null
     */
    public function setLog(Log $log = null);

    /**
     * Checks whether this driver is connected
     * @return boolean True if the driver is connected, false otherwise
     */
    public function isConnected();

    /**
     * Connects this driver
     * @return null
     */
    public function connect();

    /**
     * Disconnects this driver
     * @return null
     */
    public function disconnect();

    /**
     * Executes an SQL script on this connection
     * @param string $sql SQL script to execute
     * @return ride\library\database\result\DatabaseResult Instance of a
     * database result
     */
    public function execute($sql);

    /**
     * Executes a statement on this connection
     * @param ride\library\database\manipulation\statement\Statement $statement
     * Definition of the statement
     * @return ride\library\database\result\DatabaseResult Instance of a
     * database result
     */
    public function executeStatement(Statement $statement);

    /**
     * Gets the primary key of the last inserted record
     * @param string $name Name of the sequence object
     * @return string Primary key of the last inserted record or null if no
     * record has been inserted yet
     */
    public function getLastInsertId($name = null);

    /**
     * Quotes a database identifier
     * @param string $identifier Identifier to quote
     * @return string Quoted identifier
     * @throws ride\library\database\exception\DatabaseException when the
     * provided identifier is empty or not a scalar value
     */
    public function quoteIdentifier($identifier);

    /**
     * Quotes a database value
     * @param string $value Value to quote
     * @return string Quoted value
     * @throws ride\library\database\exception\DatabaseException when the
     * provided value is not a scalar value
     */
    public function quoteValue($value);

    /**
     * Gets the statement parser, a parser which parses statement objects into
     * SQL
     * @return ride\library\database\manipulation\StatementParser
     */
    public function getStatementParser();

    /**
     * Begins a new transaction
     * @return boolean True is a new transaction is started, false if a
     * transaction is already in progress
     */
    public function beginTransaction();

    /**
     * Commits the transaction in progress
     * @return null
     */
    public function commitTransaction();

    /**
     * Performs a rollback of the transaction in progress
     * @return null
     */
    public function rollbackTransaction();

    /**
     * Checks whether a transaction is in progress
     * @return boolean True if a transaction is in progress, false otherwise
     */
    public function isTransactionStarted();

}