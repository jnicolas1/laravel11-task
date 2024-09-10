<!-- ====== Table Section Start -->
<section class="bg-white" wire:poll="renderAllTasks">
    <div class="container">
        <div class="flex flex-wrap -mx-4">
            <div class="w-full px-4">
                <div class="max-w-full overflow-x-auto">
                    <button class="bg-purple-800 text-white px-4 py-2 rounded-md hover:bg-purple-700 my-6"
                        wire:click= "openCreateModal">
                        Nuevo
                    </button>

                    <table class="table-auto w-full">
                        <thead>
                            <tr class="bg-purple-800 text-center">
                                <th
                                    class=" w-1/6 min-w-[160px] text-lg font-semibold text-white py-4 lg:py-7 px-3 lg:px-4 border-l border-transparent">
                                    Titulo
                                </th>
                                <th
                                    class="w-1/6 min-w-[160px] text-lg font-semibold text-white py-4 lg:py-7 px-3 lg:px-4">
                                    Descripción
                                </th>
                                <th
                                    class="w-1/6 min-w-[160px] text-lg font-semibold text-white py-4 lg:py-7 px-3 lg:px-4">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tasks as $task)
                                <tr>
                                    <td
                                        class="text-center text-dark font-medium text-base py-5 px-2 bg-[#F3F6FF] border-b border-l border-[#E8E8E8]">
                                        {{ $task->title }}
                                    </td>
                                    <td
                                        class="text-center text-dark font-medium text-base py-5 px-2 bg-white border-b border-[#E8E8E8]">
                                        {{ $task->description }}
                                    </td>
                                    <td
                                        class="text-center text-dark font-medium text-base py-5 px-2 bg-[#F3F6FF] border-b border-[#E8E8E8]">
                                        @if ((isset($task->permission) && $task->permission == 'edit') || Auth::user()->id == $task->user_id)
                                            <button wire:click="openCreateModal({{ $task }})"
                                                class="bg-yellow-800 text-white px-4 py-2 rounded-md hover:bg-yellow-700">
                                                Editar
                                            </button>
                                            <button wire:confirm="Are you sure you want to delete this task?"
                                                wire:click="deleteTask({{ $task }})"
                                                class="bg-red-800 text-white px-4 py-2 rounded-md hover:bg-red-700">
                                                Eliminar
                                            </button>
                                        @endif

                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <!-- Main Modal -->
    @if ($modal == true)
        <div class="fixed left-0 top-0 flex h-full w-full items-center justify-center bg-black bg-opacity-50 py-10">
            <div class="max-h-full w-full max-w-xl overflow-y-auto sm:rounded-2xl bg-white">
                <div class="w-full">
                    <div class="m-8 my-20 max-w-[400px] mx-auto">
                        <div class="mb-8">
                            <h1 class="mb-4 text-3xl font-extrabold">Crear nueva tarea</h1>
                            <form>
                                <div class="mb-4">
                                    <label for="title"
                                        class="block mb-2 text-sm font-medium text-gray-900">Título</label>
                                    <input autofocus type="text" id="title" name="title" wire:model="title"
                                        class="bg-gray-50 border border-gray-300 focus:ring-blue-500 w-full focus:
                                ring-2 focus:ring-inset focus:border-blue-500 rounded-md">
                                </div>
                                <div class="mb-4">
                                    <label for="description"
                                        class="block mb-2 text-sm font-medium text-gray-900">Descripción</label>
                                    <input type="text" id="description" name="description" wire:model="description"
                                        class="bg-gray-50 border border-gray-300 focus:ring-blue-500 w-full focus:
                                ring-2 focus:ring-inset focus:border-blue-500 rounded-md">
                                </div>

                            </form>
                        </div>
                        <div class="flex flex-row">
                            <button class="p-3 bg-black rounded-full text-white w-full font-semibold"
                                wire:click="createorUpdateTask">
                                {{ isset($miTarea->id) ? 'Actualizar' : 'Crear' }} tarea
                            </button>
                            <button class="p-3 bg-white border rounded-full w-full font-semibold"
                                wire:click.prevent="closeCreateModal">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

</section>
<!-- ====== Table Section End -->
