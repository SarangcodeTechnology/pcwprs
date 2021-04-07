<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <table class="table table-bordered">
            <tr>
                <th>User</th>
                <th>Roles</th>
            </tr>
            @foreach ($users as $item)
            <tr>
                <td>{{ $item->name }}</td>
                <td>
                    @foreach ($item->roles as $item2)
                        @foreach ($item2->permissions as $item3)
                            {{ $item3->name }},
                        @endforeach
                    @endforeach
                </td>
            </tr>
            @endforeach
        </table>
    </div>
</body>
</html>
