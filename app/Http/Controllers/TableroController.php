<?php

namespace App\Http\Controllers;

use App\Models\Partida;
use App\Models\Tablero;
use Illuminate\Http\Request;

class TableroController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tablero  $tablero
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $tablero = Tablero::find($id);
        return response()->json([
            'tablero' => $tablero
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tablero  $tablero
     * @return \Illuminate\Http\Response
     */
    public function edit(Tablero $tablero)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tablero  $tablero
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, $nameCampo, $clear = '0')
    {
        // Si el tablero no se va a limpiar, se ejecutara las acciones del juego
        if ($clear === '0') {
            $tableroJugado = Tablero::where('id', $id)->update([$nameCampo => $request->valueCampo]);
            $tableroActualizado = Tablero::find($id);
            $partidaActual = Partida::find($tableroActualizado->partida_id);
            // Valida si existe o no un jugador:
            if ($this->validateGanador($tableroActualizado)) {
                $tableroFinalizado = Tablero::where('id', $tableroActualizado->id)->update(['campo_1' => 'F', 'campo_2' => 'F', 'campo_3' => 'F', 'campo_4' => 'F', 'campo_5' => 'F', 'campo_6' => 'F', 'campo_7' => 'F', 'campo_8' => 'F', 'campo_9' => 'F']);
                $partidaActual->ganador = $partidaActual->turno === 1 ? $partidaActual->jugador_1 : $partidaActual->jugador_2;
                $partidaActual->save();
                $tableroActualizado = Tablero::find($id);
            } else {
                // Si no hay ganador se actualiza el turno:
                $partidaActual->turno = $partidaActual->turno === 1 ? 2 : 1;
                $partidaActual->save();
                $tableroActualizado = Tablero::find($id);
            }
            return response()->json([
                'tablero' => $tableroActualizado
            ]);
        } else {
            // Se reinician todos los valores del tablero:
            $tableroActualizado = Tablero::find($id);
            $tableroJugado = $this->clearTablero($tableroActualizado->partida_id, $id);
            return response()->json([
                'tablero' => $tableroJugado
            ]);
        }
    }

    // Existe ganador:
    public function validateGanador($tableroActualizado) {
        if ($this->existeGanadorInRow($tableroActualizado)) return true;
        if ($this->existeGanadorInCol($tableroActualizado)) return true;
        if ($this->existeGanadorInDia($tableroActualizado)) return true;
        return false;
    }

    // Reinicio de la partida y de todo el tablero:
    public function clearTablero($idPartida, $idTablero) {
        $tableroLimpio = Tablero::where('id', $idTablero)->update(['campo_1' => '-', 'campo_2' => '-', 'campo_3' => '-', 'campo_4' => '-', 'campo_5' => '-', 'campo_6' => '-', 'campo_7' => '-', 'campo_8' => '-', 'campo_9' => '-']);
        $partidaLimpia = Partida::where('id', $idPartida)->update(['ganador' => null, 'turno' => 1]);
        $tableroActualizado = Tablero::find($idTablero);
        return $tableroActualizado;
    }

    // Detectar ganador en las filas:
    public function existeGanadorInRow($tablero) {
        if ($tablero->campo_1 !== '-' && $tablero->campo_2 !== '-' && $tablero->campo_3 !== '-') {
            if ($tablero->campo_1 === $tablero->campo_2  && $tablero->campo_1 === $tablero->campo_3) {
                return true;
            }
        }

        if ($tablero->campo_4 !== '-' && $tablero->campo_5 !== '-' && $tablero->campo_6 !== '-') {
            if ($tablero->campo_4 === $tablero->campo_5  && $tablero->campo_4 === $tablero->campo_6) {
                return true;
            }
        }

        if ($tablero->campo_7 !== '-' && $tablero->campo_8 !== '-' && $tablero->campo_9 !== '-') {
            if ($tablero->campo_7 === $tablero->campo_8  && $tablero->campo_7 === $tablero->campo_9) {
                return true;
            }
        }
    }

    // Detectar ganador en las columnas:
    public function existeGanadorInCol($tablero) {
        if ($tablero->campo_1 !== '-' && $tablero->campo_4 !== '-' && $tablero->campo_7 !== '-') {
            if ($tablero->campo_1 === $tablero->campo_4  && $tablero->campo_1 === $tablero->campo_7) {
                return true;
            }
        }

        if ($tablero->campo_2 !== '-' && $tablero->campo_5 !== '-' && $tablero->campo_8 !== '-') {
            if ($tablero->campo_2 === $tablero->campo_5  && $tablero->campo_2 === $tablero->campo_8) {
                return true;
            }
        }

        if ($tablero->campo_3 !== '-' && $tablero->campo_6 !== '-' && $tablero->campo_9 !== '-') {
            if ($tablero->campo_3 === $tablero->campo_6  && $tablero->campo_3 === $tablero->campo_9) {
                return true;
            }
        }
    }

    // Detectar ganador en las diagonales:
    public function existeGanadorInDia($tablero) {
        if ($tablero->campo_1 !== '-' && $tablero->campo_5 !== '-' && $tablero->campo_9 !== '-') {
            if ($tablero->campo_1 === $tablero->campo_5  && $tablero->campo_1 === $tablero->campo_9) {
                return true;
            }
        }

        if ($tablero->campo_3 !== '-' && $tablero->campo_5 !== '-' && $tablero->campo_7 !== '-') {
            if ($tablero->campo_3 === $tablero->campo_5  && $tablero->campo_3 === $tablero->campo_7) {
                return true;
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tablero  $tablero
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tablero $tablero)
    {
        //
    }
}
