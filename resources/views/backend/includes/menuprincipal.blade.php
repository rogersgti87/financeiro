<ul>
  <li class="active"><a href="{{ route('backend.home') }}"><i class="fa fa-home"></i> Início</a></li>
  <li class="dropdown">
    <a href="javascript:;" class="dropdown-toggle"><i class="fa fa-users"></i> Clientes</a>
    <ul class="dropdown-menu" aria-labelledby="dropmenuBlog">
      <li><a href="{{ route('backend.customers') }}"><i class="fa fa-users"></i> Todos Clientes</a></li>
      <li><a href="{{ route('backend.services.customers') }}"><i class="fa fa-book"></i> Serviços Ativos</a></li>
    </ul>
  </li>

  <li><a href="{{ route('backend.services') }}"><i class="fa fa-cubes"></i> Produtos/Serviços</a></li>
  <li><a href="{{ route('backend.backups') }}"><i class="fa fa-cubes"></i> Backups</a></li>
  <li><a href="{{ route('backend.invoices') }}"><i class="fa fa-book"></i> Faturas</a></li>
  <li><a href="{{ route('backend.payables') }}"><i class="fa fa-book"></i> Contas a pagar</a></li>
  <li><a href="{{ route('backend.reports') }}"><i class="fa fa-flag"></i> Relatórios</a></li>

</ul>
