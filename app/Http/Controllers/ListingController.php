<?php

namespace App\Http\Controllers;

use App\Models\Listing as ModelsListing;
use App\Http\Requests\ListingRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ListingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $queryBuilder = ModelsListing::latest();

        if (request()->has('tag')) {
            $queryBuilder->where('tags', 'like', '%' . request('tag') . '%');
        }

        if (request()->has('search')) {
            $queryBuilder->where('title', 'like', '%' . request('search') . '%')
                ->orWhere('description', 'like', '%' . request('search') . '%')
                ->orWhere('tags', 'like', '%' . request('search') . '%');
        }

        $listings = $queryBuilder->simplePaginate(6);

        return view('listings/index')->with('listings', $listings);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('listings/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ListingRequest $request)
    {
        $listing = new ModelsListing;

        $listing->company = $request->input('company');
        $listing->user_id = Auth::user()->id;
        $listing->title = $request->input('title');
        $listing->location = $request->input('location');
        $listing->email = $request->input('email');
        $listing->website = $request->input('website');
        $listing->tags = $request->input('tags');
        $listing->description = $request->input('description');

        $res = $listing->save();

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = $listing->id . '.' . $file->extension();
            $result = $file->storeAs('logos', $filename, 'public');
            $listing->logo = $result;
            $listing->save();
        }

        $message = $res ? 'Listing created successfully!' : 'Error on creation of listing';

        session()->flash('message', $message);

        return redirect('/');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ModelsListing $listing)
    {
        return view('listings/show')->with('listing', $listing);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(ModelsListing $listing)
    {
        return view('listings/edit')->with('listing', $listing);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ListingRequest $request, ModelsListing $listing)
    {
        if ($listing->user_id !== Auth::user()->id) {
            return abort(403, 'Unathorized action');
        }

        $listing->company = $request->input('company');
        $listing->title = $request->input('title');
        $listing->location = $request->input('location');
        $listing->email = $request->input('email');
        $listing->website = $request->input('website');
        $listing->tags = $request->input('tags');
        $listing->description = $request->input('description');

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = $listing->id . '.' . $file->extension();
            $result = $file->storeAs('logos', $filename, 'public');
            $listing->logo = $result;
        }

        $res = $listing->save();

        $message = $res ? 'Listing updated successfully!' : 'Error on updating of listing';

        session()->flash('message', $message);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ModelsListing $listing)
    {
        if ($listing->user_id !== Auth::user()->id) {
            return abort(403, 'Unathorized action');
        }

        $res = $listing->delete();

        if ($res && $listing->logo && Storage::disk('public')->exists($listing->logo)) {
            Storage::disk('public')->delete($listing->logo);
        }

        $message = $res ? "Listing has been removed" : "Error on removing operation";

        session()->flash('message', $message);

        return redirect('/');
    }

    public function userListings()
    {
        $listings = ModelsListing::latest()
            ->where('user_id', Auth::user()->id)
            ->get();

        return view('listings/manage')->with('listings', $listings);
    }
}
