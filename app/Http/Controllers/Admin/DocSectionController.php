<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DocSection;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DocSectionController extends Controller
{
    public function index()
    {
        $sections = DocSection::orderBy('ordem')->get();
        return view('admin.docs.sections.index', compact('sections'));
    }

    public function create()
    {
        return view('admin.docs.sections.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'icon' => 'nullable|string|max:50',
            'ordem' => 'nullable|integer',
        ]);

        $data['slug'] = Str::slug($data['title']);
        DocSection::create($data);

        return redirect()->route('sections.index')->with('success', 'Seção criada com sucesso.');
    }

    public function edit(DocSection $section)
    {
        return view('admin.docs.sections.edit', compact('section'));
    }

    public function update(Request $request, DocSection $section)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'icon' => 'nullable|string|max:50',
            'ordem' => 'nullable|integer',
        ]);

        $data['slug'] = Str::slug($data['title']);
        $section->update($data);

        return redirect()->route('sections.index')->with('success', 'Seção atualizada com sucesso.');
    }

    public function destroy(DocSection $section)
    {
        $section->delete();
        return back()->with('success', 'Seção excluída com sucesso.');
    }
}
