<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\http\Controllers\AuthManager;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderDetailsController;
use App\Http\Controllers\usersinertia;
use App\Http\Controllers\OrderControllerVue;
use App\http\Controllers\userview;
use App\Models\Agents;
use App\Models\Agents_Assignments;
use App\Models\Agents_Calendar;
use App\Models\Calls;
use App\Models\Calls_States;
use App\Models\Calls_WhyNot;
use App\Models\Customers;
use App\Models\Groups;
use App\Models\Groups_Headers;
use App\Models\Groups_OStates;
use App\Models\Groups_Parameters;
use App\Models\Groups_Users;
use App\Models\Lists;
use App\Models\Lists_Headers;
use App\Models\Lists_RecallTypes;
use App\Models\Lists_States;
use App\Models\Orders;
use App\Models\Orders_Details;
use App\Models\Orders_DetailStates;
use App\Models\Orders_PS;
use App\Models\Orders_States;
use App\Models\Parameters;
use App\Models\Portfolios;
use App\Models\PredictiveEvents;
use App\Models\PredictiveEventsBase;
use App\Models\PredictiveTODO;
use App\Models\Users;
use App\Models\Zones_Criteria;
use App\Models\Zones_Groups;
use App\Models\Zones_Headers;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');


// Rotta per pagina user con 10 righe di esempio
Route::get('/users', function () {
    $users = Users::take(10)->get(); // Prendi i primi 10 utenti
    return view('users', ['users' => $users]);
    dd($users->toArray());
});

// Rotta per pagina agents con 10 righe di esempio
Route::get('/agents', function () {
    $agents = Agents::take(10)->get(); // Prendi i primi 10 utenti
    return view('agents', ['agents' => $agents]);
    dd($agents->toArray());
});

// Rotta per pagina orders con 10 righe di esempio
Route::get('/orders', function () {
    $orders = Orders::take(10)->get(); // Recupera solo 10 ordini
    return view('orders.index', ['orders' => $orders]); // Passa i dati alla vista 
    dd($orders->toArray()); // Debug dei dati (commentare o rimuovere dopo il debug)
});

// Rotta per pagina dettagli con 10 righe di esempio
Route::get('/orders-details', function () {
    $ordersDetails = Orders_Details::take(10)->get(); // Recupera solo 10 righe della tabella Orders_Details
    return view('orders-details', ['ordersDetails' => $ordersDetails]); // Passa i dati alla vista
    dd($ordersDetails->toArray()); // Debug dei dati (commentare o rimuovere dopo il debug)
});

// Rotta per pagina prodotti con 10 righe di esempio
Route::get('/orders-ps', function () {
    $ordersPS = Orders_PS::take(10)->get(); // Recupera i primi 10 record dalla tabella Orders_PS
    return view('orders-ps', ['ordersPS' => $ordersPS]); // Restituisce una vista (se esiste) oppure un JSON per debug
});

// Rotta per pagina stati dettaglio con 10 righe di esempio
Route::get('/orders-detailstates', function () {
    $detailStates = Orders_DetailStates::take(10)->get(); // Recupera i primi 10 record
    return view('orders-detailstates', ['detailStates' => $detailStates]); // Passa i dati alla vista
});

// Rotta per pagina stati ordine con 10 righe di esempio
Route::get('/orders-states', function () {
    $ordersStates = Orders_States::where('Disabled', false)
        ->orderBy('IDOrderState', 'asc')
        ->take(10) // Mostra i primi 10 record
        ->get();

    return view('orders-states', ['ordersStates' => $ordersStates]);
})->name('orders.states.index');

// Rotta per pagina Groups con 40 righe di esempio
Route::get('/groups', function () {
    $groups = Groups::orderBy('IDUsersGroup', 'asc')->take(40)->get();
    return view('groups', compact('groups'));
})->name('groups.index');

// Rotta per pagina Groups_Headers con 40 righe di esempio
Route::get('/groups-headers', function () {
    $groupsHeaders = Groups_Headers::orderBy('CodHeader', 'asc')->take(40)->get();
    return view('groups_headers', compact('groupsHeaders'));
})->name('groups.headers.index');

// Rotta per pagina Parameters
Route::get('/parameters', function () {
    $parameters = Parameters::all(); // Recupera tutti i parametri
    return view('parameters', ['parameters' => $parameters]);
})->name('parameters.index');

// Rotta per pagina Groups_Parameters con 40 righe di esempio
Route::get('/groups-parameters', function () {
    $groupsParameters = Groups_Parameters::orderBy('CodUsersGroup', 'asc')->take(40)->get();
    return view('groups_parameters', ['groupsParameters' => $groupsParameters]);
})->name('groups.parameters.index');

// Rotta per pagina Groups_OStates con 40 righe di esempio
Route::get('/groups-ostates', function () {
    $groupsOStates = Groups_OStates::orderBy('CodUsersGroup', 'asc')->take(40)->get();
    return view('groups-ostates', ['groupsOStates' => $groupsOStates]);
})->name('groups.ostates.index');

// Rotta per pagina Groups_Users con 40 righe di esempio
Route::get('/groups-users', function () {
    $groupsUsers = Groups_Users::take(40)->get();
    return view('groups_users', compact('groupsUsers'));
})->name('groups.users.index');

// Rotta per pagina Portfolios con 40 righe di esempio
Route::get('/portfolios', function () {
    $portfolios = Portfolios::with('parentPortfolio', 'childPortfolios')->orderBy('Portfolio', 'asc')->take(10)->get();
    return view('portfolios', compact('portfolios'));
})->name('portfolios.index');

// Rotta per pagina PredictiveEvents con 10 righe di esempio
Route::get('/predictive-events', function () {
    $events = PredictiveEvents::orderBy('IDEvent', 'asc')->take(10)->get();
    return view('predictive-events', compact('events'));
})->name('predictive-events.index');

// Rotta per pagina PredictiveEventsBase con 10 righe di esempio
Route::get('/predictive-events-base', function () {
    $events = PredictiveEventsBase::orderBy('ServerEvent', 'asc')->take(10)->get();
    return view('predictive-events-base', compact('events'));
})->name('predictive-events-base.index');

// Rotta per pagina PredictiveTODO con 10 righe di esempio
Route::get('/predictive-todo', function () {
    $predictiveTodo = PredictiveTODO::with(['event', 'chain'])->orderBy('IDTODO', 'asc')->take(10)->get();
    return view('predictive-todo', compact('predictiveTodo'));
})->name('predictive.todo.index');

// Rotta per pagina Zones_Criteria con 10 righe di esempio
Route::get('/zones-criteria', function () {
    $zonesCriteria = Zones_Criteria::orderBy('IDCriteria', 'asc')->take(10)->get();
    return view('zones_criteria', compact('zonesCriteria'));
})->name('zones_criteria.index');

// Rotta per pagina Zones_Groups
Route::get('/zones-groups', function () {
    $zonesGroups = Zones_Groups::orderBy('IDZoneGroup', 'asc')->get();
    return view('zones_groups', compact('zonesGroups'));
})->name('zones_groups.index');

// Rotta per pagina Zones_Headers
Route::get('/zones-headers', function () {
    $zonesHeaders = Zones_Headers::orderBy('IDZone', 'asc')->get();
    return view('zones_headers', compact('zonesHeaders'));
})->name('zones_headers.index');

// Rotta per pagina Lists con 10 righe di esempio
Route::get('/lists', function () {
    $lists = Lists::orderBy('IDList', 'asc')->take(10)->get();
    return view('lists', compact('lists'));
})->name('lists.index');

// Rotta per pagina Lists_RecallTypes con 10 righe di esempio
Route::get('/lists-recall-types', function () {
    $recallTypes = Lists_RecallTypes::orderBy('IDRecallType', 'asc')->take(10)->get();
    return view('lists_recall_types', compact('recallTypes'));
})->name('lists_recall_types.index');

// Rotta per pagina Lists_States con 10 righe di esempio
Route::get('/lists-states', function () {
    $listsStates = Lists_States::orderBy('IDListState', 'asc')->take(10)->get();
    return view('lists_states', compact('listsStates'));
})->name('lists_states.index');

// Rotta per pagina Lists_Headers con 10 righe di esempio
Route::get('/lists-headers', function () {
    $headers = Lists_Headers::with(['parent', 'portfolio'])->orderBy('Header', 'asc')->take(10)->get();
    return view('lists_headers', compact('headers'));
})->name('lists_headers.index');

// Rotta per pagina Agents_Assignments con 10 righe di esempio
Route::get('/agents-assignments', function () {
    $assignments = Agents_Assignments::with(['agent', 'zone', 'portfolio'])->orderBy('CodZone', 'asc')->take(10)->get();
    return view('agents_assignments', compact('assignments'));
})->name('agents_assignments.index');

// Rotta per pagina Agents_Calendar con 10 righe di esempio
Route::get('/agents-calendar', function () {
    $calendar = Agents_Calendar::with('agent')->orderBy('DayOfTheWeek')->take(10)->get();
    return view('agents_calendar', compact('calendar'));
})->name('agents_calendar.index');

// Rotta per pagina Calls con 10 righe di esempio
Route::get('/calls', function () {
    $calls = Calls::with(['agent', 'list', 'order'])->orderBy('CallStart', 'desc')->take(10)->get();
    return view('calls', compact('calls'));
})->name('calls.index');

// Rotta per pagina Calls_States con 10 righe di esempio
Route::get('/calls-states', function () {
    $callsStates = Calls_States::with(['orderState', 'listState', 'recallType', 'header'])->orderBy('IDCallState', 'asc')->take(10)->get();
    return view('calls_states', compact('callsStates'));
})->name('calls_states.index');

// Rotta per pagina Calls_WhyNot con 10 righe di esempio
Route::get('/calls-whynot', function () {
    $callsWhyNot = Calls_WhyNot::with('header')->orderBy('WhyNot')->take(10)->get();
    return view('calls_whynot', compact('callsWhyNot'));
})->name('calls_whynot.index');

// Rotta per pagina Customers con 10 righe di esempio
Route::get('/customers', function () {
    $customers = Customers::with(['portfolio', 'previousPortfolio', 'zone'])->orderBy('IDCustomer', 'asc')->take(10)->get();
    return view('customers', compact('customers'));
})->name('customers.index');

//PER DOPO UPDATE DETAIL CON DETAIL STATES
Route::patch('/order-details/{detailId}/update-state', [OrderDetailsController::class, 'updateState'])->name('order-details.update-state');


/* // Mostra il form di login
// Route::get('/login', [AuthManager::class, 'showLogin'])->name('login');

// // Gestisci il form di login
// Route::post('/login', [AuthManager::class, 'login']);

// Logout
Route::get('/logout', [AuthManager::class, 'logout'])->name('logout');
 */
// Dashboard protetta (esempio)
Route::get('/dashboard', function () {
    if (!session('user')) {
        return redirect('/login')->with('error', 'Devi essere loggato per accedere.');
    }

    return view('dashboard', ['user' => session('user')]);
})->name('dashboard');

// ricerca ordini
//Route::get('/orders', [OrderController::class, 'showFilterForm'])->name('orders');
Route::post('/orders/results', [OrderController::class, 'filterOrders'])->name('orders.submit');
Route::get('/orders/order{order}', [OrderController::class, 'show'])->name('orders.show');

Route::put('/orders/{order}/update-state', [OrderController::class, 'updateState'])->name('orders.updateState');


//-----------------------------VUE-----------------------------//

//esempio di raccoulta utenti con Vue
Route::get('/users', [usersinertia::class, 'index'])->name('users.index');

// Esempio paina Vue
Route::get('/welcome', function () {
    return Inertia::render('Welcome');
})->name('welcome');


use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;


Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Pagine di esempio
    Route::get('/orders', [OrderControllerVue::class, 'index'])->name('orders');
    Route::post('/orders/filter', [OrderControllerVue::class, 'filter'])->name('orders.filter');
    Route::get('/pagina2', fn () => Inertia::render('Pagina2'))->name('pagina2');
});


// Route::get('/orders-vue', [OrderControllerVue::class, 'index'])->name('orders.vue.index');
// Route::post('/orders-vue/filter', [OrderControllerVue::class, 'filter'])->name('orders.vue.filter');


require __DIR__.'/auth.php'; 