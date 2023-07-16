<?php

namespace App\Http\Controllers;
use App\Models\Candidat;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;


class CandidatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       return view('candidat.add',['candidats' => Candidat::get()]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function liste()

    {
        $candidat = Candidat::paginate(10);

        return view('candidat.index', compact("candidat"));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom'=> 'required',
            'prenom'=> 'required',
            'DateNaissance'=> 'required',
            //'photo'=> 'required|mimes:jpeg,png,gif,bmp,tiff,raw,svg|max:2000 ',
            'matricule'=> 'required',
            'faculte'=> 'required',
            'filiere'=> 'required',
            'niveau'=> 'required',
            'motivation'=> 'required',
        ]);
        //dd($request);

        //upload image
        if ($request->hasFile('photo')) {
            $imageName = time(). '.' .$request->photo->getClientOriginalExtension();
        } else {
            // handle the error here
        }

        if ($request->hasFile('photo')) {
            $request->photo->move(public_path('candidats'), $imageName);
        } else {
            // handle the error here
        }


        $candidat = new Candidat();
        $candidat->nom =$request->nom;
        $candidat->prenom =$request->prenom;
        $candidat->DateNaissance =$request->DateNaissance;
        $candidat->photo=$request->photo;
        $candidat->matricule =$request->matricule;
        $candidat->faculte =$request->faculte;
        $candidat->filiere =$request->filiere;
        $candidat->niveau =$request->niveau;
        $candidat->motivation =$request->motivation;
        $candidat->cptVote = 0;
        $candidat->save();

        return redirect()->back()->with('success','Candidature soumise avec succes.');


    }


/*     public function vote(Request $request, $id)
{
    // Get the candidate by ID
    $candidat = Candidat::findOrFail($id);

    // Increase the vote count by 1
    $candidat->cptVote++;

    // Save the updated candidate
    $candidat->save();

    // Create a vote record in the database
    Vote::create([
        'candidat_id' => $candidat->id,
        'voter_id' => $request->user()->id, // Assuming the user is authenticated
    ]);

    return redirect()->back()->with('success', 'Vote enregistré avec succès.');
} */

public function vote(Request $request, $level)
{
    // Get the candidates of the specified level
    $candidates = Candidat::where('niveau', $level)->get();

    // Display the voting form for the candidates of the specified level
    return view('vote.vote', compact('candidates','level'));
}

public function castVote(Request $request)
{
    // Validate the submitted form data
    $request->validate([
        'candidate_id' => 'required|exists:candidats,id',
    ]);

    // Get the selected candidate
    $candidate = Candidat::findOrFail($request->input('candidate_id'));

    // Increase the vote count by 1
    $candidate->cptVote++;
    $candidate->save();

    // Create a vote record in the database
    Vote::create([
        'candidat_id' => $candidate->id,
        'voter_id' => $request->user()->id, // Assuming the user is authenticated
    ]);

    return redirect()->back()->with('success', 'Vote enregistré avec succès.');
}



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $candidat = Candidat::find($id);
        return view('candidat.editer', compact('candidat'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $candidat = Candidat::find($id);
        $candidat->nom =$request->nom;
        $candidat->prenom =$request->prenom;
        $candidat->DateNaissance=$request->DateNaissance;
        $candidat->matricule =$request->matricule;
        $candidat->faculte =$request->faculte;
        $candidat->filiere =$request->filiere;
        $candidat->niveau =$request->niveau;
        $candidat->photo=$request->photo;
        $candidat->motivation =$request->motivation;
        $candidat->cptVote = $request->cptVote;

        $candidat->update();
        return redirect()->route('index.candidat')->with('success',"Candidat modifier");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $candidat = Candidat::find($id);
        $candidat->delete();
        return back();
    }
}
