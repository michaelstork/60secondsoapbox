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
                                @if ($createUserMessage)
                                    <span>{{ $createUserMessage }}</span>
                                @endif
                            </form>
                            @if (Auth::user())
                                <a href="{{ url('/logout') }}">Logout</a> 
                            @endif
                        </div>
                        <soapbox-users-table :user-data="users"></soapbox-users-table>
                    </div>
                </section>
                <section class="content-panel dashboard-panel">
                    <div class="panel-content">
                        <div class="panel-header">
                            <h2>Content</h2>
                        </div>
                        <form method="POST" action="/save-content" class="content-form">
                            {{ csrf_field() }}
                            @foreach ($content as $item)
                                <label>{{ $item->title }}</label>
                                @if ($item->type === 'text')
                                    <input type="text" name="{{ $item->name }}" value="{{ $item->content }}" />
                                @elseif ($item->type === 'textarea')
                                    <textarea name="{{ $item->name }}">{!! $item->content !!}</textarea>
                                @endif
                            @endforeach
                            <button type="submit" class="rect">
                                <span>Save</span>
                            </button>
                        </form>
                    </div>
                </section>
            </div>
        </div>
    </div>
    <script>
        window.soapboxUsers = <?php echo json_encode($users); ?>;
        window.soapboxContent = <?php echo json_encode($content); ?>;
    </script>
    <script src="js/admin/dashboard.js"></script>
@endsection
