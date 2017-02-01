@extends('layouts.admin')

@section('content')
<div class="container vue-mount">
    <div class="panel-container">
        <div class="panel-track">
            
            <section class="content-panel email-panel">
                <div class="panel-content">                    
                    <div class="panel-header">
                        <h1>Forgot Password</h1>
                    </div>
                    <soapbox-form :form="emailForm" class="auth-form" action="{{ url('/password/email') }}">
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
                            @endif
                        </div>
                        <nav slot="form-bottom">
                            {{ csrf_field() }}
                            <button class="round" type="submit">
                                <i class="mdi mdi-keyboard-backspace mdi-flip-horizontal"></i>
                                <span>Send</span>
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
