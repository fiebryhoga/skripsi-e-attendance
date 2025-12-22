@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3>Edit Kategori Pelanggaran</h3>
        </div>
        <div class="card-body">
            {{-- Asumsi variabel dari controller bernama $violationCategory --}}
            <form action="{{ route('admin.violation-categories.update', $violationCategory->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="grup" class="form-label">Grup Pelanggaran</label>
                    <select name="grup" id="grup" class="form-control @error('grup') is-invalid @enderror">
                        <option value="A" {{ $violationCategory->grup == 'A' ? 'selected' : '' }}>Grup A</option>
                        <option value="B" {{ $violationCategory->grup == 'B' ? 'selected' : '' }}>Grup B</option>
                        <option value="C" {{ $violationCategory->grup == 'C' ? 'selected' : '' }}>Grup C</option>
                        <option value="D" {{ $violationCategory->grup == 'D' ? 'selected' : '' }}>Grup D</option>
                    </select>
                    @error('grup')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="kode" class="form-label">Kode Pelanggaran</label>
                    <input type="text" name="kode" class="form-control @error('kode') is-invalid @enderror" value="{{ old('kode', $violationCategory->kode) }}">
                    @error('kode')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="deskripsi" class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="3">{{ old('deskripsi', $violationCategory->deskripsi) }}</textarea>
                    @error('deskripsi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                 {{-- Jika ada kolom poin --}}
                <div class="mb-3">
                    <label for="poin" class="form-label">Poin</label>
                    <input type="number" name="poin" class="form-control" value="{{ old('poin', $violationCategory->poin ?? '') }}">
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('admin.violation-categories.index') }}" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</div>
@endsection