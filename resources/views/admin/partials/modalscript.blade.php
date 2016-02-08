<script>
    $('#confirm-delete-modal').on('show.bs.modal', function(e) {
        $(this).find('.delete-form').attr('action', $(e.relatedTarget).data('action'));
        $(this).find('.object-name').html($(e.relatedTarget).data('objectname'));
    });
</script>