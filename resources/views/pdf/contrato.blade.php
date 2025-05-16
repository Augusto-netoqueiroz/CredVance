<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Contrato de Consórcio</title>
    <style>
        body { font-family: serif; font-size: 12pt; margin: 40px; line-height: 1.6; text-align: center; }
        header { margin-bottom: 20px; }
        x-application-logo2222, header img { max-height: 60px; display: block; margin: 0 auto; }
        .company-info { margin-bottom: 30px; }
        .company-info p { margin: 4px 0; }
        .section { margin-bottom: 20px; }
        .section p, .section h3 { text-align: center; }
        table { width: 80%; border-collapse: collapse; margin: 10px auto; }
        th, td { border: 1px solid #333; padding: 5px; text-align: center; }
        th { background-color: #eee; }
        .clause { margin: 15px auto; padding: 10px; background-color: #f9f9f9; border-left: 4px solid #333; width: 80%; text-align: left; }
        .clause h4 { margin: 0 0 5px; font-weight: bold; text-align: center; }
        .clause ul { margin: 5px auto 0; padding-left: 20px; text-align: left; width: 90%; }
        .signature { margin-top: 50px; display: block; width: 60%; margin-left: auto; margin-right: auto; }
        .sig-block { margin-bottom: 40px; }
        .sig-line { border-bottom: 1px solid #000; width: 100%; height: 0; margin-bottom: 6px; }
        .sig-label { font-weight: bold; margin-bottom: 10px; }
        .signed { font-size: 10pt; margin-top: 5px; text-align: center; word-break: break-all; }
        footer { margin-top: 40px; font-size: 10pt; }
    </style>
</head>
<body>
    <header>
        <x-application-logo2222 />
        <h1>CREDVANCE ADMINISTRADORA DE CONSÓRCIO LTDA</h1>
        <p>CNPJ: 55.209.479/0001-62</p>
    </header>

    <div class="company-info">
        <p><strong>Contrato nº:</strong> {{ $contrato->id }}</p>
        <p><strong>Data de celebração:</strong> {{ \Carbon\Carbon::parse($contrato->aceito_em)->timezone('America/Sao_Paulo')->format('d/m/Y H:i') }}</p>
        <p><strong>Local:</strong> Brasília/DF</p>
    </div>

    <p style="text-align:justify;">
    Pelo presente instrumento particular de contrato de consórcio, de um lado <strong>CREDVANCE ADMINISTRADORA DE CONSÓRCIO LTDA</strong>, inscrita no CNPJ nº 55.209.479/0001-62, com sede em Brasília/DF, doravante denominada <strong>ADMINISTRADORA</strong>, e de outro o(a) Sr(a). <strong>{{ $contrato->cliente->name }}</strong>, CPF nº <strong>{{ $contrato->cliente->cpf }}</strong>, doravante denominado(a) <strong>PARTICIPANTE</strong>, têm entre si justo e contratado o que segue.
    </p>

    <div class="section">
        <h3>1. Objeto</h3>
        <p>Participação no consórcio para aquisição de cotas do plano <strong>{{ $contrato->consorcio->plano }}</strong>, prazo de <strong>{{ $contrato->consorcio->prazo }} meses</strong>, quantidade <strong>{{ $contrato->quantidade_cotas }}</strong> cotas.</p>
    </div>

    <div class="section">
        <h3>2. Cronograma de Pagamentos</h3>
        <table>
            <thead>
                <tr><th>Parcela</th><th>Vencimento</th><th>Valor (R$)</th><th>Status</th></tr>
            </thead>
            <tbody>
                @foreach($pagamentos as $i => $p)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($p->vencimento)->timezone('America/Sao_Paulo')->format('d/m/Y') }}</td>
                    <td>{{ number_format($p->valor, 2, ',', '.') }}</td>
                    <td>{{ ucfirst($p->status) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="clause">
      <h4>3. Cláusula de Desistência</h4>
      <p>Em caso de desistência, o PARTICIPANTE receberá o valor pago, <strong>deduzida taxa administrativa de 23%</strong>.</p>
    </div>

    <div class="clause">
      <h4>4. Outras Condições</h4>
      <ul>
        <li>Conhecimento das regras de contemplação e penalidades.</li>
        <li>Atualização de dados pelo PARTICIPANTE.</li>
        <li>Atrasos sujeitam-se a cobrança judicial.</li>
        <li>Foro: comarca de Brasília/DF.</li>
      </ul>
    </div>

    <div class="signature">
        <div class="sig-block">
            <div class="sig-line"></div>
            <div class="sig-label">ADMINISTRADORA</div>
            <div class="signed">
                Assinado digitalmente em {{ \Carbon\Carbon::parse($contrato->aceito_em)->timezone('America/Sao_Paulo')->format('d/m/Y H:i') }}<br>
                Hash: {{ hash('sha256', $contrato->id.$contrato->aceito_em) }}
            </div>
        </div>

        <div class="sig-block">
            <div class="sig-line"></div>
            <div class="sig-label">PARTICIPANTE</div>
            <div class="signed">
                Assinado digitalmente em {{ \Carbon\Carbon::parse($contrato->aceito_em)->timezone('America/Sao_Paulo')->format('d/m/Y H:i') }}<br>
                Hash: {{ hash('sha256', $contrato->id.$contrato->cliente->cpf.$contrato->aceito_em) }}
            </div>
        </div>
    </div>

    <footer>
        Documento assinado digitalmente por ambas as partes através de Certificado Digital ICP-Brasil.<br>
        www.credvance.com.br
    </footer>
</body>
</html>