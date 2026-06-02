@extends('layouts.app')

@section('content')

<div class="max-w-2xl mx-auto p-6">

<form action="/debts" method="POST"
      class="bg-white shadow rounded-2xl p-6 space-y-4">

    @csrf

    <input type="text"
           name="title"
           placeholder="Nama Utang"
           class="w-full border p-3 rounded-xl">

    {{-- Input yang ditampilkan --}}
    <input type="text"
           id="rupiah"
           placeholder="Total Utang"
           class="w-full border p-3 rounded-xl">

    {{-- Input yang dikirim ke database --}}
    <input type="hidden"
           name="total_amount"
           id="total_amount">

    <input type="number"
           name="total_month"
           placeholder="Berapa Bulan"
           class="w-full border p-3 rounded-xl">

    <input type="date"
           name="start_date"
           class="w-full border p-3 rounded-xl">

    <textarea name="description"
              placeholder="Deskripsi"
              class="w-full border p-3 rounded-xl"></textarea>

    <button class="bg-indigo-600 text-white px-5 py-3 rounded-xl">
        Simpan
    </button>

</form>

</div>

<script>
const rupiahInput = document.getElementById('rupiah');
const hiddenInput = document.getElementById('total_amount');

rupiahInput.addEventListener('input', function(e) {

    // Ambil angka saja
    let value = this.value.replace(/\D/g, '');

    // Simpan angka asli ke hidden input
    hiddenInput.value = value;

    // Format Rupiah
    this.value = value
        ? 'Rp ' + Number(value).toLocaleString('id-ID')
        : '';
});
</script>

@endsection