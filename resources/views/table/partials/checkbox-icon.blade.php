<td class="{{ $tdStyle }}">
    <x-simple-tables::checkbox
        class="bulk-checkbox"
        x-on:click="selectId('{{ $rowId }}')"
        value="{{ $rowId }}"
    />
</td>
