@extends('layouts.admin')

@section('content')
    <div class="container admin-container vue-mount">
        <div class="panel-container dashboard-panel-container">
            <div class="panel-track">            
                <section class="content-panel dashboard-panel">
                    <div class="panel-content">            
                        <div class="panel-header">
                            <h1>Dashboard</h1>
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
