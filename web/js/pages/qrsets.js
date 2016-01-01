$(function() {
    $(document).ready(function() {
        $('#new-qr-set').click(function(e) {
            $('#new-qr-set').addClass('disabled');
            $('#new-qr-set-form').show();
            e.preventDefault();
            return false;
        });

        var $select2 = $("#qrset_tagsFromString").select2({
            tags: true,
            selectOnBlur: true,
            data: existingTags,
            placeholder: "Tags (e.g. 2016, Seminar 2)",
            "language": {
                "noResults": function(){
                    return "";
                }
            },
            initSelection: function (element, callback) {
                var data = [{id: existingTags[existingTags.length-1], text: existingTags[existingTags.length-1]}];
                //$(element.val().split(/;/)).each(function () {
                    //data.push({id: this, text: this });
                //});
                callback(data);
            },
            tokenSeparators: [',', ' ']
        });

        $('#new-qr-set-form.fhide').hide();
    });
});