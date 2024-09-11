<?php

namespace App\Console\Commands;

use App\Models\Task;
use App\Models\User;
use Illuminate\Console\Command;

class DeleteTaskPending extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:deletetask';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Borra todas las tareas que estan en softdeleted';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //borrar tarea que el deleted_at es mayor a 5 días
        Task::where('deleted_at', '!=', null)
        ->where('deleted_at', '<', now()->subDays(5))
        ->forceDelete();

    }
}
