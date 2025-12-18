<x-ui.button

  x-bind:disabled="selected.length <= 0"
    @click="$wire.deleteall(selected)"
    size="xs"
    variant="error"
    class="btn btn-error  btn-sm disabled:btn-goust">
    Delete <span x-text="selected.length"></span> Record(s)

</x-ui.button>
