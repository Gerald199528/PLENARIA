<?php

use Livewire\Volt\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use App\Models\Setting;

new class extends Component {
    
    public function rendering(View $view)
    {
        $view->title('Usuarios');
    }

    public function deleteUser(User $user)
    {
        if ($user->id === Auth::id()) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Acción no permitida',
                'text' => 'No puedes eliminar tu propia cuenta',
            ]);
            return;
        }

        if ($user->id === 1) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Acción no permitida',
                'text' => 'No puedes eliminar el usuario principal del sistema',
            ]);
            return;
        }

        if ($user->hasRole('Super Admin')) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Acción no permitida',
                'text' => 'No puedes eliminar un usuario con rol Super Admin',
            ]);
            return;
        }

        try {
            $user->roles()->detach();
            $user->delete();

            $this->dispatch('showAlert', [
                'icon' => 'success',
                'title' => 'Usuario eliminado',
                'text' => 'El usuario se ha eliminado correctamente',
            ]);

            $this->dispatch('pg:eventRefresh-user-table-2zxsby-table');
        } catch (\Exception $e) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Ocurrió un error al intentar eliminar el usuario',
            ]);
        }
    }

    public function downloadUserPdf($userId)
    {
        try {
            $user = User::with('roles')->findOrFail($userId);

            $logoPath = Setting::get('logo_horizontal');
            $logoIcon = null;
            if ($logoPath && Storage::disk('public')->exists($logoPath)) {
                $imageContent = Storage::disk('public')->get($logoPath);
                $mimeType = Storage::disk('public')->mimeType($logoPath);
                $logoIcon = 'data:' . $mimeType . ';base64,' . base64_encode($imageContent);
            }

                        $image = null;
                if ($user->image_url) {
                    $path = storage_path('app/public/' . $user->image_url);
                    if (file_exists($path)) {
                        $imageContent = file_get_contents($path);
                        $mimeType = mime_content_type($path);
                        $image = 'data:' . $mimeType . ';base64,' . base64_encode($imageContent);
                    }
                }
                $primaryColor = Setting::get('primary_color', '#0f2440');
                $secondaryColor = Setting::get('secondary_color', '#00d4ff');

            $fields = [
                ['label' => 'Nombre', 'value' => $user->name],
                ['label' => 'Apellido', 'value' => $user->last_name],
                ['label' => 'Email', 'value' => $user->email],
                ['label' => 'Documento', 'value' => $user->document ?? 'N/A'],
                ['label' => 'Teléfono', 'value' => $user->phone ?? 'N/A'],
                ['label' => 'Creado', 'value' => $user->created_at->format('d/m/Y H:i')],
            ];

            $tags = $user->roles->pluck('name')->toArray();

            $html = view('livewire.pages.admin.pdf.pdf-layout', [
                'fields' => $fields,
                'title' => $user->name . ' ' . $user->last_name,
                'subtitle' => 'Información de Usuario',
                'logo_icon' => $logoIcon,
                'image' => $image,
                'primaryColor' => $primaryColor,
                'secondaryColor' => $secondaryColor,
                'tags' => $tags,
                'badgeTitle' => 'Roles Asignados',
                'sectionTitle' => 'Información Personal'
            ])->render();

            $html = '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>' . $html;

            $pdf = Pdf::loadHTML($html)
                ->setPaper('a4')
                ->setOption('encoding', 'UTF-8')
                ->setOption('default_font', 'DejaVu Sans');

            return response()->streamDownload(function () use ($pdf) {
                echo $pdf->stream();
            }, "usuario_{$user->id}.pdf", [
                'Content-Type' => 'application/pdf',
            ]);

        } catch (\Exception $e) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Error al generar el PDF: ' . $e->getMessage(),
            ]);
        }
    }
};
?>

<div>
    <x-slot name="breadcrumbs">
        <livewire:components.breadcrumb :breadcrumbs="[
            [
                'name' => 'Dashboard',
                'route' => route('admin.dashboard'),
            ],
            [
                'name' => 'Usuarios',
            ],
        ]" />
    </x-slot>

    @can('create-user')
        <x-slot name="action">
            <div class="mt-2 sm:mt-3 md:mt-4">
                <a 
                    href="{{ route('admin.users.create') }}" 
                    wire:navigate
                    class="inline-flex items-center gap-1.5 sm:gap-2 md:gap-3 px-3 sm:px-4 md:px-6 py-1.5 sm:py-2 md:py-3 bg-gradient-to-r from-blue-600 to-indigo-500 text-white font-semibold rounded-lg sm:rounded-xl shadow-md sm:shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:from-blue-500 hover:to-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-400 text-xs sm:text-sm md:text-base"
                >
                    <i class="fa-solid fa-plus animate-bounce text-xs sm:text-sm md:text-base flex-shrink-0"></i>
                    Nuevo Usuario
                </a>
            </div>
        </x-slot>
    @endcan

    <x-container class="w-full px-4">
        <livewire:user-table />
    </x-container>

    @push('scripts')
        <script>
            function confirmDelete(user_id) {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: 'No podrás revertir esto!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.call('deleteUser', user_id);
                    }
                });
            }
        </script>
    @endpush
</div>