<?php

namespace MovieStar\DAO;

interface DaoInterface
{
    public function create(array $data): int;
    public function findById(int $id);
    public function find();
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
}