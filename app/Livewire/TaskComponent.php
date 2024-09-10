<?php

namespace App\Livewire;

use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TaskComponent extends Component
{
    public $tasks = [];
    public $miTarea = null;
    public $id;
    public $title;
    public $description;
    public $modal = false;
    public $isUpdating = false;


    public function mount()
    {
        $this->tasks = $this->getTasks()->sortByDesc('id');
    }

    public function renderAllTasks() {
        $this->tasks = $this->getTasks()->sortByDesc('id');
    }

    public function getTasks()
    {
        $user = auth()->user();//no detecta pero si funciona
        //$ = Auth::user();
        $misTareas = Task::where('user_id', $user->id)->get();
        $misSharedTasks =  $user->sharedTasks()->get();
        return $misSharedTasks->merge($misTareas);
    }
    
   

    public function render()
    {
        return view('livewire.task-component');
    }

    private function clearFields()
    {
        $this->title = '';
        $this->description = '';
        $this->id = '';
        $this->miTarea = null;
        $this->isUpdating = false;
    }
    public function openCreateModal(Task $task = null)
    {
        if ($task) {
            $this->isUpdating = true;
            $this->miTarea = $task;
            $this->title = $task->title;
            $this->description = $task->description;
            $this->id = $task->id;
            //$task->save();
        } else {
            $this->clearFields();
        }
        $this->modal = true;
    }

    public function closeCreateModal()
    {
        $this->modal = false;
    }

    public function createorUpdateTask()
    {
        if ($this->miTarea->id) {
            $task = Task::find($this->miTarea->id);
            $task->update(
                [
                    'title' => $this->title,
                    'description' => $this->description
                ]
            );
        } else {
            Task::create(
                [
                    'title' => $this->title,
                    'description' => $this->description,
                    'user_id' => Auth::user()->id
                ]
            );
        }

        $this->clearFields();
        $this->modal = false;
        $this->tasks = $this->getTasks()->sortByDesc('id');
    }

    public function deleteTask(Task $task)
    {
        $task->delete();
        $this->tasks = $this->getTasks()->sortByDesc('id');
    }
}
