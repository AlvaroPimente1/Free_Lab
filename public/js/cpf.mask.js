$(document).ready(function(){
    $("#cpf").mask("000.000.000-00", {reverse: true});
    $("#phone").mask("(00)00000-0000");
    $("#cep").mask("00000-000");
    $("#height").mask("0.00");
    $("#weight").mask("000", {reverse:true});

    $("#patientForm").submit(function(){
        $("#phone").unmask();
        $("#cep").unmask();
        $("#height").unmask();
        let weight = $("#weight").val(); // Peso está como inteiro no db, valor é truncado - Alterar caso o peso vire float
        weight = Math.round(weight);
        $("#weight").val(weight);
    })
})
