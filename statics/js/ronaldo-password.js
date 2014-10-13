$(document).ready(function(){
    $("#i1").keyup(function(){
        if($("#i1").val() != "")
        {
            $("#d1").addClass("has-success");
            $("#s1").removeClass("glyphicon-warning-sign");
            $("#s1").addClass("glyphicon-ok");

            if($("#i2").val() != "")
            {
                if($("#i1").val() == $("#i2").val())
                {
                    $("#d2").removeClass("has-error");
                    $("#d2").addClass("has-success");
                    $("#s2").removeClass("glyphicon-warning-sign");
                    $("#s2").addClass("glyphicon-ok");
                    $("#btn").attr("disabled", false);
                }
                else
                {
                    $("#d2").removeClass("has-success");
                    $("#d2").addClass("has-error");
                    $("#s2").removeClass("glyphicon-ok");
                    $("#s2").addClass("glyphicon-warning-sign");
                    $("#btn").attr("disabled", true);
                }
            }
        }
        else
        {
            $("#d1").removeClass("has-success");
            $("#d2").addClass("has-error");
            $("#s1").removeClass("glyphicon-ok");
            $("#s1").addClass("glyphicon-warning-sign");
            $("#btn").attr("disabled", true);
        }
    })

    $("#i2").keyup(function(){
        if($("#i2").val() != "")
        {
            if($("#i1").val() == $("#i2").val())
            {
                $("#d2").removeClass("has-error");
                $("#d2").addClass("has-success");
                $("#s2").removeClass("glyphicon-warning-sign");
                $("#s2").addClass("glyphicon-ok");
                $("#btn").attr("disabled", false);
            }
            else
            {
                $("#d2").removeClass("has-success");
                $("#d2").addClass("has-error");
                $("#s2").removeClass("glyphicon-ok");
                $("#s2").addClass("glyphicon-warning-sign");
                $("#btn").attr("disabled", true);
            }
        }
        else
        {
            $("#d2").removeClass("has-success");
            $("#d2").addClass("has-error");
            $("#s2").removeClass("glyphicon-ok");
            $("#s2").addClass("glyphicon-warning-sign");
            $("#btn").attr("disabled", true);
        }
    })
});
