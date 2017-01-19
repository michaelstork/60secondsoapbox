@extends('layouts.admin')

@section('content')
    <div class="container admin-container vue-mount">
        <div class="panel-container dashboard-panel-container">
            <div class="panel-track">            
                <section class="content-panel dashboard-panel">
                    <div class="panel-content">            
                        <div class="panel-header">
                            <form method="POST" action="/create-user">
                                {{ csrf_field() }}
                                <i class="mdi mdi-account-plus"></i>
                                <input type="email" name="email" placeholder="Email" spellcheck="false" />
                                <button type="submit" class="rect"><span>Send Invite</span></button>
                            </form>
                            @if (Auth::user())
                                <a href="{{ url('/logout') }}">Logout</a> 
                            @endif
                        </div>
                        <soapbox-dashboard :data="users"></soapbox-dashboard>
                    </div>
                </section>
            </div>
        </div>
    </div>
    <script>
        window.soapboxUsers = <?php echo json_encode($users); ?>;
    </script>
    <script src="js/admin/dashboard.js"></script>
@endsection
