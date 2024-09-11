<?php

namespace App\Livewire;

use App\Jobs\RemoveAllTasks;
use App\Mail\SharedTask;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
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

    public $users = [];
    public $user_id;
    public $permiso;
    public $modalShare = false;

    public function mount()
    {
        $this->users = User::where('id', "!=", auth()->user()->id)->get();
        $this->tasks = $this->getTasks()->sortByDesc('id');
    }

    public function renderAllTasks()
    {
        $this->tasks = $this->getTasks()->sortByDesc('id');
    }

    public function getTasks()
    {
        $user = auth()->user(); //no detecta pero si funciona
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

    public function openShareModal(Task $task)
    {
        $this->modalShare = true;
        $this->miTarea = $task;
    }

    public function closeShareModal()
    {
        $this->modalShare = false;
    }

    public function shareTask()
    {
        $task = Task::find($this->miTarea->id);
        $user = User::find($this->user_id);
        $user->sharedTasks()->attach($task->id, ['permission' => $this->permiso]);
        $this->closeShareModal();
        $this->tasks = $this->getTasks()->sortByDesc('id');

        $userOrigin = User::find(auth()->user()->id);
        Mail::to($user->email)->queue(new SharedTask($task,$userOrigin));
    }

    public function taskUnShared(Task $task) {
        $user = User::find(auth()->user()->id);
        $user->sharedTasks()->detach($task->id);
        $this->tasks = $this->getTasks()->sortByDesc('id');
    }

    public function removeAllTasks() {
        $user = User::find(auth()->user()->id);
        RemoveAllTasks::dispatch($user);//envia  a la cola de trabajo
        $this->tasks = $this->getTasks()->sortByDesc('id');       
        
    }

    public function recoverAllTasks() {
        $user = User::find(auth()->user()->id);
        $user->tasks()->restore();
        $this->tasks = $this->getTasks()->sortByDesc('id');
    }
}
