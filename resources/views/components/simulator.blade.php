<div class="card shadow-lg border-0 bg-white bg-opacity-95 backdrop-blur position-relative overflow-hidden">
    <!-- Gradient Background -->
    <div class="position-absolute top-0 start-0 w-100 h-100 bg-gradient-to-br from-blue-500/10 to-purple-500/10"></div>
    
    <div class="card-body p-4 position-relative">
        <h3 class="card-title text-center mb-4 fw-bold">
            <i class="bi bi-calculator text-primary me-2"></i>
            Simulador Rápido
        </h3>
        
        <form id="simulatorForm">
            <div class="mb-4">
                <label class="form-label fw-medium d-flex justify-content-between">
                    <span>Valor desejado:</span>
                    <span class="text-primary fw-bold fs-5">R$ <span id="valorDisplay">50.000</span></span>
                </label>
                <input type="range" 
                       class="form-range custom-range" 
                       id="valorRange" 
                       min="10000" 
                       max="100000" 
                       step="5000" 
                       value="50000"
                       data-bs-toggle="tooltip" 
                       title="Arraste para ajustar o valor">
                <div class="d-flex justify-content-between text-muted small mt-1">
                    <span>R$ 10.000</span>
                    <span>R$ 100.000</span>
                </div>
            </div>
            
            <div class="mb-4">
                <label class="form-label fw-medium d-flex justify-content-between">
                    <span>Prazo:</span>
                    <span class="text-primary fw-bold fs-5"><span id="prazoDisplay">24</span> meses</span>
                </label>
                <input type="range" 
                       class="form-range custom-range" 
                       id="prazoRange" 
                       min="12" 
                       max="60" 
                       step="6" 
                       value="24"
                       data-bs-toggle="tooltip" 
                       title="Arraste para ajustar o prazo">
                <div class="d-flex justify-content-between text-muted small mt-1">
                    <span>12 meses</span>
                    <span>60 meses</span>
                </div>
            </div>
            
            <!-- Results Card with Animation -->
            <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-3 p-4 mb-4 border border-primary border-opacity-25 shadow-sm">
                <div class="row g-3">
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted fw-medium">
                                <i class="bi bi-calendar-month me-2 text-primary"></i>
                                Parcela mensal:
                            </span>
                            <span class="h4 fw-bold text-primary mb-0 animate-number" id="parcelaMensal">R$ 2.083,33</span>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted fw-medium">
                                <i class="bi bi-trophy me-2 text-success"></i>
                                Total com bônus (20%):
                            </span>
                            <span class="h5 fw-bold text-success mb-0 animate-number" id="totalBonus">R$ 60.000,00</span>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted fw-medium">
                                <i class="bi bi-percent me-2 text-warning"></i>
                                Rendimento:
                            </span>
                            <span class="h6 fw-bold text-warning mb-0" id="rendimento">R$ 10.000,00</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Progress Bar -->
            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <small class="text-muted">Progresso da simulação</small>
                    <small class="text-primary fw-bold" id="progressPercent">100%</small>
                </div>
                <div class="progress" style="height: 6px;">
                    <div class="progress-bar bg-gradient-to-r from-blue-500 to-purple-600" 
                         role="progressbar" 
                         style="width: 100%" 
                         id="simulationProgress"></div>
                </div>
            </div>
            
            <div class="d-grid gap-2">
                <button type="button" class="btn btn-gradient btn-lg py-3 position-relative overflow-hidden" onclick="openContractModal()">
                    <span class="position-relative z-1">
                        <i class="bi bi-check-circle me-2"></i>
                        Contratar Agora
                    </span>
                    <div class="position-absolute top-0 start-0 w-100 h-100 bg-white opacity-0 hover-overlay"></div>
                </button>
                <div class="row g-2">
                    <div class="col-6">
                        <button type="button" class="btn btn-outline-primary w-100" onclick="shareSimulation()">
                            <i class="bi bi-share me-1"></i>
                            Compartilhar
                        </button>
                    </div>
                    <div class="col-6">
                        <button type="button" class="btn btn-outline-success w-100" onclick="saveSimulation()">
                            <i class="bi bi-bookmark me-1"></i>
                            Salvar
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal de Contratação -->
<div class="modal fade" id="contractModal" tabindex="-1" aria-labelledby="contractModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-gradient text-white">
                <h5 class="modal-title" id="contractModalLabel">
                    <i class="bi bi-file-earmark-text me-2"></i>
                    Solicitar Contratação
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    Preencha seus dados para receber uma proposta personalizada
                </div>
                
                <form id="contractForm">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nome Completo *</label>
                            <input type="text" class="form-control" name="nome" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">CPF *</label>
                            <input type="text" class="form-control" name="cpf" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email *</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Telefone *</label>
                            <input type="tel" class="form-control" name="telefone" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Renda Mensal</label>
                            <select class="form-select" name="renda">
                                <option value="">Selecione sua faixa de renda</option>
                                <option value="ate-2000">Até R$ 2.000</option>
                                <option value="2000-5000">R$ 2.000 - R$ 5.000</option>
                                <option value="5000-10000">R$ 5.000 - R$ 10.000</option>
                                <option value="acima-10000">Acima de R$ 10.000</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="termos" required>
                                <label class="form-check-label" for="termos">
                                    Aceito os <a href="#" class="text-primary">termos de uso</a> e 
                                    <a href="#" class="text-primary">política de privacidade</a> *
                                </label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-gradient" onclick="submitContract()">
                    <i class="bi bi-send me-2"></i>
                    Enviar Solicitação
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function openContractModal() {
    const valor = document.getElementById('valorDisplay').textContent;
    const prazo = document.getElementById('prazoDisplay').textContent;
    
    // Preenche os dados da simulação no modal
    document.querySelector('#contractModal .modal-body').insertAdjacentHTML('afterbegin', `
        <div class="alert alert-success">
            <strong>Simulação Selecionada:</strong><br>
            Valor: R$ ${valor} | Prazo: ${prazo} meses
        </div>
    `);
    
    const modal = new bootstrap.Modal(document.getElementById('contractModal'));
    modal.show();
}

function submitContract() {
    const form = document.getElementById('contractForm');
    const formData = new FormData(form);
    
    // Validação básica
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
    // Simula envio (em produção, enviaria para o servidor)
    alert('Solicitação enviada com sucesso! Entraremos em contato em breve.');
    
    const modal = bootstrap.Modal.getInstance(document.getElementById('contractModal'));
    modal.hide();
}

function shareSimulation() {
    const valor = document.getElementById('valorDisplay').textContent;
    const prazo = document.getElementById('prazoDisplay').textContent;
    const parcela = document.getElementById('parcelaMensal').textContent;
    
    const text = `Simulei um consórcio na CredVance: Valor R$ ${valor}, ${prazo} meses, parcela de ${parcela}. Confira: ${window.location.href}`;
    
    if (navigator.share) {
        navigator.share({
            title: 'Simulação CredVance',
            text: text,
            url: window.location.href
        });
    } else {
        navigator.clipboard.writeText(text).then(() => {
            alert('Simulação copiada para a área de transferência!');
        });
    }
}
</script>

