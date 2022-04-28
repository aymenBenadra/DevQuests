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

        $modules = $this->model->modules($data['id']);

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

        $roadmap->modules = $this->model->modules($data['id']) ?? [];

        if (count($roadmap->modules) > 0) {
            // get module nodes for each module
            foreach ($roadmap->modules as $i => $module) {
                $roadmap->modules[$i]->nodes = $this->model('Module')->nodes($module->id);
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

        if (!$this->model->add($data)) {
            Router::abort(500, [
                'status' => 'error',
                'message' => 'Server error'
            ]);
        }

        // Add Modules to created Roadmap
        if (isset($data['modules'])) {
            foreach ($data['modules'] as $module) {
                $module['roadmap_id'] = $this->model->getLastInsertedId();
                $this->model('Module')->add($module);
            }
        }

        Response::code(201);
        Response::send([
            'status' => 'Created successfully.',
            'data' => $this->model->get(
                $this->model->getLastInsertedId()
            )
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
        $id = $data['id'];
        unset($data['id']);

        // check if Roadmap exists
        if (!$this->model->get($id)) {
            Router::abort(404, [
                'status' => 'error',
                'message' => 'Roadmap not found'
            ]);
        }

        // Check if Roadmap title already exists
        $Roadmap = $this->model->getBy('title', $data['title']);

        if ($Roadmap !== false && $Roadmap->id !== $id) {
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
     * Toggle done status
     * 
     * @param array $data
     * @return void
     */
    public function toggleDone($data = [])
    {
        // check if Roadmap exists
        $Roadmap = $this->model->get($data['id']);

        if (!$Roadmap) {
            Router::abort(404, [
                'status' => 'error',
                'message' => 'Roadmap not found'
            ]);
        }

        $Roadmap->done = !$Roadmap->done;

        if (!$this->model->update($data['id'], ['done' => $Roadmap->done])) {
            Router::abort(500, [
                'status' => 'error',
                'message' => 'Server error'
            ]);
        }

        Response::send([
            'status' => 'Toggled successfully.',
            'data' => $Roadmap
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
