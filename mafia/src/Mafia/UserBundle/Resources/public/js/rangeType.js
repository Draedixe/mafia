$(function() {
    $('.range').change(function(){
        $(this).parent().find(".valeur").text($(this).find("input").val() + " minutes");
    });
});