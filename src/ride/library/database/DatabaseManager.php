<?php

namespace ride\library\database;

use ride\library\database\definition\definer\Definer;
use ride\library\database\driver\Driver;
use ride\library\database\exception\DatabaseException;
use ride\library\log\Log;

use \Exception;
use \ReflectionClass;

/**
 * Manager of the database connections and drivers.
 */
class DatabaseManager {

    /**
     * Class name of the abstract database driver
     * @var string
     */
    const INTERFACE_DRIVER = 'ride\\library\\database\\driver\\Driver';

    /**
     * The Log
     * @var \ride\library\log\Log
     */
    protected $log;

    /**
     * Array with all the registered definers; the protocol of the driver as
     * key and the definer instance as value
     * @var array
     */
    protected $definers;

    /**
     * Array with all the registered drivers; the protocol of the driver as key
     * and the driver class name as value
     * @var array
     */
    protected $drivers;

    /**
     * Array with all the registered connections; the name as key and the
     * driver instance as value
     * @var array
     */
    protected $connections;

    /**
     * Name of the default connection
     * @var string
     */
    protected $defaultConnectionName;

    /**
     * Constructs a new database manager
     * @return null
     */
    public function __construct() {
        $this->log = null;
        $this->connections = array();
        $this->drivers = array();
        $this->definers = array();
        $this->defaultConnectionName = null;
    }

    /**
     * Disconnects all connections on destruction of the manager
     * @return null
     */
    public function __destruct() {
        foreach ($this->connections as $connection) {
            $connection->disconnect();
        }
    }

    /**
     * Sets the Log to the manager
     * @param \ride\library\log\Log $log
     * @return null
     */
    public function setLog(Log $log) {
        $this->log = $log;
    }

    /**
     * Hook to implement get[ConnectionName]Connection()
     * @param string $name Name of the invoked method
     * @param array $arguments Arguments for the method
     * @return \ride\library\database\driver\Driver
     * @throws Exception when the method is not a get[ConnectionName]Connection
     */
    public function __call($name, $arguments) {
        if (strpos($name, 'get') !== 0 && strpos(substr($name, -10), 'Connection') !== 0) {
            throw new DatabaseException('Could not invoke ' . $name . ': method does not exist');
        }

        return $this->getConnection(strtolower(substr($name, 3, -10)));
    }

    /**
     * Gets a registered database connection
     * @param string $name Name of the connection, skip this argument to get
     * the default connection
     * @param boolean $connect Set to false to skip connecting to the database
     * @return \ride\library\database\driver\Driver Instance of the database
     * connection
     * @throws \ride\library\database\exception\DatabaseException when the
     * database connection could not be found
     */
    public function getConnection($name = null, $connect = true) {
        if (!$name) {
            $name = $this->getDefaultConnectionName();
            if (!$name) {
                throw new DatabaseException('No connections set');
            }
        }

        if (!$this->hasConnection($name)) {
            throw new DatabaseException('Connection ' . $name . ' not found');
        }

        if ($connect && !$this->connections[$name]->isConnected()) {
            $this->connections[$name]->setLog($this->log);
            $this->connections[$name]->connect();
        }

        return $this->connections[$name];
    }

    /**
     * Gets all the database connections
     * @return array Array with the name of the connection as key and a
     * instance of Driver as value
     * @see ride\library\database\driver\Driver
     */
    public function getConnections() {
        if ($this->log) {
            foreach ($this->connections as $connection) {
                $connection->setLog($this->log);
            }
        }

        return $this->connections;
    }

    /**
     * Checks if a connection has been registered
     * @param string $name Name of the connection
     * @return boolean True if the connection exists, false otherwise
     */
    public function hasConnection($name) {
        if (!is_string($name) || !$name) {
            throw new DatabaseException('Provided connection name is empty or invalid');
        }

        return isset($this->connections[$name]);
    }

    /**
     * Sets the default connection
     * @param string $defaultConnectionName Name of the new default connection
     * @return null
     * @throws \ride\library\database\exception\DatabaseException when the
     * connection name is invalid or when the connection does not exist
     */
    public function setDefaultConnectionName($defaultConnectionName) {
        if (!$this->hasConnection($defaultConnectionName)) {
            throw new DatabaseException('Connection ' . $defaultConnectionName . ' does not exist');
        }

        $this->defaultConnectionName = $defaultConnectionName;
    }

    /**
     * Gets the name of the default connection
     * @return string Name of the default connection
     */
    public function getDefaultConnectionName() {
        return $this->defaultConnectionName;
    }

    /**
     * Registers a connection in the manager
     * @param string $name Name of the connection
     * @param Dsn $dsn DSN connection properties
     * @return null
     * @throws \ride\library\database\exception\DatabaseException when the name
     * is invalid or already registered and connected
     * @throws \ride\library\database\exception\DatabaseException when the
     * protocol has no driver available
     */
    public function registerConnection($name, Dsn $dsn) {
        if (!is_string($name) || !$name) {
            throw new DatabaseException('Provided database name is empty');
        }

        $protocol = $dsn->getProtocol();
        if (!isset($this->drivers[$protocol])) {
            throw new DatabaseException('Protocol ' . $protocol . ' has no database driver available');
        }

        if (isset($this->connections[$name]) && $this->connections[$name]->isConnected()) {
            throw new DatabaseException('Connection ' . $name . ' is already registered and connected. Disconnect the connection first');
        }

        $this->connections[$name] = new $this->drivers[$protocol]($dsn, $this->log);

        if ($this->defaultConnectionName == null) {
            $this->defaultConnectionName = $name;
        }
    }

    /**
     * Unregisters a connection from the manager
     * @param string $name Name of the connection
     * @return null
     * @throws \ride\library\database\exception\DatabaseException when the
     * name is invalid
     * @throws \ride\library\database\exception\DatabaseException when no
     * connection is registered with the provided name
     */
    public function unregisterConnection($name) {
        if (!$this->hasConnection($name)) {
            throw new DatabaseException('Connection ' . $name . ' is not registered');
        }

        if ($this->connections[$name]->isConnected()) {
            $this->connections[$name]->disconnect();
        }

        unset($this->connections[$name]);

        if ($this->defaultConnectionName == $name) {
            if ($this->connections) {
                foreach ($this->connections as $connectionName => $connection) {
                    $this->defaultConnectionName = $connectionName;

                    break;
                }
            } else {
                $this->defaultConnectionName = null;
            }
        }
    }

    /**
     * Gets the available drivers
     * @return array Array with the protocol as key and the driver class name
     * as value
     */
    public function getDrivers() {
    	return $this->drivers;
    }

    /**
     * Gets the driver for the provided protocol
     * @param string $protocol Name of the protocol
     * @return string Full class name of the driver
     */
    public function getDriver($protocol) {
        if (!$this->hasDriver($protocol)) {
            throw new DatabaseException('Could not get the driver for ' . $protocol . ': driver not registered');
        }

        return $this->drivers[$protocol];
    }

    /**
     * Checks if a driver has been registered
     * @param string $protocol Protocol of the driver
     * @return boolean True if the driver exists, false otherwise
     */
    public function hasDriver($protocol) {
        if (!is_string($protocol) || !$protocol) {
            throw new DatabaseException('Provided protocol is empty or invalid');
        }

        return isset($this->drivers[$protocol]);
    }

    /**
     * Registers a database driver with it's protocol in the manager
     * @param string $protocol Database protocol of this driver
     * @param string $className Class name of the driver
     * @return null
     * @throws \ride\library\database\exception\DatabaseException when the
     * protocol or class name is empty or invalid
     * @throws \ride\library\database\exception\DatabaseException when the
     * database driver does not exist or is not a valid driver class
     */
    public function registerDriver($protocol, $className) {
        if (!is_string($protocol) || !$protocol) {
            throw new DatabaseException('Provided database protocol is empty');
        }

        if (!is_string($className) || !$className) {
            throw new DatabaseException('Provided database driver class name is empty');
        }

        try {
            $reflection = new ReflectionClass($className);
        } catch (Exception $e) {
            throw new DatabaseException('Provided database driver class does not exist');
        }

        if (!$reflection->isSubclassOf(self::INTERFACE_DRIVER)) {
            throw new DatabaseException('Provided database driver class does not implement ' . self::INTERFACE_DRIVER);
        }

        $this->drivers[$protocol] = $className;
    }

    /**
     * Unregisters a driver from the manager
     * @param string $protocol Protocol of the connection
     * @return null
     * @throws \ride\library\database\exception\DatabaseException when the
     * protocol is invalid
     * @throws \ride\library\database\exception\DatabaseException when no
     * driver is registered with the provided protocol
     */
    public function unregisterDriver($protocol) {
        if (!$this->hasDriver($protocol)) {
            throw new DatabaseException('No driver registered for protocol ' . $protocol);
        }

        unset($this->drivers[$protocol]);
    }

    /**
     * Gets the definer for a database connection
     * @param \ride\library\database\driver\Driver $connection Instance of a
     * connection
     * @return \ride\library\database\definition\definer\Definer
     * @throws \ride\library\database\exception\DatabaseException when no
     * definer is registered for the protocol of the connection
     */
    public function getDefiner(Driver $connection) {
        $protocol = $connection->getDsn()->getProtocol();

        if (!$this->hasDefiner($protocol)) {
            throw new DatabaseException('No definer registered for ' . $protocol);
        }

        $definer = clone $this->definers[$protocol];
        $definer->setConnection($connection);

        return $definer;
    }

    /**
     * Checks if a definer has been registered
     * @param string $protocol Protocol of the definer
     * @return boolean True if the definer exists, false otherwise
     */
    public function hasDefiner($protocol) {
        if (!is_string($protocol) || !$protocol) {
            throw new DatabaseException('Provided protocol is empty or invalid');
        }

        return isset($this->definers[$protocol]);
    }

    /**
     * Registers a definer with it's protocol in the manager
     * @param string $protocol Database protocol of this definer
     * @param \ride\library\database\definition\definer\Definer $definer
     * Instance of the definer
     * @return null
     * @throws \ride\library\database\exception\DatabaseException when the
     * protocol is empty or invalid
     */
    public function registerDefiner($protocol, Definer $definer) {
        if (!is_string($protocol) || !$protocol) {
            throw new DatabaseException('Provided database protocol is empty');
        }

        $this->definers[$protocol] = $definer;
    }

    /**
     * Unregisters a definer from the manager
     * @param string $protocol Protocol of the definer
     * @return null
     * @throws \ride\library\database\exception\DatabaseException when the
     * protocol is invalid
     * @throws \ride\library\database\exception\DatabaseException when no
     * definer is registered with the provided protocol
     */
    public function unregisterDefiner($protocol) {
        if (!$this->hasDefiner($protocol)) {
            throw new DatabaseException('No definer registered for protocol ' . $protocol);
        }

        unset($this->definers[$protocol]);
    }

}
