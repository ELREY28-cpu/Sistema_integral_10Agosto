<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Role;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    //Home trabajara solo con las rutas get genenerales del sistema

    // la ruta index muestra el incio de la pagina
    public function Index() 
    {
        try {
            // Cambiamos para mostrar estadísticas del sistema de asistencia
            $estudiantes = \App\Models\Estudiante::count();
            $cursos = \App\Models\Curso::count();
            $asistencias = \App\Models\Asistencia::count();
            $usuarios = \App\Models\Usuario::count();
            
            $datos = [
                'estudiantes' => $estudiantes,
                'cursos' => $cursos,
                'asistencias' => $asistencias,
                'usuarios' => $usuarios
            ];
            
            return view('extens.home', compact('datos'));
           
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error al cargar'.$e);
        }

    }
    public function buscar(Request $request)
    {
        try{
            // Búsqueda de estudiantes para el sistema de asistencia
            $query = \App\Models\Estudiante::query();

            // Aplica los filtros de búsqueda si se proporcionan
            if ($request->has('filtro_nombre')) {
                $filtro_nombre = $request->input('filtro_nombre');
                $query->where('nombres', 'like', "%$filtro_nombre%")
                      ->orWhere('apellidos', 'like', "%$filtro_nombre%");
            }
            
            if ($request->has('filtro_cedula')) {
                $filtro_cedula = $request->input('filtro_cedula');
                $query->where('cedula', 'like', "%$filtro_cedula%");
            }

            $datos = $query->latest()->paginate(10);

            return view('extens.home', compact('datos'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error al Cargar');
        }
    }
    // la ruta registros muetra el formulario para registrar usuarios
    public function Register(){
       
        return view('extens.register');
     }
    // muestra la pagina de inicio de sesion
    public function Login()
    {
        if (!session()->has('user')) {
            return view('extens.login');
        } else {
            return redirect('home');
        }
    }
// muestra el panel de control
    public function Dashboard()
    {
        if (session()->has('user')) {
            $user = session('user');
          
            //  return view('prueba', compact('user'));
           

               
              
                return view('extens.dashboard');
            
        } else {
            return redirect('login');
        }
    }

    // funcionpara salir del login inciado
    public function Salir()
    {
        session()->forget('user');
        session()->flush();
        return redirect('/');
    }
// funcion para mostrat el modulo de usuarios

// llamar al frmularios de usuarios para crear




}

