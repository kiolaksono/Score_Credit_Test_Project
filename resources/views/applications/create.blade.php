@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto">

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                <strong class="font-bold">Gagal Menyimpan!</strong>
                <span class="block sm:inline">Mohon periksa input berikut:</span>
                <ul class="list-disc list-inside mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('applications.store') }}" method="POST" class="space-y-6">
            @csrf
            <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                <h2 class="text-xl font-semibold mb-4 text-blue-600 border-b pb-2">Data Pemohon</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="application_name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                        <input type="text" name="application_name" id="application_name" 
                            class="shadow border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline" required placeholder="Masukkan nama pemohon">
                    </div>

                    <div>
                        <label for="application_gender" class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                        <select name="application_gender" id="application_gender" 
                            class="shadow border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline" required>
                            <option value="" disabled selected>-- Pilih Jenis Kelamin --</option>
                            <option value="1">Laki-Laki</option>
                            <option value="2">Perempuan</option>
                        </select>
                    </div>

                    <div>
                        <label for="application_birth_place" class="block text-sm font-medium text-gray-700">Tempat Lahir</label>
                        <input type="text" name="application_birth_place" id="application_birth_place" 
                            class="shadow border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline">
                    </div>

                    <div>
                        <label for="application_birth_date" class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                        <input type="date" name="application_birth_date" id="application_birth_date" 
                            class="shadow border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline" required>
                    </div>

                    <div>
                        <label for="application_postal_code" class="block text-sm font-medium text-gray-700">Kode Pos</label>
                        <input type="text" name="application_postal_code" id="application_postal_code" 
                            class="shadow border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline">
                    </div>

                    <div>
                        <label for="application_address" class="block text-sm font-medium text-gray-700">Alamat</label>
                        <textarea name="application_address" id="application_address" rows="3"
                        class="shadow border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline"></textarea>
                    </div>
                </div>
            </div>                

            <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                <h2 class="text-xl font-semibold mb-6 text-blue-600 border-b pb-2">Kriteria Penilaian</h2>

                @foreach($groups as $group)
                    <div class="mb-8 p-4 border rounded-lg bg-gray-50">
                        <h3 class="font-bold text-lg text-gray-800 mb-4">{{ $group->group_name }}</h3>
                        
                        <div class="grid grid-cols-1 gap-6">
                            @foreach($group->groupItems as $item)
                                <div>
                                    <label class="block text-gray-700 text-sm font-medium mb-2">
                                        {{ $item->group_item_name }}
                                    </label>
                                    
                                    <select name="items[{{ $item->group_item_id }}]" required
                                        class="shadow border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline bg-white">
                                        <option value="" disabled selected>-- Pilih Opsi --</option>
                                        @foreach($item->items as $option)
                                            <option value="{{ $option->item_id }}">
                                                {{ $option->item_name }} 
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="flex items-center justify-end">
                <button class="bg-blue-600 hover:bg-blue-800 text-white font-bold py-3 px-6 rounded focus:outline-none focus:shadow-outline transition duration-300" 
                        type="submit">
                    Simpan & Hitung Skor
                </button>
            </div>

        </form>
    </div>
@endsection