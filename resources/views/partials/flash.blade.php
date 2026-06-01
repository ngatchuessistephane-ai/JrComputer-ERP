@if(session()->has('message'))
    <div class="flash-msg success"><i class="bi bi-check-circle-fill"></i> {{ session('message') }}</div>
@endif
@if(session()->has('error'))
    <div class="flash-msg danger"><i class="bi bi-exclamation-circle-fill"></i> {{ session('error') }}</div>
@endif
@if($errors->any())
    <div class="flash-msg danger">
        <i class="bi bi-exclamation-circle-fill"></i>
        <div><ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
    </div>
@endif