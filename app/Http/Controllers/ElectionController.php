<?php

namespace App\Http\Controllers;

use App\Models\Election;

use Illuminate\Http\Request;

class ElectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     public function ListeElectionConfondu()

     {

         return view('election.liste');
     }

    public function index()
    {

        return view('election.ajouter');
    }

    public function liste(Request $request)
    {
        $filter = $request->get('filter', null);
        if (!isset($filter)) {
            return back();
        }
        $date = now();
        $query = Election::query();
        if ($filter == 'encour') {
            $query->where('datedebut', '<=', $date)->where('datefin', '>=', $date);
            $type = 'En cours';
        } else if ($filter == 'terminer') {
            $query->where('datefin', '<', $date);
            $type = 'Teminer';
        } else if ($filter == 'avenir') {
            $query->where('datedebut', '>', $date);
            $type = 'A venir';
        } else {
            return back();
        }
        $elections = $query->get();
        return view('election.index', compact('elections', 'type'));
    }



    /**
     * Show the form for creating a new resource.
     */
    public function ResultatElection()
    {
            return view('election.resul');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'datedebut' => 'required|date|before_or_equal:datefin',
            'datefin' => 'required|date',
        ]);
        $election = new Election();
        $election->faculte = $request->faculte;
        $election->filiere = $request->filiere;
        $election->niveau = $request->niveau;
        $election->datedebut = $request->datedebut;
        $election->datefin = $request->datefin;
        $election->statuts = $request->statuts;

        $election->save();

        return redirect()->back()->with('success', 'Election enregistrée avec succes.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $election = Election::find($id);
        return view('election.editer', compact('election'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'datedebut' => 'required|date|before_or_equal:datefin',
            'datefin' => 'required|date',
        ]);
        $election = Election::find($id);

        $election->faculte = $request->faculte;
        $election->filiere = $request->filiere;
        $election->niveau = $request->niveau;
        $election->datedebut = $request->datedebut;
        $election->datefin = $request->datefin;
        $election->statuts = $request->statuts;

        $election->update();
        return to_route('index.election')->with('success', "Election modifier avec succès");
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $election = Election::find($id);
        $election->delete();
        return back();
    }



}
