@extends("adminlte::page")
@section('content')
<div class="card" style="width: 18rem;">
<img src="{{ asset('storage/' . $meal->img) }}" alt="photo" class="img-fluid">

  <div class="card-body">
    <h5 class="card-title">Meal Price :</h5>
	<p>{{$meal->price}}</p>
    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
    <a href="#" class="btn btn-primary">Go somewhere</a>
  </div>
</div>
@endsection