<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class NosotrosController extends Controller
{
    public function index()
    {
        $empresa = Empresa::first();
        $logo = Setting::get('logo');

        // Calcular el tamaño del organigrama
        $organigramaSize = null;
        if ($empresa && $empresa->organigrama_ruta) {
            $filePath = storage_path('app/public/' . $empresa->organigrama_ruta);
            if (file_exists($filePath)) {
                $organigramaSize = round(filesize($filePath) / 1048576, 2);
            }
        }

        // Variable $nosotros para el título (puede ser la empresa misma)
        $nosotros = $empresa;

        return view('web.page.nosotros.index', [
            'empresa' => $empresa,
            'logo' => $logo,
            'nosotros' => $nosotros,
            'organigramaSize' => $organigramaSize,
        ]);
    }
}
