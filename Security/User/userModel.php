<?php

namespace User;

use Connection\Connection;
use PDO;

class userModel implements Dao
{
    private  Connection $connection;

    public function __construct(Connection $connection){
        $this->connection = $connection;
    }
    public function findAll(): false|array
    {
        return $this->connection->connect()->query("select * from user")->fetchAll(PDO::FETCH_OBJ);
    }

    public function findById(int $id): mixed
    {
         $query = $this->connection->connect()->prepare("select * from user where id = :id");
         $query->execute(array("id" => $id));
         return  $query->fetch(PDO::FETCH_OBJ);
    }

    public function save($entity): bool
    {
        $query = $this->connection->connect()->prepare("insert into user (firstName,lastName,email,username,password,enabled,role) values (?, ?, ?, ?, ?, ?, ?)");
        return $query->execute([
            $entity->getFirstName(),
            $entity->getLastName(),
            $entity->getEmail(),
            $entity->getUsername(),
            $entity->getPassword(),
            $entity->getEnabled(),
            $entity->getRole()
        ]);
    }

    public function update($entity): bool
    {
      $query = $this->connection->connect()->prepare("update user set firstName = ?, lastName = ?, email = ?, username = ?, password = ?, enabled = ?, role = ? where id = ?");
      return $query->execute([
          $entity->getFirstName(),
          $entity->getLastName(),
          $entity->getEmail(),
          $entity->getUsername(),
          $entity->getPassword(),
          $entity->getEnabled(),
          $entity->getRole(),
          $entity->getId()
      ]);
    }

    public function delete($entity): bool
    {
        $query = $this->connection->connect()->prepare("delete from user where id = ?");
        return $query->execute([$entity->getId()]);
    }

    public function findByEmailAndPassword($getEmail, $getPassword): false|array
    {
        $query = $this->connection->connect()->prepare("select * from user where email = :email and password = :password");
        $query->execute(array("email" => $getEmail, "password" => $getPassword));
        return $query->fetchAll(PDO::FETCH_OBJ);
    }

}