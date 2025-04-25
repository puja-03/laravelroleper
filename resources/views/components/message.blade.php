@if(Session::has('success'))
<div class="p-6 bg-green-100 border-l-4 border-green-500 text-green-700" role="alert">
    {{ Session::get('success') }}
</div>
@endif
@if(Session::has('error'))
<div class="p-6 bg-red  -100 border-l-4 border-red-500 text-red-700" role="alert">
    {{ Session::get('error') }}
</div>
@endif