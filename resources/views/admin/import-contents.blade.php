@extends('layouts.app')

@section('title', 'Importer les Contenus')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-upload me-2"></i>Importer les Contenus Locaux
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <strong>📊 État actuel:</strong> {{ $contenusActuels }} contenu(s) valide(s) dans la base de données.
                    </div>

                    <p>Ce processus va importer <strong>230 contenus</strong> de votre base locale vers la production.</p>
                    
                    <div class="alert alert-warning">
                        <strong>⚠️ Attention:</strong> Cette action peut prendre quelques minutes. Ne fermez pas cette page.
                    </div>

                    <form id="importForm">
                        @csrf
                        <input type="hidden" name="token" value="{{ env('IMPORT_TOKEN', 'default-token') }}">
                        
                        <button type="submit" class="btn btn-primary btn-lg w-100" id="importBtn">
                            <i class="bi bi-upload me-2"></i>Importer les Contenus
                        </button>
                    </form>

                    <div id="result" class="mt-4" style="display: none;"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('importForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const btn = document.getElementById('importBtn');
    const resultDiv = document.getElementById('result');
    
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Import en cours...';
    resultDiv.style.display = 'none';
    
    fetch('{{ route("admin.import.contents") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            token: document.querySelector('input[name="token"]').value
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            resultDiv.innerHTML = `
                <div class="alert alert-success">
                    <h5>✅ Import réussi !</h5>
                    <p><strong>Contenus avant:</strong> ${data.data.contenus_avant}</p>
                    <p><strong>Contenus après:</strong> ${data.data.contenus_apres}</p>
                    <p><strong>Contenus ajoutés:</strong> ${data.data.contenus_ajoutes}</p>
                    <a href="{{ route('dashboard') }}" class="btn btn-success mt-2">Retour au Dashboard</a>
                </div>
            `;
        } else {
            resultDiv.innerHTML = `
                <div class="alert alert-danger">
                    <h5>❌ Erreur</h5>
                    <p>${data.message}</p>
                </div>
            `;
        }
        resultDiv.style.display = 'block';
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-upload me-2"></i>Importer les Contenus';
    })
    .catch(error => {
        resultDiv.innerHTML = `
            <div class="alert alert-danger">
                <h5>❌ Erreur</h5>
                <p>Une erreur est survenue: ${error.message}</p>
            </div>
        `;
        resultDiv.style.display = 'block';
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-upload me-2"></i>Importer les Contenus';
    });
});
</script>
@endsection

