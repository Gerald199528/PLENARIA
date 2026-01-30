<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;
use Illuminate\Support\Facades\Auth;


final class UserTable extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'user-table-2zxsby-table';

    public string $sortField = 'id';
    public string $sortDirection = 'desc';

    public function setUp(): array
    {
        /* $this->showCheckBox(); */

        return [

            PowerGrid::exportable(fileName: 'usuarios')
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),

            PowerGrid::header()
                ->showSearchInput(),

            PowerGrid::footer()
                ->showPerPage(perPage: 10, perPageValues: [10, 20, 50, 100, 500, 1000, 0])
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return User::query()->with('roles');
    }

    public function relationSearch(): array
    {
       return [
            'roles' => ['name'], // Permite buscar usando roles.name
        ];

    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('name')
            ->add('last_name')
            ->add('document')
            ->add('phone')
            ->add('email')
             ->add('roles_name', fn(User $user) => $user->roles->pluck('name')->implode(', '))
            ->add('created_at')
            ->add('updated_at');
    }

    public function columns(): array
    {
        return [
            Column::action('Acciones'),

            Column::make('Id', 'id'),
            Column::make('Nombres', 'name')
                ->sortable()
                ->searchable(),

            Column::make('Apellidos', 'last_name')
                ->sortable()
                ->searchable(),

            Column::make('Documento', 'document')
                ->sortable()
                ->searchable(),

            Column::make('Teléfono', 'phone')
                ->sortable()
                ->searchable(),

            Column::make('Correo', 'email')
                ->sortable()
                ->searchable(),

            Column::make('Roles', 'roles_name')
              ->sortable()
                ->searchable(),


            Column::make('Creado el', 'created_at', 'created_at')
                ->sortable(),

            Column::make('Actualizado el', 'created_at', 'updated_at')
                ->sortable()
                ->searchable(),

        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('name')->operators(['contains']),
            Filter::inputText('last_name')->operators(['contains']),
            Filter::inputText('document')->operators(['contains']),
            Filter::inputText('phone')->operators(['contains']),
            Filter::inputText('email')->operators(['contains']),
            Filter::inputText('roles_name')->operators(['contains']),
            Filter::inputText('created_at')->operators(['contains']),
            Filter::inputText('updated_at')->operators(['contains']),
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert('.$rowId.')');
    }

public function actions(User $row): array
{
    $actions = [];

    if (Auth::check() && Auth::user()->can('view-user')) {
        $actions[] = Button::add('show')
            ->slot('<i class="fas fa-eye"></i>')
            ->class('bg-amber-500 text-black px-2 sm:px-3 py-1 sm:py-1.5 rounded-md hover:bg-amber-600 shadow-sm mr-1 sm:mr-2 text-xs sm:text-sm transition-all duration-300 hover:scale-105')
            ->route('admin.users.show', ['user' => $row->id])
            ->attributes(['wire:navigate' => true, 'title' => 'Ver usuario']);
    }

    if (Auth::check() && Auth::user()->can('edit-user')) {
        $actions[] = Button::add('edit')
            ->slot('<i class="fas fa-edit"></i>')
            ->class('bg-indigo-600 text-white px-2 sm:px-3 py-1 sm:py-1.5 rounded-md hover:bg-indigo-700 shadow-sm mr-1 sm:mr-2 text-xs sm:text-sm transition-all duration-300 hover:scale-105')
            ->route('admin.users.edit', ['user' => $row->id])
            ->attributes(['wire:navigate' => true, 'title' => 'Editar usuario']);
    }
if (Auth::check() && Auth::user()->can('print-user')) {
    $actions[] = Button::add('pdf')
        ->slot('<i class="fas fa-file-pdf"></i>')
        ->class('bg-blue-600 text-white px-2 sm:px-3 py-1 sm:py-1.5 rounded-md hover:bg-blue-700 shadow-sm mr-1 sm:mr-2 text-xs sm:text-sm transition-all duration-300 hover:scale-105')
        ->attributes([
            'wire:click' => "\$parent.call('downloadUserPdf', {$row->id})",
            'title' => 'Descargar PDF',
            'style' => 'cursor: pointer;'
        ]);
}
    if (Auth::check() && Auth::user()->can('delete-user')) {
        $actions[] = Button::add('delete')
            ->slot('<i class="fas fa-trash"></i>')
            ->class('bg-red-600 text-white px-2 sm:px-3 py-1 sm:py-1.5 rounded-md hover:bg-red-700 shadow-sm text-xs sm:text-sm transition-all duration-300 hover:scale-105')
            ->attributes(['onclick' => 'confirmDelete(' . $row->id . ')', 'title' => 'Eliminar usuario']);
    }

    return $actions;
}



    public function header(): array
    {
        return [
            Button::add('group-wrapper')
                ->class('btn-group')
        ];
    }


}
