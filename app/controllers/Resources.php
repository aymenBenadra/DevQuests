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

        Response::send([
            $resources
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
        Response::send([
            $this->model->get($data['id'])
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
                'message' => 'Server error'
            ]);
        }

        Response::code(201);
        Response::send([
            'message' => 'Created successfully.'
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
                'message' => 'Server error'
            ]);
        }

        Response::send([
            'message' => 'Updated successfully.'
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
                'message' => 'Server error'
            ]);
        }

        Response::send([
            'message' => 'Deleted successfully.'
        ]);
    }
}
