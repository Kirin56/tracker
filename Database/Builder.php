<?php
/**
 * Created by PhpStorm.
 * User: kirin
 * Date: 12/25/20
 * Time: 6:40 PM
 */

namespace Database;


use Exception;
use PDO;

/**
 * Class Builder
 * @package Database
 */
class Builder
{
    /**
     * @var mixed
     */
    private $db;

    /**
     * @var string
     */
    private $table;

    /**
     * @var array
     */
    private $where = ['values' => []];

    /**
     * Builder constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->db = app('database');
    }

    /**
     * @param string $key
     * @param $value
     * @param string $delimiter
     * @return $this
     */
    public function where(string $key, $value, string $delimiter = 'AND')
    {
        $this->where['values'][] = "$key = $value";

        $this->where['delimiter'] = $delimiter;

        return $this;
    }

    /**
     * @param string $fields
     * @return array
     * @throws Exception
     */
    public function get($fields = '*'): array
    {
        $selectClause = is_array($fields) ? implode(',', $fields) : $fields;

        $query = "
            SELECT {$selectClause}
            FROM {$this->table}
        ";

        if (count($this->where['values']) > 0) {
            $query .= " WHERE {$this->getWhereClause()}";
        }

        $statement = $this->db->prepare($query);

        $statement->setFetchMode(PDO::FETCH_ASSOC);

        if (!$statement->execute()) {
            throw new Exception('Error while fetching data from database');
        }

        return $statement->fetchAll();
    }

    /**
     * @param array $data
     * @return void
     * @throws Exception
     */
    public function insert(array $data): void
    {
        $fields     = [];
        $values     = [];
        $conditions = [];

        foreach ($data as $key => $value) {
            $fields[]     = $key;
            $values[]     = $value;
            $conditions[] = '?';
        }

        $fieldsString = implode(', ', $fields);
        $conditionsString = implode(', ', $conditions);

        $query = "INSERT INTO {$this->table} ({$fieldsString}) VALUES ({$conditionsString})";

        $statement = $this->db->prepare($query);

        if (!$statement->execute($values)) {
            throw new Exception('Error while inserting data into database');
        }
    }

    /**
     * @param array $data
     * @return void
     * @throws Exception
     */
    public function update(array $data): void
    {
        $values     = [];
        $conditions = [];

        foreach ($data as $key => $value) {
            $values[]     = $value;
            $conditions[] = "$key = ?";
        }

        $conditionsString = implode(', ', $conditions);

        $query = "UPDATE {$this->table} SET {$conditionsString}";

        if (count($this->where['values']) > 0) {
            $query .= " WHERE {$this->getWhereClause()}";
        }

        $statement = $this->db->prepare($query);

        if (!$statement->execute($values)) {
            throw new Exception('Error while updating data into database');
        }
    }

    /**
     * @return void
     * @throws Exception
     */
    public function delete()
    {
        $query = "DELETE FROM {$this->table}";

        if (count($this->where['values']) > 0) {
            $query .= " WHERE {$this->getWhereClause()}";
        }

        $statement = $this->db->prepare($query);

        if (!$statement->execute()) {
            throw new Exception('Error while deleting data into database');
        }
    }

    /**
     * @return string
     */
    protected function getWhereClause(): string
    {
        return implode(" {$this->where['delimiter']} ", $this->where['values']);
    }

    /**
     * @param string $table
     * @return Builder
     * @throws Exception
     */
    static public function table(string $table)
    {
        $builder = new static;

        $builder->table = $table;

        return $builder;
    }
}