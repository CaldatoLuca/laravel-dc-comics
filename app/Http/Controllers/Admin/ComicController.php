<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ComicRequest;
use App\Models\Comic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ComicController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //ottenfo tutti i dati dal db
        $comics = Comic::all();

        //ritorno una view (pagina index nella cartella comics, a cui passo i dati ottenuti prima)
        return view('comics.index', compact('comics'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('comics.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ComicRequest $request)
    {
        //validazione dei dati inseriti dall'utente
        //metodo built in
        // $request->validate([
        //     'title' => 'required|string|max:100',
        //     'description' => 'required|string|max:1000',
        //     'thumb' => 'required|string|url|ends_with:png,jpg,webp|max:500',
        //     'price' => 'required|numeric|between:0,9999.99',
        //     'series' => 'required|string|max:100',
        //     'sale_date' => 'required|date',
        //     'type' => 'required|string|max:50',
        // ]);

        // $data = $request->all();

        //richiamo il metodo validator creato e ne salvo dentro i dati
        //valido e salvo nella stessa operazione

        // $data = $this->validateData($request->all());

        //Form request 

        $data = $request->validated();

        $comic = new Comic();

        // $comic->title =  $data['title'];
        // $comic->description =  $data['description'];
        // $comic->thumb =  $data['thumb'];
        // $comic->price =  $data['price'];
        // $comic->series =  $data['series'];
        // $comic->sale_date =  $data['sale_date'];
        // $comic->type =  $data['type'];

        $comic->fill($data);

        $comic->save();

        return redirect()->route('comics.show', $comic->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(Comic $comic)
    {
        //passo un istanza comic alla comics.show e laravel recupera l id e mostra il comic giusto
        return view('comics.show', compact('comic'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comic $comic)
    {
        return view('comics.edit',  compact('comic'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ComicRequest $request, Comic $comic)
    {
        //richiamo il metodo per fare validazione
        // $data = $this->validateData($request->all());

        //from request
        $data = $request->validated();

        //evita riassegnazione e save
        $comic->update($data);

        return redirect()->route('comics.show', $comic->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comic $comic)
    {
        $comic->delete();

        return redirect()->route('comics.index');
    }

    /**
     * My validator function
     */
    // private function  validateData($data)
    // {
    //     //creo un validator importanto Validator
    //     //passo 3 argomenti: dati, validazioni e messaggi di errore personalizzati
    //     //nei messaggi di errore posso usare :attribute o :max per richiamare il valore messo dall' utente
    //     //alla fine valido il tutto e rwturno il validator
    //     $validator = Validator::make($data, [
    //         'title' => 'required|string|max:100',
    //         'description' => 'required|string|max:1000',
    //         'thumb' => 'required|string|url|max:500',
    //         'price' => 'required|numeric|between:0,9999.99',
    //         'series' => 'required|string|max:100',
    //         'sale_date' => 'required|date',
    //         'type' => 'required|string|max:50',
    //     ], [
    //         'required' => "Il campo :attribute è richiesto.",
    //         'string'  => "Il campo :attribute deve essere un testo.",
    //         'thumb.url' => "L'url non ha un formato valido",
    //         'max' => [
    //             'string' => "La lunghezza del campo :attribute non può superare i
    //             :max caratteri."
    //         ],
    //         'numeric' => "Il campo :attribute deve contenere solo numeri.",
    //         'price.between' =>  "Il prezzo deve essere compreso tra 0 e 9999.99",
    //         'sale_date' => "La data non ha un formato valido"
    //     ])->validate();

    //     return $validator;
    // }
}
