<head>
    <link rel="stylesheet" href="/Presentation/Assets/css/profile.css">
</head>

<div class="page-container">
    <h2>Modifier mon profil</h2>
    <hr />
    <div class="profile-container">
        <div class="list">
            <a href="/profile/username">Modifier mon nom d'utilisateur</a>
            <a href="/profile/email">Modifier mon email</a>
            <a href="/profile/password">Modifier mon password</a>
            <div class="toggle-container">
                <div id="cb-label">
                    Toggle Button (current state: <span id="toggle-state">off</span>)
                </div>
                <input id="cb-toggle" type="checkbox" class="hide-me" aria-labelledby="cb-label">
                <label for="cb-toggle" class="toggle"></label>
            </div>
        </div>
    </div>
</div>

<script>

    var cb = document.querySelector('#cb-toggle');
    cb.addEventListener('click', function () {
        var stateSpan = document.querySelector('#toggle-state');
        var currentState;
        if (cb.checked) {
            currentState = 'on';
        } else {
            currentState = 'off';
        }
        stateSpan.innerHTML = currentState;
    }, false);

</script>