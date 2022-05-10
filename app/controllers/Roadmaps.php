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
                'status' => 'error',
                'message' => 'Server error'
            ]);
        }

        Response::send([
            'status' => 'success',
            'data' => $roadmaps,
            'count' => count($roadmaps)
        ]);
    }

    /**
     * Get all Modules for a roadmap
     * 
     * @param int $data
     * @return array
     */
    public function getModules($data = [])
    {
        // check if Roadmap exists
        if ($this->model->get($data['id']) === false) {
            Router::abort(404, [
                'status' => 'error',
                'message' => 'Roadmap not found'
            ]);
        }

        $modules = $this->model('Module')->getAllBy("roadmap_id", $data['id']);

        if ($modules === false) {
            Router::abort(500, [
                'status' => 'error',
                'message' => 'Server error'
            ]);
        }

        Response::send([
            'status' => 'success',
            'data' => $modules,
            'count' => count($modules)
        ]);
    }

    /**
     * Get a Roadmap with its modules
     *
     * @param array $data
     * @return void
     */
    public function show($data = [])
    {
        $roadmap = $this->model->get($data['id']);

        if ($roadmap === false) {
            Router::abort(404, [
                'status' => 'error',
                'message' => 'Roadmap not found'
            ]);
        }

        $roadmap->modules = $this->model('Module')->getAllBy("roadmap_id", $data['id']);

        if (!empty($roadmap->modules)) {
            foreach ($roadmap->modules as $key => $module) {
                $roadmap->modules[$key]->nodes = $this->model('Node')->getAllBy("module_id", $module->id);
            }
        }

        Response::send([
            'status' => 'success',
            'data' => $roadmap
        ]);
    }

    /**
     * Store a Roadmap
     *
     * @param array $data
     * @return void
     */
    public function store($data = [])
    {
        // check if the Roadmap already exists
        if ($this->model->getBy('title', $data['title']) !== false) {
            Router::abort(409, [
                'status' => 'error',
                'message' => 'Roadmap already exists'
            ]);
        }

        $modules = $data['modules'] ?? [];
        unset($data['modules']);

        if (!$this->model->add($data)) {
            Router::abort(500, [
                'status' => 'error',
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
                        'status' => 'error',
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
                            'status' => 'error',
                            'message' => 'Server error: Module ' . $module['title'] . ' Nodes not added'
                        ]);
                    }
                }
            }
        }

        Response::code(201);
        Response::send([
            'status' => 'Created successfully.'
        ]);
    }

    /**
     * Toggle completed status
     * 
     * @param array $data
     * @return void
     */
    public function toggleCompleted($data = [])
    {
        // check if Roadmap exists
        if (!$this->model->get($data['id'])) {
            Router::abort(404, [
                'status' => 'error',
                'message' => 'Roadmap not found'
            ]);
        }

        // get user id from token username
        $user_id = Auth::user()->id;

        if ($this->model->isCompleted($user_id, $data['id'])) {
            if ($this->model->uncomplete($user_id, $data['id']) === false) {
                Router::abort(500, [
                    'status' => 'error',
                    'message' => 'Server error'
                ]);
            }
        } else {
            if ($this->model->complete($user_id, $data['id']) === false) {
                Router::abort(500, [
                    'status' => 'error',
                    'message' => 'Server error'
                ]);
            }
        }

        Response::send([
            'status' => 'Toggled successfully.'
        ]);
    }

    /**
     * Get all Roadmaps for a user
     * 
     * @param array $data
     * @return void
     */
    public function getUserRoadmaps($data = [])
    {
        // check if user exists
        if (!$this->model('User')->get($data['id'])) {
            Router::abort(404, [
                'status' => 'error',
                'message' => 'User not found'
            ]);
        }

        $roadmaps = $this->model->getAllBy("user_id", $data['id']);

        if ($roadmaps === false) {
            Router::abort(500, [
                'status' => 'error',
                'message' => 'Server error'
            ]);
        }

        Response::send([
            'status' => 'success',
            'data' => $roadmaps,
            'count' => count($roadmaps)
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
        // check if Roadmap exists
        if (!$this->model->get($data['id'])) {
            Router::abort(404, [
                'status' => 'error',
                'message' => 'Roadmap not found'
            ]);
        }

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

        Response::send([
            'status' => 'Retrieved successfully.',
            'data' => $status
        ]);
    }

    /**
     * Toggle Roadmap relaxed status
     * 
     * @param array $data
     * @return void
     */
    public function toggleRelaxed($data = [])
    {
        // check if Roadmap exists
        if (!$this->model->get($data['id'])) {
            Router::abort(404, [
                'status' => 'error',
                'message' => 'Roadmap not found'
            ]);
        }

        // get user id from token username
        $user_id = Auth::user()->id;

        if ($this->model->isRelaxed($user_id, $data['id'])) {
            if ($this->model->unrelax($user_id, $data['id']) === false) {
                Router::abort(500, [
                    'status' => 'error',
                    'message' => 'Server error'
                ]);
            }
        } else {
            if ($this->model->relax($user_id, $data['id']) === false) {
                Router::abort(500, [
                    'status' => 'error',
                    'message' => 'Server error'
                ]);
            }
        }

        Response::send([
            'status' => 'Toggled successfully.'
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
        // check if Roadmap exists
        if (!$this->model->get($data['id'])) {
            Router::abort(404, [
                'status' => 'error',
                'message' => 'Roadmap not found'
            ]);
        }

        if (!$this->model->delete($data['id'])) {
            Router::abort(500, [
                'status' => 'error',
                'message' => 'Server error'
            ]);
        }

        Response::send([
            'status' => 'Deleted successfully.'
        ]);
    }
}
