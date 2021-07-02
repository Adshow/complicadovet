<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>Upload de Arquivos</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
</head>

<body>
    <div class="container mt-5">

        <form  method="post" action="{{ route('upload.form') }}" novalidate enctype="multipart/form-data">

            <div class="input-group">
                <div class="custom-file">
                    <input required type="file" class="form-control" name="files[]" placeholder="address" multiple>
                    <label class="custom-file-label"></label>
                </div>
            </div>

            <div class="d-grid mt-3">
              <input type="submit" name="send" value="Enviar" class="btn btn-dark btn-block">
            </div>
        </form>
    </div>
</body>

</html>