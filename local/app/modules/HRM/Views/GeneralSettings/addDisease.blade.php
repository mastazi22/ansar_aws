@extends('template.master')
@section('title','Entry of Disease Information')
@section('breadcrumb')
    {!! Breadcrumbs::render('disease_information_entry') !!}
@endsection
@section('content')
    <div>

        <!-- Content Header (Page header) -->

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <!-- left column -->
                <div class="col-lg-8 col-centered">
                    <!-- general form elements -->

                    <!-- Input addon -->

                    <div class="box box-info">
                        <div class="box-body">

                            {!! Form::open(array('url' => 'HRM/disease_entry', 'class' => 'form-horizontal','method' => 'post')) !!}
                            <div class="box-body">
                                
                                <div class="form-group required">
                                    {!! Form::label('disease_name_eng', 'Disease Name:', $attributes = array('class' => 'col-sm-4 control-label')) !!}
                                    <div class="col-sm-8 @if($errors->has('disease_name_eng')) has-error @endif">
                                        {!! Form::text('disease_name_eng', $value = Request::old('disease_name_eng'), $attributes = array('class' => 'form-control', 'id' => 'disease_name_eng', 'placeholder' => 'Enter Disease Name')) !!}
                                        @if($errors->has('disease_name_eng'))
                                            <p class="text-danger">{{$errors->first('disease_name_eng')}}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group required">
                                    {!! Form::label('disease_name_bng', 'অসুখের নাম:', $attributes = array('class' => 'col-sm-4 control-label')) !!}
                                    <div class="col-sm-8 @if($errors->has('disease_name_bng')) has-error @endif">
                                        {!! Form::text('disease_name_bng', $value = Request::old('disease_name_bng'), $attributes = array('class' => 'form-control', 'id' => 'disease_name_bng', 'placeholder' => 'অসুখের নাম লিখুন বাংলায়')) !!}
                                        @if($errors->has('disease_name_bng'))
                                            <p class="text-danger">{{$errors->first('disease_name_bng')}}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <!-- /.box-body -->
                        </div>
                        <!-- /.box-body -->

                    </div>
                    <!-- /.box -->
                    <div>
                        <button type="submit" class="btn btn-info pull-right">Submit</button>
                    </div>
                    <!-- /.box-footer -->
                    {!! Form::close() !!}

                </div>
                <!--/.col (left) -->
                <!-- right column -->

            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div><!-- /.content-wrapper -->
@endsection