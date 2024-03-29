
# Creazione di un progetto CRUD

Spiegazione e esercitazione passo passo di un progetto in cui implementare 

- Migration
- Model
- Seeder
- Resource Controller per le CRUD
- Validation


## Migration

La migration ci permette di lavorare sul db presente, in questo caso, su phpMyAdmin.

Questo sarà possibile se il file .env conterrà il collegamento al db con relativa porta e password.

### Creazione di una Migration 

Per creare una migration è necessario avere un db a cui fare riferimento: __creo il db su phpMyAdmin__.

Successivamente si può creare la migration: __'lancio il comando'__ `php artisan make:migration nome_della_migration` create_comics_table (seguendo le convenzioni)

Definisco le operazioni della migration: __inserisco i campi che riempiranno la tabella__

### Modifiche o Update

Per modificare il dato si può creare una nuova migration lanciando il comando: `php artisan make:migration update_nometabella_table --table=nometabella`

Per controllare lo stato delle migrazioni lancio il comando: `php artisan migrate:status`

### Metodi up e down

Per eseguire una migration si lancia il comando: `php artisan migrate` che eseguirà il codice contenuto nella funzione up

Per ritornare allo stato precedente si lancia il comando: `php artisan migrate:rollback` che eseguirà il codice contenuto in down (sempre antagonista a up)
## Model

Il Model è La classe che mappa le tabelle del database e rappresentano la struttura della tabella.

Ogni tabella del nostro database sarà mappata con un modello Laravel che ci permetterà facilmente di leggerne il contenuto, crearlo, aggiornarlo o cancellarlo.

Questa modalità prende il nome di ORM (Object Relationship Mapping) e serve ad ottenere,
attraverso un’interfaccia orientata agli oggetti, tutti i servizi inerenti alla persistenza del dato.

Ogni tabella del database verrà quindi mappata in una classe PHP che faciliterà la gestione, la scrittura e la lettura dei dati, attraverso dei metodi forniti dall’ORM stesso.

### Creazione di un Model

Per creare un model lancio il comando: `php artisan make:model NomeModel` dove il nome è singolare e in pascaCase: se ho una tabella comics il suo model sarà Comic.
## Seeder

I Seeder ci permettorno di popolare le tabelle coi dati desiderati

### Creazione di un Seeder

Per creare un Seeder lancio il comando: `php artisan make:seeder NometabellaTableSeeder`

### Utilizzo del Seeder

Al suo interno troviamo al funzione run dove andremo a a inserire la logica per popolare la tabella.

Le operazioni obbligatorie sono creare una nuova istanza della tabella, richaimando il Model e salvarla sul db. Nel mezzo si inseriscono i dati.

Nel mio caso includo un file contenente un array su cui ciclare per riempire la tabella.

### Eseguire un Seeder

Per eseguire un Seeder lancio il comando: `php artisan db:seed --class:NometabellaTableSeeder`
## CRUD

CRUD è un acronimo che sta per: 

- Create: creare un dato da inserire nel db
- Read: leggere/visualizzare tutti i dati presenti nel db
- Update: aggiornare/modificare i dati del db
- Delete: cancellare dati dal db

Queste sono le operazioni base (minimo necessario) per ogni applicativo.

Alle CRUD è applicata l' architettura REST (REpresentational State Transfer) che prevede una struttura ben definita per scambiare risorse tra client e server:

- una struttura degli URL ben definita e univoca per ogni risorsa

- l'utilizzo dei metodi HTTP per azioni specifiche sulle risorse:
   - __GET__ per il recupero dei dati
   - __POST__ per la creazione di nuovi dati
   - __PUT/PATCH__ per l' aggiornamento dei dati
   - __DELETE__ per la cancellazione dei dati

## Resource Controller

Laravel ci permette di creare un Controller con integrate funzioni e rotte per le CRUD.

### Creazione di un Resource Controller

Per creare un Resource Controller lancio il comando: `php artisan make:controller --resource NomeController` usando la naming convenction e un eventuale divisione di controller tra admin e user vado a chiamarlo Admin/ComicController.

Al suo interno troveremo le funzioni che gestiscono le CRUD:

- index --> mostra tutte le risorse
- create --> crea una nuova risorsa (avrà un form) 
- store --> memorizza nel db la risorsa creata da crate 
- show --> mostra una risorsa specifica (pagina di dettaglio)
- edit --> modifica una risorsa (avrà un form)
- update --> salva la risorsa modifica da edit nel db
- destroy --> rimuove una risorsa specifica

### Gestione delle rotte con un Resource Controller

Un altra comodità è quella di avere molte rotte disponibili per visualizzare le pagine relative alle CRUD.

Invece di creare una rotta specifica per ogni pagina basterà scrivere il seguente comando codice in web: `Route::resource('nomerotta', NomeController::class);`

In questo modo dando un nome solo alla rotta principale (comics nel mio caso) Laravel creerà una serie di rotte da usare.

Per visualizzare le rotte a disposizione lancio il comando: __'php artisan route:list'__

### Index, visualizzare la lista delle risorse

Nel metodo index vado a raccogliere tutti i dati presenti nel db: `$comics = Comic::all();`

Questi poi vengono passati alla view (per convenzione di nome index in una cartella comics):`return view('comics.index', compact('comics'));`

Ora nella pagina index.blade.php ho a disposizione tutti i dati per fare un foreach e mostrare le classiche card di ogni prodotto.

### Show, mostra il dettaglio della risorsa

Al metodo show passo un istanza del model Comic(Dependency injection): `public function show(Comic $comic)` se passassi l' id dovrei usare il metodo find, passando un instanza avviene lo stesso ma sotto al cofano

Al return passo l' istanza creata: `return view('comics.show', compact('comic'));`

Poi nell mia index creo un link, dentro al foreach, che rimanda alla pagina show: `<a href="{{ route('comics.show', $comic->id) }}">view details</a>`  a cui passo l' id

### Create, creazione di una nuova risorsa

Nel metodo create vado solo a inserire un return che rimanda alla pagina create del sito.

Dentro questa pagina sarà presente un form dove inserire in campi di un nuovo dato, rispettando le informazioni date al db con le migrations.

Il form avrà come action la rotta per store, per salvare la risorsa: `action="{{ route('comics.store') }}"` 

Il metodo sarà post: `method="POST"`

Subito dopo il form sarà necessario inserire un token che genera Laravel per assicurarsi che la chiamata post avvenga tramite un form del sito: `@csrf`

Infine i name dei campi input devono essere uguali a quelli dati nel db.

### Store, salviamo il nuovo comic

Questo metodo non necessita di una view perchè farà un return allo show del comic appena creato: `return redirect()->route('comics.show', $comic->id);` è necessario passare l' id della nuova istanza per visualizzare i dettagli.

Grazie a una request prende i dati inseriti, li salviamo in una variabile: `$data = $request->all();`

Creiamo poi una nuova istanza, inseriamo i dati e la salviamo: 

`$comic = new Comic();`

`$comic->title =  $data['title'];`

`$comic->save();`

Se i campi hanno tutti lo stesso nome possiamo non salvarli uno ad uno ma usare il metodo fill e poi salvare: `$comic->fill($data);`

### Edit, modifichiamo un dato esistente

Funziona similmente a create e store,

Nel metodo edit, tramite Dependency Ingection, passiamo solo l' istanza comic desiderata; andiamo quindi a dare l' id al link che richiama questa rotta: `return view('comics.edit',  compact('comic'));` e `<a href="{{ route('comics.edit', $comic->id) }}">Edit</a>`

Nella view edit inseriamo un form, nel mio caso identico a quello in create.

Il form dovrà avere i campi precompilati: `value="{{ $comic->title }}"`

Come action la rotta update con id: `action="{{ route('comics.update', $comic->id) }}"`

Come metodo POST che però andiamo a cambiare in PUT: `@method('PUT')`

NB 

PUT è usato per modificare tutta la risorsa

PATCH è usato per modificare un campo

### Update, salviamo l' edit

Come per lo store salviamo tutti i dati con request: `$data = $request->all();`

Utilizziamo il metodo update, che riassegna i valori e li salva per noi: `$comic->update($data);` simile a fill ma senza save

Returniamo un redirect alla show, passando l' id: `return redirect()->route('comics.show', $comic->id);`

Dobbiamo però specificare quali campi possono cambiare con un mass update, vado nel model e ho 2 opzioni:

- fillable: `protected $fillable = ['title', 'description', 'thumb', 'price', 'series', 'sale_date', 'type'];` indico i campi autorizzati

- guarded: `$guarded = [];` indico i campi non autorizzati - questa è piu comoda

NB Questo va a influenzare anche lo store

### Delete, cancelliamo una risorsa

Sempre garzie alla Dependency Ingection andiamo ad agire sull' istanza voluta, questo perchè nella index passiamo l' id al link: `$comic->delete();`

Per comodità facciamo un redirecr alla index

Nella index per ogni elemento devoaggiungere un form con solo un bottone, a cui date il metodo DELETE:

```html
    <form action="{{ route('comics.destroy', $comic->id) }}" method="POST">
         @csrf

        {{-- aggiungo il metodo delete --}}
        @method('DELETE')

         <button type="submit" class="btn btn-danger">Delete</button>
    </form>
```
## Validation

Esistono tre metodi per fare validazione, il terzo è il migliore per divisione di file e logica.

### Validazione dentro store e update

Tramite il metodo validai valido i dati dando dei valori all' array associativo, a seconda dei campi inseriti metto la validazione voluta.

```php
$request->validate([
    'title' => 'required|string|max:100',
    'thumb' => 'required|string|url|ends_with:png,jpg,webp|max:500',
]);

$data = $request->all();
```

### Validazione tramite funzione built in nel Controller

Creo una funzione e esterna e la richiamo in store e update quando salvo i dati, nel secodo array personalizzo gli errori.

```php
private function  validateData($data)
{
    $validator = Validator::make($data, [
        'title' => 'required|string|max:100',
        'description' => 'required|string|max:1000',
        'price' => 'required|numeric|between:0,9999.99',
        'type' => 'required|string|max:50',
    ], [
        'required' => "Il campo :attribute è richiesto.",
        'string'  => "Il campo :attribute deve essere un testo.",
        'max' => [
                 'string' => "La lunghezza del campo :attribute non può superare i
                 :max caratteri."
             ],
        'price.between' =>  "Il prezzo deve essere compreso tra 0 e 9999.99",
    ]
}

// in store e update salvo e richiamo la funzione assieme
$data = $this->validateData($request->all());
```

### Creazione di un Form Request

Crea un Form Request atto a fare validazione, nel mio caso uso lo stesso sia per store che per update, si crea con il seguente comando: `php artisan make:request NomeRequest`

Funzione per autorizzazione delle operazioni
```php
    public function authorize(): bool
    {
        //di base è false-> non autorizzato
        return true;
    }
```

Funzione per la validazione
```php
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:100',
            'description' => 'required|string|max:1000',
            'thumb' => 'required|string|url|max:500',
            'price' => 'required|numeric|between:0,9999.99',
            'series' => 'required|string|max:100',
            'sale_date' => 'required|date',
            'type' => 'required|string|max:50',
        ];
    }
```

Funzione per personalizzare gli errori
```php
    public function messages()
    {
        return [
            'required' => "Il campo :attribute è richiesto.",
            'string'  => "Il campo :attribute deve essere un testo.",
            'thumb.url' => "L'url non ha un formato valido",
            'max' => [
                'string' => "La lunghezza del campo :attribute non può superare i
                         :max caratteri."
            ],
            'numeric' => "Il campo :attribute deve contenere solo numeri.",
            'price.between' =>  "Il prezzo deve essere compreso tra 0 e 9999.99",
            'sale_date' => "La data non ha un formato valido"
        ];
    }
```

Nello store e update salvo cosi i dati: `$data = $request->validated();`

Cambio però la classe che richiama $request: `(ComicRequest $request)`

### Gestione errori nel form

Per vedere gli errori si usa la variabile di Laravel di istanza `$errors`.

```php
    @if ($errors->any())
        <h3 class="text-danger">Something went wrong</h3>
        <div class="alert alert-danger">
            <ul class="list-group mb-0 ">
                @foreach ($errors->all() as $error)
                    <li class="list-group-item bg-danger-subtle border-0">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
```

Per vedere il tipo di errore sotto al campo input e non dove faccio il ciclo devo dare una classe di style (is-invalid) se c'è errore: `@error('title') is-invalid @enderror"`

```php
                <input type="text" class="form-control @error('title') is-invalid @enderror" id="comic-title"
                    aria-describedby="basic-addon3 basic-addon4" name="title" value="{{ old('title') }}" required>
```

Sotto poi visualizzo l' errore:

```php
            @error('title')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
```

Per tenere il valore inserito nel form uso il metodo old, questo è utile perchè dopo aver compilato tutto il form e non valida solo un campo posso tenere compilato tutto e modificare solo l' errore.

Nel create metto old nel value: `value="{{ old('title') }}"`

In edit do anche un valore di default, il valore del campo da editare: `value="{{ old('title', $comic->title) }}"`
