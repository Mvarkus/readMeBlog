@if ($errors->any())
    <div class="error-section">
        <span>- Attempt errors -</span>
        <ul>
            @foreach ($errors->all() as $error)
                <li>* {{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif