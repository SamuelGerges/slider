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
        .form-style-6 {
            font: 95% Arial, Helvetica, sans-serif;
            max-width: 400px;
            margin: 10px auto;
            padding: 16px;
            background: #F7F7F7;
        }

        .form-style-6 h1 {
            background: #43D1AF;
            padding: 20px 0;
            font-size: 140%;
            font-weight: 300;
            text-align: center;
            color: #fff;
            margin: -16px -16px 16px -16px;
        }

        .form-style-6 input[type="text"],
        .form-style-6 input[type="file"],
        .form-style-6 input[type="date"],
        .form-style-6 input[type="datetime"],
        .form-style-6 input[type="email"],
        .form-style-6 input[type="number"],
        .form-style-6 input[type="search"],
        .form-style-6 input[type="time"],
        .form-style-6 input[type="url"],
        .form-style-6 textarea,
        .form-style-6 select {
            -webkit-transition: all 0.30s ease-in-out;
            -moz-transition: all 0.30s ease-in-out;
            -ms-transition: all 0.30s ease-in-out;
            -o-transition: all 0.30s ease-in-out;
            outline: none;
            box-sizing: border-box;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            width: 100%;
            background: #fff;
            margin-bottom: 4%;
            border: 1px solid #ccc;
            padding: 3%;
            color: #555;
            font: 95% Arial, Helvetica, sans-serif;
        }

        .form-style-6 input[type="text"]:focus,
        .form-style-6 input[type="file"]:focus,
        .form-style-6 input[type="date"]:focus,
        .form-style-6 input[type="datetime"]:focus,
        .form-style-6 input[type="email"]:focus,
        .form-style-6 input[type="number"]:focus,
        .form-style-6 input[type="search"]:focus,
        .form-style-6 input[type="time"]:focus,
        .form-style-6 input[type="url"]:focus,
        .form-style-6 textarea:focus,
        .form-style-6 select:focus {
            box-shadow: 0 0 5px #43D1AF;
            padding: 3%;
            border: 1px solid #43D1AF;
        }

        .form-style-6 input[type="submit"],
        .form-style-6 input[type="button"] {
            box-sizing: border-box;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            float: inside;
            width: 30%;
            padding: 3%;
            background: #43D1AF;
            border-bottom: 3px solid #30C29E;
            border-top-style: none;
            margin: 3px;
            border-right-style: none;
            border-left-style: none;
            color: #fff;
        }

        .form-style-6 input[type="submit"]:hover,
        .form-style-6 input[type="button"]:hover {
            background: #2EBC99;
        }

        .form-style-6 .box, .showSlider {
            border: 2px solid #bac7c4
        }
    </style>
</head>
<body>
<div class="form-style-6">
    <h1>Update </h1>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="post" action="{{ route('places.update',$place['id']) }}" enctype="multipart/form-data">
        @csrf

        <input type="hidden" name="id" value="{{ $place['id'] }}"/>
        <input type="text" name="name" placeholder="Your Name" value="{{ $place['name'] }}"/>
        <input type="text" name="description" placeholder="Your description" value="{{ $place['description'] }}"/>

        <div id="slider" style="border: 3px solid #30C29E">
            @isset($place['slider'])
                @foreach($place['slider'] as $key=>$img)
                    <div class="showSlider" id="showSlider">
                        <input type="hidden" name="check_exist_{{$key}}" value="yes">
                        <input type="text" name="old_title_{{$key}}" value="{{$img['title']}}" placeholder="title">
                        <input type="text" name="old_alt_{{$key}}" value="{{$img['alt']}}" placeholder="alt">
                        <img src="{{$img['url']}}" style="width: 100px;height: 30px">
                        <input type="file" name="slider_{{$key}}">
                        <input type="button" class="removeFile" value="Remove File">
                    </div>
                @endforeach
            @endisset

           <div class="box">
                <input type="text" name="new_title[]" placeholder="title" >
                <input type="text" name="new_alt[]" placeholder="alt" >
                <input type="file" name="new_slider[]" >
               <input type="button" class="remove" value="Remove"/>
            </div>
        </div>
        <input type="button" id="addFile" value="Add file"/>
        <input type="submit" value="Update"/> <br> <br>
    </form>
</div>


<script>
    $(document).ready(function () {
        $(".box").hide();
        $("#addFile").click(function (){
            var clonedHtml = $(".box").first().clone().show();
            $("input",clonedHtml).val("");
            $("#slider").append(clonedHtml);
            $(".remove").click(function (){
                $(this).parents(".box").remove();
            });
        });
        $(".removeFile").click(function (){
            $(this).parents(".showSlider").remove();
        });


    });
</script>

</body>
</html>
