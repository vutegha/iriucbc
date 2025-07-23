<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Institut de Recherche Intégré - IRI-UCBC')</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logos/iri-favicon.png') }}">
    
    <!-- Meta Tags pour SEO -->
    <meta name="description" content="@yield('description', 'Institut de Recherche Intégré de l\'Université Chrétienne Bilingue du Congo - Recherche, Innovation et Développement')">
    <meta name="keywords" content="@yield('keywords', 'IRI, UCBC, recherche, innovation, développement, Congo, université')">
    <meta name="author" content="IRI-UCBC">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="@yield('title', 'Institut de Recherche Intégré - IRI-UCBC')">
    <meta property="og:description" content="@yield('description', 'Institut de Recherche Intégré de l\'Université Chrétienne Bilingue du Congo')">
    <meta property="og:image" content="{{ asset('assets/img/logos/iri-logo.png') }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    
    <!-- TailwindCSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts - Poppins et Inter pour un look moderne -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Custom CSS basé sur la charte graphique IRI -->
    <style>
        :root {
            --iri-primary: #1e472f;      /* Vert foncé principal */
            --iri-secondary: #2d5a3f;    /* Vert moyen */
            --iri-accent: #d2691e;       /* Orange/rouge accent */
            --iri-light: #f0f9f4;        /* Vert très clair */
            --iri-gold: #b8860b;         /* Or pour les détails */
            --iri-gray: #64748b;         /* Gris moderne */
            --iri-dark: #1a1a1a;         /* Noir moderne */
        }
        
        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
            color: var(--iri-dark);
        }
        
        .font-poppins {
            font-family: 'Poppins', sans-serif;
        }
        
        .text-iri-primary { color: var(--iri-primary); }
        .text-iri-secondary { color: var(--iri-secondary); }
        .text-iri-accent { color: var(--iri-accent); }
        .text-iri-gold { color: var(--iri-gold); }
        .text-iri-gray { color: var(--iri-gray); }
        
        .bg-iri-primary { background-color: var(--iri-primary); }
        .bg-iri-secondary { background-color: var(--iri-secondary); }
        .bg-iri-accent { background-color: var(--iri-accent); }
        .bg-iri-light { background-color: var(--iri-light); }
        .bg-iri-gold { background-color: var(--iri-gold); }
        
        .border-iri-primary { border-color: var(--iri-primary); }
        .border-iri-accent { border-color: var(--iri-accent); }
        
        /* Gradient institutionnel */
        .gradient-iri {
            background: linear-gradient(135deg, var(--iri-primary) 0%, var(--iri-secondary) 100%);
        }
        
        .gradient-iri-accent {
            background: linear-gradient(135deg, var(--iri-accent) 0%, var(--iri-gold) 100%);
        }
        
        /* Animations et transitions */
        .transition-all-300 {
            transition: all 0.3s ease;
        }
        
        .hover-scale:hover {
            transform: scale(1.05);
        }
        
        .hover-lift:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        /* Styles pour les boutons */
        .btn-iri-primary {
            background: var(--iri-primary);
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: 2px solid var(--iri-primary);
        }
        
        .btn-iri-primary:hover {
            background: var(--iri-secondary);
            border-color: var(--iri-secondary);
            transform: translateY(-1px);
        }
        
        .btn-iri-accent {
            background: var(--iri-accent);
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: 2px solid var(--iri-accent);
        }
        
        .btn-iri-accent:hover {
            background: var(--iri-gold);
            border-color: var(--iri-gold);
            transform: translateY(-1px);
        }
        
        /* Styles pour les cartes */
        .card-iri {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
            transition: all 0.3s ease;
        }
        
        .card-iri:hover {
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }
        
        /* Styles pour la navigation */
        .nav-iri {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(30, 71, 47, 0.1);
        }
        
        .nav-iri.scrolled {
            background: rgba(255, 255, 255, 0.98);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        /* Styles pour le footer */
        .footer-iri {
            background: linear-gradient(135deg, var(--iri-primary) 0%, var(--iri-secondary) 100%);
            color: white;
        }
        
        /* Animations personnalisées */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-fadeInUp {
            animation: fadeInUp 0.6s ease forwards;
        }
        
        .animate-delay-200 {
            animation-delay: 0.2s;
        }
        
        .animate-delay-400 {
            animation-delay: 0.4s;
        }
        
        /* Styles pour les liens */
        .link-iri {
            color: var(--iri-primary);
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .link-iri:hover {
            color: var(--iri-accent);
        }
        
        .link-iri::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--iri-accent);
            transition: width 0.3s ease;
        }
        
        .link-iri:hover::after {
            width: 100%;
        }
        
        /* Responsive design */
        @media (max-width: 768px) {
            .mobile-menu {
                background: rgba(255, 255, 255, 0.98);
                backdrop-filter: blur(10px);
            }
        }
        
        /* Styles pour les sections */
        .section-iri {
            padding: 80px 0;
        }
        
        .section-iri-alt {
            background: var(--iri-light);
            padding: 80px 0;
        }
        
        /* Styles pour les titres */
        .title-iri {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            color: var(--iri-primary);
            line-height: 1.2;
        }
        
        .subtitle-iri {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            color: var(--iri-secondary);
            line-height: 1.3;
        }
        
        /* Styles pour les formulaires */
        .form-iri input,
        .form-iri textarea,
        .form-iri select {
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            padding: 12px 16px;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        
        .form-iri input:focus,
        .form-iri textarea:focus,
        .form-iri select:focus {
            outline: none;
            border-color: var(--iri-primary);
            box-shadow: 0 0 0 3px rgba(30, 71, 47, 0.1);
        }
        
        /* Styles pour les alertes */
        .alert-iri-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            border-radius: 8px;
            padding: 12px 16px;
        }
        
        .alert-iri-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            border-radius: 8px;
            padding: 12px 16px;
        }
        
        /* Styles pour les modals */
        .modal-iri {
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
        }
        
        .modal-content-iri {
            background: white;
            border-radius: 12px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        }
        
        /* Styles pour les badges */
        .badge-iri {
            background: var(--iri-primary);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        /* Styles pour les boutons secondaires et outline */
        .btn-iri-secondary {
            background: var(--iri-secondary);
            color: white;
            border: 2px solid var(--iri-secondary);
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .btn-iri-secondary:hover {
            background: #c55a00;
            border-color: #c55a00;
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(210, 105, 30, 0.3);
        }
        
        .btn-iri-outline {
            background: transparent;
            color: var(--iri-primary);
            border: 2px solid var(--iri-primary);
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .btn-iri-outline:hover {
            background: var(--iri-primary);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(30, 71, 47, 0.3);
        }
        
        /* Styles pour les boutons de succès et d'erreur */
        .btn-iri-success {
            background: #28a745;
            color: white;
            border: 2px solid #28a745;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .btn-iri-success:hover {
            background: #218838;
            border-color: #218838;
        }
        
        .btn-iri-error {
            background: #dc3545;
            color: white;
            border: 2px solid #dc3545;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .btn-iri-error:hover {
            background: #c82333;
            border-color: #c82333;
        }
        
        /* Styles pour les tableaux */
        .table-iri-container {
            overflow-x: auto;
            margin: 16px 0;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .table-iri {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .table-iri th {
            background: var(--iri-primary);
            color: white;
            padding: 16px;
            text-align: left;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 14px;
        }
        
        .table-iri td {
            padding: 16px;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .table-iri tbody tr:hover {
            background: #f8f9fa;
        }
        
        .table-iri tbody tr:last-child td {
            border-bottom: none;
        }
        
        /* Styles pour les inputs et labels de formulaire */
        .form-input {
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            padding: 12px 16px;
            font-size: 16px;
            transition: all 0.3s ease;
            width: 100%;
        }
        
        .form-input:focus {
            outline: none;
            border-color: var(--iri-primary);
            box-shadow: 0 0 0 3px rgba(30, 71, 47, 0.1);
        }
        
        .form-textarea {
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            padding: 12px 16px;
            font-size: 16px;
            transition: all 0.3s ease;
            width: 100%;
            resize: vertical;
            min-height: 120px;
        }
        
        .form-textarea:focus {
            outline: none;
            border-color: var(--iri-primary);
            box-shadow: 0 0 0 3px rgba(30, 71, 47, 0.1);
        }
        
        .form-select {
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            padding: 12px 16px;
            font-size: 16px;
            transition: all 0.3s ease;
            width: 100%;
            background: white;
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 12px center;
            background-repeat: no-repeat;
            background-size: 16px;
            padding-right: 48px;
        }
        
        .form-select:focus {
            outline: none;
            border-color: var(--iri-primary);
            box-shadow: 0 0 0 3px rgba(30, 71, 47, 0.1);
        }
        
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--iri-primary);
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        /* Styles pour les alertes supplémentaires */
        .alert-iri-info {
            background: #cce7ff;
            color: #0066cc;
            border: 1px solid #99d6ff;
            border-radius: 8px;
            padding: 12px 16px;
        }
        
        .alert-iri-warning {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 12px 16px;
        }
        
        /* Styles pour la navigation (breadcrumb et pagination) */
        .breadcrumb-iri {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 16px 0;
            padding: 12px 0;
        }
        
        .breadcrumb-item {
            color: var(--iri-primary);
            text-decoration: none;
            font-weight: 500;
        }
        
        .breadcrumb-item:hover {
            text-decoration: underline;
        }
        
        .breadcrumb-current {
            color: var(--iri-secondary);
            font-weight: 600;
        }
        
        .breadcrumb-separator {
            color: #6b7280;
            font-weight: 500;
        }
        
        .pagination-iri {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 24px 0;
            justify-content: center;
        }
        
        .pagination-item {
            padding: 8px 16px;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            text-decoration: none;
            color: var(--iri-primary);
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .pagination-item:hover {
            background: var(--iri-primary);
            color: white;
            border-color: var(--iri-primary);
        }
        
        .pagination-item.active {
            background: var(--iri-primary);
            color: white;
            border-color: var(--iri-primary);
        }
        
        .pagination-dots {
            color: #6b7280;
            font-weight: 500;
        }
            font-size: 14px;
            font-weight: 600;
        }
        
        .badge-iri-accent {
            background: var(--iri-accent);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
        }
        
        /* Styles pour les séparateurs */
        .divider-iri {
            height: 2px;
            background: linear-gradient(90deg, var(--iri-primary), var(--iri-accent));
            border-radius: 1px;
            margin: 40px 0;
        }
        
        /* Styles pour le loader */
        .loader-iri {
            border: 4px solid #f3f4f6;
            border-top: 4px solid var(--iri-primary);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
    
    <!-- TailwindCSS Configuration -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'iri-primary': '#1e472f',
                        'iri-secondary': '#2d5a3f',
                        'iri-accent': '#d2691e',
                        'iri-light': '#f0f9f4',
                        'iri-gold': '#b8860b',
                        'iri-gray': '#64748b',
                        'iri-dark': '#1a1a1a'
                    },
                    fontFamily: {
                        'poppins': ['Poppins', 'sans-serif'],
                        'inter': ['Inter', 'sans-serif']
                    },
                    spacing: {
                        '18': '4.5rem',
                        '88': '22rem'
                    },
                    animation: {
                        'fadeInUp': 'fadeInUp 0.6s ease forwards',
                        'bounce-slow': 'bounce 2s infinite',
                        'pulse-slow': 'pulse 3s infinite'
                    }
                }
            }
        }
    </script>
</head>
<body class="font-inter antialiased bg-gray-50">

    <!-- Navigation -->
    @include('partials.menu')

    <!-- Contenu principal -->
    <main class="min-h-screen">
        @yield('content')
    </main>

    <!-- Footer -->
    @include('partials.footer')

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    
    <!-- Script pour la navigation sticky -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const navbar = document.querySelector('.nav-iri');
            
            if (navbar) {
                window.addEventListener('scroll', function() {
                    if (window.scrollY > 100) {
                        navbar.classList.add('scrolled');
                    } else {
                        navbar.classList.remove('scrolled');
                    }
                });
            }
            
            // Menu mobile
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            
            if (mobileMenuButton && mobileMenu) {
                mobileMenuButton.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                });
            }
            
            // Fermer le menu mobile en cliquant sur un lien
            const mobileMenuLinks = document.querySelectorAll('#mobile-menu a');
            mobileMenuLinks.forEach(link => {
                link.addEventListener('click', function() {
                    mobileMenu.classList.add('hidden');
                });
            });
            
            // Animations au scroll
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };
            
            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.animationDelay = entry.target.dataset.delay || '0s';
                        entry.target.classList.add('animate-fadeInUp');
                    }
                });
            }, observerOptions);
            
            // Observer tous les éléments avec la classe animate-on-scroll
            document.querySelectorAll('.animate-on-scroll').forEach(el => {
                observer.observe(el);
            });
        });
    </script>
    
    <!-- Scripts personnalisés -->
    @stack('scripts')
</body>
</html>
