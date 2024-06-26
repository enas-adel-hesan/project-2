@extends('adminlte::page')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js" defer></script>
@section('content')
 @if ($message = Session::get('success'))
        <div id="not" class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
		<script>
		setTimeout(function() {
    $('#not').fadeOut('fast');
}, 3000); //
		</script>
    @endif
	<div Style="background-color:white;border-top:solid 3px green;padding:2%;">
<a class="btn  btn-success" href="category/create" Style="margin-top:0%;margin-left:86%;margin-bottom:2%;">create category</a>

<table id="table" class="display my-custom-table">
<thead>
<tr>
<th>name</th>

<th>action</th>


</tr>
</thead>
</table>
</div>
@endsection

<script>

$(document).ready( function () {
    $('#table').DataTable({
	//	processing: true,
	//serverSide:true,
		
		ajax:{
			url: "{{'pagination-category'}}",
		},
	 columns: [
            {data:'name'},
		
			{data:'action'},
			
			 ],
		
	})	
	});
</script>
