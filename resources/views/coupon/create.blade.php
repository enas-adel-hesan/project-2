@extends("adminlte::page")
@section('content')
<div Style="background-color:white;border-top:solid 3px red;padding:1%;">
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
<div Style="background-color:white;border-bottom: 0.9px solid gray;color:gray;">           
		     <div Style="padding-top:1%;" >
         <h2> <i class="fas fa-plus"> Add Coupon</i> </h2> 
        </div>
		   </div>
        </div>

    </div>
</div>
   
@if ($errors->any())
    <div class="alert alert-danger">
        <strong>Whoops!</strong> There were some problems with your input.<br><br>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
   
<form action="{{ route('coupon.store') }}" method="POST">
    @csrf
    <div class="form-group">
        <label for="name">Coupon Name</label>
        <input type="text" class="form-control" id="name" name="name" required>
    </div>
    <div class="form-group">
        <label for="course_id">Course</label>
        <select class="form-control" id="course_id" name="course_id" required>
            @foreach($courseIds as $course)
                <option value="{{ $course->id }}">{{ $course->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="value">Value</label>
        <input type="text" class="form-control" id="value" name="value" required>
    </div>
    <div class="form-group">
        <label for="count">Count</label>
        <input type="number" class="form-control" id="count" name="count" required>
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>

		 <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('coupon.index') }}"> Back</a>
        </div>
    </div>
   
</form>
</div>
@endsection  