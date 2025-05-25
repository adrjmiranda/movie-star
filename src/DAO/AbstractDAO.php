<?php

namespace MovieStar\DAO;

use Medoo\Medoo;
use MovieStar\Core\Database;

abstract class AbstractDAO implements DaoInterface
{
    protected Medoo $db;

    public function __construct()
    {
        $this->db = Database::instance();
    }
}