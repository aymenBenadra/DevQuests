<?php

namespace App\Controllers;

use Core\{Controller, Router};
use Core\Helpers\Response;

/**
 * Modules Controller
 *
 * @author Mohammed-Aymen Benadra
 * @package App\Controllers
 */
class Modules extends Controller
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
        $this->model = $this->model('Module');

        Response::headers();
        Response::code();
    }

    /**
     * Get all Modules
     *
     * @return void
     */
    public function index()
    {
        $modules = $this->model->getAll();

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
     * Get all Roadmaps of a module
     * 
     * @param int $data
     * @return array
     */
    public function getRoadmaps($data = [])
    {
        // check if roadmap exists
        if ($this->model->get($data['id']) === false) {
            Router::abort(404, [
                'status' => 'error',
                'message' => 'Module not found'
            ]);
        }

        $roadmaps = $this->model->roadmaps($data['id']);

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
     * Get a module
     *
     * @param array $data
     * @return void
     */
    public function show($data = [])
    {
        $module = $this->model->get($data['id']);

        if ($module === false) {
            Router::abort(404, [
                'status' => 'error',
                'message' => 'Module not found'
            ]);
        }

        $module->nodes = $this->model->nodes($data['id']);

        Response::send([
            'status' => 'success',
            'data' => $module
        ]);
    }

    /**
     * Store a module
     *
     * @param array $data
     * @return void
     */
    public function store($data = [])
    {
        // check if the module already exists
        if ($this->model->getBy('title', $data['title']) !== false) {
            Router::abort(409, [
                'status' => 'error',
                'message' => 'Module already exists'
            ]);
        }

        $nodes = $data['nodes'];
        unset($data['nodes']);

        // Create new module
        if (!$this->model->add($data)) {
            Router::abort(500, [
                'status' => 'error',
                'message' => 'Server error'
            ]);
        }

        // Get the id of the new module
        $module_id = $this->model->getLastInsertedId();

        // Create module nodes
        foreach ($nodes as $node) {
            $node['module_id'] = (int)$module_id;
            // var_dump($node);
            if (!$this->model('Node')->add($node)) {
                $this->model->delete($module_id);
                Router::abort(500, [
                    'status' => 'error',
                    'message' => 'Server error'
                ]);
            }
        }

        // Get the created module with nodes
        $module = $this->model->get($module_id);
        $module->nodes = $this->model->nodes($module_id);

        Response::code(201);
        Response::send([
            'status' => 'Created successfully.',
            'data' => $module
        ]);
    }

    /**
     * Update a module
     *
     * @param array $data
     * @return void
     */
    public function update($data = [])
    {
        $id = $data['id'];
        unset($data['id']);

        // check if module exists
        if (!$this->model->get($id)) {
            Router::abort(404, [
                'status' => 'error',
                'message' => 'Module not found'
            ]);
        }

        // Check if module title already exists
        $module = $this->model->getBy('title', $data['title']);

        if ($module !== false && $module->id !== $id) {
            Router::abort(409, [
                'status' => 'error',
                'message' => 'Title already taken'
            ]);
        }

        if (!$this->model->update($id, $data)) {
            Router::abort(500, [
                'status' => 'error',
                'message' => 'Server error'
            ]);
        }

        Response::send([
            'status' => 'Updated successfully.',
            'data' => $this->model->get($id)
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
        // check if module exists
        $module = $this->model->get($data['id']);

        if (!$module) {
            Router::abort(404, [
                'status' => 'error',
                'message' => 'Module not found'
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

        // Check if all modules of the given roadmap are done
        // $roadmapModules = $this->model('Roadmap')->modules($data['roadmap_id']);

        // if ($roadmapModules !== false) {
        //     $done = true;

        //     foreach ($roadmapModules as $roadmapModule) {
        //         if (!$roadmapModule->is_done) {
        //             $done = false;
        //             break;
        //         }
        //     }

        //     if ($done) {
        //         $this->model('Roadmap')->update($data['roadmap_id'], ['done' => true]);
        //     }
        // }

        Response::send([
            'status' => 'Toggled successfully.',
            'data' => $module
        ]);
    }

    /**
     * Delete a module
     *
     * @param array $data
     * @return void
     */
    public function destroy($data = [])
    {
        // check if module exists
        if (!$this->model->get($data['id'])) {
            Router::abort(404, [
                'status' => 'error',
                'message' => 'Module not found'
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
