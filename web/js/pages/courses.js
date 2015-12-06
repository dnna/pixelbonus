$(function() {
    $(document).ready(function() {
        $('#new-course').click(function(e) {
            $('#new-course').addClass('disabled');
            $('#new-course-form').show();
            e.preventDefault();
            return false;
        });
    });
});