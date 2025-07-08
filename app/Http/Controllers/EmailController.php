<?php

namespace App\Http\Controllers;

use App\Mail\DynamicEmail;
use App\Models\BoletoLog;
use App\Models\Contrato;
use App\Models\Email;
use App\Models\EmailTemplate;
use App\Models\Pagamento;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;


class EmailController extends Controller
{
    // Listar templates com paginação
    public function listarTemplates()
    {
        $templates = EmailTemplate::paginate(10);
        return view('emails.templates.index', compact('templates'));
    }

    // Formulário para criar template
    public function criarTemplateForm()
    {
        return view('emails.templates.create');
    }

    // Salvar novo template
    public function salvarTemplate(Request $request)
    {
        $request->validate([
            'nome' => 'required',
            'tipo' => 'required',
            'assunto_padrao' => 'required',
            'corpo_html' => 'required',
        ]);

        EmailTemplate::create($request->only('nome', 'tipo', 'assunto_padrao', 'corpo_html'));

        return redirect()->route('emails.templates.index')->with('success', 'Template criado com sucesso!');
    }

    // Formulário para editar template
    public function editarTemplateForm(EmailTemplate $template)
    {
        return view('emails.templates.edit', compact('template'));
    }

    // Atualizar template
    public function atualizarTemplate(Request $request, EmailTemplate $template)
    {
        $request->validate([
            'nome' => 'required',
            'tipo' => 'required',
            'assunto_padrao' => 'required',
            'corpo_html' => 'required',
        ]);

        $template->update($request->only('nome', 'tipo', 'assunto_padrao', 'corpo_html'));

        return redirect()->route('emails.templates.index')->with('success', 'Template atualizado com sucesso!');
    }

    // Listar emails com paginação
    public function listarEmails()
    {
        $emails = Email::with('template')->orderBy('criado_em', 'desc')->paginate(20);
        return view('emails.index', compact('emails'));
    }

    // Formulário para criar email
    public function criarEmailForm()
    {
        $templates = EmailTemplate::where('ativo', 1)->get();
        return view('emails.create', compact('templates'));
    }

    // Salvar novo email
    public function salvarEmail(Request $request)
    {
        $request->validate([
            'email_template_id' => 'required|exists:email_templates,id',
            'email_destino' => 'required|email',
            'assunto' => 'required',
            // corpo_customizado e dados_json são opcionais
        ]);

        Email::create([
            'email_template_id' => $request->email_template_id,
            'email_destino' => $request->email_destino,
            'assunto' => $request->assunto,
            'corpo_customizado' => $request->corpo_customizado ?? null,
            'dados_json' => $request->dados_json ? json_decode($request->dados_json, true) : null,
            'status' => 'pendente',
            'agendado_em' => $request->agendado_em ?? now(),
        ]);

        return redirect()->route('emails.index')->with('success', 'Email criado e agendado!');
    }

    // Registrar abertura do email (tracking pixel)
    public function registrarAbertura($emailId, Request $request)
    {
        $email = Email::find($emailId);
        if (!$email) {
            abort(404);
        }

        if (!$email->visualizado_em) {
            $email->visualizado_em = now();
            $email->save();
        }

        $email->trackings()->create([
            'tipo_evento' => 'abertura',
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Retorna GIF transparente 1x1
        $gif = base64_decode('R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==');
        return response($gif)
            ->header('Content-Type', 'image/gif')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate');
    }

    // Mostrar detalhes do email
public function mostrarEmail(Email $email)
{
    $email->load('template', 'trackings'); // carrega relações para a view

    return view('emails.show', compact('email'));
}

public function excluirEmail(Email $email)
{
    // Aqui você pode adicionar verificação de permissão, se necessário

    $email->delete();

    return redirect()->route('emails.index')->with('success', 'Email excluído com sucesso!');
}


public function editarEmailForm(Email $email)
{
    // Pega templates ativos para o select no form
    $templates = EmailTemplate::where('ativo', 1)->get();

    return view('emails.edit', compact('email', 'templates'));
}

public function atualizarEmail(Request $request, Email $email)
{
    $request->validate([
        'email_template_id' => 'required|exists:email_templates,id',
        'email_destino' => 'required|email',
        'assunto' => 'required',
        // corpo_customizado e dados_json opcionais
    ]);

    $email->update([
        'email_template_id' => $request->email_template_id,
        'email_destino' => $request->email_destino,
        'assunto' => $request->assunto,
        'corpo_customizado' => $request->corpo_customizado ?? null,
        'dados_json' => $request->dados_json ? json_decode($request->dados_json, true) : null,
        'status' => $email->status,  // Mantém o status atual
        'agendado_em' => $request->agendado_em ?? $email->agendado_em,
    ]);

    return redirect()->route('emails.index')->with('success', 'Email atualizado com sucesso!');
}

 public function enviarEmail(Email $email)
    {
        $template = $email->template;

        // Decodifica dados_json ou cria array vazio
        $dados = json_decode($email->dados_json, true);
        if (!is_array($dados)) {
            $dados = [];
        }

        // Busca cliente pelo email_destino
        $cliente = User::where('email', $email->email_destino)->first();

        // Busca contrato do cliente (último contrato ativo)
        $contrato = null;
        if ($cliente) {
            $contrato = Contrato::where('cliente_id', $cliente->id)->latest()->first();
        }

        // Busca boleto/pagamento do contrato
        $boleto = null;
        if ($contrato) {
            $boleto = Pagamento::where('contrato_id', $contrato->id)->latest()->first();
        }

        // Passa os dados para o template
        $dados['email'] = $email;
        $dados['cliente'] = $cliente;
        $dados['contrato'] = $contrato;
        $dados['boleto'] = $boleto;

        // Renderiza o corpo HTML com Blade
        $bodyHtml = Blade::render($template->corpo_html, $dados);

        // Define caminho do boleto para anexo (verifica se boleto existe)
        $boletoPath = null;
        if ($boleto && !empty($boleto->boleto_path)) {
            $boletoPath = storage_path('app/private/' . $boleto->boleto_path);
            if (!file_exists($boletoPath)) {
                $boletoPath = null; // arquivo não existe, não anexa
            }
        }

        try {
            Mail::to($email->email_destino)
                ->send(new DynamicEmail($email->assunto, $bodyHtml, $boletoPath));

            // Atualiza status e data de envio
            $email->status = 'enviado';
            $email->enviado_em = now();
            $email->save();

            return ['message' => 'Email enviado com sucesso!', 'email_id' => $email->id];
        } catch (\Exception $e) {
            return ['message' => 'Erro ao enviar email: ' . $e->getMessage(), 'email_id' => $email->id];
        }
    }





public function enviarProximoEmail()
{
    $email = Email::where('status', 'pendente')->first();

    if (!$email) {
        return response()->json(['message' => 'Nenhum email pendente para enviar.']);
    }

    try {
        $this->enviarEmail($email);
        return response()->json([
            'message' => 'Email enviado com sucesso!',
            'email_id' => $email->id,
            'destino' => $email->email_destino
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Erro ao enviar email: ' . $e->getMessage(),
            'email_id' => $email->id,
        ], 500);
    }
}


public function pixelOpened($pagamentoId, Request $request)
    {
        // Buscar o log existente
        $log = BoletoLog::where('pagamento_id', $pagamentoId)->first();

        // Se não existir, criar um novo log com dados mínimos do pagamento
        if (!$log) {
            $pagamento = DB::table('pagamentos')->where('id', $pagamentoId)->first();

            if (!$pagamento) {
                // Se nem pagamento existir, retornar o pixel direto (evita erro)
                return $this->returnTransparentPixel();
            }

            $log = new BoletoLog();
            $log->pagamento_id = $pagamentoId;
            $log->contrato_id = $pagamento->contrato_id;
            $log->cliente_id = $pagamento->cliente_id;
            $log->enviado = false;
            $log->enviado_em = null;
            $log->aberto = false;
            $log->aberto_em = null;
        }

        // Atualizar dados de abertura caso ainda não tenha sido marcado como aberto
        if (!$log->aberto) {
            $log->aberto = true;
            $log->aberto_em = Carbon::now();
            $log->ip = $request->ip();
            $log->user_agent = $request->userAgent();
            $log->save();
        } else {
            // Se já estava aberto, pode atualizar IP/User-Agent caso seja diferente (opcional)
            if ($log->ip !== $request->ip() || $log->user_agent !== $request->userAgent()) {
                $log->ip = $request->ip();
                $log->user_agent = $request->userAgent();
                $log->save();
            }
        }

        // Retorna o pixel invisível
        return $this->returnTransparentPixel();
    }

    private function returnTransparentPixel()
    {
        $pixel = base64_decode('R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==');

        return response($pixel)
            ->header('Content-Type', 'image/gif')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }
}
