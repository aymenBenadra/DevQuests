<?php

namespace App\Models;

use App\Controllers\Auth;
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
            'id' => 'required|int|exists:Roadmap',
            'title' => 'required|string|unique:Roadmap,title',
            'description' => 'required|string',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ]);
        $this->table = 'Roadmaps';
    }

    /**
     * Get all roadmaps and completed status
     * 
     * @return array
     */
    public function getAll()
    {
        $roadmaps = parent::getAll();

        if ($roadmaps === false) {
            return false;
        }

        $user_id = Auth::user()->id;

        foreach ($roadmaps as $key => $roadmap) {
            $roadmaps[$key]->started = $this->isStarted($user_id, $roadmap->id);
            $roadmaps[$key]->completed = $this->isCompleted($user_id, $roadmap->id);
            $roadmaps[$key]->relaxed = $this->isRelaxed($user_id, $roadmap->id);
        }

        return $roadmaps;
    }

    /**
     * Get a roadmap with completed status
     * 
     * @param int $id
     * @return object
     */
    public function get($id)
    {
        $roadmap = parent::get($id);

        if ($roadmap === false) {
            return false;
        }

        $roadmap->started = $this->isStarted(Auth::user()->id, $roadmap->id);
        $roadmap->completed = $this->isCompleted(Auth::user()->id, $roadmap->id);
        $roadmap->relaxed = $this->isRelaxed(Auth::user()->id, $roadmap->id);

        return $roadmap;
    }

    /**
     * Get a roadmap by a field and completed status
     * 
     * @param string $field
     * @param string $value
     * @return object
     */
    public function getBy($field, $value)
    {
        $roadmap = parent::getBy($field, $value);

        if ($roadmap === false) {
            return false;
        }

        $roadmap->started = $this->isStarted(Auth::user()->id, $roadmap->id);
        $roadmap->completed = $this->isCompleted(Auth::user()->id, $roadmap->id);
        $roadmap->relaxed = $this->isRelaxed(Auth::user()->id, $roadmap->id);

        return $roadmap;
    }

    /**
     * Get all roadmaps by a field and completed status
     * 
     * @param string $field
     * @param string $value
     * @return array
     */
    public function getAllBy($field, $value)
    {
        $roadmaps = parent::getAllBy($field, $value);

        if ($roadmaps === false) {
            return false;
        }

        foreach ($roadmaps as $key => $roadmap) {
            $roadmaps[$key]->started = $this->isStarted(Auth::user()->id, $roadmap->id);
            $roadmaps[$key]->completed = $this->isCompleted(Auth::user()->id, $roadmap->id);
            $roadmaps[$key]->relaxed = $this->isRelaxed(Auth::user()->id, $roadmap->id);
        }

        return $roadmaps;
    }

    /**
     * Check if a user has started a roadmap
     *  
     * @param int $user_id
     * @param int $roadmap_id
     * @return bool
     */
    public function isStarted(int $user_id, int $roadmap_id)
    {
        $this->db->query("SELECT * FROM `user_roadmaps`
            WHERE roadmap_id = :roadmap_id
            AND user_id = :user_id");
        $this->db->bind(':roadmap_id', $roadmap_id);
        $this->db->bind(':user_id', $user_id);

        return $this->db->rowCount() > 0;
    }

    /**
     * Start a roadmap
     * 
     * @param int $user_id
     * @param int $roadmap_id
     * @return bool
     */
    public function start($user_id, $roadmap_id)
    {
        $this->db->query("INSERT INTO `user_roadmaps` (`user_id`, `roadmap_id`)
            VALUES (:user_id, :roadmap_id)");
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':roadmap_id', $roadmap_id);

        return $this->db->execute();
    }

    /**
     * Check if Roadmap is relaxed for a user
     * 
     * @param int $user_id
     * @param int $roadmap_id
     * @return bool
     */
    public function isRelaxed(int $user_id, int $roadmap_id)
    {
        $this->db->query("SELECT is_relaxed FROM `user_roadmaps`
            WHERE `user_id` = :user_id
            AND `roadmap_id` = :roadmap_id");
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':roadmap_id', $roadmap_id);

        return (bool)($this->db->single()->is_relaxed ?? false);
    }

    /**
     * Mark a roadmap as relaxed
     * 
     * @param int $user_id
     * @param int $roadmap_id
     * @return bool
     */
    public function relax($user_id, $roadmap_id)
    {
        $this->db->query("UPDATE `user_roadmaps`
            SET `is_relaxed` = 1
            WHERE `user_id` = :user_id
            AND `roadmap_id` = :roadmap_id");
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':roadmap_id', $roadmap_id);

        return $this->db->execute();
    }

    /**
     * Mark a roadmap as not relaxed
     * 
     * @param int $user_id
     * @param int $roadmap_id
     * @return bool
     */
    public function unrelax($user_id, $roadmap_id)
    {
        $this->db->query("UPDATE `user_roadmaps`
            SET `is_relaxed` = 0
            WHERE `user_id` = :user_id
            AND `roadmap_id` = :roadmap_id");
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':roadmap_id', $roadmap_id);

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
        $this->db->query("SELECT is_completed FROM `user_roadmaps`
            WHERE `user_id` = :user_id
            AND `roadmap_id` = :roadmap_id");
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':roadmap_id', $roadmap_id);

        return (bool)($this->db->single()->is_completed ?? false);
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
        $this->db->query("UPDATE `user_roadmaps`
            SET `is_completed` = 1
            WHERE `user_id` = :user_id
            AND `roadmap_id` = :roadmap_id");
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
        $this->db->query("UPDATE `user_roadmaps`
            SET `is_completed` = 0
            WHERE `user_id` = :user_id
            AND `roadmap_id` = :roadmap_id");
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':roadmap_id', $roadmap_id);

        return $this->db->execute();
    }
}
