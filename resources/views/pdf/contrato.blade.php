
{{-- resources/views/pdf/contrato.blade.php --}}
@php
    // Garante que $frameBase64 esteja disponível (vindo do Controller ou lido aqui)
    if (!isset($frameBase64)) {
        $path = public_path('assets/img/moldura-contrato.jpg');
        if (file_exists($path)) {
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = @file_get_contents($path);
            if ($data !== false) {
                $frameBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            } else {
                $frameBase64 = null;
                \Log::warning('Não conseguiu ler moldura-contrato.jpg');
            }
        } else {
            $frameBase64 = null;
            \Log::warning('Arquivo moldura-contrato.jpg não encontrado em public/assets/img');
        }
    }
@endphp

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Contrato de Adesão - CREDVANCE</title>
    <style>
        /*** Zero de margens de página; moldura fixa cobrirá toda a página ***/
        @page {
            margin: 0;
            size: A4 portrait;
        }
        body {
            margin: 0;
            padding: 0;
        }
        /*** Moldura fixa em background, repetida em cada página ***/
        .page-frame {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: -100;
        }
        .page-frame img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -100;
        }
        .page-frame::before {
            /* fallback de background-image; Dompdf às vezes ignora background em fixed, mas tentamos */
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            @if(!empty($frameBase64))
            background-image: url("{{ $frameBase64 }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            @endif
            z-index: -101;
        }

        /*** Container principal: padding para reservar espaço superior/inferior ***/
        .content {
            /* Ajuste estes valores conforme sua moldura: 
               padding-top: espaço até começar o texto sem invadir decoração superior;
               padding-bottom: espaço para não invadir decoração inferior. */
            padding-top: 40mm;
            padding-bottom: 40mm;
            padding-left: 15mm;
            padding-right: 15mm;
            /* O conteúdo fluirá e quebrará em várias páginas automaticamente */
        }

        /*** Estilos do contrato inalterados ***/
        .contract-body {
            font-family: serif;
            font-size: 12pt;
            margin: 0;
            line-height: 1.6;
            color: #222;
        }
        .contract-body header {
            margin-bottom: 5mm;
            text-align: center;
        }
        .contract-body .logo {
            max-height: 60px;
            margin: 0 auto 12px;
            display: block;
        }
        .contract-body h1 {
            font-size: 22pt;
            margin-bottom: 2px;
        }
        .contract-body h2 {
            font-size: 16pt;
            margin: 10px 0 5px;
        }
        .contract-body h3 {
            font-size: 13pt;
            margin: 18px 0 10px;
            text-align: left;
        }
        .contract-body .contract-info,
        .contract-body .client-info {
            margin-bottom: 16px;
            text-align: center;
        }
        .contract-body .section-title {
            font-weight: bold;
            text-decoration: underline;
            margin-top: 20px;
        }
        .contract-body .clause {
            margin: 15px 0 0 0;
            padding: 10px 18px;
            background: #f8f8f8;
            border-left: 4px solid #3477c8;
            page-break-inside: avoid;
        }
        .contract-body ul {
            margin: 8px 0 0 30px;
        }
        .contract-body .signature {
            margin-top: 10mm;
            width: 95%;
            text-align: center;
        }
        .contract-body .sig-block {
            margin-bottom: 36px;
        }
        .contract-body .sig-line {
            border-bottom: 1px solid #000;
            width: 90%;
            height: 0;
            margin: 0 auto 4px;
        }
        .contract-body .sig-label {
            font-weight: bold;
        }
        .contract-body .signed {
            font-size: 10pt;
            margin-top: 5px;
        }
        .contract-body footer {
            margin-top: 10mm;
            font-size: 9pt;
            text-align: center;
            color: #666;
        }
    </style>
</head>
<body>
    {{-- Moldura fixa --}}
    <div class="page-frame">
        @if(!empty($frameBase64))
            {{-- Fallback via <img> --}}
            <img src="{{ $frameBase64 }}" alt="Moldura Contrato">
        @endif
    </div>

    {{-- Conteúdo em fluxo, com header apenas no início --}}
    <div class="content">
        <div class="contract-body">
            {{-- Header apenas na primeira página --}}
            <header>
                <h1>CONTRATO DE ADESÃO AOS TERMOS DO PLANO FINANCEIRO</h1>
                <h2>CREDVANCE</h2>
            </header>

            {{-- Informações iniciais --}}
            <div class="contract-info">
                <p><strong>Contrato nº:</strong> {{ $contrato->id }}</p>
                <p><strong>Data de celebração:</strong> {{ \Carbon\Carbon::parse($contrato->aceito_em)->timezone('America/Sao_Paulo')->format('d/m/Y H:i') }}</p>
                <p><strong>Local:</strong> São Sebastião/DF</p>
            </div>

            <div class="client-info">
                <p>
                    Pelo presente instrumento particular de adesão, de um lado, a empresa <b>CREDVANCE CONSÓRCIO</b>, pessoa jurídica de direito privado, inscrita no CNPJ sob o nº 55.209.479/0001-62, com sede na Rua 35, Casa 101, Setor Tradicional, São Sebastião – DF, doravante denominada <b>CONTRATADA</b>, esclarecendo desde já que a expressão "consórcio" em seu nome fantasia é de cunho exclusivamente comercial, e de outro lado,
                    o <b>ADERENTE</b>, <b>{{ $contrato->cliente->name }}</b> (CPF: <b>{{ $contrato->cliente->cpf }}</b>), qualificado eletronicamente por meio do aceite digital no sítio oficial da CONTRATADA, resolvem celebrar o presente Contrato de Adesão, que será regido pelas cláusulas e condições a seguir dispostas:
                </p>
            </div>

            {{-- Cláusulas completas --}}
            <div class="clause">
                <h3>CLÁUSULA PRIMEIRA – DO OBJETO</h3>
                <p>
                    O presente contrato tem por objeto a adesão voluntária e irrevogável do ADERENTE aos planos financeiros disponibilizados pela CONTRATADA, os quais consistem em aportes mensais com devolução futura do valor total pago, acrescido de juros previamente fixados, desde que observadas as condições pactuadas neste instrumento.
                </p>
            </div>


            <div style="height:80px; visibility:hidden;"></div>
            <div style="height:80px; visibility:hidden;"></div>
 

            <div class="clause">
                <h3>CLÁUSULA PRIMEIRA-A – DA NATUREZA JURÍDICA DA OPERAÇÃO E DO USO DA EXPRESSÃO “CONSÓRCIO”</h3>
                <ul>
                    <li>A CONTRATADA declara expressamente que a operação objeto deste contrato não se caracteriza como consórcio, nos termos da Lei nº 11.795/2008, não havendo sorteios, lances, formação de grupos ou administração de bens por terceiros.</li>
                    <li>Trata-se de um plano financeiro privado com retorno programado, regido pelas normas do Código Civil e do Código de Defesa do Consumidor, com previsão de devolução futura dos valores pagos, corrigidos por juros contratuais.</li>
                    <li>A expressão "consórcio" constante do nome fantasia da CONTRATADA possui finalidade meramente comercial, não implicando qualquer vínculo institucional ou regulatório com o sistema oficial de consórcios fiscalizado pelo Banco Central do Brasil.</li>
                </ul>
            </div>

            <div class="clause">
                <h3>CLÁUSULA SEGUNDA – DOS PLANOS DISPONÍVEIS</h3>
                <p>
                    <b>Plano contratado:</b> {{ $contrato->consorcio->plano }}<br>
                    <b>Prazo:</b> {{ $contrato->consorcio->prazo }} meses<br>
                    <b>Quantidade de cotas:</b> {{ $contrato->quantidade_cotas }}</p>
                @if($contrato->consorcio->prazo == 12)
                    <ul>
                        <li>Parcelas mensais decrescentes, iniciando em R$ 155,00 e reduzindo até R$ 100,00.</li>
                        <li>Valor total a ser pago: R$ 1.530,00.</li>
                        <li>Juros contratuais: 16% sobre o valor total pago.</li>
                        <li>Valor a ser recebido após o prazo de carência: R$ 1.774,80.</li>
                    </ul>
                @elseif($contrato->consorcio->prazo == 24)
                    <ul>
                        <li>Parcelas mensais decrescentes, com duas parcelas por valor.</li>
                        <li>Valor total a ser pago: R$ 3.060,00.</li>
                        <li>Juros contratuais: 20% sobre o valor total pago.</li>
                        <li>Valor a ser recebido após o prazo de carência: R$ 3.672,00.</li>
                    </ul>
                @else
                    <ul>
                        <li>Condições específicas do plano serão detalhadas conforme proposta comercial aprovada.</li>
                    </ul>
                @endif
                <p>O ADERENTE poderá contratar mais de uma cota, sendo o valor final recebido proporcional à quantidade de cotas contratadas.</p>
            </div>

            <div style="height:80px; visibility:hidden;"></div>
            <div style="height:80px; visibility:hidden;"></div>
                

            <div class="clause">
                <h3>CLÁUSULA TERCEIRA – DO PAGAMENTO E ATIVAÇÃO DO PLANO</h3>
                <ul>
                    <li>O pagamento deverá ser efetuado mensalmente por meio de boleto bancário, Pix ou transferência para a conta empresarial da CONTRATADA.</li>
                    <li>O plano é considerado ativo a partir do efetivo pagamento da primeira parcela.</li>
                    <li>As demais parcelas deverão ser pagas no mesmo dia de cada mês subsequente, com tolerância máxima de 10 (dez) dias de atraso por parcela.</li>
                </ul>
            </div>

            <div class="clause">
                <h3>CLÁUSULA QUARTA – DO RESGATE E JUROS CONTRATUAIS</h3>
                <ul>
                    <li>O ADERENTE fará jus à devolução do valor total efetivamente pago, acrescido dos juros definidos no plano escolhido, desde que:</li>
                    <ul>
                        <li>Todas as parcelas estejam quitadas integralmente;</li>
                        <li>Tenha transcorrido o período mínimo de 365 dias (Plano 1) ou 730 dias (Plano 2) a contar da data da primeira parcela.</li>
                    </ul>
                    <li>O pagamento do valor corrigido será realizado pela CONTRATADA em até 30 (trinta) dias corridos após o cumprimento do prazo contratual.</li>
                </ul>
            </div>

            <div class="clause">
                <h3>CLÁUSULA QUINTA – DA DESISTÊNCIA E CANCELAMENTO</h3>
                <ul>
                    <li>O ADERENTE poderá, a qualquer tempo, solicitar a sua retirada do plano, mediante envio de solicitação formal por e-mail ou correspondência à CONTRATADA.</li>
                    <li>Em caso de desistência, o ADERENTE terá direito à devolução de 77% (setenta e sete por cento) do valor total pago até a data do pedido, renunciando expressamente ao direito de recebimento dos juros previstos neste instrumento.</li>
                </ul>
            </div>

            <div style="height:80px; visibility:hidden;"></div>
            <div style="height:80px; visibility:hidden;"></div>
            <div style="height:80px; visibility:hidden;"></div>
            <div style="height:80px; visibility:hidden;"></div>

            <div class="clause">
                <h3>CLÁUSULA SEXTA – DO ACEITE ELETRÔNICO</h3>
                <ul>
                    <li>Este contrato estará disponível para leitura integral no site oficial da CONTRATADA.</li>
                    <li>O ADERENTE manifesta sua plena ciência e concordância com todos os termos aqui previstos ao clicar na opção “Li e Aceito os Termos”, sendo este aceite eletrônico dotado de plena validade jurídica, nos termos da legislação brasileira.</li>
                </ul>
            </div>

            <div class="clause">
                <h3>CLÁUSULA SÉTIMA – DISPOSIÇÕES GERAIS</h3>
                <ul>
                    <li>O presente contrato constitui o entendimento integral entre as partes.</li>
                    <li>A CONTRATADA poderá, a seu critério, atualizar os termos deste contrato, respeitando os direitos adquiridos pelo ADERENTE.</li>
                    <li>O não exercício de qualquer direito previsto neste contrato não implicará em renúncia ou novação.</li>
                    <li>O foro eleito para dirimir eventuais conflitos oriundos deste contrato é o da comarca de São Sebastião – DF.</li>
                </ul>
            </div>

            <div class="clause">
                <h3>CLÁUSULA SÉTIMA-A – DA PUBLICIDADE E TRANSPARÊNCIA</h3>
                <ul>
                    <li>A CONTRATADA compromete-se a assegurar que todo material publicitário e comunicação institucional veiculados contenham menção clara de que a operação ofertada é um plano financeiro privado com retorno programado, e não se trata de consórcio nos termos da Lei nº 11.795/2008.</li>
                    <li>O ADERENTE declara estar ciente de que a utilização da expressão “consórcio” no nome fantasia da CONTRATADA tem finalidade meramente comercial.</li>
                    <li>Para fins de reforço da transparência, a CONTRATADA poderá exigir a assinatura de termo complementar de ciência, por meio físico ou eletrônico.</li>
                </ul>
            </div>
            <div style="height:80px; visibility:hidden;"></div>
            <div style="height:80px; visibility:hidden;"></div>
             

            <div class="clause">
                <h3>CLÁUSULA SÉTIMA-B – DA JUSTIFICATIVA DA RETENÇÃO PARCIAL EM CASO DE INADIMPLEMENTO</h3>
                <ul>
                    <li>Em caso de rescisão contratual por inadimplemento superior a 90 dias, a devolução ao ADERENTE será limitada a 70% do montante pago até a data da rescisão, sem incidência de juros.</li>
                    <li>O percentual retido justifica-se em razão dos encargos administrativos, operacionais e financeiros já suportados pela CONTRATADA.</li>
                    <li>Facultativamente, o ADERENTE inadimplente poderá requerer a contratação de novo plano com reaproveitamento proporcional dos valores pagos, a critério da CONTRATADA.</li>
                </ul>
            </div>

            <div class="clause">
                <h3>CLÁUSULA OITAVA – DA CIÊNCIA E COMPROMISSO</h3>
                <p>
                    Ao clicar em “Li e Aceito os Termos”, o ADERENTE declara ter lido, compreendido e aceitado todas as cláusulas e condições deste contrato, aderindo integralmente aos seus termos e reconhecendo sua validade legal para todos os efeitos de direito.
                </p>
            </div>

            <div style="height:80px; visibility:hidden;"></div>
            <div style="height:80px; visibility:hidden;"></div>
            <div style="height:80px; visibility:hidden;"></div>
            <div style="height:80px; visibility:hidden;"></div>
            <div style="height:80px; visibility:hidden;"></div>
            <div style="height:80px; visibility:hidden;"></div>

            {{-- Assinaturas --}}
            <div class="signature">
                <div class="sig-block">
                    <div class="sig-line"></div>
                    <div class="sig-label">CONTRATADA</div>
                    <div class="signed">
                        Assinado digitalmente em {{ \Carbon\Carbon::parse($contrato->aceito_em)->timezone('America/Sao_Paulo')->format('d/m/Y H:i') }}<br>
                        IP: {{ $contrato->ip ?? '---' }}<br>
                        Hash: {{ hash('sha256', $contrato->id.$contrato->aceito_em) }}
                    </div>
                </div>
                <div class="sig-block">
                    <div class="sig-line"></div>
                    <div class="sig-label">ADERENTE</div>
                    <div class="signed">
                        Assinado digitalmente em {{ \Carbon\Carbon::parse($contrato->aceito_em)->timezone('America/Sao_Paulo')->format('d/m/Y H:i') }}<br>
                        IP: {{ $contrato->ip ?? '---' }}<br>
                        Hash: {{ hash('sha256', $contrato->id.$contrato->cliente->cpf.$contrato->aceito_em) }}
                    </div>
                </div>
            </div>

            {{-- Footer apenas na última página --}}
            <footer>
                Documento assinado digitalmente por ambas as partes por aceite eletrônico.<br>
                www.meucredvance.com.br
            </footer>
        </div>
    </div>
</body>
</html>