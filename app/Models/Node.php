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
            'id' => 'required|int|exists:Node',
            'title' => 'required|string',
            'description' => 'required|string',
            `order` => 'required|int',
            'module_id' => 'required|int|exists:Module',
        ]);
        $this->table = 'Nodes';
    }

    /**
     * Add multiple nodes
     * 
     * @param int $module_id
     * @param array $nodes
     * @return bool
     */
    public function addMultiple($module_id, $nodes)
    {
        foreach ($nodes as $order => $node) {
            $node['module_id'] = $module_id;
            $node['order'] = $order;

            if (!$this->add($node)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Update multiple nodes
     * 
     * @param array $nodes
     * @return bool
     */
    public function updateMultiple($nodes)
    {
        foreach ($nodes as $order => $node) {
            $node['order'] = $order;

            $id = $node['id'];
            unset($node['id']);

            if (!$this->update($id, $node)) {
                return false;
            }
        }

        return true;
    }
}
