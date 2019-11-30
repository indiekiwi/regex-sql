<?php

include 'TempDb.php';

/**
 * Class processor
 */
class Processor
{
    // configs
    const DEBUG_MODE                    = true,
          MAX_FILE_SIZE                 = '10M',
          CHAR_LIMIT                    = 50000000,
          TIMEOUT                       = 300;

    // request data
    const REQUEST_POST_REGEX_PATTERN    = 'regex-pattern',
          REQUEST_POST_DIRECT_DATA      = 'direct-data',
          REQUEST_POST_TABLE_NAME       = 'table-name',
          REQUEST_POST_SQL_QUERY        = 'sql-query';

    // placeholders
    const DEFAULT_COLUMN_NAME           = 'col',
          DEFAULT_COLUMN_TYPE           = 'type';

    // errors
    const ERROR_REGEX_IS_INVALID        = 'Check your Regex',
          ERROR_DATA_MISSING            = 'Data is required',
          ERROR_CHAR_LIMIT_EXCEEDED     = 'Too much data, char limit is: ' . self::CHAR_LIMIT,
          ERROR_INVALID_TABLE_NAME      = 'Table name is invalid',
          ERROR_CAPTURE_COUNT_MISMATCH  = 'Regex has different number of capture groups than defined',
          ERROR_GENERAL                 = 'Error';

    /**
     * processor constructor
     */
    public function __construct()
    {
        if (self::DEBUG_MODE) {
            error_reporting(E_ALL);
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
        }
        ini_set('upload_max_filesize', self::MAX_FILE_SIZE);
        ini_set('post_max_size', self::MAX_FILE_SIZE);
        ini_set('max_input_time', self::TIMEOUT);
        ini_set('max_execution_time', self::TIMEOUT);
    }

    /**
     * Main method
     *
     * @return array    Table output data
     *          | bool  Submit not clicked
     * @throws Exception
     */
    public function generate()
    {
        // Regex
        $regexPattern = $this->_getPost(self::REQUEST_POST_REGEX_PATTERN);
        if (!$regexPattern) {
            return false;
        }
        $isRegexInvalid = @preg_match($regexPattern, null) === false;
        if ($isRegexInvalid) {
            throw new Exception(self::ERROR_REGEX_IS_INVALID);
        }

        // Data
        if (isset($_FILES['file']['size']) && $_FILES['file']['size'] > 0 && isset($_FILES['file']['tmp_name'])) {
            $data = file_get_contents($_FILES['file']['tmp_name']);
        } elseif ($directData = $this->_getPost(self::REQUEST_POST_DIRECT_DATA)) {
            $data = $directData;
        } else {
            throw new Exception(self::ERROR_DATA_MISSING);
        }
        if (empty($data)) {
            throw new Exception(self::ERROR_DATA_MISSING);
        }
        if (strlen($data) >= self::CHAR_LIMIT) {
            throw new Exception(self::ERROR_CHAR_LIMIT_EXCEEDED);
        }

        $tableName = $this->_getPost(self::REQUEST_POST_TABLE_NAME);
        if (!preg_match('@^([\w_]+)$@', $tableName)) {
            throw new Exception(self::ERROR_INVALID_TABLE_NAME);
        }

        $data = explode(PHP_EOL, $data);
        $columns = [];
        for ($i = 1; $i < 100; $i++) {
            $columnName = $this->_getPost(self::DEFAULT_COLUMN_NAME . $i);
            $columnType = $this->_getPost(self::DEFAULT_COLUMN_TYPE . $i);
            if ($columnName && $columnType) {
                $columns[$i] = [
                    'name' => $columnName,
                    'type' => $columnType,
                ];
            } else{
                break;
            }
        }

        $rows = [];
        foreach ($data as $i => $line) {
            if (preg_match($regexPattern, $line, $m)) {
                unset($m[0]);
                if ($i === 0 && count($m) !== count($columns)) {
                    throw new Exception(self::ERROR_CAPTURE_COUNT_MISMATCH);
                }
                $rows[] = $m;
            }
        }
        $db = new TempDb($tableName, $columns, $rows);

        return $db->select($this->_getPost(self::REQUEST_POST_SQL_QUERY));
    }

    /**
     * Get from post and validate
     *
     * @param string $field
     * @param mixed $default
     * @return mixed
     */
    private function _getPost($field, $default = false)
    {
        $variable = $default;

        if (isset($_POST[$field])) {
            $variable = $_POST[$field];
        }

        return $variable;
    }
}
