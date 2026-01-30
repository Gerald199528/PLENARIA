<?php

namespace App\Livewire;

use App\Models\Cronica;
use App\Models\CategoriaCronica;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class CronicaTable extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'cronica-table';

    public function setUp(): array
    {
        return [
            PowerGrid::exportable(fileName: 'cronicas')
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),

            PowerGrid::header()->showSearchInput(),

            PowerGrid::footer()
                ->showPerPage(perPage: 10, perPageValues: [10, 20, 50, 100])
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Cronica::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields([
            'titulo',
            'contenido',
            'archivo_pdf',
            'cronista_id',
            'categoria_id',
        ])
        ->add('cronista_nombre', function ($model) {
            if ($model->cronista) {
                return $model->cronista->nombre_completo . ' ' . $model->cronista->apellido_completo;
            }
            return 'Sin cronista';
        })
        ->add('categoria_nombre', function ($model) {
            return $model->categoria
                ? $model->categoria->nombre
                : '<span class="text-gray-400 italic">Sin categoría</span>';
        })
        ->add('fecha_publicacion_formatted', fn($model) => Carbon::parse($model->fecha_publicacion)
            ->timezone('America/Caracas')
            ->format('d/m/Y h:i A'))
        ->add('created_at_formatted', fn($model) => Carbon::parse($model->created_at)
            ->timezone('America/Caracas')
            ->format('d/m/Y h:i A'))
        ->add('archivo_pdf_formatted', function ($model) {
            if ($model->archivo_pdf) {
                return basename($model->archivo_pdf);
            }
            return 'Sin archivo';
        });
    }

    public function columns(): array
    {
        return [
            Column::action('Acciones'),

            Column::make('ID', 'id')
                ->sortable()
                ->searchable(),

            Column::make('Título', 'titulo')
                ->sortable()
                ->searchable()
                ->bodyAttribute('truncate max-w-xs'),

            Column::make('Contenido', 'contenido')
                ->sortable()
                ->searchable()
                ->bodyAttribute('truncate max-w-xs'),

            Column::make('Archivo PDF', 'archivo_pdf_formatted')
                ->sortable()
                ->searchable()
                ->bodyAttribute('text-center'),

            Column::make('Cronista', 'cronista_nombre')
                ->sortable()
                ->searchable()
                ->bodyAttribute('text-center'),

            Column::make('Categoría', 'categoria_nombre')
                ->sortable()
                ->searchable()
                ->bodyAttribute('text-center'),

            Column::make('Fecha Publicación', 'fecha_publicacion_formatted', 'fecha_publicacion')
                ->sortable()
                ->bodyAttribute('text-center'),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('titulo')->operators(['contains']),
            Filter::inputText('contenido')->operators(['contains']),
            Filter::inputText('archivo_pdf_formatted')->operators(['contains']),
            Filter::inputText('cronista_nombre')->operators(['contains']),
            Filter::inputText('categoria_nombre')->operators(['contains']),
            Filter::datepicker('fecha_publicacion'),
        ];
    }

    public function actions(Cronica $row): array
    {
        return [
            // Botón Descargar
            Button::add('download_pdf')
                ->slot('<i class="fas fa-download"></i>')
                ->class('bg-emerald-600 text-white px-2 sm:px-3 py-1 sm:py-2 rounded-md hover:bg-emerald-700 shadow-sm mr-1 sm:mr-2 text-xs sm:text-sm transition-all duration-200 transform hover:scale-105')
                ->attributes([
                    'onclick' => "const link = document.createElement('a');
                                link.href = '" . asset('storage/' . $row->archivo_pdf) . "';
                                link.download = '" . basename($row->archivo_pdf) . "';
                                document.body.appendChild(link);
                                link.click();
                                document.body.removeChild(link);",
                    'title' => 'Descargar'
                ]),

// Botón Ver PDF
Button::add('view')
    ->slot('<i class="fas fa-eye"></i>')
    ->class('bg-amber-500 text-black px-2 sm:px-3 py-1 sm:py-1.5 rounded-md hover:bg-amber-600 shadow-sm mr-1 sm:mr-2 text-xs sm:text-sm transition-all duration-300 hover:scale-105')
    ->attributes([
        'onclick' => "window.open('" . asset('storage/' . $row->archivo_pdf) . "', '_blank')",
        'title' => 'Ver documento'
    ]),
            // Botón Editar
            Button::add('edit')
                ->slot('<i class="fas fa-edit"></i>')
                ->class('bg-indigo-600 text-white px-2 sm:px-3 py-1 sm:py-1.5 rounded-md hover:bg-indigo-700 shadow-sm mr-1 sm:mr-2 text-xs sm:text-sm transition-all duration-300 hover:scale-105')
                ->route('admin.cronicas.edit', ['cronica' => $row->id])
                ->attributes(['wire:navigate' => true]),

            // Botón Eliminar
            Button::add('delete')
                ->slot('<i class="fas fa-trash"></i>')
                ->class('bg-red-600 text-white px-2 sm:px-3 py-1 sm:py-1.5 rounded-md hover:bg-red-700 shadow-sm text-xs sm:text-sm transition-all duration-300 hover:scale-105')
                ->attributes(['onclick' => "confirmDelete({$row->id})"]),
        ];
    }
}