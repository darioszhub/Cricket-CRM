<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Orders_Details;
use App\Models\Orders_DetailStates;

class OrderDetailsController extends Controller
{

    public function updateState(Request $request, $detailId)
    {
        $request->validate([
            'state' => 'required|string|exists:Orders_DetailStates,State',
        ]);

        // Recupera il dettaglio
        $orderDetail = Orders_Details::find($detailId);

        if (!$orderDetail) {
            abort(404, 'Dettaglio non trovato.');
        }

        // Aggiorna lo stato
        $orderDetail->State1 = $request->input('state');
        $orderDetail->save();

        // Reindirizza alla pagina dell'ordine con un messaggio di successo
        return redirect()->route('orders.show', $orderDetail->CodOrder)
            ->with('success', 'Stato del dettaglio aggiornato con successo.');
    }
}
