@if ($errors->has($input))
<span class="help-block">
        <strong style="color: red;">{{ $errors->first($input) }}</strong>
</span>
@endif