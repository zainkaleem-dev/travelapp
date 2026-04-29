<div class="w-full" x-data="dashboardCharts()" x-init="initCharts()">
    <div class="px-1 py-1 w-full">
        <!-- Header -->
        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm mb-6">
            <div class="px-6 py-5 bg-gradient-to-r from-white to-[#f2feff] border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-black text-gray-900 tracking-tight">Dashboard</h1>
                        <p class="mt-1 text-sm text-gray-500">Welcome back, <span class="font-semibold text-gray-700">{{ auth()->user()->name }}</span></p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Today</p>
                        <p class="text-sm font-bold text-gray-700">{{ now()->format('D, M d, Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- KPI Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-{{ $isSuperAdmin ? '4' : '3' }} gap-4 mb-6">
            <!-- Organizations / Partners -->
            <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm hover:shadow-md transition-all duration-200 flex items-center justify-between group">
                <div class="flex items-center gap-4">
                    <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-gradient-to-br from-[#2ab4c0] to-[#1e9ba6] flex items-center justify-center shadow-sm group-hover:scale-105 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400">{{ $isSuperAdmin ? 'Organizations' : 'Partners' }}</p>
                        <p class="text-xs text-gray-500 font-medium truncate">{{ $isSuperAdmin ? 'Total Registered' : 'Managed Partners' }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-3xl font-black text-gray-900 tabular-nums">{{ $isSuperAdmin ? $stats['organizations'] : $stats['partners'] }}</p>
                </div>
            </div>

            <!-- Branches -->
            <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm hover:shadow-md transition-all duration-200 flex items-center justify-between group">
                <div class="flex items-center gap-4">
                    <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-gradient-to-br from-violet-500 to-violet-600 flex items-center justify-center shadow-sm group-hover:scale-105 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Branches</p>
                        <p class="text-xs text-gray-500 font-medium truncate">Active Locations</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-3xl font-black text-gray-900 tabular-nums">{{ $stats['branches'] }}</p>
                </div>
            </div>

            <!-- Users -->
            <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm hover:shadow-md transition-all duration-200 flex items-center justify-between group">
                <div class="flex items-center gap-4">
                    <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-gradient-to-br from-amber-500 to-amber-600 flex items-center justify-center shadow-sm group-hover:scale-105 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Users</p>
                        <p class="text-xs text-gray-500 font-medium truncate">System Accounts</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-3xl font-black text-gray-900 tabular-nums">{{ $stats['users'] }}</p>
                </div>
            </div>

            @if($isSuperAdmin)
            <!-- Airports -->
            <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm hover:shadow-md transition-all duration-200 flex items-center justify-between group">
                <div class="flex items-center gap-4">
                    <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-gradient-to-br from-rose-500 to-rose-600 flex items-center justify-center shadow-sm group-hover:scale-105 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Airports</p>
                        <p class="text-xs text-gray-500 font-medium truncate">Global Entries</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-3xl font-black text-gray-900 tabular-nums">{{ $stats['airports'] }}</p>
                </div>
            </div>

            <!-- Countries -->
            <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm hover:shadow-md transition-all duration-200 flex items-center justify-between group">
                <div class="flex items-center gap-4">
                    <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center shadow-sm group-hover:scale-105 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Countries</p>
                        <p class="text-xs text-gray-500 font-medium truncate">Supported Regions</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-3xl font-black text-gray-900 tabular-nums">{{ $stats['countries'] }}</p>
                </div>
            </div>

            <!-- Cities -->
            <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm hover:shadow-md transition-all duration-200 flex items-center justify-between group">
                <div class="flex items-center gap-4">
                    <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-sm group-hover:scale-105 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/></svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Cities</p>
                        <p class="text-xs text-gray-500 font-medium truncate">Mapped Cities</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-3xl font-black text-gray-900 tabular-nums">{{ $stats['cities'] }}</p>
                </div>
            </div>

            <!-- Trip Purposes -->
            <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm hover:shadow-md transition-all duration-200 flex items-center justify-between group">
                <div class="flex items-center gap-4">
                    <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center shadow-sm group-hover:scale-105 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Trip Purposes</p>
                        <p class="text-xs text-gray-500 font-medium truncate">Category Types</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-3xl font-black text-gray-900 tabular-nums">{{ $stats['trip_purposes'] }}</p>
                </div>
            </div>

            <!-- Audit Logs -->
            <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm hover:shadow-md transition-all duration-200 flex items-center justify-between group">
                <div class="flex items-center gap-4">
                    <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center shadow-sm group-hover:scale-105 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Audit Logs</p>
                        <p class="text-xs text-gray-500 font-medium truncate">Total Actions</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-3xl font-black text-gray-900 tabular-nums">{{ $stats['audit_logs'] }}</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Charts & Activity Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            @if($isSuperAdmin)
            <!-- Line Chart: Monthly Activity -->
            <div class="rounded-2xl border border-gray-200 bg-white shadow-sm overflow-hidden flex flex-col h-full">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-white to-[#f2feff]">
                    <h2 class="text-sm font-bold text-gray-700 uppercase tracking-wider">System Activity</h2>
                    <p class="text-xs text-gray-400 mt-0.5">Monthly audit log volume — Last 6 months</p>
                </div>
                <div class="p-6 flex-1 flex items-center justify-center">
                    <canvas id="activityLineChart" height="260" style="max-height: 260px; width: 100%;"></canvas>
                </div>
            </div>
            @endif

            <!-- Bar Chart: Entity Breakdown -->
            <div class="rounded-2xl border border-gray-200 bg-white shadow-sm overflow-hidden flex flex-col h-full">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-white to-[#f2feff]">
                    <h2 class="text-sm font-bold text-gray-700 uppercase tracking-wider">Entity Breakdown</h2>
                    <p class="text-xs text-gray-400 mt-0.5">Total records by category</p>
                </div>
                <div class="p-6 flex-1 flex items-center justify-center">
                    <canvas id="entityBarChart" height="260" style="max-height: 260px; width: 100%;"></canvas>
                </div>
            </div>

            @if(!$isSuperAdmin)
            <!-- Recent Activity Feed (For Org Admin) -->
            <div class="rounded-2xl border border-gray-200 bg-white shadow-sm overflow-hidden flex flex-col h-full max-h-[385px]">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-white to-[#f2feff] flex items-center justify-between sticky top-0 bg-white z-10">
                    <div>
                        <h2 class="text-sm font-bold text-gray-700 uppercase tracking-wider">Recent Notifications</h2>
                        <p class="text-xs text-gray-400 mt-0.5">Latest system actions</p>
                    </div>
                </div>
                <div class="divide-y divide-gray-50 overflow-y-auto flex-1 custom-scrollbar">
                    @forelse($recentActivity as $log)
                        <div class="px-6 py-4 flex items-center gap-4 hover:bg-gray-50/50 transition-colors">
                            <div class="flex-shrink-0 w-9 h-9 rounded-full flex items-center justify-center text-xs font-bold text-white
                                @if($log->action_name === 'created') bg-emerald-500
                                @elseif($log->action_name === 'updated') bg-amber-500
                                @elseif($log->action_name === 'deleted') bg-rose-500
                                @elseif($log->action_name === 'viewed') bg-blue-500
                                @else bg-gray-400
                                @endif
                            ">
                                @if($log->action_name === 'created')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                                @elseif($log->action_name === 'updated')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                @elseif($log->action_name === 'deleted')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                @else
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-800 truncate">
                                    {{ $log->user?->name ?? 'System' }}
                                    <span class="font-normal text-gray-500">{{ $log->action_name ?? 'performed action' }}</span>
                                    <span class="font-medium text-gray-700">on {{ ucwords(str_replace(['.', '-', '_'], ' ', $log->page ?? 'system')) }}</span>
                                </p>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $log->created_at?->diffForHumans() }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-8 text-center flex-1 flex flex-col items-center justify-center">
                            <p class="text-sm text-gray-400 italic">No recent notifications.</p>
                        </div>
                    @endforelse
                </div>
            </div>
            @endif
        </div>

        @if($isSuperAdmin)
        <!-- Recent Activity Feed (Full width for Super Admin) -->
        <div class="rounded-2xl border border-gray-200 bg-white shadow-sm overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-white to-[#f2feff] flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-bold text-gray-700 uppercase tracking-wider">Recent Activity</h2>
                    <p class="text-xs text-gray-400 mt-0.5">Latest system actions</p>
                </div>
                <a href="{{ route('admin.audit-logs') }}" class="text-xs font-semibold text-[#2ab4c0] hover:text-[#239ea9] transition">View All →</a>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($recentActivity as $log)
                    <div class="px-6 py-4 flex items-center gap-4 hover:bg-gray-50/50 transition-colors">
                        <div class="flex-shrink-0 w-9 h-9 rounded-full flex items-center justify-center text-xs font-bold text-white
                            @if($log->action_name === 'created') bg-emerald-500
                            @elseif($log->action_name === 'updated') bg-amber-500
                            @elseif($log->action_name === 'deleted') bg-rose-500
                            @elseif($log->action_name === 'viewed') bg-blue-500
                            @else bg-gray-400
                            @endif
                        ">
                            @if($log->action_name === 'created')
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                            @elseif($log->action_name === 'updated')
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            @elseif($log->action_name === 'deleted')
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            @else
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-800 truncate">
                                {{ $log->user?->name ?? 'System' }}
                                <span class="font-normal text-gray-500">{{ $log->action_name ?? 'performed action' }}</span>
                                <span class="font-medium text-gray-700">on {{ ucwords(str_replace(['.', '-', '_'], ' ', $log->page ?? 'system')) }}</span>
                            </p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $log->created_at?->diffForHumans() }}</p>
                        </div>
                        <a href="{{ route('admin.audit-logs.view', $log) }}" class="flex-shrink-0 text-xs font-semibold text-[#2ab4c0] hover:text-[#239ea9]">Details</a>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center flex-1 flex flex-col items-center justify-center">
                        <p class="text-sm text-gray-400 italic">No recent activity recorded.</p>
                    </div>
                @endforelse
            </div>
        </div>
        @endif
    </div>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>

    <script>
        function dashboardCharts() {
            return {
                initCharts() {
                    this.$nextTick(() => {
                        this.renderLineChart();
                        this.renderBarChart();
                    });
                },
                renderLineChart() {
                    const ctx = document.getElementById('activityLineChart');
                    if (!ctx) return;

                    const labels = @json($monthlyActivity['labels']);
                    const data = @json($monthlyActivity['data']);

                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Activity Logs',
                                data: data,
                                borderColor: '#2ab4c0',
                                backgroundColor: 'rgba(42, 180, 192, 0.08)',
                                borderWidth: 3,
                                fill: true,
                                tension: 0.4,
                                pointBackgroundColor: '#ffffff',
                                pointBorderColor: '#2ab4c0',
                                pointBorderWidth: 3,
                                pointRadius: 5,
                                pointHoverRadius: 7,
                                pointHoverBackgroundColor: '#2ab4c0',
                                pointHoverBorderColor: '#ffffff',
                                pointHoverBorderWidth: 2,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    backgroundColor: '#1a1a2e',
                                    titleFont: { size: 12, weight: 'bold' },
                                    bodyFont: { size: 11 },
                                    cornerRadius: 10,
                                    padding: 12,
                                    displayColors: false,
                                }
                            },
                            scales: {
                                x: {
                                    grid: { display: false },
                                    ticks: { font: { size: 11, weight: '600' }, color: '#9ca3af' }
                                },
                                y: {
                                    beginAtZero: true,
                                    grid: { color: 'rgba(0,0,0,0.04)', drawBorder: false },
                                    ticks: {
                                        font: { size: 11 },
                                        color: '#9ca3af',
                                        stepSize: 1,
                                        callback: function(val) { return Number.isInteger(val) ? val : ''; }
                                    }
                                }
                            }
                        }
                    });
                },
                renderBarChart() {
                    const ctx = document.getElementById('entityBarChart');
                    if (!ctx) return;

                    const labels = @json($entityBreakdown['labels']);
                    const data = @json($entityBreakdown['data']);

                    const gradientColors = [
                        ['#2ab4c0', '#1e9ba6'],
                        ['#8b5cf6', '#7c3aed'],
                        ['#f59e0b', '#d97706'],
                        ['#ef4444', '#dc2626'],
                        ['#10b981', '#059669'],
                        ['#3b82f6', '#2563eb'],
                    ];

                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Total Records',
                                data: data,
                                backgroundColor: gradientColors.map(c => c[0]),
                                borderColor: gradientColors.map(c => c[1]),
                                borderWidth: 2,
                                borderRadius: 8,
                                borderSkipped: false,
                                barPercentage: 0.6,
                                categoryPercentage: 0.7,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    backgroundColor: '#1a1a2e',
                                    titleFont: { size: 12, weight: 'bold' },
                                    bodyFont: { size: 11 },
                                    cornerRadius: 10,
                                    padding: 12,
                                    displayColors: true,
                                }
                            },
                            scales: {
                                x: {
                                    grid: { display: false },
                                    ticks: { font: { size: 11, weight: '600' }, color: '#9ca3af' }
                                },
                                y: {
                                    beginAtZero: true,
                                    grid: { color: 'rgba(0,0,0,0.04)', drawBorder: false },
                                    ticks: {
                                        font: { size: 11 },
                                        color: '#9ca3af',
                                        stepSize: 1,
                                        callback: function(val) { return Number.isInteger(val) ? val : ''; }
                                    }
                                }
                            }
                        }
                    });
                }
            };
        }
    </script>
</div>
