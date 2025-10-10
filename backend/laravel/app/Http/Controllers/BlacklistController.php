<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rules\IsRegex;
use App\Blacklist;

class BlacklistController extends Controller
{
    public function index()
    {
        $entries = Blacklist::get();

        return view('blacklist.index');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'regex' => ['required', new IsRegex],
        ]);

        $blacklist_entry = new Blacklist;
        $blacklist_entry->regex = $request->regex;
        $blacklist_entry->store();

        return redirect()->route('blacklist');
    }

    public function updateAll(Request $request)
    {
        $this->validate($request, [
            'entries' => 'required|array',
            'entries.*.id' => 'required|distinct',
            'entries.*.regex' => ['required', new IsRegex],
        ]);

        foreach ($request->entries as $entry) {
            Blacklist->where('id', $entry->id)
                     ->update(['regex' => $entry->regex]);
        }

        return redirect()->route('blacklist');
    }

    public function delete(Blacklist $blacklist)
    {
        $blacklist->delete();

        return redirect()->route('blacklist');
    }
}
