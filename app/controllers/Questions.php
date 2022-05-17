<?php

namespace App\Controllers;

use Core\{Controller, Router};
use Core\Helpers\Response;

/**
 * Questions Controller
 *
 * @author Mohammed-Aymen Benadra
 * @package App\Controllers
 */
class Questions extends Controller
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
        $this->model = $this->model('Question');

        Response::headers();
        Response::code();
    }

    /**
     * Get all Questions
     *
     * @return void
     */
    public function index()
    {
        $questions = $this->model->getAll();

        if ($questions === false) {
            Router::abort(500, [
                'message' => 'Server error'
            ]);
        }

        Response::send([
            $questions
        ]);
    }

    /**
     * Get a question
     *
     * @param array $data
     * @return void
     */
    public function show($data = [])
    {
        $question = $this->model->get($data['id']);

        Response::send([
            $question
        ]);
    }

    /**
     * Store a question
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
     * Update an question
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
     * Delete a question
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
