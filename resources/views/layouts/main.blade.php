<!DOCTYPE html>
<html lang="en">
<head>
    @include('layouts.head')

</head>
<body>
    @include('layouts.sidebar')
    <div class="blackdrop"></div>
    <div class="main-container">
        @include('layouts.navbar')
        <div class="body-container container">
            <div class="row">
                @yield('container')
            </div>
        </div>
    </div>
    @include('layouts.foot')
</body>
</html>
