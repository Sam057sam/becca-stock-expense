<!DOCTYPE html>
<html lang="en">
<head>
    @php
        $company = \App\Support\AppSettings::company();
        $faviconPath = $company?->company_favicon_path;
        $faviconUrl = $faviconPath ? asset('storage/' . $faviconPath) : asset('favicon.ico');
        $faviconType = match (true) {
            str_ends_with($faviconUrl, '.svg') => 'image/svg+xml',
            str_ends_with($faviconUrl, '.png') => 'image/png',
            default => 'image/x-icon',
        };
    @endphp
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name', 'Stock & Expense Manager') }}</title>
    <link rel="icon" type="{{ $faviconType }}" href="{{ $faviconUrl }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link rel="stylesheet" href="https://fonts.bunny.net/css?family=figtree:400,500,600,700">
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    @livewireStyles
    @stack('head')
</head>
<body class="min-h-screen bg-slate-100 font-[Figtree] text-slate-800">
    <div class="min-h-screen">
        @auth
        <div class="flex min-h-screen">
            <div id="sidebar-overlay" class="fixed inset-0 z-30 bg-slate-900/60 opacity-0 transition-opacity duration-200 pointer-events-none lg:hidden"></div>

            <aside id="app-sidebar" class="fixed inset-y-0 left-0 z-40 flex w-72 -translate-x-full transform flex-col bg-slate-900 text-white shadow-xl transition-transform duration-200 ease-out lg:static lg:translate-x-0 lg:shadow-none">
                <div class="flex items-center gap-3 border-b border-white/10 px-6 py-5">
                    <a wire:navigate href="{{ route('dashboard') }}" class="flex items-center gap-3 text-lg font-semibold tracking-tight" data-company-brand data-logo-class="company-logo max-h-10 w-auto rounded-md shadow-md">
                        @if ($company?->company_logo_path)
                            <img src="{{ asset('storage/' . $company->company_logo_path) }}" alt="{{ $company->company_name ?? config('app.name', 'Stock & Expense Manager') }}" class="company-logo max-h-10 w-auto rounded-md shadow-md">
                        @endif
                        <span data-company-name data-fallback-name="{{ config('app.name', 'Stock & Expense Manager') }}" class="text-base font-semibold text-white/90 {{ $company?->company_logo_path ? 'hidden' : '' }}">{{ $company?->company_name ?? config('app.name', 'Stock & Expense Manager') }}</span>
                    </a>
                </div>
                <nav class="flex-1 overflow-y-auto px-4 py-6 text-sm font-medium">
                    <div class="space-y-1">
                        <a wire:navigate href="{{ route('dashboard') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 transition-colors {{ request()->routeIs('dashboard') ? 'bg-white/10 text-sky-200' : 'text-white/80 hover:bg-white/5 hover:text-sky-200' }}">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9l8.25-6 8.25 6v10.5a1.5 1.5 0 01-1.5 1.5h-4.5a1.5 1.5 0 01-1.5-1.5V15a1.5 1.5 0 00-1.5-1.5H9.75A1.5 1.5 0 008.25 15v4.5a1.5 1.5 0 01-1.5 1.5h-4.5a1.5 1.5 0 01-1.5-1.5V9z" />
                            </svg>
                            <span>Dashboard</span>
                        </a>
                        <a wire:navigate href="{{ route('products.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 transition-colors {{ request()->routeIs('products.*') ? 'bg-white/10 text-sky-200' : 'text-white/80 hover:bg-white/5 hover:text-sky-200' }}">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 7.5h16.5m-15 3.75h13.5m-12 3.75h10.5M6 3.75h12a2.25 2.25 0 012.25 2.25v12a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18V6A2.25 2.25 0 016 3.75z" />
                            </svg>
                            <span>Products</span>
                        </a>
                        <a wire:navigate href="{{ route('warehouses.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 transition-colors {{ request()->routeIs('warehouses.*') ? 'bg-white/10 text-sky-200' : 'text-white/80 hover:bg-white/5 hover:text-sky-200' }}">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5L12 3l9 4.5v9a1.5 1.5 0 01-.879 1.371l-7.5 3.333a1.5 1.5 0 01-1.242 0l-7.5-3.333A1.5 1.5 0 013 16.5v-9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 10.5h6M9 13.5h6" />
                            </svg>
                            <span>Warehouses</span>
                        </a>
                        <a wire:navigate href="{{ route('units.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 transition-colors {{ request()->routeIs('units.*') ? 'bg-white/10 text-sky-200' : 'text-white/80 hover:bg-white/5 hover:text-sky-200' }}">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.5h16.5M3.75 19.5h16.5M6 8.25h12M6 12h12M6 15.75h12" />
                            </svg>
                            <span>Units</span>
                        </a>
                        <a wire:navigate href="{{ route('company-settings.edit') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 transition-colors {{ request()->routeIs('company-settings.*') ? 'bg-white/10 text-sky-200' : 'text-white/80 hover:bg-white/5 hover:text-sky-200' }}">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 6.75h15M6.75 6.75v-1.5a1.5 1.5 0 011.5-1.5h7.5a1.5 1.5 0 011.5 1.5v1.5m-1.5 0v12a1.5 1.5 0 01-1.5 1.5H9.75a1.5 1.5 0 01-1.5-1.5v-12" />
                            </svg>
                            <span>Company</span>
                        </a>

                        <a wire:navigate href="{{ route('customers.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 transition-colors {{ request()->routeIs('customers.*') ? 'bg-white/10 text-sky-200' : 'text-white/80 hover:bg-white/5 hover:text-sky-200' }}">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                            </svg>
                            <span>Customers</span>
                        </a>

                        <a wire:navigate href="{{ route('suppliers.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 transition-colors {{ request()->routeIs('suppliers.*') ? 'bg-white/10 text-sky-200' : 'text-white/80 hover:bg-white/5 hover:text-sky-200' }}">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5h18M3 12h12M3 16.5h8" />
                            </svg>
                            <span>Suppliers</span>
                        </a>
                        <a wire:navigate href="{{ route('quotes.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 transition-colors {{ request()->routeIs('quotes.*') ? 'bg-white/10 text-sky-200' : 'text-white/80 hover:bg-white/5 hover:text-sky-200' }}">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.25h13.5a.75.75 0 01.75.75v12a.75.75 0 01-.75.75H5.25a.75.75 0 01-.75-.75v-12a.75.75 0 01.75-.75z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 8.25h7.5M8.25 12h4.5M8.25 15.75h3" />
                            </svg>
                            <span>Quotes</span>
                        </a>
                        <a wire:navigate href="{{ route('purchases.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 transition-colors {{ request()->routeIs('purchases.*') ? 'bg-white/10 text-sky-200' : 'text-white/80 hover:bg-white/5 hover:text-sky-200' }}">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v12.75" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 12l4.5 4.5 4.5-4.5" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5h15" />
                            </svg>
                            <span>Purchases</span>
                        </a>

                        <a wire:navigate href="{{ route('expenses.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 transition-colors {{ request()->routeIs('expenses.*') ? 'bg-white/10 text-sky-200' : 'text-white/80 hover:bg-white/5 hover:text-sky-200' }}">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 4.5h10.5a1.5 1.5 0 011.5 1.5v12.75l-3-2.25-3 2.25-3-2.25-3 2.25V6a1.5 1.5 0 011.5-1.5z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9h4.5M9.75 12h3" />
                            </svg>
                            <span>Expenses</span>
                        </a>

                    </div>
                    @if (auth()->user()?->hasRole('admin'))
                        <div class="mt-4 space-y-1 border-t border-white/10 pt-4">
                            <a wire:navigate href="{{ route('admin.users') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 transition-colors {{ request()->routeIs('admin.users') ? 'bg-white/10 text-sky-200' : 'text-white/80 hover:bg-white/5 hover:text-sky-200' }}">
                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372c1.317 0 2.569-.258 3.708-.726a4.125 4.125 0 00-7.341-1.831M15 19.128v-.003c0-1.113-.285-2.16-.784-3.069M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766a6.375 6.375 0 0111.238-4.241M12 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span>Accounts</span>
                            </a>
                            <a wire:navigate href="{{ route('admin.roles') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 transition-colors {{ request()->routeIs('admin.roles') ? 'bg-white/10 text-sky-200' : 'text-white/80 hover:bg-white/5 hover:text-sky-200' }}">
                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l3 3M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Roles</span>
                            </a>
                        </div>
                    @endif
                </nav>
            </aside>

            <div class="flex flex-1 flex-col">
                <nav class="bg-slate-900 text-white shadow-lg">
                    <div class="flex h-16 items-center justify-between px-4 sm:px-6 lg:px-8">
                        <div class="flex items-center gap-3">
                            <button id="sidebar-toggle" type="button" class="rounded-lg border border-white/20 p-2 text-white transition hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-white/40 lg:hidden" aria-controls="app-sidebar" aria-expanded="false">
                                <span class="sr-only">Toggle navigation</span>
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5" />
                                </svg>
                            </button>
                            <a wire:navigate href="{{ route('dashboard') }}" class="flex items-center gap-3 text-lg font-semibold tracking-tight lg:hidden" data-company-brand data-logo-class="company-logo max-h-8 w-auto rounded-md shadow-md">
                                @if ($company?->company_logo_path)
                                    <img src="{{ asset('storage/' . $company->company_logo_path) }}" alt="{{ $company->company_name ?? config('app.name', 'Stock & Expense Manager') }}" class="company-logo max-h-8 w-auto rounded-md shadow-md">
                                @endif
                                <span data-company-name data-fallback-name="{{ config('app.name', 'Stock & Expense Manager') }}" class="text-base font-semibold text-white/90 {{ $company?->company_logo_path ? 'hidden' : '' }}">{{ $company?->company_name ?? config('app.name', 'Stock & Expense Manager') }}</span>
                            </a>
                        </div>
                        <div class="flex items-center gap-4 text-sm text-slate-200">
                            <div class="hidden items-center gap-2 md:flex">
                                <div class="h-2 w-2 animate-pulse rounded-full bg-emerald-400"></div>
                                <span class="uppercase tracking-wide">Live</span>
                            </div>
                            <div class="hidden text-right md:block">
                                <div class="font-semibold text-white/90">{{ auth()->user()->name }}</div>
                                <div class="text-xs text-white/70">{{ now()->format('d M Y') }}</div>
                            </div>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="rounded-lg border border-white/20 px-3 py-1 text-xs font-semibold text-white hover:bg-white/10">Sign out</button>
                            </form>
                        </div>
                    </div>
                </nav>

                <header class="bg-white shadow-sm">
                    <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-6 sm:px-6 lg:px-8">
                        <div>
                            <h1 class="text-2xl font-semibold text-slate-900">{{ $header ?? ($title ?? 'Overview') }}</h1>
                            @isset($subheader)
                                <p class="mt-1 text-sm text-slate-500">{{ $subheader }}</p>
                            @endisset
                        </div>
                        @isset($actions)
                            <div class="flex items-center gap-2">{{ $actions }}</div>
                        @endisset
                    </div>
                </header>

                <main class="flex-1 mx-auto max-w-7xl px-4 pb-16 pt-8 sm:px-6 lg:px-8">
                    @if (session('status'))
                        <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 shadow-sm">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-6 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700 shadow-sm">
                            <h2 class="font-semibold">Please fix the following:</h2>
                            <ul class="mt-2 list-disc space-y-1 pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @yield('content')

                    {{ $slot ?? '' }}
                </main>
            </div>
        </div>
        @else
        <main class="px-4 py-12">
            @if (session('status'))
                <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 shadow-sm">
                    {{ session('status') }}
                </div>
            @endif

            @yield('content')

            {{ $slot ?? '' }}
        </main>
        @endauth
    </div>

    <div id="toast-root" class="pointer-events-none fixed inset-x-0 top-5 z-50 flex flex-col items-end gap-3 px-4 sm:px-6 lg:px-8"></div>

    @livewireScripts(['navigate' => true])
    <script>
        (function () {
            var sidebar;
            var toggleButton;
            var overlay;
            var livewireUtilitiesInitialised = false;

            function attachListener(element, eventName, key, handler) {
                if (!element) {
                    return;
                }
                if (element[key]) {
                    element.removeEventListener(eventName, element[key]);
                }
                element[key] = handler;
                element.addEventListener(eventName, handler);
            }

            function openSidebar() {
                if (!sidebar) {
                    return;
                }
                sidebar.classList.remove('-translate-x-full');
                if (overlay) {
                    overlay.classList.remove('pointer-events-none');
                    overlay.classList.add('opacity-100');
                }
                document.body.classList.add('overflow-hidden');
                if (toggleButton) {
                    toggleButton.setAttribute('aria-expanded', 'true');
                }
            }

            function closeSidebar(skipFocus) {
                if (!sidebar) {
                    return;
                }
                sidebar.classList.add('-translate-x-full');
                if (overlay) {
                    overlay.classList.add('pointer-events-none');
                    overlay.classList.remove('opacity-100');
                }
                document.body.classList.remove('overflow-hidden');
                if (toggleButton) {
                    toggleButton.setAttribute('aria-expanded', 'false');
                    if (!skipFocus) {
                        try {
                            toggleButton.focus();
                        } catch (error) {
                            /* ignore focus errors */
                        }
                    }
                }
            }

            function bindNavigationLinks() {
                if (!sidebar) {
                    return;
                }
                var links = sidebar.querySelectorAll('a');
                for (var i = 0; i < links.length; i += 1) {
                    (function (link) {
                        if (!link || !link.hasAttribute('wire:navigate')) {
                            return;
                        }
                        attachListener(link, 'click', '__sidebarNavHandler', function () {
                            if (window.innerWidth < 1024) {
                                closeSidebar(true);
                            }
                        });
                    })(links[i]);
                }
            }

            function bindResizeWatcher() {
                if (window.__appSidebarResizeHandler) {
                    window.removeEventListener('resize', window.__appSidebarResizeHandler);
                }
                window.__appSidebarResizeHandler = function () {
                    if (window.innerWidth >= 1024) {
                        closeSidebar(true);
                    }
                };
                window.addEventListener('resize', window.__appSidebarResizeHandler);
            }

            function attachSidebarHandlers() {
                sidebar = document.getElementById('app-sidebar');
                toggleButton = document.getElementById('sidebar-toggle');
                overlay = document.getElementById('sidebar-overlay');

                if (!sidebar) {
                    return;
                }

                attachListener(toggleButton, 'click', '__sidebarToggleHandler', function () {
                    var isOpen = !sidebar.classList.contains('-translate-x-full');
                    if (isOpen) {
                        closeSidebar(false);
                    } else {
                        openSidebar();
                    }
                });

                attachListener(overlay, 'click', '__sidebarOverlayHandler', function () {
                    closeSidebar(true);
                });

                bindResizeWatcher();
                bindNavigationLinks();

                window.__appOpenSidebar = openSidebar;
                window.__appCloseSidebar = closeSidebar;
            }

            function initialiseLivewireUtilities() {
                if (livewireUtilitiesInitialised) {
                    return;
                }
                livewireUtilitiesInitialised = true;

                var palette = {
                    success: { border: 'border-emerald-500', badge: 'bg-emerald-100 text-emerald-700', label: 'Success' },
                    error: { border: 'border-rose-500', badge: 'bg-rose-100 text-rose-700', label: 'Error' },
                    warning: { border: 'border-amber-500', badge: 'bg-amber-100 text-amber-700', label: 'Warning' },
                    info: { border: 'border-sky-500', badge: 'bg-sky-100 text-sky-700', label: 'Info' }
                };

                window.Livewire.on('notify', function (payload) {
                    if (!payload || !payload.message) {
                        return;
                    }

                    var type = payload.type || 'success';
                    var root = document.getElementById('toast-root');
                    if (!root) {
                        return;
                    }

                    var paletteEntry = palette[type] || palette.success;
                    var toast = document.createElement('div');
                    toast.className = 'pointer-events-auto flex w-full max-w-sm items-start gap-3 rounded-xl border-l-4 ' + paletteEntry.border + ' bg-white px-4 py-3 text-sm shadow-xl ring-1 ring-slate-900/10 transition-all duration-200 ease-out';
                    toast.style.opacity = '0';
                    toast.style.transform = 'translateY(-10px)';

                    var badge = document.createElement('div');
                    badge.className = 'mt-0.5 flex h-8 w-8 items-center justify-center rounded-full text-xs font-semibold ' + paletteEntry.badge;
                    badge.textContent = paletteEntry.label;

                    var body = document.createElement('div');
                    body.className = 'flex-1 text-sm font-medium text-slate-800';
                    body.textContent = payload.message;

                    var dismissButton = document.createElement('button');
                    dismissButton.type = 'button';
                    dismissButton.className = 'mt-0.5 text-xs font-semibold uppercase tracking-wide text-slate-400 transition hover:text-slate-600';
                    dismissButton.textContent = 'Dismiss';

                    var dismiss = function () {
                        toast.style.opacity = '0';
                        toast.style.transform = 'translateY(-6px)';
                        setTimeout(function () {
                            if (toast.parentNode) {
                                toast.parentNode.removeChild(toast);
                            }
                        }, 200);
                        dismissButton.removeEventListener('click', dismiss);
                    };

                    dismissButton.addEventListener('click', dismiss);

                    toast.appendChild(badge);
                    toast.appendChild(body);
                    toast.appendChild(dismissButton);

                    root.appendChild(toast);

                    requestAnimationFrame(function () {
                        toast.style.opacity = '1';
                        toast.style.transform = 'translateY(0)';
                    });

                    setTimeout(function () {
                        if (document.body.contains(toast)) {
                            dismiss();
                        }
                    }, 4000);
                });

                window.Livewire.on('company-settings-updated', function (payload) {
                    var name = payload && payload.name ? payload.name : '';
                    var logo = payload && payload.logo ? payload.logo : '';
                    var favicon = payload && payload.favicon ? payload.favicon : '';

                    var brandLinks = document.querySelectorAll('[data-company-brand]');
                    for (var i = 0; i < brandLinks.length; i += 1) {
                        var brandLink = brandLinks[i];
                        var nameSpan = brandLink.querySelector('[data-company-name]');
                        var fallbackName = '';

                        if (nameSpan) {
                            fallbackName = nameSpan.getAttribute('data-fallback-name') || nameSpan.textContent;
                            nameSpan.textContent = name && String(name).trim() ? name : fallbackName;
                        }

                        var logoImg = brandLink.querySelector('img.company-logo');
                        var hasLogo = logo && String(logo).trim();

                        if (hasLogo) {
                            if (!logoImg) {
                                logoImg = document.createElement('img');
                                var logoClass = brandLink.getAttribute('data-logo-class') || 'company-logo max-h-10 w-auto rounded-md shadow-md';
                                logoImg.className = logoClass;
                                if (nameSpan) {
                                    brandLink.insertBefore(logoImg, nameSpan);
                                } else {
                                    brandLink.appendChild(logoImg);
                                }
                            }
                            logoImg.src = logo;
                            logoImg.style.display = '';
                            var altText = (name && String(name).trim()) || fallbackName || '';
                            if (altText) {
                                logoImg.alt = altText;
                            }
                            if (nameSpan) {
                                nameSpan.classList.add('hidden');
                            }
                        } else {
                            if (logoImg && logoImg.parentNode) {
                                logoImg.parentNode.removeChild(logoImg);
                            }
                            if (nameSpan) {
                                nameSpan.classList.remove('hidden');
                            }
                        }
                    }

                    var fallbackFavicon = "{{ asset('favicon.ico') }}";
                    var nextFaviconHref = favicon && String(favicon).trim() ? favicon : fallbackFavicon;
                    var faviconLink = document.querySelector('link[rel="icon"]');

                    function resolveFaviconType(href) {
                        if (!href) {
                            return 'image/x-icon';
                        }
                        var lowerHref = href.toLowerCase();
                        if (lowerHref.indexOf('.svg') !== -1) {
                            return 'image/svg+xml';
                        }
                        if (lowerHref.indexOf('.png') !== -1) {
                            return 'image/png';
                        }
                        if (lowerHref.indexOf('.ico') !== -1) {
                            return 'image/x-icon';
                        }
                        return 'image/x-icon';
                    }

                    if (faviconLink) {
                        faviconLink.href = nextFaviconHref;
                        faviconLink.type = resolveFaviconType(nextFaviconHref);
                    } else if (nextFaviconHref) {
                        var link = document.createElement('link');
                        link.rel = 'icon';
                        link.href = nextFaviconHref;
                        link.type = resolveFaviconType(nextFaviconHref);
                        document.head.appendChild(link);
                    }
                });
            }

            function handleNavigate() {
                if (typeof window.__appCloseSidebar === 'function') {
                    window.__appCloseSidebar(true);
                }
                setTimeout(attachSidebarHandlers, 0);
            }

            attachSidebarHandlers();

            document.addEventListener('DOMContentLoaded', attachSidebarHandlers);

            document.addEventListener('livewire:init', function () {
                initialiseLivewireUtilities();
                attachSidebarHandlers();
            });

            if (window.Livewire && typeof window.Livewire.on === 'function') {
                initialiseLivewireUtilities();
            }

            document.addEventListener('livewire:navigate', handleNavigate);
        })();
    </script>
    @stack('scripts')
</body>
</html>









