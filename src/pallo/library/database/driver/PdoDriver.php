<?php

namespace pallo\library\database\driver;

use pallo\library\database\exception\DatabaseException;
use pallo\library\database\result\PdoDatabaseResult;
use pallo\library\database\Dsn;

use \PDOException;
use \PDO;

/**
 * Generic PDO implementation of the database driver
 */
class PdoDriver extends AbstractDriver {

    /**
     * Quote of an identifier
     * @var string
     */
    const QUOTE_IDENTIFIER = '`';

    /**
     * The pdo of the database connection
     * @var PDO
     */
    protected $pdo;

    /**
     * Gets the instance of the PDO driver
     * @return PDO|null PDO if this driver is connected, null otherwise
     */
    public function getPdo() {
        return $this->pdo;
    }

    /**
     * Checks whether this driver is connected
     * @return boolean true if connected, false otherwise
     */
    public function isConnected() {
        return $this->pdo !== null ? true : false;
    }

    /**
     * Connects this connection
     * @return null
     * @throws pallo\library\database\mysql\exception\MysqlException when no
     * connection could be made with the host
     * @throws pallo\library\database\mysql\exception\MysqlException when the
     * database could not be selected
     */
    public function connect() {
        $protocol = $this->dsn->getProtocol();
        $host = $this->dsn->getHost();
        $port = $this->dsn->getPort();
        $username = $this->dsn->getUsername();
        $password = $this->dsn->getPassword();
        $database = $this->dsn->getDatabase();

        $dsn = $protocol . ':host=' . $host;
        if ($port) {
            $dsn .= ';port=' . $port;
        }
        $dsn .= ';dbname=' . $database;
//         $dsn .= ';charset=utf8'; // >= PHP 5.3.6

        $options = array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
//             PDO::MYSQL_ATTR_INIT_COMMAND => 'SET CHARACTER SET utf8; SET NAMES utf8',
//             PDO::ATTR_AUTOCOMMIT => false,
        );

        try {
            $this->pdo = new PDO($dsn, $username, $password, $options);

            if ($this->log) {
                $this->log->logDebug('Connected to ' . $this->dsn->getProtectedDsn(), null, self::LOG_SOURCE);
            }

            if ($protocol == 'mysql') {
                $this->pdo->query('SET CHARACTER SET utf8');
                $this->pdo->query('SET NAMES utf8');
            }
        } catch (PDOException $exception) {
            $this->pdo = null;

            throw new DatabaseException('Could not connect to ' . $protocol . '://' . $username . ':*****@' . $host . '/' . $database, 0, $exception);
        }
    }

    /**
     * Disconnects this connection
     * @return null
     */
    public function disconnect() {
        if (!$this->isConnected()) {
            return;
        }

        $this->pdo = null;

        if ($this->log) {
            $this->log->logDebug('Disconnected from ' . $this->dsn->getProtectedDsn(), null, self::LOG_SOURCE);
        }
    }

    /**
     * Executes an SQL script on the connection
     * @param string $sql SQL script
     * @return pallo\library\database\mysql\MysqlResult Result object
     * @throws pallo\library\database\exception\DatabaseException when the
     * provided SQL is empty
     * @throws pallo\library\database\exception\DatabaseException when not
     * connected to the database or when the SQL could not be executed
     */
    public function execute($sql) {
        if (!is_string($sql) || !$sql) {
            throw new DatabaseException('Provided SQL is empty');
        }

        if (!$this->isConnected()) {
            throw new DatabaseException('Not connected to the database');
        }

        try {
            if ($this->log) {
                $this->log->logDebug($sql, null, self::LOG_SOURCE);
            }

            $statement = $this->pdo->query($sql, PDO::FETCH_ASSOC);
        } catch (PDOException $exception) {
            throw new DatabaseException('Could not execute: ' . $sql, 0, $exception);
        }

        $result = new PdoDatabaseResult($sql, $statement);

        unset($statement);

        return $result;
    }

    /**
     * Gets the primary key of the last inserted record
     * @param string $name Name of the sequence object
     * @return string primary key of the last inserted record or null if no
     * record has been inserted yet
     */
    public function getLastInsertId($name = null) {
        $id = null;

        if ($this->isConnected()) {
            $id = $this->pdo->lastInsertId($name);
        }

        return $id;
    }

    /**
     * Quotes a database value
     * @param string $value value to quote
     * @return string quoted value
     * @throws pallo\library\database\exception\DatabaseException when the
     * provided value is not a scalar value
     */
    public function quoteValue($value) {
        parent::quoteValue($value);

        if (is_bool($value)) {
            return $value ? 1 : 0;
        }

        $valueLength = strlen($value);

        if (is_numeric($value)) {
            return $value;
        }

        if (is_null($value) || $value === self::VALUE_NULL) {
            return self::VALUE_NULL;
        }

        if (!$this->isConnected()) {
            $this->connect();
        }

        $quotedValue = $this->pdo->quote($value);
        if ($quotedValue === false) {
            throw new DatabaseException('Could not quote ' . $value);
        }

        return $quotedValue;
    }

    /**
     * Quotes a database identifier
     * @param string $identifier
     * @return string quoted identifier
     * @throws pallo\library\database\exception\DatabaseException when the
     * provided identifier is not a scalar value or when $identifier is empty
     */
    public function quoteIdentifier($identifier) {
        parent::quoteIdentifier($identifier);

        $identifier = str_replace(self::QUOTE_IDENTIFIER, self::QUOTE_IDENTIFIER . self::QUOTE_IDENTIFIER, $identifier);

        return self::QUOTE_IDENTIFIER . $identifier . self::QUOTE_IDENTIFIER;
    }

}