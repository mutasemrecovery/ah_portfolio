<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::orderBy('sort_order')->get();
        return view('admin.clients.index', compact('clients'));
    }

    public function create()
    {
        return view('admin.clients.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name_en'     => 'required|string|max:200',
            'name_ar'     => 'required|string|max:200',
            'logo'        => 'nullable|image|max:2048',
            'website_url' => 'nullable|url|max:300',
            'sort_order'  => 'nullable|integer|min:0',
        ]);

        $data = $request->only(['name_en', 'name_ar', 'website_url', 'sort_order']);
        $data['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('logo')) {
            $data['logo'] = 'uploads/clients/' . uploadImage('uploads/clients', $request->file('logo'));
        }

        Client::create($data);

        return redirect()->route('admin.clients.index')->with('success', 'تم إضافة العميل بنجاح.');
    }

    public function edit(Client $client)
    {
        return view('admin.clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        $request->validate([
            'name_en'     => 'required|string|max:200',
            'name_ar'     => 'required|string|max:200',
            'logo'        => 'nullable|image|max:2048',
            'website_url' => 'nullable|url|max:300',
            'sort_order'  => 'nullable|integer|min:0',
        ]);

        $data = $request->only(['name_en', 'name_ar', 'website_url', 'sort_order']);
        $data['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('logo')) {
            $data['logo'] = 'uploads/clients/' . uploadImage('uploads/clients', $request->file('logo'));
        }

        $client->update($data);

        return redirect()->route('admin.clients.index')->with('success', 'تم تحديث العميل بنجاح.');
    }

    public function destroy(Client $client)
    {
        $client->delete();
        return back()->with('success', 'تم حذف العميل.');
    }
}
