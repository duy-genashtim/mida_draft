<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="/css/bootstrap.min.css">
	<link rel="stylesheet" href="/css/animate.min.css">
	<link rel="stylesheet" href="/css/fontawesome-all.css">
	<link rel="stylesheet" href="/css/style.css">
	<link rel="stylesheet" type="text/css" href="/css/colors/switch.css">
	<!-- Color Alternatives -->
	<link href="/css/colors/color-2.css" rel="alternate stylesheet" type="text/css" title="color-2">
	<link href="/css/colors/color-3.css" rel="alternate stylesheet" type="text/css" title="color-3">
	<link href="/css/colors/color-4.css" rel="alternate stylesheet" type="text/css" title="color-4">
	<link href="/css/colors/color-5.css" rel="alternate stylesheet" type="text/css" title="color-5">

    <style type="text/css">
         .multisteps-form__progress-btn:after {
        display: none;
    }
    .wizard-part-title {
        padding: 70px;
        padding-top: 60px;
        padding-bottom: 10px;
    }
    .wizard-topper {
        top: -340px;
        display: none;
    }
    .wizard-content-item {
        padding-top: 25px;
        margin-top: -40px;
    }
    .multisteps-form__progress {
        padding-top: 40px;
        padding-bottom: 120px;
        display: flex;
        align-items: center;
        justify-content: space-around;
    }
    .wizard-topper .wizard-progress .progress .progress-bar {
        height: 19px;
    }
    .pb-10 {
        padding-bottom: 10px;
    }
    .n-checked label span {
        cursor: pointer;
    }
    .n-checked label {
        margin-bottom: .25rem;
    }
    .n-checked label span,
    .multisteps-form__progress-btn.js-active:before,
    .multisteps-form__progress-btn.js-active {
        color: #5756a2;
        border-color: #5756a2;
    }
    .radio-wrapper {
        display: flex;
        flex-wrap: wrap;
        flex-direction: column;
    }
    .has-float-label i {
        z-index: 999;
    }
    .has-select-info select {
        text-indent: 3rem;
    }

    @media screen and (max-width: 767px) {

        .wizard-topper {
            width: 70%;
            top: -150px !important;
        }
        
    }

    @media screen and (max-width: 460px) {
        .multisteps-form__progress {
            padding-bottom: 30px;
            padding-top: 20px;
        }
        .wizard-part-title h3 {
            font-size: 36px;
            text-align: center;
        }
    }
    </style>

    @vite(['resources/js/app.js'])
</head>
<body>
    <div id="app">
        @yield('content')
    </div>
    
    <!--<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>-->
    <script type="text/javascript" src="/js/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="/js/popper.min.js"></script>
    <script type="text/javascript" src="/js/bootstrap.min.js"></script>
</body>
</html>