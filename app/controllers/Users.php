<?php

namespace App\Controllers;

use Core\{Controller, Router};
use Core\Helpers\Response;

/**
 * Users Controller
 *
 * @author Mohammed-Aymen Benadra
 * @package App\Controllers
 */
class Users extends Controller
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
        $this->model = $this->model('User');

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
                'message' => 'Resource not found'
            ]);
        }

        Response::send([
            'status' => 'success',
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
        if (!$this->model->get($id)) {
            Router::abort(404, [
                'status' => 'error',
                'message' => 'Resource not found'
            ]);
        }

        // Check if resource title already exists
        $resource = $this->model->getBy('title', $data['title']);

        if ($resource !== false && $resource->id !== $id) {
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
            'status' => 'Updated successfully.'
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
        if (!$this->model->get($data['id'])) {
            Router::abort(404, [
                'status' => 'error',
                'message' => 'Resource not found'
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
