@extends('layouts.app')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Data Motor</h1>
    <div>
        <a href="/motor/create" class="d-none d-sm-inline-block btn btn-plus btn-primary shadow-sm"><i
        class="fas fa-plus fa-sm text-white-50"></i> Tambah Data</a>
    </div>
    </div>

    {{-- Cards grid --}}
    <div class="row">
        <div class="col">
            <div class="card shadow">
                <div class="card-body">
                    @if ($motors->isEmpty())
                        <div class="text-center py-5">
                            <p class="mb-0">Data tidak tersedia</p>
                        </div>
                    @else
                        <div class="row">
                            @foreach ($motors as $item)
                                <div class=" col-sm-6 col-md-4 col-lg-3 mb-4">
                                    <div class="card h-100">
                                        {{-- Foto motor --}}
                                        @php
                                            // sesuaikan path foto jika disimpan di storage/public atau folder lain
                                            $foto = $item->foto_motor
                                                ? asset('storage/' . ltrim($item->foto_motor, '/'))
                                                : asset('images/no-image.png');
                                        @endphp

                                        <img src="{{ $foto }}" class="card-img-top" alt="{{ $item->merk }} {{ $item->tipe_cc }}" style="object-fit:cover;height:180px;">

                                        <div class="card-body d-flex flex-column">
                                            {{-- Nama motor --}}
                                            <h5 class="card-title mb-1 text-truncate">{{ $item->merk }} {{ $item->tipe_cc }}</h5>

                                            {{-- Harga sewa (tarif harian) --}}
                                            <p class="card-text text-muted mb-3">
                                                Harga sewa per hari:
                                                <strong>
                                                    Rp {{ number_format($item->tarif_harian ?? 0, 0, ',', '.') }}
                                                </strong>
                                            </p>

                                            {{-- Tombol Details --}}
                                            <div class="mt-auto">
                                                <button type="button"
                                                    class="btn btn-primary btn-block details-btn"
                                                    data-id="{{ $item->id }}"
                                                    data-merk="{{ e($item->merk) }}"
                                                    data-tipe="{{ e($item->tipe_cc) }}"
                                                    data-tarif="{{ number_format($item->tarif_harian ?? 0, 0, ',', '.') }}"
                                                    data-foto="{{ $foto }}"
                                                    data-nopol="{{ e($item->no_plat ?? '') }}"
                                                >
                                                    Details
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Pagination (jika pake paginator) --}}
                        @if(method_exists($motors, 'links'))
                            <div class="d-flex justify-content-center mt-3">
                                {{ $motors->links() }}
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Modal for details (card inside modal) --}}
    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="detailModalLabel" class="modal-title">Detail Motor</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card mb-0">
                        <div class="row no-gutters">
                            <div class="col-md-5">
                                <img id="detailImage" src="" class="card-img" alt="Motor" style="object-fit:cover;height:100%;">
                            </div>
                            <div class="col-md-7">
                                <div class="card-body">
                                    <h4 id="detailTitle" class="card-title mb-2"></h4>
                                    <p class="mb-1"><strong>Harga sewa / hari:</strong> <span id="detailTarif"></span></p>
                                    <p class="mb-1"><strong>No. Polisi:</strong> <span id="detailNopol"></span></p>
                                    <hr>
                                    <p id="detailDeskripsi" class="mb-0 text-muted"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Script to populate and show modal (works with Bootstrap 4/5 and jQuery fallback) --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const buttons = document.querySelectorAll('.details-btn');
            const modalEl = document.getElementById('detailModal');
            const titleEl = document.getElementById('detailTitle');
            const imgEl = document.getElementById('detailImage');
            const tarifEl = document.getElementById('detailTarif');
            const tahunEl = document.getElementById('detailTahun');
            const warnaEl = document.getElementById('detailWarna');
            const nopolEl = document.getElementById('detailNopol');
            const deskEl = document.getElementById('detailDeskripsi');

            function showModal() {
                if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                    new bootstrap.Modal(modalEl).show();
                } else if (typeof $ !== 'undefined' && $.fn.modal) {
                    $('#detailModal').modal('show');
                } else {
                    // fallback: toggle classes (not as pretty)
                    modalEl.classList.add('show');
                    modalEl.style.display = 'block';
                    modalEl.removeAttribute('aria-hidden');
                }
            }

            buttons.forEach(btn => {
                btn.addEventListener('click', function () {
                    const merk = this.getAttribute('data-merk') || '';
                    const tipe = this.getAttribute('data-tipe') || '';
                    const foto = this.getAttribute('data-foto') || '';
                    const tarif = this.getAttribute('data-tarif') || '';
                    const tahun = this.getAttribute('data-tahun') || '-';
                    const warna = this.getAttribute('data-warna') || '-';
                    const nopol = this.getAttribute('data-nopol') || '-';
                    const deskripsi = this.getAttribute('data-deskripsi') || '';

                    titleEl.textContent = merk + (tipe ? (' ' + tipe) : '');
                    imgEl.src = foto;
                    imgEl.alt = titleEl.textContent;
                    tarifEl.textContent = 'Rp ' + tarif;
                    tahunEl.textContent = tahun;
                    warnaEl.textContent = warna;
                    nopolEl.textContent = nopol;
                    deskEl.textContent = deskripsi;

                    showModal();
                });
            });

            // close fallback modal when click outside (fallback only)
            if (! (typeof bootstrap !== 'undefined' || (typeof $ !== 'undefined' && $.fn.modal))) {
                modalEl.addEventListener('click', function (e) {
                    if (e.target === modalEl) {
                        modalEl.classList.remove('show');
                        modalEl.style.display = 'none';
                        modalEl.setAttribute('aria-hidden', 'true');
                    }
                });
            }
        });
    </script>
@endsection