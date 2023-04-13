
<style>
table.dataTable th {
  font-size: 12px;
}
table.dataTable td {
  font-size: 12px;
}
</style>

<div class="table-responsive">
    <table class="display compact" style="width:100%" id="myTable">
        <thead>
            <tr>
            <th>Tipo</th>
            <th>Assunto</th>
            <th>Data</th>
            <th>Evento</th>
            <th>Email</th>
            <th>Status</th>
            <th>Mensagem Status</th>
            </tr>
        </thead>
        <tbody>


            @foreach ($notifications as $notification)
                <tr>
                    <td>
                        @if ($notification->type_send == "whatsapp")
                            <span class="badge badge-success"><i class="fa fa-whatsapp"></i></span>
                        @else
                            <span class="badge badge-info"><i class="fa fa-envelope"></i></span>
                        @endif
                    </td>
                    <td>{{ $notification->type_send == 'whatsapp' ? $notification->subject_whatsapp : $notification->subject }}</td>

                     @if($notification->type_send == 'whatsapp')
                     <td>{{ $notification->timestamp != null ? date('d/m/Y H:i:s',strtotime($notification->timestamp)) : '-' }}</td>
                     @else
                     <td>{{ $notification->date != null ? date('d/m/Y H:i:s',strtotime($notification->date)) : '-'}}</td>
                     @endif

                    <td>
                        @if($notification->event == 'delivered')
                            Entregue
                        @elseif($notification->event == 'opened')
                            Abriu
                        @elseif($notification->event == 'clicked')
                            Clicou
                        @else
                            {{$notification->event}}
                        @endif
                    </td>
                    <td>{{ $notification->recipient}}</td>
                    <td>
                        @if ($notification->type_send == 'whatsapp' && $notification->status == "Success")
                            <span class="badge badge-success">Enviado</span>
                        @elseif ($notification->type_send == 'email' && ($notification->event == "opened" || $notification->event == "clicked" || $notification->event == "delivered"))
                            <span class="badge badge-success">Enviado</span>
                        @else
                            <span class="badge badge-danger">Erro</span>
                        @endif
                    </td>
                    <td>{{ $notification->message_status}}</td>
                </tr>
            @endforeach

        </tbody>
    </table>
</div><!-- table-responsive -->

<script>
     $('#myTable').DataTable({
        order: [[2, 'desc']],
    });
</script>
