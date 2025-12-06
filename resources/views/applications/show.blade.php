@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-semibold text-gray-800">Detail Aplikasi</h2>
                <p class="text-sm text-gray-500">Detail data pemohon dan hasil penilaian</p>
            </div>
            <div>
                <a href="{{ route('applications.index') }}" class="inline-block bg-gray-200 text-gray-800 px-3 py-2 rounded">Kembali ke List</a>
            </div>
        </div>

        <div class="bg-white shadow rounded p-6 mb-6">
            <h3 class="font-semibold text-lg mb-3">Data Pemohon</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <div class="text-sm text-gray-500">Nomor</div>
                    <div class="font-medium">{{ $application->application_number }}</div>
                </div>
                <div>
                    <div class="text-sm text-gray-500">Nama</div>
                    <div class="font-medium">{{ $application->application_name }}</div>
                </div>
                <div>
                    <div class="text-sm text-gray-500">Tempat Lahir</div>
                    <div>{{ $application->application_birth_place }}</div>
                </div>
                <div>
                    <div class="text-sm text-gray-500">Tanggal Lahir</div>
                    <div>{{ optional($application->application_birth_date)->format('d M Y') }}</div>
                </div>
                <div class="md:col-span-2">
                    <div class="text-sm text-gray-500">Alamat</div>
                    <div>{{ $application->application_address }}</div>
                </div>
            </div>
            <div class="mt-4">
                <div class="text-sm text-gray-500">Skor Akhir</div>
                <div class="text-2xl font-semibold text-gray-800">{{ $application->application_summary_score ?? 0 }}</div>
            </div>
            <div class="mt-4 p-4 rounded border {{ $risk_level['bg'] }} {{ $risk_level['border'] }}">
                <div class="text-sm {{ $risk_level['text'] }}">Status Risiko</div>
                <div class="text-lg font-semibold {{ $risk_level['text'] }}">{{ $risk_level['level'] }}</div>
            </div>
        </div>

        {{-- Per-Group breakdown --}}
        @foreach($groups as $group)
            <div class="bg-white shadow rounded p-4 mb-4">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <h4 class="font-semibold text-lg">{{ $group->group_name }}</h4>
                        <div class="text-sm text-gray-500">Bobot Group: {{ ($group->group_rate * 100) ?? 0 }}%</div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-gray-500">Group Score</div>
                        <div class="font-semibold">{{ $groupScoresMap->has($group->group_id) ? $groupScoresMap->get($group->group_id)->group_score : '0' }}</div>
                    </div>
                </div>

                <div class="border-t pt-3">
                    @foreach($group->groupItems as $groupItem)
                        @php $itemScore = $itemScoresMap->get($groupItem->group_item_id); @endphp
                        <div class="mb-3">
                            <div class="flex justify-between">
                                <div>
                                    <div class="text-sm text-gray-600">{{ $groupItem->group_item_name }}</div>
                                    <div class="text-xs text-gray-500">Bobot Pertanyaan: {{ $groupItem->group_item_rate ?? 0 }}</div>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm text-gray-500">Jawaban</div>
                                    <div class="font-medium">
                                        @if($itemScore && $itemScore->item)
                                            {{ $itemScore->item->item_name }}
                                        @else
                                            -
                                        @endif
                                    </div>
                                    <div class="text-sm text-gray-500">Nilai: {{ $itemScore ? $itemScore->group_item_score : '0' }}</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

    </div>
@endsection
