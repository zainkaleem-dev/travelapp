<div class="max-w-4xl mx-auto px-4 py-10">
    <div class="rounded-2xl border border-gray-200 bg-white shadow-sm p-8 text-center">
        <div class="text-xl font-black text-gray-900">Opening branches…</div>
        <div class="mt-2 text-sm text-gray-600">{{ $company->name }}</div>
    </div>

    <script>
        window.location.href = @json(route('superadmin.companies.branches.index', $company));
    </script>
</div>

