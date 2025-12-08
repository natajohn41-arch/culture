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
          <!-- Start Navbar Links -->
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                <i class="bi bi-list"></i>
              </a>
            </li>
            <li class="nav-item d-none d-md-block"><a href="#" class="nav-link">Accueil</a></li>
            <li class="nav-item d-none d-md-block"><a href="#" class="nav-link">Contact</a></li>
          </ul>
          
          <!-- End Navbar Links -->
          <ul class="navbar-nav ms-auto">
            <!-- Navbar Search -->
            <li class="nav-item">
              <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                <i class="bi bi-search"></i>
              </a>
            </li>
            
            <!-- Messages Dropdown Menu -->
            <li class="nav-item dropdown">
              <a class="nav-link" data-bs-toggle="dropdown" href="#">
                <i class="bi bi-chat-text"></i>
                <span class="navbar-badge badge text-bg-danger">3</span>
              </a>
              <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                <a href="#" class="dropdown-item">
                  <div class="d-flex">
                    <div class="flex-shrink-0">
                      <img
                        src="{{ URL::asset('adminlte/img/user1-128x128.jpg') }}"
                        alt="User Avatar"
                        class="img-size-50 rounded-circle me-3"
                      />
                    </div>
                    <div class="flex-grow-1">
                      <h3 class="dropdown-item-title">
                        Brad Diesel
                        <span class="float-end fs-7 text-danger"
                          ><i class="bi bi-star-fill"></i
                        ></span>
                      </h3>
                      <p class="fs-7">Call me whenever you can...</p>
                      <p class="fs-7 text-secondary">
                        <i class="bi bi-clock-fill me-1"></i> 4 Hours Ago
                      </p>
                    </div>
                  </div>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                  <div class="d-flex">
                    <div class="flex-shrink-0">
                      <img
                        src="{{ URL::asset('adminlte/img/user8-128x128.jpg') }}"
                        alt="User Avatar"
                        class="img-size-50 rounded-circle me-3"
                      />
                    </div>
                    <div class="flex-grow-1">
                      <h3 class="dropdown-item-title">
                        John Pierce
                        <span class="float-end fs-7 text-secondary">
                          <i class="bi bi-star-fill"></i>
                        </span>
                      </h3>
                      <p class="fs-7">I got your message bro</p>
                      <p class="fs-7 text-secondary">
                        <i class="bi bi-clock-fill me-1"></i> 4 Hours Ago
                      </p>
                    </div>
                  </div>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-footer">Voir tous les messages</a>
              </div>
            </li>
            
            <!-- Notifications Dropdown Menu -->
            <li class="nav-item dropdown">
              <a class="nav-link" data-bs-toggle="dropdown" href="#">
                <i class="bi bi-bell-fill"></i>
                <span class="navbar-badge badge text-bg-warning">15</span>
              </a>
              <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                <span class="dropdown-item dropdown-header">15 Notifications</span>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                  <i class="bi bi-envelope me-2"></i> 4 new messages
                  <span class="float-end text-secondary fs-7">3 mins</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                  <i class="bi bi-people-fill me-2"></i> 8 friend requests
                  <span class="float-end text-secondary fs-7">12 hours</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                  <i class="bi bi-file-earmark-fill me-2"></i> 3 new reports
                  <span class="float-end text-secondary fs-7">2 days</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-footer"> Voir toutes les notifications </a>
              </div>
            </li>
            
            <!-- Fullscreen Toggle -->
            <li class="nav-item">
              <a class="nav-link" href="#" data-lte-toggle="fullscreen">
                <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
                <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display: none"></i>
              </a>
            </li>
            
            <!-- User Menu Dropdown -->
            <li class="nav-item dropdown user-menu">
              <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                <img
                  src="{{ URL::asset('adminlte/img/user2-160x160.jpg') }}"
                  class="user-image rounded-circle shadow"
                  alt="User Image"
                />
                <span class="d-none d-md-inline">Alexander Pierce</span>
              </a>
              <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                <li class="user-header text-bg-primary">
                  <img
                    src="{{ URL::asset('adminlte/img/user2-160x160.jpg') }}"
                    class="rounded-circle shadow"
                    alt="User Image"
                  />
                  <p>
                    Alexander Pierce - Web Developer
                    <small>Member since Nov. 2023</small>
                  </p>
                </li>
                <li class="user-body">
                  <div class="row">
                    <div class="col-4 text-center"><a href="#">Followers</a></div>
                    <div class="col-4 text-center"><a href="#">Sales</a></div>
                    <div class="col-4 text-center"><a href="#">Friends</a></div>
                  </div>
                </li>
                <li class="user-footer">
                  <a href="#" class="btn btn-default btn-flat">Profile</a>
                  <a href="#" class="btn btn-default btn-flat float-end">Sign out</a>
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
                  <!--begin::App Content Header-->
         <div class="container d-flex align-items-center" style="min-height:60vh;">
  <div class="row justify-content-center w-100">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">Créer un contenu</div>
        <div class="card-body">
          @if ($errors->any())
            <div class="alert alert-danger">
              <ul class="mb-0">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form action="{{ route('contenu.store') }}" method="POST">
            @csrf

            <div class="mb-3">
              <label for="titre" class="form-label">Titre</label>
              <input type="text" name="titre" id="titre" value="{{ old('titre') }}" class="form-control" required>
            </div>

            <div class="mb-3">
              <label for="id_region" class="form-label">Région (ID)</label>
              <input type="number" name="id_region" id="id_region" value="{{ old('id_region') }}" class="form-control" required>
            </div>

            <div class="mb-3">
              <label for="date_creation" class="form-label">Date de création</label>
              <input type="date" name="date_creation" id="date_creation" value="{{ old('date_creation') }}" class="form-control" required>
            </div>

            <div class="mb-3">
              <label for="texte" class="form-label">Texte</label>
              <textarea name="texte" id="texte" class="form-control" rows="4" required>{{ old('texte') }}</textarea>
            </div>

            <div class="mb-3">
              <label for="statut" class="form-label">Statut</label>
              <input type="text" name="statut" id="statut" value="{{ old('statut') }}" class="form-control" required>
            </div>

            <div class="mb-3">
              <label for="id_langue" class="form-label">Langue (ID)</label>
              <input type="number" name="id_langue" id="id_langue" value="{{ old('id_langue') }}" class="form-control" required>
            </div>

            <div class="mb-3">
              <label for="id_auteur" class="form-label">Auteur (ID)</label>
              <input type="number" name="id_auteur" id="id_auteur" value="{{ old('id_auteur') }}" class="form-control" required>
            </div>

            <div class="mb-3">
              <label for="date_validation" class="form-label">Date de validation</label>
              <input type="date" name="date_validation" id="date_validation" value="{{ old('date_validation') }}" class="form-control">
            </div>

            <div class="mb-3">
              <label for="id_moderateur" class="form-label">Modérateur (ID)</label>
              <input type="number" name="id_moderateur" id="id_moderateur" value="{{ old('id_moderateur') }}" class="form-control">
            </div>

            <div class="mb-3">
              <label for="parent_id" class="form-label">Parent (ID)</label>
              <input type="number" name="parent_id" id="parent_id" value="{{ old('parent_id') }}" class="form-control">
            </div>

            <div class="d-flex justify-content-between">
              <a href="{{ route('contenu.index') }}" class="btn btn-secondary">Annuler</a>
              <button type="submit" class="btn btn-primary">Créer</button>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>
</div>   
        <!--end::App Content-->
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