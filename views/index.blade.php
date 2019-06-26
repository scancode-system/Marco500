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
						{{ Form::open(['route' => 'marco500.xlsx', 'method' => 'GET']) }}
						{{ Form::hidden('filial', $filial) }}
						{{ Form::hidden('data_fechamento_depois', $data_fechamento_depois) }}
						{{ Form::hidden('data_fechamento_antes', $data_fechamento_antes) }}
						<button class="btn btn-primary pull-right" type="submit"><i class="fa fa-file-excel-o fa-lg"></i></button>
						{{ Form::close() }}
						<!--<a href="{{ route('marco500.xlsx', ['filial' => $filial, 'data_fechamento_antes' => $data_fechamento_antes, 'data_fechamento_depois' => $data_fechamento_depois]) }}" class="btn btn-primary pull-right">
							<i class="fa fa-file-excel-o fa-lg"></i>
						</a>-->
					</div>
				</header>
				<div class="main-box-body clearfix">
					{{ Form::open(['route' => 'marco500', 'method' => 'GET']) }}
					<div class="row">
						
						<div class="col-md-4">
							<div class="form-group">
								{{ Form::select('filial', $filiais, $filial, ['id' => 'filial', 'class' => 'form-control']) }}
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								{{ Form::label('data_fechamento_depois', 'Depois de:', ['class' => 'col-md-3 control-label']) }}
								<div class="col-md-9">
									{{ Form::date('data_fechamento_depois', $data_fechamento_depois, ['id' => 'data_fechamento_depois', 'class' => 'form-control']) }}
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								{{ Form::label('data_fechamento_antes', 'Anted de:', ['class' => 'col-md-3 control-label']) }}
								<div class="col-md-9">
									{{ Form::date('data_fechamento_antes', $data_fechamento_antes, ['id' => 'data_fechamento_antes', 'class' => 'form-control']) }}
								</div>
							</div>
						</div>
						
					</div>
					{{ Form::close() }}

					<div class="table-responsive">
						<table class="table">
							<thead>
								<tr>
									<th><span>Código</span></th>
									<th><span>Descrição</span></th>
									<th class="text-center"><span>Preço</span></th>
									<th class="text-center"><span>Quantiade</span></th>
									<th class="text-center"><span>Total (Bruto)</span></th>
								</tr>
							</thead>
							<tbody>
								@foreach ($produtos as $produto)
								<tr>
									<td>
										{{ $produto->codigo_2 }}
									</td>
									<td>
										{{ $produto['descricao'] }}
									</td>
									<td class="text-center">
										R${{ number_format($produto->preco, 2, ',', '.') }}                                       
									</td>
									<td class="text-center">
										{{ $produto->qtd }}                                       
									</td>
									<td class="text-center">
										R${{ number_format($produto->total, 2, ',', '.') }}                                       
									</td>
								</tr>
								@endforeach                                   
							</tbody>
							<tfoot style="font-size: 18px; font-weight: bold;">
								<tr>
									<td colspan="3">Totais</td>
									<td class="text-center">{{ $tot_qtd }}</td>
									<td class="text-center">R${{ number_format($tot_total, 2, ',', '.') }}</td>
								</tr>
							</tfoot>
						</table>
					</div>
					{{ $produtos->links() }}
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