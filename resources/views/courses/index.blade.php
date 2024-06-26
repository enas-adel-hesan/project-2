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
	<div Style="background-color:white;border-top:solid 3px rgb(255, 132, 0);padding:2%;">


<table id="table" class="display">
<thead>
<tr>
<th>name</th>
<th>category</th>
<th>teacher</th>
<th>price</th>
<th>discription</th>



</tr>
</thead>

</table>
</div>
@endsection

<script>

$(document).ready( function () {
	//$.fn.dataTable.ext.errMode = 'throw';
    $('#table').DataTable({
		processing: true,
		//serverSide:true,
		
		ajax:'pagination-course',
			//url: "{{'pagination-teacher'}}",
			
                
		
	 columns: [
            {data:'name'},
		   {data:'category'},
			{data:'teacher'},
            {data:'price'},
			{data:'discription'},
		
			 ],
		
	})	
	});
</script>
