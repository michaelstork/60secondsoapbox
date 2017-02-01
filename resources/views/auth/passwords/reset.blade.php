@extends('layouts.admin')

@section('content')
<div class="container vue-mount">
    <div class="panel-container">
        <div class="panel-track">
            
            <section class="content-panel reset-panel">
                <div class="panel-content">                    
                    <div class="panel-header">
                        <h1>Reset Password</h1>
                    </div>
                    <soapbox-form :form="resetForm" class="auth-form" action="{{ url('/password/reset') }}">
                        <div slot="form-top">
                            @if (session('status'))
                                <span class="help-block">
                                    {{ session('status') }}
                                </span>
                            @endif
                            @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @elseif ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @elseif ($errors->has('password_confirmation'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                                </span>
                            @endif
                        </div>
                        <nav slot="form-bottom">
                            {{ csrf_field() }}
                            <input type="hidden" name="token" value="{{ $token }}">
                            <button class="round" type="submit">
                                <i class="mdi mdi-keyboard-backspace mdi-flip-horizontal"></i>
                                <span>Reset Password</span>
                            </button>
                        </nav>
                    </soapbox-form>
                </div>
            </section>
        </div>
    </div>
</div>
<script src="/js/admin/auth.js"></script>
@endsection
