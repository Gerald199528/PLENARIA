<?php

namespace App\Livewire;

use App\Models\Cronista;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class CronistaTable extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'cronista-table';

    public function setUp(): array
    {
        return [
            PowerGrid::exportable(fileName: 'cronistas')
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),

            PowerGrid::header()->showSearchInput(),

            PowerGrid::footer()
                ->showPerPage(perPage: 10, perPageValues: [10, 20, 50, 100])
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Cronista::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('cedula')
            ->add('nombre_completo')
            ->add('apellido_completo')
            ->add('email')
            ->add('telefono')
            ->add('cargo')
            ->add('perfil')
            ->add('imagen_url')
            ->add('created_at_formatted', fn($c) => $c->created_at->format('d/m/Y h:i A'))
            ->add('imagen_formatted', function ($cronista) {
                if ($cronista->imagen_url) {
                    return '<div class="flex justify-center"><img src="' . asset('storage/' . $cronista->imagen_url) . '" class="w-12 h-12 object-cover rounded-full border-2 border-gray-200 shadow-sm"></div>';
                }
                return '<div class="flex justify-center"><div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center"><svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg></div></div>';
            });
    }

    public function columns(): array
    {
        return [
            Column::action('Acciones'),

            Column::make('ID', 'id')
                ->sortable()
                ->searchable(),

            Column::make('Imagen Perfil', 'imagen_formatted')
                ->bodyAttribute('text-center')
                ->field('imagen_formatted')
                ->editOnClick(false)
                ->visibleInExport(false),

            Column::make('Cédula', 'cedula')
                ->sortable()
                ->searchable()
                ->bodyAttribute('truncate max-w-xs'),

            Column::make('Nombre', 'nombre_completo')
                ->sortable()
                ->searchable()
                ->bodyAttribute('truncate max-w-xs'),

            Column::make('Apellido', 'apellido_completo')
                ->sortable()
                ->searchable()
                ->bodyAttribute('truncate max-w-xs'),

            Column::make('Email', 'email')
                ->sortable()
                ->searchable()
                ->bodyAttribute('truncate max-w-xs'),

            Column::make('Teléfono', 'telefono')
                ->sortable()
                ->searchable()
                ->bodyAttribute('truncate max-w-xs'),

            Column::make('Cargo', 'cargo')
                ->sortable()
                ->searchable()
                ->bodyAttribute('truncate max-w-xs'),

            Column::make('Perfil', 'perfil')
                ->sortable()
                ->searchable()
                ->bodyAttribute('truncate max-w-xs'),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('id')->operators(['contains']),
            Filter::inputText('cedula')->operators(['contains']),
            Filter::inputText('nombre_completo')->operators(['contains']),
            Filter::inputText('apellido_completo')->operators(['contains']),
            Filter::inputText('email')->operators(['contains']),
            Filter::inputText('telefono')->operators(['contains']),
            Filter::inputText('cargo')->operators(['contains']),
            Filter::inputText('perfil')->operators(['contains']),
        ];
    }


public function actions(Cronista $row): array
{
    return [
        Button::add('pdf')
            ->slot('<i class="fas fa-file-pdf"></i>')
            ->class('bg-blue-600 text-white px-2 sm:px-3 py-1 sm:py-1.5 rounded-md hover:bg-blue-700 shadow-sm mr-1 sm:mr-2 text-xs sm:text-sm transition-all duration-300 hover:scale-105')
            ->attributes([
                'wire:click' => "\$parent.call('downloadCronistaPdf', {$row->id})",
                'title' => 'Descargar PDF',
                'style' => 'cursor: pointer;'
            ]),

        Button::add('edit')
            ->slot('<i class="fas fa-edit"></i>')
            ->class('bg-indigo-600 text-white px-2 sm:px-3 py-1 sm:py-1.5 rounded-md hover:bg-indigo-700 shadow-sm mr-1 sm:mr-2 text-xs sm:text-sm transition-all duration-300 hover:scale-105')
            ->route('admin.cronistas.edit',['cronista' => $row->id])
            ->attributes(['wire:navigate' => true]),
        
        Button::add('delete')
            ->slot('<i class="fas fa-trash"></i>')
            ->class('bg-red-600 text-white px-2 sm:px-3 py-1 sm:py-1.5 rounded-md hover:bg-red-700 shadow-sm text-xs sm:text-sm transition-all duration-300 hover:scale-105')
            ->attributes(['onclick' => "confirmDeleteCronista({$row->id})"]),
    ];
}}