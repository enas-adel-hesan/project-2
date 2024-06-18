@extends("adminlte::page")
@section('content')
@if ($errors->any())
    <div>
        <strong>Whoops!</strong> There were some problems with your input.<br><br>
        
            @foreach ($errors->all() as $error)
               {{ $error }}
            @endforeach
       
    </div>
@endif
<div Style="background-color:white;border-top:solid 3px red;padding:1%;">
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
<div Style="background-color:white;border-bottom: 0.9px solid gray;color:gray;">           
		     <div Style="padding-top:1%;" >
         <h2> <i class="fas fa-plus"> Edit User</i> </h2> 
        </div>
		   </div>
        </div>

    </div>
</div>
<form action="{{route('user.update',$user->id)}}" method="POST" enctype="multipart/form-data">

@csrf
{{method_field('PATCH')}}
   <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Name:</strong>
                <input type="text" name="username" class="form-control" placeholder="Name">
            </div>
        </div>

   <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Password:</strong>
                <input type="password" name="password" class="form-control" placeholder="Password">
            </div>
        </div>

   <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Role:</strong>
                <select name="role_id">
@foreach ($roleIds as $c)
<option value="{{$c->id}}">{{$c->name}}</option>
@endforeach
</select>  </div>
        </div>


<input type="submit" value="save"/>
</form>
</div>
@endsection