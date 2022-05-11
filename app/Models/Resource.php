<?php

namespace App\Models;

use Core\Model;

/**
 * Resource Model
 * 
 * @package App\Models
 * @uses Core\Model Core Model
 * @author Mohammed-Aymen Benadra
 */
class Resource extends Model
{
    public function __construct()
    {
        parent::__construct([
            'id' => 'required|int|exists:Resource',
            'title' => 'required|string|unique:Resource,title',
            'description' => 'required|string',
            'link' => 'required|string',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ]);
        $this->table = 'Resources';
    }
}
