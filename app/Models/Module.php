<?php

namespace App\Models;

use Core\Model;

/**
 * Module Model
 * 
 * @package App\Models
 * @uses Core\Model Core Model
 * @author Mohammed-Aymen Benadra
 */
class Module extends Model
{
    public function __construct()
    {
        parent::__construct([
            'id' => 'int',
            'title' => 'required|string',
            'description' => 'required|string',
            'weeks' => 'required|int'
        ]);
        $this->table = 'Modules';
    }

    /**
     * Get all Nodes of a Module
     * 
     * @param int $module_id
     * @return array
     */
    public function nodes(int $module_id)
    {
        $this->db->query("SELECT * FROM Nodes
            WHERE module_id = :module_id
            ORDER BY `order` ASC");
        $this->db->bind(':module_id', $module_id);

        return $this->db->resultSet();
    }

    /**
     * Get all roadmaps of a module
     * 
     * @param int $module_id
     * @return array
     */
    public function roadmaps(int $module_id)
    {
        $this->db->query("SELECT r.* FROM Roadmaps_Modules as rm
            INNER JOIN Roadmaps as r ON r.id = rm.roadmap_id
            WHERE rm.module_id = :module_id");
        $this->db->bind(':module_id', $module_id);

        return $this->db->resultSet();
    }

    /**
     * Check if a module is completed by a user
     * 
     * @param int $user_id
     * @param int $module_id
     * @return bool
     */
    public function isCompleted(int $user_id, int $module_id)
    {
        $this->db->query("SELECT * FROM `user_modules`
            WHERE `user_id` = :user_id
            AND `module_id` = :module_id");
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':module_id', $module_id);

        return $this->db->rowCount() > 0;
    }

    /**
     * Mark a module as completed
     * 
     * @param int $user_id
     * @param int $module_id
     * @return void
     */
    public function complete(int $user_id, int $module_id)
    {
        $this->db->query("INSERT INTO `user_modules` (`user_id`, `module_id`)
            VALUES (:user_id, :module_id)");
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':module_id', $module_id);
        return $this->db->execute();
    }

    /**
     * Mark a module as not completed
     * 
     * @param int $user_id
     * @param int $module_id
     * @return void
     */
    public function uncomplete(int $user_id, int $module_id)
    {
        $this->db->query("DELETE FROM `user_modules`
            WHERE `user_id` = :user_id
            AND `module_id` = :module_id");
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':module_id', $module_id);
        return $this->db->execute();
    }
}
