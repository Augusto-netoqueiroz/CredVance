<?php
// app/Http/Controllers/Admin/DocArticleController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DocArticle;
use App\Models\DocSection;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DocArticleController extends Controller
{
    public function index()
    {
        $articles = DocArticle::with('section')->orderBy('ordem')->get();
        return view('admin.docs.articles.index', compact('articles'));
    }

    public function create()
    {
        $sections = DocSection::orderBy('ordem')->get();
        return view('admin.docs.articles.create', compact('sections'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'section_id' => 'required|exists:doc_sections,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'ordem' => 'nullable|integer',
        ]);

        $data['slug'] = Str::slug($data['title']);
        DocArticle::create($data);

        return redirect()->route('articles.index')->with('success', 'Artigo criado com sucesso.');
    }

    public function edit(DocArticle $article)
    {
        $sections = DocSection::orderBy('ordem')->get();
        return view('admin.docs.articles.edit', compact('article', 'sections'));
    }

    public function update(Request $request, DocArticle $article)
    {
        $data = $request->validate([
            'section_id' => 'required|exists:doc_sections,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'ordem' => 'nullable|integer',
        ]);

        $data['slug'] = Str::slug($data['title']);
        $article->update($data);

        return redirect()->route('articles.index')->with('success', 'Artigo atualizado com sucesso.');
    }

    public function destroy(DocArticle $article)
    {
        $article->delete();
        return back()->with('success', 'Artigo excluído com sucesso.');
    }
}
