<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import { computed, ref, watch, onMounted } from 'vue';

const page = usePage();

// Dati degli ordini e errore globale (non legato a un singolo form di filtro)
const orders = computed(() => page.props.orders || []);
const globalError = ref(page.props.error || ''); // Per errori generali dal backend

// --- LOGICA PER LA GESTIONE DEI FILTRI MULTIPLI E PERSISTENZA ---

// Chiave per il Local Storage che conterrà l'array di tutti i form di filtro
const localStorageKey = 'orders_all_filter_forms';

// Variabile reattiva per controllare la visibilità della tabella dei risultati di ricerca
const showResults = ref(false);

// Nuova variabile reattiva per tenere traccia delle righe selezionate
// Useremo un Set per una gestione efficiente di aggiunta/rimozione e controllo di esistenza.
const selectedOrderIds = ref(new Set());

// Computed property per il contatore delle righe selezionate
const selectedRowCount = computed(() => selectedOrderIds.value.size);

// Variabili reattive per la gestione dell'ordinamento
const sortColumn = ref(null); // Colonna attualmente ordinata (es. 'Name', 'Surname', 'CodFisc', 'TimestampINS')
const sortDirection = ref('asc'); // Direzione dell'ordinamento ('asc' o 'desc')

// Computed property per gli ordini ordinati
const sortedOrders = computed(() => {
  if (!sortColumn.value) {
    return orders.value; // Se nessuna colonna è selezionata per l'ordinamento, restituisci gli ordini originali
  }

  // Crea una copia dell'array per evitare di mutare l'originale
  const sorted = [...orders.value].sort((a, b) => {
    let valA = a[sortColumn.value];
    let valB = b[sortColumn.value];

    // Gestione specifica per l'ordinamento delle date
    if (sortColumn.value === 'TimestampINS') {
      valA = new Date(valA);
      valB = new Date(valB);
    } else if (typeof valA === 'string' && typeof valB === 'string') {
      // Ordinamento di stringhe case-insensitive
      valA = valA.toLowerCase();
      valB = valB.toLowerCase();
    }

    // Logica di confronto
    if (valA < valB) {
      return sortDirection.value === 'asc' ? -1 : 1;
    }
    if (valA > valB) {
      return sortDirection.value === 'asc' ? 1 : -1;
    }
    return 0; // I valori sono uguali
  });

  return sorted;
});

// --- LOGICA PER IL MODAL DI VISUALIZZAZIONE E MODIFICA ORDINI ---
const showModal = ref(false); // Controlla la visibilità del modal
const currentOrderIndex = ref(0); // Indice dell'ordine attualmente visualizzato nel modal

// Computed property che restituisce un array degli oggetti ordine selezionati
const selectedOrdersArray = computed(() => {
  // Filtra gli ordini originali per includere solo quelli i cui ID sono nel Set selectedOrderIds
  // Per semplicità, qui usiamo orders.value, ma potresti usare sortedOrders.value se l'ordinamento è rilevante per la navigazione nel modal.
  return orders.value.filter(order => selectedOrderIds.value.has(order.IDOrder));
});

// Variabile reattiva per i dati dell'ordine visualizzati e MODIFICABILI nel modal
const editableOrderData = ref(null);

// Funzione per copiare i dati dell'ordine corrente in editableOrderData VECCHIA
/* const loadEditableOrderData = () => {
  if (selectedOrdersArray.value.length > 0) {
    // Crea una copia profonda per evitare di modificare l'oggetto originale direttamente
    editableOrderData.value = JSON.parse(JSON.stringify(selectedOrdersArray.value[currentOrderIndex.value]));
  } else {
    editableOrderData.value = null;
  }
};
 */


//Inizio logica formattazione data leggibile dagli input//
function useDateField(field) {
    return computed({
        get() {
            const value = editableOrderData.value?.[field];

            // DEBUG: Stampa il valore originale che arriva dal database
            console.log(`Valore originale per ${field}:`, value);

            // Se il valore non esiste, restituisci una stringa vuota.
            if (!value) {
                console.log(`Valore per l'input '${field}': '' (campo vuoto)`);
                return '';
            }

            // Usa 'T' come separatore per estrarre la parte della data
            const datePart = value.split('T')[0];

            // DEBUG: Stampa il valore formattato che verrà usato nell'input
            console.log(`Valore per l'input '${field}':`, datePart);

            // Restituisci la stringa della data formattata correttamente (yyyy-MM-dd)
            return datePart;
        },
        set(val) {
            // Quando l'utente modifica l'input, mantieni il formato datetime completo.
            // Se l'utente seleziona una data '2025-10-26', la trasformi in '2025-10-26 00:00:00'.
            editableOrderData.value[field] = val ? val + ' 00:00:00' : null;
        },
    });
}

// Per utilizzare la funzione, crea una computed property per ogni campo data
// che vuoi mostrare/modificare.
const dateContractModel = useDateField('Date_Contract');
const timestampINSModel = useDateField('TimestampINS'); // <-- Aggiungi questo!
//Fine logica formattazione data leggibile dagli input//

//Inizio logica formattazione date e time leggibile dagli input//
function useDateTimeField(field) {
  return computed({
    get() {
      const value = editableOrderData.value?.[field];

      // DEBUG: Stampa il valore originale che arriva dal backend
      console.log(`GET - Valore originale dal backend per ${field}:`, value);

      if (!value) {
        console.log(`GET - Valore per l'input '${field}': '' (campo vuoto)`);
        return '';
      }

      // Rimuove i millisecondi e la 'Z' finale per adattarsi al formato richiesto
      // dall'input datetime-local (yyyy-MM-ddThh:mm)
      const formattedValue = value.replace(/\.\d+Z$/, '');

      // DEBUG: Stampa il valore formattato che viene passato all'input
      console.log(`GET - Valore formattato per l'input '${field}':`, formattedValue);

      return formattedValue;
    },
    set(val) {
      // DEBUG: Stampa il valore che l'utente ha inserito nell'input
      console.log(`SET - Valore inserito dall'utente per ${field}:`, val);

      // Converte il valore nel formato completo con millisecondi e fuso orario 'Z'
      // per allinearlo ai dati che ricevi dal backend.
      if (val) {
        const newValue = val + ':00.000000Z';
        editableOrderData.value[field] = newValue;
        // DEBUG: Stampa il valore finale che viene salvato nella variabile reattiva
        console.log(`SET - Valore finale salvato per ${field}:`, newValue);
      } else {
        editableOrderData.value[field] = null;
        console.log(`SET - Valore finale salvato per ${field}: null`);
      }
    },
  });
}

const dateAppntModel = useDateTimeField('Date_Appnt');
//Fine logica formattazione date e time leggibile dagli input//

const loadEditableOrderData = () => {
  if (selectedOrdersArray.value.length > 0) {
    // Array di tutti i campi che devono essere inizializzati a una stringa vuota se non esistono.
    const fieldsToInitialize = [
      'Provincia',
      'Email',
      'Address',
      'Cell1',
      'AddressToponomy',
      'AddressNumber',
      'Tel1',
      'Email1',
      'City',
      'Prov',
      'NotesINT', // Il campo Note Interne
      'Date_Appnt', // <-- Aggiungi questo!
      'Date_Contract', // <-- E anche questo!
    ];

    // Crea una copia profonda per evitare di modificare l'oggetto originale direttamente
    const copiedData = JSON.parse(JSON.stringify(selectedOrdersArray.value[currentOrderIndex.value]));

    // Itera sull'array dei campi e inizializza quelli mancanti
    fieldsToInitialize.forEach(field => {
      if (!copiedData[field]) {
        copiedData[field] = '';
      }
    });

    editableOrderData.value = copiedData;
  } else {
    editableOrderData.value = null;
  }
};

// Funzione per aprire il modal
const openModal = () => {
  if (selectedOrdersArray.value.length > 0) {
    currentOrderIndex.value = 0; // Inizia sempre dal primo ordine selezionato
    loadEditableOrderData(); // Carica i dati del primo ordine nel form
    showModal.value = true;
  }
};

// Funzione per chiudere il modal
const closeModal = () => {
  showModal.value = false;
  editableOrderData.value = null; // Pulisci i dati del form quando il modal è chiuso
};

// Funzione per navigare all'ordine successivo nel modal
const nextOrder = () => {
  if (selectedOrdersArray.value.length > 0) {
    currentOrderIndex.value = (currentOrderIndex.value + 1) % selectedOrdersArray.value.length;
    loadEditableOrderData(); // Carica i dati del nuovo ordine nel form
  }
};

// Funzione per navigare all'ordine precedente nel modal
const prevOrder = () => {
  if (selectedOrdersArray.value.length > 0) {
    currentOrderIndex.value = (currentOrderIndex.value - 1 + selectedOrdersArray.value.length) % selectedOrdersArray.value.length;
    loadEditableOrderData(); // Carica i dati del nuovo ordine nel form
  }
};

// Funzione (placeholder) per salvare le modifiche dell'ordine
const saveOrderChanges = () => {
  if (editableOrderData.value) {
    // Qui implementerai la logica per inviare i dati modificati al tuo backend.
    // Ad esempio, potresti usare router.post o router.put di Inertia,
    // o una chiamata fetch/axios a un endpoint API dedicato per l'aggiornamento.
    console.log('Salvataggio modifiche per ordine:', editableOrderData.value.IDOrder);
    console.log('Dati da salvare:', editableOrderData.value);
    // Dopo il salvataggio, potresti voler chiudere il modal o aggiornare la tabella
    // Esempio: closeModal();
    // Esempio: router.reload(); // per ricaricare i dati della tabella
    alert('Logica di salvataggio da implementare. Controlla la console per i dati.');
  }
};

// Funzione per creare un nuovo oggetto form di filtro vuoto
const createEmptyFilterForm = () => ({
  id: Date.now() + Math.random(), // ID univoco per ogni form
  date: '',
  name: '',
  surname: '',
  codfisc: '',
  error: '', // Errore specifico per questo form
});

// Array che conterrà tutti gli oggetti dei form di filtro
const filterForms = ref([]);

// Funzione per caricare tutti i form di filtro dal Local Storage
const loadAllFilterForms = () => {
  try {
    const stored = localStorage.getItem(localStorageKey);
    // Assicurati che sia un array e che ogni elemento abbia un 'id'
    const parsedForms = stored ? JSON.parse(stored) : [];
    return Array.isArray(parsedForms) && parsedForms.every(form => typeof form.id === 'number')
      ? parsedForms
      : [];
  } catch (e) {
    console.error("Errore nel caricamento dei filtri dal Local Storage:", e);
    return [];
  }
};

// Funzione per salvare tutti i form di filtro nel Local Storage
const saveAllFilterForms = (formsToSave) => {
  try {
    localStorage.setItem(localStorageKey, JSON.stringify(formsToSave));
  } catch (e) {
    console.error("Errore nel salvataggio dei filtri nel Local Storage:", e);
  }
};

// Inizializzazione dei form di filtro all'avvio del componente
onMounted(() => {
  let loadedForms = loadAllFilterForms();
  const urlFiltersPresent = Object.keys(page.props.filters || {}).length > 0;

  if (urlFiltersPresent) {
    // Se ci sono filtri nell'URL (es. dopo un refresh di una ricerca),
    // questi devono popolare il primo form.
    if (loadedForms.length > 0) {
      // Aggiorna il primo form esistente con i filtri dall'URL
      loadedForms[0] = {
        ...loadedForms[0], // Mantieni l'ID esistente e altre proprietà se presenti
        date: page.props.filters.date || '',
        name: page.props.filters.name || '',
        surname: page.props.filters.surname || '',
        codfisc: page.props.filters.codfisc || '',
        error: page.props.error || '',
      };
    } else {
      // Se non ci sono form salvati, crea un nuovo form con i filtri dall'URL
      loadedForms.push({
        id: Date.now() + Math.random(),
        date: page.props.filters.date || '',
        name: page.props.filters.name || '',
        surname: page.props.filters.surname || '',
        codfisc: page.props.filters.codfisc || '',
        error: page.props.error || '',
      });
    }
    // SE CI SONO FILTRI DALL'URL, MOSTRA I RISULTATI
    showResults.value = true;
    console.log('Dati degli ordini caricati:', orders.value); // Mantenuto per debug
  }

  // Se dopo l'inizializzazione non ci sono ancora form (es. prima visita senza URL filtri)
  if (loadedForms.length === 0) {
    loadedForms.push(createEmptyFilterForm());
  }

  filterForms.value = loadedForms;
});

// Watcher per salvare tutti i form nel Local Storage ogni volta che l'array filterForms cambia
// o quando cambiano le proprietà di un form al suo interno.
watch(filterForms, (newForms) => {
  saveAllFilterForms(newForms);
}, { deep: true });

// Funzione per aggiungere un nuovo form di filtro vuoto
const addFilterForm = () => {
  filterForms.value.push(createEmptyFilterForm());
};

// Funzione per rimuovere un form di filtro specifico
const removeFilterForm = (idToRemove) => {
  filterForms.value = filterForms.value.filter(form => form.id !== idToRemove);
  // Assicurati che ci sia sempre almeno un form
  if (filterForms.value.length === 0) {
    addFilterForm();
  }
};

// Funzione per pulire i campi di un singolo form di filtro
const clearSingleFilterForm = (formId) => {
  const formToClear = filterForms.value.find(form => form.id === formId);
  if (formToClear) {
    formToClear.date = '';
    formToClear.name = '';
    formToClear.surname = '';
    formToClear.codfisc = '';
    formToClear.error = ''; // Pulisci anche l'errore specifico del form
  }
  // La watch su filterForms salverà automaticamente lo stato aggiornato
};

// Funzione per inviare un form di filtro specifico
const submitFilter = (formToSubmit) => {
  // Pulisci gli errori precedenti per tutti i form e l'errore globale
  filterForms.value.forEach(form => form.error = '');
  globalError.value = '';
  // Resetta le selezioni ogni volta che viene inviato un nuovo filtro
  selectedOrderIds.value.clear();
  // Resetta anche l'ordinamento quando si esegue una nuova ricerca
  sortColumn.value = null;
  sortDirection.value = 'asc';

  // Invia la richiesta GET con i filtri del form specifico
  router.get('/orders', formToSubmit, {
    preserveScroll: true,
    preserveState: true,
    replace: true,
    onSuccess: (page) => {
      // Se Laravel ha restituito un errore (es. "Inserisci almeno un filtro."),
      // lo troviamo in page.props.error. Lo assegniamo al form che ha fatto il submit.
      if (page.props.error) {
        const submittedForm = filterForms.value.find(f => f.id === formToSubmit.id);
        if (submittedForm) {
          submittedForm.error = page.props.error;
        } else {
          // Fallback per errori non attesi non legati a un form specifico
          globalError.value = page.props.error;
        }
        // Se c'è un errore, i risultati potrebbero non essere validi, quindi nascondili
        showResults.value = false;
      } else {
        // Mostra i risultati solo se la ricerca ha successo e non ci sono errori globali
        showResults.value = true;
      }
    },
    onError: (errors) => {
      // Gestione degli errori di validazione di Inertia (es. 'date' non è una data valida)
      const submittedForm = filterForms.value.find(f => f.id === formToSubmit.id);
      if (submittedForm) {
        let formErrorMessages = [];
        for (const key in errors) {
          formErrorMessages.push(errors[key]);
        }
        submittedForm.error = formErrorMessages.join('; '); // Unisci più messaggi di errore
      } else {
        globalError.value = 'Si è verificato un errore durante l\'invio del form.';
      }
      // In caso di errori di validazione, nascondi i risultati
      showResults.value = false;
    }
  });
};

// Funzione per pulire tutti i filtri salvati nel Local Storage e resettare i form
const clearAllFormsAndLocalStorage = () => {
  localStorage.removeItem(localStorageKey); // Rimuovi tutti i filtri salvati

  // Resetta l'array dei form e aggiungi un nuovo form vuoto
  filterForms.value = [];
  addFilterForm();
  // Resetta anche le selezioni
  selectedOrderIds.value.clear();
  // Resetta anche l'ordinamento
  sortColumn.value = null;
  sortDirection.value = 'asc';

  // Invia una richiesta GET senza filtri per aggiornare la pagina
  // E NASCONDI I RISULTATI QUANDO SI PULISCE TUTTO
  router.get('/orders', {}, {
    onSuccess: () => {
      showResults.value = false;
    }
  });
};

// Funzione per gestire la selezione/deselezione di una riga
const toggleRowSelection = (orderId) => {
  if (selectedOrderIds.value.has(orderId)) {
    selectedOrderIds.value.delete(orderId);
  } else {
    selectedOrderIds.value.add(orderId);
  }
};

// Funzione per verificare se una riga è selezionata
const isRowSelected = (orderId) => {
  return selectedOrderIds.value.has(orderId);
};

// Funzione per selezionare/deselezionare tutte le righe
const toggleSelectAll = (event) => {
  if (event.target.checked) {
    orders.value.forEach(order => selectedOrderIds.value.add(order.IDOrder));
  } else {
    selectedOrderIds.value.clear();
  }
};

// Computed property per sapere se tutte le righe visibili sono selezionate
const allRowsSelected = computed(() => {
  if (orders.value.length === 0) return false;
  return orders.value.every(order => selectedOrderIds.value.has(order.IDOrder));
});

// Computed property per sapere se almeno una riga è selezionata (per lo stato "indeterminate" della checkbox)
const someRowsSelected = computed(() => {
  return selectedOrderIds.value.size > 0 && selectedOrderIds.value.size < orders.value.length;
});

// Funzione per gestire l'ordinamento per colonna
const sortBy = (column) => {
  if (sortColumn.value === column) {
    // Se la stessa colonna è cliccata di nuovo, cambia la direzione dell'ordinamento
    sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc';
  } else {
    // Se viene cliccata una nuova colonna, imposta quella colonna e l'ordinamento ascendente
    sortColumn.value = column;
    sortDirection.value = 'asc';
  }
};

</script>

<template>

  <Head title="Ordini" />

  <AuthenticatedLayout>


    <!-- Risualtati ricerca dell'ordine -->
    <div v-if="showResults" class="max-w-full mx-auto sm:px-4 lg:px-4 my-8">
      <div class="bg-white dark:bg-gray-800 p-6 shadow-md sm:rounded-lg">
        <h3 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white flex justify-between items-center">
          <span>
            Risultati della Ricerca
            <span v-if="selectedRowCount > 0" class="ml-3 text-base font-normal text-blue-600 dark:text-blue-400">
              ({{ selectedRowCount }} selezionat{{ selectedRowCount === 1 ? 'o' : 'i' }})
            </span>
          </span>
          <button @click="showResults = false"
            class="p-2 rounded-md hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors duration-200">
            <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"
              xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </h3>
        <div class="relative overflow-auto" style="max-height: 600px;">
          <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead
              class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 sticky top-0 z-10">
              <tr>
                <th scope="col" class="p-4">
                  <div class="flex items-center">
                    <input id="checkbox-all" type="checkbox" :checked="allRowsSelected"
                      :indeterminate="someRowsSelected" @change="toggleSelectAll"
                      class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="checkbox-all" class="sr-only">checkbox</label>
                  </div>
                </th>
                <th scope="col" class="px-6 py-3 cursor-pointer select-none" @click="sortBy('Name')">
                  Nome
                  <span v-if="sortColumn === 'Name'" class="ml-1 inline-block">
                    <svg v-if="sortDirection === 'asc'" class="w-3 h-3" fill="none" stroke="currentColor"
                      viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                    </svg>
                    <svg v-else class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                      xmlns="http://www.w3.org/2000/svg">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                  </span>
                </th>
                <th scope="col" class="px-6 py-3 cursor-pointer select-none" @click="sortBy('Surname')">
                  Cognome
                  <span v-if="sortColumn === 'Surname'" class="ml-1 inline-block">
                    <svg v-if="sortDirection === 'asc'" class="w-3 h-3" fill="none" stroke="currentColor"
                      viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                    </svg>
                    <svg v-else class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                      xmlns="http://www.w3.org/2000/svg">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                  </span>
                </th>
                <th scope="col" class="px-6 py-3 cursor-pointer select-none" @click="sortBy('CodFisc')">
                  Codice Fiscale
                  <span v-if="sortColumn === 'CodFisc'" class="ml-1 inline-block">
                    <svg v-if="sortDirection === 'asc'" class="w-3 h-3" fill="none" stroke="currentColor"
                      viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                    </svg>
                    <svg v-else class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                      xmlns="http://www.w3.org/2000/svg">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                  </span>
                </th>
                <th scope="col" class="px-6 py-3 cursor-pointer select-none" @click="sortBy('TimestampINS')">
                  Data Inserimento
                  <span v-if="sortColumn === 'TimestampINS'" class="ml-1 inline-block">
                    <svg v-if="sortDirection === 'asc'" class="w-3 h-3" fill="none" stroke="currentColor"
                      viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                    </svg>
                    <svg v-else class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                      xmlns="http://www.w3.org/2000/svg">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                  </span>
                </th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="sortedOrders.length === 0">
                <td colspan="5" class="px-6 py-4 text-center text-gray-600 dark:text-gray-400">Nessun ordine trovato con
                  i
                  filtri specificati.</td>
              </tr>
              <tr v-else v-for="order in sortedOrders" :key="order.IDOrder" @click="toggleRowSelection(order.IDOrder)"
                :class="{
                  'bg-white border-b dark:bg-gray-800 dark:border-gray-700': !isRowSelected(order.IDOrder),
                  'bg-blue-50 dark:bg-blue-900 border-b dark:border-gray-700 hover:bg-blue-100 dark:hover:bg-blue-800': isRowSelected(order.IDOrder),
                  'cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-600': true // Aggiungi classi per hover e cursore
                }">
                <td class="w-4 p-4">
                  <div class="flex items-center">
                    <input :id="'checkbox-table-' + order.IDOrder" type="checkbox"
                      :checked="isRowSelected(order.IDOrder)" @click.stop="toggleRowSelection(order.IDOrder)"
                      class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label :for="'checkbox-table-' + order.IDOrder" class="sr-only">checkbox</label>
                  </div>
                </td>
                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                  {{ order.Name }}
                </td>
                <td class="px-6 py-4">
                  {{ order.Surname }}
                </td>
                <td class="px-6 py-4">
                  {{ order.CodFisc }}
                </td>
                <td class="px-6 py-4">
                  {{ new Date(order.TimestampINS).toLocaleDateString() }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <!-- Pulsante "Apri" che appare solo se ci sono righe selezionate -->
        <div v-if="selectedRowCount > 0" class="flex justify-end mt-4">
          <button @click="openModal"
            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            Apri ({{ selectedRowCount }})
          </button>
        </div>
      </div>
    </div>

    <!-- Filtro ordini -->
    <div class="max-w-full mx-auto sm:px-4 lg:px-4 mt-8">

      <div class="max-w-full mx-auto">
        <div class="bg-white dark:bg-gray-800 dark:text-white p-5">
          <p class="text-center">Ricerca ordini</p>
        </div>
      </div>

      <div v-for="form in filterForms" :key="form.id"
        class="bg-white flex items-center justify-center p-5 rounded-sm bg-gray-50 dark:bg-gray-800 mb-4 shadow-sm sm:rounded-lg">
        <form @submit.prevent="submitFilter(form)">
          <div class="grid gap-6 mb-6 md:grid-cols-4">
            <div>
              <label :for="'name-' + form.id"
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nome</label>
              <input type="text" :id="'name-' + form.id" v-model="form.name"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                placeholder="Inserisci Nome" />
            </div>
            <div>
              <label :for="'surname-' + form.id"
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Cognome</label>
              <input type="text" :id="'surname-' + form.id" v-model="form.surname"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                placeholder="Inserisci Cognome" />
            </div>
            <div>
              <label :for="'codfisc-' + form.id"
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Codice
                Fiscale</label>
              <input type="text" :id="'codfisc-' + form.id" v-model="form.codfisc"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                placeholder="Inserisci Codice Fiscale" />
            </div>
            <div>
              <label :for="'date-' + form.id"
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Data:</label>
              <input type="date" :id="'date-' + form.id" v-model="form.date"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                placeholder="Inserisci Data">
            </div>
          </div>
          <button type="submit"
            class="mt-6 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Cerca</button>

          <button type="button" @click="clearSingleFilterForm(form.id)"
            class="mt-6 ml-4 text-gray-700 dark:text-white bg-gray-200 hover:bg-gray-300 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800">Pulisci
            Filtro</button>

          <button type="button" @click="removeFilterForm(form.id)" v-if="filterForms.length > 1"
            class="mt-6 ml-4 text-gray-700 dark:text-white bg-red-200 hover:bg-red-300 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">Rimuovi
            Form</button>

          <div v-if="form.error" class="text-red-600 mt-2">
            {{ form.error }}
          </div>
        </form>
      </div>

      <div class="flex justify-center mt-6">
        <button @click="addFilterForm"
          class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Aggiungi
          Nuovo Filtro</button>

        <button type="button" @click="clearAllFormsAndLocalStorage"
          class="ml-4 text-gray-700 dark:text-white bg-gray-200 hover:bg-gray-300 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800">Pulisci
          Tutti i Form Salvati</button>
      </div>

      <div v-if="globalError" class="text-red-600 mt-2">
        {{ globalError }}
      </div>
    </div>
  </AuthenticatedLayout>

  <!-- Modal per la visualizzazione e modifica dei dettagli dell'ordine -->
  <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white rounded-lg p-6 shadow-xl w-full mx-4 relative dark:bg-gray-800 flex flex-col"
      style="height:95%">
      <!-- Pulsante di chiusura del modal -->
      <button @click="closeModal"
        class="absolute top-3 right-3 p-2 rounded-md hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors duration-200 text-gray-600 dark:text-gray-300 z-20">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
      </button>

      <h4 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">
        Dettagli Ordine <span v-if="editableOrderData">{{ editableOrderData.IDOrder }}</span> Data/ora creazione:
        <span v-if="editableOrderData">{{ new Date(editableOrderData.TimestampINS).toLocaleDateString('it-IT') }}</span>
      </h4>

      <form v-if="editableOrderData" @submit.prevent="saveOrderChanges" class="flex-grow flex flex-col overflow-hidden">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4 overflow-y-auto pr-2 flex-grow">
          <!-- Colonna di sinistra - Dati Anagrafici Base -->
          <div class="p-4 rounded-sm overflow-auto">
            <h5 class="text-md font-medium mb-3 text-gray-900 dark:text-white">Dettagli Ordine</h5>
            <div class="space-y-4">
              <div>
                <label :for="'modal-notesint-' + editableOrderData.IDOrder"
                  class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Note Interne</label>
                <textarea :id="'modal-notesint-' + editableOrderData.IDOrder" rows="4"
                  v-model="editableOrderData.NotesINT"
                  class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                  placeholder="Scrivi le tue note qui..."></textarea>
              </div>
              <div>
                <label :for="'modal-notesext-' + editableOrderData.IDOrder"
                  class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Note Esterne</label>
                <textarea :id="'modal-notesext-' + editableOrderData.IDOrder" rows="4"
                  v-model="editableOrderData.NotesEXT"
                  class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                  placeholder="Scrivi le tue note qui..."></textarea>
              </div>

              <div>
                <label :for="'modal-date-' + editableOrderData.IDOrder"
                  class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Data Appuntamento</label>
                <input type="datetime-local" :id="'modal-date-' + editableOrderData.IDOrder"
                  v-model="dateAppntModel"
                  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
              </div>
              <div>
                <label :for="'modal-whynot1-' + editableOrderData.IDOrder"
                  class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Motivo Rifiuto 1</label>
                <input type="text" :id="'modal-whynot1-' + editableOrderData.IDOrder"
                  v-model="editableOrderData.WhyNot1"
                  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
              </div>
              <div>
                <label :for="'modal-whynot2-' + editableOrderData.IDOrder"
                  class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Motivo Rifiuto 2</label>
                <input type="text" :id="'modal-whynot2-' + editableOrderData.IDOrder"
                  v-model="editableOrderData.WhyNot2"
                  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
              </div>
              <div>
                <label :for="'modal-notesrecall-' + editableOrderData.IDOrder"
                  class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Note Richiamo</label>
                <textarea :id="'modal-notesrecall-' + editableOrderData.IDOrder" rows="4"
                  v-model="editableOrderData.NotesRecall"
                  class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                  placeholder="Scrivi le tue note qui..."></textarea>
              </div>
              <div>
                <label :for="'modal-consents-' + editableOrderData.IDOrder"
                  class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Consensi</label>
                <textarea :id="'modal-consents-' + editableOrderData.IDOrder" rows="4"
                  v-model="editableOrderData.Consents"
                  class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                  placeholder="Scrivi le tue note qui..."></textarea>
              </div>
              <div>
                <label :for="'modal-notescompetitors-' + editableOrderData.IDOrder"
                  class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Note Concorrenza</label>
                <textarea :id="'modal-notescompetitors-' + editableOrderData.IDOrder" rows="4"
                  v-model="editableOrderData.NotesCompetitors"
                  class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                  placeholder="Scrivi le tue note qui..."></textarea>
              </div>
              <div>
                <label :for="'modal-notesrif-' + editableOrderData.IDOrder"
                  class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Note Riferimenti</label>
                <textarea :id="'modal-notesrif-' + editableOrderData.IDOrder" rows="4"
                  v-model="editableOrderData.NotesRIF"
                  class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                  placeholder="Scrivi le tue note qui..."></textarea>
              </div>
            </div>
          </div>

          <!-- Colonna di destra - Dati Anagrafici -->
          <div class="p-4 rounded-sm overflow-auto">
            <h5 class="text-md font-medium mb-3 text-gray-900 dark:text-white">Dati Anagrafici - Cliente {{
              editableOrderData.CodCustomer }}</h5>
            <div class="space-y-4">
              <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4 overflow-y-auto pr-2 flex-grow">
                <!-- Riga Anagrafica -->
                <div class="col-span-full">
                  <!-- Questo div interno sarà una griglia su schermi piccoli/medi, e un flex container su schermi grandi -->
                  <div
                    class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-none lg:flex lg:flex-row lg:items-end lg:gap-4">
                    <!-- Nome -->
                    <div class="md:col-span-2 lg:flex-grow lg:min-w-0"> <!-- Su schermi grandi, larghezza fissa -->
                      <label :for="'modal-name-' + editableOrderData.IDOrder"
                        class="block mb-1 text-xs font-medium text-gray-900 dark:text-white">Nome</label>
                      <input type="text" :id="'modal-name-' + editableOrderData.IDOrder"
                        v-model="editableOrderData.Name"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    </div>
                    <!-- Cognome -->
                    <div class="md:col-span-2 lg:flex-grow lg:min-w-0">
                      <label :for="'modal-surname-' + editableOrderData.IDOrder"
                        class="block mb-1 text-xs font-medium text-gray-900 dark:text-white">Cognome</label>
                      <input type="text" :id="'modal-surname-' + editableOrderData.IDOrder"
                        v-model="editableOrderData.Surname"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    </div>
                    <!-- Codice Fiscale -->
                    <div class="md:col-span-2 lg:flex-grow lg:min-w-0"> <!-- Su schermi grandi, larghezza fissa -->
                      <label :for="'modal-codfisc-' + editableOrderData.IDOrder"
                        class="block mb-1 text-xs font-medium text-gray-900 dark:text-white">Codice Fiscale</label>
                      <input type="text" :id="'modal-codfisc-' + editableOrderData.IDOrder"
                        v-model="editableOrderData.CodFisc"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    </div>
                    <!-- Sesso -->
                    <div class="md:col-span-2 lg:flex-grow lg:min-w-0"> <!-- Su schermi grandi, larghezza fissa -->
                      <label :for="'modal-gender-' + editableOrderData.IDOrder"
                        class="block mb-2 text-xs font-medium text-gray-900 dark:text-white">Sesso</label>
                      <select :id="'modal-gender-' + editableOrderData.IDOrder" v-model="editableOrderData.Gender"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option :value="null" selected>Seleziona tipo</option>
                        <option value="M">M</option>
                        <option value="F">F</option>
                      </select>
                    </div>
                  </div>
                </div>
                <!-- Riga Telefono -->
                <div class="col-span-full">
                  <!-- Questo div interno sarà una griglia su schermi piccoli/medi, e un flex container su schermi grandi -->
                  <div
                    class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-none lg:flex lg:flex-row lg:items-end lg:gap-4">
                    <!-- Telefono -->
                    <div class="md:col-span-2 lg:flex-grow lg:min-w-0"> <!-- Su schermi grandi, larghezza fissa -->
                      <label :for="'modal-tel1-' + editableOrderData.IDOrder"
                        class="block mb-1 text-xs font-medium text-gray-900 dark:text-white">Telefono</label>
                      <input type="text" :id="'modal-tel1-' + editableOrderData.IDOrder"
                        v-model="editableOrderData.Tel1"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    </div>
                    <!-- Cellulare -->
                    <div class="md:col-span-2 lg:flex-grow lg:min-w-0"> <!-- Su schermi grandi, larghezza fissa -->
                      <label :for="'modal-cell1-' + editableOrderData.IDOrder"
                        class="block mb-1 text-xs font-medium text-gray-900 dark:text-white">Cellulare</label>
                      <input type="text" :id="'modal-cell1-' + editableOrderData.IDOrder"
                        v-model="editableOrderData.Cell1"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    </div>
                    <!-- VAT -->
                    <div class="md:col-span-2 lg:flex-grow lg:min-w-0">
                      <!-- Su schermi medi, occupa 2 colonne; su grandi, si espande -->
                      <label :for="'modal-vat-' + editableOrderData.IDOrder"
                        class="block mb-1 text-xs font-medium text-gray-900 dark:text-white">Partita IVA</label>
                      <input type="text" :id="'modal-vat-' + editableOrderData.IDOrder" v-model="editableOrderData.VAT"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    </div>
                    <!-- Fax1 -->
                    <div class="md:col-span-2 lg:flex-grow lg:min-w-0"> <!-- Su schermi grandi, larghezza fissa -->
                      <label :for="'modal-fax1-' + editableOrderData.IDOrder"
                        class="block mb-1 text-xs font-medium text-gray-900 dark:text-white">Fax</label>
                      <input type="text" :id="'modal-fax1-' + editableOrderData.IDOrder"
                        v-model="editableOrderData.Fax1"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    </div>
                  </div>
                </div>
                <!-- Riga Telefono -->
                <div class="col-span-full">
                  <!-- Questo div interno sarà una griglia su schermi piccoli/medi, e un flex container su schermi grandi -->
                  <div
                    class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-none lg:flex lg:flex-row lg:items-end lg:gap-4">

                    <!-- Email -->
                    <div class="md:col-span-2 lg:flex-grow lg:min-w-0">
                      <!-- Su schermi medi, occupa 2 colonne; su grandi, si espande -->
                      <label :for="'modal-email1-' + editableOrderData.IDOrder"
                        class="block mb-1 text-xs font-medium text-gray-900 dark:text-white">Email</label>
                      <input type="text" :id="'modal-email1-' + editableOrderData.IDOrder"
                        v-model="editableOrderData.Email1"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    </div>

                    <!-- Città -->
                    <div class="md:col-span-2 lg:flex-grow lg:min-w-0"> <!-- Su schermi grandi, larghezza fissa -->
                      <label :for="'modal-city-' + editableOrderData.IDOrder"
                        class="block mb-1 text-xs font-medium text-gray-900 dark:text-white">Città</label>
                      <input type="text" :id="'modal-city-' + editableOrderData.IDOrder"
                        v-model="editableOrderData.City"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    </div>
                    <div class="lg:w-24"> <!-- Su schermi grandi, larghezza fissa -->
                      <label :for="'modal-prov-' + editableOrderData.IDOrder"
                        class="block mb-1 text-xs font-medium text-gray-900 dark:text-white">Provincia</label>
                      <input type="text" :id="'modal-prov-' + editableOrderData.IDOrder"
                        v-model="editableOrderData.Prov"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    </div>
                  </div>
                </div>
                <!-- Riga indirizzo -->
                <div class="col-span-full">
                  <!-- Questo div interno sarà una griglia su schermi piccoli/medi, e un flex container su schermi grandi -->
                  <div
                    class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-none lg:flex lg:flex-row lg:items-end lg:gap-4">
                    <!-- Toponimo -->
                    <div class="lg:w-24"> <!-- Su schermi grandi, larghezza fissa -->
                      <label :for="'modal-addresstoponomy-' + editableOrderData.IDOrder"
                        class="block mb-1 text-xs font-medium text-gray-900 dark:text-white">Toponimo</label>
                      <input type="text" :id="'modal-addresstoponomy-' + editableOrderData.IDOrder"
                        v-model="editableOrderData.AddressToponomy"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    </div>
                    <!-- Indirizzo -->
                    <div class="md:col-span-2 lg:flex-grow lg:min-w-0">
                      <!-- Su schermi medi, occupa 2 colonne; su grandi, si espande -->
                      <label :for="'modal-address-' + editableOrderData.IDOrder"
                        class="block mb-1 text-xs font-medium text-gray-900 dark:text-white">Indirizzo</label>
                      <input type="text" :id="'modal-address-' + editableOrderData.IDOrder"
                        v-model="editableOrderData.Address"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    </div>

                    <!-- Civico -->
                    <div class="lg:w-24"> <!-- Su schermi grandi, larghezza fissa -->
                      <label :for="'modal-addressnumber-' + editableOrderData.IDOrder"
                        class="block mb-1 text-xs font-medium text-gray-900 dark:text-white">Civico</label>
                      <input type="text" :id="'modal-addressnumber-' + editableOrderData.IDOrder"
                        v-model="editableOrderData.AddressNumber"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    </div>
                    <div class="lg:w-24"> <!-- Su schermi grandi, larghezza fissa -->
                      <label :for="'modal-zipcode-' + editableOrderData.IDOrder"
                        class="block mb-1 text-xs font-medium text-gray-900 dark:text-white">CAP</label>
                      <input type="text" :id="'modal-zipcode-' + editableOrderData.IDOrder"
                        v-model="editableOrderData.ZipCode"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    </div>
                  </div>
                </div>
                <!-- Riga Nazione -->
                <div class="col-span-full">
                  <!-- Questo div interno sarà una griglia su schermi piccoli/medi, e un flex container su schermi grandi -->
                  <div
                    class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-none lg:flex lg:flex-row lg:items-end lg:gap-4">
                    <!-- AddressDescription -->
                    <div class="md:col-span-2 lg:flex-grow lg:min-w-0">
                      <label :for="'modal-addressdescription-' + editableOrderData.IDOrder"
                        class="block mb-1 text-xs font-medium text-gray-900 dark:text-white">Tipologia</label>
                      <input type="text" :id="'modal-addressdescription-' + editableOrderData.IDOrder"
                        v-model="editableOrderData.AddressDescription"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    </div>
                    <!-- Web2 -->
                    <div class="md:col-span-2 lg:flex-grow lg:min-w-0">
                      <!-- Su schermi medi, occupa 2 colonne; su grandi, si espande -->
                      <label :for="'modal-town-' + editableOrderData.IDOrder"
                        class="block mb-1 text-xs font-medium text-gray-900 dark:text-white">Frazione</label>
                      <input type="text" :id="'modal-town-' + editableOrderData.IDOrder"
                        v-model="editableOrderData.Town"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    </div>
                    <!-- Nazione -->
                    <div class="md:col-span-2 lg:flex-grow lg:min-w-0">
                      <!-- Su schermi medi, occupa 2 colonne; su grandi, si espande -->
                      <label :for="'modal-country-' + editableOrderData.IDOrder"
                        class="block mb-1 text-xs font-medium text-gray-900 dark:text-white">Nazione</label>
                      <input type="text" :id="'modal-country-' + editableOrderData.IDOrder"
                        v-model="editableOrderData.Country"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    </div>
                  </div>
                </div>
                <!-- Riga Web -->
                <div class="col-span-full">
                  <!-- Questo div interno sarà una griglia su schermi piccoli/medi, e un flex container su schermi grandi -->
                  <div
                    class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-none lg:flex lg:flex-row lg:items-end lg:gap-4">
                    <!-- web -->
                    <div class="md:col-span-2 lg:flex-grow lg:min-w-0">
                      <label :for="'modal-web1-' + editableOrderData.IDOrder"
                        class="block mb-1 text-xs font-medium text-gray-900 dark:text-white">Web</label>
                      <input type="text" :id="'modal-web1-' + editableOrderData.IDOrder"
                        v-model="editableOrderData.Web1"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    </div>
                    <!-- Web2 -->
                    <div class="md:col-span-2 lg:flex-grow lg:min-w-0">
                      <!-- Su schermi medi, occupa 2 colonne; su grandi, si espande -->
                      <label :for="'modal-web2-' + editableOrderData.IDOrder"
                        class="block mb-1 text-xs font-medium text-gray-900 dark:text-white">Web 2</label>
                      <input type="text" :id="'modal-web2-' + editableOrderData.IDOrder"
                        v-model="editableOrderData.Web2"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    </div>
                  </div>
                </div>
              </div>
              <!-- Aggiungi qui altri campi della tabella Orders -->
            </div>
            <h5 class="text-md font-medium mb-3 text-gray-900 dark:text-white">Indirizzo Aggiuntivo</h5>
            <div class="space-y-4">
              <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4 overflow-y-auto pr-2 flex-grow">
                <!-- Riga 1 Indirizzi Aggiuntivi -->
                <div class="col-span-full">
                  <!-- Questo div interno sarà una griglia su schermi piccoli/medi, e un flex container su schermi grandi -->
                  <div
                    class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-none lg:flex lg:flex-row lg:items-end lg:gap-4">
                    <!-- Città 2 -->
                    <div class="md:col-span-2 lg:flex-grow lg:min-w-0"> <!-- Su schermi grandi, larghezza fissa -->
                      <label :for="'modal-city2-' + editableOrderData.IDOrder"
                        class="block mb-1 text-xs font-medium text-gray-900 dark:text-white">Città</label>
                      <input type="text" :id="'modal-city2-' + editableOrderData.IDOrder"
                        v-model="editableOrderData.City2"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    </div>
                    <!-- Provincia 2 -->
                    <div class="lg:w-24">
                      <!-- Su schermi medi, occupa 2 colonne; su grandi, si espande -->
                      <label :for="'modal-prov2-' + editableOrderData.IDOrder"
                        class="block mb-1 text-xs font-medium text-gray-900 dark:text-white">Provincia</label>
                      <input type="text" :id="'modal-prov2-' + editableOrderData.IDOrder"
                        v-model="editableOrderData.Prov2"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    </div>
                    <!-- Frazione -->
                    <div class="md:col-span-2 lg:flex-grow lg:min-w-0"> <!-- Su schermi grandi, larghezza fissa -->
                      <label :for="'modal-town2-' + editableOrderData.IDOrder"
                        class="block mb-1 text-xs font-medium text-gray-900 dark:text-white">Frazione</label>
                      <input type="text" :id="'modal-town2-' + editableOrderData.IDOrder"
                        v-model="editableOrderData.Town2"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    </div>
                  </div>
                </div>
                <!-- Riga 2 Indirizzi Aggiuntivi -->
                <div class="col-span-full">
                  <!-- Questo div interno sarà una griglia su schermi piccoli/medi, e un flex container su schermi grandi -->
                  <div
                    class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-none lg:flex lg:flex-row lg:items-end lg:gap-4">
                    <!-- Toponimo -->
                    <div class="lg:w-24"> <!-- Su schermi grandi, larghezza fissa -->
                      <label :for="'modal-addresstoponomy2-' + editableOrderData.IDOrder"
                        class="block mb-1 text-xs font-medium text-gray-900 dark:text-white">Toponimo</label>
                      <input type="text" :id="'modal-addresstoponomy2-' + editableOrderData.IDOrder"
                        v-model="editableOrderData.AddressToponomy2"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    </div>
                    <!-- Indirizzo -->
                    <div class="md:col-span-2 lg:flex-grow lg:min-w-0">
                      <!-- Su schermi medi, occupa 2 colonne; su grandi, si espande -->
                      <label :for="'modal-address2-' + editableOrderData.IDOrder"
                        class="block mb-1 text-xs font-medium text-gray-900 dark:text-white">Indirizzo</label>
                      <input type="text" :id="'modal-address2-' + editableOrderData.IDOrder"
                        v-model="editableOrderData.Address2"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    </div>
                    <!-- Civico -->
                    <div class="lg:w-24"> <!-- Su schermi grandi, larghezza fissa -->
                      <label :for="'modal-addressnumber2-' + editableOrderData.IDOrder"
                        class="block mb-1 text-xs font-medium text-gray-900 dark:text-white">Civico</label>
                      <input type="text" :id="'modal-addressnumber2-' + editableOrderData.IDOrder"
                        v-model="editableOrderData.AddressNumber2"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    </div>
                    <div class="lg:w-24"> <!-- Su schermi grandi, larghezza fissa -->
                      <label :for="'modal-zipcode2-' + editableOrderData.IDOrder"
                        class="block mb-1 text-xs font-medium text-gray-900 dark:text-white">CAP</label>
                      <input type="text" :id="'modal-zipcode2-' + editableOrderData.IDOrder"
                        v-model="editableOrderData.ZipCode2"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    </div>
                  </div>
                </div>
                <!-- Riga 3 Indirizzi Aggiuntivi -->
                <div class="col-span-full">
                  <div
                    class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-none lg:flex lg:flex-row lg:items-end lg:gap-4">
                    <!-- Tipologia Indirizzo AddressDescription2 -->
                    <div class="md:col-span-2 lg:flex-grow lg:min-w-0">
                      <label :for="'modal-addressdescription2-' + editableOrderData.IDOrder"
                        class="block mb-1 text-xs font-medium text-gray-900 dark:text-white">Tipologia</label>
                      <input type="text" :id="'modal-addressdescription2-' + editableOrderData.IDOrder"
                        v-model="editableOrderData.AddressDescription2"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    </div>
                    <!-- Nazione Country2 -->
                    <div class="md:col-span-2 lg:flex-grow lg:min-w-0">
                      <label :for="'modal-country2-' + editableOrderData.IDOrder"
                        class="block mb-1 text-xs font-medium text-gray-900 dark:text-white">Nazione</label>
                      <input type="text" :id="'modal-country2-' + editableOrderData.IDOrder"
                        v-model="editableOrderData.Country2"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    </div>
                  </div>
                </div>
              </div>
              <!-- Aggiungi qui altri campi della tabella Orders -->
            </div>
            <h5 class="text-md font-medium mb-3 text-gray-900 dark:text-white">Recapiti Alternativi</h5>
            <div class="space-y-4">
              <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4 overflow-y-auto pr-2 flex-grow">
                <!-- Riga 1 Recapiti Alternativi -->
                <div class="col-span-full">
                  <!-- Questo div interno sarà una griglia su schermi piccoli/medi, e un flex container su schermi grandi -->
                  <div
                    class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-none lg:flex lg:flex-row lg:items-end lg:gap-4">
                    <!-- Tel2 -->
                    <div class="md:col-span-2 lg:flex-grow lg:min-w-0">
                      <label :for="'modal-tel2-' + editableOrderData.IDOrder"
                        class="block mb-1 text-xs font-medium text-gray-900 dark:text-white">Telefono 2</label>
                      <input type="text" :id="'modal-tel2-' + editableOrderData.IDOrder"
                        v-model="editableOrderData.Tel2"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    </div>
                    <!-- Cell2 -->
                    <div class="md:col-span-2 lg:flex-grow lg:min-w-0">
                      <!-- Su schermi medi, occupa 2 colonne; su grandi, si espande -->
                      <label :for="'modal-cell2-' + editableOrderData.IDOrder"
                        class="block mb-1 text-xs font-medium text-gray-900 dark:text-white">Cellulare 2</label>
                      <input type="text" :id="'modal-cell2-' + editableOrderData.IDOrder"
                        v-model="editableOrderData.Cell2"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    </div>
                    <!-- Email 2 -->
                    <div class="md:col-span-2 lg:flex-grow lg:min-w-0"> <!-- Su schermi grandi, larghezza fissa -->
                      <label :for="'modal-email2-' + editableOrderData.IDOrder"
                        class="block mb-1 text-xs font-medium text-gray-900 dark:text-white">Email 2</label>
                      <input type="text" :id="'modal-email2-' + editableOrderData.IDOrder"
                        v-model="editableOrderData.Email2"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    </div>
                    <!-- Email 2 -->
                    <div class="md:col-span-2 lg:flex-grow lg:min-w-0"> <!-- Su schermi grandi, larghezza fissa -->
                      <label :for="'modal-fax2-' + editableOrderData.IDOrder"
                        class="block mb-1 text-xs font-medium text-gray-900 dark:text-white">Fax 2</label>
                      <input type="text" :id="'modal-fax2-' + editableOrderData.IDOrder"
                        v-model="editableOrderData.Fax2"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    </div>
                  </div>
                </div>
                <!-- Riga 2 Recapiti Alternativi -->
                <div class="col-span-full">
                  <!-- Questo div interno sarà una griglia su schermi piccoli/medi, e un flex container su schermi grandi -->
                  <div
                    class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-none lg:flex lg:flex-row lg:items-end lg:gap-4">
                    <!-- Tel3 -->
                    <div class="md:col-span-2 lg:flex-grow lg:min-w-0">
                      <label :for="'modal-tel3-' + editableOrderData.IDOrder"
                        class="block mb-1 text-xs font-medium text-gray-900 dark:text-white">Telefono 3</label>
                      <input type="text" :id="'modal-tel3-' + editableOrderData.IDOrder"
                        v-model="editableOrderData.Tel3"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    </div>
                    <!-- Cell3 -->
                    <div class="md:col-span-2 lg:flex-grow lg:min-w-0">
                      <!-- Su schermi medi, occupa 2 colonne; su grandi, si espande -->
                      <label :for="'modal-cell3-' + editableOrderData.IDOrder"
                        class="block mb-1 text-xs font-medium text-gray-900 dark:text-white">Cellulare 3</label>
                      <input type="text" :id="'modal-cell3-' + editableOrderData.IDOrder"
                        v-model="editableOrderData.Cell3"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    </div>
                    <!-- Email 3 -->
                    <div class="md:col-span-2 lg:flex-grow lg:min-w-0"> <!-- Su schermi grandi, larghezza fissa -->
                      <label :for="'modal-email3-' + editableOrderData.IDOrder"
                        class="block mb-1 text-xs font-medium text-gray-900 dark:text-white">Email 3</label>
                      <input type="text" :id="'modal-email3-' + editableOrderData.IDOrder"
                        v-model="editableOrderData.Email3"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    </div>
                    <!-- Fax 2 -->
                    <div class="md:col-span-2 lg:flex-grow lg:min-w-0"> <!-- Su schermi grandi, larghezza fissa -->
                      <label :for="'modal-fax3-' + editableOrderData.IDOrder"
                        class="block mb-1 text-xs font-medium text-gray-900 dark:text-white">Fax 3</label>
                      <input type="text" :id="'modal-fax3-' + editableOrderData.IDOrder"
                        v-model="editableOrderData.Fax3"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    </div>
                  </div>
                </div>
              </div>
              <!-- Aggiungi qui altri campi della tabella Orders -->
            </div>
            <h5 class="text-md font-medium mb-3 text-gray-900 dark:text-white">Codici di Migrazione</h5>
            <div class="space-y-4">
              <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4 overflow-y-auto pr-2 flex-grow">
                <!-- Riga 1 Codici di Migrazione -->
                <div class="col-span-full">
                  <!-- Questo div interno sarà una griglia su schermi piccoli/medi, e un flex container su schermi grandi -->
                  <div
                    class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-none lg:flex lg:flex-row lg:items-end lg:gap-4">
                    <!-- CDM1 -->
                    <div class="md:col-span-2 lg:flex-grow lg:min-w-0">
                      <!-- Su schermi medi, occupa 2 colonne; su grandi, si espande -->
                      <label :for="'modal-cdm1-' + editableOrderData.IDOrder"
                        class="block mb-1 text-xs font-medium text-gray-900 dark:text-white">CDM 1</label>
                      <input type="text" :id="'modal-cdm1-' + editableOrderData.IDOrder"
                        v-model="editableOrderData.CDM1"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    </div>
                    <!-- CDM2 -->
                    <div class="md:col-span-2 lg:flex-grow lg:min-w-0"> <!-- Su schermi grandi, larghezza fissa -->
                      <label :for="'modal-cdm2-' + editableOrderData.IDOrder"
                        class="block mb-1 text-xs font-medium text-gray-900 dark:text-white">CDM 2</label>
                      <input type="text" :id="'modal-cdm2-' + editableOrderData.IDOrder"
                        v-model="editableOrderData.CDM2"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    </div>
                  </div>
                </div>
              </div>
              <!-- Aggiungi qui altri campi della tabella Orders -->
            </div>
            <h5 class="text-md font-medium mb-3 text-gray-900 dark:text-white">Dati di Nascita</h5>
            <div class="space-y-4">
              <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4 overflow-y-auto pr-2 flex-grow">
                <!-- Riga 1 Dati di Nascita -->
                <div class="col-span-full">
                  <!-- Questo div interno sarà una griglia su schermi piccoli/medi, e un flex container su schermi grandi -->
                  <div
                    class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-none lg:flex lg:flex-row lg:items-end lg:gap-4">
                    <!-- BornCity -->
                    <div class="md:col-span-2 lg:flex-grow lg:min-w-0">
                      <!-- Su schermi medi, occupa 2 colonne; su grandi, si espande -->
                      <label :for="'modal-borncity-' + editableOrderData.IDOrder"
                        class="block mb-1 text-xs font-medium text-gray-900 dark:text-white">Nato a</label>
                      <input type="text" :id="'modal-borncity-' + editableOrderData.IDOrder"
                        v-model="editableOrderData.BornCity"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    </div>
                    <!-- BornProv -->
                    <div class="lg:w-24"> <!-- Su schermi grandi, larghezza fissa -->
                      <label :for="'modal-bornprov-' + editableOrderData.IDOrder"
                        class="block mb-1 text-xs font-medium text-gray-900 dark:text-white">Provincia</label>
                      <input type="text" :id="'modal-bornprov-' + editableOrderData.IDOrder"
                        v-model="editableOrderData.BornProv"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    </div>
                    <!-- BornWhen -->
                    <div class="md:col-span-2 lg:flex-grow lg:min-w-0"> <!-- Su schermi grandi, larghezza fissa -->
                      <label :for="'modal-bornwhen-' + editableOrderData.IDOrder"
                        class="block mb-1 text-xs font-medium text-gray-900 dark:text-white">In Data</label>
                      <input type="date" :id="'modal-bornwhen-' + editableOrderData.IDOrder"
                        v-model="editableOrderData.BornWhen"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    </div>
                    <!-- BornCountry -->
                    <div class="md:col-span-2 lg:flex-grow lg:min-w-0"> <!-- Su schermi grandi, larghezza fissa -->
                      <label :for="'modal-borncountry-' + editableOrderData.IDOrder"
                        class="block mb-1 text-xs font-medium text-gray-900 dark:text-white">Paese</label>
                      <input type="text" :id="'modal-borncountry-' + editableOrderData.IDOrder"
                        v-model="editableOrderData.BornCountry"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    </div>
                  </div>
                </div>
              </div>
              <!-- Aggiungi qui altri campi della tabella Orders -->
            </div>
            <h5 class="text-md font-medium mb-3 text-gray-900 dark:text-white">Documento</h5>
            <div class="space-y-4">
              <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4 overflow-y-auto pr-2 flex-grow">
                <!-- Riga 1 Documento -->
                <div class="col-span-full">
                  <!-- Questo div interno sarà una griglia su schermi piccoli/medi, e un flex container su schermi grandi -->
                  <div
                    class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-none lg:flex lg:flex-row lg:items-end lg:gap-4">
                    <!-- DocType -->
                    <div class="md:col-span-2 lg:flex-grow lg:min-w-0">
                      <label :for="'modal-doctype-' + editableOrderData.IDOrder"
                        class="block mb-2 text-xs font-medium text-gray-900 dark:text-white">Tipo di Documento</label>
                      <select :id="'modal-doctype-' + editableOrderData.IDOrder" v-model="editableOrderData.DocType"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option :value="null" selected>Seleziona tipo</option>
                        <option value="C.I.">Carta Di Identità</option>
                        <option value="Patente">Patente</option>
                        <option value="Passaporto">Passaporto</option>
                      </select>
                    </div>
                    <!-- DocNumber -->
                    <div class="md:col-span-2 lg:flex-grow lg:min-w-0">
                      <label :for="'modal-docnumber-' + editableOrderData.IDOrder"
                        class="block mb-1 text-xs font-medium text-gray-900 dark:text-white">Numero</label>
                      <input type="text" :id="'modal-docnumber-' + editableOrderData.IDOrder"
                        v-model="editableOrderData.DocNumber"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    </div>
                    <!-- DocProvider -->
                    <div class="md:col-span-2 lg:flex-grow lg:min-w-0">
                      <label :for="'modal-docprovider-' + editableOrderData.IDOrder"
                        class="block mb-1 text-xs font-medium text-gray-900 dark:text-white">Ente di Rilascio</label>
                      <input type="text" :id="'modal-docprovider-' + editableOrderData.IDOrder"
                        v-model="editableOrderData.DocProvider"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    </div>
                  </div>
                </div>
                <!-- Riga 2 Documento -->
                <div class="col-span-full">
                  <!-- Questo div interno sarà una griglia su schermi piccoli/medi, e un flex container su schermi grandi -->
                  <div
                    class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-none lg:flex lg:flex-row lg:items-end lg:gap-4">
                    <!-- DocReleaseDate" -->
                    <div class="md:col-span-2 lg:flex-grow lg:min-w-0">
                      <label :for="'modal-docreleasedate-' + editableOrderData.IDOrder"
                        class="block mb-1 text-xs font-medium text-gray-900 dark:text-white">Valido dal:</label>
                      <input type="date" :id="'modal-docreleasedate-' + editableOrderData.IDOrder"
                        v-model="editableOrderData.DocReleaseDate"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    </div>
                    <!-- DocExpirationDate -->
                    <div class="md:col-span-2 lg:flex-grow lg:min-w-0">
                      <label :for="'modal-docexpirationdate-' + editableOrderData.IDOrder"
                        class="block mb-1 text-xs font-medium text-gray-900 dark:text-white">Al:</label>
                      <input type="date" :id="'modal-docexpirationdate-' + editableOrderData.IDOrder"
                        v-model="editableOrderData.DocExpirationDate"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    </div>
                  </div>
                </div>
              </div>
              <!-- Aggiungi qui altri campi della tabella Orders -->
            </div>
            <h5 class="text-md font-medium mb-3 text-gray-900 dark:text-white">Referente</h5>
            <div class="space-y-4">
              <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4 overflow-y-auto pr-2 flex-grow">
                <!-- Riga 1 Dati di Nascita -->
                <div class="col-span-full">
                  <!-- Questo div interno sarà una griglia su schermi piccoli/medi, e un flex container su schermi grandi -->
                  <div
                    class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-none lg:flex lg:flex-row lg:items-end lg:gap-4">
                    <!-- ReferentName -->
                    <div class="md:col-span-2 lg:flex-grow lg:min-w-0">
                      <!-- Su schermi medi, occupa 2 colonne; su grandi, si espande -->
                      <label :for="'modal-referentname-' + editableOrderData.IDOrder"
                        class="block mb-1 text-xs font-medium text-gray-900 dark:text-white">Nome</label>
                      <input type="text" :id="'modal-referentname-' + editableOrderData.IDOrder"
                        v-model="editableOrderData.ReferentName"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    </div>
                    <!-- ReferentSurname -->
                    <div class="md:col-span-2 lg:flex-grow lg:min-w-0"> <!-- Su schermi grandi, larghezza fissa -->
                      <label :for="'modal-referentsurname-' + editableOrderData.IDOrder"
                        class="block mb-1 text-xs font-medium text-gray-900 dark:text-white">Cognome</label>
                      <input type="text" :id="'modal-referentsurname-' + editableOrderData.IDOrder"
                        v-model="editableOrderData.ReferentSurname"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    </div>
                    <!-- ReferentRole -->
                    <div class="md:col-span-2 lg:flex-grow lg:min-w-0"> <!-- Su schermi grandi, larghezza fissa -->
                      <label :for="'modal-referentrole-' + editableOrderData.IDOrder"
                        class="block mb-1 text-xs font-medium text-gray-900 dark:text-white">Ruolo</label>
                      <input type="text" :id="'modal-referentrole-' + editableOrderData.IDOrder"
                        v-model="editableOrderData.ReferentRole"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    </div>
                  </div>
                </div>
              </div>
              <!-- Aggiungi qui altri campi della tabella Orders -->
            </div>
            <h5 class="text-md font-medium mb-3 text-gray-900 dark:text-white">Finanza</h5>
            <div class="space-y-4">
              <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4 overflow-y-auto pr-2 flex-grow">
                <!-- Riga 1 Finanza -->
                <div class="col-span-full">
                  <!-- Questo div interno sarà una griglia su schermi piccoli/medi, e un flex container su schermi grandi -->
                  <div
                    class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-none lg:flex lg:flex-row lg:items-end lg:gap-4">
                    <!-- IBAN -->
                    <div class="md:col-span-2 lg:flex-grow lg:min-w-0">
                      <!-- Su schermi medi, occupa 2 colonne; su grandi, si espande -->
                      <label :for="'modal-iban-' + editableOrderData.IDOrder"
                        class="block mb-1 text-xs font-medium text-gray-900 dark:text-white">IBAN</label>
                      <input type="text" :id="'modal-iban-' + editableOrderData.IDOrder"
                        v-model="editableOrderData.IBAN"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    </div>
                    <!-- Bank -->
                    <div class="md:col-span-2 lg:flex-grow lg:min-w-0"> <!-- Su schermi grandi, larghezza fissa -->
                      <label :for="'modal-bank-' + editableOrderData.IDOrder"
                        class="block mb-1 text-xs font-medium text-gray-900 dark:text-white">Banca</label>
                      <input type="text" :id="'modal-bank-' + editableOrderData.IDOrder"
                        v-model="editableOrderData.Bank"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    </div>
                  </div>
                </div>
                <!-- Riga 2 Finanza -->
                <div class="col-span-full">
                  <!-- Questo div interno sarà una griglia su schermi piccoli/medi, e un flex container su schermi grandi -->
                  <div
                    class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-none lg:flex lg:flex-row lg:items-end lg:gap-4">
                    <!-- CC_Name -->
                    <div class="md:col-span-2 lg:flex-grow lg:min-w-0"> <!-- Su schermi grandi, larghezza fissa -->
                      <label :for="'modal-ccname-' + editableOrderData.IDOrder"
                        class="block mb-1 text-xs font-medium text-gray-900 dark:text-white">Carta</label>
                      <input type="text" :id="'modal-ccname-' + editableOrderData.IDOrder"
                        v-model="editableOrderData.CC_Name"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    </div>
                    <!-- CC_Number -->
                    <div class="md:col-span-2 lg:flex-grow lg:min-w-0">
                      <!-- Su schermi medi, occupa 2 colonne; su grandi, si espande -->
                      <label :for="'modal-ccnumber-' + editableOrderData.IDOrder"
                        class="block mb-1 text-xs font-medium text-gray-900 dark:text-white">Numero Carta</label>
                      <input type="text" :id="'modal-ccnumber-' + editableOrderData.IDOrder"
                        v-model="editableOrderData.CC_Number"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    </div>
                    <!-- CC_NameOwner -->
                    <div class="md:col-span-2 lg:flex-grow lg:min-w-0"> <!-- Su schermi grandi, larghezza fissa -->
                      <label :for="'modal-ccnameowner-' + editableOrderData.IDOrder"
                        class="block mb-1 text-xs font-medium text-gray-900 dark:text-white">Intestatario</label>
                      <input type="text" :id="'modal-ccnameowner-' + editableOrderData.IDOrder"
                        v-model="editableOrderData.CC_NameOwner"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    </div>
                  </div>
                </div>
                <!-- Riga 3 Finanza -->
                <div class="col-span-full">
                  <!-- Questo div interno sarà una griglia su schermi piccoli/medi, e un flex container su schermi grandi -->
                  <div
                    class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-none lg:flex lg:flex-row lg:items-end lg:gap-4">
                    <!-- CC_ExpireMonth -->
                    <div class="lg:w-24"> <!-- Su schermi grandi, larghezza fissa -->
                      <label :for="'modal-ccexpiremonth-' + editableOrderData.IDOrder"
                        class="block mb-1 text-xs font-medium text-gray-900 dark:text-white">Mese Scadenza</label>
                      <input type="text" :id="'modal-ccexpiremonth-' + editableOrderData.IDOrder"
                        v-model="editableOrderData.CC_ExpireMonth"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    </div>
                    <!-- CC_ExpireYear -->
                    <div class="lg:w-24">
                      <!-- Su schermi medi, occupa 2 colonne; su grandi, si espande -->
                      <label :for="'modal-ccexpireyear-' + editableOrderData.IDOrder"
                        class="block mb-1 text-xs font-medium text-gray-900 dark:text-white">Anno Scadenza</label>
                      <input type="text" :id="'modal-ccexpireyear-' + editableOrderData.IDOrder"
                        v-model="editableOrderData.CC_ExpireYear"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    </div>
                    <!-- CC_VerificationNumber -->
                    <div class="md:col-span-2 lg:flex-grow lg:min-w-0"> <!-- Su schermi grandi, larghezza fissa -->
                      <label :for="'modal-ccverificationnumber-' + editableOrderData.IDOrder"
                        class="block mb-1 text-xs font-medium text-gray-900 dark:text-white">Codice di Verifica</label>
                      <input type="text" :id="'modal-ccverificationnumber-' + editableOrderData.IDOrder"
                        v-model="editableOrderData.CC_VerificationNumber"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
                    </div>
                  </div>
                </div>
              </div>
              <!-- Aggiungi qui altri campi della tabella Orders -->
            </div>
          </div>
        </div>

        <!-- Pulsanti Salva e Annulla del form -->
        <div class="flex justify-end space-x-4 mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
          <button type="button" @click="closeModal"
            class="px-5 py-2.5 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
            Annulla
          </button>
          <button type="submit"
            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            Salva Modifiche
          </button>
        </div>
      </form>
      <div v-else class="text-gray-600 dark:text-gray-400 flex-grow flex items-center justify-center">
        Nessun ordine selezionato da visualizzare.
      </div>

      <!-- Controlli di navigazione nel modal (fuori dal form di salvataggio) -->
      <div class="flex justify-between mt-4">
        <button @click="prevOrder" :disabled="selectedOrdersArray.length <= 1"
          class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 disabled:opacity-50 disabled:cursor-not-allowed dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
          Indietro
        </button>
        <span class="text-gray-700 dark:text-gray-300 flex items-center">
          {{ selectedOrdersArray.length > 0 ? currentOrderIndex + 1 : 0 }} di {{ selectedOrdersArray.length }}
        </span>
        <button @click="nextOrder" :disabled="selectedOrdersArray.length <= 1"
          class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed">
          Avanti
        </button>
      </div>
    </div>
  </div>
</template>