<?php

namespace App\Livewire;

use App\Models\Miembro;
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

final class MiembroTable extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'miembro-table';

  
    public function setUp(): array
    {
        return [
            PowerGrid::exportable(fileName: 'miembros')
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),

            PowerGrid::header()->showSearchInput(),

            PowerGrid::footer()
                ->showPerPage(perPage: 10, perPageValues: [10, 20, 50, 100])
                ->showRecordCount(),
        ];
    }


    public function datasource(): Builder
    {
        return Miembro::with('comisions'); // Carga la relación de comisiones
    }

    public function relationSearch(): array
    {
        return [
            'comisions' => ['nombre', 'descripcion'], // Permite buscar por nombre y descripción de la comisión
        ];
    }
    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('fecha_inicio', fn(Miembro $m) => $m->fecha_inicio ? Carbon::parse($m->fecha_inicio)->format('d/m/Y') : '')
            ->add('fecha_fin', fn(Miembro $m) => $m->fecha_fin ? Carbon::parse($m->fecha_fin)->format('d/m/Y') : '')
            ->add('estado')
      
                ->add('nombre_concejal_formatted', function($concejal) {
                    return $concejal->concejals->pluck('nombre')->implode(', ');
                })

                ->add('comisiones_formatted', function($concejal) {
                    return $concejal->comisions->pluck('nombre')->implode(', ');
                })
                ->add('descripcion_formatted', function($concejal) {
                    return $concejal->comisions->pluck('descripcion')->implode(', ');
                });
    }

    public function columns(): array
    {
        return [
            Column::action('Acciones'),

            Column::make('ID', 'id')->sortable()->searchable(),
            Column::make('Fecha Inicio', 'fecha_inicio')->sortable()->searchable(),
            Column::make('Fecha Fin', 'fecha_fin')->sortable()->searchable(),
            Column::make('Estado', 'estado')->sortable()->searchable(),       

                    Column::make('Concejal', 'nombre_concejal_formatted')
                ->sortable()
                ->searchable()
                ->bodyAttribute('truncate max-w-xs'),
 
            Column::make('Comisión', 'comisiones_formatted')
                ->sortable()
                ->searchable()
                ->bodyAttribute('truncate max-w-xs'),

            Column::make('Descripción', 'descripcion_formatted')
                ->sortable()
                ->searchable()
                ->bodyAttribute('truncate max-w-xs'),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('id')->operators(['contains']),
            Filter::datepicker('fecha_inicio'),
            Filter::datepicker('fecha_fin'),
            Filter::inputText('estado')->operators(['contains']),
            
            Filter::inputText('nombre_concejal_formatted')->operators(['contains']),
            Filter::inputText('comisiones_formatted')->operators(['contains']),
            Filter::inputText('descripcion_formatted')->operators(['contains']),
        ];
    }

    public function actions(Miembro $row): array
    {
        return [
            Button::add('edit')
                ->slot('<i class="fas fa-edit"></i>')
                ->class('bg-indigo-600 text-white px-3 py-1 rounded-md hover:bg-indigo-700 shadow-sm mr-2')
                ->route('admin.miembros.edit', ['miembro' => $row->id])
                ->attributes(['wire:navigate' => true]),

            Button::add('delete')
                ->slot('<i class="fas fa-trash"></i>')
                ->class('bg-red-600 text-white px-3 py-1 rounded-md hover:bg-red-700 transition-all duration-200 shadow-sm')
                ->attributes(['onclick' => "confirmDeleteMiembro({$row->id})"]),
        ];
    }
}
