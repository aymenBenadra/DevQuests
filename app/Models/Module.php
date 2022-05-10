<?php

namespace App\Models;

use App\Controllers\Auth;
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
            'weeks' => 'required|int',
            'roadmap_id' => 'required|int',
            'order' => 'required|int'
        ]);
        $this->table = 'Modules';
    }

    /**
     * Get all modules and completed status
     * 
     * @return array
     */
    public function getAll()
    {
        $modules = parent::getAll();

        if ($modules === false) {
            return false;
        }

        foreach ($modules as $key => $module) {
            $modules[$key]->completed = $this->isCompleted(Auth::user()->id, $module->id);
        }

        return $modules;
    }

    /**
     * Get a module with completed status
     * 
     * @param int $id
     * @return object
     */
    public function get($id)
    {
        $module = parent::get($id);

        if ($module === false) {
            return false;
        }

        $module->completed = $this->isCompleted(Auth::user()->id, $id);
        $module->nodes = $this->nodes($id);

        return $module;
    }

    /**
     * Get all modules by a field and completed status
     * 
     * @return array
     */
    public function getAllBy($field, $value)
    {
        $modules = parent::getAllBy($field, $value);

        if ($modules === false) {
            return false;
        }

        foreach ($modules as $key => $module) {
            $modules[$key]->completed = $this->isCompleted(Auth::user()->id, $module->id);
        }

        return $modules;
    }

    /**
     * Get completed modules of a roadmap
     * 
     * @param int $roadmap_id
     * @return array
     */
    public function completed($roadmap_id)
    {
        $modules = $this->getAllBy('roadmap_id', $roadmap_id);

        if ($modules === false) {
            return false;
        }

        return count(array_filter($modules, function ($module) {
            return $module->completed;
        }));
    }

    /**
     * Get uncomplete modules of a roadmap
     * 
     * @param int $roadmap_id
     * @return array
     */
    public function uncompleted($roadmap_id)
    {
        $modules = $this->getAllBy('roadmap_id', $roadmap_id);

        if ($modules === false) {
            return false;
        }

        return count(array_filter($modules, function ($module) {
            return !$module->completed;
        }));
    }

    /**
     * Get a module by a field and completed status
     * 
     * @param int $id
     * @return object
     */
    public function getBy($field, $value)
    {
        $module = parent::getBy($field, $value);

        if ($module === false) {
            return false;
        }

        $module->completed = $this->isCompleted(Auth::user()->id, $module->id);
        $module->nodes = $this->nodes($module->id);

        return $module;
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
            WHERE `module_id` = :module_id
            ORDER BY `order` ASC");
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
        $this->db->query("SELECT * FROM `completed_modules`
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
        $this->db->query("INSERT INTO `completed_modules` (`user_id`, `module_id`)
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
        $this->db->query("DELETE FROM `completed_modules`
            WHERE `user_id` = :user_id
            AND `module_id` = :module_id");
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':module_id', $module_id);
        return $this->db->execute();
    }
}
