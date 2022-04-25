<?php

namespace App\Controllers;

use Core\{Controller, Router};
use Core\Helpers\Response;

/**
 * Resources Controller
 *
 * @author Mohammed-Aymen Benadra
 * @package App\Controllers
 */
class Resources extends Controller
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
        $this->model = $this->model('Resource');

        Response::headers();
        Response::code();
    }

    /**
     * Get all resources
     *
     * @return void
     */
    public function index()
    {
        $resources = $this->model->getAll();

        if ($resources === false) {
            Router::abort(500, [
                'status' => 'error',
                'message' => 'Server error'
            ]);
        }

        Response::send([
            'status' => 'success',
            'data' => $resources,
            'count' => count($resources)
        ]);
    }

    /**
     * Get a resource
     *
     * @param array $data
     * @return void
     */
    public function show($data = [])
    {
        $resource = $this->model->get($data['id']);

        if ($resource === false) {
            Router::abort(404, [
                'status' => 'error',
                'message' => 'resource not found'
            ]);
        }

        Response::send([
            'status' => 'success',
            'data' => $resource
        ]);
    }

    /**
     * Store a resource
     *
     * @param array $data
     * @return void
     */
    public function store($data = [])
    {
        if (!$this->model->add($data)) {
            Router::abort(500, [
                'status' => 'error',
                'message' => 'Server error'
            ]);
        }

        $resource = $this->model->get(
            $this->model->getLastInsertedId()
        );

        Response::code(201);
        Response::send([
            'status' => 'Created successfully.',
            'data' => $resource
        ]);
    }

    /**
     * Update an resource
     *
     * @param array $data
     * @return void
     */
    public function update($data = [])
    {
        $id = $data['id'];
        unset($data['id']);

        // check if resource exists
        $resource = $this->model->get($id);

        if (!$resource) {
            Router::abort(404, [
                'status' => 'error',
                'message' => 'resource not found'
            ]);
        }

        if (!$this->model->update($id, $data)) {
            Router::abort(500, [
                'status' => 'error',
                'message' => 'Server error'
            ]);
        }

        $resource = $this->model->get($id);

        Response::send([
            'status' => 'Updated successfully.',
            'data' => $resource
        ]);
    }

    /**
     * Toggle visited status
     * 
     * @param array $data
     * @return void
     */
    public function toggle($data = [])
    {
        $id = $data['id'];

        // check if resource exists
        $resource = $this->model->get($id);

        if (!$resource) {
            Router::abort(404, [
                'status' => 'error',
                'message' => 'resource not found'
            ]);
        }

        if (!$this->model->update($id, ['visited' => !$resource->visited])) {
            Router::abort(500, [
                'status' => 'error',
                'message' => 'Server error'
            ]);
        }

        $resource = $this->model->get($id);

        Response::send([
            'status' => 'Toggled successfully.',
            'data' => $resource
        ]);
    }

    /**
     * Delete a resource
     *
     * @param array $data
     * @return void
     */
    public function destroy($data = [])
    {
        // check if resource exists
        $resource = $this->model->get($data['id']);

        if (!$resource) {
            Router::abort(404, [
                'status' => 'error',
                'message' => 'resource not found'
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
