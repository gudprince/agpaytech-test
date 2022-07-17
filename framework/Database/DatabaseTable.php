<?php

namespace Framework\Database;

use PDO;

class DatabaseTable
{
    private $pdo;
    private $table;
    private $primaryKey;

    public function __construct($pdo, string $table, string $primaryKey)
    {
        $this->pdo = $pdo;
        $this->table = $table;
        $this->primaryKey = $primaryKey;
    }

    private function query($sql, $parameters = [])
    {
        $query = $this->pdo->prepare($sql);
        $query->execute($parameters);
        return $query;
    }
    public function processDate($fields)
    {
        foreach ($fields as $key => $value) {
            if ($value instanceof \DateTime) {
                $fields[$key] =  $value->format('Y-m-d');
            }
        }
        return $fields;
    }

    public function total($field = null, $value = null)
    {
        $query = 'SELECT COUNT(*) FROM `' . $this->table . '`';
        $parameters = [];
        if (!empty($field)) {
            $query .= ' WHERE `' . $field . '` = :value';
            $parameters = ['value' => $value];
        }

        $result = $this->query($query, $parameters);
        $row = $result->fetch();
        return $row[0];
    }

    public function findById($value, $orderBy = null)
    {
        $query = 'SELECT * FROM  `' . $this->table . '` WHERE `' . $this->primaryKey . '` = :id';
        if ($orderBy != null) {
            $query .= ' ORDER BY ' . $orderBy;
        }
        $parameters = [':id' => $value];
        $result = $this->query($query, $parameters);
        return $result->fetch(PDO::FETCH_ASSOC);
    }


    private function insert($fields)
    {
        $query = 'INSERT INTO `' . $this->table . '` (';
        foreach ($fields as $key => $value) {
            $query .= '`' . $key . '`,';
        }
        $query = rtrim($query, ',');
        $query .= ') VALUES (';
        foreach ($fields as $key => $value) {
            $query .= ':' . $key . ',';
        }
        $query = rtrim($query, ',');
        $query .= ')';
        $fields = $this->processDate($fields);
        $this->query($query, $fields);
        return $this->pdo->lastInsertId();
    }
    public function findAll($limit = null, $offset = null, $orderBy = null, $keyword, $column)
    {
        $query = 'SELECT * FROM ' . $this->table;

        if ($keyword !== null) {
            $query .= 	" WHERE ($column LIKE '%" . $keyword . "%')";
        }
        if ($orderBy != null) {
            $query .= ' ORDER BY ' . $orderBy;
        }
        if ($limit != null) {
            $query .= ' LIMIT ' . $limit;
        }
        if ($offset != null) {
            $query .= ' OFFSET ' . $offset;
        }


        $result = $this->query($query);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function search($column, $keyword, $limit = null, $offset = null, $orderBy = null)
    {

        $query = "SELECT * FROM currencies
			WHERE ($column LIKE '%" . $keyword . "%')";

        if ($orderBy != null) {
            $query .= ' ORDER BY ' . $orderBy;
        }
        if ($limit != null) {
            $query .= ' LIMIT ' . $limit;
        }
        if ($offset != null) {
            $query .= ' OFFSET ' . $offset;
        }

        $result = $this->query($query);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    private function update($fields)
    {
        $query = ' UPDATE `' . $this->table . '` SET ';
        foreach ($fields as $key => $value) {
            $query .= '`' . $key . '`= :' . $key . ',';
        }
        $query = rtrim($query, ',');

        $query .= ' WHERE `' . $this->primaryKey . '`= :primaryKey';
        $fields['primaryKey'] = $fields['id'];
        $fields = $this->processDate($fields);
        $this->query($query, $fields);
    }

    public function save($record)
    {

        try {
            $insertId = $this->insert($record);
        } catch (\PDOException $e) {
            $insertId = $this->update($record);
        }
        return $insertId;
    }
    public function delete($value)
    {
        $query = 'DELETE FROM`' . $this->table . '`WHERE`' . $this->primaryKey . '`= :id';
        $parameters = [':id' => $value];
        $this->query($query, $parameters);
    }

    public function find($column, $value,  $orderBy = null, $limit = null, $offset = null)
    {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE ' . $column . ' = :value';
        if ($orderBy != null) {
            $query .= ' ORDER BY ' . $orderBy;
        }
        if ($limit != null) {
            $query .= ' LIMIT ' . $limit;
        }
        if ($offset != null) {
            $query .= ' OFFSET ' . $offset;
        }



        $parameters = ['value' => $value];
        $result = $this->query($query, $parameters);
        return $result->fetch(PDO::FETCH_ASSOC);
    }

    public function deleteWhere($column, $value)
    {
        $query = 'DELETE FROM ' . $this->table . '
        WHERE ' . $column . ' = :value';
        $parameters = ['value' => $value];
        $query = $this->query($query, $parameters);
    }
}
