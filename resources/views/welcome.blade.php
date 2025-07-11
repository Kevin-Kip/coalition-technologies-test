<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet"/>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body class="">
<header class="">
</header>

<section class="col-4 m-auto">
    <div class="m-auto">
        <form class="row mt-5 p-3 border border-1 rounded" id="product_form" action="{{ route('products.store') }}">
            @csrf
            <div class="mb-3">
                <label for="product_name" class="form-label">Product Name</label>
                <input type="text" class="form-control" id="product_name" name="product_name" required>
            </div>
            <div class="mb-3">
                <label for="quantity" class="form-label">Quantity</label>
                <input type="number" class="form-control" id="quantity" name="quantity" required>
            </div>
            <div class="mb-3">
                <label for="price_per_item" class="form-label">Price per item</label>
                <input type="number" class="form-control" id="price_per_item" name="price_per_item" required>
            </div>
            <button type="submit" class="btn btn-primary form-control">Submit</button>
        </form>
    </div>
</section>

<section class="col-8 m-auto mt-4">
    <div><span class="p-1 fw-bolder">Saved items</span></div>
    <table class="table table-dark table-striped">
        <thead>
        <tr>
            <th scope="col">Product name</th>
            <th scope="col">Quantity in stock</th>
            <th scope="col">Price per item</th>
            <th scope="col">Datetime submitted</th>
            <th scope="col">Total value number</th>
        </tr>
        </thead>
        <tbody id="products_table_body">
        </tbody>
    </table>

    {{--  Error block  --}}
    <div id="error_block" class="alert alert-danger invisible" role="alert">
        Could not fetch products
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script>
    $(document).ready(function () {
        fetchSavedProducts()
    });

    $("#product_form").submit(function (e) {

        e.preventDefault(); // avoid to execute the actual submit of the form.

        let form = $(this);
        let actionUrl = form.attr('action');

        $.ajax({
            type: "POST",
            url: actionUrl,
            data: form.serialize(),
            success: function (data) {
                form.trigger('reset')
                fetchSavedProducts()
            }
        });
    });

    function fetchSavedProducts() {
        $("#products_table_body").empty()
        $("#error_block").addClass('invisible')
        $("table").removeClass('invisible')

        let productsUrl = "/products";
        $.ajax({
            type: "GET",
            url: productsUrl,
            success: function (products) {
                if (products) {
                    for (let product of products) {
                        let td = "<tr>";
                        td += "<td>" + product.product_name + "</td>"
                        td += "<td>" + product.quantity + "</td>"
                        td += "<td>" + product.price_per_item + "</td>"
                        td += "<td>" + product.total_value + "</td>"
                        td += "<td>" + product.datetime_submitted + "</td>"
                        td += "</tr>"
                        $("#products_table_body").append(td)
                    }
                }
            },
            error: function (err) {
                $("#error_block").removeClass('invisible')
                $("table").addClass('invisible')
            }
        })
    }
</script>
</body>
</html>
