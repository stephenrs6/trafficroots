@extends('layouts.app')
@section('title', '- Links')
@section('content')
    @if(Session::has('success'))
        <div class="alert alert-success">
            <h2>{{ Session::get('success') }}</h2>
        </div>
    @endif
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="ibox">
                <div class="ibox-title">Create Link</div>

                <div class="ibox-content">
                <form name="media_form" id="media_form" class="form-horizontal" role="form" method="POST" action="{{ url('/links') }}">
                {{ csrf_field() }}
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="link_name" class="col-md-4 control-label">Link Name</label>

                            <div class="col-md-6">
                                <input id="link_name" type="text" class="form-control" name="link_name" value="{{ old('link_name') }}" required autofocus>

                                @if ($errors->has('link_name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('link_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>                

                        <div class="form-group{{ $errors->has('category') ? ' has-error' : '' }}">
                            <label for="category" class="col-md-4 control-label">Category</label>

                            <div class="col-md-6">
                                <select id="category" class="form-control" name="category" required>
                                <option value="">Choose</option>
                                @foreach($categories as $type)
                                    <option value="{{ $type->id }}">{{$type->category}}</option>

                                @endforeach
                                </select>
                          
                                @if ($errors->has('category'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('category') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('url') ? ' has-error' : '' }}">
                            <label for="url" class="col-md-4 control-label">Link</label>
                            <div class="col-md-6">

                                <input type="url" name="url" id="url" class="form-control" placeholder="Must be a valid URL, with http:// or https://" required>
                                @if ($errors->has('url'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('url') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                       <div class="form-group">
                            <label for="submit" class="col-md-4 control-label">Submit Link</label>
                            <div class="col-md-6">
                                <input type="submit" name="submit" id="submit" />
                            </div>
                        </div>
                </form>
            </div>
        </div>
    </div>
</div>
   <script type="text/javascript">
       jQuery(document).ready(function ($) {
               $('.nav-click').removeClass("active");
               $('#nav_buyer').addClass("active");
       });
   </script>
@endsection
