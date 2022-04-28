<?php

namespace App\Models;

use Core\Model;

/**
 * User Model
 * 
 * @package App\Models
 * @uses Core\Model Core Model
 * @author Mohammed-Aymen Benadra
 */
class User extends Model
{
    public function __construct()
    {
        parent::__construct([
            'id' => 'int',
            'avatar' => 'string', // Auto generated from Gravatar
            'username' => 'required|string',
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string',
            'is_admin' => 'bool',
        ]);
        $this->table = 'Users';
    }

    /**
     * Get all completed modules of a user
     * 
     * @param int $user_id
     * @return array
     */
    public function completedModules(int $user_id)
    {
        $this->db->query("SELECT m.* FROM User_Modules as um
            INNER JOIN Modules as m ON m.id = um.module_id
            WHERE um.user_id = :user_id
            ORDER BY `order` ASC");
        $this->db->bind(':user_id', $user_id);

        return $this->db->resultSet();
    }

    /**
     * Get all completed roadmaps of a user
     * 
     * @param int $user_id
     * @return array
     */
    public function completedRoadmaps(int $user_id)
    {
        $this->db->query("SELECT r.* FROM User_Roadmaps as ur
            INNER JOIN Roadmaps as r ON r.id = ur.roadmap_id
            WHERE ur.user_id = :user_id");
        $this->db->bind(':user_id', $user_id);

        return $this->db->resultSet();
    }
}
