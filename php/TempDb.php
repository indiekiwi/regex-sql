<?php

/**
 * Class TempDb
 */
class TempDb
{
    const TEMP_TABLE_PLACEHOLDER = 'table_1';

    /**
     * @var string
     */
    private $_databaseFile = '';

    /**
     * @var string
     */
    private $_tableName = '';

    /**
     * @var array
     */
    private $_columns = [];

    /**
     * @var SQLite3|null
     */
    private $_db = null;

    /**
     * TempDb constructor
     *
     * @param string $tableName
     * @param array $columns
     * @param array $rows
     */
    public function __construct($tableName, $columns, $rows)
    {
        $time = microtime(true) * 10000;
        $this->_databaseFile = "db-workspace/{$time}.db";
        $this->_tableName = $tableName;
        $this->_db = new SQLite3($this->_databaseFile);
        $this->_columns = $columns;

        $this->_setup($rows);
    }

    /**
     * @param array $rows
     */
    private function _setup($rows)
    {
        $this->_createTable($this->_columns);

        foreach ($rows as $row) {
            $this->_insertRow($row);
        }
    }

    /**
     * @param array $columnsData
     */
    private function _createTable($columnsData)
    {
        $columnLine = [];
        foreach ($columnsData as $config) {
            $columnLine[] = "{$config['name']} {$config['type']}";
        }
        $query = sprintf(
            'CREATE TABLE %s (%s)',
            $this->_tableName,
            implode(',', $columnLine)
        );

        $this->_db->exec($query);
    }

    /**
     * @param array $row
     */
    private function _insertRow($row)
    {
        $columnList = $valuesList = [];
        foreach ($this->_columns as $config) {
            $columnList[] = $config['name'];
        }
        foreach ($row as $value) {
            if (is_numeric($value)) {
                $valuesList[] = $value;
            } else {
                $valuesList[] = "\"$value\"";
            }
        }

        $query = sprintf(
            'INSERT INTO %s (%s) VALUES (%s)',
            $this->_tableName,
            implode(', ', $columnList),
            implode(', ', $valuesList)
        );

        $this->_db->exec($query);
    }

    /**
     * @param string $query
     * @return array
     */
    public function select($query)
    {
        $results = [];
        $res = $this->_db->query(
            str_replace(self::TEMP_TABLE_PLACEHOLDER, $this->_tableName, $query)
        );
        while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
            $results[] = $row;
        }

        return $results;
    }

    public function __destruct()
    {
        `rm {$this->_databaseFile}`;
    }
}
