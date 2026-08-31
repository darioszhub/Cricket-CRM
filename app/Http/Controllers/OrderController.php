<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Orders;
use App\Models\Orders_DetailStates;
use App\Models\Orders_States;

class OrderController extends Controller
{
    // Mostra il form
    public function showFilterForm()
    {
        return view('orders');
    }

    public function filterOrders(Request $request)
    {
        // Valida i dati ricevuti
        $request->validate([
            'date' => 'nullable|date',
            'name' => 'nullable|string|max:255',
            'surname' => 'nullable|string|max:255',
            'codfisc' => 'nullable|string|max:16',
        ]);

        // Controlla che almeno un filtro sia presente
        if (empty(array_filter($request->only(['date', 'name', 'surname', 'codfisc'])))) {
            return redirect()->back()->with('error', 'Inserisci almeno un filtro.');
        }

        // Recupera i filtri dal form
        $date = $request->input('date');
        $name = $request->input('name');
        $surname = $request->input('surname');
        $codfisc = $request->input('codfisc');

        // Inizia la query base
        $query = Orders::query();

        // Aggiungi i filtri dinamici
        if ($date) {
            $query->whereDate('TimestampINS', $date);
        }
        if ($name) {
            $query->where('Name', 'LIKE', "%{$name}%");
        }
        if ($surname) {
            $query->where('Surname', 'LIKE', "%{$surname}%");
        }
        if ($codfisc) {
            $query->where('CodFisc', 'LIKE', "%{$codfisc}%");
        }

        // Esegui la query
        $orders = $query->get(); //senza paginazione

        // Restituisce i risultati alla vista
        return view('orders.results', compact('orders', 'date', 'name', 'surname', 'codfisc'));
    }

    public function show($orderId)
    {
        // Recupera l'ordine con tutte le relazioni necessarie
        $order = Orders::with('orders_details.ordersPs', 'orderState')->find($orderId);

        // Se l'ordine non esiste, mostra un errore
        if (!$order) {
            abort(404, 'Ordine non trovato.');
        }

        // Recupera gli stati Dettaglio (Orders_DetailStates)
        $states = Orders_DetailStates::where('Disabled', false)
            ->orderBy('State')
            ->get();

        // Recupera gli stati Ordine (Orders_States)
        $orderStates = Orders_States::where('Disabled', false)
            ->orderBy('Description')
            ->get();

        // Passa i dati alla vista
        return view('orders.show', compact('order', 'states', 'orderStates'));
    }
}
