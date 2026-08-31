<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Orders;
use App\Models\Orders_DetailStates;
use App\Models\Orders_States;
use Inertia\Inertia;

class OrderControllerVue extends Controller
{
    public function index(Request $request)
    {
        // Valida i parametri di query (filtri)
        $request->validate([
            'date' => 'nullable|date',
            'name' => 'nullable|string|max:255',
            'surname' => 'nullable|string|max:255',
            'codfisc' => 'nullable|string|max:16',
        ]);

        // Recupera i filtri dalla richiesta
        $filters = $request->only(['date', 'name', 'surname', 'codfisc']);
        $error = '';
        $query = Orders::query();

        // Applica i filtri solo se almeno uno è stato fornito
        if (!empty(array_filter($filters))) {
            if ($request->date) {
                $query->whereDate('TimestampINS', $request->date);
            }
            if ($request->name) {
                $query->where('Name', 'LIKE', '%' . $request->name . '%');
            }
            if ($request->surname) {
                $query->where('Surname', 'LIKE', '%' . $request->surname . '%');
            }
            if ($request->codfisc) {
                $query->where('CodFisc', 'LIKE', '%' . $request->codfisc . '%');
            }
            $orders = $query->get();
        } else {
            // Se nessun filtro è stato applicato (e non ci sono parametri di query),
            // puoi decidere se mostrare un set iniziale di ordini o un array vuoto.
            // Attualmente restituisce un array vuoto.
            $orders = [];
            // Se la richiesta contiene parametri di filtro ma sono tutti vuoti, mostra un errore
            if ($request->hasAny(['date', 'name', 'surname', 'codfisc'])) {
                $error = 'Inserisci almeno un filtro.';
            }
        }

        // Renderizza la pagina Inertia con gli ordini filtrati e i filtri correnti
        return Inertia::render('Orders', [
            'orders' => $orders,
            'filters' => $filters, // Passa i filtri attuali alla vista Vue
            'error' => $error,
        ]);
    }


    // Visualizzazione singolo ordine
    public function show($orderId)
    {
        // Recupera l'ordine con tutte le relazioni necessarie
        $order = Orders::with('orders_details.ordersPs', 'orderState')->find($orderId);

        if (!$order) {
            abort(404, 'Ordine non trovato.');
        }

        $states = Orders_DetailStates::where('Disabled', false)
            ->orderBy('State')
            ->get();

        $orderStates = Orders_States::where('Disabled', false)
            ->orderBy('Description')
            ->get();

        return Inertia::render('Orders/Show', [
            'order' => $order,
            'states' => $states,
            'orderStates' => $orderStates,
        ]);
    }
}
