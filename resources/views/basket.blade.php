@extends('layouts.app')

@section('content')


<div class="justify-content-center mt-5">
	<div class="row">
		@include('partials.alerts')
	</div>

@if($items->isEmpty())
    <p>
        @lang('payment.empty basket',['link' => route('products.index')])
    </p>
    @else
	<div class="row">
		<div class="col-md-7 card bg-light mr-3">
			<div class="card-body well">
				<table class="table ">
					<thead>
						<tr>
							<th>@lang('payment.product name')</th>
							<th>@lang('payment.product price')</th>
							<th>@lang('payment.quantity')</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($items as $item)
							<tr>
							<td>  {{$item->title}}</td>
							<td>{{number_format($item->price)}} @lang('payment.toman')</td>
                                <td>{{$item->quantity}}</td>
							<td>
							<form action="" method="post" class="form-inline">
									@csrf
									<select name="quantity" id="quantity" class="form-control input-sm mr-sm-2">

									<option  value=""></option>

									</select>
									<button type="submit" class="btn btn-primary btn-sm">@lang('payment.update')</button>
								</form>
							</td>
						</tr>
						@endforeach

					</tbody>
				</table>
			</div>
		</div>
		<div class="col-md-4">
			@include('summary')
		<a href="" class="btn mt-4  btn-primary btn-lg w-100 d-block">@lang('payment.confirm and continue')</a>
		</div>
	</div>
	@endif
</div>


@endsection

