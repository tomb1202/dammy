
<!DOCTYPE html>
<html lang="en-US">
<head>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title> {{$settings['title'] ?? ''}} </title>
    <meta name="description" content="{{$settings['description'] ?? ''}}" />
    <meta name="keywords" content="{{$settings['description'] ?? ''}}" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content=" {{$settings['title'] ?? ''}}" />
    <meta property="og:description" content=" {{$settings['title'] ?? ''}}" />
    <meta property="og:image" content="{{ sourceSetting($settings['logo']) }}" />
    <meta property="og:url" content="/" />
    <meta name="robots" content="noindex,nofollow" />
    <meta name="Googlebot-News" content="noindex,nofollow" />
    <link rel="canonical" href="{{url('/')}}" />
    
    <link rel="stylesheet" href="{{ url('/assets/css/style.css') }}" type="text/css">

    <script>
        var base_url = "{{url('/')}}";
    </script>
</head>

<body class="error404">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <header class="entry-header">
                    <div class="entry-featured-image">
                        <figure class="c-thumbnail"><img
                                src=" {{url('assets/images/404.png')}}"alt="404"></figure>
                    </div>
                    <div class="entry-title">
                        <h3 class="heading">
                            Oops! page not found. </h3>
                    </div>
                </header>
                <div class="entry-footer">
                    <a class="c-btn c-btn_style-3" href="{{url('/')}}">Về trang chủ</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
