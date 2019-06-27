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
		@foreach ($filiais as $filial_name => $filial)
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
	</tbody>
</table>