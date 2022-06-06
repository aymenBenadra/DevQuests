<?php

namespace App\Controllers;

use Core\{Controller, Router};
use Core\Helpers\Response;

/**
 * Roadmaps Controller
 *
 * @author Mohammed-Aymen Benadra
 * @package App\Controllers
 */
class Roadmaps extends Controller
{
    private $model;
    /**
     * Set headers for JSON response
     *
     * @return void
     */
    public function __construct()
    {
        // Set default Model for this controller
        $this->model = $this->model('Roadmap');

        Response::headers();
        Response::code();
    }

    /**
     * Get all Roadmaps
     *
     * @return void
     */
    public function index()
    {
        $roadmaps = $this->model->getAll();

        if ($roadmaps === false) {
            Router::abort(500, [
                'message' => 'Server error'
            ]);
        }

        foreach ($roadmaps as $roadmap) {
            $roadmap->modules = $this->model('Module')->getAllBy("roadmap_id", $roadmap->id);

            if (!empty($roadmap->modules)) {
                foreach ($roadmap->modules as $key => $module) {
                    $roadmap->modules[$key]->nodes = $this->model('Node')->getAllBy("module_id", $module->id);
                }
            }
        }

        Response::send(
            $roadmaps
        );
    }

    /**
     * Get all Modules for a roadmap
     * 
     * @param int $data
     * @return array
     */
    public function getModules($data = [])
    {
        $modules = $this->model('Module')->getAllBy("roadmap_id", $data['id']);

        if ($modules === false) {
            Router::abort(500, [
                'message' => 'Server error'
            ]);
        }

        Response::send(
            $modules
        );
    }

    /**
     * Get a Roadmap with its modules
     *
     * @param array $data
     * @return void
     */
    public function show($data = [])
    {
        $roadmap = isset($data['id'])
            ? $this->model->get($data['id'])
            : $this->model->getBy('title', $data['title']);

        if ($roadmap === false) {
            Router::abort(404, [
                'message' => 'Roadmap not found'
            ]);
        }

        $roadmap->modules = $this->model('Module')->getAllBy("roadmap_id", $roadmap->id);

        if (!empty($roadmap->modules)) {
            foreach ($roadmap->modules as $key => $module) {
                $roadmap->modules[$key]->nodes = $this->model('Node')->getAllBy("module_id", $module->id);
            }
        }

        Response::send(
            $roadmap
        );
    }

    /**
     * Store a Roadmap
     *
     * @param array $data
     * @return void
     */
    public function store($data = [])
    {
        $modules = $data['modules'] ?? [];
        unset($data['modules']);

        if (!$this->model->add($data)) {
            Router::abort(500, [
                'message' => 'Server error'
            ]);
        }

        // Get the Roadmap just created
        $roadmap = $this->model->getBy('title', $data['title']);

        // Add Modules to created Roadmap
        if (!empty($modules)) {
            foreach ($modules as $order => $module) {
                // Get module id from title
                $module['roadmap_id'] = $roadmap->id;
                $module['order'] = $order;

                $nodes = $module['nodes'] ?? [];
                unset($module['nodes']);

                if (!$this->model('Module')->add($module)) {
                    $this->model->delete($roadmap->id);
                    Router::abort(500, [
                        'message' => 'Server error: Module ' . $module['title'] . ' not added'
                    ]);
                }

                // Get the Module just created
                $module_id = $this->model->getLastInsertedId();

                // Add Nodes to created Module
                if (!empty($nodes)) {
                    if (!$this->model('Node')->addMultiple($module_id, $nodes)) {
                        $this->model->delete($roadmap->id);
                        Router::abort(500, [
                            'message' => 'Server error: Module ' . $module['title'] . ' Nodes not added'
                        ]);
                    }
                }
            }
        }

        Response::code(201);
        Response::send([
            'message' => 'Created successfully.'
        ]);
    }

    /**
     * Update a Roadmap
     *
     * @param array $data
     * @return void
     */
    public function update($data = [])
    {
        $modules = $data['modules'] ?? [];
        unset($data['modules']);

        $id = $data['id'];
        unset($data['id']);

        if (!$this->model->update($id, $data)) {
            Router::abort(500, [
                'message' => 'Server error'
            ]);
        }

        // Update Modules to updated Roadmap
        if (!empty($modules)) {
            foreach ($modules as $module) {
                // Get module id from title

                $nodes = $module['nodes'] ?? [];
                unset($module['nodes']);

                $id = $module['id'];
                unset($module['id']);

                if (!$this->model('Module')->update($id, $module)) {
                    Router::abort(500, [
                        'message' => 'Server error: Module ' . $module['title'] . ' not updated'
                    ]);
                }

                // Update Nodes to updated Module
                if (!empty($nodes)) {
                    if (!$this->model('Node')->updateMultiple($nodes)) {
                        Router::abort(500, [
                            'message' => 'Server error: Module ' . $module['title'] . ' Nodes not updated'
                        ]);
                    }
                }
            }
        }

        Response::send([
            'message' => 'Updated successfully.'
        ]);
    }

    /**
     * Reset a Roadmap with its modules
     * 
     * @param array $data
     * @return void
     */
    public function reset($data = [])
    {
        // Get the Roadmap
        $roadmap = $this->model->get($data['id']);
        $user_id = Auth::user()->id;

        $roadmap->modules = $this->model('Module')->getAllBy("roadmap_id", $roadmap->id);

        if ($this->model->isCompleted($user_id, $roadmap->id)) {
            $this->model->uncomplete($user_id, $roadmap->id);
        }

        foreach ($roadmap->modules as $module) {
            if ($this->model('Module')->isCompleted($user_id, $module->id)) {
                if (!$this->model('Module')->uncomplete($user_id, $module->id)) {
                    Router::abort(500, [
                        'message' => 'Server error: Module ' . $module['title'] . ' not reseted'
                    ]);
                }
            }
        }

        Response::send([
            'message' => 'Reseted successfully.'
        ]);
    }

    /**
     * Get Roadmap status for the logged in user
     * 
     * @param array $data
     * @return void
     */
    public function status($data = [])
    {
        // get user id from token username
        $user_id = Auth::user()->id;
        $roadmap = $this->model->get($data['id']);

        $status['title'] = $roadmap->title;
        $status['description'] = $roadmap->description;
        $status['started'] = $this->model->isStarted($user_id, $data['id']);
        $status['relaxed'] = $this->model->isRelaxed($user_id, $data['id']);
        $status['completed'] = $this->model->isCompleted($user_id, $data['id']);
        $status['completed_modules'] = $this->model('Module')->completed($data['id']);
        $status['uncompleted_modules'] = $this->model('Module')->uncompleted($data['id']);

        Response::send(
            $status
        );
    }

    /**
     * Toggle Roadmap mode status
     * 
     * @param array $data
     * @return void
     */
    public function toggleMode($data = [])
    {
        // get user id from token username
        $user_id = Auth::user()->id;

        if ($this->model->isRelaxed($user_id, $data['id'])) {
            if ($this->model->unrelax($user_id, $data['id']) === false) {
                Router::abort(500, [
                    'message' => 'Server error'
                ]);
            }
        } else {
            if ($this->model->relax($user_id, $data['id']) === false) {
                Router::abort(500, [
                    'message' => 'Server error'
                ]);
            }
        }

        Response::send([
            'message' => 'Toggled successfully.'
        ]);
    }
    /**
     * Toggle Roadmap started status
     * 
     * @param array $data
     * @return void
     */
    public function toggleStarted($data = [])
    {
        // get user id from token username
        $user_id = Auth::user()->id;

        if ($this->model->isStarted($user_id, $data['id'])) {
            Response::send([
                'message' => 'Already started.'
            ]);
        }

        if ($this->model->start($user_id, $data['id']) === false) {
            Router::abort(500, [
                'message' => 'Server error'
            ]);
        }

        Response::send([
            'message' => 'Started successfully.'
        ]);
    }

    /**
     * Delete a Roadmap
     *
     * @param array $data
     * @return void
     */
    public function destroy($data = [])
    {
        if (!$this->model->delete($data['id'])) {
            Router::abort(500, [
                'message' => 'Server error'
            ]);
        }

        Response::send([
            'message' => 'Deleted successfully.'
        ]);
    }
}
