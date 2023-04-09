<!DOCTYPE html>
<html>

<head>
    <title>{{$details['title']}}</title>
    <style>
        table {
            border: 1px solid #DDD;
        }

        table th,
        table td {
            padding: 3px 5px;
        }

        table th {
            border-bottom: 1px solid #DDD;
            border-right: 1px solid #DDD;
        }

        table td {
            border-right: 1px solid #DDD;
        }

        table th:last-child,
        table td:last-child {
            border-right: 0;
        }
    </style>
</head>

<body>
    <h1>{{$details['title']}}</h1>
    <p>Olá {{$details['customer']}}, tudo bem?</p>
    <p>Este é a confirmação e recibo do pagamento da <b>Fatura #{{$details['invoice_id']}}</b>.</p>

    <p><b>Serviço(s) Contratado(s):</b></p>
    <ul>
        <li>{{$details['description']}}</li>
    </ul>

    <p>----------------------------------------- </p>
    <p><b>Data de Pagamento:</b> {{$details['data_pagamento']}} <br>
        <b>Valor Pago:</b> R$ {{$details['price']}} <br>
        <b>Situação:</b> {{$details['status_payment']}}</p>

    <p><b>Observação:</b> Este e-mail servirá como recibo para este pagamento.</p>
    <p>Qualquer dúvida estamos à disposição. <br>
        Desejamos um ótimo dia!</p>

        <p>WhatsApp: <a href="https://api.whatsapp.com/send?phone=5522988280129" target="_Blank">(22) 9.8828-0129</a> || E-mail: <a href="mailto:financeiro@rogerti.com.br">financeiro@rogerti.com.br</a></p>
</body>

</html>
