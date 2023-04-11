<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style type="text/css">
        .styled-table {
            border-collapse: collapse;
            margin: 25px 0;
            font-size: 0.9em;
            font-family: sans-serif;
            min-width: 400px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
        }

        .styled-table thead tr {
            background-color: #009879;
            color: #ffffff;
            text-align: left;
        }

        .styled-table th,
        .styled-table td {
            padding: 12px 15px;
        }

        .styled-table tbody tr {
            border-bottom: 1px solid #dddddd;
        }

        .styled-table tbody tr:nth-of-type(even) {
            background-color: #f3f3f3;
        }

        .styled-table tbody tr:last-of-type {
            border-bottom: 2px solid #009879;
        }

        .styled-table tbody tr.active-row {
            font-weight: bold;
            color: #009879;
        }


    </style>

</head>
<body>
@if(Session::has('success'))
    <div class="alert alert-success">
        {{ Session::get('success') }}
        @php
            Session::forget('success');
        @endphp
    </div>
@endif

@if(Session::has('error'))
    <div class="alert alert-danger">
        {{ Session::get('error') }}
        @php
            Session::forget('error');
        @endphp
    </div>
@endif
<br>
<a class="btn btn-outline-primary btn-min-width box-shadow-3 mr-1 mb-1"
   href="{{ route('places.create') }}">Add New Place</a>
<table class="styled-table">
    <tr style="text-align: center">
        <th>#</th>
        <th>Name</th>
        <th>Description</th>
        <th>Slider</th>
        <th>Actions</th>
    </tr>

    @isset($places)
        @foreach($places as $place)
            <tr style="text-align: center">
                <td>{{$place['id']}}</td>
                <td>{{$place['name']}}</td>
                <td>{{$place['description']}}</td>
                <td>
                    @isset($place['slider'])
                        @foreach($place['slider'] as $img)

                            <img src="{{$img['url']}}" alt="{{$img['alt']}}" title="{{$img['title']}}"
                                 style="width: 100px;height: 30px">
                        @endforeach
                    @endisset
                </td>
                <td>
                    <a class="btn btn-outline-primary btn-min-width box-shadow-3 mr-1 mb-1"
                       href="{{ route('places.edit',$place['id']) }}">Edit</a>
                    <a class="btn btn-outline-danger btn-min-width box-shadow-3 mr-1 mb-1"
                       href="{{ route('places.delete',$place['id']) }}">Delete</a>
                </td>

            </tr>
        @endforeach
    @endisset
</table>
<script>

</script>
</body>
</html>
