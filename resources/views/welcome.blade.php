<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
</head>
<body>

<div class="card mt-4 col-6 offset-3">
    <div class="card-body">
        <div class="row justify-content-center">
            <h5>Masukkan Jumlah Belanja</h5>
            <input type="number" id="amount" class="form-control" />
        </div>
    </div>
    <div class="row my-2">
        <div class="col-2 offset-10">
            <button id="loading" class="btn btn-primary float-end" type="button" style="display: none;" disabled>
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                Processing...
            </button>
            <button id="submit" type="button" onclick="findCombinations()" class="btn btn-primary float-end">Cari</button>
        </div>
    </div>
    <div id="resultsContainer">
        <h5 class="row justify-content-center my-4">Kemungkinan Pembayaran</h5>
        <div id="results" class="row justify-content-center">

        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
<!--Axios-->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<!--SweetAlert-->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const http = axios.create({
        baseURL: '{{env("APP_URL")}}',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    http.interceptors.response.use(function (response) {
        //Continue
        return response;
    }, function (error) {
        if (error.response && error.response.status === 422) {
            const message = error.response.data.error;
            //Alert error
            Swal.fire({
                title: "Warning",
                text: message,
                icon: "warning"
            });
        }
    });

    function findCombinations() {
        const amount = $('#amount').val();
        if(!amount) {
            Swal.fire(
                'Warning',
                'Jumlah pembayaran wajib diisi.',
                'warning'
            );
            return;
        }
        //Show spinner
        $('#loading').show();
        $('#submit').hide();

        http.post('{{route("combinations")}}', {
            amount: amount
        }).then((response) => {
            const data = response.data.data;
            $('#results').empty();
            $.each(data, function (index, value) {
                const color = typeof value === 'string' ? 'bg-success' : 'bg-info';
                const element = $(`<div class="col-3 ${color} text-center m-2 py-2">${value}</div>`)
                $('#results').append(element);
            });
        }).finally(function () {
            //Hide spinner
            $('#loading').hide();
            $('#submit').show();
        });
    }
</script>
</body>
</html>
