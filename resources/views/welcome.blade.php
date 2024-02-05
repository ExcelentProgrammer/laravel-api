<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    @vite("resources/js/app.js")
    <link rel="stylesheet" href="{{asset("output.css")}}">
</head>
<body>
<div class="w-[100vw] h-[100vh] flex justify-center items-center">
    <div>
        <h1 class="text-red-500 text-[40px] font-bold text-center">{{ __("Jscorp/laravel") }}</h1>
        <h1 class="text-black mb-3 text-[20px] font-bold text-center">{{ __("Assalomu aleykum") }}</h1>
        <a target="_blank" href="https://github.com/ExcelentProgrammer" class="text-blue-500 font-bold">Github</a>
        <span class="border border-1 border-black m-2"></span>
        <a target="_blank" href="https://t.me/Azamov_Samandar" class="text-blue-500 font-bold">Telegram</a>
        <span class="border border-1 border-black m-2"></span>
        <a target="_blank" href="https://instagram.com/azamov.samandar.2005"
           class="text-blue-500 font-bold">Instagram</a>
        <span class="border border-1 border-black m-2"></span>
        <a target="_blank" href="https://github.com/ExcelentProgrammer/laravel-api.git" class="text-blue-500 font-bold">Source
            code</a>
    </div>
</div>
</body>
</html>
