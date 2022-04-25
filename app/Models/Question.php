<?php

namespace App\Models;

use Core\Model;

/**
 * Question Model
 * 
 * @package App\Models
 * @uses Core\Model Core Model
 * @author Mohammed-Aymen Benadra
 */
class Question extends Model
{
    public function __construct()
    {
        parent::__construct([
            'id' => 'int',
            'question' => 'required|string',
            'answer' => 'required|string',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ]);
        $this->table = 'Questions';
    }
}
