{{--
    One shared confirmation dialog for the whole app, driven by
    resources/js/confirm.js. Render it once per layout rather than per page.

    The ids are what the JS targets, so keep them in sync:
      {id}-title    heading text  (rendered by <x-modal>)
      {id}-message  body text
      {id}-confirm  the accept button
      {id}-cancel   the dismiss button
--}}
@props(['id' => 'confirm-modal'])

<x-modal
    :id="$id"
    variant="confirm"
    status="danger"
    icon="alert-triangle"
    title="Are you sure?"
    size="sm"
>
    <span id="{{ $id }}-message"></span>

    <x-slot:footer>
        <div class="col">
            <x-button :id="$id.'-cancel'" block data-bs-dismiss="modal">Cancel</x-button>
        </div>
        <div class="col">
            <x-button :id="$id.'-confirm'" color="danger" block>Confirm</x-button>
        </div>
    </x-slot:footer>
</x-modal>
