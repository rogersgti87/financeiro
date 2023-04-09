$("#checkAll").click(function () {
    $('input:checkbox').not(this).prop('checked', this.checked);
});

// Begin :: Tooltip
$(function () {
    $('[data-toggle="tooltip"]').tooltip()
});
// End :: Tooltip

// Begin :: Format Phone
function upMaskPhone(event) {
    var $element = $('#' + this.id);
    $(this).off('blur');
    $element.unmask();
    if (this.value.replace(/\D/g, '').length > 10) {
        $element.mask("(00) 00000-0000");
    } else {
        $element.mask("(00) 0000-00009");
    }
    $(this).on('blur', upMaskPhone);
}

function formatedPhone() {
    $('.formatPhone').each(function (i, el) {
        $('.formatPhone').mask("(00) 00000-0000");
    });
    $('.formatPhone').on('blur', upMaskPhone);
}
// End :: Format Phone


// Begin :: Format Price
function upMaskPrice(event) {
    var $element = $('#' + this.id);
    $(this).off('blur');
    $element.unmask();
    $element.mask('#####.##', { reverse: true });

    $(this).on('blur', upMaskPrice);
}


function formatedPrice() {
    $('.formatedPrice').each(function (i, el) {
        $('.formatedPrice').mask('#####.##', { reverse: true });
    });
    $('.formatedPrice').on('blur', upMaskPrice);
}
// End :: Format Price


// Begin :: Format Date
function upMaskDate(event) {
    var $element = $('#' + this.id);
    $(this).off('blur');
    $element.unmask();
    $element.datepicker({
        format: "dd/mm/yyyy",
        inline: true,
        language: "pt-BR",
        todayHighlight: true
    });

    $(this).on('blur', upMaskDate);
}


function formatedDate() {
    $('.formatedDate').each(function (i, el) {
        $('.formatedDate').datepicker({
            format: "dd/mm/yyyy",
            inline: true,
            language: "pt-BR",
            todayHighlight: true
        });
    });
    $('.formatedDate').on('blur', upMaskDate);
}
//End :: Format Date


function querySlug() {
    $('input[name="name"], input[name="title"]').stringToSlug({
        setEvents: 'keyup keydown blur',
        getPut: 'input[name="slug"]',
        space: '-',
        replace: '/\s?\([^\)]*\)/gi',
        AND: 'e'
    });
} // end :: querySlug


/* BOOTSTRAP WYSIHTML5 */
function wysihtml5() {
    $('.textarea').wysihtml5();
}

/* SUMMERNOTE*/
function summernote() {
    $(document).ready(function () {
        $('.summernote').summernote({
            lang: 'pt-BR'
        });
        // $('button[data-event="showImageDialog"], button[data-event="showVideoDialog"], button[data-event="codeview"], .note-toolbar .note-table').hide();
    });
}

function consultaCEP() {
    $('#cep').blur(function () {
        var cep = $.trim($('#cep').val().replace('-', ''));
        $.getJSON("https://viacep.com.br/ws/" + cep + "/json/?callback=?", function (dados) {
            if (!("erro" in dados)) {

                $('#address').val(dados.logradouro);

                $('#number').focus();

                // if (dados.bairro) {
                //     $('#distric').val(dados.bairro);
                // } else {
                //     $('#distric').val(unescape(dados.localidade));
                // }

                $('#city').val(unescape(dados.localidade));

                switch (dados.uf) {
                    case 'AC':
                        ufname = 'Acre';
                        break;
                    case 'AL':
                        ufname = 'Alagoas';
                        break;
                    case 'AP':
                        ufname = 'Amapá';
                        break;
                    case 'AM':
                        ufname = 'Amazonas';
                        break;
                    case 'BA':
                        ufname = 'Bahia';
                        break;
                    case 'CE':
                        ufname = 'Ceará';
                        break;
                    case 'DF':
                        ufname = 'Distrito Federal';
                        break;
                    case 'ES':
                        ufname = 'Espírito Santo';
                        break;
                    case 'GO':
                        ufname = 'Goiás';
                        break;
                    case 'MA':
                        ufname = 'Maranhão';
                        break;
                    case 'MT':
                        ufname = 'Mato Grosso';
                        break;
                    case 'MS':
                        ufname = 'Mato Grosso do Sul';
                        break;
                    case 'MG':
                        ufname = 'Minas Gerais';
                        break;
                    case 'PA':
                        ufname = 'Pará';
                        break;
                    case 'PB':
                        ufname = 'Paraíba';
                        break;
                    case 'PR':
                        ufname = 'Paraná';
                        break;
                    case 'PE':
                        ufname = 'Pernambuco';
                        break;
                    case 'PI':
                        ufname = 'Piauí';
                        break;
                    case 'RJ':
                        ufname = 'Rio de Janeiro';
                        break;
                    case 'RN':
                        ufname = 'Rio Grande do Norte';
                        break;
                    case 'RS':
                        ufname = 'Rio Grande do Sul';
                        break;
                    case 'RO':
                        ufname = 'Rondônia';
                        break;
                    case 'RR':
                        ufname = 'Roraima';
                        break;
                    case 'SC':
                        ufname = 'Santa Catarina';
                        break;
                    case 'SP':
                        ufname = 'São Paulo';
                        break;
                    case 'SE':
                        ufname = 'Sergipe';
                        break;
                    case 'TO':
                        ufname = 'Tocantins';
                        break;
                }

                $('#state').val(unescape(ufname));
                // $('select[name="country_id"]').find('option[value="30"]').attr('selected', true);
            }
        });
    });
}

// Mascara de CPF e CNPJ
function formatedDocument() {
    var options = {
        onKeyPress: function(cpfcnpj, e, field, options) {
            var masks = ['000.000.000-009', '00.000.000/0000-00'];
            var mask = (cpfcnpj.length > 14) ? masks[1] : masks[0];
            $('#cpf_cnpj').mask(mask, options);
        }
    };

    $('#cpf_cnpj').mask('000.000.000-009', options);

}
