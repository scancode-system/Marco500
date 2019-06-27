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
		<tr>
			<td colspan="3" style="font-weight: bold;">Totais</td>
			<td class="text-center" style="font-weight: bold;">{{ $tot_qtd }}</td>
			<td class="text-center" style="font-weight: bold;">R${{ number_format($tot_total, 2, ',', '.') }}</td>
		</tr>
	</tbody>
</table>