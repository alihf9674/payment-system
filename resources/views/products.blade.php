@extends('layouts.app')


@section('content')

<div class="row justify-content-center">
	<div class="col-md-6 mt-5">
		@include('partials.alerts')
	</div>
	<div class="card-body">
		<div class="row mb-5">

				<div class="col-md-4 mb-5 ">
				<div class="card" style="width: 18rem;">
				<img class="card-img-top" src="" alt="Card image cap">
					<div class="card-body">
					<h5 class="card-title"></h5>
						<p class="card-text">  </p>
					<a href="" class="btn btn-primary">@lang('payment.add to basket')</a>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>

@endsection()
