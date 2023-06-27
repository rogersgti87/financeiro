
<style>
table.dataTable th {
  font-size: 12px;
}
table.dataTable td {
  font-size: 12px;
}

.popup {
  display: none;
  position: fixed;
  padding: 25px 10px 25px 10px;
  width: 350px;
  left: 50%;
  margin-left: -200px;
  height: 350px;
  top: 50%;
  margin-top: -200px;
  background: #FFF;
  border: 3px solid #333;
  z-index: 20;
  font-size:12px;
  color:#333;
}

.popup:after {
  position: fixed;
  content: "";
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
  background: rgba(0,0,0,0.5);
  z-index: -2;
}

.popup:before {
  position: absolute;
  content: "";
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
  background: #FFF;
  z-index: -1;
}
.overflow{
    height:100%;
    overflow-y: scroll;
    word-break: break-word;
}

/* width */
::-webkit-scrollbar {
  width: 10px;
}

/* Track */
::-webkit-scrollbar-track {
  background: #f1f1f1;
}

/* Handle */
::-webkit-scrollbar-thumb {
  background: #888;
}

/* Handle on hover */
::-webkit-scrollbar-thumb:hover {
  background: #555;
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
            <th>Mensagem</th>
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
                     <td>{{ $notification->type_send == 'whatsapp'? date('d/m/Y H:i:s',strtotime($notification->date)) :  date('d/m/Y H:i:s',strtotime($notification->timestamp)) }}</td>
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
                    <td>@if($notification->type_send == 'whatsapp')
                        <div class="popup" id="popup-{{$notification->id}}">
                            <div class="overflow">
                                @dd(json_decode($notification->message)->body)
                                @if(property_exists(json_decode($notification->message),'body'))
                                    {!! str_replace("\n","<br>",json_decode($notification->message)->body) !!}
                                @else
                                {!! str_replace("\n","<br>",json_decode($notification->message)) !!}
                                @endif
                            <br>
                            <br>
                            </div>
                            <button type="button" class="btn btn-danger" onclick="hide({{$notification->id}})">Fechar</button>
                          </div>
                        <button onclick="openMessage({{$notification->id}})" class="btn btn-xs btn-success" type="button">Mensagem</button>
                        @endif
                    </td>
                </tr>
            @endforeach

        </tbody>
    </table>
</div><!-- table-responsive -->

<script>
     $('#myTable').DataTable({
        order: [[2, 'desc']],
    });



    function openMessage(id){
        $("#popup-"+id).attr("style", "display:block");
    }

    var hide = function(id) {
        $("#popup-"+id).attr("style", "display:none");
    }





    </script>

