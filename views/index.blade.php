@extends('layouts.main')

@section('content')

<div class="container">
	<div class="row">
		<div class="col-lg-12">
			<div class="main-box clearfix">
				<header class="main-box-header clearfix">
					<h2>Produtos</h2>
				</header>
				<div class="main-box-body clearfix">
					{{ Form::open(['route' => 'marco500', 'method' => 'GET']) }}
					<div class="row">
						<div class="col-md-6">
							{{ Form::select('filial', $filiais, $filial, ['id' => 'filial', 'class' => 'form-control']) }}
						</div>
						<div class="col-md-6">
							{{ Form::date('data_fechamento', $data_fechamento, ['id' => 'data_fechamento', 'class' => 'form-control']) }}
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

		$('#data_fechamento').change(function() {
			this.form.submit();
		});
	});

</script>
@endsection