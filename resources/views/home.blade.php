@extends('adminlte::page')
@section('content')

<div class="col-lg-3 col-6">
    <div class="small-box bg-info">
        <div class="inner">
            <h3><?php $total_teachers = DB::table('teachers')->count(); echo $total_teachers; ?></h3>
            <p>Teachers </p>
        </div>
        <div class="icon">
            <i class="fas fa-user-plus"></i></div>
            <a href="teacher" class="small-box-footer">
                "More Info"
                <i class="fas fa-arrow-circle-right">
                    
                </i>
            </a></div></div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3><?php $total_students = DB::table('students')->count(); echo $total_students; ?></h3>
                        <p>Student </p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-plus"></i></div>
                        <a href="student" class="small-box-footer">
                            "More Info"
                            <i class="fas fa-arrow-circle-right">
                            
                            </i>
                        </a></div></div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>20</h3>
                                    <p>Category </p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-chart-pie"></i></div>
                                    <a href="category" class="small-box-footer">
                                        "More Info"
                                        <i class="fas fa-arrow-circle-right">
                                        
                                        </i>
                                    </a></div></div>
@endsection