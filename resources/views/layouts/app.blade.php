<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'FlightBook' }}</title>

    {{-- Tailwind CSS – replace with Vite build in production --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Google Font --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    @php
        $primaryBg = '#2ab4c0';
        $primaryFg = '#ffffff';
        $isImpersonating = session()->has('impersonated_by') && count(session('impersonated_by', [])) > 0;
        $isSuperAdmin = auth()->check() && \Illuminate\Support\Facades\DB::table('model_has_roles')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->where('model_has_roles.model_id', auth()->id())
            ->where('model_has_roles.model_type', \App\Models\User::class)
            ->where('roles.name', 'Super Admin')
            ->whereNull('model_has_roles.company_id')
            ->exists();
        
        $useDynamicBranding = auth()->check() && ($isImpersonating || !$isSuperAdmin);

        if ($useDynamicBranding && auth()->user()->company) {
            $settings = auth()->user()->company->settings;
            $primaryBg = $settings['background_color'] ?? '#2ab4c0';
            $primaryFg = $settings['foreground_color'] ?? '#ffffff';
        }

        $brand500 = $useDynamicBranding ? $primaryBg : '#3b82f6';
    @endphp

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] },
                    colors: {
                        brand: { 500: '{{ $brand500 }}' },
                        gray: { 50: '#c8cfd6', 500: '#6b6d80' },
                    }
                }
            }
        }
    </script>

    @if($useDynamicBranding)
    <style>
        :root {
            --primary-bg: {{ $primaryBg }};
            --primary-fg: {{ $primaryFg }};
        }

        /* Global Branding Overrides */
        [class*="bg-[#2ab4c0]"], [class*="bg-teal-"], .bg-teal-500, .bg-teal-600, 
        [class*="hover:bg-[#2ab4c0]"]:hover, [class*="hover:bg-teal-"],
        [class*="bg-[#229aa4]"], [class*="hover:bg-[#229aa4]"]:hover,
        [class*="bg-[#1f9aa6]"], [class*="hover:bg-[#1f9aa6]"]:hover {
            background-color: var(--primary-bg) !important;
            color: var(--primary-fg) !important;
        }

        /* Handle opacity variants for backgrounds */
        [class*="bg-[#2ab4c0]/"], [class*="bg-teal-500/"], [class*="bg-teal-600/"] {
            background-color: color-mix(in srgb, var(--primary-bg), transparent 90%) !important;
            color: var(--primary-bg) !important;
        }
        
        [class*="text-[#2ab4c0]"], [class*="text-teal-"],
        [class*="text-[#1f9aa6]"], [class*="hover:text-[#1f9aa6]"]:hover,
        [class*="hover:text-[#2ab4c0]"]:hover, [class*="group-hover:text-[#2ab4c0]"]:hover {
            color: color-mix(in srgb, var(--primary-bg), black 15%) !important;
        }
        
        [class*="border-[#2ab4c0]"], [class*="border-teal-"],
        [class*="focus:border-[#2ab4c0]"]:focus, [class*="focus-within:border-[#2ab4c0]"]:focus-within {
            border-color: var(--primary-bg) !important;
        }

        /* Border opacity variants */
        [class*="border-[#2ab4c0]/"], [class*="border-teal-500/"] {
            border-color: color-mix(in srgb, var(--primary-bg), transparent 70%) !important;
        }

        [class*="focus:ring-[#2ab4c0]"]:focus {
            --tw-ring-color: var(--primary-bg) !important;
        }

        /* Light background variants using color-mix */
        [class*="bg-"][class*="eaf9fb"], [class*="bg-"][class*="f2feff"], .bg-teal-50, .hover\:bg-teal-50:hover {
            background-color: color-mix(in srgb, var(--primary-bg), white 97%) !important;
        }

        /* Gradient overrides */
        [class*="from-"][class*="2ab4c0"], .from-teal-500, [class*="to-"][class*="f2feff"] {
            --tw-gradient-from: var(--primary-bg) !important;
            --tw-gradient-to: color-mix(in srgb, var(--primary-bg), white 98%) !important;
            --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to) !important;
        }
        
        /* Progress bars and status indicators */
        [class*="peer-checked:bg-[#2ab4c0]"]:checked ~ [class*="peer-checked:bg-[#2ab4c0]"],
        [class*="peer-checked:bg-[#2ab4c0]"] {
            background-color: var(--primary-bg) !important;
        }

        /* Specific overrides for common components */
        thead[class*="bg-[#2ab4c0]"] th, tr[class*="bg-[#2ab4c0]"] th,
        .table-header-branded {
            background-color: var(--primary-bg) !important;
            color: var(--primary-fg) !important;
        }

        /* Navigation active states */
        .is-active, .active-tab {
            background-color: var(--primary-bg) !important;
            color: var(--primary-fg) !important;
        }

        .admin-menu-item.is-active {
            background-color: color-mix(in srgb, var(--primary-bg), transparent 92%) !important;
            color: var(--primary-bg) !important;
            font-weight: 700 !important;
        }
    </style>
    @endif

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .auth-shell {
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 18px 12px;
        }

        .auth-card {
            width: 100%;
            max-width: 440px;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 14px 40px rgba(15, 23, 42, 0.08);
        }

        .auth-card__header {
            padding: 16px 16px 10px;
        }

        .auth-card__title {
            margin: 0;
            font-size: 18px;
            font-weight: 800;
            color: #0f172a;
        }

        .auth-card__subtitle {
            margin-top: 6px;
            color: #64748b;
            font-size: 12px;
        }

        .auth-card__body {
            padding: 0 16px 16px;
        }

        .auth-card__footer {
            padding: 12px 16px;
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            font-size: 11px;
            color: #64748b;
        }

        .input-field {
            width: 100%;
            padding: 9px 12px 9px 36px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 12px;
            color: #1e293b;
            background: #f8fafc;
            transition: all 0.15s ease;
        }

        .input-field::placeholder {
            color: #94a3b8;
        }

        .input-field:focus {
            outline: none;
            border-color: #2ab4c0;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(42, 180, 192, 0.1);
        }

        .login-btn {
            background: linear-gradient(135deg, #2ab4c0 0%, #6366f1 100%);
            transition: all 0.2s ease;
            position: relative;
            overflow: hidden;
        }

        .login-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.18), transparent);
            transition: left 0.4s ease;
        }

        .login-btn:hover::before {
            left: 100%;
        }

        .login-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.28);
        }

        .login-btn:active {
            transform: translateY(0);
        }

        .login-btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }


        .flight-dot-sm {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #6366f1;
            flex-shrink: 0;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fu1 {
            animation: fadeUp 0.35s 0.05s ease both;
        }

        .fu2 {
            animation: fadeUp 0.35s 0.10s ease both;
        }

        .fu3 {
            animation: fadeUp 0.35s 0.15s ease both;
        }

        .fu4 {
            animation: fadeUp 0.35s 0.20s ease both;
        }

        .fu5 {
            animation: fadeUp 0.35s 0.25s ease both;
        }

        .fu6 {
            animation: fadeUp 0.35s 0.30s ease both;
        }

        .fu7 {
            animation: fadeUp 0.35s 0.35s ease both;
        }

        .fu8 {
            animation: fadeUp 0.35s 0.40s ease both;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .animate-spin {
            animation: spin 0.8s linear infinite;
        }

        input[type="checkbox"] {
            accent-color: #6366f1;
        }

        /* Custom styled scrollbar for horizontal navigation */
        .custom-scrollbar::-webkit-scrollbar {
            height: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
            transition: background 0.2s ease;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #2ab4c0;
        }

        /* Support for Firefox */
        .custom-scrollbar {
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 #f1f5f9;
        }
    </style>

    @livewireStyles
</head>

<body class="bg-slate-100 min-h-screen flex flex-col items-center text-xs">
    <div class="w-full flex flex-col flex-1">
        {{ $slot }}
    </div>
    @livewireScripts
</body>

</html>