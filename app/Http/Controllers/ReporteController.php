<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Proyecto;
use App\Models\Reporte;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReporteController extends Controller
{
    private function crearCarpetaPdfs(): void
    {
        if (!file_exists(public_path('pdfs'))) {
            mkdir(public_path('pdfs'), 0755, true);
        }
    }

    /*
    |-----------------------------------------------------------
    | Genera el PDF con configuración correcta de fuente y UTF-8
    |-----------------------------------------------------------
    */
    private function generarPdf(string $vista, array $datos, string $orientacion = 'portrait')
    {
        $html = view($vista, $datos)->render();

        return Pdf::loadHTML($html, 'UTF-8')
                  ->setOptions([
                      'defaultFont'          => 'DejaVu Sans',
                      'isHtml5ParserEnabled' => true,
                      'isRemoteEnabled'      => false,
                  ])
                  ->setPaper('a4', $orientacion);
    }

    public function index()
    {
        $historial = Reporte::with('usuario')
                            ->where('id_usuario', Auth::id())
                            ->orderBy('fecha_generacion', 'desc')
                            ->get();

        $proyectos = Proyecto::orderBy('nombre')->get();

        return view('reportes.index', compact('historial', 'proyectos'));
    }

    public function clientes()
    {
        $clientes = Cliente::with('persona', 'proyectos')->get();

        $pdf    = $this->generarPdf('reportes.pdf_clientes', compact('clientes'), 'portrait');
        $nombre = Reporte::generarNombre('clientes');
        $ruta   = 'pdfs/' . $nombre;

        $this->crearCarpetaPdfs();
        $pdf->save(public_path($ruta));

        Reporte::create([
            'id_usuario'       => Auth::id(),
            'id_proyecto'      => null,
            'id_cliente'       => null,
            'tipo'             => 'clientes',
            'fecha_generacion' => now(),
            'nombre_archivo'   => $nombre,
            'ruta_archivo'     => $ruta,
        ]);

        return $pdf->stream($nombre);
    }

    public function proyectos()
    {
        $proyectos = Proyecto::with(['cliente.persona', 'tareas'])->get()
                             ->map(function ($p) {
                                 $p->avance = $p->calcularAvance();
                                 return $p;
                             });

        $pdf    = $this->generarPdf('reportes.pdf_proyectos', compact('proyectos'), 'landscape');
        $nombre = Reporte::generarNombre('proyectos');
        $ruta   = 'pdfs/' . $nombre;

        $this->crearCarpetaPdfs();
        $pdf->save(public_path($ruta));

        Reporte::create([
            'id_usuario'       => Auth::id(),
            'id_proyecto'      => null,
            'id_cliente'       => null,
            'tipo'             => 'proyectos',
            'fecha_generacion' => now(),
            'nombre_archivo'   => $nombre,
            'ruta_archivo'     => $ruta,
        ]);

        return $pdf->stream($nombre);
    }

    public function tareas(Request $request)
    {
        $request->validate([
            'id_proyecto' => ['required', 'exists:proyecto,id_proyecto'],
        ]);

        $proyecto = Proyecto::with(['cliente.persona', 'tareas.asignados'])
                            ->findOrFail($request->id_proyecto);

        $pdf    = $this->generarPdf('reportes.pdf_tareas', compact('proyecto'), 'portrait');
        $nombre = Reporte::generarNombre('tareas');
        $ruta   = 'pdfs/' . $nombre;

        $this->crearCarpetaPdfs();
        $pdf->save(public_path($ruta));

        Reporte::create([
            'id_usuario'       => Auth::id(),
            'id_proyecto'      => $proyecto->id_proyecto,
            'id_cliente'       => $proyecto->id_cliente,
            'tipo'             => 'tareas',
            'fecha_generacion' => now(),
            'nombre_archivo'   => $nombre,
            'ruta_archivo'     => $ruta,
        ]);

        return $pdf->stream($nombre);
    }

    public function descargar(Reporte $reporte)
    {
        if (!$reporte->archivoExiste()) {
            return redirect()->route('reportes.index')
                             ->with('error', 'El archivo ya no existe en el servidor.');
        }

        return response()->download(
            public_path($reporte->ruta_archivo),
            $reporte->nombre_archivo
        );
    }
}