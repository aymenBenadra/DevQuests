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
                'status' => 'error',
                'message' => 'Server error'
            ]);
        }

        Response::send([
            'status' => 'success',
            'data' => $questions,
            'count' => count($questions)
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

        if ($question === false) {
            Router::abort(404, [
                'status' => 'error',
                'message' => 'Question not found'
            ]);
        }

        Response::send([
            'status' => 'success',
            'data' => $question
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
        // check if the question already exists
        if ($this->model->getBy('question', $data['question']) !== false) {
            Router::abort(409, [
                'status' => 'error',
                'message' => 'Question already exists'
            ]);
        }

        if (!$this->model->add($data)) {
            Router::abort(500, [
                'status' => 'error',
                'message' => 'Server error'
            ]);
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
     * Update an question
     *
     * @param array $data
     * @return void
     */
    public function update($data = [])
    {
        $id = $data['id'];
        unset($data['id']);

        // check if question exists
        if (!$this->model->get($id)) {
            Router::abort(404, [
                'status' => 'error',
                'message' => 'Question not found'
            ]);
        }

        // Check if question question already exists
        $question = $this->model->getBy('question', $data['question']);

        if ($question !== false && $question->id !== $id) {
            Router::abort(409, [
                'status' => 'error',
                'message' => 'Question already taken'
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
     * Delete a question
     *
     * @param array $data
     * @return void
     */
    public function destroy($data = [])
    {
        // check if question exists
        if (!$this->model->get($data['id'])) {
            Router::abort(404, [
                'status' => 'error',
                'message' => 'Question not found'
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
