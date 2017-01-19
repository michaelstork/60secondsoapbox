@extends('layouts.admin')

@section('content')
<div class="container vue-mount">
    <div class="panel-container">
        <div class="panel-track">
            
            <section class="content-panel login-panel">
                <div class="panel-content">                    
                    <div class="panel-header">
                        <h1>Login</h1>
                    </div>
                    <soapbox-form :form="loginForm" class="login-form">
                        <div slot="form-top">
                            @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @elseif ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>
                        <nav slot="form-bottom">
                            {{ csrf_field() }}
                            <button class="round" type="submit">
                                <i class="mdi mdi-keyboard-backspace mdi-flip-horizontal"></i>
                                <span>Continue</span>
                            </button>
                        </nav>                        
                    </soapbox-form>
                </div>
            </section>
        </div>
    </div>
</div>
<script src="js/admin/login.js"></script>
@endsection
