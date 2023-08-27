<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">
  <title>Waiting</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
    @charset "utf-8";

    body {
        font-family: 'arial', sans-serif;
        font-size: 16px;
        font-weight: 400;
        line-height: 1.3;
        overflow-x: hidden;
    }

    a {
        color: #fff;
        text-decoration: none;
        transition: all 250ms ease-in-out;
    }

    a:hover {
        text-decoration: none;
        color: #fff;
    }

    a:focus {
        outline: none;
        text-decoration: none;
    }

    h1, h2, h3, h4, h5, h6 {
        margin: 0 0 15px;
        font-weight: 900;
        text-transform: uppercase;
    }

    ul {
        padding: 0;
        margin: 0;
        list-style: none;
    }

    img {
        max-width: 100%;
    }

    input[type='text'],
    input[type='number'],
    input[type='password'],
    button,
    textarea,
    select,
    option,
    img,
    .item {
        outline: none;
        box-shadow: none !important;
    }

    .btn {
        background: #D1504B;
        border: 2px solid #D1504B;
        padding: 5px 14px;
        font-size: 16px;
        font-weight: 500;
        color: #fff;
        letter-spacing: 1px;
    }

    .btn:hover,
    .btn:active,
    .btn:focus-visible,
    :not(.btn-check)+.btn:active {
        border: 2px solid #D1504B;
        color: #D1504B;
        background: none;
    }

    .mobile {
        display: none !important;
    }

    ::-webkit-scrollbar {
        width: 8px;
    }

    ::-webkit-scrollbar-track {
        box-shadow: inset 0 0 6px rgba(0, 0, 0, 0);
    }

    ::-webkit-scrollbar-thumb {
        background-color: darkgrey;
        outline: none;
    }

    ::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    /*-------------------------Content Section Css-----------------------------*/
    .textbx {
        margin-top: 30%;
        height: 50vh;
        padding: 50px 0;
        /* display: flex; */
        align-items: center;
    }

    .spinner-border {
        border-color: #D1504B;
        border-right-color: transparent;
    }
    </style>
</head>

<body>

    @php 
        $order_id = $orderId ?? 0;
        $closed_route = route('close_window_app', $order_id);
    @endphp

    <!-- Content Start -->
    <section class="contentnx">
    <div class="container">
        <div class="row justify-content-center align-items-center">

        <div class="col-md-5">
            <div class="textbx text-center">
            <div class="textbx2">
                <div class="spinner-border mb-4" style="width: 5rem; height: 5rem;" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <h3>Loading...</h3>
                <p class="mb-4 mt-4">
                    @if( $order_id != 0 ) Your payment is in process.  @endif
                    <br/>Please wait while we redirect you to the app
                </p>
                
                <a href="{{ $closed_route}}" class="btn">Back to App</a>
                {{-- <a href="#/" class="btn ms-4">button 2</a> --}}
            </div>
            </div>
        </div>

        </div>
    </div>
    </section>
    <!-- Content End -->

</body>

</html>