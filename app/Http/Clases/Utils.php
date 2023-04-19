<?php

namespace App\Http\Clases;

use Illuminate\Support\Facades\Request;


/*
 *
 * Clase auxiliar que agrupa cualquier funcion comun de la API
 *
 */

class Utils
{

    /* Transforma VALRULES en Variables */
    // Controla nulo
    // En String: Revisa casos especiales (nombre, apellido, etc: a uppercase. email, usuario: a lowercase)
    // En Json: hace el json_decode
    // En Numeric: revisa si es int o float y los castea
    // En Integer: castea a int
    public static function transformaValRules($rules, $request)
    {
        $variables = [];

        // Iterar todas las reglas
        foreach ($rules as $key => $regla) {
            $variables[$key] = null;

            if ($key == "nroPagina") {
                // Si es nroPagina, al no traer el param tiene que defaultear a 1
                // sino a null
                $variables[$key] = $request->has($key) ? intval($request->input($key)) : 1;
            } else if (!is_array($regla) && strpos($regla, 'mimes:') !== false) {
                // verificar si es archivo
                $variables[$key] = $request->hasFile($key) ? $request->file($key) : null;
            } else {
                $variables[$key] = $request->has($key) ? $request->input($key) : null;
            }

            // Procesamiento posterior para convertir los valores
            if (!is_array($regla) && $variables[$key] !== null) {
                if (strpos($regla, 'string') !== false) {
                    if (in_array($key, array("username", "usuario", "email"))) {
                        $variables[$key] = strtolower(trim($variables[$key]));
                    }
                    if (in_array($key, array("apellido", "nombre"))) {
                        $variables[$key] = trim($variables[$key]);
                    }
                }
                if (strpos($regla, 'json') !== false) {
                    $variables[$key] = json_decode($variables[$key], true);
                }
                if (strpos($regla, 'numeric') !== false) {
                    if ($variables[$key] === "") {
                        $variables[$key] = null;
                    } else {
                        if ((int) $variables[$key] == $variables[$key]) {
                            $variables[$key] = intval($variables[$key]);
                        } else {
                            $variables[$key] = floatval($variables[$key]);
                        }
                    }
                }
                if (strpos($regla, 'integer') !== false) {
                    if ($variables[$key] === "") {
                        $variables[$key] = null;
                    } else {
                        $variables[$key] = intval($variables[$key]);
                    }
                }
                if (strpos($regla, 'boolean') !== false) {
                    if ($variables[$key] === "") {
                        $variables[$key] = null;
                    } else {
                        if ($variables[$key] === '1' || $variables[$key] === '0'){
                            $variables[$key] = intval($variables[$key]);
                        }else{
                            $variables[$key] = boolval($variables[$key]);
                        }
                    }
                }
            }

            // En caso de ser string, verificar de que no sea "null" o "undefined"
            // para cuando se manda con FormData
            if ($variables[$key] === "null" || $variables[$key] === "undefined") {
                $variables[$key] = null;
            }

            // Cuando la regla es "sometimes" el validator no valida
            // los vacios. Devolver nulo si el valor es vacio.
            if ($variables[$key] === "" && strpos($regla, 'sometimes') !== false) {
                $variables[$key] = null;
            }
        }

        return $variables;
    }
}