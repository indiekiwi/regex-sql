<?php

/**
 * Class TempDb
 */
class TempDb
{
    const BATCH_SIZE = 500;

    const NO_RESULT_CHECK_QUERY = 'No result, please check the query';

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
        $file = tmpfile();
        $this->_databaseFile = stream_get_meta_data($file)['uri'];
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


        foreach (array_chunk($rows, self::BATCH_SIZE) as $rows) {
            $this->_insertRows($rows);
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
     * @param array $rows
     */
    private function _insertRows($rows)
    {
        $columnList = [];
        foreach ($this->_columns as $config) {
            $columnList[] = $config['name'];
        }
        $insertRows = [];
        foreach ($rows as $row) {
            $formattedValues = [];
            foreach ($row as $value) {
                if (is_numeric($value)) {
                    $formattedValues[] = $value;
                } else {
                    $formattedValues[] = "\"$value\"";
                }
            }
            $insertRows[] = '(' . implode(', ', $formattedValues) . ')';
        }

        if ($insertRows) {
            $query = sprintf(
                'INSERT INTO %s (%s) VALUES %s',
                $this->_tableName,
                implode(', ', $columnList),
                implode(', ', $insertRows)
            );

            $this->_db->exec($query);
        }
    }

    /**
     * @param string $query
     * @return array
     */
    public function select($query)
    {
        $results = [];
        $result = $this->_db->query(
            str_replace(self::TEMP_TABLE_PLACEHOLDER, $this->_tableName, $query)
        );
        if ($result !== false) {
            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                $results[] = $row;
            }
        } else {
            $results[] = ['' => self::NO_RESULT_CHECK_QUERY];
        }

        return $results;
    }

    public function __destruct()
    {
        `rm {$this->_databaseFile}`;
    }
}
