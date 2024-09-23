<?php

namespace Camagru\Core\Models;

use PDO;
use Camagru\Core\Data\Connection;


//TODO: change name
class LikeModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Connection::getDBConnection();
    }
    
}