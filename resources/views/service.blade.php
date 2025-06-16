<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Planos & Simulação - Cred Vance</title>
  <meta name="description" content="Consórcio CredVance: parcelas decrescentes com retorno garantido">
  <meta name="keywords" content="consórcio, simulação, parcelas, CredVance">

  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">

  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <link href="assets/css/main.css" rel="stylesheet">

  <style>
    #service-details table {
      border-collapse: separate !important;
      border-spacing: 0;
      border-radius: 0.5rem;
      overflow: hidden;
    }
    #service-details .table-sm th,
    #service-details .table-sm td {
      padding: 0.3rem;
      color: #000 !important;
    }
    #service-details .table-sm {
      font-size: 0.9rem;
    }
    #service-details h3 {
      font-size: 1.1rem;
      margin-bottom: 0.5rem;
    }
    #service-details .form-control-sm {
      font-size: 0.8rem;
      padding: 0.2rem 0.4rem;
      max-width: 70px;
    }
    #service-details .btn-light {
      background-color: #f8f9fa;
      border: 1px solid #ddd;
      color: #000;
    }
    @media (max-width: 575.98px) {
      #service-details .row > div {
        margin-bottom: 1rem;
      }
    }
  </style>
</head>
<body class="index-page">

<header id="header" class="header d-flex align-items-center fixed-top">
  <div class="container-fluid container-xl position-relative d-flex align-items-center">
    <a href="/pagina#hero" class="logo d-flex align-items-center me-auto">
      <h1 class="sitename">Cred Vance</h1>
    </a>
    <nav id="navmenu" class="navmenu">
      <ul>
        <li><a href="/pagina#hero">Início</a></li>
        <li><a href="/pagina#about">Sobre</a></li>
        <li><a href="/pagina#services">Planos</a></li>
        <li><a href="/pagina#simulacao">Simulador de Parcelas</a></li>
        <li><a href="/pagina#stats">Estatísticas</a></li>
        <li><a href="/pagina#contact">Contato</a></li>
      </ul>
      <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
    </nav>
    <a class="cta-btn" href="{{ route('login') }}">Área do Cliente</a>
  </div>
</header>

<main class="main">
  <div class="page-title dark-background" id="simulacao" data-aos="fade" style="background-image: url('assets/img/hero2.png');">
    <div class="container position-relative">
      <h1>Planos & Simulação</h1>
      <p>Entenda nosso modelo de consórcio e simule quantas cotas desejar.</p>
    </div>
  </div>

  <section id="service-details" class="service-details section dark-background">
    <div class="container" data-aos="fade-up">
      <div class="row">
        <div class="col-12">
          <h3 class="mb-2">Simule seu plano ideal</h3>
          <p class="small">
            Escolha entre nossos dois planos com parcelas decrescentes e retorno garantido:<br>
            - <strong>Plano 12 meses</strong>: 12 parcelas de R$155,00 a R$100,00, com juros de 16%.<br>
            - <strong>Plano 24 meses</strong>: 24 parcelas de R$155,00 a R$100,00 (em pares), com juros de 20%.<br>
            Digite a quantidade de cotas desejada e clique em "Calcular" para visualizar os valores.
          </p>
          <div class="d-flex align-items-center gap-2 mb-3">
              <select id="planoToggle" class="form-select form-select-sm w-auto">
                <option value="12">Plano 12 Meses</option>
                <option value="24">Plano 24 Meses</option>
              </select>
              <label for="quantidadeCotas" class="mb-0">COTAS:</label>
              <input type="number" class="form-control form-control-sm" id="quantidadeCotas" placeholder="Cotas" min="1" value="1">
              <button onclick="atualizarTabela()" class="btn btn-light btn-sm">Calcular</button>
            </div>

          <div id="tabelaParcelas"></div>
        </div>
      </div>
    </div>
  </section>
</main>

<footer id="footer" class="footer dark-background">
  <div class="container text-center">
    <p>&copy; Copyright <strong class="px-1 sitename">Cred Vance</strong>. Todos os direitos reservados.</p>
  </div>
</footer>

<a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center">
  <i class="bi bi-arrow-up-short"></i>
</a>

<div id="preloader"></div>

<script>
function atualizarTabela() {
  const plano = parseInt(document.getElementById('planoToggle').value);
  const q = parseInt(document.getElementById('quantidadeCotas').value) || 1;
  let parcelas = [];
  let juros = '';
  if (plano === 12) {
    parcelas = [155,150,145,140,135,130,125,120,115,110,105,100];
    juros = '16%';
  } else {
    parcelas = [155,155,150,150,145,145,140,140,135,135,130,130,125,125,120,120,115,115,110,110,105,105,100,100];
    juros = '20%';
  }
  let html = `<h5 class='small'>Plano ${plano} Meses</h5><table class='table table-sm table-bordered'>
    <thead><tr><th>Mês</th><th>Por Cota</th><th>Total</th></tr></thead><tbody>`;
  let totalPago = 0;
  for (let i = 0; i < parcelas.length; i++) {
    const valor = parcelas[i];
    const total = valor * q;
    totalPago += total;
    html += `<tr><td>${i + 1}</td><td>R$ ${valor.toFixed(2).replace('.', ',')}</td><td>R$ ${total.toFixed(2).replace('.', ',')}</td></tr>`;
  }
  html += `</tbody></table>`;
  const retornoFinal = plano === 12 ? 1774.80 * q : 3672.00 * q;
  html += `<div class='bg-white p-3 rounded shadow-sm text-black'>
    <p class='small mb-1'><strong>Juros:</strong> ${juros}</p>
    <p class='small mb-1'><strong>Total Pago:</strong> R$ ${totalPago.toFixed(2).replace('.', ',')}</p>
    <p class='small'><strong>Retorno Final:</strong> R$ ${retornoFinal.toFixed(2).replace('.', ',')}</p>
  </div>`;
  document.getElementById('tabelaParcelas').innerHTML = html;
}

window.addEventListener('DOMContentLoaded', () => atualizarTabela());
document.getElementById('planoToggle').addEventListener('change', atualizarTabela);
</script>

<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/php-email-form/validate.js"></script>
<script src="assets/vendor/aos/aos.js"></script>
<script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
<script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
<script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
<script src="assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
<script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
<script>
if (window.AOS) AOS.init({ duration: 800, once: true });
window.addEventListener('load', function() {
  const pre = document.getElementById('preloader');
  if (pre) pre.remove();
});
</script>
</body>
</html>
