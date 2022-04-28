<?php

namespace App\Models;

use Core\Model;

/**
 * Node Model
 * 
 * @package App\Models
 * @uses Core\Model Core Model
 * @author Mohammed-Aymen Benadra
 */
class Node extends Model
{
    public function __construct()
    {
        parent::__construct([
            'id' => 'int',
            'title' => 'required|string',
            'description' => 'required|string',
            `order` => 'required|int',
            'module_id' => 'required|int',
        ]);
        $this->table = 'Nodes';
    }
}
