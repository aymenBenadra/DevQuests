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
     * Get a module
     *
     * @param array $data
     * @return void
     */
    public function show($data = [])
    {
        $module = isset($data['id'])
            ? $this->model->get($data['id'])
            : $this->model->getBy('title', $data['title']);

        Response::send(
            $module
        );
    }

    /**
     * Toggle completed status
     * 
     * @param array $data
     * @return void
     */
    public function toggleCompleted($data = [])
    {
        // get user id from token username
        $user_id = Auth::user()->id;
        $roadmap_id = $this->model->get($data['id'])->roadmap_id;

        // Mark Roadmap as started if not already started
        if (!$this->model('Roadmap')->isStarted($user_id, $roadmap_id)) {
            $this->model('Roadmap')->start($user_id, $roadmap_id);
        }

        // check if user has already completed this module
        if ($this->model->isCompleted($user_id, $data['id'])) {
            if ($this->model->uncomplete($user_id, $data['id']) === false) {
                Router::abort(500, [
                    'message' => 'Server error'
                ]);
            }
        } else {
            if ($this->model->complete($user_id, $data['id']) === false) {
                Router::abort(500, [
                    'message' => 'Server error'
                ]);
            }
        }

        // Check if all modules are completed for this roadmap to mark it as completed
        if (!$this->model->uncompleted($roadmap_id)) {
            $this->model('Roadmap')->complete($user_id, $roadmap_id);
        } else {
            $this->model('Roadmap')->uncomplete($user_id, $roadmap_id);
        }

        Response::send([
            'message' => 'Completed successfully.'
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
