<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Culture CMS')</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" crossorigin="anonymous" />
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif; background: #f8f9fa; }
        
        /* Navbar */
        .navbar { background: white; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
        .navbar-brand { font-weight: 700; color: #667eea !important; font-size: 1.4rem; }
        .nav-link { color: #333 !important; font-weight: 500; transition: 0.3s ease; }
        .nav-link:hover { color: #667eea !important; }
        
        /* Sidebar */
        .sidebar { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: calc(100vh - 70px); padding: 2rem 0; position: fixed; width: 260px; left: 0; top: 70px; overflow-y: auto; box-shadow: 2px 0 8px rgba(0,0,0,0.1); }
        .sidebar-menu { list-style: none; padding: 0 1rem; }
        .sidebar-menu-title { color: rgba(255,255,255,0.7); font-weight: 600; font-size: 0.85rem; margin-top: 2rem; margin-bottom: 1rem; text-transform: uppercase; letter-spacing: 0.5px; }
        .sidebar-menu .nav-link { color: rgba(255,255,255,0.85) !important; padding: 0.75rem 1rem; margin-bottom: 0.5rem; border-radius: 8px; transition: all 0.3s ease; }
        .sidebar-menu .nav-link:hover, .sidebar-menu .nav-link.active { background: rgba(255,255,255,0.15); color: white !important; }
        .sidebar-menu .nav-link i { margin-right: 0.75rem; }
        
        /* Main content */
        .main-content { margin-left: 260px; padding-top: 1.5rem; }
        .page-header { background: white; padding: 2rem; border-radius: 12px; margin-bottom: 2rem; box-shadow: 0 2px 8px rgba(0,0,0,0.06); }
        .page-header h1 { color: #333; font-weight: 700; font-size: 2rem; margin-bottom: 0; }
        
        /* Buttons */
        .btn-culture { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; color: white; padding: 10px 24px; border-radius: 20px; font-weight: 600; transition: all 0.3s ease; }
        .btn-culture:hover { color: white; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4); }
        
        /* Cards */
        .card { border: none; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); transition: all 0.3s ease; }
        .card:hover { box-shadow: 0 4px 16px rgba(0,0,0,0.1); }
        .card-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 12px 12px 0 0; font-weight: 600; }
        
        /* Tables */
        .table { background: white; }
        .table thead { background: #f8f9fa; }
        .table th { border-top: 2px solid #667eea; border-bottom: 2px solid #667eea; color: #333; font-weight: 600; }
        
        /* Forms */
        .form-control, .form-select { border-radius: 8px; border: 1px solid #e0e0e0; }
        .form-control:focus, .form-select:focus { border-color: #667eea; box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15); }
        .form-label { font-weight: 600; color: #333; margin-bottom: 0.5rem; }
        
        /* Alerts */
        .alert { border-radius: 8px; border: none; }
        .alert-success { background: #d4edda; color: #155724; }
        .alert-danger { background: #f8d7da; color: #721c24; }
        .alert-warning { background: #fff3cd; color: #856404; }
        
        /* Actions */
        .btn-sm { padding: 0.4rem 0.8rem; border-radius: 6px; font-size: 0.875rem; }
        
        /* Footer */
        footer { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; margin-top: 3rem; padding: 2rem 0; }
        
        /* Responsive */
        @media (max-width: 768px) {
            .sidebar { width: 200px; }
            .main-content { margin-left: 0; }
            .sidebar { transform: translateX(-100%); transition: transform 0.3s ease; z-index: 1000; }
            .sidebar.show { transform: translateX(0); }
            .page-header h1 { font-size: 1.5rem; }
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top" style="top: 0; z-index: 999;">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="bi bi-globe-europe-africa me-2"></i>Culture
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('contenu.index') }}">Contenus</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('media.index') }}">Médias</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('region.index') }}">Régions</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('utilisateurs.index') }}">Utilisateurs</a>
                    </li>
                </ul>
                <div class="d-flex ms-3">
                    @auth
                        <span class="nav-link me-3">{{ Auth::user()->name }}</span>
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm">Déconnexion</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-primary me-2">Connexion</a>
                        <a href="{{ route('register') }}" class="btn btn-culture">Inscription</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <ul class="sidebar-menu">
            <li><div class="sidebar-menu-title">Tableau de Bord</div></li>
            <li><a href="{{ route('home') }}" class="nav-link"><i class="bi bi-speedometer"></i>Accueil</a></li>
            
            <li><div class="sidebar-menu-title">Gestion des Contenus</div></li>
            <li><a href="{{ route('contenu.index') }}" class="nav-link"><i class="bi bi-file-text"></i>Contenus</a></li>
            <li><a href="{{ route('typecontenu.index') }}" class="nav-link"><i class="bi bi-tags"></i>Types de Contenu</a></li>
            <li><a href="{{ route('commentaire.index') }}" class="nav-link"><i class="bi bi-chat-square-text"></i>Commentaires</a></li>
            
            <li><div class="sidebar-menu-title">Gestion des Médias</div></li>
            <li><a href="{{ route('media.index') }}" class="nav-link"><i class="bi bi-image"></i>Médias</a></li>
            <li><a href="{{ route('typemedia.index') }}" class="nav-link"><i class="bi bi-collection"></i>Types de Média</a></li>
            
            <li><div class="sidebar-menu-title">Gestion Géographique</div></li>
            <li><a href="{{ route('region.index') }}" class="nav-link"><i class="bi bi-globe"></i>Régions</a></li>
            
            <li><div class="sidebar-menu-title">Gestion des Utilisateurs</div></li>
            <li><a href="{{ route('utilisateurs.index') }}" class="nav-link"><i class="bi bi-people"></i>Utilisateurs</a></li>
            <li><a href="{{ route('role.index') }}" class="nav-link"><i class="bi bi-person-badge"></i>Rôles</a></li>
            
            <li><div class="sidebar-menu-title">Configuration</div></li>
            <li><a href="{{ route('langues.index') }}" class="nav-link"><i class="bi bi-translate"></i>Langues</a></li>
        </ul>
    </aside>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container-fluid" style="padding: 1.5rem;">
            @yield('content')
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="bi bi-globe-europe-africa me-2"></i>Culture CMS</h5>
                    <p class="mb-0">Système de gestion de contenu culturel</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0">&copy; 2024 Culture CMS. Tous droits réservés.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js" crossorigin="anonymous"></script>
    <script>
        // Mobile sidebar toggle
        document.querySelector('.navbar-toggler')?.addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
        });
        // Close sidebar when clicking on a link (mobile)
        document.querySelectorAll('.sidebar-menu .nav-link').forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    document.getElementById('sidebar').classList.remove('show');
                }
            });
        });
    </script>
    @yield('scripts')
</body>
</html>
