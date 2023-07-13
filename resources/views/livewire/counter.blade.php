<div>
    <h1>Hello Livewire!</h1>
    <!-- <div class="col-lg-6 col-md-6 col-sm-12 form-group" wire:ignore>
        <label>Lokasi Absen</label>
        <select name="location_id" id="location_id" class="form-control  @error('location_id') parsley-error @enderror" wire:model="locationId">
            <option value="">-- Pilih Lokasi --</option>
            @foreach($lokasis as $item)
            <option value="{{ $item->id }}">{{ $item->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12 form-group">
        <label>Pegawai</label>
        <select name="pegawai_id" id="pegawai_id" class=" form-control  @error('pegawai_id') parsley-error @enderror" data-parsley-required="true" data-live-search="true" data-style="btn-warning" wire:model="pegawaiId">
            @foreach($pegawais as $item)
            <option value="{{ $item->id }}">{{ $item->name }}</option>
            @endforeach
        </select>
    </div> -->

    <button wire:click="like">Like Post</button>
</div>

@push('scripts')
<script>
    // $('#location_id').on('select2:select', function(data) {
    //     console.log(data.params.data);
    //     // livewire.emit('changedLocation', data.params.data.id)
    //     // @this.locationId = data.params.data.id;
    //     @this.set('locationId', data.params.data.id);
    // });

    // // $('#location_id').on('change', function(e) {
    // //     console.log(e);
    // //     Livewire.emit('ubahLokasi');
    // //     // Livewire.on('ubahLoklasi', () => {
    // //     //     $('#pegawai_id').select2();
    // //     // });
    // // });
    // Livewire.on('reinit', (id) => {
    //     console.log('kise console log ' + id);
    //     $('#pegawai_id').select2({
    //         placeholder: 'Pilih Lokasi Dahulu!'
    //     });
    // });
</script>

@endpush