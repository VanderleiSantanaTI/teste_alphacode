$(document).ready(function() {
    const baseUrl = 'http://localhost/Rest_API_PHP/user/';

    // Deixa o label azul ao focar
    function labelBlueOnFocus() {
        $('.form-text-input input').focus(function () {
            $(this).prev('label').css({
            'color': '#068ed0',
            'font-size': '22px' // Aumenta o tamanho da fonte
            });
        }).blur(function () {
            $(this).prev('label').css({
            'color': '',
            'font-size': '' // Restaura o tamanho da fonte original
            });
        });
    
    }

    // Aplica máscara nos campos de texto do formulário
    function applyMask() {
        $('.data').mask('00/00/0000');
        $('.telefone').mask('(00) 0000-0000');
        $('.celular').mask('(00) 00000-0000');
    }

    // Métodos personalizados para validar data e celular
    function validationMethodsConfig() {
        $.validator.methods.date = function(value, element) {
            var regex = /^(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[012])\/(19[0-9]{2}|20[0-9]{2}|210[0-5])$/;
            return this.optional(element) || regex.test(value);
        };

        $.validator.methods.cell = function(value, element) {
            var regex = /^\(\d{2}\) \d{5}-\d{4}$/;
            return this.optional(element) || regex.test(value);
        };

        $.validator.methods.tel = function(value, element) {
            var regex = /^\(\d{2}\) \d{4}-\d{4}$/;
            return this.optional(element) || regex.test(value);
        };
    }

    // Configura validação do formulário

    function validationFormConfig() {
        $(".main-form").validate({
        rules: {
            nome: {
            minlength: 2
            },
            data: {
            date: true
            },
            email: {
            email: true
            },
            profissao: {
            minlength: 3
            },
            telefone: {
            tel: true
            },
            celular: {
            cell: true
            }
        },
        messages: {
            nome: {
            required: "Por favor, digite seu nome completo!",
            minlength: "O nome precisa ter no mínimo duas letras!"
            },
            data: "Data invalida, digite nesse formato dd/mm/yyyy!",
            profissao: {
            required: "Por favor, digite sua profissão!",
            minlength: "Precisa ter no mínimo três letras!"
            },
            email: "Por favor, um E-mail!",
            telefone: "Data inválida!",
            celular: "Número inválido!"
        },
        submitHandler: function(form) {
            form.submit();
        }
        });
    }

    // Mostra popup de erro
    function showErrorPopup(mensagem) {
        Swal.fire('Erro!', mensagem, 'error');
    }

    // Carrega contatos
    function loadContacts() {
        $.ajax({
            url: baseUrl,
            method: 'GET',
            dataType: 'json',
            success: function (data) {
                if (data.status === 200 && data.data) {
                    $('#tabela-contatos').find('tr:gt(0)').remove();
                    data.data.forEach(addTableRow);
                    }
                },
            error: function (error) {
                console.error('Erro na requisição AJAX:', error);
            }
        });
    }

    // Formata a data
    function formatDate(data) {
        return moment(data, 'YYYY-MM-DD').format('DD/MM/YYYY');
    }

    // Formata o celular
    function formatCell(celular) {
        return celular.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
    }

    // Formata o telefone
    function formatTel(telefone) {
        return telefone.replace(/(\d{2})(\d{4,5})(\d{4})/, '($1) $2-$3');
    }

    // Adiciona uma linha à tabela
    function addTableRow(contato) {
        var tabela = $('#tabela-contatos');
        var linha = $('<tr>').data('id', contato.id).append(
            $('<td>').text(contato.nome),
            $('<td>').text(formatDate(contato.nascimento)),
            $('<td>').text(contato.email),
            $('<td>').text(formatCell(contato.celular)),
            $('<td>').html('<div class="tabela-options">' +
            '<button class="editar-contato" data-id="' + contato.id + '"><img src="assets/editar.png" alt="editar"></button>' +
            '<button class="excluir-contato" data-id="' + contato.id + '"><img src="assets/excluir.png" alt="excluir"></button>' +
            '</div>')
            );
            tabela.append(linha);
    }

    // Cadastra um contato
    $('#form-cadastro').submit(function (event) {
        event.preventDefault();
    
        var formDataInsert = {
            nome: $('#nome').val(),
            nascimento: moment($('#data').val(), 'DD/MM/YYYY').format('YYYY-MM-DD'),
            email: $('#email').val(),
            profissao: $('#profissao').val(),
            telefone: $('#telefone').val().replace(/\D/g, ''),
            celular: $('#celular').val().replace(/\D/g, ''),
            celular_whatsapp: $('#whatsapp').prop('checked') ? 1 : 0,
            recebe_email: $('#emailnotif').prop('checked') ? 1 : 0,
            recebe_sms: $('#sms').prop('checked') ? 1 : 0
        };
    
        $.ajax({
            url: baseUrl, // 
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(formDataInsert),
            success: function (response) {
                console.log('Resposta da API:', response);
                
               
          
                loadContacts(); // Recarregue os contatos após a inserção

            },
            error: function (error) {
                showErrorPopup("Cadastro de contato não realizado. Por favor, tente novamente!");
                console.error('Erro ao cadastrar contato:', error);
            }
        });
    
        return false;
    });
    

    // Botão exclui um contato
    $(document).on('click', '#tabela-contatos button.excluir-contato', function () {
        var linha = $(this).closest('tr');
        var nomeContato = linha.find('td:first').text();

        Swal.fire({
        title: 'Confirmar exclusão',
        text: 'Deseja realmente excluir o contato ' + nomeContato + '?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#068ed0',
        
        cancelButtonColor: '#E41912',
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Não, cancelar'
        }).then((result) => {
        if (result.isConfirmed) {
            var contatoId = linha.data('id');

            $.ajax({
            url: baseUrl + contatoId,
            method: 'DELETE',
            success: function (response) {
            console.log('Contato excluído com sucesso:', response);
            linha.remove();
            Swal.fire('Sucesso!', 'O contato foi excluído.', 'success');
            },
            error: function (error) {
            console.error('Erro ao excluir contato:', error);
            showErrorPopup('Ocorreu um erro ao excluir o contato.');
            }
        });
        }
    });
    });

    // Botão edita um contato
    $(document).on('click', '#tabela-contatos button.editar-contato', function () {
        var contatoId = $(this).data('id');

        $.ajax({
        url: baseUrl + contatoId,
        method: 'GET',
        dataType: 'json',
        success: function (data) {
        if (data.status === 200 && data.data) {
            openUpdatePopup(contatoId);
            fillForm(data.data);
        }
        },
        error: function (error) {
        showErrorPopup("Erro ao solicitar edição para o servidor. Por favor, tente novamente mais tarde!");
        }
        });
    });

    // Preenche o formulário de edição com os dados atuais do contato
    function fillForm(contato) {
        applyMask();
        validationMethodsConfig();
        validationFormConfig();
        labelBlueOnFocus();

        $('#edit-nome').val(contato.nome);
        $('#edit-data').val(moment(contato.nascimento).format('DD/MM/YYYY'));
        $('#edit-email').val(contato.email);
        $('#edit-profissao').val(contato.profissao);
        $('#edit-telefone').val(formatTel(contato.telefone));
        $('#edit-celular').val(formatCell(contato.celular));
        $('#edit-whatsapp').prop('checked', contato.celular_whatsapp);
        $('#edit-emailnotif').prop('checked', contato.recebe_email);
        $('#edit-sms').prop('checked', contato.recebe_sms);
    }

    // Abre o popup de edição e atualiza o contato
function openUpdatePopup(contatoId) {
    Swal.fire({
        title: 'Editar Contato',
        width: 600,
        html: `
            <form id="form-atualizar" class="main-form">
                <div class="form-group">
                    <label for="edit-nome">Nome:</label>
                    <input type="text" id="edit-nome" name="nome" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="edit-data">Data de Nascimento:</label>
                    <input type="text" id="edit-data" name="data" class="form-control date-input" required>
                </div>
                <div class="form-group">
                    <label for="edit-email">Email:</label>
                    <input type="email" id="edit-email" name="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="edit-profissao">Profissão:</label>
                    <input type="text" id="edit-profissao" name="profissao" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="edit-telefone">Telefone:</label>
                    <input type="text" id="edit-telefone" name="telefone" class="form-control phone-input" required>
                </div>
                <div class="form-group">
                    <label for="edit-celular">Celular:</label>
                    <input type="text" id="edit-celular" name="celular" class="form-control phone-input" required>
                </div>
                <div class="form-group">
                    <label for="edit-whatsapp">Possui WhatsApp:</label>
                    <input type="checkbox" id="edit-whatsapp" name="whatsapp">
                </div>
                <div class="form-group">
                    <label for="edit-emailnotif">Recebe Notificações por Email:</label>
                    <input type="checkbox" id="edit-emailnotif" name="emailnotif">
                </div>
                <div class="form-group">
                    <label for="edit-sms">Recebe Notificações por SMS:</label>
                    <input type="checkbox" id="edit-sms" name="sms">
                </div>
            </form>
        `,
        showCancelButton: true,
        confirmButtonColor: '#068ed0',
        cancelButtonColor: '#E41912',
        confirmButtonText: 'Atualizar',
        cancelButtonText: 'Cancelar',
        preConfirm: function () {
            var formDataUpdate = {
                id: contatoId,
                nome: $('#edit-nome').val(),
                nascimento: moment($('#edit-data').val(), 'DD/MM/YYYY').format('YYYY-MM-DD'),
                email: $('#edit-email').val(),
                profissao: $('#edit-profissao').val(),
                telefone: $('#edit-telefone').val().replace(/\D/g, ''),
                celular: $('#edit-celular').val().replace(/\D/g, ''),
                celular_whatsapp: $('#edit-whatsapp').prop('checked') ? 1 : 0,
                recebe_email: $('#edit-emailnotif').prop('checked') ? 1 : 0,
                recebe_sms: $('#edit-sms').prop('checked') ? 1 : 0
            };

            return $.ajax({
                url: baseUrl,
                method: 'PUT',
                contentType: 'application/json',
                data: JSON.stringify(formDataUpdate)
            }).done(function (result) {
                if (result.status === 200) {
                    Swal.fire('Sucesso!', 'O contato foi atualizado com sucesso.', 'success').then(() => {
                        loadContacts();
                    });
                } else {
                    Swal.fire('Erro!', 'O contato não foi atualizado.', 'error');
                }
            }).fail(function (error) {
                Swal.fire('Erro!', 'Ocorreu um erro durante a atualização do contato.', 'error');
            });
        }
    });
}

    

    // Inicializa as máscaras e validações
    applyMask();
    validationMethodsConfig();
    validationFormConfig();
    labelBlueOnFocus();

    // Carrega contatos ao carregar a página
    loadContacts();
});
