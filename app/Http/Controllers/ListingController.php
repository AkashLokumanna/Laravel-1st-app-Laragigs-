<?php

namespace App\Http\Controllers;
use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ListingController extends Controller
{

    // show all listing
    public function index() {
        return view('listings.index',  [
            'listings' => Listing:: latest()->filter(request(['tag', 'search']))
            ->paginate(6) 
               
        ]);
    }

// show single listing
    public function show(Listing $listing) {

        return view('listings.show', [
            'listing' => $listing
       ]);  
    }

// show create form

  public function create() {
        return view('listings.create');
    }

    

// store listings data

public function store(Request $request) {
   $formFields = $request->validate([
    'title' => 'required',
    'company' => ['required', Rule::unique('listings','company')],
    'location'=> 'required',
    'website' => 'required',
    'email' => ['required','email'],
    'tags' => 'required',
    'description' => 'required',
    
   ]);

    if($request->hasFile('logo')) {
        $formFields['logo'] = $request->file('logo')->store('logos', 'public');
    }

   // Add the authenticated user's ID to the listing data
     $formFields['user_id'] = Auth::id();
   

     // Insert into the database

    Listing::create($formFields);
   
    return redirect('/')->with('message', 'Your listing has been created successfully!');
    
 
}



// show edit form 
    public function edit(Listing $listing) {

        return view('listings.edit', ['listing'=> $listing]);
 
}

// update listings data

public function update(Request $request, Listing $listing) {
    
    //Make sure logged in user is owner of the listing
    if ($listing->user_id != Auth::id()) {
        abort(403, 'Unauthorized Action.');
    }

    $formFields = $request->validate([
     'title' => 'required',
     'company' => ['required',],
     'location'=> 'required',
     'website' => 'required',
     'email' => ['required','email'],
     'tags' => 'required',
     'description' => 'required',
     
    ]);
 
     if($request->hasFile('logo')) {
         $formFields['logo'] = $request->file('logo')->store('logos', 'public');
     }
 
 
     $listing->update($formFields);
    
     return back()->with('message', 'Your listing has been updated successfully!');
     
 }


  //delete listing 

public function destroy(Listing $listing) {
    //Make sure logged in user is owner of the listing
    if ($listing->user_id != Auth::id()) {
        abort(403, 'Unauthorized Action.');
    }
    
    $listing->delete();
    return redirect('/')->with('message', 'Your listing has been deleted successfully!');
}

// Manage Listings
public function manage() {
    $listings = Auth::user()->listings; // This should return a collection
    return view('listings.manage', compact('listings'));

}
// public function manage() {
//     return view('listings.manage', ['listings' => Listing::where('user_id', Auth::id())->user()->listings()->get()]);
// }


}