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
    let sessionCount = 0;
    let fields = {};

    function btnRemoveDisabled() {
        $('#removeSession').prop('disabled', sessionCount === 0);
    }

    $('#addSession').click(function(){
        sessionCount++;
        let sessionID = 'session' + sessionCount;
        fields[sessionID] = [];

        $('#sessionsGroup').append(`
            <div class="session mb-4" id="${sessionID}">
                <h5>Sessão <input type="text" class="form-control d-inline-block w-auto" placeholder="Nome da Sessão" name="${sessionID}_name" required></h5>
                <div class="row g-3 mb-2 mt-3">
                    <div class="col-sm-5"><h6>Nome do Exame</h6></div>
                    <div class="col-sm"><h6>Valor de Referência</h6></div>
                </div>
                <div class="examsGroup mt-2"></div>
                <button type="button" class="btn btn-primary addExam" data-session="${sessionID}">Adicionar Exame</button>
                <button type="button" class="btn btn-danger removeSession" data-session="${sessionID}">Remover Sessão</button>
                <hr>
            </div>
        `);

        btnRemoveDisabled();
    });

    $(document).on('click', '.addExam', function(){
        let sessionID = $(this).data('session');
        let examID = fields[sessionID].length + 1;

        fields[sessionID].push(examID);

        $('#' + sessionID + ' .examsGroup').append(`
            <div class="row g-3 mb-2 exam" id="${sessionID}_exam${examID}">
                <div class="col-sm-5">
                    <input type="text" class="form-control" placeholder="Ex: Plaquetas" name="${sessionID}_exam${examID}_name" required>
                </div>
                <div class="col-sm">
                    <input type="text" class="form-control" placeholder="Ex: 140 000 a 450 000/µL" name="${sessionID}_exam${examID}_ref" required>
                </div>
                <div class="col-sm-2">
                    <button type="button" class="btn btn-danger removeExam" data-session="${sessionID}" data-exam="${examID}">Remover Exame</button>
                </div>
            </div>
        `);
    });

    $(document).on('click', '.removeExam', function(){
        let sessionID = $(this).data('session');
        let examID = $(this).data('exam');
        
        $('#' + sessionID + '_exam' + examID).remove();
        
        // Remove o exame da estrutura fields
        fields[sessionID] = fields[sessionID].filter(id => id !== examID);
    });

    $(document).on('click', '.removeSession', function(){
        let sessionID = $(this).data('session');
        $('#' + sessionID).remove();
        delete fields[sessionID];
        sessionCount--;
        btnRemoveDisabled();
    });

    $('#procedureForm').submit(function(){
        let jsonStructure = {
            sessions: []
        };

        $('.session').each(function(){
            let sessionName = $(this).find('input[name$="_name"]').val();
            let exams = [];

            $(this).find('.exam').each(function(){
                let examName = $(this).find('input[name$="_name"]').val();
                let referenceValue = $(this).find('input[name$="_ref"]').val();

                exams.push({
                    examName: examName,
                    value: null,
                    referenceValue: referenceValue
                });
            });

            jsonStructure.sessions.push({
                sessionName: sessionName,
                exams: exams
            });
        });

        $('textarea#body').val(JSON.stringify(jsonStructure));
    });

    $('#procedureFormEdit').submit(function(){
        let jsonStructure = {
            sessions: []
        };

        $('.session').each(function(){
            let sessionName = $(this).find('input[name$="_name"]').val();
            let exams = [];

            $(this).find('.exam').each(function(){
                let examName = $(this).find('input[name$="_name"]').val();
                let referenceValue = $(this).find('input[name$="_ref"]').val();

                exams.push({
                    examName: examName,
                    value: null,
                    referenceValue: referenceValue
                });
            });

            jsonStructure.sessions.push({
                sessionName: sessionName,
                exams: exams
            });
        });

        console.log(JSON.stringify(jsonStructure));

        $('textarea#body').val(JSON.stringify(jsonStructure));
    });
});
