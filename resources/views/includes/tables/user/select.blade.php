<x-ui.button
    x-if="selected.length<=0 ?'disabled:''"
    @click="$wire.deleteall(selected)
    size="xs"
    variant="error"
    class="btn btn-error btn-sm disabled:btn-error/50">
    Delete <span x-text="selected.length"></span> Record(s)

</x-ui.button>