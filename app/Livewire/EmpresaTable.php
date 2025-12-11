<?php

namespace App\Livewire;

use App\Models\Empresa;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class EmpresaTable extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'empresa-table';

    public function setUp(): array
    {
        return [
            PowerGrid::exportable(fileName: 'empresas')
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),

            PowerGrid::header()->showSearchInput(),

            PowerGrid::footer()
                ->showPerPage(perPage: 10, perPageValues: [10, 20, 50])
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Empresa::query();
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('name')
            ->add('razon_social')
            ->add('rif')
            ->add('direccion_fiscal')
            ->add('oficina_principal')
            ->add('horario_atencion')
            ->add('telefono_principal')
            ->add('telefono_secundario')
            ->add('email_principal')
            ->add('email_secundario')
            ->add('domain')
            ->add('actividad')
            ->add('description')
            ->add('organigrama_ruta')
            ->add('mision')
            ->add('vision')
            ->add('created_at_formatted', fn($e) => $e->created_at->format('d/m/Y h:i A'));
    }

    public function columns(): array
    {
        return [
            Column::action('Acciones'),

            Column::make('ID', 'id')
                ->sortable()
                ->searchable(),

            Column::make('Nombre', 'name')
                ->sortable()
                ->searchable(),

            Column::make('Razón Social', 'razon_social')
                ->sortable()
                ->searchable(),

            Column::make('RIF', 'rif')
                ->sortable()
                ->searchable(),

            Column::make('Teléfono', 'telefono_principal')
                ->sortable()
                ->searchable(),

            Column::make('Email', 'email_principal')
                ->sortable()
                ->searchable(),

            Column::make('Domain', 'domain')
                ->sortable()
                ->searchable(),

            Column::make('Actividad', 'actividad')
                ->sortable()
                ->searchable(),

            Column::make('Creado', 'created_at_formatted')
                ->sortable(),
        ];
    }

public function filters(): array
{
    return [
        Filter::inputText('id')->operators(['contains']),
        Filter::inputText('name')->operators(['contains']),
        Filter::inputText('razon_social')->operators(['contains']),
        Filter::inputText('rif')->operators(['contains']),
        Filter::inputText('telefono_principal')->operators(['contains']),
        Filter::inputText('email_principal')->operators(['contains']),
        Filter::inputText('domain')->operators(['contains']),
        Filter::inputText('actividad')->operators(['contains']),
        Filter::inputText('created_at_formatted')->operators(['contains']),
    ];
}
public function actions($row): array
{
    return [
        // PDF
        Button::add('pdf')
            ->slot('<i class="fas fa-file-pdf"></i>')
            ->class('bg-blue-600 text-white px-2 sm:px-3 py-1 sm:py-1.5 rounded-md hover:bg-blue-700 shadow-sm mr-1 sm:mr-2 text-xs sm:text-sm transition-all duration-200')
            ->attributes([
                'wire:click' => "\$parent.call('generatePdf', {$row->id})",
                'title' => 'Descargar PDF',
                'style' => 'cursor: pointer;'
            ]),

        // Editar
        Button::add('edit')
            ->slot('<i class="fas fa-edit"></i>')
            ->class('bg-indigo-600 text-white px-2 sm:px-3 py-1 sm:py-1.5 rounded-md hover:bg-indigo-700 shadow-sm mr-1 sm:mr-2 text-xs sm:text-sm transition-all duration-200')
            ->route('admin.empresa.edit', ['empresa' => $row->id])
            ->attributes(['wire:navigate' => true, 'title' => 'Editar']),

        // Ver organigrama
        Button::add('ver')
            ->slot('<i class="fas fa-eye"></i>')
            ->class('bg-amber-500 text-black px-2 sm:px-3 py-1 sm:py-1.5 rounded-md hover:bg-amber-600 shadow-sm mr-1 sm:mr-2 text-xs sm:text-sm transition-all duration-200')
            ->attributes([
                'title' => 'Ver organigrama',
                'onclick' => $row->organigrama_ruta
                    ? "window.open('" . asset('storage/' . $row->organigrama_ruta) . "', '_blank')"
                    : "alert('No hay organigrama disponible')"
            ]),

        // Descargar organigrama
        Button::add('download')
            ->slot('<i class="fas fa-download"></i>')
            ->class('bg-emerald-600 text-white px-2 sm:px-3 py-1 sm:py-1.5 rounded-md hover:bg-emerald-700 shadow-sm mr-1 sm:mr-2 text-xs sm:text-sm transition-all duration-200')
            ->attributes([
                'title' => 'Descargar organigrama',
                'onclick' => $row->organigrama_ruta
                    ? "const link = document.createElement('a');
                       link.href = '" . asset('storage/' . $row->organigrama_ruta) . "';
                       link.download = '" . basename($row->organigrama_ruta) . "';
                       document.body.appendChild(link);
                       link.click();
                       document.body.removeChild(link);"
                    : "alert('No hay organigrama disponible')"
            ]),

        // Eliminar
        Button::add('delete')
            ->slot('<i class="fas fa-trash"></i>')
            ->class('bg-red-600 text-white px-2 sm:px-3 py-1 sm:py-1.5 rounded-md hover:bg-red-700 shadow-sm text-xs sm:text-sm transition-all duration-200')
            ->attributes([
                'title' => 'Eliminar',
                'onclick' => "confirmDeleteEmpresa({$row->id})"
            ]),
    ];
}
}
