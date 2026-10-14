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
                                ><svg aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.5 12s3.4-6 9.5-6 9.5 6 9.5 6-3.4 6-9.5 6-9.5-6-9.5-6Z"/><circle cx="12" cy="12" r="2.5" stroke-width="1.8"/></svg></button>
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
                                ><svg aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m16 4 4 4M4 20l4-.8L19 8a2.8 2.8 0 0 0-4-4L4 15l-.8 5Z"/></svg></button>
                                <button
                                    type="button"
                                    class="btn-delete"
                                    title="Hapus"
                                    data-modal="delete"
                                    data-id="{{ $tamu->id }}"
                                    data-nama="{{ $tamu->nama }}"
                                ><svg aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 7h16M10 11v6m4-6v6M5 7l1 14h12l1-14M9 7V4h6v3"/></svg></button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="empty">
            <div class="empty-icon" aria-hidden="true">
                @if($search !== '')
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="10.5" cy="10.5" r="6.5" stroke-width="1.8"/><path stroke-linecap="round" stroke-width="1.8" d="m16 16 5 5"/></svg>
                @else
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4" stroke-width="1.8"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 21a8 8 0 0 1 16 0"/></svg>
                @endif
            </div>
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
