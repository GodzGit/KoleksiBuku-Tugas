<!DOCTYPE html>
<html lang="en">
@include('layouts.header')

<body>
<div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth">
            <div class="row flex-grow">

                @yield('content')

            </div>
        </div>
    </div>
</div>

@include('layouts.javascript')
</body>
</html>
