@extends('layouts.app')

<!-- Main Content -->
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Active code recived at your E-mail</div>
                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/auth/activeCode') }}">
                        {!! csrf_field() !!}
                        <input type="hidden" name="name" value="{{$name}}">
                        <div class="form-group{{ $errors->has('activeCode') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Active Code</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="activeCode">

                                @if ($errors->has('activeCode'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('activeCode') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('message') ? ' has-error' : '' }}">
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-btn fa-envelope"></i>Send
                                </button>
                                <a class="btn btn-link" href="/social-laravel/mail/{{$name }}"style="text-decoration:none;">resend code</a>
                            </div>
                        </div>
                        <div class="col-md-6">
                        @if ($errors->has('message'))
                            <span class="help-block">
                                <strong>{{ $errors->first('message') }}</strong>
                            </span>
                        @endif
                      </div>
                      </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
