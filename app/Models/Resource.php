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
            'id' => 'int',
            'title' => 'required|string',
            'description' => 'required|string',
            'link' => 'required|string',
            'visited' => 'required|bool',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ]);
        $this->table = 'Resources';
    }
}
