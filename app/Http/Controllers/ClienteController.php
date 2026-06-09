<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Persona;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ClienteController extends Controller
{
    public function index(): View
    {
        $clientes = Cliente::with('persona')
                          ->orderBy('id_cliente', 'desc')
                          ->paginate(10);
        
        return view('clientes.index', compact('clientes'));
    }

    public function create(): View
    {
        return view('clientes.create');
    }

    public function store(Request $request): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'nombre'    => 'required|string|max:100',
                'apellido'  => 'required|string|max:100',
                'email'     => 'required|email|unique:persona,email',
                'telefono'  => 'nullable|string|max:20',
                'direccion' => 'nullable|string|max:255',
                'empresa'   => 'nullable|string|max:255',
            ], [
                'nombre.required'   => 'El nombre es obligatorio.',
                'apellido.required' => 'El apellido es obligatorio.',
                'email.required'    => 'El email es obligatorio.',
                'email.email'       => 'El email debe tener un formato válido.',
                'email.unique'      => 'Este email ya está registrado.',
            ]);

            DB::transaction(function () use ($validated) {
                // 1. Crear Persona
                $persona = Persona::create([
                    'nombre'    => $validated['nombre'],
                    'apellido'  => $validated['apellido'],
                    'email'     => $validated['email'],
                    'telefono'  => $validated['telefono'] ?? null,
                    'direccion' => $validated['direccion'] ?? null,
                ]);

                // 2. Crear Cliente
                Cliente::create([
                    'id_persona'     => $persona->id_persona,
                    'empresa'        => $validated['empresa'] ?? null,
                    'fecha_registro' => Carbon::now(),
                ]);
            });

            return redirect()
                ->route('clientes.index')
                ->with('success', 'Cliente creado exitosamente.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->withInput();

        } catch (\Exception $e) {
            Log::error('Error al crear cliente: ' . $e->getMessage());
            
            return redirect()
                ->back()
                ->with('error', 'Error al crear el cliente: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Cliente $cliente): View
    {
        $cliente->load('persona', 'proyectos');
        return view('clientes.show', compact('cliente'));
    }

    public function edit(Cliente $cliente): View
    {
        $cliente->load('persona');
        return view('clientes.edit', compact('cliente'));
    }

    public function update(Request $request, Cliente $cliente): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'nombre'    => 'required|string|max:100',
                'apellido'  => 'required|string|max:100',
                'email'     => 'required|email|unique:persona,email,' . $cliente->id_persona . ',id_persona',
                'telefono'  => 'nullable|string|max:20',
                'direccion' => 'nullable|string|max:255',
                'empresa'   => 'nullable|string|max:255',
            ], [
                'nombre.required'   => 'El nombre es obligatorio.',
                'apellido.required' => 'El apellido es obligatorio.',
                'email.required'    => 'El email es obligatorio.',
                'email.email'       => 'El email debe tener un formato válido.',
                'email.unique'      => 'Este email ya está registrado.',
            ]);

            DB::transaction(function () use ($validated, $cliente) {
                $cliente->persona->update([
                    'nombre'    => $validated['nombre'],
                    'apellido'  => $validated['apellido'],
                    'email'     => $validated['email'],
                    'telefono'  => $validated['telefono'] ?? null,
                    'direccion' => $validated['direccion'] ?? null,
                ]);

                $cliente->update([
                    'empresa' => $validated['empresa'] ?? null,
                ]);
            });

            return redirect()
                ->route('clientes.index')
                ->with('success', 'Cliente actualizado exitosamente.');

        } catch (\Exception $e) {
            Log::error('Error al actualizar cliente: ' . $e->getMessage());
            
            return redirect()
                ->back()
                ->with('error', 'Error al actualizar: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Cliente $cliente): RedirectResponse
    {
        try {
            if ($cliente->tieneProyectosActivos()) {
                return redirect()
                    ->route('clientes.index')
                    ->with('error', 'No se puede eliminar el cliente porque tiene proyectos activos.');
            }

            DB::transaction(function () use ($cliente) {
                $personaId = $cliente->id_persona;
                $cliente->delete();
                Persona::find($personaId)?->delete();
            });

            return redirect()
                ->route('clientes.index')
                ->with('success', 'Cliente eliminado exitosamente.');

        } catch (\Exception $e) {
            Log::error('Error al eliminar cliente: ' . $e->getMessage());
            
            return redirect()
                ->back()
                ->with('error', 'Error al eliminar: ' . $e->getMessage());
        }
    }
}