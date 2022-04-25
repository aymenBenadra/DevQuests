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
            'time' => 'required|int',
            'done' => 'required|bool',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ]);
        $this->table = 'Roadmaps';
    }
}
