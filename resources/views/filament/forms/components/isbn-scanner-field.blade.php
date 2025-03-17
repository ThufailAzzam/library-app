<!-- resources/views/filament/forms/components/isbn-scanner-field.blade.php -->
<x-dynamic-component
    :component="$getFieldWrapperView()"
    :id="$getId()"
    :label="$getLabel()"
    :helper-text="$getHelperText()"
    :hint="$getHint()"
    :required="$isRequired()"
    :state-path="$getStatePath()"
>
    <div
        wire:ignore
        x-data="{
            isbn: @entangle($getStatePath()),
            judul: @entangle('data.judul'),
            penulis: @entangle('data.penulis'), 
            kode_buku: @entangle('data.kode_buku'),
            penerbit: @entangle('data.penerbit'),
            tahun_terbit: @entangle('data.tahun_terbit'),
        }"
        x-init="
            Livewire.on('bookDataFetched', data => {
                isbn = data.isbn;
                kode_buku = data.isbn;
                judul = data.title;
                penulis = data.authors;
                penerbit = data.publisher;
                tahun_terbit = data.publish_year;
            })
        "
    >
        @livewire('isbn-scanner')
    </div>
</x-dynamic-component>