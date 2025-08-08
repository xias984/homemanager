# Paginazione e Ordinamento delle Tabelle

## Panoramica

Sono state integrate le funzionalità di paginazione e ordinamento in tutte le tabelle principali del sistema HomeManager. Queste funzionalità migliorano significativamente l'esperienza utente quando si lavora con grandi quantità di dati.

## Funzionalità Implementate

### 1. Paginazione
- **Elementi per pagina**: Configurabile (5, 10, 25, 50 elementi)
- **Navigazione**: Link "Precedente" e "Successivo"
- **Numeri di pagina**: Visualizzazione intelligente con ellipsis (...)
- **Riepilogo**: Mostra "Mostrando X - Y di Z elementi"

### 2. Ordinamento
- **Colonne ordinabili**: Tutte le colonne tranne "Actions"
- **Direzione**: ASC/DESC con icone visuali
- **Tipi di dati**: Supporto per testo, numeri e valute
- **Persistenza**: Mantiene l'ordinamento durante la navigazione

### 3. Controlli Utente
- **Selettore elementi per pagina**: Dropdown per cambiare il numero di elementi
- **Intestazioni cliccabili**: Con icone di ordinamento
- **Responsive**: Funziona su dispositivi mobili e desktop

## Tabelle Aggiornate

### 1. Lista Utenti (`listusers.php`)
- Paginazione e ordinamento per nome, cognome, email, admin
- Mantiene le funzionalità di editing inline

### 2. Categorie Finanziarie (`financecategory.php`)
- Paginazione e ordinamento per categoria, utente, data
- Mantiene le funzionalità di editing inline

### 3. Metodi di Pagamento (`financepayment.php`)
- Paginazione e ordinamento per metodo, utente, data
- Mantiene le funzionalità di editing inline

### 4. Prospetto Finanziario (`financeprospect.php`)
- Paginazione e ordinamento per data, tipo, categoria, importo
- Mantiene tutte le funzionalità di editing e azioni
- Gestione complessa dei dati originali per le azioni

### 5. Analisi PriceWatcherBot (`analisipwb.php`)
- Paginazione e ordinamento per nome prodotto, prezzo, ASIN, categoria
- Mantiene i link esterni ai prodotti

## Implementazione Tecnica

### Classe TableHelper
La classe `TableHelper` gestisce tutta la logica di paginazione e ordinamento:

```php
// Inizializzazione
$tableHelper = new TableHelper($data, $itemsPerPage);
$paginatedData = $tableHelper->getPaginatedData();

// Controlli
echo $tableHelper->getPaginationSummary();
echo $tableHelper->getItemsPerPageSelector();
echo $tableHelper->createPaginationLinks();

// Intestazioni ordinabili
echo $tableHelper->createSortableHeader($columnIndex, $columnName);
```

### Parametri URL
- `page`: Numero di pagina corrente
- `sort`: Indice della colonna da ordinare
- `direction`: Direzione ordinamento (ASC/DESC)
- `itemsPerPage`: Numero di elementi per pagina

### JavaScript
Funzione `changeItemsPerPage()` per gestire il cambio dinamico degli elementi per pagina.

## Stili CSS
Sono stati aggiunti stili personalizzati per:
- Paginazione Bootstrap
- Intestazioni ordinabili
- Hover effects
- Colori coerenti con il tema

## Compatibilità
- **Browser**: Tutti i browser moderni
- **Responsive**: Funziona su mobile e desktop
- **Backward Compatibility**: Non rompe le funzionalità esistenti

## Manutenzione
Per aggiungere paginazione e ordinamento a nuove tabelle:

1. Includi `TableHelper.class.php`
2. Inizializza `TableHelper` con i dati
3. Usa `getPaginatedData()` per i dati
4. Aggiungi i controlli di paginazione
5. Usa `createSortableHeader()` per le intestazioni
6. Aggiungi i link di paginazione

## Note
- Le colonne "Actions" non sono ordinabili per design
- L'ordinamento rimuove automaticamente i tag HTML
- I numeri con valuta sono ordinati correttamente
- La paginazione mantiene tutti i parametri URL esistenti 