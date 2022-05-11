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
