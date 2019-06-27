@extends('layouts.main')

@section('content')

<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="main-box clearfix">
				<header class="main-box-header clearfix">
					<div class="pull-left">
						<h2>Produtos</h2>                    
					</div>
					<div class="pull-right">
						{{ Form::open(['route' => 'marco500.pedidos.xlsx', 'method' => 'GET']) }}
						{{ Form::hidden('filial', $filial) }}
						{{ Form::hidden('data_fechamento_depois', $data_fechamento_depois) }}
						{{ Form::hidden('data_fechamento_antes', $data_fechamento_antes) }}
						<button class="btn btn-primary pull-right" type="submit"><i class="fa fa-file-excel-o fa-lg"></i></button>
						{{ Form::close() }}
					</div>
				</header>
				<div class="main-box-body clearfix">
					{{ Form::open(['route' => 'marco500.pedidos', 'method' => 'GET']) }}
					<div class="row">
						
						<div class="col-md-4">
							<div class="form-group">
								{{ Form::select('filial', $filiais, $filial, ['id' => 'filial', 'class' => 'form-control']) }}
							</div>
						</div>
					</div>
					{{ Form::close() }}

					<div class="table-responsive">
						<table class="table">
							<thead>
								<tr>
									<th></th>
									@foreach($datas as $data)
									<th class="text-center">{{ $data }}</th>
									@endforeach
									<th class="text-center">TOTAL</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($totais as $filial_name => $filial)
								<tr>
									<td>
										{{ $filial_name }}
									</td>
									@foreach($datas as $data)
									<th class="text-center">R${{ number_format($filial->datas[$data], 2, ',', '.') }}</th>
									@endforeach
									<td class="text-center">
										R${{ number_format($filial->total, 2, ',', '.') }}                                       
									</td>
								</tr>
								@endforeach
								<tr>
									<td>TOTAL</td>
									@foreach($datas as $data)
									<th class="text-center">R${{ number_format($datas_totais->totais[$data], 2, ',', '.') }} </th>
									@endforeach
									<th class="text-center">R${{ number_format($datas_totais->total, 2, ',', '.') }} </th>
								</tr>                                   
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection

@section('scripts')
<script>
	$(document).ready(function() {
		$('#filial').change(function() {
			this.form.submit();
		});

		$('#data_fechamento_antes').change(function() {
			this.form.submit();
		});

		$('#data_fechamento_depois').change(function() {
			this.form.submit();
		});
	});

</script>
@endsection