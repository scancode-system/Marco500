@extends('layouts.main')

@section('content')

<div class="container">
	<div class="row">
		<div class="col-lg-6 col-sm-6 col-xs-12">
			<a href="{{ route('marco500.produtos') }}" class="btn btn-primary btn-lg w-full" style="padding: 25px;">Vendas por Produto</a>
		</div>  
		<div class="col-lg-6 col-sm-6 col-xs-12">
			<a href="{{ route('marco500.pedidos') }}" class="btn btn-primary btn-lg w-full" style="padding: 25px;">Vendas por Pedido</a>
		</div> 
	</div>
</div>

@endsection
