<?php

namespace User;

interface Dao
{

    public function findAll();
    public function save($entity);
    public function update($entity);
    public function delete($entity);


}