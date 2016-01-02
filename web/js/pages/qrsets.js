$(function() {
    $(document).ready(function() {
        $('#new-qr-set').click(function(e) {
            $('#new-qr-set').addClass('disabled');
            $('#new-qr-set-form').show();
            e.preventDefault();
            return false;
        });

        $("#qrset_tagsFromString").select2({
            tags: true,
            selectOnBlur: true,
            data: existingTags,
            placeholder: "Tags (e.g. 2016, Seminar 2)",
            "language": {
                "noResults": function(){
                    return "";
                }
            },
            tokenSeparators: [',', ' ']
        });
        $("#qrset_tagsFromString").val([
            existingTags[existingTags.length-2],
            existingTags[existingTags.length-1]
        ]).trigger('change');

        $('#new-qr-set-form.fhide').hide();
    });
});