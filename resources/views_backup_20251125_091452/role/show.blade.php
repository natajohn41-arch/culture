<!doctype html>
<html lang="fr">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>AdminLTE 4 | Détails Langue</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <meta name="color-scheme" content="light dark" />
    <meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)" />
    <meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)" />
    <meta name="title" content="AdminLTE 4 | Détails Langue" />
    <meta name="author" content="ColorlibHQ" />
    <meta
      name="description"
      content="AdminLTE is a Free Bootstrap 5 Admin Dashboard, 30 example pages using Vanilla JS. Fully accessible with WCAG 2.1 AA compliance."
    />
    <meta
      name="keywords"
      content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard, accessible admin panel, WCAG compliant"
    />
    <meta name="supported-color-schemes" content="light dark" />
    <link rel="preload" href="{{ URL::asset('adminlte/css/adminlte.css') }}" as="style" />
    
    <!-- Fonts -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
      integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q="
      crossorigin="anonymous"
      media="print"
      onload="this.media='all'"
    />
    
    <!-- Third Party Plugins -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css"
      crossorigin="anonymous"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
      crossorigin="anonymous"
    />
    
    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="{{ URL::asset('adminlte/css/adminlte.css') }}" />
  </head>
  
  <body class="layout-fixed sidebar-expand-lg sidebar-open bg-body-tertiary">
    <div class="app-wrapper">
      <!-- Header -->
      <nav class="app-header navbar navbar-expand bg-body">
  <div class="container-fluid">
    <!-- Logo et nom du projet -->
    <a class="navbar-brand d-none d-md-flex align-items-center" href="{{ url('/') }}">
      <i class="bi bi-globe-europe-africa me-2"></i>
      <span class="fw-bold">Culture</span>
    </a>
    
    <!-- Menu de navigation gauche -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
          <i class="bi bi-list"></i>
        </a>
      </li>
     
      <li class="nav-item d-none d-md-block">
        <a href="{{ url('/') }}" class="nav-link">Site Public</a>
      </li>
    </ul>
    
    <!-- Menu de navigation droite -->
    <ul class="navbar-nav ms-auto">
      <!-- Recherche -->
      <li class="nav-item">
        <a class="nav-link" data-widget="navbar-search" href="#" role="button">
          <i class="bi bi-search"></i>
        </a>
      </li>
      
      <!-- Notifications des contenus -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-bs-toggle="dropdown" href="#">
          <i class="bi bi-bell-fill"></i>
          <span class="navbar-badge badge text-bg-warning">5</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
          
          <div class="dropdown-divider"></div>
          <a href="{{ route('commentaire.index') }}" class="dropdown-item">
            <i class="bi bi-chat-text me-2"></i> 3 nouveaux commentaires
            <span class="float-end text-secondary fs-7">2h</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="{{ route('contenu.index') }}" class="dropdown-item">
            <i class="bi bi-file-text me-2"></i> 2 contenus en attente
            <span class="float-end text-secondary fs-7">1j</span>
          </a>
         
        </div>
      </li>
      
      <!-- Sélecteur de langue -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-bs-toggle="dropdown" href="#">
          <i class="bi bi-translate"></i>
          <span class="d-none d-md-inline">FR</span>
        </a>
       
      </li>
      
      <!-- Mode plein écran -->
      <li class="nav-item">
        <a class="nav-link" href="#" data-lte-toggle="fullscreen">
          <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
          <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display: none"></i>
        </a>
      </li>
      
      <!-- Menu utilisateur -->
      <li class="nav-item dropdown user-menu">
        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
          <div class="user-image-placeholder rounded-circle bg-primary d-flex align-items-center justify-content-center text-white fw-bold" style="width: 32px; height: 32px; font-size: 14px;">
            <i class="bi bi-person-fill"></i>
          </div>
          <span class="d-none d-md-inline">Administrateur</span>
        </a>
        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
          <li class="user-header text-bg-primary">
            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto mb-2 text-primary" style="width: 90px; height: 90px; font-size: 40px;">
              <i class="bi bi-person-fill"></i>
            </div>
            <p>
              Administrateur Culture
              <small>Gestionnaire de contenu</small>
            </p>
          </li>
          <li class="user-body">
            <div class="row">
              <div class="col-6 text-center">
                <a href="#">Profil</a>
              </div>
              
            </div>
          </li>
         
        </ul>
      </li>
    </ul>
  </div>
</nav>
      
      <!-- Sidebar Simplifiée -->
      <div class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
        <div class="sidebar-wrapper">
          <nav class="sidebar-main">
            <div class="nav nav-pills nav-sidebar flex-column" data-lte-toggle="treeview" role="menu">
              <!-- En-tête principal -->
              <div class="nav-header text-uppercase">Tableau de Bord</div>
              <a href="#" class="nav-link">
                <i class="nav-icon bi bi-speedometer"></i>
                <p>Dashboard</p>
              </a>
              
              <!-- Gestion des contenus -->
              <div class="nav-header text-uppercase mt-3">Gestion des Contenus</div>
              <a href="{{ route('contenu.index') }}" class="nav-link">
                <i class="nav-icon bi bi-file-text"></i>
                <p>Contenus</p>
              </a>
              <a href="{{ route('typecontenu.index') }}" class="nav-link">
                <i class="nav-icon bi bi-tags"></i>
                <p>Types de Contenu</p>
              </a>
              <a href="{{ route('commentaire.index') }}" class="nav-link">
                <i class="nav-icon bi bi-chat-square-text"></i>
                <p>Commentaires</p>
              </a>
              
              <!-- Gestion des médias -->
              <div class="nav-header text-uppercase mt-3">Gestion des Médias</div>
              <a href="{{ route('media.index') }}" class="nav-link">
                <i class="nav-icon bi bi-image"></i>
                <p>Médias</p>
              </a>
              <a href="{{ route('typemedia.index') }}" class="nav-link">
                <i class="nav-icon bi bi-collection"></i>
                <p>Types de Média</p>
              </a>
              
              <!-- Gestion géographique -->
              <div class="nav-header text-uppercase mt-3">Gestion Géographique</div>
              <a href="{{ route('region.index') }}" class="nav-link">
                <i class="nav-icon bi bi-globe"></i>
                <p>Régions</p>
              </a>
              
              <!-- Gestion des utilisateurs -->
              <div class="nav-header text-uppercase mt-3">Gestion des Utilisateurs</div>
              <a href="{{ route('utilisateurs.index') }}" class="nav-link">
                <i class="nav-icon bi bi-people"></i>
                <p>Utilisateurs</p>
              </a>
              <a href="{{ route('role.index') }}" class="nav-link">
                <i class="nav-icon bi bi-person-badge"></i>
                <p>Rôles</p>
              </a>
              
              <!-- Configuration -->
              <div class="nav-header text-uppercase mt-3">Configuration</div>
              <a href="{{ route('langues.index') }}" class="nav-link ">
                <i class="nav-icon bi bi-translate"></i>
                <p>Langues</p>
              </a>
            </div>
          </nav>
        </div>
      </div>
      
      <!-- Main Content -->
      <main class="app-main">
        <div class="container d-flex align-items-center" style="min-height:60vh;">
  <div class="row justify-content-center w-100">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">Détails</div>
        <div class="card-body">
          <dl class="row">
            <dt class="col-sm-4">ID</dt>
            <dd class="col-sm-8">{{ $role->id_role ?? '-' }}</dd>

            <dt class="col-sm-4">Nom</dt>
            <dd class="col-sm-8">{{ $role->nom_role ?? '-' }}</dd>
          </dl>

          <div class="d-flex justify-content-end">
            <a href="{{ route('role.index') }}" class="btn btn-secondary">Retour</a>
            <a href="{{ route('role.edit', $role->id_role) }}" class="btn btn-primary ms-2">Éditer</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
      </main>
      
      <!-- Footer -->
      <footer class="app-footer">
        <div class="float-end d-none d-sm-inline">Système de gestion</div>
        <strong>
          Copyright &copy; 2014-2025&nbsp;
          <a href="https://adminlte.io" class="text-decoration-none">AdminLTE.io</a>.
        </strong>
        Tous droits réservés.
      </footer>
    </div>
    
    <!-- Scripts -->
    <script
      src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js"
      crossorigin="anonymous"
    ></script>
    <script
      src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
      crossorigin="anonymous"
    ></script>
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js"
      crossorigin="anonymous"
    ></script>
    <script src="{{ URL::asset('js/adminlte.js') }}"></script>
    
    <script>
      const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
      const Default = {
        scrollbarTheme: 'os-theme-light',
        scrollbarAutoHide: 'leave',
        scrollbarClickScroll: true,
      };
      document.addEventListener('DOMContentLoaded', function () {
        const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
        if (sidebarWrapper && OverlayScrollbarsGlobal?.OverlayScrollbars !== undefined) {
          OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
            scrollbars: {
              theme: Default.scrollbarTheme,
              autoHide: Default.scrollbarAutoHide,
              clickScroll: Default.scrollbarClickScroll,
            },
          });
        }
      });
    </script>
  </body>
</html>