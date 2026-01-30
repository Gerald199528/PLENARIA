<?php

namespace App\Livewire;

use Spatie\Permission\Models\Role;
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

final class RoleTable extends PowerGridComponent
{
    use WithExport;
    public string $tableName = 'role-table';

    public string $sortField = 'id';
    public string $sortDirection = 'desc';

    public function setUp(): array
    {
        return [

            PowerGrid::exportable(fileName: 'roles') 
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
        return Role::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('name')
            ->add('guard_name')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::action('Acciones'),
            Column::make('Id', 'id'),
            Column::make('Nombre', 'name'),
            Column::make('Guard Name', 'guard_name'),
            Column::make('Creado el', 'created_at', 'created_at')
                ->sortable()
                ->searchable(),
            Column::make('Actualizado el', 'created_at', 'updated_at')
                ->sortable()
                ->searchable(),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('name')->operators(['contains']),
            Filter::inputText('guard_name')->operators(['contains']),
            Filter::inputText('created_at')->operators(['contains']),
            Filter::inputText('updated_at')->operators(['contains']),
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert('.$rowId.')');
    }



public function actions(Role $row): array
{
    $actions = [];

    if(Auth::check() && Auth::user()->can('view-role')){
        $actions[] = Button::add('show')
            ->slot('<i class="fas fa-eye"></i>')
            ->class('bg-blue-600 text-white px-3 py-1 rounded-md hover:bg-blue-700 shadow-sm mr-2 transform transition-all duration-300 hover:scale-105')
            ->route('admin.roles.show', ['role' => $row->id])
            ->attributes(['wire:navigate' => true]);
    }

    if(Auth::check() && Auth::user()->can('edit-role')){
        $actions[] = Button::add('edit')
            ->slot('<i class="fas fa-edit"></i>')
            ->class('bg-indigo-600 text-white px-3 py-1 rounded-md hover:bg-indigo-700 shadow-sm mr-2 transform transition-all duration-300 hover:scale-105')
            ->route('admin.roles.edit', ['role' => $row->id])
            ->attributes(['wire:navigate' => true]);
    }

    if(Auth::check() && Auth::user()->can('delete-role')){
        $actions[] = Button::add('delete')
            ->slot('<i class="fas fa-trash"></i>')
            ->class('bg-red-600 text-white px-3 py-1 rounded-md hover:bg-red-700 shadow-sm transform transition-all duration-300 hover:scale-105')
            ->attributes(['onclick' => 'confirmDelete('.$row->id.')']);
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
