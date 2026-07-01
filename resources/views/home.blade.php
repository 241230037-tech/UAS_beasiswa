<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Portal Beasiswa</title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

</head>

<body>

@include('partials.navbar')

<div class="container mt-4">

    <h3 class="mb-4">
        Beasiswa Terbaru
    </h3>

    <div class="row">

        @foreach($scholarships as $scholarship)

            <div class="col-md-3 mb-4">

                @include('components.scholarship-card',[
                    'title'=>$scholarship['title'],
                    'provider'=>$scholarship['provider'],
                    'location'=>$scholarship['location'],
                    'level'=>$scholarship['level'],
                    'deadline'=>$scholarship['deadline'],
                    'status'=>$scholarship['status'],
                    'image'=>$scholarship['image'],
                    'link'=>$scholarship['link']
                ])

            </div>

        @endforeach

    </div>

</div>

</body>

</html>