<div class="guest-search-results" id="guestSearchStatus" role="status">
    @if($search !== '')
        <strong>{{ $tamus->total() }}</strong> hasil untuk pencarian <strong>{{ $search }}</strong>
    @else
        Menampilkan seluruh data tamu
    @endif
</div>

<div class="table-wrapper">
    @if($tamus->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Tamu</th>
                    <th>Instansi</th>
                    <th>Tujuan</th>
                    <th>Keperluan</th>
                    <th>Waktu</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tamus as $index => $tamu)
                    <tr>
                        <td class="number">{{ $tamus->firstItem() + $index }}</td>
                        <td>
                            <div class="visitor-name">{{ $tamu->nama }}</div>
                            <div class="institution">{{ $tamu->no_handphone }}</div>
                        </td>
                        <td>{{ $tamu->instansi }}</td>
                        <td><span class="badge">{{ $tamu->tujuan }}</span></td>
                        <td class="purpose">{{ Str::limit($tamu->keperluan, 45) }}</td>
                        <td>
                            <strong>{{ \Carbon\Carbon::parse($tamu->hari_tanggal)->format('d M Y') }}</strong>
                            <br>
                            <small>{{ $tamu->jam }}</small>
                        </td>
                        <td>
                            <div class="action">
                                <button
                                    type="button"
                                    class="btn-view"
                                    title="Detail"
                                    data-modal="view"
                                    data-id="{{ $tamu->id }}"
                                    data-nama="{{ $tamu->nama }}"
                                    data-instansi="{{ $tamu->instansi }}"
                                    data-tujuan="{{ $tamu->tujuan }}"
                                    data-hari="{{ \Carbon\Carbon::parse($tamu->hari_tanggal)->locale('id')->translatedFormat('l, d F Y') }}"
                                    data-jam="{{ $tamu->jam }} WIT"
                                    data-handphone="{{ $tamu->no_handphone }}"
                                    data-alamat="{{ $tamu->alamat }}"
                                    data-keperluan="{{ $tamu->keperluan }}"
                                    data-paraf="{{ $tamu->paraf ? 'Tersedia' : 'Tidak tersedia' }}"
                                    data-paraf-url="{{ $tamu->paraf && \Illuminate\Support\Str::startsWith($tamu->paraf, 'data:image/') ? route('admin.tamu.paraf', $tamu) : '' }}"
                                    data-gambar-url="{{ $tamu->documentationPath() ? route('admin.tamu.gambar', $tamu) : '' }}"
                                >👁</button>
                                <button
                                    type="button"
                                    class="btn-edit"
                                    title="Edit"
                                    data-modal="edit"
                                    data-id="{{ $tamu->id }}"
                                    data-nama="{{ $tamu->nama }}"
                                    data-instansi="{{ $tamu->instansi }}"
                                    data-tujuan="{{ $tamu->tujuan }}"
                                    data-hari="{{ $tamu->hari_tanggal }}"
                                    data-jam="{{ substr($tamu->jam, 0, 5) }}"
                                    data-handphone="{{ $tamu->no_handphone }}"
                                    data-alamat="{{ $tamu->alamat }}"
                                    data-keperluan="{{ $tamu->keperluan }}"
                                    data-paraf-url="{{ $tamu->paraf && \Illuminate\Support\Str::startsWith($tamu->paraf, 'data:image/') ? route('admin.tamu.paraf', $tamu) : '' }}"
                                    data-gambar-url="{{ $tamu->documentationPath() ? route('admin.tamu.gambar', $tamu) : '' }}"
                                >✎</button>
                                <button
                                    type="button"
                                    class="btn-delete"
                                    title="Hapus"
                                    data-modal="delete"
                                    data-id="{{ $tamu->id }}"
                                    data-nama="{{ $tamu->nama }}"
                                >🗑</button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="empty">
            <div class="empty-icon">{{ $search !== '' ? '⌕' : '♙' }}</div>
            <strong>{{ $search !== '' ? 'Tidak ada hasil pencarian' : 'Belum ada data tamu' }}</strong>
            <p>
                @if($search !== '')
                    Coba kata kunci lain atau hapus pencarian untuk melihat semua data.
                @else
                    Data kunjungan akan muncul di sini.
                @endif
            </p>
        </div>
    @endif
</div>

@if($tamus->hasPages())
    <div class="pagination">
        {{ $tamus->links('pagination::simple-tailwind') }}
    </div>
@endif
