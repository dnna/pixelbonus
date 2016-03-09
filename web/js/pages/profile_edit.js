$(function() {
    $(document).ready(function() {
        var updateCurvedGradingParametersVisibility = function() {
            if($('#fos_user_profile_form_preferredGradingModel').val() == 'curved_grading') {
                $('#fos_user_profile_form_gradeMultiplier').parent().parent().show();
                $('#fos_user_profile_form_maxGrade').parent().parent().show();
                $('#fos_user_profile_form_minGrade').parent().parent().show();
            } else {
                $('#fos_user_profile_form_gradeMultiplier').parent().parent().hide();
                $('#fos_user_profile_form_maxGrade').parent().parent().hide();
                $('#fos_user_profile_form_minGrade').parent().parent().hide();
            }
        }
        updateCurvedGradingParametersVisibility();
        $('#fos_user_profile_form_preferredGradingModel').change(updateCurvedGradingParametersVisibility);
    });
});