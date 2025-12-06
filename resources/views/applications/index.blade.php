@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-semibold text-gray-800">Daftar Aplikasi</h2>
                <p class="text-sm text-gray-500">Ringkasan semua pengajuan aplikasi.</p>
            </div>
            <div>
                <a href="{{ route('applications.create') }}" class="inline-block bg-blue-600 text-white px-4 py-2 rounded-md">Buat Aplikasi Baru</a>
            </div>
        </div>

        <div class="mb-6">
            <form method="GET" action="{{ route('applications.index') }}" class="flex items-center gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari berdasarkan nama aplikasi..." class="border rounded px-3 py-2 w-80" />
                <button type="submit" class="bg-gray-800 text-white px-3 py-2 rounded">Cari</button>
                @if(request('search'))
                    <a href="{{ route('applications.index') }}" class="ml-2 text-sm text-gray-600">Bersihkan</a>
                @endif
            </form>
        </div>

        <div class="grid grid-cols-1 gap-4">
            @forelse($applications as $app)
                <div class="bg-white p-4 rounded shadow-sm flex items-center justify-between">
                    <div>
                        <div class="text-sm text-gray-500">#{{ $app->application_number }} • {{ optional($app->created_at)->format('d M Y') }}</div>
                        <div class="text-lg font-medium text-gray-800">{{ $app->application_name }}</div>
                        <div class="text-sm text-gray-600">Tgl Lahir: {{ optional($app->application_birth_date)->format('d M Y') }}</div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-gray-500">Skor</div>
                        <div class="text-xl font-semibold text-gray-800">{{ $app->application_summary_score ?? '0' }}</div>
                        <div class="text-sm text-gray-500">Groups: {{ $app->group_scores_count ?? $app->groupScores->count() }}</div>
                        <div class="mt-3">
                            <a href="{{ route('applications.show', $app->application_id) }}" class="inline-block mt-2 bg-gray-100 hover:bg-gray-200 text-gray-800 px-3 py-1 rounded">Detail</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white p-6 rounded shadow-sm text-center text-gray-700">Belum ada aplikasi.</div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $applications->appends(request()->only('search'))->links() }}
        </div>
    </div>
@endsection
