class Stack { // Para os campos, utiliza-se uma pilha

    constructor (){
        this.top = 0; // Aponta para a posição onde um novo elemento será adicionado
    }

    // Adiciona 1 na pilha
    push (){
        this.top = this.top + 1;
    }

    pop (){
        if(this.length()){
            this.top = this.top - 1; // tira 1 do contador do tamanho
        }
    }

    // Para sabermos o tamanho da pilha
    length (){
        return this.top;
    }

}

$(document).ready(function(){
    let fields = new Stack;
    if($('#fieldCount').length > 0){
        fields.top = parseInt($('#fieldCount').val());
    }

    function btnRemoveDisabled() {
        var btn = document.getElementById("removeField");
        if(fields.length() == 0){
            btn.disabled = true;
        } else {
            btn.disabled = false;
        }
    }

    $('#addField').click(function(){
        fields.push(); // Incrementa em 1
        $('#fieldsGroup').append('<div class="row g-3 mb-2" id="'+ fields.length() +'"></tr>');
        $('#'+fields.length()).append('<div class="col-sm-5" id="'+ fields.length() +'_1"></div>');
        $('#'+fields.length()+'_1').append('<input type="text" class="form-control" id="iField' + fields.length() + '"placeholder="Ex: Plaquetas" required>');
        $('#'+fields.length()).append('<div class="col-sm" id="'+ fields.length() +'_2"></div>');
        $('#'+fields.length()+'_2').append('<input type="text" placeholder="Preenchido depois" class="form-control" disabled>');
        $('#'+fields.length()).append('<div class="col-sm" id="'+ fields.length() +'_3"></div>');
        $('#'+fields.length()+'_3').append('<input type="text" id="ref' + fields.length() + '"placeholder="Ex: 140 000 a 450 000/µL" class="form-control" required>');
        btnRemoveDisabled();
    })

    $('#removeField').click(function(){
        $('div').remove('#'+fields.length());
        fields.pop();
        btnRemoveDisabled();
    })

    /* $('#addField').click(function(){
        fields.push(); // Incrementa em 1

        $('#fieldsGroup').append('<tr id="'+ fields.length() +'"></tr>');
        $('#'+fields.length()).append('<label class="mt-2">Exame '+ fields.length() +' </label>');
        $('#'+fields.length()).append('<input type="text" id="iField' + fields.length() + '"placeholder="Plaquetas" class="form-control" required>');
        $('#'+fields.length()).append('<label class="mt-2">Valor de referência do exame '+ fields.length() +' </label>');
        $('#'+fields.length()).append('<input type="text" id="ref' + fields.length() + '"placeholder="140 000 a 450 000/µL" class="form-control" required>');
    }) */

    /* $('#removeField').click(function(){
        $('tr').remove('#'+fields.length());
        $('input').remove('#iField'+fields.length());
        $('label').remove('#field'+fields.length());
        $('input').remove('#ref'+fields.length());
        fields.pop();
    }) */

    $('#addConclusion').click(function(){
        $('#addConclusion').addClass("d-none");
        $('#removeConclusion').removeClass("d-none");

        $('#conclusionDiv').removeClass("d-none");
    })

    $('#removeConclusion').click(function(){
        $('#removeConclusion').addClass("d-none");
        $('#addConclusion').removeClass("d-none");

        $('#conclusionDiv').addClass("d-none");
        $('#conclusion').val("");
    })

    // Gera o valor final que será incluído nos campos p/ a criação de novo tipo de laudo
    $('#procedureForm').submit(function(){
        if(fields.length()){
            let counterValue = fields.length();
            console.log(fields.length());
            let full;
            for (let i=1; i<=counterValue; i++){
                console.log(i);
                if (full){
                    full = full + "!@#" + $('#iField'+i).val() + '!-!' + $('#ref'+i).val();
                    console.log(full);
                }
                else{
                    full = $('#iField'+i).val() + '!-!' + $('#ref'+i).val();
                    console.log(full);
                }
            }
            $('textarea#body').val(full);
        }
    })

    // Gera o valor final que será incluído nos campos p/ a edição de laudos
    $('#procedureFormEdit').submit(function(){
        if(fields.length()){
            let counterValue = fields.length();
            console.log(fields.length());
            let full;
            for (let i=1; i<=counterValue; i++){
                console.log(i);
                if (full){
                    full = full + "!@#" + $('#iField'+i).val() + '!-!' + $('#ref'+i).val();
                    console.log(full);
                }
                else{
                    full = $('#iField'+i).val() + '!-!' + $('#ref'+i).val();
                    console.log(full);
                }
            }
            $('textarea#body').val(full);
        }
    })
})
