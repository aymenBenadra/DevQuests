<?php

namespace App\Models;

use Core\Model;

/**
 * Roadmap Model
 * 
 * @package App\Models
 * @uses Core\Model Core Model
 * @author Mohammed-Aymen Benadra
 */
class Roadmap extends Model
{
    public function __construct()
    {
        parent::__construct([
            'id' => 'int',
            'title' => 'required|string',
            'description' => 'required|string',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ]);
        $this->table = 'Roadmaps';
    }

    /**
     * Get all modules of a roadmap
     * 
     * @param int $roadmap_id
     * @return array
     */
    public function modules(int $roadmap_id)
    {
        $this->db->query("SELECT m.*, rm.module_order FROM `roadmaps_modules` as rm
            INNER JOIN `modules` as m ON m.id = rm.module_id
            WHERE rm.roadmap_id = :roadmap_id
            ORDER BY `module_order` ASC");
        $this->db->bind(':roadmap_id', $roadmap_id);

        return $this->db->resultSet();
    }

    /**
     * Add a module to a roadmap
     * 
     * @param int $roadmap_id
     * @param int $module_id
     * @param int $module_order
     * @return bool
     */
    public function addModule(int $roadmap_id, int $module_id, int $module_order)
    {
        $this->db->query("INSERT INTO `roadmaps_modules` (`roadmap_id`, `module_id`, `module_order`)
            VALUES (:roadmap_id, :module_id, :module_order)");
        $this->db->bind(':roadmap_id', $roadmap_id);
        $this->db->bind(':module_id', $module_id);
        $this->db->bind(':module_order', $module_order);

        return $this->db->execute();
    }

    /**
     * Remove a module from a roadmap
     * 
     * @param int $roadmap_id
     * @param int $module_id
     * @return bool
     */
    public function removeModule(int $roadmap_id, int $module_id)
    {
        $this->db->query("DELETE FROM `roadmaps_modules` WHERE `roadmap_id` = :roadmap_id AND `module_id` = :module_id");
        $this->db->bind(':roadmap_id', $roadmap_id);
        $this->db->bind(':module_id', $module_id);

        return $this->db->execute();
    }

    /**
     * Update a module order in a roadmap
     * 
     * @param int $roadmap_id
     * @param int $module_id
     * @param int $module_order
     * @return bool
     */
    public function updateModuleOrder(int $roadmap_id, int $module_id, int $module_order)
    {
        $this->db->query("UPDATE `roadmaps_modules` SET `module_order` = :module_order 
            WHERE `roadmap_id` = :roadmap_id 
            AND `module_id` = :module_id");
        $this->db->bind(':roadmap_id', $roadmap_id);
        $this->db->bind(':module_id', $module_id);
        $this->db->bind(':module_order', $module_order);

        return $this->db->execute();
    }

    /**
     * Check if a roadmap is completed by a user
     * 
     * @param int $user_id
     * @param int $roadmap_id
     * @return bool
     */
    public function isCompleted(int $user_id, int $roadmap_id)
    {
        $this->db->query("SELECT * FROM `user_roadmaps`
            WHERE `user_id` = :user_id
            AND `roadmap_id` = :roadmap_id");
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':roadmap_id', $roadmap_id);

        return $this->db->rowCount() > 0;
    }

    /**
     * Mark a roadmap as completed by a user
     * 
     * @param int $user_id
     * @param int $roadmap_id
     * @return bool
     */
    public function complete(int $user_id, int $roadmap_id)
    {
        $this->db->query("INSERT INTO `user_roadmaps` (`user_id`, `roadmap_id`)
            VALUES (:user_id, :roadmap_id)");
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':roadmap_id', $roadmap_id);

        return $this->db->execute();
    }

    /**
     * Mark a roadmap as not completed by a user
     * 
     * @param int $user_id
     * @param int $roadmap_id
     * @return bool
     */
    public function uncomplete(int $user_id, int $roadmap_id)
    {
        $this->db->query("DELETE FROM `user_roadmaps`
            WHERE `user_id` = :user_id
            AND `roadmap_id` = :roadmap_id");
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':roadmap_id', $roadmap_id);

        return $this->db->execute();
    }
}
