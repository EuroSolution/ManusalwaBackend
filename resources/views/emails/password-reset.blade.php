<!doctype html>
<html>
<body>
<style>
    .contactPage {
        padding: 70px 180px;
    }
</style>
<div class="contactPage">
    <h2 class="text-center">Mannosalwa</h2>
    <div style="margin-bottom: 20px;">
        <p>Hello {{$name??''}}</p>
        <p>Click below link to reset your password</p>
        @if(isset($url))
            <p><a target="_blank" href="{{$url??''}}">{{$url}}</a></p>
        @endif
    </div>
</div>
</body>
</html>
