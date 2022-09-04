<?php

namespace models;

use libraries\Database;
use PDOStatement;

class Post
{

    private Database $db;
    private string $tableName;

    private int $id;
    private string $category_name;
    private int $category_id;
    private string $title;
    private string $body;
    private string $author;
    private string $email;
    private string $created_at;
    private string $updated_at;

    private array $data;


    public function __construct()
    {
        $this->db = new Database();
        $this->tableName = 'posts';
    }

    /**
     * @return Database
     */
    public function getDb(): Database
    {
        return $this->db;
    }

    /**
     * @param Database $db
     */
    public function setDb(Database $db): void
    {
        $this->db = $db;
    }

    /**
     * @return string
     */
    public function getTableName(): string
    {
        return $this->tableName;
    }

    /**
     * @param string $tableName
     */
    public function setTableName(string $tableName): void
    {
        $this->tableName = $tableName;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getCategoryName(): string
    {
        return $this->category_name;
    }

    /**
     * @param string $category_name
     */
    public function setCategoryName(string $category_name): void
    {
        $this->category_name = $category_name;
    }

    /**
     * @return int
     */
    public function getCategoryId(): int
    {
        return $this->category_id;
    }

    /**
     * @param int $category_id
     */
    public function setCategoryId(int $category_id): void
    {
        $this->category_id = $category_id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @param string $body
     */
    public function setBody(string $body): void
    {
        $this->body = $body;
    }

    /**
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * @param string $author
     */
    public function setAuthor(string $author): void
    {
        $this->author = $author;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    /**
     * @param string $created_at
     */
    public function setCreatedAt(string $created_at): void
    {
        $this->created_at = $created_at;
    }

    /**
     * @return string
     */
    public function getUpdatedAt(): string
    {
        return $this->updated_at;
    }

    /**
     * @param string $updated_at
     */
    public function setUpdatedAt(string $updated_at): void
    {
        $this->updated_at = $updated_at;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData(array $data): void
    {
        $this->data = $data;
    }


    public function read($category_id = null): bool|array
    {
        $query = 'SELECT 
                    p.id,
                    p.category_id,
                    c.name AS category_name,
                    p.title,
                    p.body,
                    p.author,
                    p.email,
                    p.created_at,
                    p.updated_at
                  FROM ' . $this->tableName . ' p
                       JOIN categories c
                        ON p.category_id = c.id';

        if ($category_id !== null) {
            $query .= ' WHERE p.category_id = :category_id
                            ORDER BY p.created_at DESC;';
            $this->db->prepareQuery($query);
            $this->db->bind(':category_id', $category_id);
        } else {
            $query .= ' ORDER BY p.created_at DESC;';
            $this->db->prepareQuery($query);
        }
        $this->data = $this->db->getRowsInSet();

        return $this->data;
    }

    public function readSingle(string|int $id): object|bool|array
    {
        $query = 'SELECT 
                    p.id,
                    p.name AS category_name,
                    p.title,
                    p.body,
                    p.author,
                    p.email,
                    p.created_at,
                    p.updated_at
                  FROM ' . $this->tableName . ' p
                       JOIN categories c
                        ON p.category_id = c.id
                    WHERE p.id = :id;';
        $this->db->prepareQuery($query);
        $this->db->bind(':id', $id);

        $this->data = $this->db->getSingleRow();
        $this->assignProperties($this->data);

        return $this->data;
    }

    private function assignProperties($fields): void
    {
        foreach ($fields as $key => $value) {
            $this->{$key} = $value;
        }
    }

    public function create(): array|bool|object
    {
        $query = 'INSERT INTO ' . $this->tableName . ' 
                    (
                        category_id,
                        title,
                        body,
                        author,
                        email                        
                    )
                  VALUES
                    (
                        :category_id,
                        :title,
                        :body,
                        :author,
                        :email
                    );';

        $this->db->prepareQuery($query);
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->body = htmlspecialchars(strip_tags($this->body));
        $this->author = htmlspecialchars(strip_tags($this->author));
        $this->email = htmlspecialchars(strip_tags($this->email));


        $this->db->bind(':category_id', $this->category_id);
        $this->db->bind(':title', $this->title);
        $this->db->bind(':body', $this->body);
        $this->db->bind(':author', $this->author);
        $this->db->bind(':email', $this->email);

        return $this->db->executeStmt() ?? $this->printError();
    }


    public function update(): ?bool
    {

        $query = 'UPDATE ' . $this->tableName
            . ' SET title = :title, body = :body, category_id = :category_id WHERE id= :id;';

        $this->db->prepareQuery($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        $this->title = htmlspecialchars($this->title);
        $this->body = htmlspecialchars($this->body);

        $this->db->bind(':category_id', $this->category_id);
        $this->db->bind(':title', $this->title);
        $this->db->bind(':body', $this->body);
        $this->db->bind(':id', $this->id);

        return $this->db->executeStmt() ?? $this->printError();
    }

    public function delete(): bool {
        $query = 'DELETE FROM ' . $this->tableName . ' WHERE id = :id';
        $this->db->prepareQuery($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->db->bind(':id', $this->id);

        return $this->db->executeStmt() ?? $this->printError();
    }


    private function printError(): bool
    {
        printf('Error: %s' . PHP_EOL, $this->db->getStmt()->errorCode());

        return false;
    }


}