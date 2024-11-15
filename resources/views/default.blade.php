<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>API Methods</title>

    <!-- Add Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body class="bg-dark">
    <div class="container mt-5">
        <h2 class="text-center mb-4 text-white">API Methods</h2>
        <div class="list-group">
            <div class="list-group-item">
                <h5 class="mb-1">POST /api/v1/login</h5>
                <p class="mb-1">Authenticates a user and returns a token.</p>
            </div>
            <div class="list-group-item">
                <h5 class="mb-1">GET api/v1/gifs/search</h5>
                <p class="mb-1">Search for gifs based on query parameters.</p>
            </div>
            <div class="list-group-item">
                <h5 class="mb-1">GET api/v1/gifs/{id}</h5>
                <p class="mb-1">Get a specific gif by its ID.</p>
            </div>
            <div class="list-group-item">
                <h5 class="mb-1">POST api/v1/gifs/favorite</h5>
                <p class="mb-1">Add a gif to the user's list of favorites.</p>
            </div>
        </div>
    </div>
</body>
</html>
